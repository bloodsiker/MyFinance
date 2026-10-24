<?php

namespace App\Providers;

use App\Services\PaymentInterface;
use App\Services\PaymentService;
use App\Services\Sms\BsgService;
use App\Services\Sms\SmsInterface;
use App\Services\StageOfRegistrationInterface;
use App\Services\StageOfRegistrationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind(StageOfRegistrationInterface::class, StageOfRegistrationService::class);
//        $this->app->bind(PaymentInterface::class, PaymentService::class);
//        $this->app->bind(SmsInterface::class, BsgService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
