<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Address;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Auth;


class CustomersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $auth_id = auth()->user()->id;
        $row['userid'] = $auth_id;
        $customer = Customer::create([
            'customername' => $row['customername'],
            'phonenumber' => $row['phonenumber'],
            'userid' => $row['userid'],
        ]);

        $address = Address::create([
            'customerid' => $customer->id,
            'address' => $row['address'],
            'authid' => $row['userid'],
         ]);

        return $customer;
    }
}
