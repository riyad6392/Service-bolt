<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $table = 'quote';
    protected $fillable = ['userid','workerid','parentid','customerid','customername','serviceid','servicename','personnelid','price','amount_paid','over_paid','partial','frequency','radiogroup','etc','time','minute','description','customer_notes','internal_notes','address_id','address','latitude','longitude','product_id','product_name','ticket_status','giventime','givenendtime','givendate','givenstartdate','givenenddate','checklist','note_for_customer','internal_notes','imagelist','payment_mode','checknumber','payment_amount','primaryname','duedate','tickettotal','tax','invoiceid','flag','count','ticket_created_date','invoicenote'];
}
