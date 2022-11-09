<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['userid','workerid','servicename','price','productid','type','frequency','time','minute','image','checklist','color','description'];
}
