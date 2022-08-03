<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $table = 'adminproductfeature';
    protected $fillable = ['productfeature','image','status'];
}
