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
        
        $commissiondata = PaymentSetting::where('uid',$auth_id)->whereNull('pid')->where('type','amount')->get();
        $commissionpdata = PaymentSetting::where('uid',$auth_id)->whereNull('pid')->where('type','percent')->get();

        $commissiondata1 = PaymentSetting::select('allspvalue')->where('uid',$auth_id)->whereNull('pid')->where('type','amount')->get();
        $commissionpdata1 = PaymentSetting::select('allspvalue')->where('uid',$auth_id)->whereNull('pid')->where('type','percent')->get();
        
        if(count($commissiondata) == 0) { 
            $commissiondata = "";
            $type = ""; 
        } else {
            @$commissiondata = json_decode(@$commissiondata[0]->content,true);
            $type = "amount";
        }

        if(count($commissionpdata) == 0) { 
           $commissionpdata = "";
           $type1 = ""; 
        } else {
            $commissionpdata = json_decode(@$commissionpdata[0]->content,true);
            $type1 = "percent";   
        }
       
        return view('commission.index',compact('services','products','commissiondata','type','type1','commissionpdata','commissiondata1','commissionpdata1'));
    }

    public function commissioncreate(Request $request) {

     
      $auth_id = auth()->user()->id;
      
      if(auth()->user()->role == 'company') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }
      $services = Service::select('id','servicename')->where('userid',$auth_id)->get();
        $products = Inventory::select('id','productname')->where('user_id',$auth_id)->get();
        foreach($services as $key=>$value) {
            $servrname[] = $value->servicename;
        }
        foreach($products as $key1=>$value1) {
            $productname[] = $value1->productname;
        }

        $mainarray = array_merge($servrname,$productname);    
      
      $iscomisiondata = PaymentSetting::where('uid',$auth_id)->whereNull('pid')->get();
       if(count($iscomisiondata) >= 1) {
        PaymentSetting::where('uid',$auth_id)->whereNull('pid')->delete();
       }

      $paymentSetting = new PaymentSetting;
      $paymentSetting->uid = $auth_id;
        if($request->commission == "amount") {
            $datajson = array();
            //$amountarray = array_filter($request->amountwise);
            //$amountvaluearray = array_filter($request->amountvalue);
            $amountvaluearray = $request->amountvalue;
            $typevalue = $request->commission;
            $paymentSetting->paymentbase = "commission";

            
            foreach($mainarray as $key=> $value) {
              $servicename = $value;
              if($amountvaluearray[$key]==null){
                $amountvaluearray[$key] = 0;
              }
              $servicevalue = $amountvaluearray[$key];
              array_push($datajson,[$servicename=>$servicevalue]);
            }

            if($request->amountall=="on") {
              if($request->amountallamount!=null) {
                $paymentSetting->allspvalue = $request->amountallamount;
              }
            }

            $paymentSetting->content=json_encode($datajson);
            $paymentSetting->type=$typevalue;
            $paymentSetting->save();
        }  
      $paymentSetting1 = new PaymentSetting;
      $paymentSetting1->uid = $auth_id;
        if($request->commission1 == "percent") {
            $datajson1 = array();
            //$parray = $request->percentwise;
            $pvaluearray = $request->percentvalue;
            $typevalue = $request->commission1;
            $paymentSetting1->paymentbase = "commission";

            foreach($mainarray as $key=> $value1) {
              $servicename1 = $value1;
              if($pvaluearray[$key]==null){
                $pvaluearray[$key] = 0;
              }
              $servicevalue1 = $pvaluearray[$key];
              array_push($datajson1,[$servicename1=>$servicevalue1]);
            }

            if($request->percentall=="on") {
              if($request->percentallamount!=null) {
                $paymentSetting1->allspvalue = $request->percentallamount;
              }
            }

            $paymentSetting1->content=json_encode($datajson1);
            $paymentSetting1->type=$typevalue;
            $paymentSetting1->save();
        }
        return redirect()->back(); 
    }
}
