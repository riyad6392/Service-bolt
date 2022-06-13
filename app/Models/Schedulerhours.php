<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedulerhours extends Model
{
    protected $table = 'schedulertimesheet';
    protected $fillable = ['workerid','ticketid','starttime','endtime','date','date1','totalhours'];
}
