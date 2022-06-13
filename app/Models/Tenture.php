<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenture extends Model
{
    protected $table = 'tenture';
    protected $fillable = ['tenturename','day','status'];
}
