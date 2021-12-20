<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
{

    /**
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'phone'      => phone_to_string($this->phone),
            'created_at' => $this->created_at->format('d.M.Y'),
        ];
    }
}
