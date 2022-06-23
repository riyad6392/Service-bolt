<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use DateTime;
use App\Models\Schedulerhours;
use App\Models\Workerhour;
use App\Models\Workersethour;
use App\Models\Workertimeoff;
use App\Models\User;

class WorkerSchedulerController extends Controller
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
      @$serviceids = DB::table('quote')->select('serviceid','customerid')->where('personnelid',$worker->workerid)->get();
if(count($serviceids)>0) {
      foreach($serviceids as $key => $value) {
          @$serviceidss[] = $value->serviceid;
      }

      foreach($serviceids as $key => $value) {
          $customerids[] = $value->customerid;
      }


      $servicedetails = DB::table('quote')->whereIn('serviceid',$serviceidss)->whereIn('customerid',$customerids)->get();
    } else {
      $servicedetails = array();
    }
      $todaydate = date('l - F d, Y');
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();
      
      //$workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date', $todaydate)->first();
      $workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->first();
      $timeoff = DB::table('timeoff')->where('workerid',$worker->workerid)->where('date', $todaydate)->first();
      
      return view('personnel.myscheduler',compact('auth_id','servicedetails','scheduleData','workerh','timeoff','userData'));
    }

    public function leftbarschedulerdata(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      $userData = User::select('openingtime','closingtime')->where('id',$worker->userid)->first();
      $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();
      $json = array();
      $countsdata = count($scheduleData);
      $datacount = $countsdata;
      $html = "";
      for($i=$userData->openingtime; $i<=$userData->closingtime; $i++) {
        $plusone = $i+1;
        $colon = ":00";
        if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
          $times = $i.":00";
          $html .='<li><div class="ev-calender-hours">'.strtoupper(date("h:i a", strtotime($times))).'</span></div></li>';
          foreach($scheduleData as $key => $value) {
              $f= $i+1;
              $m =   ":00";
              // echo $value->giventime;
              // echo "space";
              // echo $f.$m .' '.$ampm;
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
                            <h5>'.$value->customername.'</h5>
                            <p>'.$value->servicename.'</p>
                            <p>Time : '.$value->giventime.' '.$givntime.'</p>
                            <div class="grinding" style="display:block;">
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
      return json_encode(['html' =>$html,'countsdata'=>$countsdata]);
       
    }

    public function updatehours(Request $request) {
      //dd($request->all());
      $fulldate = $request->dateval;
      $date1 =$request->date1;
      $timepicker = $request->timepicker;
      $timepicker1 = $request->timepicker1;
      
      $datetime1 = new DateTime($timepicker);
      $datetime2 = new DateTime($timepicker1);
      $interval = $datetime1->diff($datetime2);
      $totalhours = $interval->format('%hh %im');
      
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

      //$workerh = Workerhour::where('workerid',$worker->workerid)->where('date', $fulldate)->orderBy('id','desc')->first();

      // if(!empty($workerh)) {
      //   $workerh->workerid = $worker->workerid;
      //   $workerh->starttime = $timepicker;
      //   $workerh->endtime = $timepicker1;
      //   $workerh->date = $fulldate;
      //   $workerh->date1 = $request->date1;
      //   $workerh->totalhours = $totalhours;
      //   $workerh->save();
      // } else {
        $data['workerid'] = $worker->workerid;
        $data['starttime'] = $timepicker;
        $data['endtime'] = $timepicker1;
        $data['date'] = $fulldate;
        $data['date1'] = $date1;
        $data['totalhours'] = $totalhours;

      Workerhour::create($data);
    //}
      /*$request->session()->flash('success', 'Ticket Pickup successfully');*/
      return redirect()->route('worker.scheduler');
    }

    public function updatesethours(Request $request) {
      
      $fulldate = $request->dateval;
      
      $timepicker = $request->timepickernew;
      $timepicker1 = $request->timepickernew1;
      
      $datetime1 = new DateTime($timepicker);
      $datetime2 = new DateTime($timepicker1);
      $interval = $datetime1->diff($datetime2);
      $totalhours = $interval->format('%hh %im');
      
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

        $data['workerid'] = $worker->workerid;
        $data['starttime'] = $timepicker;
        $data['endtime'] = $timepicker1;
        $data['date'] = $fulldate;
        $data['date1'] = $request->datenew1;
        $data['totalhours'] = $totalhours;
        Workersethour::create($data);

        return redirect()->route('worker.scheduler');
    }

    public function timeoff(Request $request) {
      $pto = "pto";
      return redirect()->route('worker.timeoff');
      return view('personnel.timeoff',compact('pto'));

      $fulldate = $request->dateval;
      if($request->notes){
        $notes = $request->notes;
      } else {
        $notes = "";
      }
      $auth_id = auth()->user()->id;
      
      $worker = DB::table('users')->select('workerid','userid')->where('id',$auth_id)->first();

      $workertimeoff = Workertimeoff::where('workerid',$worker->workerid)->where('date', $fulldate)->first();
      if(!empty($workertimeoff)) {
        $workertimeoff->workerid = $worker->workerid;
        $workertimeoff->userid = $worker->userid;
        $workertimeoff->date = $fulldate;
        $workertimeoff->notes = $notes;
        $workertimeoff->date1 = $request->leavedate;
        $workertimeoff->save();
      } else {
        $data['workerid'] = $worker->workerid;
        $data['userid'] = $worker->userid;
        $data['date'] = $fulldate;
        $data['date1'] = $request->leavedate;
        $data['notes'] = $notes;
        Workertimeoff::create($data);
      } 
      /*$request->session()->flash('success', 'Ticket Pickup successfully');*/
      return redirect()->route('worker.scheduler');
    }

    public function gettimedata(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

      $workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date', $fulldate)->orderBy('id','desc')->first();
      $json = array();
      if(!empty($workerh)) {
        $starttime = $workerh->starttime;
        $endtime = $workerh->endtime;
      } else {
        $starttime = "";
        $endtime = "";
      }
      $date1 =  $request->date1;
     
     return json_encode(['starttime' =>$starttime,'endtime'=>$endtime,'date1'=>$date1]);
       
    }

    public function gethourdata(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

      $workerh = DB::table('sethours')->where('workerid',$worker->workerid)->where('date', $fulldate)->orderBy('id','desc')->first();
      $json = array();
      if(!empty($workerh)) {
        $starttime = $workerh->starttime;
        $endtime = $workerh->endtime;
      } else {
        $starttime = "";
        $endtime = "";
      }
      $date1 =  $request->date1;
     
     return json_encode(['starttime' =>$starttime,'endtime'=>$endtime,'date1'=>$date1]);
       
    }

    public function getleavenote(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

      $timeoff = DB::table('timeoff')->where('workerid',$worker->workerid)->where('date', $fulldate)->first();

      $json = array();
      if(!empty($timeoff)) {
        $notes = $timeoff->notes;
      } else {
        $notes = "";
      }
      $leavedate = $request->leavedate;
     
     return json_encode(['notes' =>$notes, 'leavedate' =>$leavedate]);
       
    }
}
