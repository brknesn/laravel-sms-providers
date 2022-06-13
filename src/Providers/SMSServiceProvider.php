<?php

namespace SuStartX\SMSProviders\Providers;

use Illuminate\Support\ServiceProvider;

class SMSServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('sms',function(){
            $username = env('SMS_USERNAME');
            $password = env('SMS_PASSWORD');
            $url = env('SMS_URL');
            $token = env('SMS_TOKEN');
            $from = env('SMS_FROM');

            $gateway = new \SuStartX\SMSProviders\Gateways\SmsApiGateway($username, $password, $url, $token, $from);

            return new \SuStartX\SMSProviders\Services\SmsService($gateway);
        });
    }

    public function boot()
    {
        //
    }
}
