<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workertimeoff extends Model
{
    protected $table = 'timeoff';
    protected $fillable = ['workerid','userid','date','date1','notes','status'];
}
