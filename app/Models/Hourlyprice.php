<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hourlyprice extends Model
{
    protected $table = 'hourlyprice';
    protected $fillable = ['id','ticketid','serviceid','hour','minute','price','servicedescription','productdescription'];
}
