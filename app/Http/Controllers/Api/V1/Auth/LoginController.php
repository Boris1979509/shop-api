<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\LoginTypeRequest;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginController
 * @package App\Http\Controllers\Api\V1\Auth
 */
class LoginController extends BaseController
{
    /**
     * Login
     * @param LoginTypeRequest $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function login(LoginTypeRequest $request): JsonResponse
    {
        app()->make('App\Http\Requests\Api\V1\Auth\\' . ucfirst($request->get('type')) . 'Request');
        $credentials = $request->merge(['status' => User::STATUS_ACTIVE])->except('type');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'password' => trans('auth.invalidUsernameOrPassword'),
            ]);
        }
        $request->session()->regenerate();

        return response()->json(
            ['message' => trans('auth.youHaveSuccessfullyLoggedIn')],
            200
        );
    }

    /**
     * Logout
     * @param Request $request
     * @return JsonResponse|void
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['message' => trans('auth.youHaveSuccessfullyLoggedOut')]);
        } catch (\Exception $error) {
            return response()->json($error->getMessage());
        }
    }
}
