<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\UseCases\Api\V1\Auth\AuthAdapterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class PasswordResetController
 * @package App\Http\Controllers\Api\V1\Auth
 */
class PasswordResetController extends BaseController
{
    /**
     * @var AuthAdapterService $authAdapterService
     */
    private $authAdapterService;

    /**
     * PasswordResetController constructor.
     * @param AuthAdapterService $authAdapterService
     */
    public function __construct(AuthAdapterService $authAdapterService)
    {
        $this->authAdapterService = $authAdapterService;
    }


    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function reset(Request $request)
    {
        try {
            return $this->authAdapterService->register($request);
        } catch (\RuntimeException $error) {
            Log::error($error);
        }
    }
}
