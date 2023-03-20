<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Quote;
use App\Models\PaymentSetting;
use Carbon\Carbon;
use App\Models\Service;

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

        $servicereport = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->orderBy('quote.id','DESC')->get();
        
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
            $since = date('Y-m-d', strtotime($request->since));
            $until = date('Y-m-d', strtotime($request->until));
                @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personeldata)->where('quote.ticket_status',3)->whereColumn('quote.personnelid','quote.primaryname')->whereBetween('quote.ticketdate', [$since, $until])->groupBy('quote.personnelid')->get();    
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
                    $since = date('Y-m-d', strtotime($request->since));
                    $until = date('Y-m-d', strtotime($request->until));
                    @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->whereBetween('quote.ticketdate', [$since, $until])->groupBy('quote.personnelid')->get();
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

        $productinfo = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->get();
        $personnelids  =array();
        foreach($productinfo as $key =>$value) {
           $pids[] = $value->product_id;
           $personnelids[] = $value->personnelid;
        }

        $counts = implode(",", $pids);
        $arrayv = explode(",",$counts);
        $countsf = array_count_values($arrayv);
        arsort($countsf);
        $newArray1 = array_flip($countsf);
        $productinfo = DB::table('products')->whereIn('id',$newArray1)->get();
        $numerickey = array_values($countsf);
        
        $countsf1 = array_count_values($personnelids);
        arsort($countsf1);
        $personnelids = array_flip($countsf1);
        $personnelids = array_values($personnelids);

        $salesreport = DB::table('quote')
        ->select(DB::raw('givenstartdate as date'),DB::raw('group_concat(quote.serviceid) as serviceid'),DB::raw('group_concat(quote.product_id) as product_id'),'quote.id','quote.updated_at','customer.customername','personnel.personnelname', DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN price END) as totalprice'),DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN tickettotal END) as tickettotalprice'),DB::raw('COUNT(CASE WHEN quote.personnelid = quote.primaryname THEN quote.id END) as totalticket'))
        ->join('customer', 'customer.id', '=', 'quote.customerid')
        ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
        ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5','4'])->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.givenstartdate','!=',null)
        ->groupBy(DB::raw('date'))->orderBy('date','desc')
        ->get();
        return view('report.index',compact('auth_id','pdata1','tickedata','percentall','amountall','tickedatadetails','personnelid','comisiondataamount','comisiondatapercent','currentdate','from','to','servicereport','productinfo','numerickey','personnelids','salesreport'));
    }

    public function servicefilter(Request $request) 
    {
      $auth_id = auth()->user()->id;
      $servicereport = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->orderBy('quote.id','DESC')->get();

        $fileName = date('d-m-Y').'_order.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Ticket #','Customer Name','Service location','Personnel','Service Provided', 'Cost','Status');

        $callback = function() use($servicereport, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($servicereport as $key =>$ticket) {
              $explode_id = explode(',', $ticket->serviceid);
              $servicedata = Service::select('servicename')
                ->whereIn('services.id',$explode_id)->get();
              if($ticket->payment_status!=null || $ticket->payment_mode!=null) {
                $payment_status = "Completed";
              } else {
                $payment_status = "Pending";
              }
              if($ticket->payment_mode!=null) {
                $paid_status = '-'.$ticket->payment_mode;
              } else {
                $paid_status = "";
              }
              $statusfinal = $payment_status.$paid_status;
               $i=0;
               foreach($servicedata as $servicename) {
                if(count($servicedata) == 0) {
                    $servicename = '-';
                  } else {
                    $servicename = $servicename->servicename;
                  }
                  $i=1; break;
               }
              fputcsv($file, array($ticket->id, $ticket->customername, $ticket->address, $ticket->personnelname,$servicename, $ticket->price, $statusfinal));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function productfilter(Request $request) 
    {
      $auth_id = auth()->user()->id;
      $productinfo = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->get();
        $personnelids  =array();
        foreach($productinfo as $key =>$value) {
           $pids[] = $value->product_id;
           $personnelids[] = $value->personnelid;
        }
        
        $counts = implode(",", $pids);
        $arrayv = explode(",",$counts);
        $countsf = array_count_values($arrayv);
        arsort($countsf);
        $newArray1 = array_flip($countsf);
        $productinfo = DB::table('products')->whereIn('id',$newArray1)->get();
        $numerickey = array_values($countsf);
        
        $countsf1 = array_count_values($personnelids);
        arsort($countsf1);
        $personnelids = array_flip($countsf1);
        $personnelids = array_values($personnelids);

        $fileName = date('d-m-Y').'_order.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Product','Units Sold','Date of Last Sale','Remain Stock','Total Cost', 'Top Seller (Personnel)');

        $callback = function() use($productinfo, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($productinfo as $key =>$ticket) {
              $pinfo = Personnel::select('personnelname')->where('id',$personnelids[$key])->first();
            $lastdate = Quote::whereRaw('FIND_IN_SET("'.$ticket->id.'",product_id)')->where('quote.userid',$auth_id)->whereIn('ticket_status',array('2','3','4'))->where('payment_status','!=',null)->where('payment_mode','!=',null)->where('parentid', '=',"")->orderBy('id','desc')->first();

              fputcsv($file, array($ticket->productname, $numerickey[$key], $lastdate->updated_at, $ticket->quantity,$ticket->price*$numerickey[$key], $pinfo->personnelname));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
