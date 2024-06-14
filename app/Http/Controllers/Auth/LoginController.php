<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectPath() {
        return route('company.home');
        // if(auth()->user()->role == "restaurant"){
        //     return route('restaurant.home');
        // }else if(auth()->user()->role == "worker"){
        //     return route('worker.home');
        // }else{
        //     return route('admin.home');
        // }
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            if(auth()->user()->role == "worker") {
                return redirect(route('logout'));
            }
            if(auth()->user()->role == "company") {
                return redirect(route('company.home'));
            }
            // if(auth()->user()->role == "restaurant"){
            //     return redirect(route('restaurant.home'));
            // }else if(auth()->user()->role == "worker"){
            //     $res = RestaurantWorker::where('worker_id',auth()->user()->id)->first();

            //     // worker linked with restaurant
            //     if($request->restaurant_id != ''){
            //         $res = RestaurantWorker::where('restaurant_id',$request->restaurant_id)->where('worker_id',auth()->user()->id)->count();
            //         if($res==0){
            //             $res = new RestaurantWorker();
            //             $res->restaurant_id = $request->restaurant_id;
            //             $res->worker_id = auth()->user()->id;
            //             $res->save();
            //         }else{
            //             $res = RestaurantWorker::where('restaurant_id',$request->restaurant_id)->where('worker_id',auth()->user()->id)->first();
            //         }
            //     }
            //     // worker linked with team
            //     if($request->team_id != ''){
            //         $check_team = TeamUser::where('team_id',$request->team_id)->where('user_id',$res->id)->count();

            //         if($check_team == 0){
            //             $team = new TeamUser();
            //             $team->team_id = $request->team_id;
            //             $team->user_id = $res->id;
            //             $team->category_id = 0;
            //             $team->save();
            //         }
            //     }

            //     // set value to identify worker is manager
            //     if(isset($res)){
            //         $request->session()->put('is_manager', $res->is_manager);
            //         $request->session()->put('restaurant_id', $res->restaurant_id);
            //     }
                
            //     return redirect(route('worker.home'));
            // }else{
            //     return redirect(route('admin.home'));
            // }
        }
        
        return redirect('login')->with('error', 'Opps! You have entered invalid credentials')->withInput();
        //return redirect()->back()->withInput()->withErrors(['invalid'=>'Login details are not valid'],'loginError');
    }

    public function customLogin1(Request $request)
    {
        ///dd($request->all());
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(auth()->user()->role == "company") {
                return redirect(route('logout'));
            }
            if(auth()->user()->role == "worker") {
                return redirect(route('worker.home'));
            }
            // if(auth()->user()->role == "restaurant"){
            //     return redirect(route('restaurant.home'));
            // }else if(auth()->user()->role == "worker"){
            //     $res = RestaurantWorker::where('worker_id',auth()->user()->id)->first();

            //     // worker linked with restaurant
            //     if($request->restaurant_id != ''){
            //         $res = RestaurantWorker::where('restaurant_id',$request->restaurant_id)->where('worker_id',auth()->user()->id)->count();
            //         if($res==0){
            //             $res = new RestaurantWorker();
            //             $res->restaurant_id = $request->restaurant_id;
            //             $res->worker_id = auth()->user()->id;
            //             $res->save();
            //         }else{
            //             $res = RestaurantWorker::where('restaurant_id',$request->restaurant_id)->where('worker_id',auth()->user()->id)->first();
            //         }
            //     }
            //     // worker linked with team
            //     if($request->team_id != ''){
            //         $check_team = TeamUser::where('team_id',$request->team_id)->where('user_id',$res->id)->count();

            //         if($check_team == 0){
            //             $team = new TeamUser();
            //             $team->team_id = $request->team_id;
            //             $team->user_id = $res->id;
            //             $team->category_id = 0;
            //             $team->save();
            //         }
            //     }

            //     // set value to identify worker is manager
            //     if(isset($res)){
            //         $request->session()->put('is_manager', $res->is_manager);
            //         $request->session()->put('restaurant_id', $res->restaurant_id);
            //     }
                
            //     return redirect(route('worker.home'));
            // }else{
            //     return redirect(route('admin.home'));
            // }
        }
        
        return redirect('personnel/login')->with('error', 'Opps! You have entered invalid credentials')->withInput();
        //return redirect()->back()->withInput()->withErrors(['invalid'=>'Login details are not valid'],'loginError');
    }

    public function superadminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(auth()->user()->role == "company") {
                return redirect(route('logout'));
            }

            if(auth()->user()->role == "worker") {
                return redirect(route('logout'));
            }

            if(auth()->user()->role == "superadmin") {
                return redirect(route('superadmin.home'));
            }
        }
        return redirect('superadmin')->with('error', 'Opps! You have entered invalid credentials')->withInput();
    }

}
