<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Auth;
use DB;

class WorkerChangepasswordController extends Controller
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
        
      if(auth()->user()->role == 'worker') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }
      
        return view('personnel.changepassword');
    }

    public function update(Request $request, $id = null) {
        
        $validateData = $request->validate([
            'new_password' => 'min:5|different:old_password|required_with:password_confirmation|same:change_password',
            'change_password' => 'min:5'
        ]);

        if($validateData) {
            $auth_id = auth()->user()->id;
            $worker = DB::table('users')->select('email','userid','workerid')->where('id',$auth_id)->first();

            $credentials = ["email" => $worker->email, "password" => $request->old_password];
            if (Auth::attempt($credentials)) {

                if ($request->new_password == $request->change_password) {
                    $user = User::find(Auth::user()->id);
                    $user->password = bcrypt($request->new_password);
                    $user->wpassword = $request->new_password;
                    $user->save();
                    $request->session()->flash("success", "Password has been changed");

                    return redirect()->route('worker.changepassword');
                   

                } else {
                    
                    $request->session()->flash("error", "Confirm Password did not match");
                    return redirect()->route('worker.changepassword');

                }
            } else {
                $request->session()->flash("error", "Incorrect old password");
                 return redirect()->route('worker.changepassword');

            }
        }
         return redirect()->route('worker.changepassword');

    }

}
