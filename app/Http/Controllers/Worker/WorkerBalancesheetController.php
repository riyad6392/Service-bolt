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

    public function balancesheetfilter(Request $request) 
    {

      $auth_id = auth()->user()->id;
     
      if(auth()->user()->role == 'worker') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $balancesheet = Balancesheet::where('userid', $worker->userid)->where('workerid', $worker->workerid)->orderBy('id','desc')->get();

      $fileName = date('d-m-Y').'_order.csv';
      $headers = array(
          "Content-type"        => "text/csv",
          "Content-Disposition" => "attachment; filename=$fileName",
          "Pragma"              => "no-cache",
          "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
          "Expires"             => "0"
      );
      $columns = array('Transaction#','TicketId','Date','Amount','Payment Method', 'Customer');

      $callback = function() use($balancesheet, $columns) {
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns);

      foreach ($balancesheet as $key =>$value) {
            
            $newdate  = date("M, d Y", strtotime($value->created_at));
            fputcsv($file, array($value->id, $value->ticketid, $newdate, $value->amount,$value->paymentmethod, $value->customername));
      }
        fclose($file);
    };
    return response()->stream($callback, 200, $headers);
      // foreach($balancesheet as $key =>$value) {

      // }
      // return view('personnel.balancesheet',compact('auth_id','balancesheet'));
    }
}
