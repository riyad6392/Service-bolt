<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'appnotification';
    protected $fillable = ['uid','pid','ticketid','message','read_by','created_at','updated_at'];
}
