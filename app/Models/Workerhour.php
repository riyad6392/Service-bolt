<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workerhour extends Model
{
    protected $table = 'workerhour';
    protected $fillable = ['workerid','starttime','endtime','date','date1','totalhours','note'];
}
