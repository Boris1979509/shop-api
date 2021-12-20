<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;
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
 * @property string $password
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property Carbon $phone_verified_at
 * @property string $status
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
    protected array $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'phone_verify_token_expire',
        'phone_verify_token',
        'status',
        'full_address',
    ];

    /**
     * @var array $hidden
     */
    protected array $hidden = [
        'password',
        'remember_token',
        'phone_verify_token',
        'phone_verify_token_expire',
    ];

    /**
     * @var array $casts
     */
    protected array $casts = [
        'email_verified_at'         => 'datetime',
        'phone_verify_token_expire' => 'datetime',
        'full_address'              => 'object',
        'phone'                     => 'integer',
    ];

    /**
     * @param $password
     */
    public function setPasswordAttribute($password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

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
     * @return mixed
     */
    public static function registerByPhone(string $phone, string $phone_verify_token)
    {
        return static::updateOrCreate(
            [
                'phone' => $phone,
            ],
            [
                'status'                    => self::STATUS_PENDING,
                'phone_verify_token'        => $phone_verify_token,
                'phone_verify_token_expire' => (Carbon::now())->copy()->addMinute(),
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
                'phone_verify_token' => trans('Incorrect verify token.'),
            ]);
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw ValidationException::withMessages([
                'phone_verify_token' => trans('Token is expired.'),
            ]);
        }
        $this->password = $this->phone_verify_token;
        $this->phone_verify_token = $this->phone_verify_token_expire = null;

        $this->status = self::STATUS_ACTIVE;
        $this->phone_verified_at = $now;

        $this->saveOrFail();
    }
}
