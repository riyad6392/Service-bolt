<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Workertimeoff;
use DateTime;
use App\Events\MyEvent;
use App\Models\Notification;

class WorkerTimeoffController extends Controller
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

      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
      $todaydate = date('l - F d, Y');
      //$timeoff = DB::table('timeoff')->where('workerid',$worker->workerid)->get();

      $timeoff = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('COUNT(timeoff.id) as counttotal'))->where('timeoff.workerid',$worker->workerid)->groupBy('timeoff.created_at')->get();


      $timeoff1 = DB::table('timeoff')->where('workerid',$worker->workerid)->where('date', $todaydate)->first();
      return view('personnel.timeoff',compact('auth_id','timeoff','timeoff1'));
    }

    public function timeoffpto(Request $request) 
    {
       $auth_id = auth()->user()->id;
       $worker = DB::table('users')->select('workerid','userid')->where('id',$auth_id)->first();

       if($request->datepicker2!="") {
        $dates = explode(',',$request->datepicker2);
        if($request->notes) {
          $notes = $request->notes;
        } else {
          $notes = "";
        }
        foreach($dates as $key => $value) {
          $old_date_timestamp = strtotime($value);
          $fulldate = date('l - F d, Y', $old_date_timestamp);   
          //echo $fulldate; die;
          $data['workerid'] = $worker->workerid;
          $data['userid'] = $worker->userid;
          $data['date'] = $fulldate;
          $data['date1'] = $value;
          $data['notes'] = $notes;
          $data['submitted_by'] = "Personnel";
          //dd($data);
          Workertimeoff::create($data);
        }
        $workerinfo = DB::table('personnel')->select('personnelname')->where('id',$worker->workerid)->first();

        $personnelname = $workerinfo->personnelname;

        $ticketsub = "Leave requested by $personnelname";
        $data1['uid'] = $worker->userid;
        $data1['pid'] = $worker->workerid;
        $data1['message'] = $ticketsub;

        Notification::create($data1);
        event(new MyEvent($ticketsub));  

      }
      $request->session()->flash('success', 'PTO added successfully');
      return redirect()->back();
    }
}
