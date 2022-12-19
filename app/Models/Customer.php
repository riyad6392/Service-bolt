<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable = ['userid','workerid','customername','phonenumber','email','companyname','serviceid','productid','image','billingaddress','mailingaddress'];

    public function getService($auth_id) {

    }
}

