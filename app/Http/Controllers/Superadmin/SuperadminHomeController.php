<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Workerhour;
use App\Models\User;
use DateTime;

class SuperadminHomeController extends Controller
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

        if(auth()->user()->role == 'superadmin') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $date = Carbon::now()->subDays(7);
  
        $users = User::where('created_at', '>=', $date)->get();

        if(count($users)>0) {
           $totalclientlast = count($users); 
        } else {
           $totalclientlast = 0;
        }

        $userstotal = User::select('*')->get();
        $totalclient = count($userstotal);

        $balancesheet = DB::table('balancesheet')->where('created_at', '>=', $date)->get();

        if(count($balancesheet)>0) {
           $totalbalancesheetlast = count($balancesheet); 
        } else {
           $totalbalancesheetlast = 0;
        }

        $balancesheettotal = DB::table('balancesheet')->get();
        $balancesheettotal = count($balancesheettotal);

        return view('superadmin.home',compact('auth_id','totalclient','totalclientlast','totalbalancesheetlast','balancesheettotal'));
    }
}
