<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Role
 * @package App\Models
 * @property string $name
 */
class Role extends Model
{
    use HasFactory;

    /**
     * @var array $fillable
     */
    protected $guarded = [
        'name',
    ];
    /**
     * Roles statuses
     */
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
