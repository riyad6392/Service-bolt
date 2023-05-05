<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'password',
        'wpassword',
        'email',
        'companyname',
        'phone',
        'cardnumber',
        'date',
        'securitycode',
        'accept_terms_conditions',
        'role',
        'userid',
        'workerid',
        'address',
        'latitude',
        'longitude',
        'amount',
        'host',
        'smtpusername',
        'smtppassword',
        'mail_encryption',
        'mail_port',
        'mail_driver',
        'openingtime',
        'closingtime',
        'paymenttype',
        'color',
        'expmonth',
        'expyear',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }
}
