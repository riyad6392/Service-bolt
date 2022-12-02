<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use DB;
use Image;

class PaymentsettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        return view('report.index',compact('auth_id'));
    }

    public function create(Request $request)
    {
        dd($request->all());
          $auth_id = auth()->user()->id;
          $pid = $request->pid;
            // $validate = Validator($request->all(), [
            //     'productname' => 'required',
            // ]);
            // if ($validate->fails()) {
            //     return redirect()->route('company.inventorycreate')->withInput($request->all())->withErrors($validate);
            // }
            //dd(end(Request::segments()));
                $data['uid'] = $auth_id;
                $data['productname'] = $request->productname;
                if(isset($request->serviceid)) {
                  $data['serviceid'] = implode(',', $request->serviceid);
                } else {
                   $data['serviceid'] = null;
                }

                $data['quantity'] = $request->quantity;
                $data['pquantity'] = $request->pquantity;
                $data['sku'] = $request->sku;
                $data['price'] = $request->price;
                $data['category'] = $request->category;
                $data['description'] = $request->description;
         
            Inventory::create($data);
            if(isset($request->duplicate)) {
              $request->session()->flash('success', 'Duplicate Product added successfully');  
            } else {
            $request->session()->flash('success', 'Product added successfully');
           }
            
            return redirect()->route('company.inventory');
    }
}
