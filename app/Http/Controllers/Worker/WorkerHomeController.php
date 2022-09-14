<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Workerhour;
use DateTime;
use App\Models\User;
use App\Models\AppNotification;
use App\Models\Personnel;

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

        $cdate = Carbon::now();

        $ctime =  date_format($cdate, 'g:i a');
        
        $times = strtotime($ctime);
        
        $middle = strtotime($cdate);
        
        $new_date = date('l - F d, Y', $middle); 
        

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $userData = User::select('openingtime','closingtime')->where('id',$worker->userid)->first();
        $workername = DB::table('personnel')->select('personnelname','ticketid')->where('id',$worker->workerid)->first();

        $todayservicecall = DB::table('quote')->where('personnelid',$worker->workerid)->whereIn('ticket_status',[2,3,4])->where('givendate', $new_date)->limit('2')->orderBy('id','DESC')->get();

        if(count($todayservicecall)>0) {
          foreach($todayservicecall as $key => $value) {

            $tickettime= strtotime($value->giventime);
            //DB::enableQuerylog();
             $tickettimeg= DB::table('quote')->where('personnelid',$worker->workerid)->where('ticket_status','2')->where('givendate', $new_date)->whereRaw("time_to_sec('giventime')<={$times}")->where('ticket_status','2')->get();
             //dd(DB::getQuerylog()); die;
             //dd($tickettimeg[0]);
            if(count($tickettimeg)>0) {
              $ticketid = $tickettimeg[0]->id;
              if(!empty($tickettimeg[0]->parentid))
              {
                $ticketid=$tickettimeg[0]->parentid;
              }
              $notification = new AppNotification;
              $notification->uid = $auth_id;
              $notification->pid = $worker->workerid;
              $notification->ticketid = $ticketid;
              $notification->message =  "Your ticket #" .$ticketid. " have not picked it up yet";
              $notification->save();

              $puser = Personnel::select('device_token')->where("id", $worker->workerid)->first();

              $msgarray = array (
                  'title' => 'Ticket have not picked it up yet',
                  'msg' => "Your ticket #" .$ticketid. " have not picked it up yet",
                  'type' => 'ticketnotpickedyet',
              );

              $fcmData = array(
                  'message' => $msgarray['msg'],
                  'body' => $msgarray['title'],
              );

              $this->sendFirebaseNotification($puser, $msgarray, $fcmData); 
             
            }
            
          }
        }

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
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname','services.color')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->join('services', 'services.servicename', '=', 'quote.servicename')->whereIn('quote.ticket_status',[2,3,4])->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();

        $completedticketcount = DB::table('quote')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',"3")->where('givendate', $todaydate)->count();
        
        $pendingticketcount = DB::table('quote')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',array('2','3'))->where('givendate', $todaydate)->count();
        
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
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname','services.color')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->join('services', 'services.servicename', '=', 'quote.servicename')->whereIn('quote.ticket_status',[2,3,4])->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();
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

              $gtime = explode(":",$value->giventime);
              $gtimeampm = explode(" ",$gtime[1]);

              $giventime = $gtime[0].':00'.' '.$gtimeampm[1];
              
              if($value->givenendtime!="") { 
                $givntime = 'to '.$value->givenendtime;
                } else {
                $givntime = "";
              }
              if($giventime == $settimes) {
                $imagepath = url('/').'/uploads/customer/'.$value->image;
              $html .='<li class="inner yellow-slide" id="drop_'.$value->id.'">
                        <div class="card">
                          <div class="card-body" style="background-color:'.$value->color.'; border-radius: 12px;">
                            <div class="imgslider" style="display:none;">
                              <img src="'.$imagepath.'" alt=""/>
                            </div>
                            <input type="hidden" name="customerid" id="customerid" value="'.$value->customerid.'">
                            <input type="hidden" name="quoteid" id="quoteid_'.$value->id.'" value="'.$value->id.'"><span style="color: #fff;">#'.$value->id.'</span>
                            <h5 style="color: #fff;">'.$value->customername.'</h5><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"></a>
                            <p>'.$value->servicename.'</p>
                            <p>Time : '.$value->giventime.' '.$givntime.'</p>
                            <div class="grinding" style="display:block;color: #fff;">
                              <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="5" cy="5" r="5" fill="currentColor" style="display:none;">
                              </svg>'.$value->time.' '.$value->minute.'</a>
                              <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="5" cy="5" r="5" fill="currentColor" style="display:none;">
                              </svg>'; 
                                $date=date_create($value->etc);
                                $dateetc = date_format($date,"F d, Y");
                            $html .='ETC : '.$dateetc.'</a>
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

    public function sendFirebaseNotification($puser, $msgarray, $fcmData) 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fcmApiKey = "AAAARQKM8JU:APA91bGX3j2-L9qPoU7PhhTrxIZzjUDDUa8XFkyMsyYHUVr8uqC5yHofDtQR73vlmUarnbQDAexn2TQVRGlWhf99gVkD8UcCvSIX_1DcqX5ZdLy8xu3JOfAMgJmN3Zl6NZ-H3WBKXJDl";

        $fcmMsg = array(
            'title' => $msgarray['title'],
            'text' => $msgarray['msg'],
            'type' => $msgarray['type'],
            'vibrate' => 1,
            "date_time" => date("Y-m-d H:i:s"),
            'message' => $msgarray['msg'],
        );

        $fcmFields = array(
            'to' => $puser->device_token,
            'priority' => 'high',
            'notification' => $fcmMsg,
            'data' => $fcmMsg,
        );

        $headers = array(
        'Authorization: key=' . $fcmApiKey,
        'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);

        if ($result === false) {

        }
        curl_close($ch);
        return $result;
    }
}
