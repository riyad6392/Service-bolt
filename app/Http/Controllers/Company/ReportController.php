<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Quote;
use App\Models\PaymentSetting;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Inventory;
use DB;
use Image;
use PDF;

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

        @$sinceservice = $request->sinceservice;
        @$untilservice = $request->untilservice;
        if($request->sinceservice!=null && $request->untilservice!=null) {
            $startDate = date('Y-m-d', strtotime($request->sinceservice));
            $endDate = date('Y-m-d', strtotime($request->untilservice)); 
            $servicereport = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->whereBetween(DB::raw('DATE(quote.created_at)'), [$startDate, $endDate])->orderBy('quote.id','DESC')->get();
        } else {
           $servicereport = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->orderBy('quote.id','DESC')->get(); 
        }
        
       @$pdata1 = Personnel::where('userid',$auth_id)->get();
        $percentall = array();
        $amountall = array();
        $comisiondataamount = array();
        $comisiondatapercent = array();
       ;
        @$pdata = PaymentSetting::where('uid',$auth_id)->groupBy('pid')->get()->pluck('pid')->toArray();
        
        if(empty($request->all()) || $request->frequencyid == 'All') {
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
            $personnelid = @$request->frequencyid;
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

        @$sinceproduct = $request->sinceproduct;
        @$untilproduct = $request->untilproduct;
        if($request->sinceproduct!=null && $request->untilproduct!=null) {
            $startDate = date('Y-m-d', strtotime($request->sinceproduct));
            $endDate = date('Y-m-d', strtotime($request->untilproduct)); 
            $productinfo = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->whereBetween(DB::raw('DATE(quote.created_at)'), [$startDate, $endDate])->get();
        } else {
             $productinfo = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->get(); 
        }
        $personnelids  =array();
        

        
        foreach($productinfo as $key =>$value) {
           $pids[] = $value->product_id;
           $personnelids[] = $value->personnelid;
        }

        if(count($productinfo)>0) {
            $counts = implode(",", $pids);
            $arrayv = explode(",",$counts);
            $countsf = array_count_values($arrayv);
            arsort($countsf);
            $newArray1 = array_flip($countsf);
            $productinfo = DB::table('products')->whereIn('id',$newArray1)->get();
            $numerickey = array_values($countsf);
        } else {
           $productinfo = array(); 
           $numerickey = array();
        }

        $countsf1 = array_count_values($personnelids);
        arsort($countsf1);
        $personnelids = array_flip($countsf1);
        $personnelids = array_values($personnelids);

        @$sincesale = $request->sincesale;
        @$untilsale = $request->untilsale;
        if($request->sincesale!=null && $request->untilsale!=null) {
            $startDate = date('Y-m-d', strtotime($request->sincesale));
            $endDate = date('Y-m-d', strtotime($request->untilsale));
                $salesreport = DB::table('quote')
                ->select(DB::raw('givenstartdate as date'),DB::raw('group_concat(quote.serviceid) as serviceid'),DB::raw('group_concat(quote.product_id) as product_id'),'quote.id','quote.updated_at','customer.customername','personnel.personnelname', DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN price END) as totalprice'),DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN tickettotal END) as tickettotalprice'),DB::raw('COUNT(CASE WHEN quote.personnelid = quote.primaryname THEN quote.id END) as totalticket'))
                ->join('customer', 'customer.id', '=', 'quote.customerid')
                ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
                ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5','4'])->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.givenstartdate','!=',null)->whereBetween('quote.givenstartdate', [$startDate, $endDate])->groupBy(DB::raw('date'))->orderBy('date','desc')
                ->get();
        } else {
            $salesreport = DB::table('quote')
                ->select(DB::raw('givenstartdate as date'),DB::raw('group_concat(quote.serviceid) as serviceid'),DB::raw('group_concat(quote.product_id) as product_id'),'quote.id','quote.updated_at','customer.customername','personnel.personnelname', DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN price END) as totalprice'),DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN tickettotal END) as tickettotalprice'),DB::raw('COUNT(CASE WHEN quote.personnelid = quote.primaryname THEN quote.id END) as totalticket'))
                ->join('customer', 'customer.id', '=', 'quote.customerid')
                ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
                ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5','4'])->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.givenstartdate','!=',null)
                ->groupBy(DB::raw('date'))->orderBy('date','desc')
                ->get();  
        }
        $frequencyid = [];
        $recurringreport = [];
       // if(isset($request->frequencyid)) {
            $startDate = date('Y-m-d', strtotime($request->sincerecur));
            $endDate = date('Y-m-d', strtotime($request->untilrecur));
            if($request->frequencyid == null) {
                $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.givenstartdate','!=',"")->orWhereBetween(DB::raw('DATE(givenstartdate)'), [$startDate, $endDate])->orderBy('quote.id','DESC')->get();

           } elseif($request->frequencyid == 'All') {
                $frequencyid = [];
                if($request->sincerecur!=null && $request->untilrecur!=null) {
                    $startDate = date('Y-m-d', strtotime($request->sincerecur));
                    $endDate = date('Y-m-d', strtotime($request->untilrecur));
                    $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.givenstartdate','!=',"")->whereBetween(DB::raw('DATE(givenstartdate)'), [$startDate, $endDate])->orderBy('quote.id','DESC')->get();
                } else {
                    $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.givenstartdate','!=',"")->orderBy('quote.id','DESC')->get();

                }
            } else {
                $frequencyid = $request->frequencyid;
                if($request->sincerecur!=null && $request->untilrecur!=null) {
                    $startDate = date('Y-m-d', strtotime($request->sincerecur));
                    $endDate = date('Y-m-d', strtotime($request->untilrecur));
                    $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.givenstartdate','!=',"")->whereBetween(DB::raw('DATE(givenstartdate)'), [$startDate, $endDate])->where('quote.frequency',$request->frequencyid)->orderBy('quote.id','DESC')->get();
                } else {
                    $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.givenstartdate','!=',"")->where('quote.frequency',$request->frequencyid)->orderBy('quote.id','DESC')->get();
                }
            }  
       
        
         $frequency = DB::table('tenture')->get();
         @$sincerecur = $request->sincerecur;
         @$untilrecur = $request->untilrecur;

         //dd($frequencyid);

        return view('report.index',compact('auth_id','pdata1','tickedata','percentall','amountall','tickedatadetails','personnelid','comisiondataamount','comisiondatapercent','currentdate','from','to','servicereport','productinfo','numerickey','personnelids','salesreport','recurringreport','frequency','frequencyid','sincerecur','untilrecur','sincesale','untilsale','sinceservice','untilservice','sinceproduct','untilproduct'));
    }

    public function servicefilter(Request $request) 
    {
      $auth_id = auth()->user()->id;
      if($request->sinceservices!=null && $request->untilservices!=null) {
        $startDate = date('Y-m-d', strtotime($request->sinceservices));
        $endDate = date('Y-m-d', strtotime($request->untilservices));
        $servicereport = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->whereBetween(DB::raw('DATE(quote.created_at)'), [$startDate, $endDate])->orderBy('quote.id','DESC')->get();
        } else {
           $servicereport = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->orderBy('quote.id','DESC')->get(); 
        }
        $fileName = date('d-m-Y').'_servicereport.csv';
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

    public function recuringfilter(Request $request)
    {
        $auth_id = auth()->user()->id;
        $frequencytype = $request->frequencytype;
        if($frequencytype == "All") {
            if($request->sincerecuring!=null && $request->untilrecuring!=null) { 
              $startDate = date('Y-m-d', strtotime($request->sincerecuring));
              $endDate = date('Y-m-d', strtotime($request->untilrecuring));
                $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.givenstartdate','!=',"")->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->orderBy('quote.id','DESC')->get();
            } else {
                $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.givenstartdate','!=',"")->orderBy('quote.id','DESC')->get();
            }
        } else {
            if($request->sincerecuring!=null && $request->untilrecuring!=null) { 
              $startDate = date('Y-m-d', strtotime($request->sincerecuring));
              $endDate = date('Y-m-d', strtotime($request->untilrecuring));
                $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.frequency',$frequencytype)->where('quote.givenstartdate','!=',"")->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->orderBy('quote.id','DESC')->get();
            } else {
                $recurringreport = Quote::where('quote.userid',$auth_id)->where('quote.count','!=',0)->where('quote.frequency',$frequencytype)->where('quote.givenstartdate','!=',"")->orderBy('quote.id','DESC')->get();
            }
        }

        $fileName = date('d-m-Y').'_recurringreport.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Ticket #','Date','Frequency','Service Address');

        $callback = function() use($recurringreport, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($recurringreport as $key =>$ticket) {
              fputcsv($file, array($ticket->id, $ticket->created_at, $ticket->frequency, $ticket->address));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function productfilter(Request $request) 
    {
        $auth_id = auth()->user()->id;
        if($request->sinceproducts!=null && $request->untilproducts!=null) {
            $startDate = date('Y-m-d', strtotime($request->sinceproducts));
            $endDate = date('Y-m-d', strtotime($request->untilproducts)); 
            $productinfo = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->whereBetween(DB::raw('DATE(quote.created_at)'), [$startDate, $endDate])->get();
        } else {
            $productinfo = Quote::select('quote.*','customer.email','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.parentid', '=',"")->get();
        }
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

        $fileName = date('d-m-Y').'_productreport.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Product','Units Sold','Date of Last Sale','Remain Stock','Total Cost','Top Seller (Personnel)');

         $callback = function() use($productinfo,$auth_id,$numerickey,$personnelids,$columns) {
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

    public function salesfilter(Request $request)
    {
        $auth_id = auth()->user()->id;
        if($request->sincesales!=null && $request->untilsales!=null) {
            $startDate = date('Y-m-d', strtotime($request->sincesales));
            $endDate = date('Y-m-d', strtotime($request->untilsales));
            $salesreport = DB::table('quote')
            ->select(DB::raw('givenstartdate as date'),DB::raw('group_concat(quote.serviceid) as serviceid'),DB::raw('group_concat(quote.product_id) as product_id'),'quote.id','quote.updated_at','customer.customername','personnel.personnelname', DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN price END) as totalprice'),DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN tickettotal END) as tickettotalprice'),DB::raw('COUNT(CASE WHEN quote.personnelid = quote.primaryname THEN quote.id END) as totalticket'))
            ->join('customer', 'customer.id', '=', 'quote.customerid')
            ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
            ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5','4'])->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.givenstartdate','!=',null)->whereBetween('quote.givenstartdate', [$startDate, $endDate])
            ->groupBy(DB::raw('date'))->orderBy('date','desc')
            ->get();
        } else {
            $salesreport = DB::table('quote')
                ->select(DB::raw('givenstartdate as date'),DB::raw('group_concat(quote.serviceid) as serviceid'),DB::raw('group_concat(quote.product_id) as product_id'),'quote.id','quote.updated_at','customer.customername','personnel.personnelname', DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN price END) as totalprice'),DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN tickettotal END) as tickettotalprice'),DB::raw('COUNT(CASE WHEN quote.personnelid = quote.primaryname THEN quote.id END) as totalticket'))
                ->join('customer', 'customer.id', '=', 'quote.customerid')
                ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
                ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5','4'])->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->where('quote.givenstartdate','!=',null)
                ->groupBy(DB::raw('date'))->orderBy('date','desc')
                ->get();
        }
        $fileName = date('d-m-Y').'_salesreport.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Date','# of Tickets','Service Sold Total ($)','Product Sold Total ($)','Ticket Total','Billing Total');
        $callback = function() use($salesreport,$auth_id,$columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($salesreport as $key =>$value) {
              if($value->tickettotalprice==null || $value->tickettotalprice==0 || $value->tickettotalprice=="") {
              $newprice = $value->totalprice;
            } else {
              $newprice = $value->tickettotalprice;
            }

           $arrayv = explode(",",$value->serviceid);
           $countsf = array_count_values($arrayv);
           arsort($countsf);

           $totalssold = 0;
           foreach($countsf as $key1=>$value1) {
                $servicedata = Service::select('price')
                    ->where('id',$key1)->get();
                $totalssold =  $totalssold+@$servicedata[0]->price;
           }

           $parrayv = explode(",",$value->product_id);
           $pcountsf = array_count_values($parrayv);
           arsort($pcountsf);
           
           $totalpsold = 0;
           foreach($pcountsf as $key11=>$value11) {
                $pdata = Inventory::select('price')
                    ->where('id',$key11)->get();
                $totalpsold =  $totalpsold+@$pdata[0]->price;
           }
           $price1 = number_format((float)$newprice, 2, '.', '');
           $price2 = number_format((float)$value->totalprice, 2, '.', '');
           $tdate = date('m-d-Y', strtotime($value->date));
              fputcsv($file, array($tdate, $value->totalticket, $totalssold, $totalpsold,$price1, $price2));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers); 
        // $pdf = PDF::loadView('mail_templates.salesreport', ['salesreport'=>$salesreport]);

        
        // return $pdf->download('salesreport.pdf');
    }
    public function commissiondownload(Request $request)
    {
        $auth_id = auth()->user()->id;
        $persid = $request->persid;
        @$from = $request->sinced;
        @$to = $request->untild;
        $pname = Personnel::select('personnelname')->where('id',$request->persid)->first();
        @$pdata1 = Personnel::where('userid',$auth_id)->get();
        $percentall = array();
        $amountall = array();
        $comisiondataamount = array();
        $comisiondatapercent = array();
       ;
        @$pdata = PaymentSetting::where('uid',$auth_id)->groupBy('pid')->get()->pluck('pid')->toArray();
        $tickerdatas = Quote::select('primaryname')->where('personnelid',$persid)->whereColumn('personnelid','primaryname')->where('ticket_status','3')->get();  

        @$percentall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','percent')->get();

        @$amountall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','amount')->get();
        if(count($amountall)!=0 || count($percentall)!=0) {


            if(count($tickerdatas)>=1) { 
                foreach($tickerdatas as $key => $value) {
                    if($value->primaryname == $persid) {

                        if($request->sinced!=null && $request->untild!=null) {
                            $since = date('Y-m-d', strtotime($request->sinced));
                            $until = date('Y-m-d', strtotime($request->untild));
                            @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->whereBetween('quote.ticketdate', [$since, $until])->groupBy('quote.personnelid')->get();
                        } else {
                            @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->groupBy('quote.personnelid')->get();
                        }
                        
                        @$tickedatadetails = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->get();

                        @$percentall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','percent')->get();

                        @$comisiondatapercent = json_decode($percentall[0]->contentcommission);

                        @$amountall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','amount')->get();
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

      if(@$from!=null && @$to!=null) {
            $since = date('Y-m-d', strtotime($from));
            $until = date('Y-m-d', strtotime($to));
            @$tickedatadetailsdata = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->where('quote.ticket_status',3)->whereColumn('quote.personnelid','quote.primaryname')->whereBetween('quote.ticketdate', [$since, $until])->get();
        } else {
            @$tickedatadetailsdata = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->get();
        }

               $fileName = $pname->personnelname.'_commissionreport.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
         $columns = array('Date','Ticket#','Services','Products','Flat Amount','Variable Amount','Total Payout');

         $callback = function() use($tickedatadetailsdata,$persid,$amountall,$percentall,$comisiondataamount,$comisiondatapercent,$pname,$from,$to,$columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tickedatadetailsdata as $key =>$value) {
              $ids=$value->id;
                if(!empty($value->parentid))
                {
                    $ids=$value->parentid;

                }
                $explode_id = explode(',', $value->serviceid);
                $servicedata = Service::select('servicename','price')
                                ->whereIn('services.id',$explode_id)->get();
                $pexplode_id = 0;
                $productname = "--";
                if($value->product_id!=null || $value->product_id!="") {   
                  $pexplode_id = explode(',', $value->product_id);
                   $pdata = Inventory::select('id','price','productname')
                    ->whereIn('products.id',$pexplode_id)->get();
                }
             
                $ttlflat = 0;
                $ptamounttotal = 0;
                if(count($amountall)>0) {

                    if($amountall[0]->allspvalue==null) {
                        foreach($servicedata as $key1=>$value1) {
                          $sname[] = $value1->servicename;
                           $servname = implode(',',$sname);
                        }
                        if($pexplode_id!=0) {
                        foreach($pdata as $key2=>$value2) {
                           @$pname[] = @$value2->productname;
                           $productname = implode(',',$pname);
                         }
                        }

                        $ttlflat1 = 0;

                        foreach($explode_id as $servicekey =>$servicevalue) {
                            foreach($comisiondataamount->service as $key=>$sitem)
                            {
                                if($sitem->id==$servicevalue && $sitem->price!=0)
                                {
                                  $ttlflat1 += $sitem->price;
                                }
                            }
                        }

                        $ttlflat2 = 0;
                            if($pexplode_id!=0) {
                                foreach($pexplode_id as $servicekey =>$servicevalue) {
                                    foreach($comisiondataamount->product as $key=>$sitem1)
                                    {
                                        if($sitem1->id==$servicevalue && $sitem1->price!=0)
                                        {
                                          $ttlflat2 += $sitem1->price;
                                        }
                                    }
                                }
                            }
                                $ttlflat = $ttlflat1 +$ttlflat2;
                                
                            } else {

                                 foreach($servicedata as $key1=>$value1) {
                                  $sname[] = $value1->servicename;
                                   $servname = implode(',',$sname);
                                }

                                if($pexplode_id!=0) {
                                   foreach($pdata as $key2=>$value2) {
                                   @$pname = @$value2->productname;
                                   $productname = $pname;
                                 }
                                }

                        $flatvalue = $amountall[0]->allspvalue;

                        $flatv = $flatvalue*count($explode_id); 
                        if($pexplode_id!=0) {
                            $pvalue  =  $flatvalue*count($pexplode_id);
                        } else {
                            $pvalue = 0;  
                        }
                        
                        $ttlflat = $flatv+$pvalue;
                    }
                }
                if(count($percentall)>0) {
                         $ptamount = 0;
                         $sname = array();
                         $ptamount1 = 0;
                         $pname = array();

                         if($percentall[0]->allspvalue == null) {
                            
                            foreach($explode_id as $servicekey =>$servicevalue) {
                                foreach($comisiondatapercent->service as $key=>$sitem)
                                {
                                  if($sitem->id==$servicevalue && $sitem->price!=0)
                                  {
                                    $servicedata = Service::select('servicename','price')
                                    ->where('services.id',$sitem->id)->first();
                                    $ptamount += $servicedata->price*$sitem->price/100;               
                                  }
                                }
                            }
                            if($pexplode_id!=0) {
                                foreach($pexplode_id as $key=>$pid) {
                                    foreach($comisiondatapercent->product as $key=>$sitem)
                                    {
                                      if($sitem->id==$pid && $sitem->price!=0)
                                      {
                                        $pdata = Inventory::select('id','price')->where('products.id',$sitem->id)->first();
                                        
                                        @$ptamount1 += @$pdata->price*@$sitem->price/100;               
                                      }
                                    }
                                }
                            }
                         } else {
                         foreach($servicedata as $key1=>$value1) {
                           $ptamount += $value1->price*$percentall[0]->allspvalue/100;
                           $ptamount222 =  $ptamount *count($servicedata);
                           $sname[] = $value1->servicename;
                           $servname = implode(',',$sname);
                         }
                        if($pexplode_id!=0) { 
                         foreach($pdata as $key2=>$value2) {

                           @$ptamount1 += @$value2->price*@$percentall[0]->allspvalue/100;
                           @$pname[] = @$value2->productname;
                           $productname = implode(',',$pname);
                         }
                       }
                     }
                         $ptamounttotal =$ptamount+$ptamount1;
                 }
                fputcsv($file, array($value->ticketdate, $ids, $servname, $productname,$ttlflat, $ptamounttotal,$ttlflat+@$ptamounttotal));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);    
    }

    public function commissionreport(Request $request)
    {
        $auth_id = auth()->user()->id;
        $persid = $request->pidd;
        @$from = $request->sincedd;
        @$to = $request->untildd;
        $pname = Personnel::select('personnelname')->where('id',$request->pidd)->first();
        @$pdata1 = Personnel::where('userid',$auth_id)->get();
        $percentall = array();
        $amountall = array();
        $comisiondataamount = array();
        $comisiondatapercent = array();
        @$pdata = PaymentSetting::where('uid',$auth_id)->groupBy('pid')->get()->pluck('pid')->toArray();

        if(empty($request->all()) || $request->pidd == 'All') {
            $tickedata = [];
            $personnelid = [];
            $tickedatadetails = "";
            $personeldata = Personnel::select('personnel.id','quote.primaryname')->join('quote','quote.primaryname','=','personnel.id')->where('ticket_status','3')->whereIn('personnel.id',$pdata)->whereColumn('quote.personnelid','quote.primaryname')->groupBy('primaryname')->get()->pluck('primaryname')->toArray();
            if($request->sincedd!=null && $request->untildd!=null) {

            $since = date('Y-m-d', strtotime($request->sincedd));
            $until = date('Y-m-d', strtotime($request->untildd));
               // DB::enableQueryLog();
                @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('quote.personnelid',$personeldata)->where('quote.ticket_status',3)->whereColumn('quote.personnelid','quote.primaryname')->whereBetween('quote.ticketdate', [$since, $until])->groupBy('quote.personnelid')->get(); 
               // $query = DB::getQueryLog();
            
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
        $tickerdatas = Quote::select('primaryname')->where('personnelid',$persid)->whereColumn('personnelid','primaryname')->where('ticket_status','3')->get();  
        @$percentall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','percent')->get();
        @$amountall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','amount')->get();
        if(count($amountall)!=0 || count($percentall)!=0) {
            if(count($tickerdatas)>=1) { 
                foreach($tickerdatas as $key => $value) {
                    if($value->primaryname == $persid) {
                        if($request->sincedd!=null && $request->untildd!=null) {
                            $since = date('Y-m-d', strtotime($request->sincedd));
                            $until = date('Y-m-d', strtotime($request->untildd));
                            @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->whereBetween('quote.ticketdate', [$since, $until])->groupBy('quote.personnelid')->get();
                        } else {
                            @$tickedata = Quote::select(DB::raw('quote.*, GROUP_CONCAT(quote.serviceid ORDER BY quote.id) AS serviceid'),DB::raw('GROUP_CONCAT(quote.product_id ORDER BY quote.id) AS product_id'),DB::raw('COUNT(quote.id) as counttotal'),'personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->groupBy('quote.personnelid')->get();
                        }
                        
                        @$tickedatadetails = Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->get();
                        @$percentall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','percent')->get();
                        @$comisiondatapercent = json_decode($percentall[0]->contentcommission);
                        @$amountall=PaymentSetting::where('pid',$persid)->where('paymentbase','commission')->where('type','amount')->get();
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
        }}
        $fileName = 'commissionreport.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
         $columns = array('PERSONNEL NAME','TICKETS WORKED','FLAT AMOUNT','VARIABLE AMOUNT','   TOTOL PAYOUT');
         
        $callback = function() use($tickedata,$amountall,$percentall,$comisiondataamount,$comisiondatapercent,$columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tickedata as $key =>$value) {
              $ttlflat = 0;
                  $ptamounttotal = 0;
                  $explode_id = explode(',', $value->serviceid);
                  $pexplode_id = 0;
                  $servicedata = Service::select('servicename','price')
                    ->whereIn('services.id',$explode_id)->get();
                    if($value->product_id!=null || $value->product_id!="") {
                        $pexplode_id = explode(',', $value->product_id);
                    }
                  if($pexplode_id!=0) {
                    $pdata = Inventory::select('id','price')
                    ->whereIn('products.id',$pexplode_id)->get();
                  }
                    $ttlflat=0;
                    $ttlflat2 = 0;
                    if(count($amountall)>0) {
                        if($amountall[0]->allspvalue==null) {
                            foreach($explode_id as $servicekey =>$servicevalue) {
                                foreach($comisiondataamount->service as $key=>$sitem)
                              {
                                if($sitem->id==$servicevalue && $sitem->price!=0)
                                {
                                    $ttlflat2 += $sitem->price;
                                }

                              }
                            } 
                            $ttlflat1 = 0;
                            if($pexplode_id!=0) {
                                foreach($pexplode_id as $servicekey =>$servicevalue) {
                                    foreach($comisiondataamount->product as $key=>$sitem1)
                                    {
                                        if($sitem1->id==$servicevalue && $sitem1->price!=0)
                                        {
                                            $ttlflat1 += $sitem1->price;
                                        }
                                    }  
                                } 
                            } 
                             $ttlflat =$ttlflat1+$ttlflat2;
                        } else {
                            $flatvalue = $amountall[0]->allspvalue;
                            $flatv = $flatvalue*count($explode_id);
                            if($value->product_id!=null || $value->product_id!="") {
                                $pvalue = $flatvalue *count($pexplode_id);
                            } else {
                                $pvalue = 0;
                            }
                            $ttlflat = $flatv+$pvalue;
                        }
                    }
                    if(count($percentall)>0) {
                        $ptamount = 0;
                        $ptamount1 = 0;
                        if($percentall[0]->allspvalue==null) {
                            foreach($explode_id as $servicekey =>$servicevalue) {
                                foreach($comisiondatapercent->service as $key=>$sitem)
                                {
                                  if($sitem->id==$servicevalue && $sitem->price!=0)
                                  {
                                    $servicedata = App\Models\Service::select('servicename','price')
                                    ->where('services.id',$sitem->id)->first();

                                    $ptamount += $servicedata->price*$sitem->price/100;               
                                  }
                                }
                            } 
                          if($pexplode_id!=0) { 
                            foreach($pexplode_id as $key=>$pid) {
                                foreach($comisiondatapercent->product as $key=>$sitem)
                                {
                                  if($sitem->id==$pid && $sitem->price!=0)
                                  {
                                    $pdata = Inventory::select('id','price')->where('products.id',$sitem->id)->first();
                                    
                                    @$ptamount1 += @$pdata->price*@$sitem->price/100;               
                                  }
                                }
                            }
                         }
                        } else {
                            foreach($explode_id as $key=>$serviceid) {
                                $servicedata = Service::select('servicename','price')
                                ->where('services.id',$serviceid)->first();
                                $ptamount += $servicedata->price*$percentall[0]->allspvalue/100;               
                             }   
                            $ptamount1 = 0;
                            if($pexplode_id!=0) {
                                foreach($pexplode_id as $key=>$pid) {
                                    $pdata = Inventory::select('id','price')->where('products.id',$pid)->first();
                                    @$ptamount1 += @$pdata->price*@$percentall[0]->allspvalue/100;               
                                } 
                            }
                        }  
                        $ptamounttotal =$ptamount+$ptamount1;
                    }
                fputcsv($file, array($value->personnelname, $value->counttotal, @$ttlflat, @$ptamounttotal,@$ttlflat+@$ptamounttotal));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);    
    }
    
}
