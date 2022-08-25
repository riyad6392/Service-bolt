<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Personnel;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Quote;
use App\Models\PaymentSetting;
use Illuminate\Support\Str;
use DB;

class ComissionController extends Controller
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

        if($request->pid==null) {
            $personnelid = $pdata[0]->id;
        } else {
            $personnelid = $request->pid;
        }

        @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->groupBy('quote.personnelid')->get();

        @$tickedatadetails = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->where('quote.ticket_status',3)->get();

        //->select(DB::raw('quote.*, GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS agreements'),'personnel.personnelname')
        //dd($tickedata);
        @$percentall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','percent')->get();
        
        @$comisiondatapercent = $percentall[0]->contentcommission;
        
        @$amountall=PaymentSetting::where('pid',$personnelid)->where('paymentbase','commission')->where('type','amount')->get();
        @$comisiondataamount = $amountall[0]->contentcommission;
        //dd($comisiondataamount);
        return view('report.commission',compact('auth_id','pdata','tickedata','percentall','amountall','tickedatadetails','personnelid','comisiondataamount','comisiondatapercent'));
    }
}
