<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use DB;
use Auth;

class SuperadminChangepasswordController extends Controller
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

        return view('superadmin.changepassword');
    }

    public function update(Request $request, $id = null) {
        $validateData = $request->validate([
        'new_password' => 'min:8|different:old_password|required_with:password_confirmation|same:change_password',
        'change_password' => 'min:8'
        ]);
        if ($validateData) {
            $credentials = ["email" => Auth::user()->email, "password" => $request->old_password];
            if (Auth::attempt($credentials)) {

                if ($request->new_password == $request->change_password) {
                    $user = User::find(Auth::user()->id);
                    $user->password = bcrypt($request->new_password);
                    $user->save();
                    $request->session()->flash("success", "Password has been changed");

                    return redirect()->route('superadmin.changepassword');
                   

                } else {
                    
                    $request->session()->flash("error", "Confirm Password did not match");
                    return redirect()->route('superadmin.changepassword');

                }
            } else {
                $request->session()->flash("error", "Incorrect old password");
                 return redirect()->route('superadmin.changepassword');

            }
        }
         return redirect()->route('superadmin.changepassword');
    }
}
