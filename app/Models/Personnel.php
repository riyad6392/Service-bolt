<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    protected $table = 'personnel';
    protected $fillable = ['userid','color','workerid','personnelname','phone','email','ticketid','image','address','latitude','longitude'];
}
