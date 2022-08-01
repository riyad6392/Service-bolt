<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use App\Models\Personnel;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Password; 
use Validator;
use Illuminate\Support\Facades\Notification;
use Tzsk\Otp\Facades\Otp;
use Mail;
use App\Helpers\Files\Storage\StorageDisk;
use Intervention\Image\Facades\Image;
use Auth;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Quote;
use App\Models\Tenture;
use App\Models\Inventory;
use DateTime;
use App\Models\Workerhour;
use App\Models\Workertimeoff;
use App\Models\Workersethour;
use App\Models\Balancesheet;
use App\Models\PasswordReset;

class UserController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 400;
    public $errorresultStatus = 401;
    // 404- data not found
    // 400 - fatal errors

    
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login() {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['email'])){
                foreach($errors['email'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['password'])){
                foreach($errors['password'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $token =  auth()->user()->createToken('API Token')->plainTextToken;
            //$user->createToken('MyApp')->accessToken; 
            //$details = DB::table('personnel')->where('id',$user->workerid)->first();
            $pdetails = User::select('users.id', 'psnl.id as pid', 'psnl.personnelname', 'psnl.phone', 'psnl.email', 'psnl.ticketid as permission', 'psnl.address','psnl.image','psnl.vibration','psnl.notification')
                ->join('personnel as psnl', 'psnl.id', '=', 'users.workerid')->where('psnl.id',$user->workerid)->get();
                $data1 = array();
                foreach($pdetails as $value) {
                    $data1['id']= $value->id;
                    $data1['pid']= $value->pid;
                    $data1['personnelname']= $value->personnelname;
                    $data1['phone']= $value->phone;
                    $data1['email']= $value->email;
                    $data1['permission']= explode(",",$value->permission);
                    $data1['address']= $value->address;
                    $data1['image']= $value->image;
                    $data1['vibration']= $value->vibration;
                    $data1['notification']= $value->notification;
                }

                $device_token = request('device_token');
                
                if($device_token!=null) {
                    DB::table('personnel')->where('id','=',$user->workerid)
                      ->update([ 
                          "device_token"=>"$device_token"
                    ]);
                }   
            return response()->json(['status'=>1,'message'=>__('You are successfully logged in.'),'token'=>$token,'data'=>$data1],$this->successStatus); 
        } 
        else{ 
            return response()->json(['status'=>0,'message'=>__('You have entered an invalid username or password.')],$this->successStatus); 
        }
    }

    public function loginerror() {
        return response()->json(['status'=>0,'message'=>'Unauthenticated'],$this->errorresultStatus);   
    }

    public function getprofile(Request $request) {
        $user = Auth::user();

        $pdetails = User::select('users.id', 'psnl.id as pid', 'psnl.personnelname', 'psnl.phone', 'psnl.email', 'psnl.ticketid as permission', 'psnl.address','psnl.image','psnl.vibration','psnl.notification')
                ->join('personnel as psnl', 'psnl.id', '=', 'users.workerid')->where('psnl.id',$user->workerid)->get();
        $data1 = array();
                foreach($pdetails as $value) {
                    $data1['id']= $value->id;
                    $data1['pid']= $value->pid;
                    $data1['personnelname']= $value->personnelname;
                    $data1['phone']= $value->phone;
                    $data1['email']= $value->email;
                    $data1['permission']= explode(",",$value->permission);
                    $data1['address']= $value->address;
                    $data1['image']= $value->image;
                    $data1['vibration']= $value->vibration;
                    $data1['notification']= $value->notification;
                }

        if ($pdetails) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$data1],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'id not found'],$this->errorStatus);
        }
    }

    public function updateprofile(Request $request) {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

       if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['name'])){
                foreach($errors['name'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['phone'])){
                foreach($errors['phone'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['address'])){
                foreach($errors['address'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $user = Auth::user();

        if($request->currentpassword!="" && $request->newpassword!="" && $request->confpassword!="") {
            $credentials = ["email" => Auth::user()->email, "password" => $request->currentpassword];

            if (Auth::guard('client')->attempt($credentials)) {
                if ($request->newpassword == $request->confpassword) {
                    $user = Auth::user();
                    $user->password = Hash::make($request->newpassword);
                    $user->wpassword = $request->newpassword;
                    $user->save();
                } else {
                    return response()->json(['status'=>0,'message'=>'Confirm Password did not match'],$this->successStatus);
                }
            } else {
                return response()->json(['status'=>0,'message'=>'Current Password did not match'],$this->successStatus);
            }
        }
        $personnel = Personnel::find($user->workerid);
        $personnel->personnelname = $request->name;
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

        $img = $request->image;
        if (!empty($img)) {    
            $image_parts = explode(";base64,", $img);
            $value = base64_decode($image_parts[1]);
            $imageName = time()  . '.png';

            $path = 'uploads/personnel/'.$imageName;
            $thumbPath = 'uploads/personnel/thumbnail/'.$imageName;

            file_put_contents($path, $value);
            Image::make($path)->fit(300, 300)->save($thumbPath);

            $personnel->image = $imageName;
        }

        $personnel->vibration = $request->vibration;
        $personnel->notification = $request->notification;    
        $personnel->save();
        return response()->json(['status'=>1,'message'=>'Updated successfully'],$this->successStatus);
    }

    public function dashboardDetails(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

        $todayservicecall = DB::table('quote')->where('personnelid',$worker->workerid)->whereIn('ticket_status',['2','3','4'])->whereDate('created_at', Carbon::today())->limit('2')->orderBy('id','DESC')->get();

        $customerData = DB::table('quote')->select('quote.*', 'customer.id','customer.phonenumber','customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status','2')->limit('2')->orderBy('quote.id','DESC')->get();

        $todaydate = date('l - F d, Y');
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname','services.color')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.servicename', '=', 'quote.servicename')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',[2,3,4])->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();
                
        return response()->json(['status'=>1,'message'=>'success','todayticket'=>$todayservicecall,'customerData'=>$customerData,'scheduleData'=>$scheduleData],$this->successStatus);
    }

    public function myticketData(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first(); 
        $ticketdata = DB::table('quote')->where('personnelid',$worker->workerid)->whereIn('ticket_status',array('2','3','4'))->get();

        if ($ticketdata) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$ticketdata],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function completedticketdata(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first(); 
        $ticketdata = DB::table('quote')->where('personnelid',$worker->workerid)->whereIn('ticket_status',array('3'))->get();

        if ($ticketdata) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$ticketdata],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function myticketDetail(Request $request) {
        $main_array =array();
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'ticketId' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['ticketId'])){
                foreach($errors['ticketId'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $ticketId = $request->ticketId;

        $sdata = Quote::select('serviceid')->where('id',$ticketId)->first();

        if($sdata->serviceid!="") {
          $serviceidarrays = explode(',', $sdata->serviceid);
        } else {
          $serviceidarrays = array();
        }

        $checklistData = DB::table('checklist')->select('id','checklist')->whereIn('serviceid',$serviceidarrays)->get();

        $quoteData = DB::table('quote')->select('quote.id','quote.customerid','quote.customername','quote.address','quote.latitude','quote.longitude','quote.etc','quote.givendate','quote.giventime','quote.givenendtime','quote.description','quote.product_id','quote.serviceid', 'customer.phonenumber','quote.ticket_status','quote.customernotes','quote.checklist','quote.price')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.id',$ticketId)->first();
        
        if($quoteData) {
            $serviceidarray = explode(',', $quoteData->serviceid);
            $servicedetails = Service::select('id','servicename','price')->whereIn('id', $serviceidarray)->get();
            $sum = 0;
            $serearray=array();
            foreach ($servicedetails as $key => $value) {
                 $serearray[] = array (
                     'id' =>$value['id'],
                     'servicename' => $value['servicename'],
                     
                   );
             // $sname[] = $value['servicename'];
              $sum+= (int)$value['price'];
            }

            $servicename = "";

            $pidarray = explode(',', $quoteData->product_id);
            $pdetails = Inventory::select('productname','id','price')->whereIn('id', $pidarray)->get();
            $sum1 = 0;
            $proarray=array();
            foreach ($pdetails as $key => $value) {
                $proarray[] = array (
                     'id' =>$value['id'],
                     'productname' => $value['productname'],
                );
              //$pname[] = $value['productname'];
              $sum1+= (int)$value['price'];
            }

            if($quoteData->checklist!="") {
                $pointbox =  explode(',', $quoteData->checklist);   
            } else {
                $pointbox = array();  
            }


            array_push($main_array, [
                   'id'=>$quoteData->id,
                   'customerid'=>$quoteData->customerid,
                   'customername'=>$quoteData->customername,
                   'address'=>$quoteData->address,
                   'latitude'=>$quoteData->latitude,
                   'longitude'=>$quoteData->longitude,
                   'etc'=>$quoteData->etc,
                   'givendate'=>$quoteData->givendate,
                   'giventime'=>$quoteData->giventime,
                   'description'=>$quoteData->description,
                   'phonenumber'=>$quoteData->phonenumber,
                   'ticket_status'=>$quoteData->ticket_status,
                   'pointcheckbox'=>$pointbox,
                   'servicedata'=>$serearray,
                   'productdata'=>$proarray,
                   'customernotes'=>$quoteData->customernotes,
                ]);  
            
            //$servicename = implode(',', $sname);

           // $totalprice = $sum+$sum1;
            $totalprice = $quoteData->price;

           // $productname = implode(',', $pname);

            $prequoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber')->leftjoin('customer', 'customer.id', '=', 'quote.customerid')->where('quote.customerid',$quoteData->customerid)->whereIn('quote.ticket_status',array('3','4'))->get();

            return response()->json(['status'=>1,'message'=>'success','data'=>$main_array,'totalprice'=>$totalprice,'checklistData'=>$checklistData,'priviousTicketData'=>$prequoteData],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function myserviceData(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $servicedetails = DB::table('services')->select('services.id','services.userid','services.servicename','services.price','services.time as hours','services.minute')->where('userid',$worker->userid)->orderBy('services.id','desc')->get();
        // $serviceids = DB::table('quote')->select('serviceid','customerid')->where('personnelid',$worker->workerid)->get();

        //   foreach($serviceids as $key => $value) {
        //       $serviceidss[] = $value->serviceid;
        //   }

        //   foreach($serviceids as $key => $value) {
        //       $customerids[] = $value->customerid;
        //   }

        // $servicedetails = DB::table('quote')->whereIn('serviceid',$serviceidss)->whereIn('customerid',$customerids)->orderBy('id','DESC')->get();

        if ($servicedetails) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$servicedetails],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }
    public function myproductData(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        
       //  $productids = DB::table('quote')->select('product_id')->where('personnelid',$worker->workerid)->groupBy('product_id')->get();

       //  foreach($productids as $key => $value) {
       //    $product_ids[] = $value->product_id;
       // }
      
        $productdata = DB::table('products')->select('products.id','products.productname','products.quantity','products.sku','products.price','products.description','products.category')->where('user_id',$worker->userid)->orderBy('products.id','desc')->get();

        

        if ($productdata) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$productdata],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function mycustomerData(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $customerids = DB::table('quote')->select('customerid')->where('personnelid',$worker->workerid)->groupBy('customerid')->get();

      foreach($customerids as $key => $value) {
          $customeridss[] = $value->customerid;
      }

      //$customerData = DB::table('customer')->whereIn('id',$customeridss)->get();

      $customerData = DB::table('customer')->where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get(); 

        if ($customerData) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$customerData],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function customerdetails(Request $request) {

        $validator = Validator::make(request()->all(), [
            'customerid' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['customerid'])){
                foreach($errors['customerid'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $customerid = $request->customerid;
        $user = Auth::user();
        $auth_id = $user->id;

        $customerData = Customer::where('id',$customerid)->get();
        $mainarray = array();
        if($customerData[0]->serviceid!="") {
            $serviceids =explode(",",$customerData[0]->serviceid);
            $servicearray = Service::select('id','servicename')->whereIn('id',$serviceids)->get();

            foreach($servicearray as $key=> $servicedata) {
                $mainarray[] = array(
                    'id' => $servicedata->id,
                    'servicename' => $servicedata->servicename,
                );
            }
        }
        $data1 = array();
        
        foreach($customerData as $key => $value) {
            $data1['id']= $value->id;
            $data1['userid']= $value->userid;   
            $data1['workerid']= $value->workerid;   
            $data1['customername']= $value->customername;   
            $data1['phonenumber']= $value->phonenumber;   
            $data1['email']= $value->email;   
            $data1['companyname']= $value->companyname;   
            $data1['servicearray']= $mainarray;   
            $data1['image']= $value->image;
            $data1['created_at']= $value->created_at; 
            $data1['updated_at']= $value->updated_at;    
        }

        $customerAddress = Address::where('customerid',$customerid)->get();
        $recentTicket = Quote::where('customerid',$customerid)->orderBy('id','DESC')->get();

        if ($customerData) {
                return response()->json(['status'=>1,'message'=>'success','customerData'=>$data1,'connectedAddress'=>$customerAddress,'recentTickets'=>$recentTicket],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function schedulerdata(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;
        $validator = Validator::make(request()->all(), [
            'date' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['date'])) {
                foreach($errors['date'] as $e) {
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $date = $request->date;

        
        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname','services.color')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.servicename', '=', 'quote.servicename')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',[2,3,4])->where('quote.givendate',$date)->orderBy('quote.id','ASC')->get();
                
        return response()->json(['status'=>1,'message'=>'success','scheduleData'=>$scheduleData],$this->successStatus);
    }

    public function myticketCompleted(Request $request) {
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'ticketId' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['ticketId'])){
                foreach($errors['ticketId'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }
        
        $ticketId = $request->ticketId;

        $quoteData = Quote::where('id',$ticketId)->first(); 
        if($quoteData){
           $quoteData->ticket_status = "3";
           $quoteData->save();
           return response()->json(['status'=>1,'message'=>'Ticket Completed successfully'],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function myticketPickup(Request $request) {
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'ticketId' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['ticketId'])){
                foreach($errors['ticketId'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $ticketId = $request->ticketId;

        $quoteData = Quote::where('id',$ticketId)->first();
        if($quoteData) {
                $quoteData->ticket_status = "4";
                $quoteData->save();  
                return response()->json(['status'=>1,'message'=>'Ticket Pickup successfully'],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function createticketdata(Request $request) {
      $user = Auth::user();  
      $auth_id = $user->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $customer = Customer::select('id','customername')->where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();

      $tenture = Tenture::select('tenturename')->where('status','Active')->get();

      return response()->json(['status'=>1,'message'=>'success','customer'=>$customer,'tenture'=>$tenture],$this->successStatus);

    }

    public function getaddressbyid(Request $request) {

        $data['address'] = Address::where("customerid",$request->customerid)
                    ->get(["address","id"]);

        $customer = Customer::where('id', $request->customerid)->get();
        if(isset($customer[0])) {
            
            $sid = explode(',',$customer[0]->serviceid);

            $data['serviceData'] = Service::whereIn('id',$sid)->get(["servicename","id"]);
        }

        return response()->json(['status'=>1,'message'=>'success','data'=>$data],$this->successStatus);
    }

    public function createticket(Request $request) {

        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

        $serviceid = $request->serviceid;
        $servids = explode(',',$serviceid);

        $servicedetails = Service::select('servicename','productid')->whereIn('id', $servids)->get();

        foreach($servicedetails as $key => $value) {
          $pid[] = $value['productid'];
          $sname[] = $value['servicename'];
        }

        $productid = implode(',', array_unique($pid));
        
        $servicename = implode(',', $sname);

        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $data['userid'] = $worker->userid;
        $data['workerid'] = $worker->workerid;
        $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $serviceid;
        $data['servicename'] = $servicedetails[0]->servicename;
        if($request->productid) {
            $data['product_id'] = $request->productid;
        } else {
            $data['product_id'] = rtrim($productid, ',');
        }
        $data['radiogroup'] = $request->radiogroup;
        $data['frequency'] = $request->frequency;
        if($request->hour!=null || $request->hour!=0) {
          $data['time'] = $request->hour.' Hours';
        }
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';;
        }
        $data['price'] = $request->price;
        $data['etc'] = $request->etc;
        $data['description'] = $request->description;
        $data['customername'] =  $customer->customername;
        $data['address'] = $request->address;
        

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
        $output = json_decode($geocodeFromAddr);
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;

        $data['ticket_status'] = 1;
        
        Quote::create($data);

      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sharequote', ['address'=>$request->address, 'servicename'=>$servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$request->hour,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Ticket details!');
          $message->from($app_email,$app_name);
        }); 

       return response()->json(['status'=>1,'message'=>'Ticket Created Successfully'],$this->successStatus);    
    }

    public function servicedata(Request $request) {
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $services = Service::select('id','servicename')->where('userid', $worker->userid)->orWhere('workerid',$worker->workerid)->get();

       return response()->json(['status'=>1,'message'=>'success','services'=>$services],$this->successStatus);   
    }

    public function addcustomer(Request $request) {

      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $data['userid'] = $worker->userid;
      $data['workerid'] = $worker->workerid;
      $data['customername'] = $request->customername;
      $data['phonenumber'] = $request->phonenumber;
      $data['email'] = $request->email;
      $data['companyname'] = $request->companyname;
      if(isset($request->serviceid)) {
          //$data['serviceid'] = implode(',', $request->serviceid);
        $data['serviceid'] = $request->serviceid;
      }
        $img = $request->image;
        if (!empty($img)) {    
            $image_parts = explode(";base64,", $img);
            $value = base64_decode($image_parts[1]);
            $imageName = time()  . '.png';

            $path = 'uploads/customer/'.$imageName;
            $thumbPath = 'uploads/customer/thumbnail/'.$imageName;

            file_put_contents($path, $value);
            Image::make($path)->fit(300, 300)->save($thumbPath);

            $data['image'] = $imageName;
        } 
        Customer::create($data);

       return response()->json(['status'=>1,'message'=>'Customer Created Successfully'],$this->successStatus);   
    }

    public function ticketupdate(Request $request) {

      $quoteid = $request->ticketid;

      $quote = Quote::where('id', $quoteid)->get()->first();
      if($request->pointckbox) {
        //cheklist =implode(",", $request->pointckbox);
        $quote->checklist =  $request->pointckbox;
      } else {
        $quote->checklist = null;
      }
      $quote->customernotes =  $request->cnotes;
      
      $quote->save();
      return response()->json(['status'=>1,'message'=>'Ticket Updated successfully'],$this->successStatus); 
    }

    public function serviceview(Request $request) {
        $serviceid = $request->id; 
        $servicedata = Service::select('productid')->where('id', $serviceid)->get()->first();
        if($servicedata->productid!="") {
            $pidarray = explode(',', $servicedata->productid);
            $pdetails = Inventory::select('productname','id','category')->whereIn('id', $pidarray)->get();
             
            foreach ($pdetails as $key => $value) {
              $pname[] = $value['productname'];
              $category[] = $value['category'];
            }
            $catname = implode(',', $category);
        } else {
            $pname = array();
            $catname = "";
        }
        
        //$productname = implode(',', $pname);
        return response()->json(['status'=>1,'message'=>'Success','product'=>$pname,'category'=>$catname],$this->successStatus); 
    }

    public function sendinvoice(Request $request) {
        $customerid = $request->customerid;

      $customer = Customer::where('id', $customerid)->get()->first();

      //$serviceid = implode(',', $request->serviceid);
      $serviceid = $request->serviceid;
      $servicedetails = Service::select('servicename','productid')->whereIn('id', array($request->serviceid))->get();
       
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
      }

      //$productid = implode(',', $request->productid);
      $productid = $request->productid;
      $servicename = implode(',', $sname);

      $pdetails = Inventory::select('productname','id')->whereIn('id', array($request->productid))->get();
      
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
      } 
      
      $productname = implode(',', $pname);
      $quote = Quote::where('id', $request->id)->get()->first();

      $company = User::where('id', $quote->userid)->get()->first();
      if($company->image!=null) {
        $companyimage = url('').'/userimage/'.$company->image;
      } else {
        $companyimage = url('').'/uploads/servicebolt-noimage.png';
      }
      $cdefaultimage = url('').'/uploads/servicebolt-noimage.png';
      if($request->description) {
        $quote->description =  $request->description;
      } else {
        $quote->description = null;
      }
      $quote->serviceid = $serviceid;
      $quote->servicename = $servicedetails[0]->servicename;
      $quote->product_id = rtrim($productid, ',');
      $quote->price = $request->price;
      $quote->save();

      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sendinvoice', ['invoiceId'=>$quote->id,'address'=>$request->address, 'customername'=>$request->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$request->price,'time'=>$quote->giventime,'date'=>$quote->givendate,'description'=>$request->description,'companyname'=>$company->companyname,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productid], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Invoice details!');
          $message->from($app_email,$app_name);
        });

      return response()->json(['status'=>1,'message'=>'Invoice has been send successfully'],$this->successStatus); 
    }

    public function allproducData(Request $request) {

        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $productData = Inventory::select('id','productname')->where('user_id',$worker->userid)->orderBy('id','DESC')->get();

        return response()->json(['status'=>1,'message'=>'Success','product'=>$productData],$this->successStatus);    
    }

    public function googleplacekey(Request $request) {
        $googleplacekey = "AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc";

    return response()->json(['status'=>1,'message'=>'Success','google_place_key'=>$googleplacekey],$this->successStatus);    
    }

    public function manuallogin(Request $request) {
      
      $fulldate = date('l - F d, Y', strtotime($request->date));
      $fulldate = $fulldate;
      $timepicker = $request->clockin;
      $timepicker1 = $request->clockout;
        
      $datetime1 = new DateTime($timepicker);
      $datetime2 = new DateTime($timepicker1);
      $interval = $datetime1->diff($datetime2);
      $totalhours = $interval->format('%hh %im');
      
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

        $data['workerid'] = $worker->workerid;
        $data['starttime'] = $timepicker;
        $data['endtime'] = $timepicker1;
        $data['date'] = $fulldate;
        $data['date1'] = $request->date;
        $data['totalhours'] = $totalhours;

      Workerhour::create($data);
    

    return response()->json(['status'=>1,'message'=>'Success'],$this->successStatus); 
    }

    public function clockstatus(Request $request) {

       $auth_id = auth()->user()->id;
       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first(); 
       $workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->first(); 
       if(empty($workerh)) {
          $status = "Clock In";
       }
        elseif(!empty($workerh) && $workerh->date1==null) {
          $status = "Clock Out";
        }
        else {
            $status = "Clock In";
        }
        return response()->json(['status'=>1,'message'=>'Success','clockstatus'=>$status],$this->successStatus);
    }

    public function clockin(Request $request) {
      
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

      $fulldate = date('l - F d, Y', strtotime($request->date));
      $fulldate = $fulldate;

      $starttime = $request->starttime;
      $fulldate = $fulldate;
      $data['workerid'] = $worker->workerid;
      $data['starttime'] = $starttime;
      $data['date'] = $fulldate;
      Workerhour::create($data);
      return response()->json(['status'=>1,'message'=>'Success'],$this->successStatus);     
    }

    public function clockout(Request $request) {
      
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

      $date1=date('Y-m-d');
      

      $fulldate = date('l - F d, Y', strtotime($request->date));
      $fulldate = $fulldate;

      $workerhour = Workerhour::where('workerid', $worker->workerid)->where('date', $fulldate)->orderBy('id','desc')->get()->first();

      $starttime = $workerhour->starttime;
      $endtime = $request->endtime;

      $datetime1 = new DateTime($starttime);
      $datetime2 = new DateTime($endtime);
      $interval = $datetime1->diff($datetime2);
      $totalhours = $interval->format('%hh %im');

      $workerhour->endtime = $request->endtime;
      $workerhour->totalhours = $totalhours;
      $workerhour->date1 = $date1;
      $workerhour->save();

      return response()->json(['status'=>1,'message'=>'Success'],$this->successStatus);     
    }

    public function resendSchedule(Request $request) {
        
      $quote = Quote::where('id', $request->ticketid)->get()->first();
      $serviceid = $request->serviceid;

      $servicedetails = Service::select('servicename','productid')->whereIn('id', array($request->serviceid))->get();
       
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 

      $productid = $request->productid;

      $pdetails = Inventory::select('productname','id')->whereIn('id', array($request->productid))->get();
       
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
      } 

      $servicename = implode(',', $sname);
      $productname = implode(',', $pname);

      $data['customerid'] =  $quote->customerid;
      $data['userid'] =  $quote->userid;
      $data['workerid'] =  $quote->workerid;
      $data['serviceid'] = $serviceid;
      $data['servicename'] = $servicedetails[0]->servicename;
      $data['product_id'] = $productid;
      $data['product_name'] = $pdetails[0]->productname;
      $data['personnelid'] = $quote->personnelid;
      $data['radiogroup'] = $request->radiogroup;
      $data['frequency'] = $request->frequency;
        if($request->hour!=null || $request->hour!=0) {
          $data['time'] = $request->hour.' Hours';
        }
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';;
        }
      $data['price'] = $request->price;
      $data['etc'] = $request->etc;
      $data['description'] = $request->description;
      $data['customername'] =  $quote->customername;
      $data['address'] = $quote->address;
      $data['latitude'] = $quote->latitude;
      $data['longitude'] = $quote->longitude;
      $data['checklist'] = $quote->checklist;
      $data['ticket_status'] = 1;
      Quote::create($data);

      return response()->json(['status'=>1,'message'=>'Ticket has been Scheduled Successfully'],$this->successStatus); 
    }

    public function getResendScheduleData(Request $request) {
        $quotedetails = Quote::select("time","minute","radiogroup","etc","frequency")->where('id', $request->id)->get();
        $time =  explode(" ", $quotedetails[0]->time);
        $minute =  explode(" ", $quotedetails[0]->minute);
        $rgroup = $quotedetails[0]->radiogroup;
        $etc= $quotedetails[0]->etc;

        $data[] = array(
            'hour' => $time[0],
            'minute' => $minute[0], 
            'radiogroup' => $rgroup,
            'frequency' => $quotedetails[0]->frequency,
            'etc' => $etc, 
        );
        $radiogroup= array("perhour","flatrate","recurring");
        
        $frequency = Tenture::where('status','Active')->get();

        return response()->json(['status'=>1,'message'=>'Success','data'=>array($data[0]),'radiogroup'=>$radiogroup,'frequency'=>$frequency],$this->successStatus);
    }

    public function gettime(Request $request) {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $userData = User::select('openingtime','closingtime')->where('id',$worker->userid)->first();
      return response()->json(['status'=>1,'message'=>'Success','data'=>$userData],$this->successStatus);  
    }

    public function getservicedatabyid(Request $request) {
       $quote = Quote::select('customerid')->where('id', $request->id)->first();
       $customer = Customer::where('id', $quote->customerid)->get();

       $sid = explode(',',$customer[0]->serviceid);

       $serviceData = Service::select('id','servicename')->whereIn('id',$sid)->orderBy('id','ASC')->get(); 

        if ($serviceData) {
            return response()->json(['status'=>1,'message'=>'success','services'=>$serviceData],$this->successStatus);
        }
         
    }

    public function pendingticket(Request $request) {
     
        $user = Auth::user();
        $pendingData = Quote::select('id','servicename','giventime','customername','price')->where('personnelid',$user->workerid)->where('ticket_status','2')->limit('2')->orderBy('id','DESC')->get();

        return response()->json(['status'=>1,'message'=>'Success','pendingdata'=>$pendingData],$this->successStatus);    
    }

    public function saveaddress(Request $request) {
       
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $cid = $request->customerid;
        $data['authid'] = $worker->workerid;
        $data['customerid'] = $cid;
        $data['address'] = $request->address;
        Address::create($data);
        return response()->json(['status'=>1,'message'=>'address added successfully'],$this->successStatus);
    }

    public function pto(Request $request) {
       
       $validator = Validator::make(request()->all(), [
            'date' => 'required',
            'notes' => 'required',
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['date'])){
                foreach($errors['date'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['notes'])){
                foreach($errors['notes'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('workerid','userid')->where('id',$auth_id)->first();

        if($request->date!="") {
        $dates = explode(',',$request->date);
        if($request->notes) {
          $notes = $request->notes;
        } else {
          $notes = "";
        }
        foreach($dates as $key => $value) {
          $old_date_timestamp = strtotime($value);
          $fulldate = date('l - F d, Y', $old_date_timestamp);   
          $data['workerid'] = $worker->workerid;
          $data['userid'] = $worker->userid;
          $data['date'] = $fulldate;
          $data['date1'] = $value;
          $data['notes'] = $notes;
          Workertimeoff::create($data);
        }
      }
        return response()->json(['status'=>1,'message'=>'PTO added successfully'],$this->successStatus); 
    }

    public function ptolist(Request $request) {
      
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('workerid','userid')->where('id',$auth_id)->first();
        $timeoff = DB::table('timeoff')->where('workerid',$worker->workerid)->orderBy('id','desc')->get();

        if(count($timeoff)>0) {
            return response()->json(['status'=>1,'message'=>'Success','data'=>$timeoff],$this->successStatus);    
        } else {
            $timeoff = [];
            return response()->json(['status'=>1,'message'=>'Success','data'=>$timeoff],$this->successStatus);
        }
        
    }

    public function sethours(Request $request) {
        
        $validator = Validator::make(request()->all(), [
            'date' => 'required',
            'starttime' => 'required',
            'endtime' => 'required',
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['date'])) {
                foreach($errors['date'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['starttime'])) {
                foreach($errors['starttime'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['endtime'])) {
                foreach($errors['endtime'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

            $fulldate = date('l - F d, Y', strtotime($request->date));
            $fulldate = $fulldate;
            $timepicker = $request->starttime;
            $timepicker1 = $request->endtime;

            $datetime1 = new DateTime($timepicker);
            $datetime2 = new DateTime($timepicker1);
            $interval = $datetime1->diff($datetime2);
            $totalhours = $interval->format('%hh %im');

            $auth_id = auth()->user()->id;
            $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

            $data['workerid'] = $worker->workerid;
            $data['starttime'] = $timepicker;
            $data['endtime'] = $timepicker1;
            $data['date'] = $fulldate;
            $data['date1'] = $request->date;
            $data['totalhours'] = $totalhours;
            Workersethour::create($data);
            
            return response()->json(['status'=>1,'message'=>'Success'],$this->successStatus);
    }

    public function timesheetview(Request $request) {
        
        $validator = Validator::make(request()->all(), [
            'date' => 'required'
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['date'])) {
                foreach($errors['date'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>1,'message'=>$msg_err],$this->successStatus);
        }

            $fulldate = date('l - F d, Y', strtotime($request->date));
            $fulldate = $fulldate;

            $auth_id = auth()->user()->id;
            $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

            $timesheetdata = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date',$fulldate)->orderBy('id','desc')->get();

            $workhours = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date',$fulldate)->orderBy('id','desc')->first();
            $lasttime = array();
            $data = array();
            if(count($timesheetdata)>0) {
                foreach($timesheetdata as $key => $value) {
                    $data[] = array (
                     'starttime' =>$value->starttime,
                     'endtime' => $value->endtime,
                     'totalhours' => $value->totalhours,
                    );
                }
            }
            if($workhours) {
                $lasttime = array(
                   'totalhours'=> $workhours->totalhours,
                   'starttime'=> $workhours->starttime,
                   'endtime'=> $workhours->endtime,
                );
            }

          
        return response()->json(['status'=>1,'message'=>'Success','data'=>$data,'latesttime'=>$lasttime],$this->successStatus);
    }

    public function deleteaddressbyid(Request $request) {

        $validator = Validator::make(request()->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['id'])){
                foreach($errors['id'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $address = Address::find($request->id)->delete();

        return response()->json(['status'=>1,'message'=>'Address deleted successfully!'],$this->successStatus);
    }

    public function timesheetviewfilter(Request $request) {
        
        $validator = Validator::make(request()->all(), [
            'fromdate' => 'required',
            'todate' => 'required'
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['fromdate'])) {
                foreach($errors['fromdate'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['todate'])) {
                foreach($errors['todate'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>1,'message'=>$msg_err],$this->successStatus);
        }

            $fromdate = date('Y-m-d', strtotime($request->fromdate));
            $todate = date('Y-m-d', strtotime($request->todate));
            
            $auth_id = auth()->user()->id;
            $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

            $timesheetdata = DB::table('workerhour')->where('workerid',$worker->workerid)->whereDate('date1','>=', $fromdate)->whereDate('date1','<=', $todate)->orderBy('id','desc')->get();

            $data = array();
            if(count($timesheetdata)>0) {
                foreach($timesheetdata as $key => $value) {
                    $data[] = array (
                     'starttime' =>$value->starttime,
                     'endtime' => $value->endtime,
                     'totalhours' => $value->totalhours,
                     'date' =>$value->date1,
                    );
                }
            }
            
        return response()->json(['status'=>1,'message'=>'Success','data'=>$data],$this->successStatus);
    }

    public function getbalancesheet(Request $request) {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $balancesheet = Balancesheet::where('userid', $worker->userid)->where('workerid', $worker->workerid)->orderBy('id','DESC')->get();

      return response()->json(['status'=>1,'message'=>'Success','data'=>$balancesheet],$this->successStatus);  
    }

    public function customerupdate(Request $request) {
      $validator = Validator::make(request()->all(), [
            'customername' => 'required',
            'phonenumber' => 'required',
            'companyname' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['customername'])){
                foreach($errors['customername'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['phonenumber'])){
                foreach($errors['phonenumber'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['companyname'])){
                foreach($errors['companyname'] as $e){
                    $msg_err .= $e;
                }
            }
            if(isset($errors['email'])){
                foreach($errors['email'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

      $customerid = $request->customerid;

      $customer = Customer::where('id', $customerid)->get()->first();
      
      if($request->serviceid!="") {
        $customer->serviceid = $request->serviceid;
      }
      $customer->customername = $request->customername;
      $customer->phonenumber = $request->phonenumber;
      $customer->companyname = $request->companyname;
      $customer->email = $request->email;
      
      $img = $request->image;

      if (!empty($img)) {    
          $image_parts = explode(";base64,", $img);
          $value = base64_decode($image_parts[1]);
          $imageName = time()  . '.png';

          $path = 'uploads/customer/'.$imageName;
          $thumbPath = 'uploads/customer/thumbnail/'.$imageName;

          file_put_contents($path, $value);
          Image::make($path)->fit(300, 300)->save($thumbPath);

          $customer->image = $imageName;
      }
      $customer->save();
      
      return response()->json(['status'=>1,'message'=>'Customer has been updated successfully'],$this->successStatus); 
    }

    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        
        $user = User::where("email", "=", $request->email)->where('role', 'worker')->first();
        if (!empty($user)) {
            $email = $request->email;
            $code = mt_rand(1000,9999);
            $token = Hash::make($code);
            $passwordreset = PasswordReset::where('email', '=', $email)->first();
            if (empty($passwordreset)) {
                $passwordreset = new PasswordReset;
                $passwordreset->email = $email;
            }
            $passwordreset->token = $token;
            $passwordreset->created_at = Carbon::now();
            $passwordreset->save();

              $app_name = 'ServiceBolt';
              $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
              $email = $request->email;
                
              Mail::send('mail_templates.appforgotpaasword', ['otpcode'=>$code], function($message) use ($app_name,$app_email,$email) {
                  $message->to($email)
                  ->subject('Forgot password OTP Code');
                  $message->from($app_email,$app_name);
                }); 

            return response()->json(['status'=>1,'message'=>"We will send 4 digit code to your email for verification.","code"=>$code],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>"This Email Address does not exists!"],$this->successStatus);
        }
    }

    public function verification_code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'code' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $response = array('status'=>0,'message' => $validator->errors()->toArray());
            return response()->json($response,$this->successStatus);
        }
        $user = User::where("email", "=", $request->email)->where('role', 'worker')->first();
        if (!empty($user)) {
            
            $email = $request->email;
            $usercode = $request->code;
            $passwordreset = PasswordReset::where('email', '=', $email)->first();
            $email = $request->email;


            $user = PasswordReset::where('email', '=', $email)->first();
            if (!empty($user)) {
                $hashedPassword = $user->token;
                if (Hash::check($usercode, $hashedPassword)) {
                    $response = array('status'=>1,'message' => "Success");
                    return response()->json($response,$this->successStatus);
                } else {

                    $response = array('status'=>0,'message' => "OTP Code not validate");
                    return response()->json($response,$this->successStatus);
                }
            } else {
                $response = array('status'=>0,'message' => "Email Not Found");
                return response()->json($response,$this->successStatus);
            }
        } else {
            $response = array('status'=>0,'message' => "This Email does not exists");
            return response()->json($response,$this->successStatus);
        }
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);
        if ($validator->fails()) {
            $response = array('status'=>0,'message' => $validator->errors()->toArray());
            return response()->json($response,$this->successStatus);
        }
        $user = User::where("email", "=", $request->email)->where('role', 'worker')->first();
        if (!empty($user)) {
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                DB::table('password_resets')->where(['email'=> $request->email])->delete();
                $response = array('status'=>1,'message' => "Your password has been changed!");
                return response()->json($response,$this->successStatus);       
            } else {
                $response = array('status'=>0,'message' =>"Something went wrong!");
                return response()->json($response,$this->errorStatus);       
            }
        } else {
            $response = array('status'=>0,'message' => "Email Not Found");
            return response()->json($response,$this->successStatus);
        }
    }

    public function getnotification(Request $request) {

      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $notification = DB::table('appnotification')->where('pid', $worker->workerid)->orderBy('id','DESC')->get();

      return response()->json(['status'=>1,'message'=>'Success','data'=>$notification],$this->successStatus);  
    }

    public function paynow(Request $request) {
        $validator = Validator::make($request->all(), [
            'ticketid' => 'required',
            'payment_mode' => 'required',
            'payment_amount' => 'required',
        ]);

        if($validator->fails()) {
            $response = array('status'=>0,'message' => $validator->errors()->toArray());
            return response()->json($response,$this->successStatus);
        }

        $quote = Quote::where('id', $request->ticketid)->first();
        $quote->payment_amount = $request->payment_amount;
        $quote->payment_mode = $request->payment_mode;
          
        if(!empty($request->checknumber)) {
            $quote->checknumber = $request->checknumber;
        }

        $quote->save();

        return response()->json(['status'=>1,'message'=>'Payment has been successfully'],$this->successStatus);  
    }

    public function paynowsuccess(Request $request) {
        $validator = Validator::make($request->all(), [
            'ticketid' => 'required',
        ]);

        if($validator->fails()) {
            $response = array('status'=>0,'message' => $validator->errors()->toArray());
            return response()->json($response,$this->successStatus);
        }

        $quoteData = DB::table('quote')->select('*')->where('id',$request->ticketid)->first();
        
        if($quoteData->payment_mode !="") {
          $paymentpaid = "1";
          $payment_mode = $quoteData->payment_mode;
        } else {
          $paymentpaid = "0";
          $payment_mode = "";
        }

        return response()->json(['status'=>1,'paymentstatus'=>$paymentpaid,'payment_mode'=>$payment_mode],$this->successStatus);  
    }

}
