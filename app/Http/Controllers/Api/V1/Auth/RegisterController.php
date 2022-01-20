<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\UseCases\Api\V1\Auth\AuthAdapterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Api\V1\Auth
 */
class RegisterController extends BaseController
{
    /**
     * @var AuthAdapterService $authAdapterService
     */
    private $authAdapterService;

    /**
     * RegisterController constructor.
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
    public function register(Request $request)
    {
        try {
            return $this->authAdapterService->register($request);
        } catch (\RuntimeException $error) {
            Log::error($error);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function verify(Request $request)
    {
        try {
            $this->authAdapterService->verify($request);
        } catch (\RuntimeException $error) {
            return response()->json(['error' => $error->getMessage()]);
        }
    }
}
