<?php

namespace App\UseCases\Api\V1\Auth;

use Illuminate\Http\Request;

/**
 * Class AuthByEmailService
 * @package App\UseCases\Api\V1\Auth
 */
class AuthByEmailService implements AuthAdapterService
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        // TODO: Implement register() method.
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function verify(Request $request)
    {
        // TODO: Implement verify() method.
    }
}
