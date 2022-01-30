<?php

namespace App\Http\Resources;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use JsonSerializable;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
{
    /**
     * @var string $wrap
     */
    public static $wrap = 'user';

    /**
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array
    {
        /** @var User $user */
        $user = Auth::user();
        return [
            'phone'      => phone_to_string($this->phone),
            'roles'       => $this->roles
            /** $this->when($user->isAdmin(), Role::ROLE_ADMIN)*/
            ,
            'created_at' => $this->created_at->format('d.m.Y'),
        ];
    }
}
