<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
//use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property object $full_address
 * @property string $phone
 * @property string|null $password
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property Carbon $phone_verified_at
 * @property string $status
 * @property-read Role[] $roles
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // statuses
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'phone_verify_token_expire',
        'phone_verify_token',
        'phone_verified_at',
        'status',
        'full_address',
    ];

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'password',
        'remember_token',
        'phone_verify_token',
        'phone_verify_token_expire',
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'email_verified_at'         => 'datetime',
        'phone_verify_token_expire' => 'datetime',
        'full_address'              => 'object',
        'phone'                     => 'integer',
    ];
    /**
     * @var array $with
     */
    protected $with = ['roles'];

    /**
     * @param $first_name
     */
    public function setFirstNameAttribute($first_name): void
    {
        $this->attributes['first_name'] = Str::ucfirst($first_name);
    }

    /**
     * @param $last_name
     */
    public function setLastNameAttribute($last_name): void
    {
        $this->attributes['last_name'] = Str::ucfirst($last_name);
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Register by phone
     * @param string $phone
     * @param string $phone_verify_token
     * @param Carbon $ttl
     * @return mixed
     */
    public static function registerByPhone(string $phone, string $phone_verify_token, Carbon $ttl)
    {
        return static::updateOrCreate(
            [
                'phone' => $phone,
            ],
            [
                'status'                    => self::STATUS_PENDING,
                'phone_verify_token'        => $phone_verify_token,
                'password'                  => null,
                'phone_verified_at'         => null,
                'phone_verify_token_expire' => $ttl,
            ]
        );
    }

    /**
     * @param string $token
     * @param Carbon $now
     * @return JsonResponse|void
     * @throws \Throwable
     */
    public function verifyPhone(string $token, Carbon $now)
    {
        if ($token !== $this->phone_verify_token) {
            throw ValidationException::withMessages([
                'phone_verify_token' => trans('auth.incorrectVerifyToken'),
            ]);
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw ValidationException::withMessages([
                'phone_verify_token' => trans('auth.tokenIsExpired'),
            ]);
        }
        $this->password = Hash::make($this->phone_verify_token);
        $this->phone_verify_token = $this->phone_verify_token_expire = null;

        $this->status = self::STATUS_ACTIVE;
        $this->phone_verified_at = $now;

        $this->saveOrFail();
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return $this
     */
    public function attachRole(): self
    {
        if ($this->wasRecentlyCreated) { // if created
            $role = Role::where('name', Role::ROLE_USER)->get();
            $this->roles()->attach($role);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        /** @var $roles Collection */
        $roles = $this->roles;
        return $roles->contains('name', Role::ROLE_ADMIN);
    }
}
