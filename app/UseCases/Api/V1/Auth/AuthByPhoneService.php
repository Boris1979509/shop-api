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
    private $smsRu;
    /**
     * @var Timer $timer
     */
    private $timer;

    /**
     * AuthByPhoneService constructor.
     * @param SmsRu $smsRu
     * @param Timer $timer
     */
    public function __construct(SmsRu $smsRu, Timer $timer)
    {
        $this->smsRu = $smsRu;
        $this->timer = $timer;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $request->validate(['phone' => 'required|digits:10']);

        $timer_data = $this->timer->add();
        $phone = $request->input('phone');

        /** @var User $user */
        $user = User::where('phone', $phone)->first();

        // if ($user && $user->isActive()) {
        //    throw new BadRequestException(trans('auth.userIsAlreadyVerified'));
        // }

        if ($user && $user->isPending()
            && $user->phone_verify_token_expire->gt(
                $now = Carbon::now()->copy()
            )) {
            return $this->timer->diff($user->phone_verify_token_expire, $now);
        }

        $phone_verify_token = auth_code_generator();

        User::registerByPhone($phone, $phone_verify_token, $timer_data['ttl'])
            ->attachRole();
        $this->smsRu->send($phone, $phone_verify_token);

        return $timer_data;
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
            throw new BadRequestException(trans('auth.userIsAlreadyVerified'));
        }
        $user->verifyPhone($request->get('phone_verify_token'), Carbon::now());
    }
}
