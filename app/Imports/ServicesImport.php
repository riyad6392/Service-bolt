<?php

namespace App\Imports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;


class ServicesImport implements ToModel, WithHeadingRow
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
        return new Service([
            'servicename' => $row['servicename'],
            'price' => $row['price'],
            'description' => $row['description'],
            'userid' => $row['user_id'],
        ]);
    }
}
