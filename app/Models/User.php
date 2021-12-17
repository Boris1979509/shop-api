<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // statuses
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'status',
        'full_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected array $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected array $casts = [
        'email_verified_at' => 'datetime',
        'full_address'      => 'object',
        'phone'             => 'integer',
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
}
