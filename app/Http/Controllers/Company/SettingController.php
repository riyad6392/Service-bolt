<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Auth;
use Image;

class SettingController extends Controller
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
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        $userData = User::where('id',$auth_id)->first();
        return view('setting.setting',compact('auth_id','userData'));
    }

    public function update(Request $request, $id = null) {
        $user = User::find(Auth::user()->id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->companyname = $request->companyname;
        $user->phone = $request->phone;
        $user->email = $request->email;
        //$user->cardnumber = $request->cardnumber;
        $user->date = $request->date;
        $user->securitycode = $request->securitycode;

        if(isset($request->paymenttype)) {
          $user->paymenttype = implode(',', $request->paymenttype);
        } else {
           $user->paymenttype = null; 
        }

        $logofile = $request->file('imageUpload');
        if (isset($logofile)) {
            $datetime = date('YmdHis');
            $image = $request->imageUpload->getClientOriginalName();
            $image = str_replace(" ", "", $image);
            $imageName = $datetime . '_' . $image;
            $logofile->move(public_path('userimage/'), $imageName);

            Image::make(public_path('userimage/').$imageName)->fit(300,300)->save(public_path('userimage/').$imageName);

            $user->image = $imageName;
        }

        $user->openingtime = $request->openingtime;
        $user->closingtime = $request->closingtime;
        $user->company_address = $request->address;
        $formattedAddr = str_replace(' ','+',$request->address);
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        if($output->results!=NULL) {
            $user->latitude  = $output->results[0]->geometry->location->lat; 
            $user->longitude = $output->results[0]->geometry->location->lng;
        }
        else {
          $user->latitude  = 0; 
          $user->longitude = 0;
        }

        $user->goodproduct = $request->goodproduct;
        $user->lowproduct = $request->lowproduct;
        $user->color = $request->color;
       // $user->restockproduct = $request->restockproduct;


        $user->save();
        $request->session()->flash("success", "Settings Updated Successfully");

        return redirect()->route('company.adminsetting');
    }

}
