<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Balancesheet;

class WorkerBalancesheetController extends Controller
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
     $balancesheet = Balancesheet::where('userid', $worker->userid)->where('workerid', $worker->workerid)->get();
     return view('personnel.balancesheet',compact('auth_id','balancesheet'));
    }
}
