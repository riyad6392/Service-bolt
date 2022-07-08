<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Support\Str;
use Mail;
use App\Models\Managefield;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register()
    {
         
      return view('auth.register');
    }

    public function storeUser(Request $request)
    {
       // dd($request->all());
       $pricev = User::select('featureprice')->where('role','superadmin')->first();
        $request->session()->put('price', $pricev->featureprice);
        //dd($request->session()->get('price'));
        if($request->session()->get('price')>0) {
            $allData = $request->all();
            return view('auth.signupcomplete',compact('allData'));
        } else {
             return view('auth.register');
        }
        // $request->validate([
        //     'firstname' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     // 'password' => 'required|string|min:8|confirmed',
        //     // 'password_confirmation' => 'required',
        //     'companyname' => 'required',
        //     'phone' => 'required',
        //     'accept_terms_conditions' => 'required',
        // ]);


        $password = Str::random(8);
        
        // User::create([
        //     'firstname' => $request->firstname,
        //     'lastname' => $request->lastname,
        //     'email' => $request->email,
        //     'password' => Hash::make($password),
        //     'phone' => $request->phone,
        //     'date' => $request->date,
        //     'companyname' => $request->companyname,
        //     'cardnumber' => $request->cardnumber,
        //     'securitycode' => $request->securitycode,
        //     'accept_terms_conditions' => $request->accept_terms_conditions,
        //     'role' => "company",
        // ]);

        // $app_name = 'ServiceBolt';
        // $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
        // $email = $request->email;
        // $user_exist = User::where('email', $email)->first();

        // Mail::send('mail_templates.registration', ['email'=>$email, 'password'=>$password], function($message) use ($user_exist,$app_name,$app_email) {
        //     $message->to($user_exist->email)
        //     ->subject('Thank you for Registration!');
        //     $message->from($app_email,$app_name);
        // });
        // return redirect('login')->with('success', 'Please check your email for the email and password you can use to login.');
        //return redirect('home');
    }

    public function signupcomplete1(Request $request) {
        
        $request->validate([
            'firstname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore('email')->where(function ($query) {
                    return $query;
                })],
                //->where('role', '!=', 'worker')
            //'email' => 'required|string|email|max:255|unique:users,"worker",role',
            'password' => 'min:6|required_with:confirmpassword|same:confirmpassword',
            'confirmpassword' => 'min:6',
            'companyname' => 'required',
            'phone' => 'required',
            'accept_terms_conditions' => 'required',
        ]);



        //$password = Str::random(8);
        $password = $request->password;
        $data1 = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($password),
            'phone' => $request->phone,
            'date' => $request->date,
            'companyname' => $request->companyname,
            'cardnumber' => $request->cardnumber,
            'securitycode' => $request->securitycode,
            'accept_terms_conditions' => $request->accept_terms_conditions,
            'role' => "company",
            'amount' => $request->price,
            'openingtime' => 0,
            'closingtime' => 23,
        ]);

        $userid = $data1->id;

        $pagename1= "companyquote";
        $fieldlist1 = array(
            '0'=>"customername",
            '1'=>"servicename"
        );

        foreach($fieldlist1 as $key => $value) {
          $data['userid'] = $userid;
          $data['page'] = $pagename1;
          $data['columname'] = $value;
          Managefield::create($data);  
        }

        $pagename2= "companyticket";
        $fieldlist2 = array(
            '0'=>"customername",
            '1'=>"servicename"
        );

        foreach($fieldlist2 as $key => $value) {
          $data['userid'] = $userid;
          $data['page'] = $pagename2;
          $data['columname'] = $value;
          Managefield::create($data);  
        }

        $pagename3= "companybilling";
        $fieldlist3 = array(
            '0'=>"servicename",
            '1'=>"price",
            '2'=>"payment_status"
        );

        foreach($fieldlist3 as $key => $value) {
          $data['userid'] = $userid;
          $data['page'] = $pagename3;
          $data['columname'] = $value;
          Managefield::create($data);  
        }

        $pagename4= "companyservice";
        $fieldlist4 = array(
            '0'=>"servicename",
            '1'=>"price",
            '2'=>"frequency"
        );

        foreach($fieldlist4 as $key => $value) {
          $data['userid'] = $userid;
          $data['page'] = $pagename4;
          $data['columname'] = $value;
          Managefield::create($data);  
        }

        $pagename5= "companyproduct";
        $fieldlist5 = array(
            '0'=>"productname",
            '1'=>"quantity",
            '2'=>"sku"
        );

        foreach($fieldlist5 as $key => $value) {
          $data['userid'] = $userid;
          $data['page'] = $pagename5;
          $data['columname'] = $value;
          Managefield::create($data);  
        }

        $pagename6= "companycustomer";
        $fieldlist6 = array(
            '0'=>"customername",
            '1'=>"email",
            '2'=>"serviceid"
        );

        foreach($fieldlist6 as $key => $value) {
          $data['userid'] = $userid;
          $data['page'] = $pagename6;
          $data['columname'] = $value;
          Managefield::create($data);  
        }


        $app_name = 'ServiceBolt';
        $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
        $email = $request->email;
        $user_exist = User::where('email', $email)->first();

        Mail::send('mail_templates.registration', ['email'=>$email, 'password'=>$password], function($message) use ($user_exist,$app_name,$app_email) {
            $message->to($user_exist->email)
            ->subject('Thank you for Registration!');
            $message->from($app_email,$app_name);
        });

        $credentials = array('email'=>$request->email,'password'=>$request->password);
          if (Auth::attempt($credentials)) {
            if(auth()->user()->role == "company") {
                return redirect(route('company.home'));
            }
          } else {
            return redirect('login')->with('success', 'Please check your email for the email and password you can use to login.');
          }
    }

    public function login()
    {

     if(auth()->user()!=null){
      if(auth()->user()->status == "InActive") {
        Auth::logout();
        return redirect('login'); 
      }
      if(auth()->user()->role=="company"){
          return redirect(route('company.home'));
      } 
      if(auth()->user()->role=="worker") {
          return redirect(route('worker.home'));
      } 
      if(auth()->user()->role=="superadmin") {
          return redirect(route('superadmin.home'));
      } 
    }

      return view('auth.login');
    }

    public function workerlogin()
    {
        
     if(auth()->user()!=null) {
      if(auth()->user()->role=="company"){
          return redirect(route('company.home'));
      } 
      if(auth()->user()->role=="worker") {
          return redirect(route('worker.home'));
      } 
    }
      return view('auth.workerlogin');
    }

    public function superadminlogin()
    {
        return view('auth.superadminlogin');
    }

    // public function authenticate(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);

    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         return redirect()->intended('home');
    //     }

    //     return redirect('login')->with('error', 'Opps! You have entered invalid credentials');
    // }

    public function logout() {
      Auth::logout();

      return redirect('login');
    }

    public function workerlogout() {
      Auth::logout();

      return redirect('personnel/login');
    }

    public function superadminlogout() {
      Auth::logout();
      return redirect('superadmin');
    }

    // public function home()
    // {
    //   return view('home');
    // }
}