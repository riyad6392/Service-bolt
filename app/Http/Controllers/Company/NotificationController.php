<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Personnel;
use App\Models\Quote;
use App\Models\Notification;
use Mail;
use Illuminate\Support\Str;
use DB;

  class NotificationController extends Controller
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

    public function index(Request $request)
    {
      $auth_id = auth()->user()->id;
      if(auth()->user()->role == 'company') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }
      
      $notificationlist = Notification::where('uid',$auth_id)->orderBy('id','DESC')->get();
      
      return view('notification.index',compact('auth_id','notificationlist'));
    }

    public function deletenotification(Request $request) {
      $id = $request->id;
      DB::table('notification')->delete($id);
      echo "1";
    }
  }
