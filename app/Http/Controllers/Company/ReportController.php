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
        
        @$pdata = Personnel::where('userid',$auth_id)->get();
        $percentall = array();
        $amountall = array();
        $comisiondataamount = array();
        $comisiondatapercent = array();
        if($request->pid == 'All') {
        foreach($pdata as $key=>$value) {
            $personnelid[] = $value->id;
        }
        
        if($request->since!=null && $request->until!=null) {
            @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->whereBetween('quote.ticketdate', [$request->since, $request->until])->groupBy('quote.personnelid')->get();    
        } else {
            @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->groupBy('quote.personnelid')->get();
        }

        

        @$tickedatadetails = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->get();

 
        foreach($tickedata as $key=>$value) {
          @$percentall=PaymentSetting::where('pid',$personnelid[$key])->where('paymentbase','commission')->where('type','percent')->get();
          @$amountall=PaymentSetting::where('pid',$personnelid[$key])->where('paymentbase','commission')->where('type','amount')->get();
          @$comisiondatapercent = json_decode($percentall[$key]->contentcommission);
         
         
         @$comisiondataamount = json_decode($amountall[$key]->contentcommission);
        }
    } else {
            if($request->pid==null) {
                $personnelid = $pdata[0]->id;
            } else {
                $personnelid = $request->pid;
            }

            if($request->since!=null && $request->until!=null) {
                @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->whereBetween('quote.ticketdate', [$request->since, $request->until])->groupBy('quote.personnelid')->get();
            } else {
                @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->groupBy('quote.personnelid')->get();
            }
            
    
            @$tickedatadetails = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->get();

            //->select(DB::raw('quote.*, GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS agreements'),'personnel.personnelname')
            //dd($tickedata);
            @$percentall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','percent')->get();

            @$comisiondatapercent = json_decode($percentall[0]->contentcommission);

            @$amountall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','amount')->get();
            @$comisiondataamount = json_decode($amountall[0]->contentcommission);
        }
        $currentdate = Carbon::now();
        $currentdate = date('Y-m-d', strtotime($currentdate));
        @$from = $request->since;
        @$to = $request->until;
        return view('report.index',compact('auth_id','pdata','tickedata','percentall','amountall','tickedatadetails','personnelid','comisiondataamount','comisiondatapercent','currentdate','from','to'));
    }
}
