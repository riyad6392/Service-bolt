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
            'host' => $mailsetting->host,
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => $mailsetting->smtpusername,
            'password' => $mailsetting->smtppassword,
            'from' => [
                'address' => $mailsetting->smtpusername,
                'name' => 'ServiceBolt',
            ]
        ];
        Config::set('mail',$data);
      }
    }
}
