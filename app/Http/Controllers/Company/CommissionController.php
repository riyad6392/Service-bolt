<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use DB;
use Image;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\PaymentSetting;

class CommissionController extends Controller
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

        $services = Service::select('id','servicename')->where('userid',$auth_id)->get();
        $products = Inventory::select('id','productname')->where('user_id',$auth_id)->get();
        $iscomisiondata = PaymentSetting::where('uid',$auth_id)->whereNull('pid')->first();
        
        if($iscomisiondata == null) { 
           $commissiondata = ""; 
           $iscomisiondata = "";
        } else {
            $commissiondata = json_decode($iscomisiondata->content);
        }
        return view('commission.index',compact('services','products','commissiondata','iscomisiondata'));
    }

    public function commissioncreate(Request $request) {
       $auth_id = auth()->user()->id;
      
      if(auth()->user()->role == 'company') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }

      $datajson = array();

      $paymentSetting = new PaymentSetting;
      $paymentSetting->uid = $auth_id;

        $amountarray = $request->amountwise;
        $amountvaluearray = array_filter($request->amountvalue);
        foreach($amountarray as $key=> $value) {
          $servicename = $value;
          $servicevalue = $amountvaluearray[$key];
          array_push($datajson,[$servicename=>$servicevalue]);
        }
       
       $paymentSetting->paymentbase = "commission";
       if($request->commission == "amount") {
         $typevalue = $request->commission;
       }

       if($request->commission == "percent") {
          $typevalue = $request->commission;
       }
        
        // $datajson=array (
        //   $typevalue=>$datajson
        // );

        $paymentSetting->content=json_encode($datajson);
        $paymentSetting->type=$typevalue;
      
        
        $paymentSetting->save();

      
      return redirect()->back(); 
    }
}
