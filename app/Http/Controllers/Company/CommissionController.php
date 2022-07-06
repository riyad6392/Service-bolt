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
        $iscomisiondata = PaymentSetting::where('uid',$auth_id)->whereNull('pid')->get();
        //dd($iscomisiondata);
        if(count($iscomisiondata) == 0) { 
           @$commissiondata = ""; 
           $iscomisiondata = "";
           $commissionpdata = "";
        } else {
            if($iscomisiondata[0]->type=="amount") {
                @$commissiondata = json_decode(@$iscomisiondata[0]->content,true);
                $commissionpdata = "";
            }
            if($iscomisiondata[0]->type=="percent") {
                $commissionpdata = json_decode(@$iscomisiondata[0]->content,true);
                @$commissiondata = "";
            }
            
        }
       
        return view('commission.index',compact('services','products','commissiondata','iscomisiondata','commissionpdata'));
    }

    public function commissioncreate(Request $request) {
       $auth_id = auth()->user()->id;
      
      if(auth()->user()->role == 'company') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }

      $datajson = array();
      $iscomisiondata = PaymentSetting::where('uid',$auth_id)->whereNull('pid')->get();
       if(count($iscomisiondata) >= 1) {
        PaymentSetting::where('uid',$auth_id)->whereNull('pid')->delete();
       }

      $paymentSetting = new PaymentSetting;
      $paymentSetting->uid = $auth_id;
        if($request->commission == "amount") {
            $amountarray = array_filter($request->amountwise);
            $amountvaluearray = array_filter($request->amountvalue);
        }  else {
            $amountarray = array_filter($request->percentwise);
            $amountvaluearray = array_filter($request->percentvalue);
        }

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
