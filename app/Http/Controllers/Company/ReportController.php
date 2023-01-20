<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Quote;
use App\Models\PaymentSetting;
use Carbon\Carbon;

use DB;
use Image;

class ReportController extends Controller
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
        
       @$pdata1 = Personnel::where('userid',$auth_id)->get();
        $percentall = array();
        $amountall = array();
        $comisiondataamount = array();
        $comisiondatapercent = array();
       ;
        @$pdata = PaymentSetting::where('uid',$auth_id)->groupBy('pid')->get()->pluck('pid')->toArray();
        
        if(empty($request->all()) || $request->pid == 'All') {
            $tickedata = [];
            $personnelid = [];
            $tickedatadetails = "";

            $personeldata = Personnel::select('personnel.id','quote.primaryname')->join('quote','quote.primaryname','=','personnel.id')->where('ticket_status','3')->whereIn('personnel.id',$pdata)->whereColumn('quote.personnelid','quote.primaryname')->groupBy('primaryname')->get()->pluck('primaryname')->toArray();
            //dd($personeldata);
            if($request->since!=null && $request->until!=null) {
                @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personeldata)->where('quote.ticket_status',3)->whereColumn('quote.personnelid','quote.primaryname')->whereBetween('quote.ticketdate', [$request->since, $request->until])->groupBy('quote.personnelid')->get();    
            } else {
                @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personeldata)->where('quote.ticket_status',3)->whereColumn('quote.personnelid','quote.primaryname')->groupBy('quote.personnelid')->get();
            }
            @$tickedatadetails = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personeldata)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->get();

 
            foreach($tickedata as $key=>$value) {
              @$percentall=PaymentSetting::where('pid',$personeldata[$key])->where('paymentbase','commission')->where('type','percent')->get();
              @$amountall=PaymentSetting::where('pid',$personeldata[$key])->where('paymentbase','commission')->where('type','amount')->get();
              @$comisiondatapercent = json_decode($percentall[$key]->contentcommission);
             
             
             @$comisiondataamount = json_decode($amountall[$key]->contentcommission);
            }

            if(count($amountall)==0 && count($percentall)==0) {
                $tickedata = array();
                $tickedatadetails = "";
            }

        } else {
            $personnelid = @$request->pid;
            $tickerdatas = Quote::select('primaryname')->where('personnelid',$personnelid)->whereColumn('personnelid','primaryname')->where('ticket_status','3')->get();  

            @$percentall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','percent')->get();

            @$amountall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','amount')->get();
            if(count($amountall)!=0 || count($percentall)!=0) {


            if(count($tickerdatas)>=1) { 
                foreach($tickerdatas as $key => $value) {
                if($value->primaryname == $personnelid) {

                if($request->since!=null && $request->until!=null) {
                    @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->whereBetween('quote.ticketdate', [$request->since, $request->until])->groupBy('quote.personnelid')->get();
                } else {
                    @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->groupBy('quote.personnelid')->get();
                }
                
                @$tickedatadetails = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->get();

                @$percentall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','percent')->get();

                @$comisiondatapercent = json_decode($percentall[0]->contentcommission);

                @$amountall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','amount')->get();
                @$comisiondataamount = json_decode($amountall[0]->contentcommission);
             } else {
                $tickedata = array();
                $tickedatadetails = "";
             }
            }
        }
        else {
          $tickedata = array();
            $tickedatadetails = "";  
        }
        } else {
            $tickedata = array();
            $tickedatadetails = "";  
        }
      }

        $currentdate = Carbon::now();
        $currentdate = date('Y-m-d', strtotime($currentdate));
        @$from = $request->since;
        @$to = $request->until;
        return view('report.index',compact('auth_id','pdata1','tickedata','percentall','amountall','tickedatadetails','personnelid','comisiondataamount','comisiondatapercent','currentdate','from','to'));
    }
}
