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

      $timeoff = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('GROUP_CONCAT(timeoff.date1 ORDER BY timeoff.date1) AS selectdates'),DB::raw('COUNT(timeoff.id) as counttotal'))->where('timeoff.workerid',$worker->workerid)->groupBy('timeoff.created_at')->orderBy('timeoff.id','desc')->get();


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

    public function edittimeoff(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $html ='<div class="add-customer-modal">
                  <h5>PTO</h5>
        <p>Choose multiple dates needed off</p>
                </div>';
       $html .='<div class="col-md-12">
        <input type="text" id="datepicker3" name="datepicker3" placeholder="Choose Date" style="cursor: pointer;" class="mb-3 form-control" value="'.$request->dates.'" required></div><input type="hidden" name="ids" id="ids" value="'.$request->id.'">
        <div id="datepicker3" class="mb-3"></div>
      <div class="time-note mb-2">
        <textarea class="form-control mb-4" placeholder="Notes" name="notes" id="notes" required>'.$request->notes.'</textarea>
      </div>
    <div class="row">
      <div class="col-lg-6 mb-3">
         <a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</a>
      </div>
      <div class="col-lg-6 mb-3">
         <button class="btn btn-add btn-block">Submit Request</button>
      </div>
      </div>';
        return json_encode(['html' =>$html]);
        die;
    }

    public function updatetimeoff(Request $request)
    {
      $ids = explode(",",$request->ids);
      $timeoff = Workertimeoff::whereIn('id',$ids)->where('workerid',auth()->user()->workerid)->delete(); 

      $dates = explode(',',$request->datepicker3);
      $notes = $request->notes;
      foreach($dates as $key => $value) {
          $old_date_timestamp = strtotime($value);
          $fulldate = date('l - F d, Y', $old_date_timestamp);   
          $data['workerid'] = auth()->user()->workerid;
          $data['userid'] = auth()->user()->userid;
          $data['date'] = $fulldate;
          $data['date1'] = $value;
          $data['notes'] = $notes;
          Workertimeoff::create($data);
        }

      $request->session()->flash('success', 'PTO updated successfully');
      return redirect()->back();
    }
}
