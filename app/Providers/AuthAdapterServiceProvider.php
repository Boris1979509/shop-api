<?php

namespace App\Providers;

use App\Services\Sms\SmsRu;
use App\UseCases\Api\V1\Auth\AuthAdapterService;
use App\UseCases\Api\V1\Auth\AuthByEmailService;
use App\UseCases\Api\V1\Auth\AuthByPhoneService;
use App\UseCases\Api\V1\Auth\Timer;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthAdapterServiceProvider
 * @package App\Providers
 */
class AuthAdapterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AuthAdapterService::class, static function ($app) {
            switch ($app->make('config')->get('services.auth_adapter')) {
                case 'phone':
                    return new AuthByPhoneService(new SmsRu(), new Timer());
                case 'email':
                    return new AuthByEmailService();
                default:
                    throw new \RuntimeException('Adapter class not found.');
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
