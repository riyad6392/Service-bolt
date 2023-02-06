<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workertimeoff extends Model
{
    protected $table = 'timeoff';
    // protected $casts = [
    //     'created_at' => "datetime:Y-m-d h:i:s",
    // ];
    protected $fillable = ['workerid','userid','date','date1','notes','submitted_by','status'];
}
