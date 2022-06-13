<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Support\Str;
use Mail;
use Carbon\Carbon; 
use DB;
use Illuminate\Support\Facades\Session;

class ForgotController extends Controller
{
    public function showForgetPasswordForm()
    {

      return view('auth.forgetPassword');
    }

    public function submitForgetPasswordForm(Request $request)
    {
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);
          
        $user = User::where('email', $request->email)->where('role', 'worker')->first();  
        if ($user) {
          $token = Str::random(64);
  
          DB::table('password_resets')->insert([
              'email' => $request->email, 
              'token' => $token, 
              'created_at' => Carbon::now()
            ]);
  
          Mail::send('mail_templates.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password');
          });
          Session::flash('message', 'We have e-mailed your password reset link!'); 
          Session::flash('alert-class', 'alert-success');
          return redirect()->route('personnellogin.forget.get');  
          //return back()->with('message', 'We have e-mailed your password reset link!');
        } else {
            Session::flash('message', "This Email Address does not exists!");
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('personnellogin.forget.get');

        }
    }

    /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token) { 
         return view('auth.forgetPasswordLink', ['token' => $token]);
      }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);
        
        $user = User::where('email', $request->email)->where('role', 'worker')->first();  
        if ($user) {

          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword) {
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password),'wpassword' => $request->password]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
          
          return redirect('/personnel/login')->with('success', 'Your password has been changed!');
      } else {

      }
  }
}