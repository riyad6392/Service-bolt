<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'products';
    protected $fillable = ['user_id','workerid','productname','serviceid','quantity','pquantity','sku','price','category','description','image'];
}
