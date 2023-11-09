<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;


class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $auth_id = auth()->user()->id;

        $row['user_id'] = $auth_id;
        return new Product([
            'productname' => $row['productname'],
            'quantity' => $row['quantity'],
            'pquantity' => $row['pquantity'],
            'sku' => $row['sku'],
            'price' => $row['price'],
            'description' => $row['description'],
            'user_id' => $row['user_id'],
        ]);
    }
}
