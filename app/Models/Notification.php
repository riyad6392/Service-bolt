<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $fillable = ['uid','pid','ticketid','message','read_by','created_at','updated_at'];
}
