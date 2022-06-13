<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Managefield extends Model
{
    protected $table = 'tablecolumnlist';
    protected $fillable = ['userid','page','columname'];
}
