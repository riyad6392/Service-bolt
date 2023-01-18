<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $table = 'quote';
    protected $fillable = ['userid','workerid','parentid','customerid','customername','serviceid','servicename','personnelid','price','frequency','radiogroup','etc','time','minute','description','address','latitude','longitude','product_id','product_name','ticket_status','giventime','givenendtime','givendate','givenstartdate','givenenddate','checklist','customernotes','imagelist','payment_mode','checknumber','payment_amount','primaryname','duedate','tickettotal','tax','invoiceid'];
}
