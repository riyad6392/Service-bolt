<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Cache;
use App\Models\Inventory;
use App\Events\SendLocation;

class HomeController extends Controller
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
        if(auth()->user()->status == "InActive") {
             Auth::logout();
            return redirect('login'); 
        }
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $days = $request->get('f');
        $dateS = Carbon::now()->subDay($days);
        $dateE = Carbon::now();
        $ticket = DB::table('quote')->where('userid',$auth_id)->where('parentid', '=',"")->where('ticket_status', '!=' ,'0')->where('ticket_status', '!=' ,'5')->limit('3')->orderBy('id','DESC')->get();
        
        $inventoryData = Inventory::where('user_id',$auth_id)->inRandomOrder()->get();

        $usersaddress = DB::table('users')->select('latitude','longitude','company_address','goodproduct','lowproduct')->where('id',$auth_id)->first();

        $goodproduct =  array();
        $lowproduct =  array();
        $restockproduct =  array();
        foreach($inventoryData as $key =>$value) {
            if($usersaddress->goodproduct!=null) {
                $fifypercent = $value->pquantity*$usersaddress->goodproduct/100;
            } else {
                $fifypercent = $value->pquantity*50/100;
            }
            
            if($usersaddress->lowproduct!=null) {
                $twentyfivepercent = $value->pquantity*$usersaddress->lowproduct/100;
            } else {
                $twentyfivepercent = $value->pquantity*25/100;
            }
            
            if($value->quantity>=$fifypercent) {
                $goodproduct[] = $value->productname;
            }
            elseif($value->quantity>=$twentyfivepercent) {
                $lowproduct[] = $value->productname;
            }
            elseif($value->quantity<=$twentyfivepercent) {
                $restockproduct[] = $value->productname;
            }
        }

        $customerData = DB::table('quote')->select('quote.*', 'customer.id','customer.phonenumber','customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status', '!=' ,'0')->where('quote.ticket_status', '!=' ,'1')->limit('2')->orderBy('quote.id','DESC')->get();
        $inventoryinfo = DB::table('quote')
                 ->select('quote.product_id','quote.product_name', DB::raw('count(*) as total'),'products.pquantity','products.quantity')->join('products', 'products.id', '=', 'quote.product_id')->where('quote.userid',$auth_id)->where('quote.product_name', '!=',"")->limit(3)->orderBy('quote.product_id','DESC')
                 ->groupBy('quote.product_name')->get();
        $serviceinfo = DB::table('quote')
                 ->select('quote.serviceid','services.servicename','services.color', DB::raw('count(*) as total'))->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.userid',$auth_id)->limit(4)
                 ->orderBy('total','DESC')
                 ->groupBy('quote.servicename')
                 ->get();
        $scheduleData = DB::table('quote')->select('quote.*', 'personnel.image','personnel.personnelname','personnel.latitude as lat','personnel.longitude as long')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('personnel.livelat','!=',null)->where('personnel.livelong','!=',null)->where('quote.ticket_status','4')->orderBy('quote.id','ASC')->get();

        $completedticketcount = DB::table('quote')->where('quote.userid',$auth_id)->where('quote.ticket_status',"3")->whereDate('quote.created_at', Carbon::today())->count();
        $pendingticketcount = DB::table('quote')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->whereDate('quote.created_at', Carbon::today())->count();
        //dd($completedticketcount);
        if($pendingticketcount!=0) {
            $dailyprogress = $completedticketcount/$pendingticketcount*100;
            $dailyprogress =  round($dailyprogress,2);
        } else {
          $dailyprogress = 0;
        }
        
        return view('company.home',compact('auth_id','ticket','customerData','inventoryinfo','scheduleData','serviceinfo','dailyprogress','goodproduct','lowproduct','restockproduct','inventoryData','usersaddress'));
    }

    public function index1(Request $request)
    {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $days = $request->get('f');
        $dateS = Carbon::now()->subDay($days);
        $dateE = Carbon::now();
        $ticket = DB::table('quote')->where('userid',$auth_id)->where('ticket_status', '!=' ,'0')->limit('3')->orderBy('id','DESC')->get();
        
        $inventoryData = Inventory::where('user_id',$auth_id)->inRandomOrder()->get();
        $usersaddress = DB::table('users')->select('latitude','longitude','company_address','goodproduct','lowproduct')->where('id',$auth_id)->first();
        $goodproduct =  array();
        $lowproduct =  array();
        $restockproduct =  array();
        foreach($inventoryData as $key =>$value) {
            if($usersaddress->goodproduct!=null) {
                $fifypercent = $value->pquantity*$usersaddress->goodproduct/100;
            } else {
                $fifypercent = $value->pquantity*50/100;
            }
            
            if($usersaddress->lowproduct!=null) {
                $twentyfivepercent = $value->pquantity*$usersaddress->lowproduct/100;
            } else {
                $twentyfivepercent = $value->pquantity*25/100;
            }
            
            if($value->quantity>=$fifypercent) {
                $goodproduct[] = $value->productname;
            }
            elseif($value->quantity>=$twentyfivepercent) {
                $lowproduct[] = $value->productname;
            }
            elseif($value->quantity<=$twentyfivepercent) {
                $restockproduct[] = $value->productname;
            }
        }

        $customerData = DB::table('quote')->select('quote.*', 'customer.id','customer.phonenumber','customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status', '!=' ,'0')->where('quote.ticket_status', '!=' ,'1')->limit('2')->orderBy('quote.id','DESC')->get();

        //$ticket = DB::table('quote')->select('product_id')->where('userid',$auth_id)->get();
        $inventoryinfo = DB::table('quote')
                 ->select('quote.product_id','quote.product_name', DB::raw('count(*) as total'),'products.pquantity','products.quantity')->join('products', 'products.id', '=', 'quote.product_id')->where('quote.userid',$auth_id)->limit(3)->orderBy('quote.product_id','DESC')
                 ->groupBy('quote.product_name')
                 ->get();     
        $serviceinfo = DB::table('quote')
                 ->select('quote.serviceid','services.servicename','services.color', DB::raw('count(*) as total'))->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.userid',$auth_id)->limit(4)
                 ->orderBy('total','DESC')
                 ->groupBy('quote.serviceid')
                 ->get();
         $scheduleData = DB::table('quote')->select('quote.*', 'personnel.image','personnel.personnelname','personnel.latitude as lat','personnel.longitude as long')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('personnel.livelat','!=',null)->where('personnel.livelong','!=',null)->where('quote.ticket_status','4')->orderBy('quote.id','ASC')->get();

        $completedticketcount = DB::table('quote')->where('quote.userid',$auth_id)->where('quote.ticket_status',"3")->whereDate('quote.created_at', Carbon::today())->count();
        $pendingticketcount = DB::table('quote')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',array('2','3','4'))->whereDate('quote.created_at', Carbon::today())->count();
        //dd($completedticketcount);
        if($pendingticketcount!=0) {
            $dailyprogress = $completedticketcount/$pendingticketcount*100;
            $dailyprogress =  round($dailyprogress,2);
        } else {
          $dailyprogress = 0;
        }
        
         return view('company.home',compact('auth_id','ticket','customerData','inventoryinfo','scheduleData','serviceinfo','dailyprogress','goodproduct','lowproduct','restockproduct','inventoryData','usersaddress'));
    }

    public function index2(Request $request) {
        return view('company.scheduler');
    }

    public function mapdata(Request $request)
    {
      $auth_id = auth()->user()->id;
      $users = DB::table('users')->where('role','worker')->where('userid',$auth_id)->get();
        $workerids = array();
        foreach ($users as $user) {
            $workerids[] = $user->workerid;

           //  if (Cache::has('user-is-online-' . $user->id)) {
           //      $workerids[] = $user->workerid;
                
           //      DB::table('personnel')->where('id','=',$user->workerid)
           //        ->update([ 
           //            "checkstatus"=>"online"
           //      ]);
           //  } else {
           //      $workerids[] = $user->workerid;
           //      DB::table('personnel')->where('id','=',$user->workerid)
           //        ->update([ 
           //            "checkstatus"=>"offline"
           //      ]);
           // }
        } 
    //DB::enableQuerylog();
    //$scheduleData = DB::table('quote')->select('quote.*', 'personnel.image','personnel.personnelname','personnel.livelat as lat','personnel.livelong as long','personnel.checkstatus')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->whereIn('personnel.id',$workerids)->where('personnel.livelat','!=',null)->where('personnel.livelong','!=',null)->whereIn('ticket_status',array('2','3','4'))->groupBy('quote.personnelid')->orderBy('quote.id','desc')->get();

   // $scheduleData = DB::table('personnel')->select('quote.*', 'personnel.image','personnel.personnelname','personnel.livelat as lat','personnel.livelong as long','personnel.checkstatus')->leftJoin('quote', 'quote.personnelid', '=', 'personnel.id')->whereIn('personnel.id',$workerids)->where('personnel.livelat','!=',null)->where('personnel.livelong','!=',null)->groupBy('quote.id')->orderBy('quote.updated_at','desc')->get();

    $scheduleData = DB::table('personnel')->select('quote.*', 'personnel.image','personnel.personnelname','personnel.livelat as lat','personnel.livelong as long','personnel.checkstatus')->leftJoin('quote', 'quote.personnelid', '=', 'personnel.id')->whereIn('quote.ticket_status',array(2,4))->whereIn('personnel.id',$workerids)->where('personnel.livelat','!=',null)->where('personnel.livelong','!=',null)->orderBy('quote.updated_at','asc')->get();



    //dd(DB::getQueryLog());
    //dd($scheduleData);
      $json = array();
      $data = [];
        foreach($scheduleData as $key => $value) {
            array_push($data, [$value->personnelname,$value->lat,$value->long,$value->longitude,$value->image,$value->id,$value->giventime,$value->checkstatus,$value->ticket_status]);
        }
        //dd($data);
      return json_encode(['html' =>$data]);
    }

    public function payment(Request $request) {
        return view('company.payment');
    }

    public function aggrement(Request $request) {
        return view('company.aggrement');
    }

    public function agreements(Request $request) {
        return view('company.agreements');
    }
}
