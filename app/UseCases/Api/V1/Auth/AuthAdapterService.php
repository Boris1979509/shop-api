<?php

namespace App\UseCases\Api\V1\Auth;

use Illuminate\Http\Request;

/**
 * Interface AuthAdapterService
 * @package App\UseCases\Api\V1\Auth
 */
interface AuthAdapterService
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function verify(Request $request);

}
