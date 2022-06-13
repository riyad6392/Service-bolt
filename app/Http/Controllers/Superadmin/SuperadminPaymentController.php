<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use DB;

class SuperadminPaymentController extends Controller
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

        // $paymentData = DB::table('quote')->select('quote.id','quote.invoiceid','quote.price','quote.givendate','quote.payment_status', 'customer.customername', 'customer.email','users.companyname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('users', 'users.id', '=', 'quote.userid')->where('quote.invoiceid','!=',null)->where('quote.ticket_status',"3")->orderBy('quote.id','desc')->get();
        $currentdate = Carbon::now();
        $currentdate = date('Y-m-d', strtotime($currentdate));

        if(isset($_REQUEST['since']) && isset($_REQUEST['until'])) {
            $since = $_REQUEST['since'];
            $until = $_REQUEST['until'];
            $paymentData = User::where('role','company')->whereBetween(DB::raw('DATE(created_at)'), [$since, $until])->get();
        } else {
            $since = "";
            $until = "";
            $paymentData = User::where('role','company')->get();
        }

        
          return view('superadmin.payment',compact('auth_id','paymentData','currentdate','since','until'));
    }

    public function subscriptionstatus(Request $request) {
      $users = User::where('id', $request->userid)->first();
      if($request->status == "Paid") {
        $users->amount = "99";
      } else {
        $users->amount = null;
      }
        $users->save();
        echo "1";
    }
}
