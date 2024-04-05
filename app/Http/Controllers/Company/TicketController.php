<?php
namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Personnel;
use App\Models\Inventory;
use App\Models\Address;
use Mail;
use Illuminate\Support\Str;
use DB;
use App\Models\Managefield;
use App\Models\Tenture;
use App\Models\User;
use PDF;
use App\Models\AppNotification;

class TicketController extends Controller
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

        $quoteData = Quote::select('quote.*','customer.email')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status','0')->orderBy('quote.id','DESC')->get();
        
        $ticketData = Quote::select('quote.*','customer.email')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['1','2','4'])->where('quote.parentid', '=',"")->orderBy('quote.id','DESC')->get();
        $customer = Customer::where('userid',$auth_id)->orderBy('id','DESC')->get();
        $services = Service::where('userid', $auth_id)->get();
        $services1 = Service::where('userid', $auth_id)->get();
        $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
       	$worker = Personnel::where('userid', $auth_id)->get();
        $productData = Inventory::where('user_id',$auth_id)->get();
        $completedticketData = Quote::where('userid',$auth_id)->where('ticket_status','3')->where('quote.parentid', '=',"")->orderBy('id','DESC')->get();
        $table="quote";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);
        $fields1 = DB::getSchemaBuilder()->getColumnListing($table);
        $tenture = Tenture::where('status','Active')->get();
        //dd($services);
        return view('ticket.index',compact('auth_id','quoteData','ticketData','customer','services','services1','worker','productData','completedticketData','fields','fields1','tenture','userData'));
    }

    public function updateticket(Request $request) {
       $auth_id = auth()->user()->id;
       $quoteid = $request->quoteid;
       $quote = Quote::where('id', $quoteid)->get()->first();
       if($quote->personnelid!="") {
        $message = "A ticket #" .$quoteid. " has been reopened";
        $notification = new AppNotification;
        $notification->uid = $auth_id;
        $notification->pid = $quote->personnelid;
        $notification->ticketid = $quoteid;
        $notification->message =  $message;
        $notification->save();

        $puser = Personnel::select('device_token')->where("id", $quote->personnelid)->first();

        $msgarray = array (
            'title' => $message,
            'msg' => $message,
            'type' => 'ticketreopen',
        );

        $fcmData = array(
            'message' => $msgarray['msg'],
            'body' => $msgarray['title'],
        );

        $this->sendFirebaseNotification($puser, $msgarray, $fcmData);
    }
      if($request->name!="") {
        $quote->ticket_status = "4";
        $quote->save();
        echo "1";  
      } elseif($quote->personnelid!="") {
        $quote->ticket_status = "2";
        $currentdate = Carbon::now();
        $currentdate = date('Y-m-d', strtotime($currentdate));
        $quote->ticket_created_date = $currentdate;
        $quote->save();
        echo "1";

        if(!empty($quote->product_id)) {
          $pidarray = explode(',', $quote->product_id);
          foreach($pidarray as $key => $pid) {
            $productd = Inventory::where('id', $pid)->first();
            if($productd!=null) {
              $productd->quantity = @$productd->quantity - 1;
              $productd->save();  
            }
          }
        }

       } else {
          $quote->ticket_status = "1";
          $currentdate = Carbon::now();
          $currentdate = date('Y-m-d', strtotime($currentdate));
          $quote->ticket_created_date = $currentdate;
          $quote->save();
          echo "1";
          if(!empty($quote->product_id)) {
          $pidarray = explode(',', $quote->product_id);
          foreach($pidarray as $key => $pid) {
            $productd = Inventory::where('id', $pid)->first();
            if($productd!=null) {
              $productd->quantity = @$productd->quantity - 1;
              $productd->save();  
            }
          }
        } 
       }
        
    }

    public function updatetoquote(Request $request)
    {
        $quote = Quote::where('id', $request->quoteid)->first();
        $quote->ticket_status = 0;
        $quote->save();
        echo "1";
    }

    public function quotecreate(Request $request)
    {
        $serviceid = implode(',', $request->servicename);
        $productid = "";
        if(isset($request->productname)) {
          $productid = implode(',', $request->productname);
        }

        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

        $servicedetails = Service::select('servicename','productid','price')->whereIn('id', $request->servicename)->get();

        $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', auth()->user()->id)->first();

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

        //$productid = implode(',', array_unique($pid));
        
        $servicename = implode(',', $sname);
        $productname = "";

        $sum1 = 0;
        $txvalue1 = 0;
        $productname1 = "";
        if($request->productname!="") {
          $productdetails = Inventory::select('productname','price')->whereIn('id', $request->productname)->get();
         
          foreach ($productdetails as $key => $value) {
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
          $productname1 = $productdetails[0]->productname;
        }
        $totaltax = $sum+$sum1;
        $totaltax = number_format($totaltax,2);
        $totaltax = preg_replace('/[^\d.]/', '', $totaltax);
        
        $auth_id = auth()->user()->id;
	      $data['userid'] = $auth_id;
	      $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $serviceid;
        $data['product_id'] = $productid;
	      $data['servicename'] = $servicedetails[0]->servicename;
        $data['product_name'] = $productname1;
        
        $data['personnelid'] = $request->personnelid;
	      $data['radiogroup'] = $request->radiogroup;
	      $data['frequency'] = $request->frequency;
        //$fhours = 0;
	      if($request->time!=null || $request->time!=0) {
          $data['time'] = $request->time.' Hours';
          //$fhours = $request->time * 60;
        }
        //$shours = 0;
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';
          //$shours = $request->minute;
        }
        //$totalsecond =$fhours + $shours;

        // $persvalue = $request->price/60;
        // $totalvalue = $persvalue*$totalsecond;
        $data['price'] = $request->price;
        
	      $data['etc'] = Carbon::createFromFormat('m/d/Y', $request->etc)->format('Y-m-d');
	      $data['description'] = $request->description;
	      $data['customername'] =  $customer->customername;
          $addressinfo = explode("#id#",$request->address);
        $data['address_id'] = $addressinfo[0];
        $data['address'] = $addressinfo[1];
        $data['tickettotal'] = $request->ticketprice;
        $data['tax'] = $totaltax;
        $formattedAddr = str_replace(' ','+',$addressinfo[1]);
        //Send request and receive json data by address
        $auth_id = auth()->user()->id;
        $placekey = custom_userinfo($auth_id);
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        //print_r($output->results[0]->geometry->location->lat); die;
        $latitude  = @$output->results[0]->geometry->location->lat; 
        $longitude = @$output->results[0]->geometry->location->lng;

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;

          //for new feature
            if($request->time == null || $request->time == "" || $request->time == 00 || $request->time == 0) {
                  $hours = 0;
            } else {
                $hours = preg_replace("/[^0-9]/", '', $request->time);    
            }

            if($request->minute == null || $request->minute == "" || $request->minute == 00 || $request->minute == 0) {
                $minutes = 0;
            } else {
                $minutes = preg_replace("/[^0-9]/", '', $request->minute);    
            }

            if($request->personnelid!="") {
              //display the converted time
              $endtime = date('h:i a',strtotime("+{$hours} hour +{$minutes} minutes",strtotime($request->giventime)));
              $time = $request->giventime;
              
                $date = Carbon::createFromFormat('m/d/Y', $request->date)->format('l - F d, Y');
                $newdate = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');

              /*Get Dayclose time*/
                $closingtime = DB::table('users')->select('closingtime')->where('id',$auth_id)->first();
                $dayclosetime =$closingtime->closingtime;

                $tstarttime = explode(':',$time);
                $ticketstarttime = $tstarttime[0];
                $ticketdifferncetime = $dayclosetime - $ticketstarttime;
                // echo $ticketdifferncetime; die;
                $givenenddate = $newdate;
                if($hours != null || $hours != "" || $hours != 00 || $hours != 0) {
                    if($hours > $ticketdifferncetime) {
                        $nextdaytime = $hours - $ticketdifferncetime; 
                        //echo $nextdaytime; die;
                        $givenenddate = $this->getenddatecalculation($newdate,$nextdaytime);
                    } else {
                        $givenenddate = $newdate; 
                    }
                }
            $data['giventime'] = $time;
            $data['givenendtime'] = $endtime;
            $data['givendate'] = $date;
            $data['givenstartdate'] = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');
            $data['givenenddate'] = $givenenddate;
            $data['primaryname'] = $request->personnelid;
          }

	    $quotelastid = Quote::create($data);

        if($request->personnelid!="") {
            $appnotifiction = AppNotification::where('pid',$request->personnelid)->where('ticketid',$quotelastid->id)->get(); 

            $notification = new AppNotification;
            $notification->uid = $auth_id;
            $notification->pid = $request->personnelid;
            $notification->ticketid = $quotelastid->id;
            $notification->message =  "Quote #" .$quotelastid->id. " has been assigned";
            $notification->save();

            $puser = Personnel::select('device_token')->where("id", $request->personnelid)->first();

            $msgarray = array (
                'title' => "Quote #" .$quotelastid->id. " has been assigned",
                'msg' => "Quote #" .$quotelastid->id. " has been assigned",
                'type' => 'quoteassign',
            );

            $fcmData = array(
                'message' => $msgarray['msg'],
                'body' => $msgarray['title'],
            );

            $this->sendFirebaseNotification($puser, $msgarray, $fcmData); 
        }

        $quoteee = Quote::where('id', $quotelastid->id)->first();
        $randomid = 100;
        $quoteee->invoiceid = $randomid.''.$quotelastid->id;

        $quoteee->save();

    if($customer->email!=null) { 
      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sharequote', ['name'=>'service quote','address'=>$addressinfo[1], 'servicename'=>$servicename,'productname'=>$productname,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Service Quote from ' . auth()->user()->companyname);
          //$message->from($app_email,$app_name);
        });
    }
        if($request->share =='share') {
      	  $request->session()->flash('success', 'Quote share successfully');
         } else {
          $request->session()->flash('success', 'Quote added successfully');    
         }
         
          return redirect()->route('company.quote');
    }

    public function viewservicemodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
       $services = Service::where('id', $request->id)->get();
       if($services[0]->image != "") {
        $userimage = url('uploads/services/'.$services[0]->image);
        }
        if($services[0]->productid ==1) {
            $selected = "selected";
        } else {
            $selected = "";
        }
        if($services[0]->productid ==2) {
            $selected1 = "selected";
        } else {
            $selected1 = "";
        }

        if($services[0]->type =='perhour') {
            $checked = "checked";
        }
        if($services[0]->type =='recurring') {
            $checked3 = "checked";
        }
        if($services[0]->type =='flatrate') {
            $checked2 = "checked";
        }

        if($services[0]->frequency =='Weekly') {
            $selectedf = "selected";
        }
        if($services[0]->frequency =='Be weekly') {
            $selectedf1 = "selected";
        }
        if($services[0]->frequency =='Monthly') {
            $selectedf2 = "selected";
        }
        if($services[0]->time =='15 Minutes') {
            $selectedt1 = "selected";
        }
        if($services[0]->time =='30 Minutes') {
            $selectedt2 = "selected";
        }
        if($services[0]->time =='45 Minutes') {
            $selectedt3 = "selected";
        }
        if($services[0]->time =='1 Hours') {
            $selectedt4 = "selected";
        }
        $cheklist =explode (",", $services[0]->checklist); 
        if(in_array('point1', $cheklist)){
            $checked = "checked";
        }
        if(in_array('point2', $cheklist)) {
            $checked1 = "checked";
        }

       $html ='<div class="add-customer-modal">
                  <h5>Edit Service</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="serviceid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Service Name</label>
            <input type="text" class="form-control" placeholder="Service Name" name="servicename" id="servicename" value="'.$services[0]->servicename.'" required>
          </div>
          </div>
          <div class="col-md-12 mb-2">
            <label>Service Default Price</label>

            <input type="text" class="form-control" placeholder="Service Default Price" value="'.$services[0]->price.'" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
          </div>
          <div class="col-md-12 mb-2">
            <label>Default Product</label>
            <select class="form-select" name="defaultproduct" id="defaultproduct" required="">
              <option value="">Default Product</option>';

              foreach($productData as $key => $value) {
                  if($value->id == $services[0]->productid) {
                    $selectedp = "selected";
                  } else {
                    $selectedp = "";
                }
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->productname.'</option>';
              }
        $html .='</select>
          </div>
          <div class="col-md-12 mb-2">
            <div class="align-items-center justify-content-lg-between d-flex services-list">
               <p>
                <input type="radio" id="test4" name="radiogroup" value="perhour" '.@$checked.'>
                <label for="test4">Per Hour</label>
              </p>
              <p>
                <input type="radio" id="test5" name="radiogroup" value="flatrate" '.@$checked2.'>
                <label for="test5">Flate Rate</label>
              </p>
              <p>
                <input type="radio" id="test6" name="radiogroup" value="recurring" '.@$checked3.'>
                <label for="test6">Recurring</label>
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-2">
            <label>Service Frequency</label>
            <select class="form-select" name="frequency" id="frequency" required="">
              <option value="">Service Frequency</option>
              <option name="Weekly" value="Weekly" '.@$selectedf.'>Weekly</option>
              <option name="Be weekly" value="Be weekly" '.@$selectedf1.'>Bi-weekly  </option>
              <option name="Monthly" value="Monthly" '.@$selectedf2.'>Monthly  </option>
            </select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Time</label>
            <select class="form-select" name="time" required="">
              <option value="">Default Time</option>
              <option value="15 Minutes" '.@$selectedt1.'>15 Minutes</option>
              <option value="30 Minutes" '.@$selectedt2.'>30 Minutes</option>
              <option value="45 Minutes" '.@$selectedt3.'>45 Minutes</option>
              <option value="1 Hours" '.@$selectedt4.'>1 Hours</option>
            </select>
          </div>
          
          <div class="col-lg-12 mb-2">
              
            <img src="'.$userimage.'" />
           
          </div>

          
           

          <div class="col-md-12 mb-2">
            <p class="create-gray mb-2">Create default checklist</p>
            <div class="align-items-center  d-flex services-list">
              <label class="container-checkbox me-3">Point 1
                <input type="checkbox" name="pointckbox[]" id="pointckbox" value="point1" '.@$checked.'> <span class="checkmark"></span>
              </label>
              <label class="container-checkbox me-3">Point 2
                <input type="checkbox" name="pointckbox[]" id="pointckbox" value="point2" '.@$checked1.'> <span class="checkmark"></span>
              </label>
                            
            </div>
          </div>
          <div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block">Update</button>
          </div>
        </div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function update(Request $request)
    {
            $service = Service::where('id', $request->serviceid)->get()->first();
            $service->servicename = $request->servicename;
            $service->price = $request->price;
            $service->productid = $request->defaultproduct;
            $service->type = $request->radiogroup;
            $service->frequency = $request->frequency;
            //$service->time = $request->time;
            if($request->time!=null || $request->time!=0) {
              $service->time = $request->time.' Hours';
            } else {
              $service->time = null;
            }
            if($request->minute!=null || $request->minute!=0) {
              $service->minute = $request->minute.' Minutes';
            } else {
              $service->minute = null;
            }
            $service->checklist = implode(',', $request->pointckbox);
            
            $service->save();
            $request->session()->flash('success', 'Service Updated successfully');
            return redirect()->route('company.services');
    }

    public function leftbarservicedata(Request $request)
    {
      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      
      $json = array();
      if($targetid == 0) {
        $services = Service::get();
        $countdata = count($services);
         $datacount = $countdata-1;
        $imagepath = url('/').'/uploads/services/'.$services[$datacount]->image;
      $html ='<div class="product-card targetDiv" id="div1">
              <img src="'.$imagepath.'" alt="" />
              <h2>'.$services[$datacount]->servicename.'</h2>
              <div class="product-info-list">
                <div class="mb-4">
                  <p class="number-1">Price</p>
                  <h6 class="heading-h6">'.$services[$datacount]->price.'</h6>
                </div>
                <div class="mb-5">
                  <p class="number-1">Default Time</p>
                  <h6 class="heading-h6">'.$services[$datacount]->time.'</h6>
                </div>  <a class="btn add-btn-yellow w-100 p-3 mb-3" data-bs-toggle="modal" data-bs-target="#create-tickets" id="createtickets" data-id="'.$services[$datacount]->id.'">Create a Quote</a>
                <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-services" id="editService" data-id="'.$services[$datacount]->id.'">Edit</a>
              </div>
            </div>';
      } else {
        $services = Service::where('id', $request->serviceid)->get();
        $imagepath = url('/').'/uploads/services/'.$services[0]->image;
      $html ='<div class="product-card targetDiv" id="div1">
              <img src="'.$imagepath.'" alt="" />
              <h2>'.$services[0]->servicename.'</h2>
              <div class="product-info-list">
                <div class="mb-4">
                  <p class="number-1">Price</p>
                  <h6 class="heading-h6">'.$services[0]->price.'</h6>
                </div>
                <div class="mb-5">
                  <p class="number-1">Default Time</p>
                  <h6 class="heading-h6">'.$services[0]->time.'</h6>
                </div>  <a class="btn add-btn-yellow w-100 p-3 mb-3" data-bs-toggle="modal" data-bs-target="#create-tickets" id="createtickets" data-id="'.$services[0]->id.'">Create a Quote</a>
                <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-services" id="editService" data-id="'.$services[0]->id.'">Edit</a>
              </div>
            </div>';
      }
      
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function viewquotemodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
       $services = Service::where('id', $request->id)->get();
       $customer = Customer::where('userid', $auth_id)->get();
       $worker = Personnel::where('userid', $auth_id)->get();
       
      $html ='<div class="add-customer-modal">
                <h5>Create New Quote</h5>
               </div><div class="row customer-form"><div class="col-md-12 mb-2">';
        $html .='<input type="hidden" name="serviceid" id="serviceid" value="'.$request->id.'"><div class="input_fields_wrap">
          <div class="mb-3">
            <select class="form-select" name="customerid" id="customerid" required>
              <option selected="" value="">Select a customer </option>';
              foreach($customer as $key => $value) {
                $html .='<option value="'.$value->id.'">'.$value->customername.'</option>';
              }
        $html .='</select>
          </div>
        </div>
      </div><div class="col-md-6 mb-3">
        <input type="text" class="form-control" readonly="" name="servicename" placeholder="service One" value="'.$services[0]->servicename.'">
      </div><div class="col-md-6 mb-3">
        <select class="form-select" name="personnelid" id="personnelid" required>
        <option selected="" value="">Select a Personnel </option>';
              foreach($worker as $key => $value) {
                $html .='<option value="'.$value->id.'">'.$value->personnelname.'</option>';
              }
        $html .='</select>
      </div><div class="col-md-12 mb-3">
        <div class="align-items-center justify-content-lg-between d-flex services-list">
          <label class="container-checkbox">Per hour
            <input type="radio" id="test1" name="radiogroup" value="perhour" checked>
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox">Flate rate
            <input type="radio" id="test2" name="radiogroup" value="flatrate">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox">Recurring
            <input type="radio" id="test3" name="radiogroup" value="recurring">
            <span class="checkmark"></span>
          </label>
        </div>
      </div><div class="col-md-6 mb-2">
        <select class="form-select" name="frequency" id="frequency" required="">
          <option selected="" value="">Service Frequency</option>
          <option name="Weekly" value="Weekly">Weekly</option>
          <option name="Be weekly" value="Be weekly">Bi-weekly</option>
          <option name="Monthly" value="Monthly">Monthly</option>
        </select>
      </div><div class="col-md-6 mb-2">
      <select class="form-select" name="time" required="">
        <option selected="" value="">Default Time</option>
        <option value="15 Minutes">15 Minutes</option>
        <option value="30 Minutes">30 Minutes</option>
        <option value="45 Minutes">45 Minutes</option>
        <option value="1 Hours">1 Hours</option>
      </select>
     </div><div class="col-md-12 mb-3">
    <input type="text" class="form-control" placeholder="Price" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
     </div><div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="ETC" name="etc" id="etc" required>
     </div><div class="col-md-12 mb-3">
      <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required></textarea>
     </div> <div class="col-lg-6 mb-3">
      <button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
    </div><div class="col-lg-6 mb-3">
      <button class="btn btn-add btn-block" type="submit">Add a Quote</button>
    </div><div class="col-lg-12">
     <button class="btn btn-dark btn-block btn-lg p-2" type="submit"><img src="images/share-2.png"  alt=""/> Share</button>
    </div></div>';
      return json_encode(['html' =>$html]);
        die;
   }

   public function createquote(Request $request)
   {
      $auth_id = auth()->user()->id;
      
      $data['userid'] = $auth_id;
      $data['customerid'] = $request->customerid;
      $data['serviceid'] = $request->serviceid;
      $data['servicename'] = $request->servicename;
      $data['personnelid'] = $request->personnelid;
      $data['radiogroup'] = $request->radiogroup;
      $data['frequency'] = $request->frequency;
      if($request->time!=null || $request->time!=0) {
          $data['time'] = $request->time.' Hours';
        }
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';;
        }
      $data['price'] = $request->price;
      $data['etc'] = $request->etc;
      $data['description'] = $request->description;
      
      Quote::create($data);
      
      $request->session()->flash('success', 'Quote added successfully');
      if($request->serviceticket==1){
        return redirect()->route('company.services');
      }
      return redirect()->route('company.services');
    }

    public function getaddressbyid(Request $request)
    {
        $data['address'] = Address::where("customerid",$request->customerid)
                    ->get(["address","id"]);
        return response()->json($data);
    }

    public function ticketcreate(Request $request)
    {

        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

        $serviceid = implode(',', $request->servicename);

        $servicedetails = Service::select('servicename','productid','price')->whereIn('id', $request->servicename)->get();

        $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', auth()->user()->id)->first();

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

        $productid = "";
        $productname = "";
        $productname1 = "";

          if(isset($request->productname)) {
            $productid = implode(',', $request->productname);
          }
          $sum1 = 0;
          if($request->productname!="") {

              $productdetails = Inventory::select('id','productname','price')->whereIn('id', $request->productname)->get();
              
                     
              foreach ($productdetails as $key => $value) {
                $pname[] = $value['productname'];
                $txvalue1 = 0;

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
              $productname1 = $productdetails[0]->productname;
          }
        $totaltax = $sum+$sum1;
        $totaltax = number_format($totaltax,2);
        $totaltax = preg_replace('/[^\d.]/', '', $totaltax);
        $auth_id = auth()->user()->id;
        $data['userid'] = $auth_id;
        $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $serviceid;
        $data['servicename'] = $servicedetails[0]->servicename;
        $data['product_name'] = $productname1;
        $data['product_id'] = $productid;
        //$data['product_name'] = $pname;
        
        $data['personnelid'] = $request->personnelid;
        $data['radiogroup'] = $request->radiogroup;
        $data['frequency'] = $request->frequency;
        //$fhours = 0;
        if($request->time!=null || $request->time!=0) {
          $data['time'] = $request->time.' Hours';
          //$fhours = $request->time * 60;
        }
        ///$shours = 0;
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';
          //$shours = $request->minute;
        }
        // $totalsecond =$fhours + $shours;
        // $persvalue = $request->price/60;
        // $totalvalue = $persvalue*$totalsecond;
        $data['price'] = $request->price;
        $data['etc'] = Carbon::createFromFormat('m/d/Y', $request->etc)->format('Y-m-d');
        $data['description'] = $request->description;
        $data['customername'] =  $customer->customername;

        $addressinfo = explode("#id#",$request->address);
        $data['address_id'] = $addressinfo[0];
        $data['address'] = $addressinfo[1];

        $data['tickettotal'] = $request->ticketprice1;
        $data['tax'] = $totaltax;

        $formattedAddr = str_replace(' ','+',$addressinfo[1]);
        //Send request and receive json data by address
        $auth_id = auth()->user()->id;
        $placekey = custom_userinfo($auth_id);
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        if($output->results!=NULL) {
        $latitude  = @$output->results[0]->geometry->location->lat; 
        $longitude = @$output->results[0]->geometry->location->lng;
        }
        else {
          $latitude  = 0; 
          $longitude = 0;
        }

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        
        //for new feature
          if($request->time == null || $request->time == "" || $request->time == 00 || $request->time == 0) {
                $hours = 0;
          } else {
              $hours = preg_replace("/[^0-9]/", '', $request->time);    
          }

          if($request->minute == null || $request->minute == "" || $request->minute == 00 || $request->minute == 0) {
              $minutes = 0;
          } else {
              $minutes = preg_replace("/[^0-9]/", '', $request->minute);    
          }
          
      if($request->personnelid!="") {
          //display the converted time
          $endtime = date('h:i a',strtotime("+{$hours} hour +{$minutes} minutes",strtotime($request->giventime)));
          $time = $request->giventime;
          
            $date = Carbon::createFromFormat('m/d/Y', $request->date)->format('l - F d, Y');
            $newdate = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');

          /*Get Dayclose time*/
            $closingtime = DB::table('users')->select('closingtime')->where('id',$auth_id)->first();
            $dayclosetime =$closingtime->closingtime;

            $tstarttime = explode(':',$time);
            $ticketstarttime = $tstarttime[0];
            $ticketdifferncetime = $dayclosetime - $ticketstarttime;
            // echo $ticketdifferncetime; die;
            $givenenddate = $newdate;
            if($hours != null || $hours != "" || $hours != 00 || $hours != 0) {
                if($hours > $ticketdifferncetime) {
                    $nextdaytime = $hours - $ticketdifferncetime; 
                    //echo $nextdaytime; die;
                    $givenenddate = $this->getenddatecalculation($newdate,$nextdaytime);
                } else {
                    $givenenddate = $newdate; 
                }
            }
        $data['giventime'] = $time;
        $data['givenendtime'] = $endtime;
        $data['givendate'] = $date;
        $data['givenstartdate'] = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');
        $data['givenenddate'] = $givenenddate;
        $data['ticket_status'] = 2;
        $data['primaryname'] = $request->personnelid;
      } else {
        $data['ticket_status'] = 1;
      }
        //end new feature here

      $quotelastid = Quote::create($data);

       if($request->personnelid!="") {
            $appnotifiction = AppNotification::where('pid',$request->personnelid)->where('ticketid',$quotelastid->id)->get(); 

            $notification = new AppNotification;
            $notification->uid = $auth_id;
            $notification->pid = $request->personnelid;
            $notification->ticketid = $quotelastid->id;
            $notification->message =  "Ticket #" .$quotelastid->id. " has been assigned";
            $notification->save();

            $puser = Personnel::select('device_token')->where("id", $request->personnelid)->first();

            $msgarray = array (
                'title' => "Ticket #" .$quotelastid->id. " has been assigned",
                'msg' => "Ticket #" .$quotelastid->id. " has been assigned",
                'type' => 'ticketassign',
            );

            $fcmData = array(
                'message' => $msgarray['msg'],
                'body' => $msgarray['title'],
            );

            $this->sendFirebaseNotification($puser, $msgarray, $fcmData); 
        }
      $quoteee = Quote::where('id', $quotelastid->id)->first();
      $randomid = 100;
      $quoteee->invoiceid = $randomid.''.$quotelastid->id;
      $quoteee->save();
    if($customer->email!=null) {
      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sharequote', ['name'=>'service ticket','address'=>$addressinfo[1], 'servicename'=>$servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'productname'=>$productname,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Service Ticket from '. auth()->user()->companyname);
          //$message->from($app_email,$app_name);
        });
    }
        
        if($request->share =='share') {
          $request->session()->flash('success', 'Ticket share successfully');
        } else {
          $request->session()->flash('success', 'Ticket added successfully');  
        }
        return redirect()->route('company.quote');
    }

    public function getenddatecalculation($newdate,$nextdaytime) 
    {
        $auth_id = auth()->user()->id;
        $closingtime = DB::table('users')->select('closingtime','openingtime')->where('id',$auth_id)->first();

        $fulldaytime = $closingtime->closingtime - $closingtime->openingtime;
        
        if($nextdaytime > $fulldaytime) {
           $divisionvalue = $nextdaytime / $fulldaytime;

           $dividev = explode('.',$divisionvalue);
           $daycount = $dividev[0];
           $dayhours = $dividev[1];

            if($dayhours!="") {
                $daycount = $daycount +1;
            } else {
                $daycount = $dividev[0];
            }
            $ddd = $daycount. 'day';

            //day added as per calcuation wise
            $givenenddate = date('Y-m-d', strtotime($newdate . ' +'.$ddd));
            
        }
        else {
            //day added as per calcuation wise
            $givenenddate = date('Y-m-d', strtotime($newdate . ' +1 day'));
        }
        return $givenenddate;
    }

    public function vieweditticketmodal(Request $request)
    {
      //echo $request->id; die;
       $json = array();
       $auth_id = auth()->user()->id;
       $allservices = Service::where('userid', $auth_id)->get();
       $allproducts = Inventory::where('user_id', $auth_id)->get();
       $allworker = Personnel::where('userid', $auth_id)->get();

       $quotedetailsnew = Quote::where('id', $request->id)->get()->toArray();
       $quotedetails = Quote::where('id', $request->id)->get();
       
       $allcustomer = Customer::where('userid', $auth_id)->get();
        
       $tenture = Tenture::where('status','Active')->get();
        if($quotedetails[0]->radiogroup =='perhour') {
            $checked = "checked";
        }
        if($quotedetails[0]->radiogroup =='recurring') {
            $checked3 = "checked";
        }
        if($quotedetails[0]->radiogroup =='flatrate') {
            $checked2 = "checked";
        }

        
        if($quotedetails[0]->time =='15 Minutes') {
            $selectedt1 = "selected";
        }
        if($quotedetails[0]->time =='30 Minutes') {
            $selectedt2 = "selected";
        }
        if($quotedetails[0]->time =='45 Minutes') {
            $selectedt3 = "selected";
        }
        if($quotedetails[0]->time =='1 Hours') {
            $selectedt4 = "selected";
        }

      $time =  explode(" ", $quotedetails[0]->time);
      $minute =  explode(" ", $quotedetails[0]->minute);

      $address = Address::select('id','address')->where("customerid",$quotedetails[0]->customerid)->get();
      $title = "Edit"; 
      if(isset($request->view)){
        $title = 'Edit Invoice';
      }
       $html ='<div class="add-customer-modal d-flex justify-content-between align-items-center">
       <h5>'.$title.'</h5>
       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>';
       $html .='<input type="hidden" value="'.$quotedetails[0]->tickettotal.'" name="tickettotaledit" id="tickettotaledit"><div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="quoteid">
          <div class="col-md-12 mb-2">
            <label>Select Customer</label>
            <select class="form-select" name="customerid" id="customerid2" required="">
              <option value="">Select a Customer</option>';

              foreach($allcustomer as $key => $value) {
                  if($value->id == $quotedetails[0]->customerid) {
                    $selectecp = "selected";
                  } else {
                    $selectecp = "";
                }
                $html .='<option value="'.$value->id.'" '.@$selectecp.'>'.$value->customername.'</option>';
              }
        $html .='</select>
          </div>
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label>Select Customer Address</label>
                <select class="form-select" name="address" id="address3" required>';
                foreach($address as $key => $value) {
                  if($value->address == $quotedetails[0]->address) {
                    $selectecpa = "selected";
                  } else {
                    $selectecpa = "";
                }
                 $html .='<option value="'.$value->id.'#id#'.$value->address.'" '.@$selectecpa.'>'.$value->address.'</option>';
              }
        $html .='</select>
              </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <label>Select a Service</label>
            <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" style="height:auto;" required>';
              foreach($allservices as $key => $value) {
                $serviceids =explode(",", $quotedetails[0]->serviceid);
                 if(in_array($value->id, $serviceids)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }

                $html .='<option value="'.$value->id.'" '.@$selectedp.' data-hour="'.$value->time.'" data-min="'.$value->minute.'" data-price="'.$value->price.'">'.$value->servicename.'</option>';
              }
        $html .='</select>
          </div>

          <div class="col-md-12 mb-2">
            <label>Select Products</label>
            <select class="form-control selectpickerp1" multiple aria-label="Default select example" data-live-search="true" name="productid[]" id="productid" style="height:auto;" data-placeholder="Select Products">';
              foreach($allproducts as $key => $value) {
                $productids =explode(",", $quotedetails[0]->product_id);
                 if(in_array($value->id, $productids)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }

                $html .='<option value="'.$value->id.'" '.@$selectedp.' data-price="'.$value->price.'">'.$value->productname.'</option>';
              }
        $html .='</select>
          </div>';

          

          $html.='<div class="col-md-12 mb-2">
            <div class="align-items-center justify-content-lg-between d-flex services-list">
               <p>
                <input type="radio" id="test4" name="radiogroup" value="perhour" '.@$checked.' class="radiogroupedit">
                <label for="test4">Per Hour</label>
              </p>
              <p>
                <input type="radio" id="test5" name="radiogroup" value="flatrate" '.@$checked2.' class="radiogroupedit">
                <label for="test5">Flate Rate</label>
              </p>
              <p>
                <input type="radio" id="test6" name="radiogroup" value="recurring" '.@$checked3.' class="radiogroupedit">
                <label for="test6">Recurring</label>
              </p>
            </div>
          </div>
         
          <div class="col-md-6 mb-2">
            <label>Service Frequency</label>
            <select class="form-select" name="frequency" id="frequency" required="">
              <option value="">Service Frequency</option>';
          foreach ($tenture as $key => $value) {
            if($value->tenturename== $quotedetailsnew[0]['frequency']) {
                  $selectedsf = "selected";
                } else {
                  $selectedsf = "";
                }
                $html .='<option name="'.$value->tenturename.'" value="'.$value->tenturename.'" '.@$selectedsf.'>'.$value->tenturename.'</option>';
            }
            $html .='</select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Service Time (hh:mm)</label><br>
            <div class="timepicker timepicker1 form-control" style="display: flex;align-items: center;">
           <input type="text" class="hh N popfieldsedit" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="timeedit" value="'.$time[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N popfieldsedit" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minuteedit" value="'.$minute[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div></div>
          <div class="col-md-12 mb-3 position-relative">
            <label>Price</label>
<i class="fa fa-dollar" style="position: absolute;top: 41px;left: 27px;"></i>
            <input type="text" class="form-control" placeholder="Price" name="price" id="priceticketedit" value="'.$quotedetails[0]->price.'" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
            <input type="hidden" name="edithiddenprice" id="edithiddenprice">
           </div>
           <div class="col-md-12 mb-3">
            <label style="position: relative;left: 0px;margin-bottom: 11px;">ETC</label>
           <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" value="'.$quotedetails[0]->etc.'" required>
           </div>';
            if(isset($request->view)) {
               $html .='<div class="col-md-12 mb-3 position-relative">
                <label>Invoice Note</label>
                <input type="text" class="form-control" placeholder="Invoice Note" name="invoicenote" id="invoicenote" value="'.$quotedetails[0]->invoicenote.'">
               </div>';
            }
           $html .='<div class="col-md-12 mb-3">
             <label>Description</label>
             <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required>'.$quotedetails[0]->description.'</textarea>
           </div>';
           if($request->type == "ticket") {
              $updatev="ticket";
           } else {
              $updatev="quote";
           }
          $html .= '<div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block" name="type" value="'.$updatev.'">Update</button>
          </div>
        </div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function ticketupdate(Request $request)
    {
      $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

      $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', auth()->user()->id)->first();

      $servicedetails = Service::select('servicename','productid','price')->whereIn('id', $request->serviceid)->get();
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
      $productname = "";

      $sum1 = 0;
      $txvalue1 = 0;
      $quote = Quote::where('id', $request->quoteid)->get()->first();

    if($request->type=="ticket") 
    {
      if($request->productid=="") {
        $request->productid = array();
      }
      if($quote->product_id=="") {
        $productids = array();
      }

      $productids = explode(',',$quote->product_id);

      $removedataid = array_diff($productids,$request->productid);
        if($removedataid!="") {
          foreach($removedataid as $key => $value) {
            $productd = Inventory::where('id', $value)->first();
            if(!empty($productd)) {
              $productd->quantity = (@$productd->quantity) + 1;
              $productd->save();
            }
          }
        }
      if($request->productid!=null) {
        $reqpids = $request->productid;
        $plusdataids= array_diff($reqpids,$productids); 
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
    }
      
      if($request->productid!="") {
        $productdetails = Inventory::select('productname','price')->whereIn('id', $request->productid)->get();

        foreach ($productdetails as $key => $value) {
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
        $productname = @$productdetails[0]->productname; 
      }
      $totaltax = $sum+$sum1;
      $totaltax = number_format($totaltax,2);
      $totaltax = preg_replace('/[^\d.]/', '', $totaltax);

      
      $quote->customerid =  $request->customerid;

      //$quote->serviceid = $request->serviceid;
      if(isset($request->serviceid)) {
        $quote->serviceid = implode(',', $request->serviceid);
      } else {
        $quote->serviceid = null;
      }

      if(isset($request->productid)) {
        $quote->product_id = implode(',', $request->productid);
      } else {
        $quote->product_id = null;
      }

      $quote->servicename = $servicedetails[0]->servicename;
      $quote->product_name = $productname;
      if(isset($request->personnelid)) {
        $quote->personnelid = $request->personnelid;
      }
      $quote->radiogroup = $request->radiogroup;
      $quote->frequency = $request->frequency;
      if($request->time!=null || $request->time!=0) {
        $quote->time = $request->time.' Hours';
      } else {
        $quote->time = null;
      }
      if($request->minute!=null || $request->minute!=0) {
        $quote->minute = $request->minute.' Minutes';;
      } else {
        $quote->minute = null;
      }
      $quote->price = $request->price;
      $quote->etc = $request->etc;
      $quote->description = $request->description;
      $quote->customername =  $customer->customername;

      $addressinfo = explode("#id#",$request->address);
      $quote->address_id = $addressinfo[0];
      $quote->address = $addressinfo[1];
      $quote->tickettotal = $request->tickettotaledit;
      $quote->tax = $totaltax;
      $formattedAddr = str_replace(' ','+',$addressinfo[1]);
        //Send request and receive json data by address
        $auth_id = auth()->user()->id;
        $placekey = custom_userinfo($auth_id);
      $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
      $output = json_decode($geocodeFromAddr);

      if($output->results!=NULL) {
        $latitude  = @$output->results[0]->geometry->location->lat; 
        $longitude = @$output->results[0]->geometry->location->lng;
        }
        else {
          $latitude  = 0; 
          $longitude = 0;
        }
      $quote->latitude = $latitude;
      $quote->longitude = $longitude;
      if(isset($request->invoicenote)) {
        $quote->invoicenote = $request->invoicenote;
      }
      $quote->save();
      $request->session()->flash('success', 'Updated successfully');
      return redirect()->back();
    }

    public function deleteQuote(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;
      DB::table('quote')->delete($id);
      echo "1";
    }

    public function viewcompleteticketmodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $allservices = Service::where('userid', $auth_id)->get();
       $allworker = Personnel::where('userid', $auth_id)->get();

       $quotedetails = Quote::where('id', $request->id)->get();

       $serviceid = explode(',', $quotedetails[0]->serviceid);

       $servicedetails = Service::select('servicename')->whereIn('id', $serviceid)->get();

      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 
      $servicename = implode(',', $sname);
      $productname = "";
      if($quotedetails[0]->product_id!="") {
        $product_id = explode(',', $quotedetails[0]->product_id);

         $productdetails = Inventory::select('productname')->whereIn('id', $product_id)->get();

        foreach ($productdetails as $key => $value) {
          $pname[] = $value['productname'];
        } 
        $productname = implode(',', $pname);
      }
       

       $allcustomer = Customer::where('userid', $auth_id)->get();
      
        if($quotedetails[0]->radiogroup =='perhour') {
            $checked = "checked";
        }
        if($quotedetails[0]->radiogroup =='recurring') {
            $checked3 = "checked";
        }
        if($quotedetails[0]->radiogroup =='flatrate') {
            $checked2 = "checked";
        }

        if($quotedetails[0]->frequency =='Weekly') {
            $selectedf = "selected";
        }
        if($quotedetails[0]->frequency =='Be weekly') {
            $selectedf1 = "selected";
        }
        if($quotedetails[0]->frequency =='Monthly') {
            $selectedf2 = "selected";
        }
        if($quotedetails[0]->frequency =='One Time') {
            $selectedf3 = "selected";
        }
        if($quotedetails[0]->time =='15 Minutes') {
            $selectedt1 = "selected";
        }
        if($quotedetails[0]->time =='30 Minutes') {
            $selectedt2 = "selected";
        }
        if($quotedetails[0]->time =='45 Minutes') {
            $selectedt3 = "selected";
        }
        if($quotedetails[0]->time =='1 Hours') {
            $selectedt4 = "selected";
        }
       
       $html ='<div class="add-customer-modal">
                  <h6>Ticket #'.$request->id.'</h6>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="quoteid">
          <div class="col-md-12 mb-2">
            <label>Customer Name: &nbsp;</label>'.$quotedetails[0]->customername.'</div>
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label>Customer Address:&nbsp;</label>
                '.$quotedetails[0]->address.'
              </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <label>Service Name:&nbsp;</label>'.$servicename.'</div>';
          if($productname !="") {
            $html .='<div class="col-md-12 mb-2">
              <label>Product Name:&nbsp;</label>'.$productname.'</div>';
          }
          $html .='<div class="col-md-12 mb-2">
            <label>Frequency:&nbsp;</label>'.$quotedetails[0]->frequency.'
          </div>
          <div class="col-md-12 mb-2">
            <label>Default Time: &nbsp;</label>'.$quotedetails[0]->time.' '.$quotedetails[0]->minute.'
            
          </div>
          <div class="col-md-12 mb-3">
            <label>Price:&nbsp;</label>'.$quotedetails[0]->price.'
          </div>
           <div class="col-md-12 mb-3">
            <label style="position: relative;left: 0px;margin-bottom: 11px;">ETC: &nbsp;</label>'.$quotedetails[0]->etc.'
           </div>
           <div class="col-md-12 mb-3">
             <label>Description:&nbsp;</label>'.$quotedetails[0]->description.'</div>';

          $html .= '</div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function savefieldquote(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;

      if($request->checkcolumn==null) {
        DB::table('tablecolumnlist')->where('page',"companyquote")->where('userid',$auth_id)->delete();
      } else {
        DB::table('tablecolumnlist')->where('page',"companyquote")->where('userid',$auth_id)->delete();
        $fieldlist = $request->checkcolumn;
        foreach($fieldlist as $key => $value) {
          $data['userid'] = $auth_id;
          $data['page'] = $request->page;
          $data['columname'] = $value;
          Managefield::create($data);  
        }
      }
      echo "1";
    }

    public function savefieldticket(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;

      if($request->checkcolumn==null) {
        DB::table('tablecolumnlist')->where('page',"companyticket")->where('userid',$auth_id)->delete();
      } else {
        DB::table('tablecolumnlist')->where('page',"companyticket")->where('userid',$auth_id)->delete();
        $fieldlist = $request->checkcolumn;
        foreach($fieldlist as $key => $value) {
          $data['userid'] = $auth_id;
          $data['page'] = $request->page;
          $data['columname'] = $value;
          Managefield::create($data);  
        }
      }
      echo "1";
    }

    public function leftbarinvoice(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;

      $html ='<div class="add-customer-modal d-flex justify-content-between align-items-center">
                  <h5>Share Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div><input type="hidden" name="ticketid" id="ticketid" value="'.$request->id.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>To</label>
                  <input type="text" class="form-control" placeholder="to" name="to" id="to" value="'.$request->email.'" required="">
                </div>
            </div>
          </div>
          <div class="col-md-12 mb-2" style="display:none;">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>cc</label>
                  <input type="text" class="form-control" placeholder="cc" name="cc" id="cc" value="">
                </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">share</button>
        </div>';
        return json_encode(['html' =>$html]);
          die;
    }

    public function sharequote(Request $request)
    {
      $tdata = Quote::where('id', $request->ticketid)->get()->first();

      $serviceidarray = explode(',', $tdata->serviceid);
      $servicedetails = Service::select('servicename')->whereIn('id', $serviceidarray)->get();
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 

      $servicename = implode(',', $sname);
      $productname = "";
      if($tdata->product_id!="") {
        $parray = explode(',', $tdata->product_id);
        $productdetails = Inventory::select('productname','price')->whereIn('id', $parray)->get();
        foreach ($productdetails as $key => $value) {
            $pname[] = $value['productname'];
        }
        $productname = implode(',', $pname);
      }

      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');

      $contacttoemail = explode(',', $request->to);
      
      foreach($contacttoemail as $key => $contact) {
        $contactList[] = $contact;
      }
      if($tdata->ticket_status==1){
        $title = "Ticket Details:-";
        $qid = "Ticket Id";
       Mail::send('mail_templates.sharequotedetail', ['title'=>$title,'qid'=>$qid,'ticketid'=>$tdata->id,'customername'=>$tdata->customername,'address'=>$tdata->address,'servicename'=>$servicename,'productname'=>$productname,'time'=>$tdata->time,'minute'=>$tdata->minute,'price'=>$tdata->price,'date'=>$tdata->etc], function($message) use ($contactList,$app_name,$app_email) {
          $message->to($contactList);
         
          $message->subject('Ticket Details!');
          //$message->from($app_email,$app_name);
        });
      } else {
        $title = "Quote Details:-";
        $qid = "Quote Id";
         Mail::send('mail_templates.sharequotedetail', ['title'=>$title,'qid'=>$qid,'ticketid'=>$tdata->id,'customername'=>$tdata->customername,'address'=>$tdata->address,'servicename'=>$servicename,'productname'=>$productname,'time'=>$tdata->time,'minute'=>$tdata->minute,'price'=>$tdata->price,'date'=>$tdata->etc], function($message) use ($contactList,$app_name,$app_email) {
            $message->to($contactList);
           
            $message->subject('Quote Details!');
            //$message->from($app_email,$app_name);
          });
     }
       $request->session()->flash('success', 'Ticket shared successfully');
       return redirect()->back();
    }

   public function checklati_long(Request $request) {
    //dd($request->all());
    $formattedAddr = str_replace(' ','+',$request->address);
    //Send request and receive json data by address
    $auth_id = auth()->user()->id;
    $placekey = custom_userinfo($auth_id);
    $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
    $output = json_decode($geocodeFromAddr);
    //Get latitude and longitute from json data
    if($output->results == NULL) {
    return  json_encode(array('status'=>'success','msg'=>'Address Not Found'));
    }
    return  json_encode(array('status'=>'failed'));
   }

   public function ticketdetail(Request $request)
   {
    $url =  $request->url();
    $urlexist = explode('&',$url);
    
    $auth_id = auth()->user()->id;
    $allservices = Service::where('userid', $auth_id)->get();
    $allworker = Personnel::where('userid', $auth_id)->get();

    $quotedetails = Quote::where('id', $request->id)->get();

    $serviceid = explode(',', @$quotedetails[0]->serviceid);

    $servicedetails = Service::select('servicename')->whereIn('id', $serviceid)->get();

    foreach ($servicedetails as $key => $value) {
    $sname[] = $value['servicename'];
    } 
    $servicename = implode(',', $sname);
    $productname = "";
    if($quotedetails[0]->product_id!=null) {
      $pids = explode(',', @$quotedetails[0]->product_id);

      $pdetails = Inventory::select('productname')->whereIn('id', $pids)->get();

      foreach ($pdetails as $key => $value) {
      $pname[] = $value['productname'];
      } 
      $productname = implode(',', $pname);
    }
    

    $allcustomer = Customer::where('userid', $auth_id)->get();
    if(isset($urlexist[1]) && $urlexist[1]== 'q') {
        return view('ticket.quoteview',compact('quotedetails','servicename','allcustomer','productname'));
    } else {
        return view('ticket.ticketview',compact('quotedetails','servicename','allcustomer','productname'));
    }
   }

   
  public function sendticketinvoice(Request $request)
  {
    $tdata = Quote::where('id', $request->ticketid)->get()->first();

    $cinfo = Customer::select('customername','phonenumber','email','companyname','billingaddress','term_name')->where('id',$tdata->customerid)->first();

    $serviceid = explode(',', $tdata->serviceid);
    //dd($serviceid);
    $servicedetails = Service::select('servicename','productid')->whereIn('id', $serviceid)->get();
     
    foreach ($servicedetails as $key => $value) {
      $pid[] = $value['productid'];
      $sname[] = $value['servicename'];
    } 

    $servicename = implode(',', $sname);
    $productids = explode(',', $tdata->product_id);

    $pdetails = Inventory::select('productname','id')->whereIn('id', $productids)->get();
    if(count($pdetails)>0) {
    foreach ($pdetails as $key => $value) {
      $pname[] = $value['productname'];
    } 


    $productname = implode(',', $pname);
  } else {
    $productname = "--";
  }

    $company = User::where('id', $tdata->userid)->get()->first();
    if($company->image!=null) {
      $companyimage = url('').'/userimage/'.$company->image;
    } else {
      $companyimage = url('').'/uploads/servicebolt-noimage.png';
    }

    if($company->subject!=null) {
        $subject = $company->subject;
    } else {
        $subject = 'Invoice Details!';
    }
    $tdata1 = Quote::where('id', $request->ticketid)->get()->first();
    $tdata1->invoiced = 1;
    $tdata1->save();

    $cdefaultimage = url('').'/uploads/servicebolt-noimage.png';

    $app_name = 'ServiceBolt';
    $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');


      //if($cinfo->email!=null) {
        $user_email = $request->to;

          $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'billingaddress'=>$cinfo->billingaddress,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'invoicenote'=>$tdata->invoicenote,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate,'payment_mode'=>$tdata->payment_mode,'term_name'=>$cinfo->term_name]);

          Mail::send('mail_templates.sendbillinginvoice1', ['body'=>$company->bodytext,'type'=>"sendinvoice"], function($message) use ($user_email,$app_name,$app_email,$pdf,$subject) {
          $message->to($user_email);
          $message->subject($subject);
          //$message->from($app_email,$app_name);
          $message->attachData($pdf->output(), "invoice.pdf");
        });

        $request->session()->flash('success', 'Invoice sent');
      // } else {
      //   $request->session()->flash('success', 'Customer Email id not exist.');
      // }
     
     return redirect()->back();
  }


  public function calculateproductprice(Request $request) {
    $json = array();
    $serviceidarray = explode(',', $request->serviceid);
      $servicedetails = Service::select('servicename','price')->whereIn('id', $serviceidarray)->get();
      $sum = 0;
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $sum+= (float)$value['price'];
      } 

    $pidarray = explode(',', $request->productid);
      $pdetails = Inventory::select('productname','id','price')->whereIn('id', $pidarray)->get();
      $sum1 = 0;
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
        $sum1+= (float)$value['price'];
      }
      $totalprice = $sum+$sum1;
      $totalprice = number_format($totalprice,2);
      $totalprice = preg_replace('/[^\d.]/', '', $totalprice);

      return json_encode(['totalprice' =>$totalprice]);
      die;
  }

    public function sendFirebaseNotification($puser, $msgarray, $fcmData) 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $admin = User::select('firebase')->where('role','superadmin')->first();
        
        $fcmApiKey = $admin->firebase;

        $fcmMsg = array(
            'title' => $msgarray['title'],
            'text' => $msgarray['msg'],
            'type' => $msgarray['type'],
            'vibrate' => 1,
            "date_time" => date("Y-m-d H:i:s"),
            'message' => $msgarray['msg'],
            "badge"=>1,
            "sound"=>"default"
        );

        $fcmFields = array(
            'registration_ids' => [$puser->device_token],
            'priority' => 'high',
            'notification' => $fcmMsg,
            'data' => $fcmMsg,
        );

        $headers = array(
        'Authorization: key=' . $fcmApiKey,
        'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
       
        if ($result === false) {

        }
        curl_close($ch);
        return $result;
    }

}
