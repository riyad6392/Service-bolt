<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Workerhour;
use DateTime;
use App\Models\User;

class WorkerHomeController extends Controller
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
        if(auth()->user()->role == 'worker') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $userData = User::select('openingtime','closingtime')->where('id',$worker->userid)->first();
        $workername = DB::table('personnel')->select('personnelname','ticketid')->where('id',$worker->workerid)->first();

        $todayservicecall = DB::table('quote')->where('personnelid',$worker->workerid)->where('ticket_status','2')->whereDate('created_at', Carbon::today())->limit('2')->orderBy('id','DESC')->get();

        $customerData = DB::table('quote')->select('quote.*', 'customer.id','customer.phonenumber','customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',array('2','3'))->limit('2')->orderBy('quote.id','DESC')->get();

        $ticket = DB::table('quote')->select('product_id')->where('userid',$auth_id)->get();
        $inventoryinfo = DB::table('quote')
                 ->select('quote.product_id','quote.product_name', DB::raw('count(*) as total'),'products.pquantity')->join('products', 'products.id', '=', 'quote.product_id')->where('quote.userid',$auth_id)->orderBy('quote.product_id','DESC')
                 ->groupBy('quote.product_id')
                 ->get();
        $serviceinfo = DB::table('quote')
                 ->select('quote.serviceid','quote.servicename', DB::raw('count(*) as total'))->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.userid',$auth_id)->orderBy('quote.serviceid','DESC')
                 ->groupBy('quote.serviceid')
                 ->get();
        //dd($serviceinfo);

        $todaydate = date('l - F d, Y');
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();

        $completedticketcount = DB::table('quote')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',"3")->whereDate('created_at', Carbon::today())->count();
        
        $pendingticketcount = DB::table('quote')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',array('2','3'))->whereDate('created_at', Carbon::today())->count();
        
        if($pendingticketcount!=0) {
        $dailyprogress = $completedticketcount/$pendingticketcount*100;
        $dailyprogress =  round($dailyprogress,2);
        } else {
          $dailyprogress = 0;
        }

        $workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->first();
       /// $workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->first();
        //dd($workerh);
        return view('personnel.home',compact('auth_id','todayservicecall','ticket','customerData','inventoryinfo','scheduleData','dailyprogress','workerh','workername','userData'));
    }

    public function mapdata(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      $scheduleData = DB::table('quote')->select('quote.*', 'personnel.image','personnel.personnelname','personnel.latitude as lat','personnel.longitude as long')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->orderBy('quote.id','ASC')->get();
      $json = array();
      $data = [];
          foreach($scheduleData as $key => $value) {
            array_push($data, [$value->personnelname,$value->lat,$value->long,$value->longitude,$value->image,$value->id,$value->giventime]);

          }
      return json_encode(['html' =>$data]);
    }

    public function leftbarwdashboardschedulerdata(Request $request)
    {
      $fulldate =  $request->fulldate;
      //$auth_id = auth()->user()->id;
      $personnelid = $request->id;


        $worker = DB::table('users')->select('userid','workerid')->where('id',$personnelid)->first();
        //dd($worker);
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();
        $userData = User::select('openingtime','closingtime')->where('id',$worker->userid)->first();
      //dd($userData);
      $json = array();
      $countsdata = count($scheduleData);
      $datacount = $countsdata;
      $html = "";
      $html .='<div class="ev-arrow">
             <a class="ev-left" data-id="'.$personnelid.'"></a>
             <a class="ev-right" data-id="'.$personnelid.'"></a>
             </div>
             <div class="">';
      for($i=$userData->openingtime; $i<=$userData->closingtime; $i++) {
        $plusone = $i+1;
        $colon = ":00";
        if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
          $times = $i.":00";
          $html .='<ul>
    <li><div class="ev-calender-hours">'.strtoupper(date("h:i a", strtotime($times))).'</div></li>';
          foreach($scheduleData as $key => $value) {
              $f= $i+1;
              $m =   ":00";
              $settimes = date("h:i a", strtotime($times));

              $settimes1 = explode(":", $settimes);
              $start = $settimes[0];
              $endtime = explode(":",$value->giventime);
              $endtime = $endtime[1];
              $settimefinal =$settimes1[0].":".$endtime;
              if($value->givenendtime!="") { 
                $givntime = 'to '.$value->givenendtime;
                } else {
                $givntime = "";
              }
              if($value->giventime == $settimefinal) {
                $imagepath = url('/').'/uploads/customer/'.$value->image;
              $html .='<li class="inner yellow-slide" id="drop_'.$value->id.'">
                        <div class="card">
                          <div class="card-body">
                            <div class="imgslider" style="display:none;">
                              <img src="'.$imagepath.'" alt=""/>
                            </div>
                            <input type="hidden" name="customerid" id="customerid" value="'.$value->customerid.'">
                            <input type="hidden" name="quoteid" id="quoteid_'.$value->id.'" value="'.$value->id.'"><span>#'.$value->id.'</span>
                            <h5>'.$value->customername.'</h5><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"></a>
                            <p>'.$value->servicename.'</p>
                            <p>Time : '.$value->giventime.' '.$givntime.'</p>
                            <div class="grinding" style="display:block;"></a>';

                                $date=date_create($value->etc);
                                $dateetc = date_format($date,"F d, Y");
                            $html .=' '.$value->time.' '.$value->minute.' ETC : '.$dateetc.'
                            </div>
                          </div>
                        </div>
                      </li>';
              }
          }

      }
        $html .='</ul>
        </div>';
          return json_encode(['html' =>$html,'countsdata'=>$countsdata]);
    }

    public function workerclockhoursin(Request $request) {
      $auth_id = auth()->user()->id;
      //echo $auth_id; die;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
      
      $starttime = $request->starttime;
      $fulldate = $request->fulldate;
      $data['workerid'] = $worker->workerid;
      $data['starttime'] = $starttime;
      $data['date'] = $fulldate;


      Workerhour::create($data);
      echo 1;
    }

    public function workerclockhoursout(Request $request) {
      $auth_id = auth()->user()->id;
      //echo $auth_id; die;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
      
      $date1=date('Y-m-d');
      $workerhour = Workerhour::where('workerid', $worker->workerid)->where('date', $request->fulldate)->orderBy('id','desc')->get()->first();
      $starttime = $workerhour->starttime;
      $endtime = $request->endtime;

      $datetime1 = new DateTime($starttime);
      $datetime2 = new DateTime($endtime);
      $interval = $datetime1->diff($datetime2);
      $totalhours = $interval->format('%hh %im');

      $workerhour->endtime = $request->endtime;
      $workerhour->totalhours = $totalhours;
      $workerhour->date1 = $date1;
      $workerhour->save();
      echo 1;
    }
}
