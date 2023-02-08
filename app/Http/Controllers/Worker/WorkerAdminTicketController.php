<?php

namespace App\Http\Controllers\Worker;

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

class WorkerAdminTicketController extends Controller
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

        $workers = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
       
        $quoteData = Quote::where('workerid',$workers->workerid)->where('ticket_status','0')->orderBy('id','DESC')->get();
        $ticketData = Quote::where('workerid',$workers->workerid)->where('ticket_status','1')->orderBy('id','DESC')->get();
        $customer = Customer::where('userid',$workers->userid)->where('workerid',$workers->workerid)->orderBy('id','DESC')->get();
        $services = Service::where('userid', $workers->userid)->where('workerid',$workers->workerid)->get();
        $worker = Personnel::where('userid', $workers->userid)->where('workerid',$workers->workerid)->get();
        $productData = Inventory::where('user_id',$workers->userid)->get();
        $completedticketData = Quote::where('workerid',$workers->workerid)->where('ticket_status','3')->orderBy('id','DESC')->get();
        $table="quote";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);
        $fields1 = DB::getSchemaBuilder()->getColumnListing($table);
        $tenture = Tenture::where('status','Active')->get();
        
        return view('personnel.ticket.index',compact('auth_id','quoteData','ticketData','customer','services','worker','productData','completedticketData','fields','fields1','tenture'));
    }

    public function updateticket(Request $request) {
      $quoteid = $request->quoteid;
      $quote = Quote::where('id', $quoteid)->get()->first();
      $quote->ticket_status = "1";
        $quote->save();
        echo "1";
    }

    public function quotecreate(Request $request)
    {
      
        $serviceid = implode(',', $request->servicename);

        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

        $servicedetails = Service::select('servicename','productid')->whereIn('id', $request->servicename)->get();
       
        foreach ($servicedetails as $key => $value) {
          $pid[] = $value['productid'];
          $sname[] = $value['servicename'];
        } 
        $productid = implode(',', array_unique($pid));
        
        $servicename = implode(',', $sname);
        
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $data['workerid'] = $worker->workerid;
        $data['userid'] = $worker->userid;
        $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $serviceid;
        $data['servicename'] = $servicedetails[0]->servicename;
        $data['product_id'] = rtrim($productid, ',');
        //$data['product_name'] = $pname;
        
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
        $data['customername'] =  $customer->customername;
        $data['address'] = $request->address;

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $auth_id = auth()->user()->userid;
        $placekey = custom_userinfo($auth_id);
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        //print_r($output->results[0]->geometry->location->lat); die;
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;

       $quotelastid = Quote::create($data);
     if($customer->email!=null) {   
      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sharequote', ['name'=>'service quote','address'=>$request->address, 'servicename'=>$servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Quot details!');
          //$message->from($app_email,$app_name);
        });
      }
          $request->session()->flash('success', 'Quote added successfully');
            
          return redirect()->route('worker.managequote');
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
    <input type="text" class="form-control" placeholder="Price" name="price" id="price" required>
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
      //dd($request->all());
        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

        $serviceid = implode(',', $request->servicename);

        $servicedetails = Service::select('servicename','productid')->whereIn('id', $request->servicename)->get();

        foreach ($servicedetails as $key => $value) {
          $pid[] = $value['productid'];
          $sname[] = $value['servicename'];
        } 
        $productid = implode(',', array_unique($pid));

        $servicename = implode(',', $sname);
        
        $auth_id = auth()->user()->id;
        $data['userid'] = $auth_id;
        $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $serviceid;
        $data['servicename'] = $servicedetails[0]->servicename;
        $data['product_id'] = rtrim($productid, ',');
        //$data['product_name'] = $pname;
        
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
        $data['customername'] =  $customer->customername;
        $data['address'] = $request->address;

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $auth_id = auth()->user()->userid;
        $placekey = custom_userinfo($auth_id);
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        //print_r($output->results[0]->geometry->location->lat); die;
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['ticket_status'] = 1;

       $quotelastid = Quote::create($data);
     if($customer->email!=null) {
      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sharequote', ['name'=>'service ticket','address'=>$request->address, 'servicename'=>$servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Quot details!');
          //$message->from($app_email,$app_name);
        });
    }
          $request->session()->flash('success', 'Ticket added successfully');
            
          return redirect()->route('company.quote');
    }

    public function vieweditticketmodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       
       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

       $allservices = Service::where('userid', $worker->userid)->get();
       $allworker = Personnel::where('userid', $worker->userid)->get();

       $quotedetailsnew = Quote::where('id', $request->id)->get()->toArray();
       $quotedetails = Quote::where('id', $request->id)->get();
       
       $allcustomer = Customer::where('userid', $worker->userid)->get();
        
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
       
       $html ='<div class="add-customer-modal">
                  <h5>Edit</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
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
                <select class="form-select" name="address" id="address3" required>
                  <option value="'.$quotedetails[0]->address.'">'.$quotedetails[0]->address.'</option>
                  </select>
              </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <label>Select a Service</label>
            <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" style="height:auto;">';
              foreach($allservices as $key => $value) {
                $serviceids =explode(",", $quotedetails[0]->serviceid);
                 if(in_array($value->id, $serviceids)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }

                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->servicename.'</option>';
              }
        $html .='</select>
          </div>

          <div class="col-md-12 mb-2" style="display:none;">
            <label>Select a Personnel</label>
            <select class="form-select" name="personnelid" id="personnelid">
              <option value="">Select a Personnel</option>';

              foreach($allworker as $key => $value) {
                  if($value->id == $quotedetails[0]->personnelid) {
                    $selectedp1 = "selected";
                  } else {
                    $selectedp1 = "";
                }
                $html .='<option value="'.$value->id.'" '.@$selectedp1.'>'.$value->personnelname.'</option>';
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
              <option value="">Service Frequency</option>';
          foreach ($tenture as $key => $value) {
            if(in_array($value->tenturename, $quotedetailsnew[0])) {
                  $selectedsf = "selected";
                } else {
                  $selectedsf = "";
                }
                $html .='<option name="'.$value->tenturename.'" value="'.$value->tenturename.'" '.@$selectedsf.'>'.$value->tenturename.'</option>';
            }
            $html .='</select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
           <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" value="'.$time[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" value="'.$minute[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div></div>
          <div class="col-md-12 mb-3">
            <label>Price</label>
            <input type="text" class="form-control" placeholder="Price" name="price" id="price" value="'.$quotedetails[0]->price.'" onkeypress="return onlyNumberKey(event)" required>
           </div>
           <div class="col-md-12 mb-3">
            <label style="position: relative;left: 0px;margin-bottom: 11px;">ETC</label>
           <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" value="'.$quotedetails[0]->etc.'" required>
           </div>
           <div class="col-md-12 mb-3">
             <label>Description</label>
             <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required>'.$quotedetails[0]->description.'</textarea>
           </div>';

          $html .= '<div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block">Update</button>
          </div>
        </div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function ticketupdate(Request $request)
    {
      $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

      $servicedetails = Service::select('servicename','productid')->whereIn('id', $request->serviceid)->get();
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
      } 
      $productid = implode(',', array_unique($pid));
             
      $servicename = implode(',', $sname); 

      $quote = Quote::where('id', $request->quoteid)->get()->first();
      $quote->customerid =  $request->customerid;

      //$quote->serviceid = $request->serviceid;
      if(isset($request->serviceid)) {
        $quote->serviceid = implode(',', $request->serviceid);
      } else {
        $quote->serviceid = null;
      }
      $quote->servicename = $servicedetails[0]->servicename;
      $quote->product_id = $productid;
      //$quote->product_name = $pname;
      $quote->personnelid = $request->personnelid;
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
      $quote->address = $request->address;

      $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
      $auth_id = auth()->user()->userid;
      $placekey = custom_userinfo($auth_id);
      $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$placekey); 
      $output = json_decode($geocodeFromAddr);
      $latitude  = $output->results[0]->geometry->location->lat; 
      $longitude = $output->results[0]->geometry->location->lng;

      $quote->latitude = $latitude;
      $quote->longitude = $longitude;

      $quote->save();
      $request->session()->flash('success', 'Updated successfully');
      return redirect()->route('company.quote');
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
       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

       $allservices = Service::where('userid', $worker->userid)->get();
       $allworker = Personnel::where('userid', $worker->userid)->get();

       $quotedetails = Quote::where('id', $request->id)->get();

       $serviceid = explode(',', $quotedetails[0]->serviceid);

       $servicedetails = Service::select('servicename')->whereIn('id', $serviceid)->get();

      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 
      $servicename = implode(',', $sname);

       $allcustomer = Customer::where('userid', $worker->userid)->get();
      
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
            <label>Service Name:&nbsp;</label>'.$servicename.'</div>
          <div class="col-md-12 mb-2">
            <label>Frequency:&nbsp;</label>'.$quotedetails[0]->frequency.'
          </div>
          <div class="col-md-12 mb-2">
            <label>Default Time: &nbsp;</label>'.$quotedetails[0]->time.'
            
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

}
