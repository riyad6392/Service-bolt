<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Personnel;
use App\Models\User;

use Auth;
use Image;

class WorkerSettingController extends Controller
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
        if(auth()->user()->role == 'worker') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        $userData = User::select('workerid')->where('id',$auth_id)->first();
        $workerData = Personnel::where('id',$userData->workerid)->first();
        return view('personnel.setting',compact('auth_id','workerData'));
    }

    public function update(Request $request, $id = null) {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'worker') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        $userData = User::select('workerid')->where('id',$auth_id)->first();
        $workers = Personnel::where('id',$userData->workerid)->first();

        $workers->personnelname = $request->personnelname;
        $workers->phone = $request->phone;
        $workers->address = $request->address;
        $workers->email = $request->email;

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        //print_r($output->results[0]->geometry->location->lat); die;
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

        $workers->latitude = $latitude;
        $workers->longitude = $longitude;
        
        $logofile = $request->file('imageUpload');
        if (isset($logofile)) {
            $datetime = date('YmdHis');
            $image = $request->imageUpload->getClientOriginalName();
            $image = str_replace(" ", "", $image);
            $imageName = $datetime . '_' . $image;
            $logofile->move(public_path('uploads/personnel/'), $imageName);

            Image::make(public_path('uploads/personnel/').$imageName)->fit(300,300)->save(public_path('uploads/personnel/thumbnail/').$imageName);

            $workers->image = $imageName;
        }

        $workers->save();
        $request->session()->flash("success", "Setting Updated Successfully");

        return redirect()->route('worker.setting');
    }

}
