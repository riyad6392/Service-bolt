<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Config;
use Illuminate\Contracts\Encryption\DecryptException;

class EmailConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $mail = User::where('role','=','superadmin')->first();


        if ($mail) {
            try {
                $password = $mail->mail_password;
            } catch (DecryptException $e) {
                $password = $mail->mail_password;
            }
            $config = array(
                'driver'     => $mail->mail_driver,
                'host'       => $mail->host,
                'port'       => $mail->mail_port,
                'from'       => array('address' => $mail->smtpusername, 'name' => $mail->firstname),
                'encryption' => $mail->mail_encryption,
                'username'   => $mail->smtpusername,
                'password'   => $password,
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );
            Config::set('mail', $config);
        }
    }
}
