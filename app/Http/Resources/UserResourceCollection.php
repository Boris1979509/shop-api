<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

/**
 * Class UserResourceCollection
 * @package App\Http\Resources
 */
class UserResourceCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [];
    }
}
