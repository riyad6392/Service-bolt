<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    protected $table = 'productdescription';
    protected $fillable = ['id','ticketid','productid','productdescription'];
}
