<?php

namespace App\UseCases\Api\V1\Auth;

use App\Models\User;
use App\Services\Sms\SmsRu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * Class AuthByPhoneService
 * @package App\UseCases\Api\V1\Auth
 */
class AuthByPhoneService implements AuthAdapterService
{
    /**
     * @var SmsRu $smsRu
     */
    private SmsRu $smsRu;

    /**
     * AuthByPhoneService constructor.
     * @param SmsRu $smsRu
     */
    public function __construct(SmsRu $smsRu)
    {
        $this->smsRu = $smsRu;
    }

    /**
     * Register by phone
     * @param Request $request
     * @throws \Exception
     */
    public function register(Request $request): void
    {
        $request->validate(['phone' => 'required|digits:10']);
        $phone = $request->input('phone');
        /** @var User $user */
        $user = User::where('phone', $phone)->first();
        if ($user && $user->isActive()) {
            throw new BadRequestException(trans('User is already verified.'));
        }

        $phone_verify_token = auth_code_generator();
        User::registerByPhone($phone, $phone_verify_token);
        $this->smsRu->send($phone, $phone_verify_token);
    }

    /**
     * Verify by phone
     * @param Request $request
     * @return mixed|void
     * @throws \Throwable
     */
    public function verify(Request $request)
    {
        $request->validate([
            'phone'              => 'required|digits:10',
            'phone_verify_token' => 'required|digits:6',
        ]);
        /** @var User $user */
        $user = User::where(
            'phone', $request->get('phone'),
            )->firstOrFail();
        if ($user && $user->isActive()) {
            throw new BadRequestException(trans('User is already verified.'));
        }
        $user->verifyPhone($request->get('phone_verify_token'), Carbon::now());
    }
}
