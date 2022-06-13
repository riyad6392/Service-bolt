<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Personnel;
use App\Models\User;
use DB;
use Illuminate\Support\Str;
use Image;
use Mail;
use Hash;
use App\Models\Schedulerhours;
use DateTime;
use App\Models\Workerhour;
use App\Models\Workertimeoff;
use Illuminate\Validation\Rule;
use Redirect;

class WorkerAdminPersonnelController extends Controller
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

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $PersonnelData = Personnel::where('userid',$worker->userid)->where('workerid',$worker->workerid)->orderBy('id','desc')->get();

        $todaydate = date('l - F d, Y');
        return view('personnel.personnel.index',compact('auth_id','PersonnelData'));
    }

    public function create(Request $request)
    {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

	    $validate = Validator($request->all(), [
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore('email')->where(function ($query) {
                return $query->where('role', '!=', 'company');
        })],
      ]);
      
      if($validate->fails()) {
        return Redirect::back()->withErrors($validate)->withInput($request->all());
      }

      // if ($validate->fails()) {
      //   $request->session()->flash('error', 'Email Id already exist, please try another email id');
      //   return redirect()->route('worker.managepersonnel');
      // }
      $logofile = $request->file('image');
      if (isset($logofile)) {
        $new_file = $logofile;
        $path = 'uploads/personnel/';

        $imageName = custom_fileupload($new_file,$path);

        $data['image'] = $imageName;
      }
        $data['userid'] = $worker->userid;
        $data['workerid'] = $worker->workerid;
        $data['personnelname'] = $request->personnelname;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;
        if(isset($request->ticketid)) {
            $data['ticketid'] = implode(',', $request->ticketid);
        }

        $data['address'] = $request->address;

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        //print_r($output->results[0]->geometry->location->lat); die;
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
      
        $workerdata =  Personnel::create($data);

        //$password = Str::random(8);
        $password = $request->wpassword;
        $data1['userid'] = $workerdata->userid;
        $data1['firstname'] = $request->personnelname;
        $data1['phone'] = $request->phone;
        $data1['email'] = $request->email;
        $data1['password'] = Hash::make($password);
        $data1['wpassword'] = $password;
        $data1['role'] = "worker";
        $data1['workerid'] = $workerdata->id;
          
       User::create($data1);

        $app_name = 'ServiceBolt';
        $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
        $email = $request->email;
        $workerloginurl = url('/').'/personnel/login';
        $user_exist = Personnel::where('email', $email)->first();
        Mail::send('mail_templates.workerregistration', ['email'=>$email, 'password'=>$password,'loginurl'=>$workerloginurl], function($message) use ($user_exist,$app_name,$app_email) {
            $message->to($user_exist->email)
            ->subject('Thank you for Registration!');
            $message->from($app_email,$app_name);
        });
      
      $request->session()->flash('success', 'Personnel added successfully');
            
      return redirect()->route('worker.managepersonnel');
    }

    public function leftbarservicedata(Request $request)
    {
      $auth_id = auth()->user()->id;

      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      
      $json = array();
      if($targetid == 0) {
        $PersonnelData = Personnel::where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->get();
        $countdata = count($PersonnelData);
         $datacount = $countdata-1;
         if($PersonnelData[$datacount]->image!=null) {
            $imagepath = url('/').'/uploads/personnel/'.$PersonnelData[$datacount]->image;
         } else {
            $imagepath = url('/').'/uploads/servicebolt-noimage.png';
         }

        $permissionarray =  explode(',',$PersonnelData[$datacount]->ticketid);
       // dd($permissionarray);

      $html ='<div class="card-body targetDiv" id="div1">
                <div class="placeholder">
                  <img src="'.$imagepath.'" alt="" style="object-fit: cover;">
                <h4 class="thomas-img">'.$PersonnelData[$datacount]->personnelname.'</h4>
                </div>
                <div>
                <p class="number-1">Phone Number</p>
                <h6 class="heading-h6">'.$PersonnelData[$datacount]->phone.'</h6>
                </div>
                <div>
                <p class="number-1">Email</p>
                <h6 class="heading-h6">'.$PersonnelData[$datacount]->email.'</h6>
              </div>
              <div class="time-sheet mt-4">
              <a class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#see-schedule" id="seeSchedule" data-id="'.$PersonnelData[$datacount]->id.'">See Schedule</a>
              <button class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" style="pointer-events:none;display:none;">'.$PersonnelData[$datacount]->ticketid.'</button>
              <select class="form-control selectpicker " multiple="" data-placeholder="Permissions" data-live-search="false" style="width: 100%;" tabindex="-1" aria-hidden="true" name="ticketid[]" id="ticketid">';
              foreach($permissionarray as $value) {
                $html .='<option value="'.$value.'" selected="selected" disabled>'.$value.'</option>';
              }
              $html .='</select>
              <div class="mb-3 multbox" style="display:none;">
                <select class="form-control select2 " multiple="" data-placeholder="Permissions" style="width: 100%;" tabindex="-1" aria-hidden="true">
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                  </select>
                </div>
              <button class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#see-timesheet" id="seetimesheet" data-id="'.$PersonnelData[$datacount]->id.'" style="pointer-events:block;margin-top:14px;">See Time Sheet</button>
              <button class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#see-timeoff" id="seetimeoff" data-id="'.$PersonnelData[$datacount]->id.'" style="pointer-events:block;margin-top:14px;">PTO</button>
              <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-personnel" id="editPersonnel" data-id="'.$PersonnelData[$datacount]->id.'">Edit</a>
            </div>
              
            </div>';
      } else {

        $PersonnelData = Personnel::where('id',$request->serviceid)->get();
         if($PersonnelData[0]->image!=null) {
            $imagepath = url('/').'/uploads/personnel/'.$PersonnelData[0]->image;
         } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
         }
        
      $permissionarray1 =  explode(',',$PersonnelData[0]->ticketid);
      $html ='<div class="card-body targetDiv" id="div1">
                <div class="placeholder">
                  <img src="'.$imagepath.'" alt="" style="object-fit: cover;">
                <h4 class="thomas-img">'.$PersonnelData[0]->personnelname.'</h4>
                </div>
                <div>
                <p class="number-1">Phone Number</p>
                <h6 class="heading-h6">'.$PersonnelData[0]->phone.'</h6>
                </div>
                <div>
                <p class="number-1">Email</p>
                <h6 class="heading-h6">'.$PersonnelData[0]->email.'</h6>
              </div>
              <div class="time-sheet mt-4">
              <a class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#see-schedule" id="seeSchedule" data-id="'.$PersonnelData[0]->id.'">See Schedule</a>
              <button class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" style="pointer-events:none;display:none;">'.$PersonnelData[0]->ticketid.'</button>
               <select class="form-control selectpicker" multiple="" data-placeholder="Permissions" data-live-search="false" style="width: 100%;" tabindex="-1" aria-hidden="true" name="ticketid[]" id="ticketid">';
              foreach($permissionarray1 as $value) {
                $html .='<option value="'.$value.'" selected="selected" disabled>'.$value.'</option>';
              }
              $html .='</select>
              <div class="mb-3 multbox" style="display:none;">
                <select class="form-control select2 " multiple="" data-placeholder="Permissions" style="width: 100%;" tabindex="-1" aria-hidden="true">
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                      <option>Add Ticket / quote</option>
                  </select>
                </div>
              <button class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#see-timesheet" id="seetimesheet" data-id="'.$PersonnelData[0]->id.'" style="pointer-events:block;margin-top:14px;">See Time Sheet</button>
              <button class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#see-timeoff" id="seetimeoff" data-id="'.$PersonnelData[0]->id.'" style="pointer-events:block;margin-top:14px;">PTO</button>
              <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-personnel" id="editPersonnel" data-id="'.$PersonnelData[0]->id.'">Edit</a>
            </div>
              
            </div>';
       }
      
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function viewpersonnelmodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

       $personnel = Personnel::where('id', $request->id)->get();
       $user = User::where('workerid', $request->id)->get();

      if($personnel[0]->image != null) {
        $userimage = url('uploads/personnel/'.$personnel[0]->image);
      } else {
          $userimage = url('/').'/uploads/servicebolt-noimage.png';
      }

       $html ='<div class="add-customer-modal">
                  <h5>Edit Personnel</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="personnelid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Personnel Name</label>
            <input type="text" class="form-control" placeholder="Personnel Name" name="personnelname" id="personnelname" value="'.$personnel[0]->personnelname.'" required>
          </div>
          </div>
          <div class="col-md-12 mb-3">
          <label>Address</label>
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Address" name="address" id="address" value="'.$personnel[0]->address.'" required="">
                </div>
            </div>
          </div>
          <div class="col-md-12 mb-3">
          <label>Phone</label>
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Phone Number" name="phone" id="phone" value="'.$personnel[0]->phone.'" required="">
                </div>
            </div>
          </div>

          <div class="col-md-12 mb-3">
          <label>Email</label>
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="'.$personnel[0]->email.'" required>
                </div>
            </div>
          </div>
          <div class="col-md-12 mb-2">
            <label>Select Permissions</label>
            <select class="form-control selectpicker" data-live-search="true" multiple="" data-placeholder="Permissions" style="width: 100%;height:auto;" tabindex="-1" aria-hidden="true" name="ticketid[]" id="ticketid" required="" style="height:auto;">';
              $permission = array (
                '7'=>"Administrator",
                '5'=>"Create Ticket",
                '6'=>"Add Customer",
                '4'=>"Edit Customer",
                '1'=>"Create Invoice for payment",
                '2'=>"Generate PDF for invoice"
              );
              foreach($permission as $key =>$value) {
                $permissionvalue =explode(",", $personnel[0]->ticketid);
                 if(in_array($value, $permissionvalue)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }
                $html .='<option value="'.$value.'" '.@$selectedp.'>'.$value.'</option>';
              }
        $html .='</select>
          </div>
          <div class="col-md-12 mb-3">
          <label>Password</label>
             <div class="input_fields_wrap">
                <div class="mb-3 d-flex align-items-center">
                  <input type="password" class="form-control" placeholder="Password" name="password" id="password" value="'.@$user[0]->wpassword.'" required><span toggle="#password-field" class="fa fa-fw fa-eye-slash field_icon toggle-password"></span>
                </div>
            </div>
          </div>

          
         <div class="col-md-12">
          <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
          <input type="file" class="dropify" name="image" id="image" data-max-file-size="2M" data-allowed-file-extensions="jpg jpeg png gif svg bmp" accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg" data-default-file="'.$userimage.'" data-show-remove="false">
         </div></div>';
         
         $html .= '<div class="row mt-3"><div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block">Update</button>
          </div></div>
        </div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function update(Request $request)
    {

      $countp = Personnel::where('email', $request->email)->where('id','!=', $request->personnelid)->get();
      $countp1 = User::where('email', $request->email)->get();
      

      $personnel = Personnel::where('id', $request->personnelid)->get()->first();
      $pemail =  $personnel->email;
      
      if($pemail == $request->email) {

      } else {
       $validate = Validator($request->all(), [
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore('email')->where(function ($query) {
                return $query->where('role', '!=', 'company');
        })],
      ]);
      if ($validate->fails()) {
        $request->session()->flash('error', 'The email has already been taken.
');
        return redirect()->back();
      }
    }

      // if(count($countp) != 0) {
      //   if(count($countp)!=1) {
      //     if($countp[0]->email == $request->email){
      //       $request->session()->flash('error', 'This Email Id alredy exist, please try another email id');
      //       return redirect()->back();
      //      }
      //   }
      //   if(count($countp)!=1) {
      //     if(count($countp)>1) {
      //       $request->session()->flash('error', 'This Email Id alredy exist, please try another email id');
      //       return redirect()->back();
      //     }
      //   }
      //   if(count($countp)==1) {
      //     if($countp[0]->email == $request->email) {
      //       $request->session()->flash('error', 'This Email Id alredy exist, please try another email id');
      //       return redirect()->back();
      //     }
      //   }
      // }
      
      $personnel->personnelname = $request->personnelname;
      $personnel->phone = $request->phone;
      $personnel->address = $request->address;
      $personnel->email = $request->email;
      // if(count($countp1)>0) {

      // }else {
      //  $personnel->email = $request->email;
      // }
      $formattedAddr = str_replace(' ','+',$request->address);
      //Send request and receive json data by address
      $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
      $output = json_decode($geocodeFromAddr);
      //Get latitude and longitute from json data
      //print_r($output->results[0]->geometry->location->lat); die;
      $latitude  = $output->results[0]->geometry->location->lat; 
      $longitude = $output->results[0]->geometry->location->lng;

      $personnel->latitude = $latitude;
      $personnel->longitude = $longitude;

      if(isset($request->ticketid)) {
        $personnel->ticketid = implode(',', $request->ticketid);
      }

      $logofile = $request->file('image');
      if (isset($logofile)) {
          $new_file = $logofile;
           $path = 'uploads/personnel/';
           $old_file_name = $personnel->image;
           $imageName = custom_fileupload($new_file,$path,$old_file_name);

          $personnel->image = $imageName;
      }
      $personnel->save();

      $users = User::where('workerid', $request->personnelid)->get()->first();
      if($users) {
        $users->wpassword = $request->password; 
        $users->password = Hash::make($request->password);
        $users->email = $request->email;
        // if(count($countp1)>0) {
        
        // } else {
        //   $users->email = $request->email;
        // }
        
        $users->save();
      }
      if($pemail != $request->email) {
        if(count($countp1)>0) {
        
        } else {
        $app_name = 'ServiceBolt';
        $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
        $email = $request->email;
        $workerloginurl = url('/').'/personnel/login';
        $user_exist = Personnel::where('email', $request->email)->first();
        Mail::send('mail_templates.workerregistration', ['email'=>$request->email, 'password'=>$request->password,'loginurl'=>$workerloginurl], function($message) use ($user_exist,$app_name,$app_email) {
            $message->to($user_exist->email)
            ->subject('Thank you for Registration!');
            $message->from($app_email,$app_name);
        });
        }      
      }

      $request->session()->flash('success', 'Personnel Updated successfully');
      return redirect()->back();
      return redirect()->route('company.personnel');
    }

    public function leftbarpersonnelschedulerdata(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $personnelid = $request->id;
      $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();
      $json = array();
      $countsdata = count($scheduleData);
      $datacount = $countsdata;
      $userData = User::select('openingtime','closingtime')->where('id',$worker->userid)->first();
      $html = "";

      $html .='<div class="ev-arrow">
             <a class="ev-left" data-id="'.$personnelid.'"></a>
             <a class="ev-right" data-id="'.$personnelid.'"></a>
             </div>
             <div class="ev-calender-list">';
      for($i=$userData->openingtime; $i<=$userData->closingtime; $i++) {
        $plusone = $i+1;
        $colon = ":00";
        if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
          $times = $i.":00";
          $html .='<ul class="showdata">
    <li><div class="ev-calender-hours">'.strtoupper(date("h:i a", strtotime($times))).'</div></li>';
          foreach($scheduleData as $key => $value) {
              $f= $i+1;
              $m =   ":00";
              $settimes = date("h:i a", strtotime($times));
              if($value->giventime == $settimes) {
                $imagepath = url('/').'/uploads/customer/'.$value->image;
              $html .='<li class="inner yellow-slide" id="drop_'.$value->id.'">
                        <div class="card">
                          <div class="card-body">
                            <div class="imgslider" style="display:none;">
                              <img src="'.$imagepath.'" alt=""/>
                            </div>
                            <input type="hidden" name="customerid" id="customerid" value="'.$value->customerid.'">
                            <input type="hidden" name="quoteid" id="quoteid_'.$value->id.'" value="'.$value->id.'"><span>#'.$value->id.'</span>
                            <h5>'.$value->customername.'</h5><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"><i class="fa fa-trash" style="position: absolute;right: 56px;top: 30px;"></i></a>
                            <p>'.$value->servicename.'</p>
                            <p>Personnel Name - '.$value->personnelname.'</p>
                            <div class="grinding" style="display:block;">
                              <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="5" cy="5" r="5" fill="currentColor" style="display:none;">
                              </svg>'.$value->time.'</a>
                              <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="5" cy="5" r="5" fill="currentColor" style="display:none;">
                              </svg>'; 
                                $date=date_create($value->etc);
                                $dateetc = date_format($date,"F d, Y");
                            $html .='ETC : '.$dateetc.'</a>
                            </div>
                          </div>
                        </div>
                      </li>';
              }
          }

      }
        $html .='</ul>
        </div>';
          return json_encode(['html' =>$html,'countsdata'=>$countsdata]);
    }

    public function leftbarpersonneltimesheetdata(Request $request)
    {
      if(isset($request->fulldate)) {
        $fulldate =  $request->fulldate;
      }
      $personnelid = $request->id;
      $pdata = DB::table('personnel')->select('personnelname','image')->where('id',$personnelid)->first();
      if($pdata->image!=null) {
            $imagepath = url('/').'/uploads/personnel/'.$pdata->image;
      } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }

      if(isset($request->from)) {
        //$stimesheetData  = Schedulerhours::where('workerid',$personnelid)->whereBetween('date1', [$request->from, $request->to])->get();

        $stimesheetData  = DB::table('workerhour')->where('workerid',$personnelid)->whereBetween('date1', [$request->from, $request->to])->get();

        // User::whereBetween(DB::raw('DATE(created_at)'), array($from_date, $to_date))->get();
      } else {
        //$stimesheetData  = DB::table('schedulertimesheet')->where('workerid',$personnelid)->whereDate('created_at', DB::raw('CURDATE()'))->get();
        $stimesheetData  = DB::table('workerhour')->where('workerid',$personnelid)->whereDate('date1', DB::raw('CURDATE()'))->orderBy('id','desc')->get();
      }
      $json = array();
      $countsdata = count($stimesheetData);
      $datacount = $countsdata;
      $currentdate = Carbon::now();
      $currentdate = date('Y-m-d', strtotime($currentdate));
      $html = "";
      $html .='<div class="time-sheet-box">
    <div class="row">
      <div class="col-lg-3 mb-3">
      <select class="form-select">
        <option selected>'.$pdata->personnelname.'</option>
        </select>
      </div>';
      if(isset($request->from)) {
      $html .='<div class="col-lg-3 mb-3">
      <input type="date" id="since" value="'.$request->from.'" name="since" id="since" class="form-control">
      </div>
      <div class="col-lg-3 mb-3">
      <input type="date" id="until" value="'.$request->to.'" name="until" id="until" class="form-control">
      </div>';
      } else {
        $html .='<div class="col-lg-3 mb-3">
      <input type="date" id="since" value="'.$currentdate.'" name="since" id="since" class="form-control">
      </div>
      <div class="col-lg-3 mb-3">
      <input type="date" id="until" value="'.$currentdate.'" name="until" id="until" class="form-control">
      </div>';
      }
      $html .='<div class="col-lg-3 mb-3">
       <button class="btn btn-block button" type="submit" id="search" data-id="'.$personnelid.'"">Search</button>
      </div>
  </div>
  <div class="table-responsive">
    <table class="table no-wrap table-new table-list align-items-center blue-a">
      <thead>
      <tr>
        <th>NAME</th>
        <th>DATE</th>
        <th>TIME IN</th>
        <th>TIME OUT</th>
        <th>HOURS</th>
        <th></th>
      </tr>
      </thead>
      <tbody>';
      if($countsdata>0) {
        foreach($stimesheetData as $key =>$value) {
        $html .='<tr>
          <td><a href="#">
             <div class="user-descption align-items-center">
           <div class="user-img">
           <img src="'.$imagepath.'" alt="">
           </div>
           <div class="user-content">
           <h5 class="m-0">'.$pdata->personnelname.'</h5>
           </div>
           </div></a>
        </td>
          <td>
            <span class="date-edit">'.$value->date1.'</span> <input type="text" class="form-control input-editable"/>
          </td>
          <td class="blue-light">
            <span class="date-edit">'.$value->starttime.'</span> <input type="text" class="form-control input-editable"/>
          </td>
          <td class="light-color">
            <span class="date-edit">'.$value->endtime.'</span> <input type="text" class="form-control input-editable"/>
          </td>
          <td>'.$value->totalhours.'</td>
          <td style="display:none;">
            <button class="btn btn-edit edit-td" data-bs-toggle="modal" data-bs-target="#edit-timesheet" id="edittimesheet" data-id="'.$value->id.'">Edit</button>
            <button class="btn btn-save save-td">Save</button>
          </td>
        </tr>';
        }
      } else {
        $html .='<div>No Record Found</div>';
      }
          $html .='</tbody>
        </table>
      </div>
    </div>';
          return json_encode(['html' =>$html,'countsdata'=>$countsdata]);
    }

    public function timesheetupdate(Request $request)
    {
      $personnel = Personnel::where('id', $request->personnelid)->get()->first();
      $personnel->personnelname = $request->personnelname;
      $personnel->phone = $request->phone;
      $personnel->address = $request->address;
      $formattedAddr = str_replace(' ','+',$request->address);
      //Send request and receive json data by address
      $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
      $output = json_decode($geocodeFromAddr);
      //Get latitude and longitute from json data
      //print_r($output->results[0]->geometry->location->lat); die;
      $latitude  = $output->results[0]->geometry->location->lat; 
      $longitude = $output->results[0]->geometry->location->lng;

      $personnel->latitude = $latitude;
      $personnel->longitude = $longitude;

      if(isset($request->ticketid)) {
        $personnel->ticketid = implode(',', $request->ticketid);
      }

      $logofile = $request->file('image');
      if (isset($logofile)) {
          $new_file = $logofile;
           $path = 'uploads/personnel/';
           $old_file_name = $personnel->image;
           $imageName = custom_fileupload($new_file,$path,$old_file_name);

          $personnel->image = $imageName;
      }
      $personnel->save();

      $users = User::where('workerid', $request->personnelid)->get()->first();
      $users->wpassword = $request->password; 
      $users->password = Hash::make($request->password);
      $users->save();

      $request->session()->flash('success', 'Personnel Updated successfully');
      return redirect()->route('company.personnel');
    }

    public function leftbaredittimesheetdata(Request $request)
    {
      //$timesheetdata = DB::table('schedulertimesheet')->select('starttime','endtime','date')->where('id',$request->id)->first();

      $timesheetdata = DB::table('workerhour')->select('starttime','endtime','date')->where('id',$request->id)->first();
     
      $json = array();
      if(!empty($timesheetdata)) {
        $starttime = $timesheetdata->starttime;
        $endtime = $timesheetdata->endtime;
      } else {
        $starttime = "";
        $endtime = "";
      }
     
     return json_encode(['starttime' =>$starttime,'endtime'=>$endtime,'timeid'=>$request->id,'date'=>$timesheetdata->date]);
        return json_encode(['html' =>$html]);
    }

    public function timeupdate(Request $request) {

      $timepicker = $request->timepicker;
      $timepicker1 = $request->timepicker1;
      
      $datetime1 = new DateTime($timepicker);
      $datetime2 = new DateTime($timepicker1);
      $interval = $datetime1->diff($datetime2);
      $totalhours = $interval->format('%hh %im');
      
      //$schedulerhours = Schedulerhours::where('id',$request->timeid)->first();
      $schedulerhours = Workerhour::where('id',$request->timeid)->first();
       
      $schedulerhours->starttime = $timepicker;
      $schedulerhours->endtime = $timepicker1;
      $schedulerhours->totalhours = $totalhours;
      $schedulerhours->save();

      
      return redirect()->route('company.personnel');
    }

    public function leftbarpersonneltimeoffdata(Request $request)
    {
      if(isset($request->fulldate)) {
        $fulldate =  $request->fulldate;
      }
      $personnelid = $request->id;
      $pdata = DB::table('personnel')->select('personnelname','image')->where('id',$personnelid)->first();
      if($pdata->image!=null) {
            $imagepath = url('/').'/uploads/personnel/'.$pdata->image;
      } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }

      if(isset($request->from)) {
        $stimesheetData  = DB::table('timeoff')->where('workerid',$personnelid)->whereBetween('date1', [$request->from, $request->to])->get();
      } else {
        $stimesheetData  = DB::table('timeoff')->where('workerid',$personnelid)->whereDate('date1', DB::raw('CURDATE()'))->get();
      }
      $json = array();
      $countsdata = count($stimesheetData);
      $datacount = $countsdata;
      $currentdate = Carbon::now();
      $currentdate = date('Y-m-d', strtotime($currentdate));
      $html = "";
      $html .='<div class="time-sheet-box">
    <div class="row">
      <div class="col-lg-3 mb-3">
      <select class="form-select">
        <option selected>'.$pdata->personnelname.'</option>
        </select>
      </div>';
      if(isset($request->from)) {
      $html .='<div class="col-lg-3 mb-3">
      <input type="date" id="since" value="'.$request->from.'" name="since" id="since" class="form-control">
      </div>
      <div class="col-lg-3 mb-3">
      <input type="date" id="until" value="'.$request->to.'" name="until" id="until" class="form-control">
      </div>';
      } else {
        $html .='<div class="col-lg-3 mb-3">
      <input type="date" id="since" value="'.$currentdate.'" name="since" id="since" class="form-control">
      </div>
      <div class="col-lg-3 mb-3">
      <input type="date" id="until" value="'.$currentdate.'" name="until" id="until" class="form-control">
      </div>';
      }
      $html .='<div class="col-lg-3 mb-3">
       <button class="btn btn-block button" type="submit" id="search1" data-id="'.$personnelid.'"">Search</button>
      </div>
  </div>
  <div class="table-responsive">
    <table class="table no-wrap table-new table-list align-items-center blue-a">
      <thead>
      <tr>
        <th>NAME</th>
        <th>DATE</th>
        <th>Notes</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>';
      if($countsdata>0) {
        
        foreach($stimesheetData as $key =>$value) {
        $html .='<tr>
          <td><a href="#">
             <div class="user-descption align-items-center">
           <div class="user-img">
           <img src="'.$imagepath.'" alt="">
           </div>
           <div class="user-content">
           <h5 class="m-0">'.$pdata->personnelname.'</h5>
           </div>
           </div></a>
        </td>
          <td>
            <span class="date-edit">'.$value->date1.'</span> <input type="text" class="form-control input-editable"/>
          </td>
          <td>'.$value->notes.'</td>';
          if($value->status!=null) {
            $vstatus = $value->status;
        
          $html .='<td><a class="btn btn-edit p-3 w-auto" id="accept" data-id="'.$value->id.'" style="pointer-events:none;">'.$vstatus.'</a></td>';
        }else {
            $html .='<td><a class="btn btn-edit p-3 w-auto" id="accept" data-id="'.$value->id.'">Accept</a><br>
            <a class="btn btn-edit p-3 w-auto" id="reject" data-id="'.$value->id.'">Reject</a></td>';
        }
        $html .='</tr>';
        }
      } else {
        $html .='<div>No Record Found</div>';
      }
          $html .='</tbody>
        </table>
      </div>
    </div>';
          return json_encode(['html' =>$html,'countsdata'=>$countsdata]);
    }

    public function accepttime(Request $request)
    {
      $id = $request->id;
      $timeoff = Workertimeoff::where('id', $id)->get()->first();
      $timeoff->status = "Accepted";
      $timeoff->save();
      echo "1";
    }

    public function rejecttime(Request $request)
    {
      $id = $request->id;
      $timeoff = Workertimeoff::where('id', $id)->get()->first();
      $timeoff->status = "Rejected";
      $timeoff->save();
      echo "1";
    }
}
