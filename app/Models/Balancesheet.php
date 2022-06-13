<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balancesheet extends Model
{
    protected $table = 'balancesheet';
    protected $fillable = ['userid','ticketid','amount','paymentmethod','customername','status'];
}
