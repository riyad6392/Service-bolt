<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      $mailsetting = User::where('role','superadmin')->first();
      if($mailsetting) {
        $data = [
            'driver' => env('MAIL_DRIVER', 'smtp'),
            'port' => env('MAIL_PORT', 587),
            'host' => $mailsetting->host ?? env('MAIL_HOST'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => $mailsetting->smtpusername ?? env('MAIL_USERNAME'),
            'password' => $mailsetting->smtppassword ?? env('MAIL_PASSWORD'),
            'from' => [
                'address' => $mailsetting->smtpusername ?? env('MAIL_FROM_ADDRESS'),
                'name' => 'ServiceBolt',
            ]
        ];
        Config::set('mail',$data);
      }
    }
}
