<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table = 'paymentsetting';
    protected $fillable = ['uid','pid','hourly','hourlypayment','fixedsalary','monthlysalary','bimonthlysalary','weeklysalary','biweeklysalary','amountwise','amountall','amountcontent','percentwise','percentall','percentcontent','hiredate'];
}
