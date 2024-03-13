<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Imports\CustomersImport;


class ExcelController extends Controller
{
    public function importForm()
    {
        return view('import-form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect('company/import-form')->with('success', 'Data imported successfully!');
    }

    public function importFormCustomer()
    {
        return view('import-form-customer');
    }

    public function importcustomer(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CustomersImport, $request->file('file'));

        return redirect('company/import-form')->with('success', 'Data imported successfully!');
    }
}
