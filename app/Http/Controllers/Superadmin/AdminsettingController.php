<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use DB;
use File;

class AdminsettingController extends Controller
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

        $userData = DB::table('users')->select('*')->where('role','superadmin')->first();

          
          return view('superadmin.setting',compact('auth_id','userData'));
    }

    public function update(Request $request)
    {
      $setting = User::where('role', 'superadmin')->get()->first();
      $setting->host = $request->host;
      $setting->smtpusername = $request->smtpusername;
      $setting->smtppassword = $request->smtppassword;
      $setting->currency = $request->currency;
      $setting->timezone = $request->timezone;
      $setting->featureprice = $request->featureprice;
      $setting->firebase = $request->firebase;
      $setting->save();

      // $name = "MAIL_HOST";
      // $value = $request->host;

      // $name1 = "MAIL_USERNAME";
      // $value1 = $request->smtpusername;

      // $name2 = "MAIL_PASSWORD";
      // $value2 = $request->smtppassword;

      // $path = base_path('.env');
      
      // file_put_contents($path, str_replace(
      //     $name . '=' . env($name), $name . '=' . $value, file_get_contents($path)
      // ));

      // file_put_contents($path, str_replace(
      //     $name1 . '=' . env($name1), $name1 . '=' . $value1, file_get_contents($path)
      // ));

      // file_put_contents($path, str_replace(
      //     $name2 . '=' . env($name2), $name2 . '=' . $value2, file_get_contents($path)
      // ));

      $request->session()->flash('success', 'Setting Updated successfully');
      return redirect()->route('superadmin.manageSetting');
    }
}
