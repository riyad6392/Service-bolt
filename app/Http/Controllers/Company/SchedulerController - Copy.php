<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Personnel;
use App\Models\Quote;
use DB;
use Mail;
use Illuminate\Support\Str;

class SchedulerController extends Controller
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
        //$ticketData = Quote::where('userid',$auth_id)->where('ticket_status',"1")->orderBy('id','ASC')->get();

        $ticketData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"1")->orderBy('quote.id','ASC')->get();
        //dd($ticketData);
        $todaydate = date('l - F d, Y');
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();
        //dd($scheduleData);

        $customer = Customer::where('userid',$auth_id)->orderBy('id','DESC')->get();
        $services = Service::where('userid', $auth_id)->get();
        $worker = Personnel::where('userid', $auth_id)->get();
        $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
        
        return view('scheduler.index',compact('auth_id','ticketData','scheduleData','customer','services','worker','productData'));
    }

    public function create(Request $request)
    {
    	$auth_id = auth()->user()->id;
    	    $validate = Validator($request->all(), [
                'image' => 'required',
            ]);
            if ($validate->fails()) {
                return redirect()->route('company.servicecreate')->withInput($request->all())->withErrors($validate);
            }
            $logofile = $request->file('image');
            if (isset($logofile)) {
                $datetime = date('YmdHis');
                $image = $request->image->getClientOriginalName();
                $imageName = $datetime . '_' . $image;
                $logofile->move(public_path('uploads/services/'), $imageName);
                $data['image'] = $imageName;
                $data['userid'] = $auth_id;
                $data['servicename'] = $request->servicename;
                $data['price'] = $request->price;
                $data['productid'] = $request->defaultproduct;
                $data['type'] = $request->radiogroup;
                $data['frequency'] = $request->frequency;
                $data['time'] = $request->time;
                if(isset($request->pointckbox)) {
                    $data['checklist'] = implode(',', $request->pointckbox);
                }
            }
            Service::create($data);
            $request->session()->flash('success', 'Service added successfully');
            
            return redirect()->route('company.services');
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

            <input type="text" class="form-control" placeholder="Service Default Price" value="'.$services[0]->price.'" name="price" id="price" required>
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
              <option name="Be weekly" value="Be weekly" '.@$selectedf1.'>Be weekly  </option>
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
            $service->time = $request->time;
            $service->checklist = implode(',', $request->pointckbox);
            
            $service->save();
            $request->session()->flash('success', 'Service Updated successfully');
            return redirect()->route('company.services');
    }

    public function leftbarschedulerdata(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;

      $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();
      //print_r($scheduleData); die;
     
      $json = array();
      $countsdata = count($scheduleData);
      $datacount = $countsdata;
      $html = "";
      for($i=8; $i<=23; $i++) {
        $plusone = $i+1;
        $colon = ":00";
        if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
          $html .='<li><div class="ev-calender-hours">'.$plusone.''.$colon.' <span>'.$ampm.'</span></div></li>';
          foreach($scheduleData as $key => $value) {
              $f= $i+1;
              $m =   ":00";
              // echo $value->giventime;
              // echo "space";
              // echo $f.$m .' '.$ampm;
              if($value->giventime == $f.$m .' '.$ampm) {
                $imagepath = url('/').'/uploads/customer/'.$value->image;
              $html .='<li class="inner yellow-slide" id="drop_'.$value->id.'">
                        <div class="card">
                          <div class="card-body">
                            <div class="imgslider" style="display:none;">
                              <img src="'.$imagepath.'" alt=""/>
                            </div>
                            <input type="hidden" name="customerid" id="customerid" value="'.$value->customerid.'">
                            <input type="hidden" name="quoteid" id="quoteid_'.$value->id.'" value="'.$value->id.'"><span>#'.$value->id.'</span>
                            <h5><i class="fa fa-circle" aria-hidden="true"></i>'.$value->customername.'</h5><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"><i class="fa fa-trash" style="position: absolute;right: 56px;top: 30px;"></i></a>
                            <p><i class="fa fa-circle" aria-hidden="true"></i>'.$value->servicename.'</p>
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
      return json_encode(['html' =>$html,'countsdata'=>$countsdata]);
       
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
              <option selected="">Select a customer </option>';
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
        <option selected="">Select a Personnel </option>';
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
          <option selected="">Service Frequency</option>
          <option name="Weekly" value="Weekly">Weekly</option>
          <option name="Be weekly" value="Be weekly">Be weekly</option>
          <option name="Monthly" value="Monthly">Monthly</option>
        </select>
      </div><div class="col-md-6 mb-2">
      <select class="form-select" name="time" required="">
        <option selected="">Default Time</option>
        <option value="15 Minutes">15 Minutes</option>
        <option value="30 Minutes">30 Minutes</option>
        <option value="45 Minutes">45 Minutes</option>
        <option value="1 Hours">1 Hours</option>
      </select>
     </div><div class="col-md-12 mb-3">
    <input type="text" class="form-control" placeholder="Price" name="price" id="price" required>
     </div><div class="col-md-12 mb-3">
     <label>ETC</label>
      <input type="date" class="form-control" placeholder="ETC" name="etc" id="etc" required>
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

      $customer = Customer::select('customername')->where('id', $request->customerid)->first();
      $auth_id = auth()->user()->id;
      
      $data['userid'] = $auth_id;
      $data['customerid'] = $request->customerid;
      $data['serviceid'] = $request->serviceid;
      $data['customername'] =  $customer->customername;
      $data['servicename'] = $request->servicename;
      $data['personnelid'] = $request->personnelid;
      $data['radiogroup'] = $request->radiogroup;
      $data['frequency'] = $request->frequency;
      $data['time'] = $request->time;
      $data['price'] = $request->price;
      $data['etc'] = $request->etc;
      $data['description'] = $request->description;
      
      Quote::create($data);
      $request->session()->flash('success', 'Quote added successfully');
      
      return redirect()->route('company.services');
    }

    public function sortdata(Request $request)
    {
        $auth_id = auth()->user()->id;
        $quoteid = $request->quoteid;
        $time = $request->time;
        $date = $request->date;
        $tstatus = 2;

        DB::table('quote')->where('id','=',$quoteid)
          ->update([ 
              "ticket_status"=>"$tstatus","giventime"=>"$time","givendate"=>"$date",
          ]);
    }

    public function mapdata(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;

      $scheduleData = DB::table('quote')->select('quote.*', 'personnel.image','personnel.phone','personnel.personnelname','personnel.latitude as lat','personnel.longitude as long')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();

      $json = array();
      $data = [];
          foreach($scheduleData as $key => $value) {
            array_push($data, [$value->personnelname,$value->latitude,$value->longitude,$value->image,$value->phone,$value->giventime]);

          }
      return json_encode(['html' =>$data]);
    }

    public function directquotecreate(Request $request)
    {
      //dd($request->all());
        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();
        $servicedetails = Service::select('servicename','productid')->where('id', $request->servicename)->first();

        $productd = DB::table('products')->select('productname')->where('id', $servicedetails->productid)->first();
        
        $auth_id = auth()->user()->id;
        $data['userid'] = $auth_id;
        $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $request->servicename;
        $data['servicename'] = $servicedetails->servicename;
        $data['product_id'] = $servicedetails->productid;
        $data['product_name'] = $productd->productname;
        $data['personnelid'] = $request->personnelid;
        $data['radiogroup'] = $request->radiogroup;
        $data['frequency'] = $request->frequency;
        $data['time'] = $request->time;
        $data['price'] = $request->price;
        $data['etc'] = $request->etc;
        $data['description'] = $request->description;
        $data['customername'] =  $customer->customername;
        $data['address'] = $request->address;

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyBC9O1b8JhFyUiE2kAU-ULbcio2siKePYU'); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        //print_r($output->results[0]->geometry->location->lat); die;
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
        
      Mail::send('mail_templates.sharequote', ['address'=>$request->address, 'servicename'=>$servicedetails->servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$request->time,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Quot details!');
          //$message->from($app_email,$app_name);
        });

          $request->session()->flash('success', 'Ticket added successfully');
            
          return redirect()->route('company.scheduler');
    }

    public function deleteTicket(Request $request)
    {
        $auth_id = auth()->user()->id;
        $ticketid = $request->targetid;
        $tstatus = 1;

        DB::table('quote')->where('id','=',$ticketid)
          ->update([ 
              "ticket_status"=>"$tstatus"
          ]);
      echo "1";
    }



}
