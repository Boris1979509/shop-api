<?php

namespace App\Providers;

use App\Services\Sms\SmsRu;
use App\UseCases\Api\V1\Auth\AuthAdapterService;
use App\UseCases\Api\V1\Auth\AuthByEmailService;
use App\UseCases\Api\V1\Auth\AuthByPhoneService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AuthAdapterService::class, static function ($app) {
            switch ($app->make('config')->get('services.auth_adapter')) {
                case 'phone':
                    return new AuthByPhoneService(new SmsRu());
                case 'email':
                    return new AuthByEmailService();
                default:
                    throw new \RuntimeException('Adapter class not found.');
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
