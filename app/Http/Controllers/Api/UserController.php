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
//use Illuminate\Support\Facades\Notification;
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
use App\Models\Notification;
use App\Events\MyEvent;
use App\Models\Checklist;
use PDF;
use App\Models\Hourlyprice;

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
        
        if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'role' => 'worker'])){ 
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

                DB::table('personnel')->where('id','=',$user->workerid)
                      ->update([ 
                          "checkstatus"=>"online"
                ]);
            
            //logic for other device id token expired         
            $ptoken = DB::table('personal_access_tokens')->where('tokenable_id',Auth::user()->id)->orderBy('id','desc')->get();
                    
            if(count($ptoken) > 1) {
              DB::table('personal_access_tokens')->where('token', $ptoken[1]->token)->delete();  
            }
            //end
            return response()->json(['status'=>1,'message'=>__('You are successfully logged in.'),'token'=>$token,'data'=>$data1],$this->successStatus); 
        } 
        else{ 
            return response()->json(['status'=>0,'message'=>__('You have entered an invalid username or password.')],$this->successStatus); 
        }
    }

    public function loginerror() {
        return response()->json(['status'=>0,'message'=>'Unauthenticated'],$this->errorresultStatus);   
    }

     public function workerlogout() {
        $workerid = auth()->user()->workerid;
        DB::table('personnel')->where('id','=',$workerid)
          ->update([ 
              "checkstatus"=>"offline","device_token"=>null
        ]);
          Auth::user()->tokens()->delete();
      //Auth::logout();

      return response()->json(['status'=>1,'message'=>__('You are successfully logged out.')],$this->successStatus); 
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
        $auth_id = Auth::user()->userid;
        $placekey = custom_userinfo($auth_id);
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
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

        $todayservicecall = DB::table('quote')
                            ->select("*",
                                \DB::raw('(CASE 
                                    WHEN quote.parentid = "0" THEN quote.id 
                                    ELSE quote.parentid 
                                    END) AS id'))->where('personnelid',$worker->workerid)->whereIn('ticket_status',['2','3','4'])->whereDate('created_at', Carbon::today())->limit('2')->orderBy('id','DESC')->get();

        $customerData = DB::table('quote')->select('quote.*', 'customer.id','customer.phonenumber','customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status','2')->limit('2')->orderBy('quote.id','DESC')->get();

        $todaydate = date('l - F d, Y');
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname','services.color',
                                \DB::raw('(CASE 
                                    WHEN quote.parentid = "0" THEN quote.id 
                                    ELSE quote.parentid 
                                    END) AS id'))->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',[2,3,4])->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();
        
        $completedticketcount = DB::table('quote')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',"3")->where('givendate', $todaydate)->count();
        
        $pendingticketcount = DB::table('quote')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',array('2','3','4'))->where('givendate', $todaydate)->count();

        if($pendingticketcount!=0) {
            $dailyprogress = $completedticketcount/$pendingticketcount*100;
            $dailyprogress =  round($dailyprogress,2);
        } else {
            $dailyprogress = 0;
        }

        return response()->json(['status'=>1,'message'=>'success','todayticket'=>$todayservicecall,'customerData'=>$customerData,'scheduleData'=>$scheduleData,'dailyprogress'=>$dailyprogress],$this->successStatus);
    }

    public function myticketData(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;
        @$workersdata = Personnel::where('id',$user->workerid)->first();
        @$permissonarray = explode(',',$workersdata->ticketid);
        if(in_array("Unclose Ticket", $permissonarray)) {
          $reopen = 1;
        } else {
          $reopen = 0;
        }
        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first(); 
        //$ticketdata = DB::table('quote')->where('personnelid',$worker->workerid)->whereIn('ticket_status',array('2','4'))->get();
        
        $ticketdata = DB::table('quote')
            ->select("*",
            \DB::raw('(CASE 
                WHEN quote.parentid = "0" THEN quote.id 
                ELSE quote.parentid 
                END) AS id'))->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',array('2','4'))->get();

        if ($ticketdata) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$ticketdata,'reopenticket'=>$reopen],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function completedticketdata(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first(); 
        $ticketdata = DB::table('quote')
            ->select("*",
            \DB::raw('(CASE 
                WHEN quote.parentid = "0" THEN quote.id 
                ELSE quote.parentid 
                END) AS id'))->where('personnelid',$worker->workerid)->whereIn('ticket_status',array('3'))->get();

        if ($ticketdata) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$ticketdata],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function myticketDetail(Request $request) {
        $main_array =array();
        $user = Auth::user();
        $personnelid = Auth::user()->workerid;
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

        $quoteData = DB::table('quote')->select('quote.id','quote.primaryname','quote.tax','quote.customerid','quote.customername','quote.address','quote.latitude','quote.longitude','quote.etc','quote.givendate','quote.giventime','quote.givenendtime','quote.givenstartdate','quote.givenenddate','quote.time','quote.minute','quote.description','quote.product_id','quote.serviceid','quote.imagelist', 'customer.phonenumber','customer.email','quote.ticket_status','quote.customernotes','quote.checklist','quote.price','quote.payment_mode')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.id',$ticketId)->first();
        
        if($quoteData) {
            $serviceidarray = explode(',', $quoteData->serviceid);
            $servicedetails = Service::select('id','servicename','price')->whereIn('id', $serviceidarray)->get();
            $sum = 0;
            $serearray=array();
            foreach ($servicedetails as $key => $value) {
                $horalyp = Hourlyprice::select('hour','minute')->where('ticketid',$ticketId)->where('serviceid',$value['id'])->first();
                $hours = "";
                if(isset($horalyp->hour)) {
                   $hours = $horalyp->hour; 
                }
                $minutes = "";
                if(isset($horalyp->minute)) {
                   $minutes = $horalyp->minute; 
                }
                $serearray[] = array (
                     'id' =>$value['id'],
                     'servicename' => $value['servicename'],
                     'price' => $value['price'],
                     'hrs' => $hours,
                     'mins' => $minutes,
                   );
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
                     'price' => $value['price'],
                );
              //$pname[] = $value['productname'];
              $sum1+= (int)$value['price'];
            }

            if($quoteData->checklist!="") {
                $pointbox =  explode(',', $quoteData->checklist);   
            } else {
                $pointbox = array();  
            }

            if($quoteData->imagelist!="") {
                $imagearray1 = explode(',', $quoteData->imagelist);
                
                foreach ($imagearray1 as $key => $value) {
                    $imgtype= explode('.',strtolower($value));

                    if($imgtype[1]=="mp4" || $imgtype[1]=="3gp" || $imgtype[1]=="mov" || $imgtype[1]=="avi" || $imgtype[1]=="wmv" || $imgtype[1]=="flv" || $imgtype[1]=="m3u8") {
                        $type = "video";
                        $videothumb = "videothumb.png";
                    } else {
                        $type = "image";
                        $videothumb = "";
                    }
                    $imagearray[] = array (
                        'name' =>$value,
                        'type' => $type,
                        'videothumb'=>$videothumb
                         
                    );
                }
            } else {
               $imagearray = array(); 
            }

            $addressnote = Address::select('notes')->where('customerid',$quoteData->customerid)->first();
            $addressinfo = Address::select('id')->where('customerid',$quoteData->customerid)->where('address',$quoteData->address)->first();
            if($addressnote->notes !=null) {
                $addressnote = $addressnote->notes;
            } else {
                $addressnote = "--";
            }
            array_push($main_array, [
                   'id'=>$quoteData->id,
                   'personnelid'=>$personnelid,
                   'primaryid'=>$quoteData->primaryname,
                   'price'=>$quoteData->price,
                   'tax'=>$quoteData->tax,
                   'addressid'=>@$addressinfo->id,
                   'customerid'=>$quoteData->customerid,
                   'customername'=>$quoteData->customername,
                   'address'=>$quoteData->address,
                   'latitude'=>$quoteData->latitude,
                   'longitude'=>$quoteData->longitude,
                   'etc'=>$quoteData->etc,
                   'givendate'=>$quoteData->givendate,
                   'giventime'=>$quoteData->giventime,
                   'time'=>$quoteData->time,
                   'minute'=>$quoteData->minute,
                   'givenstartdate'=>$quoteData->givenstartdate,
                   'givenenddate'=>$quoteData->givenenddate,
                   'description'=>$quoteData->description,
                   'phonenumber'=>$quoteData->phonenumber,
                   'email'=>$quoteData->email,
                   'ticket_status'=>$quoteData->ticket_status,
                   'addressnote'=>$addressnote,
                   'imagevideo'=>$imagearray,
                   'pointcheckbox'=>$pointbox,
                   'servicedata'=>$serearray,
                   'productdata'=>$proarray,
                   'customernotes'=>$quoteData->customernotes,
                   'payment_mode'=>$quoteData->payment_mode,
                ]);  
            
            //$servicename = implode(',', $sname);

           // $totalprice = $sum+$sum1;
            $totalprice = (float)$quoteData->price+(float)$quoteData->tax;
            $totalprice = number_format((float)$totalprice, 2, '.', '');
            //$totalprice = $quoteData->price;

           // $productname = implode(',', $pname);

           @$workersdata = Personnel::where('id',$personnelid)->first();
           @$permissonarray = explode(',',$workersdata->ticketid);
           if(in_array("See Previous Tickets", $permissonarray)) {     
            $prequoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber')->leftjoin('customer', 'customer.id', '=', 'quote.customerid')->where('quote.customerid',$quoteData->customerid)->whereIn('quote.ticket_status',array('3'))->get();
            } else {
              $prequoteData = array();
              //DB::table('quote')->select('quote.*', 'customer.phonenumber')->leftjoin('customer', 'customer.id', '=', 'quote.customerid')->where('quote.customerid',$quoteData->customerid)->whereIn('quote.ticket_status',array('2','4'))->get();  
            }
            if(in_array("See Price of Previous Tickets", $permissonarray)) {
              $pricevisible = 1;
            } else {
              $pricevisible = 0;
            }
            if(in_array("Unclose Ticket", $permissonarray)) {
              $reopen = 1;
            } else {
              $reopen = 0;
            }
            return response()->json(['status'=>1,'message'=>'success','data'=>$main_array,'totalprice'=>$totalprice,'checklistData'=>$checklistData,'priviousTicketData'=>$prequoteData,'pricevisible'=>$pricevisible,'reopenticket'=>$reopen],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }

    public function myserviceData(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;
        @$workersdata = Personnel::where('id',$user->workerid)->first();
        @$permissonarray = explode(',',$workersdata->ticketid);
        if(in_array("Add Service", $permissonarray)) {
          $opentab = 1;
        } else {
          $opentab = 0;
        }
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $servicedetails = DB::table('services')->select('services.id','services.userid','services.servicename','services.price','services.time as hours','services.minute','services.description')->where('userid',$worker->userid)->orderBy('services.id','desc')->get();
        // $serviceids = DB::table('quote')->select('serviceid','customerid')->where('personnelid',$worker->workerid)->get();

        //   foreach($serviceids as $key => $value) {
        //       $serviceidss[] = $value->serviceid;
        //   }

        //   foreach($serviceids as $key => $value) {
        //       $customerids[] = $value->customerid;
        //   }

        // $servicedetails = DB::table('quote')->whereIn('serviceid',$serviceidss)->whereIn('customerid',$customerids)->orderBy('id','DESC')->get();

        if ($servicedetails) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$servicedetails,'opentab'=>$opentab],$this->successStatus);
        } else {
            return response()->json(['status'=>0,'message'=>'data not found'],$this->errorStatus);
        }
    }
    public function myproductData(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;
        
        @$workersdata = Personnel::where('id',$user->workerid)->first();
        @$permissonarray = explode(',',$workersdata->ticketid);
        if(in_array("Add Product", $permissonarray)) {
          $opentab = 1;
        } else {
          $opentab = 0;
        }

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        
       //  $productids = DB::table('quote')->select('product_id')->where('personnelid',$worker->workerid)->groupBy('product_id')->get();

       //  foreach($productids as $key => $value) {
       //    $product_ids[] = $value->product_id;
       // }
      
        $productdata = DB::table('products')->select('products.id','products.productname','products.quantity','products.sku','products.price','products.description','products.category')->where('user_id',$worker->userid)->orderBy('products.id','asc')->get();

        

        if ($productdata) {
                return response()->json(['status'=>1,'message'=>'success','data'=>$productdata,'opentab'=>$opentab],$this->successStatus);
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

      @$workersdata = Personnel::where('id',$worker->workerid)->first();
      @$permissonarray = explode(',',$workersdata->ticketid);
      //dd($permissonarray);
      if(in_array("View All Customers", $permissonarray)) {
        // $cdata = DB::table('quote')->select('customerid')->where('personnelid',$worker->workerid)->groupBy('customerid')->get();
        // if(count($cdata)>0) {
        //     foreach($cdata as $key=>$value) {
        //       $cids[] = $value->customerid;
        //     }
        //         $customerData = DB::table('customer')->where('userid',$worker->userid)->whereIn('id',$cids)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();   
        // } else {
        //     $customerData = array();
        // }
      $customerData = DB::table('customer')->where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();   

    } else {
        //$customerData = DB::table('customer')->where('workerid',$worker->workerid)->orderBy('id','DESC')->get();
        $cdata = DB::table('quote')->select('id','customerid','ticket_status')->where('personnelid',$worker->workerid)->where('ticket_status','!=',3)->groupBy('customerid')->get();
          if(count($cdata)>0) {
            foreach($cdata as $key=>$value) {
              $cids[] = $value->customerid;
            }
            $customerData = DB::table('customer')->where('workerid',$worker->workerid)->orWhereIn('id',$cids)->orderBy('id','DESC')->get();   
          } else {
           $customerData = DB::table('customer')->where('workerid',$worker->workerid)->orderBy('id','DESC')->get();
          }
    }

     $tenture = Tenture::select('tenturename')->where('status','Active')->get();  

        if ($customerData) {
                return response()->json(['status'=>1,'message'=>'success','customer'=>$customerData,'tenture'=>$tenture],$this->successStatus);
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
            $servicearray = Service::select('id','servicename','price')->whereIn('id',$serviceids)->get();

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

        //$customerAddress = Address::where('customerid',$customerid)->get();

        $customerAddress = array();
        $cdata = DB::table('quote')->select('customerid','address')->where('customerid',$customerid)->where('personnelid',$user->workerid)->where('userid',$user->userid)->groupBy('address')->get();
            foreach($cdata as $key=>$value) {
              $customerAddress[] = Address::select('id')->where('authid',$user->userid)->where('customerid',$customerid)->where('address',$value->address)->first();
            }
          $cidss = array();
          foreach($customerAddress as $key=>$value) {
           $cidss[] =  $value->id;
          }
          $customerAddress = Address::whereIn('id',$cidss)->where('customerid',$customerid)->get()->toArray();

          $cadd = Address::where('authid',$auth_id)->where('customerid',$customerid)->get()->toArray();
          
         $customerAddress  = array_merge($customerAddress,$cadd);

        @$workersdata = Personnel::where('id',$user->workerid)->first();
        @$permissonarray = explode(',',$workersdata->ticketid);
        
        if(in_array("See Previous Tickets", $permissonarray)) {
            $recentTicket = Quote::where('customerid',$customerid)->where('personnelid','!=',null)->where('parentid','=',"")->where('givendate','!=',null)->orderBy('id','DESC')->get();
        } else {
            $recentTicket = Quote::where('customerid',$customerid)->where('personnelid','!=',null)->where('parentid','=',"")->where('givendate','!=',null)->whereIn('ticket_status',array('2','4'))->orderBy('id','DESC')->get();
        }

        if(in_array("See Price of Previous Tickets", $permissonarray)) {
          $pricevisible = 1;
        } else {
          $pricevisible = 0;
        }
        if($customerData) {
                return response()->json(['status'=>1,'message'=>'success','customerData'=>$data1,'connectedAddress'=>$customerAddress,'recentTickets'=>$recentTicket,'pricevisible'=>$pricevisible],$this->successStatus);
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

        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname','services.color',
            \DB::raw('(CASE 
                WHEN quote.parentid = "0" THEN quote.id 
                ELSE quote.parentid 
                END) AS id'))->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',[2,3,4])->where('quote.givendate',$date)->orderBy('quote.id','ASC')->get();
                
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
           $ticket1 = Quote::where('parentid', $ticketId)->get()->first();
           if($ticket1!=null || $ticket1!="") {
                $ticket1->ticket_status = 3;
                $ticket1->save();
           }

            $ticketid ='#'.$request->ticketId;
            $ticketsub = "Ticket $ticketid has been closed";

            $data1['uid'] = $quoteData->userid;
            $data1['pid'] = $quoteData->personnelid;
            $data1['ticketid'] = $request->ticketId;
            $data1['message'] = $ticketsub;

            Notification::create($data1);
            event(new MyEvent($data1));

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
                $ticket1 = Quote::where('parentid', $ticketId)->get()->first();
                  //new logic
                  if($ticket1!=null || $ticket1!="") {
                    $ticket1->ticket_status = 4;
                    $ticket1->save();
                  }

                  $pidarray = explode(',', $quoteData->product_id);

                  if(!empty($quoteData->product_id)) {
                    foreach($pidarray as $key => $pid) {
                      $productd = Inventory::where('id', $pid)->first();
                      if(!empty($productd)) {
                        $productd->quantity = (@$productd->quantity) - 1;
                        $productd->save();
                      }

                    }
                  }

                  $personeldata =Quote::select('quote.personnelid','personnel.personnelname')->leftjoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.id', $request->ticketId)->get()->first();

                  $ticketid ='#'.$request->ticketId;
                  $ticketsub = "Ticket $ticketid picked up by $personeldata->personnelname";

                  $data1['uid'] = $quoteData->userid;
                  $data1['pid'] = $quoteData->personnelid;
                  $data1['ticketid'] = $request->ticketId;
                  $data1['message'] = $ticketsub;

                  Notification::create($data1);
                  event(new MyEvent($data1));

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

        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', $worker->userid)->first();

        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

        $serviceid = $request->serviceid;
        $servids = explode(',',$serviceid);

        $servicedetails = Service::select('servicename','productid','price')->whereIn('id', $servids)->get();
        $sum = 0;
        foreach($servicedetails as $key => $value) {
          $pid[] = $value['productid'];
          $sname[] = $value['servicename'];
          $txvalue = 0;
          if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
                $txvalue = $value['price']*$userdetails->servicevalue/100; 
            } else {
                $txvalue = 0;
            }
          }
          $sum+= $txvalue;
        }

        //$productid = implode(',', array_unique($pid));
        
        $servicename = implode(',', $sname);

        $productid = "";
        $productname = "";
        $productname1 = "";

        $sum1 = 0;
        $txvalue1 = 0;
        if($request->productid!="") {
          $productid = explode(',', $request->productid);

          $pdetails = Inventory::select('productname','id','price')->whereIn('id', $productid)->get();

            foreach ($pdetails as $key => $value) {
              $pname[] = $value['productname'];
              if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
                if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
                    $txvalue1 = $value['price']*$userdetails->productvalue/100; 
                } else {
                    $txvalue1 = 0;
                }
                }
                $sum1+= $txvalue1;

                  $productd = Inventory::where('id', $value['id'])->first();
                  if(!empty($productd)) {
                    $productd->quantity = (@$productd->quantity) - 1;
                    $productd->save();
                  }
            } 
            $productname = implode(',', $pname);
            $productname1 = $pdetails[0]->productname;

        }

        $totaltax = $sum+$sum1;
        $totaltax = number_format($totaltax,2);
        $totaltax = preg_replace('/[^\d.]/', '', $totaltax);
        
        $servicename = implode(',', $sname);

        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $data['userid'] = $worker->userid;
        $data['workerid'] = $worker->workerid;
        $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $serviceid;
        $data['servicename'] = $servicedetails[0]->servicename;
        $data['product_name'] = $productname1;
        if($request->productid) {
            $data['product_id'] = $request->productid;
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
        $data['tickettotal'] = $request->ticketprice;
        $data['tax'] = $totaltax;
        $data['etc'] = $request->etc;
        $data['description'] = $request->description;
        $data['customername'] =  $customer->customername;
        $data['address'] = $request->address;
        

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $auth_id = Auth::user()->userid;
        $placekey = custom_userinfo($auth_id);
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
        $output = json_decode($geocodeFromAddr);
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;

        $data['ticket_status'] = 1;
        
        $quotelastid = Quote::create($data);
        $quoteee = Quote::where('id', $quotelastid->id)->first();
        $randomid = 100;
        $quoteee->invoiceid = $randomid.''.$quotelastid->id;
        $quoteee->save();
    if($customer->email!=null) {    
      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
      $name = "Ticket";  
      Mail::send('mail_templates.sharequote', ['address'=>$request->address, 'servicename'=>$servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'productname'=>$productname,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description,'name'=>$name], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Ticket details!');
          //$message->from($app_email,$app_name);
        }); 
     }
       return response()->json(['status'=>1,'message'=>'Ticket Created Successfully'],$this->successStatus);    
    }

    public function servicedata(Request $request) {
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $services = Service::select('id','servicename','price')->where('userid', $worker->userid)->orWhere('workerid',$worker->workerid)->get();

       return response()->json(['status'=>1,'message'=>'success','services'=>$services],$this->successStatus);   
    }

    public function addcustomer(Request $request) {

      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      $cdata = Customer::where('email',$request->email)->get();
      
      // if(count($cdata)>=1) {
      //   return response()->json(['status'=>0,'message'=>'This Email id already exist.'],$this->successStatus);   
      // }
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

      //for image upload
      
        $files=array();
        if(!empty($request->file('image'))) {

          foreach ($request->file('image') as $media) {
              if (!empty($media)) {
                  $datetime = date('YmdHis');
                  $image = $media->getClientOriginalName();
                  $imageName = $datetime . '_' . $image;
                  $media->move(public_path('uploads/ticketnote/'), $imageName);
                  array_push($files,$imageName);
              }
          }
        }
          $olddataarray = explode(',',$quote->imagelist);
          //dd($olddataarray);
          if(!empty($quote->imagelist)) {
            $oldnewarray = array();
            if(!empty($request->oldimage)) {
              $oldnewarray = $request->oldimage;
            }
            
           // dd($oldnewarray);
            $result=array_diff($olddataarray,$oldnewarray);

            if(count($result)>0) {
              foreach($result as $image)
              {   
                $path = 'uploads/ticketnote/';
                
                $stories_path=$path.$image;;
                @unlink($stories_path);
              }
            }

            foreach($oldnewarray as $name) {
               array_push($files,$name);
            }
          }
          
          $newimagestring = implode(',',$files);
          $quote->imagelist = $newimagestring;

      
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
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue','bodytext','subject')->where('id', $worker->userid)->first();

      $customerid = $request->customerid;

      $customer = Customer::where('id', $customerid)->get()->first();
      $customer->email = $request->email;
      $customer->save();

      $serviceid = $request->serviceid;
      $servicedetails = Service::select('servicename','productid','price')->whereIn('id', array($request->serviceid))->get();
      $sum = 0; 
      
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
        $txvalue = 0;
          if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
                $txvalue = $value['price']*$userdetails->servicevalue/100; 
            } else {
                $txvalue = 0;
            }
          }
          $sum+= $txvalue;
      }
       $servicename = implode(',', $sname);

    /*for logic product reduced*/
    $quote = Quote::where('id', $request->id)->get()->first();
    
    if($quote->product_id=="") {
        $productids = array();
    }

    $productids = explode(',',$quote->product_id);
    $pids = explode(',',$request->productid);
      $removedataid = array_diff($productids,$pids);
        if(!empty($removedataid)) {
          foreach($removedataid as $key => $value) {
            $productd = Inventory::where('id', $value)->first();
            if(!empty($productd)) {
              $productd->quantity = (@$productd->quantity) + 1;
              $productd->save();
            }
          }
        }
      if($request->productid!="") {
        $plusdataids= array_diff($pids,$productids); 
        if($plusdataids!="") {
          foreach($plusdataids as $key => $value) {
            $productd = Inventory::where('id', $value)->first();
            if(!empty($productd)) {
              $productd->quantity = (@$productd->quantity) - 1;
              $productd->save();
            }
          }
        }
      }


        $productid = "";
        $productname = "";
        $sum1 = 0;
        $txvalue1 = 0;
        $sum2 = 0;
       if($request->productid!="") {
        $productid = $request->productid;
         $pdetails = Inventory::select('productname','id','price')->whereIn('id', array($request->productid))->get();
      
          foreach ($pdetails as $key => $value) {
            $pname[] = $value['productname'];
            if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
                if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
                    $txvalue1 = $value['price']*$userdetails->productvalue/100; 
                } else {
                    $txvalue1 = 0;
                }
            }
            $sum1+= $txvalue1;
            $sum2+= $value['price'];
          }
          $productname = implode(',', $pname);    
       }

        $totaltax = $sum+$sum1;
        $totaltax = number_format($totaltax,2);
        $totaltax = preg_replace('/[^\d.]/', '', $totaltax);
       
      
      
      $quote = Quote::where('id', $request->id)->get()->first();

      $company = User::where('id', $quote->userid)->get()->first();
      if($company->image!=null) {
        $companyimage = url('').'/userimage/'.$company->image;
      } else {
        $companyimage = url('').'/uploads/servicebolt-noimage.png';
      }
      $cdefaultimage = url('').'/uploads/servicebolt-noimage.png';
      if($request->description) {
        $description =  $request->description;
      } else {
        $description = null;
      }
      $servicenames = $servicedetails[0]->servicename;

       DB::table('quote')->where('id','=',$request->id)->orWhere('parentid','=',$request->id)
          ->update([ 
              "description"=>"$description","serviceid"=>"$serviceid","servicename"=>"$servicenames","product_id"=>"$productid","price"=>"$request->price","tickettotal"=>"$request->ticketprice","tax"=>"$totaltax"
      ]);
   if($request->type=="save") {
        if(count($request->pricearray)>0) {
          DB::table('hourlyprice')->where('ticketid',$request->id)->delete();
          $pricetotal = 0;
          foreach($request->pricearray as $key =>$value) {
            $servicedetails = Service::select('id','servicename','price')->whereIn('id',array($value['id']))->first();
            $hrpicehour = 0;
            if($value['hrs']!='0' || $value['hrs']!='00') {
                $hrpicehour = $servicedetails->price*$value['hrs'];
            }
            $hrpiceminute = 0;
            if($value['mins']!='0' || $value['mins']!='00') {
              $perminuteprice =$servicedetails->price/60;
              $hrpiceminute = $perminuteprice*$value['mins'];
            }
            
            $data['ticketid'] = $request->id;
            $data['serviceid'] = $value['id'];
            $data['hour'] = $value['hrs'];
            $data['minute'] = $value['mins'];
            $data['price'] = number_format((float)$hrpicehour+$hrpiceminute, 2, '.', '');
            
            Hourlyprice::create($data);
            $pricetotal += number_format((float)$hrpicehour+$hrpiceminute, 2, '.', '');
          }
            $finalsumprice = $pricetotal+$sum2;
            DB::table('quote')->where('id','=',$request->id)->update([ 
                "price"=>$finalsumprice
            ]);
        }
        return response()->json(['status'=>1,'message'=>'Invoice has been save successfully'],$this->successStatus); 
   } else {
    //if($customer->email!=null) {  
          $app_name = 'ServiceBolt';
          $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
          $email = $customer->email;
          //$user_exist = Customer::where('email', $email)->first();
          $cemail = $request->email;
          $tdata1 = Quote::where('id', $request->id)->get()->first();
          $tdata1->invoiced = 1;
          $tdata1->save();

          if($userdetails->subject!=null) {
            $subject = $userdetails->subject;
          } else {
            $subject = 'Invoice details!';
          }
          $pdf = PDF::loadView('mail_templates.sendinvoice', ['invoiceId'=>$quote->invoiceid,'address'=>$quote->address,'ticketid'=>$quote->id,'customername'=>$customer->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$request->price,'time'=>$quote->giventime,'date'=>$quote->givenstartdate,'description'=>$quote->customernotes,'companyname'=>$customer->companyname,'phone'=>$customer->phonenumber,'email'=>$customer->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productid,'duedate'=>$quote->duedate,'quoteuserid'=>$quote->userid]);

          Mail::send('mail_templates.sendinvoice1', ['body'=>$userdetails->bodytext,'type'=>"sendinvoice"], function($message) use ($cemail,$app_name,$app_email,$pdf,$subject) {
              $message->to($cemail);
              $message->subject($subject);
              $message->attachData($pdf->output(), "invoice.pdf");

              //$message->from($app_email,$app_name);
            });
              if(count($request->pricearray)>0) {
                  DB::table('hourlyprice')->where('ticketid',$request->id)->delete();
                  $pricetotal = 0;
                  foreach($request->pricearray as $key =>$value) {
                    $servicedetails = Service::select('id','servicename','price')->whereIn('id',array($value['id']))->first();
                    $hrpicehour = 0;
                    if($value['hrs']!='0' || $value['hrs']!='00') {
                        $hrpicehour = $servicedetails->price*$value['hrs'];
                    }
                    $hrpiceminute = 0;
                    if($value['mins']!='0' || $value['mins']!='00') {
                      $perminuteprice =$servicedetails->price/60;
                      $hrpiceminute = $perminuteprice*$value['mins'];
                    }
                    
                    $data['ticketid'] = $request->id;
                    $data['serviceid'] = $value['id'];
                    $data['hour'] = $value['hrs'];
                    $data['minute'] = $value['mins'];
                    $data['price'] = number_format((float)$hrpicehour+$hrpiceminute, 2, '.', '');
                    
                    Hourlyprice::create($data);
                    $pricetotal += number_format((float)$hrpicehour+$hrpiceminute, 2, '.', '');
                  }
                    $finalsumprice = $pricetotal+$sum2;
                    DB::table('quote')->where('id','=',$request->id)->update([ 
                        "price"=>$finalsumprice
                    ]);
                }
          return response()->json(['status'=>1,'message'=>'Invoice has been send successfully'],$this->successStatus); 
        }
    }

    public function allproducData(Request $request) {

        $user = Auth::user();
        $auth_id = $user->id;

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $productData = Inventory::select('id','productname','price')->where('user_id',$worker->userid)->orderBy('id','DESC')->get();

        return response()->json(['status'=>1,'message'=>'Success','product'=>$productData],$this->successStatus);    
    }

    public function googleplacekey(Request $request) {
        $auth_id = Auth::user()->userid;
        $placekey = custom_userinfo($auth_id);
        $googleplacekey = $placekey;

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
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', $worker->userid)->first();

      $quote = Quote::where('id', $request->ticketid)->get()->first();
      $serviceid = $request->serviceid;

      $servicedetails = Service::select('servicename','productid','price')->whereIn('id', array($request->serviceid))->get();
      $sum = 0; 
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $txvalue = 0;
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
                $txvalue = $value['price']*$userdetails->servicevalue/100; 
            } else {
                $txvalue = 0;
            }
        }
        $sum+= $txvalue;
      } 

    $productid = "";
    $productname = "";
    $sum1 = 0;
    $txvalue1 = 0;

    if($request->productid!="") {
      $productid = $request->productid;

      $pdetails = Inventory::select('productname','id','price')->whereIn('id', array($request->productid))->get();
       
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
        if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
            $txvalue1 = $value['price']*$userdetails->productvalue/100; 
        } else {
            $txvalue1 = 0;
        }
        }
        $sum1+= $txvalue1;
      }

      $productname = implode(',', $pname); 
    }
      
    $servicename = implode(',', $sname);
    
    $totaltax = $sum+$sum1;
    $totaltax = number_format($totaltax,2);
    $totaltax = preg_replace('/[^\d.]/', '', $totaltax); 

      $data['customerid'] =  $quote->customerid;
      $data['userid'] =  $quote->userid;
      $data['workerid'] =  $quote->workerid;
      $data['serviceid'] = $serviceid;
      $data['servicename'] = $servicedetails[0]->servicename;
      $data['product_id'] = $productid;
      $data['product_name'] = $productname;
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
      $data['tax'] = $totaltax;
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

       $serviceData = Service::select('id','servicename','price')->whereIn('id',$sid)->orderBy('id','ASC')->get(); 

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
        if($request->type=="edit") {
          $timeoff = Workertimeoff::whereIn('id',$request->Ids)->where('workerid',auth()->user()->workerid)->delete();      
        }
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

        $workerinfo = DB::table('personnel')->select('personnelname')->where('id',$worker->workerid)->first();

        $personnelname = $workerinfo->personnelname;

        $ticketsub = "Leave requested by $personnelname";
        $data1['uid'] = $worker->userid;
        $data1['pid'] = $worker->workerid;
        $data1['message'] = $ticketsub;

        Notification::create($data1);
        event(new MyEvent($data1));

      }
      if($request->type=="edit") {
        return response()->json(['status'=>1,'message'=>'PTO Updated successfully'],$this->successStatus); 
      }
        return response()->json(['status'=>1,'message'=>'PTO added successfully'],$this->successStatus); 
    }

    public function ptolist(Request $request) {
      
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('workerid','userid')->where('id',$auth_id)->first();
        //$timeoff = DB::table('timeoff')->where('workerid',$worker->workerid)->orderBy('id','desc')->get();

        $timeoff = Workertimeoff::select(DB::raw('timeoff.*, GROUP_CONCAT(timeoff.id ORDER BY timeoff.id) AS ids'),DB::raw('GROUP_CONCAT(timeoff.date1 ORDER BY timeoff.date1) AS selectdates'),DB::raw('COUNT(timeoff.id) as counttotal'))->where('timeoff.workerid',$worker->workerid)->groupBy('timeoff.created_at')->orderBy('id','desc')->get();
//dd($timeoff);
        if(count($timeoff)>0) {
            foreach($timeoff as $key=>$value) {
                if($value->reason==null) {
                    $value->reason = "";
                }
                if($value->status==null) {
                    $value->status = "";
                }
                if($value->submitted_by==null) {
                    $value->submitted_by = "";
                }
                
                if($value->date1==null) {
                    $value->date1 = "";
                }
                if($value->selectdates!="") {
                  $sdates = explode(',',$value->selectdates);  
                }
                if($value->ids!="") {
                  $ids = explode(',',$value->ids);  
                }
                $data[] = array(
                    'created_at' => $value->created_at,
                'date' => $value->date,
                'date1' => $value->date1,
                'id' => $value->id,
                'selectedDates' => $sdates,
                'Ids' => $ids,
                'notes' => $value->notes,
                'reason' => $value->reason,
                'status' => $value->status,
                'submitted_by' => $value->submitted_by,
                'updated_at' => $value->updated_at,
                );
            }
            return response()->json(['status'=>1,'message'=>'Success','data'=>$data],$this->successStatus);    
        } else {
            $data = [];
            return response()->json(['status'=>1,'message'=>'Success','data'=>$data],$this->successStatus);
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
            //'companyname' => 'required',
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
            if(isset($errors['email'])){
                foreach($errors['email'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

      $cdata = Customer::where('email',$request->email)->get();
      
      // if(count($cdata)>1) {
      //   return response()->json(['status'=>0,'message'=>'This Email id already exist.'],$this->successStatus);   
      // }

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
                  //$message->from($app_email,$app_name);
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
        $auth_id = auth()->user()->userid;
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

        $cinfo = Customer::select('id','customername','phonenumber','email','companyname','billingaddress')->where('id',$quote->customerid)->first();
        if($quote->product_id=="") {
            $productids = array();
        }

    $productids = explode(',',$quote->product_id);
    $pids = explode(',',$request->productidnew);
      $removedataid = array_diff($productids,$pids);
        if(!empty($removedataid)) {
          foreach($removedataid as $key => $value) {
            $productd = Inventory::where('id', $value)->first();
            if(!empty($productd)) {
              $productd->quantity = (@$productd->quantity) + 1;
              $productd->save();
            }
          }
        }
      if($request->productidnew!="") {
        $plusdataids= array_diff($pids,$productids); 
        if($plusdataids!="") {
          foreach($plusdataids as $key => $value) {
            $productd = Inventory::where('id', $value)->first();
            if(!empty($productd)) {
              $productd->quantity = (@$productd->quantity) - 1;
              $productd->save();
            }
          }
        }
      }


      $quote1 = Quote::where('id', $request->ticketid)->orWhere('parentid',$request->ticketid)->get();

        foreach($quote1 as $key => $value) {
          if($value->primaryname == $value->personnelid) {
             $personnelid = $value->personnelid;
             $userid = $value->userid;
             $customername = $value->customername;

          }
        }
        if(count($quote1)>0) {
           if($request->payment_mode == "By Check") {
            if($auth_id == "68789779") {
            $data = array(
            'xKey' => 'serviceboltdev63cf6781c560436fa9f052cafa45a5d',
            'xVersion' => '4.5.9',
            "xSoftwareName" => 'ServiceBolt',
            'xSoftwareVersion' => '1.0.0',
            "xCommand"=>'check:sale',
            "xAmount"=>$request->payment_amount,
            "xAccount" =>$request->checknumber,
            "xAccountType" =>'Checking',
            "xCurrency" =>'USD',
            "xBillFirstName"=>$cinfo->customername,
            "xBillCompany"=>$cinfo->companyname,
            "xBillStreet"=>$cinfo->billingaddress,
            "xBillPhone"=>$cinfo->phonenumber,
            "xEmail"=>$cinfo->email
          );

            $headers = array(
              'Content-Type: application/json',
            );
            $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://x1.cardknox.com/gatewayjson');
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          $result = curl_exec($ch);
          curl_close($ch);
          $finalresult = json_decode($result);
          if($finalresult->xStatus == "Approved") {
            $id = DB::table('balancesheet')->insertGetId([
                  'userid' => $userid,
                  'workerid' => $personnelid,
                  'ticketid' => $request->ticketid,
                  'amount' => $request->payment_amount,
                  'customername' => $customername,
                  'paymentmethod' => $request->payment_mode,
                  'status' => "Completed"
                ]);

            DB::table('quote')->where('id','=',$request->ticketid)->orWhere('parentid','=',$request->ticketid)
              ->update([ 
                  "payment_status"=>"Completed","price"=>"$request->payment_amount","payment_amount"=>"$request->payment_amount","payment_mode"=>"$request->payment_mode","checknumber"=>"$request->checknumber","tickettotal"=>"$request->ticketprice","serviceid"=>"$request->serviceidnew","product_id"=>"$request->productidnew"
            ]);
              return response()->json(['status'=>1,'message'=>'Payment has been successfully'],$this->successStatus);
            } else {
                return response()->json(['status'=>0,'message'=>$finalresult->xError],$this->errorStatus);
            }
           } else {
             $id = DB::table('balancesheet')->insertGetId([
                  'userid' => $userid,
                  'workerid' => $personnelid,
                  'ticketid' => $request->ticketid,
                  'amount' => $request->payment_amount,
                  'customername' => $customername,
                  'paymentmethod' => $request->payment_mode,
                  'status' => "Completed"
                ]);

            DB::table('quote')->where('id','=',$request->ticketid)->orWhere('parentid','=',$request->ticketid)
              ->update([ 
                  "payment_status"=>"Completed","price"=>"$request->payment_amount","payment_amount"=>"$request->payment_amount","payment_mode"=>"$request->payment_mode","checknumber"=>"$request->checknumber","tickettotal"=>"$request->ticketprice","serviceid"=>"$request->serviceidnew","product_id"=>"$request->productidnew"
            ]);

            if(count($request->pricearray)>0) {
              DB::table('hourlyprice')->where('ticketid',$request->ticketid)->delete();
              $pricetotal = 0;
              foreach($request->pricearray as $key =>$value) {
                $servicedetails = Service::select('id','servicename','price')->whereIn('id',array($value['id']))->first();
                $hrpicehour = 0;
                if($value['hrs']!='0' || $value['hrs']!='00') {
                    $hrpicehour = $servicedetails->price*$value['hrs'];
                }
                $hrpiceminute = 0;
                if($value['mins']!='0' || $value['mins']!='00') {
                  $perminuteprice =$servicedetails->price/60;
                  $hrpiceminute = $perminuteprice*$value['mins'];
                }
                
                $data['ticketid'] = $request->ticketid;
                $data['serviceid'] = $value['id'];
                $data['hour'] = $value['hrs'];
                $data['minute'] = $value['mins'];
                $data['price'] = number_format((float)$hrpicehour+$hrpiceminute, 2, '.', '');
                
                Hourlyprice::create($data);
              }
            }
              return response()->json(['status'=>1,'message'=>'Payment has been successfully'],$this->successStatus);
           }
       }

        if($request->payment_mode == "Credit Card") {
           if($auth_id == "68789779") {
                $data = array(
                  'xCardNum' => $request->card_number,
                  'xExp' => $request->expiration_date,
                  'xKey' => 'serviceboltdev63cf6781c560436fa9f052cafa45a5d',
                  'xVersion' => '4.5.9',
                  "xSoftwareName" => 'ServiceBolt',
                  'xSoftwareVersion' => '1.0.0',
                  "xCommand"=>'cc:sale',
                  "xAmount"=>$request->payment_amount,
                  "xCVV" =>$request->cvv,
                  "xBillFirstName"=>$cinfo->customername,
                  "xBillCompany"=>$cinfo->companyname,
                  "xBillStreet"=>$cinfo->billingaddress,
                  "xBillPhone"=>$cinfo->phonenumber,
                  "xEmail"=>$cinfo->email
                );

            $headers = array(
              'Content-Type: application/json',
            );
            $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://x1.cardknox.com/gatewayjson');
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          $result = curl_exec($ch);
          curl_close($ch);
          $finalresult = json_decode($result);
          if($finalresult->xStatus == "Approved") {
            $id = DB::table('balancesheet')->insertGetId([
                  'userid' => $userid,
                  'workerid' => $personnelid,
                  'ticketid' => $request->ticketid,
                  'amount' => $request->payment_amount,
                  'customername' => $customername,
                  'paymentmethod' => $request->payment_mode,
                  'status' => "Completed"
                ]);

            DB::table('quote')->where('id','=',$request->ticketid)->orWhere('parentid','=',$request->ticketid)
              ->update([ 
                  "payment_status"=>"Completed","price"=>"$request->payment_amount","payment_amount"=>"$request->payment_amount","payment_mode"=>"$request->payment_mode","card_number"=>"$request->card_number","expiration_date"=>"$request->expiration_date","cvv"=>"$request->cvv","tickettotal"=>"$request->ticketprice","serviceid"=>"$request->serviceidnew","product_id"=>"$request->productidnew"
            ]);
              return response()->json(['status'=>1,'message'=>'Payment has been successfully'],$this->successStatus);
            } else {
                return response()->json(['status'=>0,'message'=>$finalresult->xError],$this->errorStatus);
            }
           } else {
              $id = DB::table('balancesheet')->insertGetId([
                  'userid' => $userid,
                  'workerid' => $personnelid,
                  'ticketid' => $request->ticketid,
                  'amount' => $request->payment_amount,
                  'customername' => $customername,
                  'paymentmethod' => $request->payment_mode,
                  'status' => "Completed"
                ]);

            DB::table('quote')->where('id','=',$request->ticketid)->orWhere('parentid','=',$request->ticketid)
              ->update([ 
                  "payment_status"=>"Completed","price"=>"$request->payment_amount","payment_amount"=>"$request->payment_amount","payment_mode"=>"$request->payment_mode","card_number"=>"$request->card_number","expiration_date"=>"$request->expiration_date","cvv"=>"$request->cvv","tickettotal"=>"$request->ticketprice","serviceid"=>"$request->serviceidnew","product_id"=>"$request->productidnew"
            ]);
            if(count($request->pricearray)>0) {
              DB::table('hourlyprice')->where('ticketid',$request->ticketid)->delete();
              $pricetotal = 0;
              foreach($request->pricearray as $key =>$value) {
                $servicedetails = Service::select('id','servicename','price')->whereIn('id',array($value['id']))->first();
                $hrpicehour = 0;
                if($value['hrs']!='0' || $value['hrs']!='00') {
                    $hrpicehour = $servicedetails->price*$value['hrs'];
                }
                $hrpiceminute = 0;
                if($value['mins']!='0' || $value['mins']!='00') {
                  $perminuteprice =$servicedetails->price/60;
                  $hrpiceminute = $perminuteprice*$value['mins'];
                }
                
                $data['ticketid'] = $request->ticketid;
                $data['serviceid'] = $value['id'];
                $data['hour'] = $value['hrs'];
                $data['minute'] = $value['mins'];
                $data['price'] = number_format((float)$hrpicehour+$hrpiceminute, 2, '.', '');
                
                Hourlyprice::create($data);
              }
            }
              return response()->json(['status'=>1,'message'=>'Payment has been successfully'],$this->successStatus);
           } 
        }

            if($request->payment_mode == "By Cash") {
                $id = DB::table('balancesheet')->insertGetId([
                  'userid' => $userid,
                  'workerid' => $personnelid,
                  'ticketid' => $request->ticketid,
                  'amount' => $request->payment_amount,
                  'customername' => $customername,
                  'paymentmethod' => $request->payment_mode,
                  'status' => "Completed"
                ]);

            DB::table('quote')->where('id','=',$request->ticketid)->orWhere('parentid','=',$request->ticketid)
              ->update([ 
                  "payment_status"=>"Completed","price"=>"$request->payment_amount","payment_amount"=>"$request->payment_amount","payment_mode"=>"$request->payment_mode","checknumber"=>"$request->checknumber","tickettotal"=>"$request->ticketprice","serviceid"=>"$request->serviceidnew","product_id"=>"$request->productidnew"
            ]);

                if(count($request->pricearray)>0) {
                  DB::table('hourlyprice')->where('ticketid',$request->ticketid)->delete();
                  $pricetotal = 0;
                  foreach($request->pricearray as $key =>$value) {
                    $servicedetails = Service::select('id','servicename','price')->whereIn('id',array($value['id']))->first();
                    $hrpicehour = 0;
                    if($value['hrs']!='0' || $value['hrs']!='00') {
                        $hrpicehour = $servicedetails->price*$value['hrs'];
                    }
                    $hrpiceminute = 0;
                    if($value['mins']!='0' || $value['mins']!='00') {
                      $perminuteprice =$servicedetails->price/60;
                      $hrpiceminute = $perminuteprice*$value['mins'];
                    }
                    
                    $data['ticketid'] = $request->ticketid;
                    $data['serviceid'] = $value['id'];
                    $data['hour'] = $value['hrs'];
                    $data['minute'] = $value['mins'];
                    $data['price'] = number_format((float)$hrpicehour+$hrpiceminute, 2, '.', '');
                    
                    Hourlyprice::create($data);
                  }
                }
              return response()->json(['status'=>1,'message'=>'Payment has been successfully'],$this->successStatus);
            }
           
        }

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
          $checknumber=$quoteData->checknumber;
        } else {
          $paymentpaid = "0";
          $payment_mode = "";
          $checknumber="";
        }

        return response()->json(['status'=>1,'paymentstatus'=>$paymentpaid,'payment_mode'=>$payment_mode,'checknumber'=>$checknumber],$this->successStatus);  
    }

    public function updatelivelocation(Request $request) {
        $user = Auth::user();
        $auth_id = $user->id;
        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();

        $workerid = $worker->workerid;
        if($request->latitude!=null && $request->longitude!=null) {
            DB::table('personnel')->where('id','=',$workerid)
              ->update([ 
                  "livelat"=>"$request->latitude",
                  "livelong"=>"$request->longitude",
            ]);
        } 

        return response()->json(['status'=>1],$this->successStatus);                      
    }

    public function adminchecklist(Request $request)
    {
        $user = Auth::user();
        $auth_id = $user->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $adminchecklist = DB::table('checklist')->select('checklist.serviceid as id','checklist.checklistname as checklist')->where('checklist.userid',$worker->userid)->groupBy('checklist.serviceid')->get();

        return response()->json(['status'=>1,'checklist'=>$adminchecklist],$this->successStatus);

    }

    public function updatenotes(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'addressid' => 'required'
        ]);
        if($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['addressid'])) {
                foreach($errors['addressid'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $address = Address::find($request->addressid);
        
        $address->notes = $request->notes;
        $checklistid =implode(',',$request->checklistid);
        $address->checklistid = $checklistid;
        $address->save();

        $addressdata = Address::select('checklistid','notes')->where('id',$request->addressid)->
        first();
        $ckids = explode(',',$addressdata->checklistid);
        $data = array(
            "notes"=>$addressdata->notes,
            "checklistid"=>$ckids
        );

        return response()->json(['status'=>1,'data'=>$data,'message'=>'Notes updated successfully'],$this->successStatus);
    }

    public function setnotes(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'addressid' => 'required'
        ]);
        if($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['addressid'])) {
                foreach($errors['addressid'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $addressdata = Address::select('checklistid','notes')->where('id',$request->addressid)->
        first();
        $ckids = explode(',',$addressdata->checklistid);
        $data = array(
            "notes"=>$addressdata->notes,
            "checklistid"=>$ckids
        );

        return response()->json(['status'=>1,'data'=>$data],$this->successStatus);
    }

    public function setnotesview(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'addressid' => 'required'
        ]);
        if($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['addressid'])) {
                foreach($errors['addressid'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $addressdata = Address::select('checklistid','notes')->where('id',$request->addressid)->
        first();
        if($addressdata->notes!="" || $addressdata->notes!=null) {
            $addressnote = $addressdata->notes;
        } else {
            $addressnote = "--";
        }
        $ckids = explode(',',$addressdata->checklistid);

        $data = array(
            "notes"=>$addressdata->notes,
            "checklistid"=>$ckids
        );

        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $ckinfo = array();
        $finalarray = array();
        $html = "";
        $html .="<p>Address Note : ".$addressnote."</p>";
        if($addressdata->checklistid!="") {
          $ckids = explode(',',$addressdata->checklistid);
          $ckinfo = DB::table('checklist')->select('serviceid','checklistname','checklist','userid')->whereIn('serviceid',$ckids)->where('userid',$worker->userid)->groupBy('serviceid')->get();
          
            if(!empty($ckinfo) && count($ckinfo)>0)
                $html .="<div>";
                {
                    $i=1;
                    foreach($ckinfo as $key=>$value) {
                        $html .="<p>"."($i)" . $value->checklistname."</p>";
                            
                                  $checklistdata  = Checklist::select('checklist')->where('serviceid',$value->serviceid)->where('userid',$value->userid)->get();
                            $html .="<div>";
                                foreach($checklistdata as $key => $value1) {
                                    $html .="<span style='margin-left:30px;'>".$value1->checklist."</span><br>";
                                }
                            $html .="</div>";
                            $i++;
                        }
                 $html .="</div>";
                }
           
        }

        return response()->json(['status'=>1,'data'=>$html],$this->successStatus);
    }

    public function addservice(Request $request) {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      
      $data['userid'] = $worker->userid;
      $data['workerid'] = $worker->workerid;
      $data['servicename'] = $request->servicename;
      $data['price'] = $request->price;
      if($request->productid) {
          $data['productid'] = $request->productid;
      }
      $data['type'] = $request->radiogroup;
      $data['frequency'] = $request->frequency;
      if($request->hour!=null || $request->hour!=0) {
        $data['time'] = $request->hour.' Hours';
      }
      if($request->minute!=null || $request->minute!=0) {
        $data['minute'] = $request->minute.' Minutes';;
      }

      $data['color'] = $request->color;
      $data['description'] = $request->description;

      $img = $request->image;
      if (!empty($img)) {    
        $image_parts = explode(";base64,", $img);
        $value = base64_decode($image_parts[1]);
        $imageName = time()  . '.png';

        $path = 'uploads/services/'.$imageName;
       
        file_put_contents($path, $value);
        $data['image'] = $imageName;
      } 

      Service::create($data);
      return response()->json(['status'=>1,'message'=>'Service Created Successfully'],$this->successStatus);    
    }

    public function addproduct(Request $request) {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      
      $data['user_id'] = $worker->userid;
      $data['workerid'] = $worker->workerid;
      $data['productname'] = $request->productname;
      if($request->serviceid) {
          $data['serviceid'] = $request->serviceid;
      }
      $data['quantity'] = $request->quantity;
      $data['pquantity'] = $request->pquantity;
      $data['sku'] = $request->sku;
      $data['unit'] = $request->unit;
      $data['price'] = $request->price;
      $data['description'] = $request->description;
      $data['category'] = $request->category;
     
      $img = $request->image;
      if (!empty($img)) {    
        $image_parts = explode(";base64,", $img);
        $value = base64_decode($image_parts[1]);
        $imageName = time()  . '.png';

        $path = 'uploads/inventory/'.$imageName;
       
        file_put_contents($path, $value);
        $data['image'] = $imageName;
      } 

      Inventory::create($data);
      return response()->json(['status'=>1,'message'=>'Product Created Successfully'],$this->successStatus);    
    }

    public function category(Request $request) 
    {
      $userid = auth()->user()->userid;
      $categoryList = DB::table('category')->select('id','category_name')->where('userid', $userid)->orderBy('id','DESC')->get();
      return response()->json(['status'=>1,'message'=>'Success','data'=>$categoryList],$this->successStatus);
    }

    public function deleteptolist(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'Ids' => 'required'
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
            $msg_err = '';
            if(isset($errors['Ids'])){
                foreach($errors['Ids'] as $e){
                    $msg_err .= $e;
                }
            }
            return response()->json(['status'=>0,'message'=>$msg_err],$this->successStatus);
        }

        $timeoff = Workertimeoff::whereIn('id',$request->Ids)->where('workerid',auth()->user()->workerid)->delete();
        return response()->json(['status'=>1,'message'=>'Deleted Successfully'],$this->successStatus);
    }

}
