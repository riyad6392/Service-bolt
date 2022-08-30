<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Address;
use App\Models\Personnel;
use App\Models\Quote;
use App\Models\Managefield;
use App\Models\Workertimeoff;
use Mail;
use Illuminate\Support\Str;
use DB;
use Image;

  class TimeoffController extends Controller
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
     
      $days = $request->get('f');
      $dateS = Carbon::now()->subDay($days);
      $dateE = Carbon::now();
      $todaydate = date('l - F d, Y');
      $currentdate = Carbon::now();
      $currentdate = date('Y-m-d', strtotime($currentdate));
      
      $personnelUser = Personnel::select('*')->where('userid',$auth_id)->orderBy('id','DESC')->get();
      if(count($personnelUser)>0) {
        $pid =  $personnelUser[0]->id;
      } else {
        $pid =  "";
      }
      

      $pdata = DB::table('personnel')->select('personnelname')->where('id',$pid)->first();
      if($pdata!=null) {
        $name = $pdata->personnelname;
      } else {
        $name = "";
      }
      //$stimesheetData  = DB::table('timeoff')->where('workerid',$pid)->get();
      //$stimesheetData  = DB::table('timeoff')->where('userid',$auth_id)->get();
      $stimesheetData = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('COUNT(timeoff.id) as counttotal'),'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->groupBy('timeoff.created_at')->get();

      //$stimesheetData  = DB::table('timeoff')->select('timeoff.*','GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids',DB::raw('COUNT(timeoff.id) as counttotal'),'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->groupBy('timeoff.created_at')->get();
      //dd($stimesheetData);
      return view('timeoff.index',compact('auth_id','personnelUser','currentdate','name','stimesheetData'));
    }

    public function searchtimeoff(Request $request) {
     //dd($request->all());
     if($request->pid == 'all') {
      $auth_id = auth()->user()->id;
      $personnelid = "";
      $name = "";

      if($request->phiddenid!="") {
       //$stimesheetData  = DB::table('timeoff')->select('timeoff.*', 'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->get();

       $stimesheetData = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('COUNT(timeoff.id) as counttotal'),'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->groupBy('timeoff.created_at')->get();

      } else {
        //$stimesheetData  = DB::table('timeoff')->select('timeoff.*', 'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->whereBetween('date1', [$request->since, $request->until])->get();
        $stimesheetData = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('COUNT(timeoff.id) as counttotal'),'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->groupBy('timeoff.created_at')->whereBetween('timeoff.date1', [$request->since, $request->until])->get();
      }
      $from = $request->since;
        $to = $request->until;
      $personnelUser = Personnel::select('*')->where('userid',$auth_id)->orderBy('id','DESC')->get(); 
      $currentdate = Carbon::now();
      $currentdate = date('Y-m-d', strtotime($currentdate));
    } elseif($request->phiddenid==null) {

        $auth_id = auth()->user()->id;
        $personnelid = $request->pid;
        $pdata = DB::table('personnel')->select('personnelname','image')->where('id',$personnelid)->first();
        if(isset($pdata)) {
          $name = $pdata->personnelname;
        }
        
        //$stimesheetData  = DB::table('timeoff')->select('timeoff.*', 'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->where('timeoff.workerid',$personnelid)->whereBetween('date1', [$request->since, $request->until])->get();

        $stimesheetData = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('COUNT(timeoff.id) as counttotal'),'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.userid',$auth_id)->where('timeoff.workerid',$personnelid)->groupBy('timeoff.created_at')->whereBetween('timeoff.date1', [$request->since, $request->until])->get();

        $from = $request->since;
        $to = $request->until;
        $personnelUser = Personnel::select('*')->orderBy('id','DESC')->get();
        $currentdate = Carbon::now();
        $currentdate = date('Y-m-d', strtotime($currentdate));
    } else {
      $auth_id = auth()->user()->id;
      $personnelid = $request->pid;
      $pdata = DB::table('personnel')->select('personnelname','image')->where('id',$request->phiddenid)->first();
      if(isset($pdata)) {
        $name = $pdata->personnelname;
      }
     
      //$stimesheetData  = DB::table('timeoff')->select('timeoff.*', 'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.workerid',$request->phiddenid)->get();

      //$stimesheetData  = DB::table('timeoff')->select('timeoff.*', 'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.workerid',$request->phiddenid)->get();

      $stimesheetData = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('COUNT(timeoff.id) as counttotal'),'personnel.personnelname','personnel.id as pid')->join('personnel', 'personnel.id', '=', 'timeoff.workerid')->where('timeoff.workerid',$request->phiddenid)->groupBy('timeoff.created_at')->get();

      $from = $request->since;
      $to = $request->until;
      $personnelUser = Personnel::select('*')->orderBy('id','DESC')->get();
      $currentdate = Carbon::now();
      $currentdate = date('Y-m-d', strtotime($currentdate));
    }
      return view('timeoff.index',compact('auth_id','stimesheetData','from','to','personnelUser','currentdate','name','personnelid'));
    }

    public function timeoffpto(Request $request) {
      
      $auth_id = auth()->user()->id;

      $workerid = $request->personnelid;

      if($request->datepicker2!=null) {
        $dates = explode(',',$request->datepicker2);
        if($request->notes) {
          $notes = $request->notes;
        } else {
          $notes = "";
        }
        foreach($dates as $key => $value) {
          $old_date_timestamp = strtotime($value);
          $fulldate = date('l - F d, Y', $old_date_timestamp);   
          $data['workerid'] = $workerid;
          $data['userid'] = $auth_id;
          $data['date'] = $fulldate;
          $data['date1'] = $value;
          $data['notes'] = $notes;
          $data['submitted_by'] = "Self";
          Workertimeoff::create($data);
        }
      }

      //$fulldate = $request->dateval;
      
      // $workertimeoff = Workertimeoff::where('workerid',$workerid)->where('date', $fulldate)->first();
      // if(!empty($workertimeoff)) {
      //   $workertimeoff->workerid = $workerid;
      //   $workertimeoff->userid = $auth_id;
      //   $workertimeoff->date = $fulldate;
      //   $workertimeoff->notes = $notes;
      //   $workertimeoff->date1 = $request->leavedate;
      //   $workertimeoff->save();
      // } else {
      //   $data['workerid'] = $workerid;
      //   $data['userid'] = $auth_id;
      //   $data['date'] = $fulldate;
      //   $data['date1'] = $request->leavedate;
      //   $data['notes'] = $notes;
      //   Workertimeoff::create($data);
      // } 
      $request->session()->flash('success', 'PTO added successfully');
      return redirect()->back();
    }
  }
