<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workersethour extends Model
{
    protected $table = 'sethours';
    protected $fillable = ['workerid','starttime','endtime','date','date1','totalhours'];
}
