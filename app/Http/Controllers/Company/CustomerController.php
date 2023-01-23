<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Address;
use App\Models\Personnel;
use App\Models\Quote;
use App\Models\Managefield;
use App\Models\Tenture;
use App\Models\User;
use App\Models\Checklist;
use Mail;
use Illuminate\Support\Str;
use DB;
use Image;
use PDF;

class CustomerController extends Controller
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
        $services = Service::where('userid', $auth_id)->get();
        $products = Inventory::where('user_id', $auth_id)->get();
        
        $customerData = Customer::where('userid',$auth_id)->get();
        $customerAddress = Address::where('authid',$auth_id)->get();
          
         $table="customer";
         $fields = DB::getSchemaBuilder()->getColumnListing($table);
        //dd($aaa);   
        $serviceData = Service::where('userid',$auth_id)->orderBy('id','ASC')->get();
        $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
        $tenture = Tenture::where('status','Active')->get();   
        $adminchecklist = DB::table('checklist')->select('serviceid','checklistname')->where('userid',$auth_id)->groupBy('serviceid')->get();
        return view('customer.index',compact('auth_id','services','customerData','products','customerAddress','fields','serviceData','productData','tenture','adminchecklist'));
    }

    public function create(Request $request)
    {
      $auth_id = auth()->user()->id;
      // $validate = Validator($request->all(), [
      //     'image' => 'mimes:jpeg,png',
      // ]);
      // if ($validate->fails()) {
      //     return redirect()->route('company.customercreate')->withInput($request->all())->withErrors($validate);
      // }
      $logofile = $request->file('image');
      if (isset($logofile)) {
       $new_file = $logofile;
       $path = 'uploads/customer/';
       $thumbnailpath = 'uploads/customer/thumbnail/';
       $imageName = custom_fileupload1($new_file,$path,$thumbnailpath);

       $data['image'] = $imageName; 
      }
          $data['userid'] = $auth_id;
          $data['customername'] = $request->customername;
          $data['phonenumber'] = $request->phonenumber;
          $data['email'] = $request->email;
          $data['companyname'] = $request->companyname;
          if(isset($request->serviceid)) {
              $data['serviceid'] = implode(',', $request->serviceid);
          } else {
              $data['serviceid'] = null;
          }

          if(isset($request->productid)) {
              $data['productid'] = implode(',', $request->productid);
          } else {
              $data['productid'] = null;
          }

          if(isset($request->billingaddress)) {
            $data['billingaddress'] = $request->billingaddress;
          } else {
            $data['billingaddress'] = null;
          }

          if(isset($request->mailingaddress)) {
            $data['mailingaddress'] = $request->mailingaddress;
          } else {
            $data['mailingaddress'] = null;
          }
      $cinfo = Customer::create($data);

      $lastId = $cinfo->id;

      $cid = $lastId;
      $data['authid'] = $auth_id;
      $data['customerid'] = $cid;
      $data['address'] = $request->address;
      if(isset($request->adminck)) {
        $data['checklistid'] = implode(',', $request->adminck);
      } else {
        $data['checklistid'] = null;
      }
      Address::create($data);

      $request->session()->flash('success', 'Customer added successfully');
      
      return redirect()->route('company.customer');
    }
    
    public function createcticket(Request $request)
    {
      $auth_id = auth()->user()->id;
      $logofile = $request->file('image');
      if (isset($logofile)) {
         $new_file = $logofile;
         $path = 'uploads/customer/';
         $thumbnailpath = 'uploads/customer/thumbnail/';
         $imageName = custom_fileupload1($new_file,$path,$thumbnailpath);

         $data['image'] = $imageName; 
      }
          $data['userid'] = $auth_id;
          $data['customername'] = $request->customername;
          $data['phonenumber'] = $request->phonenumber;
          $data['email'] = $request->email;
          $data['companyname'] = $request->companyname;
          if(isset($request->serviceid)) {
              $data['serviceid'] = implode(',', $request->serviceid);
          } else {
              $data['serviceid'] = null;
          } 

          if(isset($request->productid)) {
              $data['productid'] = implode(',', $request->productid);
          } else {
              $data['productid'] = null;
          }

          if(isset($request->billingaddress)) {
            $data['billingaddress'] = $request->billingaddress;
          } else {
            $data['billingaddress'] = null;
          }

          if(isset($request->mailingaddress)) {
            $data['mailingaddress'] = $request->mailingaddress;
          } else {
            $data['mailingaddress'] = null;
          }
          
      $cinfo = Customer::create($data);

      $lastId = $cinfo->id;

      $cid = $lastId;
      $data['authid'] = $auth_id;
      $data['customerid'] = $cid;
      $data['address'] = $request->address;
      Address::create($data);

      if($request->ajax()){
        return json_encode(['id' => $cinfo->id,'customername' =>$request->customername]);
    }
      $request->session()->flash('success', 'Customer added successfully');
      
      return redirect()->route('company.quote');
    }


    
    public function address(Request $request)
    {
        
        $cid = $request->customerid;
        $auth_id = auth()->user()->id;
        $data['authid'] = $auth_id;
        $data['customerid'] = $cid;
        $data['address'] = $request->saddress;
        if(isset($request->adminck)) {
          $data['checklistid'] = implode(',', $request->adminck);
        } else {
          $data['checklistid'] = null;
        }
        $data['notes'] = $request->note;

        Address::create($data);

        $request->session()->flash('success', 'Customer Address added successfully');
            
        return redirect()->back();
    }

    public function viewservicepopup(Request $request)
    {

       $json = array();
       $explode_id = explode(',', $request->id);
       $services = Service::select('servicename')->whereIn('id', $explode_id)->get();
       
       $html ='<div class="modal-header">'.$request->name.'
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div><div class="modal-body"> <div class="add-customer-modal"></div>
            <div class="row service-list-dot"><div class="col-md-12 mb-3"><ul>';
            foreach($services as $servicevalue){
                
                    $html .='<li>'.$servicevalue->servicename.'</li>';
                }
                $html .='</ul>
            </div>
        </div></div>';

      return json_encode(['html' =>$html]);
        die;
       
    }

    public function view(Request $request ,$id) {
       $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        $customerData = Customer::where('id',$id)->get(); 
        $customerAddress = Address::where('customerid',$id)->get();
        $recentTicket = Quote::where('customerid',$id)->where('ticket_status','!=',"5")->orderBy('id','DESC')->get();
        $adminchecklist = DB::table('checklist')->select('serviceid','checklistname')->where('userid',$auth_id)->groupBy('serviceid')->get();
        return view('customer.view',compact('customerData','customerAddress','recentTicket','adminchecklist'));
    }

    public function viewall(Request $request ,$id,$address) {
       $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        $customerData = Customer::where('id',$id)->get(); 
        $customerAddress = Address::where('customerid',$id)->get();
        $recentTicket = Quote::where('customerid',$id)->where('address',$address)->orderBy('id','DESC')->get();
        $customeridv = $id;
        return view('customer.viewall',compact('customerData','customerAddress','recentTicket','customeridv','address'));
    }


    public function viewcustomerquotemodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $services = Service::where('userid', $auth_id)->get();
       $customer = Customer::where('id', $request->cid)->get();
       $worker = Personnel::where('userid', $auth_id)->get();
       $tenture = Tenture::where('status','Active')->get();
       $productData = Inventory::where('user_id', $auth_id)->get();
       if(count($worker)>0) {
        $wclass = "";
       } else {
        $wclass = "active-focus";
       }

      $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();

      $html ='<div class="add-customer-modal">
                <h5>Create New Ticket</h5>
               </div>';
         
             
      $html  .='<div class="row customer-form"><div class="col-md-12 mb-2">';

        $html .='<input type="hidden" name="tickettotal" id="tickettotal" value=""><input type="hidden" name="customername" id="customername" value="'.$customer[0]->customername.'"><div class="input_fields_wrap">
          <div class="mb-3">
            <select class="form-select" name="customerid" id="customerid" required="" readonly="">';
                foreach($customer as $key => $value) {
                $html .='<option value="'.$value->id.'">'.$value->customername.'</option>';
              }
              
        $html .='</select>
          </div>
        </div>
      </div>
      <div class="col-md-12 mb-2">
       <div class="input_fields_wrap">
          <div class="mb-3">
            <input type="text" class="form-control" name="address" id="address" value="'.$request->address.'" required="" readonly="">
          </div>
      </div>
    </div>
    <div class="col-md-11 mb-2">
        <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="servicename[]" id="servicename" style="height:auto;" required="">';
              foreach($services as $key => $value) {
                $html .='<option value="'.$value->id.'" data-hour="'.$value->time.'" data-min="'.$value->minute.'">'.$value->servicename.'</option>';
              }
        $html .='</select>
        <div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a href="#"  data-bs-toggle="modal" data-bs-target="#add-services" class="" id="hidequoteservice"><i class="fa fa-plus second"></i></a>
        </div>
      </div>
      <div class="col-md-11 mb-2">
        <select class="form-control selectpickerc1" multiple aria-label="Default select example" data-live-search="true" name="productname[]" id="productname" style="height:auto;" data-placeholder="Select Products">';
              foreach($productData as $key => $value) {
                $html .='<option value="'.$value->id.'" data-price="'.$value->price.'">'.$value->productname.'</option>';
              }
        $html .='</select>
        <div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a href="#"  data-bs-toggle="modal" data-bs-target="#add-products" class="" id="hidequoteproduct"><i class="fa fa-plus third"></i></a>
        </div>
      </div>

      <div class="col-md-12 mb-3">
        <select class="form-select" name="personnelid" id="personnelid">
          <option selected="" value="">Select Personnel</option>';
         foreach($worker as $key => $value) {
                $html .='<option value="'.$value->id.'">'.$value->personnelname.'</option>';
              }
            $html .='</select>
      </div>
      <div class="form-group col-md-6 mb-3 time" style="display:none;">
        <label style="position: relative;left: 12px;margin-bottom: 11px;">Time</label>
        <select class="form-control selectpicker" aria-label="Default select example" data-placeholder="Select Time" data-live-search="true" name="giventime" id="time" style="height:auto;">
        <option value="08:00 am">08:00 am</option>
        <option value="08:30 am">08:30 am</option>
        <option value="09:00 am">09:00 am</option>
        <option value="09:30 am">09:30 am</option>
        <option value="10:00 am">10:00 am</option>
        <option value="10:30 am">10:30 am</option>
        <option value="11:00 am">11:00 am</option>
        <option value="11:30 am">11:30 am</option>
        <option value="12:00 pm">12:00 pm</option>
        <option value="12:30 pm">12:30 pm</option>
        <option value="01:00 pm">01:00 pm</option>
        <option value="01:30 pm">01:30 pm</option>
        <option value="02:00 pm">02:00 pm</option>
        <option value="02:30 pm">02:30 pm</option>
        <option value="03:00 pm">03:00 pm</option>
        <option value="03:30 pm">03:30 pm</option>
        <option value="04:00 pm">04:00 pm</option>
        <option value="04:30 pm">04:30 pm</option>
        <option value="05:00 pm">05:00 pm</option>
        <option value="05:30 pm">05:30 pm</option>
        <option value="06:00 pm">06:00 pm</option>
        <option value="06:30 pm">06:30 pm</option>
        <option value="07:00 pm">07:00 pm</option>
        <option value="07:30 pm">07:30 pm</option>
        <option value="08:00 pm">08:00 pm</option>
        <option value="08:30 pm">08:30 pm</option>
        <option value="09:00 pm">09:00 pm</option>
        <option value="09:30 pm">09:30 pm</option>
        <option value="10:00 pm">10:00 pm</option>
        <option value="10:30 pm">10:30 pm</option>
        <option value="11:00 pm">11:00 pm</option>
        <option value="11:30 pm">11:30 pm</option>
        <option value="12:00 am">12:00 am</option>
        <option value="12:30 am">12:30 am</option>
        <option value="01:00 am">01:00 am</option>
        <option value="01:30 am">01:30 am</option>
        <option value="02:00 am">02:00 am</option>
        <option value="02:30 am">02:30 am</option>
        <option value="03:00 am">03:00 am</option>
        <option value="03:30 am">03:30 am</option>
        <option value="04:00 am">04:00 am</option>
        <option value="04:30 am">04:30 am</option>
        <option value="05:00 am">05:00 am</option>
        <option value="05:30 am">05:30 am</option>
        <option value="06:00 am">06:00 am</option>
        <option value="06:30 am">06:30 am</option>
        <option value="07:00 am">07:00 am</option>
        <option value="07:30 am">07:30 am</option>';
      $html .='</select></div>
      <div class="col-md-6 mb-3 date" style="display:none;">
     <label style="position: relative;left: 12px;margin-bottom: 11px;">Date</label>
      <input type="date" class="form-control etc" placeholder="Date" name="date" id="date" onkeydown="return false" style="position: relative;">
     </div>
      <div class="col-md-12 mb-3">
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
          <option selected="" value="">Service Frequency</option>';
          foreach ($tenture as $key => $value) {
            $html .='<option name="'.$value->tenturename.'" value="'.$value->tenturename.'">'.$value->tenturename.'</option>';
            }
            $html .='</select>
      </div><div class="col-md-6 mb-2">
            <div class="timepicker timepicker1" style="display:inline-block;">
           <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div></div><div class="col-md-12 mb-3 position-relative">
            <i class="fa fa-dollar" style="position: absolute;top: 17px;left: 27px;"></i>
    <input type="text" class="form-control" placeholder="Price" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||  
   event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required>
     </div><div class="col-md-12 mb-3">
     <label style="position: relative;left: 12px;margin-bottom: 11px;">ETC</label>
      <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" required>
     </div><div class="col-md-12 mb-3">
      <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required></textarea>
     </div> <div class="col-lg-6 mb-3">
      <button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
    </div><div class="col-lg-6 mb-3">
      <button class="btn btn-add btn-block" type="submit" name="ticket" value="ticket">Add a Ticket</button>
    </div><div class="col-lg-12">
     <button class="btn btn-dark btn-block btn-lg p-2" type="submit" name="share" value="share"><img src="images/share-2.png"  alt=""/> Share</button>
    </div></div>';
      return json_encode(['html' =>$html]);
        die;
   }

   public function customercreatequote(Request $request)
   {
      $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

      $serviceid = implode(',', $request->servicename);

      $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', auth()->user()->id)->first();

      $servicedetails = Service::select('servicename','productid','price')->whereIn('id', $request->servicename)->get();
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
      $txvalue1 = 0;
      if($request->productname!="") {
        $productdetails = Inventory::select('id','productname','price')->whereIn('id', $request->productname)->get();
             
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
      $data['serviceid'] = $serviceid;
      $data['product_id'] = $productid;
      $data['customername'] =  $request->customername;
      $data['servicename'] = $servicedetails[0]->servicename;
      $data['product_name'] = $productname1;
      //$data['product_name'] = $pname;
      $data['personnelid'] = $request->personnelid;
      $data['radiogroup'] = $request->radiogroup;
      $data['frequency'] = $request->frequency;
      $data['primaryname'] = $request->personnelid;

      if($request->time!=null || $request->time!=0) {
          $data['time'] = $request->time.' Hours';
        }
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';;
        }
      $data['price'] = $request->price;
      $data['etc'] = $request->etc;
      $data['description'] = $request->description;
      $data['address'] = $request->address;
      $data['tickettotal'] = $request->tickettotal;
      $data['tax'] = $totaltax; 
       
      $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        //print_r($output->results[0]->geometry->location->lat); die;
        if(empty($output->results)) {
          $request->session()->flash('error', 'This address not found.');
          return redirect()->back();
        }
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

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
         
            $date = Carbon::createFromFormat('Y-m-d', $request->date)->format('l - F d, Y');
            $newdate = $request->date;

          
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
        $data['givenstartdate'] = $request->date;
        $data['givenenddate'] = $givenenddate;
        $data['ticket_status'] = 2;
      } else {
        $data['ticket_status'] = 1;
      }
        //end new feature here
        //dd($data);
        $quotelastid = Quote::create($data);
        $quoteee = Quote::where('id', $quotelastid->id)->first();
        $randomid = rand(100,199);
        $quoteee->invoiceid = $randomid.''.$quotelastid->id;
        $quoteee->save();
    if($customer->email!=null) {
      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sharequote', ['name'=>'service ticket','address'=>$request->address, 'servicename'=>$servicename,'productname'=>$productname,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Service Ticket from '. auth()->user()->companyname);
          //$message->from($app_email,$app_name);
        });
    }
      if($request->share =='share') {
          $request->session()->flash('success', 'Ticket share successfully');
          return redirect()->route('company.quote'); 
      }
      if($request->date!="") {
          $request->session()->flash('success', 'Ticket scheduled successfully');
          return redirect()->route('company.scheduler');
      } else {
          $request->session()->flash('success', 'Ticket created successfully');
          return redirect()->route('company.quote');  
      }
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

    public function vieweditaddressmodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
         
      $html ='<div class="add-customer-modal">
                  <h5>Edit Address</h5>
                 </div><input type="hidden" name="customerid" id="customerid" value="'.$request->cid.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address6" value="'.$request->address.'" required="">
                </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">Update</button>
        </div>';
        return json_encode(['html' =>$html]);
          die;
    }

    public function vieweditnotemodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      
      $adminchecklist = DB::table('checklist')->select('serviceid','checklistname')->where('userid',$auth_id)->groupBy('serviceid')->get();

      $addressinfo = Address::select('checklistid')->where('id',$request->cid)->first();
      $html ='<div class="add-customer-modal">
                  <div style="font-size:25px;">Edit Notes</div>
                 </div>';
               $html .='<div class="col-md-12 mb-2">
                <div class="input_fields_wrap">
                  <select class="form-control selectpicker " multiple="" data-placeholder="Select Checklist" data-live-search="true" style="width: 100%;" tabindex="-1" aria-hidden="true" name="adminck[]" id="adminck">';
                    foreach($adminchecklist as $key =>$value1) {
                      $checklistids =explode(",", $addressinfo->checklistid);
                      
                      if(in_array($value1->serviceid, $checklistids)) {
                        $selectedp = "selected";
                      } else {
                        $selectedp = "";
                      }
                      $html .='<option value="'.$value1->serviceid.'" '.@$selectedp.'>'.$value1->checklistname.'</option>';
                    }
                  $html .='</select>
                </div>
              </div>';  
              $html .='<input type="hidden" name="customerid" id="customerid" value="'.$request->cid.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                <textarea class="form-control" name="note" id="note" placeholder="Notes" cols="45" rows="5">'.$request->note.'</textarea>
                  </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">Update</button>
        </div>';
        return json_encode(['html' =>$html]);
          die;
    }

  public function updateaddress(Request $request)
  {
    $customer = Address::where('id', $request->customerid)->get()->first();
    
    $address = $customer->address;
    $cid = $customer->customerid;

    $quotedetails = Quote::where('customerid',$cid)->where('address',$address)->get();
    
    foreach($quotedetails as $key =>$value) {
      $quote = Quote::find($value->id); 
      $quote->address = $request->address;
      $quote->save();
    }
     
    $customer->address = $request->address;
    $customer->save();
    $request->session()->flash('success', 'Address updated successfully');
    
    return redirect()->back();
  }

  public function updatenotes(Request $request)
  {
    $customer = Address::where('id', $request->customerid)->get()->first();
    $customer->notes = $request->note;

    if(isset($request->adminck)) {
      $customer->checklistid = implode(',', $request->adminck);
    } else {
      $customer->checklistid = null;
    }

    $customer->save();
    $request->session()->flash('success', 'Notes updated successfully');
    
    return redirect()->back();
  }

  public function leftbarticketdata(Request $request)
  {
      $targetid =  $request->targetid;
      $customerid = $request->customerid;
      
      $json = array();
      if($targetid == 0) {
        $auth_id = auth()->user()->id;
        //$services = Service::where('userid',$auth_id)->orderBy('id','ASC')->get();
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber','personnel.phone','personnel.personnelname','services.image as serviceimage')->join('customer', 'customer.id', '=', 'quote.customerid')->leftjoin('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.customerid',$request->customerid1)->orderBy('quote.id','asc')->get();
        $countdata = count($quoteData);
         $datacount = $countdata-1;

      if($quoteData[$datacount]->serviceimage!=null) {
        $imagepath = url('/').'/uploads/services/'.$quoteData[$datacount]->serviceimage;
      } else {
        $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }

      $serviceid = explode(',', $quoteData[$datacount]->serviceid);

      $servicedetails = Service::select('servicename')->whereIn('id', $serviceid)->get();

      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 
      $servicename = implode(',', $sname);

      $productname = "";
      if($quoteData[$datacount]->product_id!="") {
        $product_id = explode(',', $quoteData[$datacount]->product_id);

        $productdetails = Inventory::select('productname')->whereIn('id', $product_id)->get();

        foreach($productdetails as $key => $value) {
          $pname[] = $value['productname'];
        } 
        $productname = implode(',', $pname);
      }

      if($quoteData[$datacount]->ticket_status == "3") {
        $ticketstatus = "Completed";
      }
      if($quoteData[$datacount]->ticket_status == "2") {
        $ticketstatus = "Assigned";
      }
      if($quoteData[$datacount]->ticket_status == "1") {
        $ticketstatus = "Not Assign";
      }
      if($quoteData[$datacount]->ticket_status == "4") {
        $ticketstatus = "Picked";
      }
      if($quoteData[$datacount]->ticket_status == "0") {
        $ticketstatus = "--";
      }

      if($quoteData[$datacount]->ticket_status == 1) {
        $tstatus = "pointer-events:none;background:#fee2002e;";
      } else {
        $tstatus = "";
      }

      $viewinvoiceurl = url('/').'/company/customer/viewinvoice/'.$quoteData[$datacount]->id;

      $html ='<div class="row"><h5 class="mb-2">Ticket Info #'.$quoteData[$datacount]->id.'</h5>
      <div class="col-md-12">
          
          <div class="col-md-12 mb-2">
            <div class="input_fields_wrap">
              <div class="mb-3">
              <label class="number-1">Ticket Status:</label>
                <p>'.$ticketstatus.'</p>
              </div>
            </div>
          </div>';

        if($quoteData[$datacount]->payment_mode != null) {
          $html .='<div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
                <div class="number-1">Payment Mode:</div>
                  '.$quoteData[$datacount]->payment_mode.'
                </div>
              </div>
            </div>
          </div>';
        }
          $html .='<div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label class="number-1">Customer Address:</label>
                <p>'.$quoteData[$datacount]->address.'</p>
              </div>
          </div>
        </div>
        </div>
        <div class="col-md-12 mb-3">
        <div class="number-1">Personnel Info:</div>
           <div class="">'.$quoteData[$datacount]->personnelname.', '.$quoteData[$datacount]->phone.'</div>
           </div>
         
         <div class="col-md-12 mb-3">
           <div class="number-1">Service Name: </div>'.$servicename.'
           
         </div>';
          if($productname !="") {
            $html .='<div class="col-md-12 mb-3">
              <div class="number-1">Product Name:</div>'.$productname.'</div>';
          }
          $html .='<div class="col-md-12 mb-3">
            <div class="number-1">Frequency:</div>'.$quoteData[$datacount]->frequency.'
          </div>
          <div class="col-md-12 mb-2">
            <div class="number-1">Default Time: &nbsp;</div>'.$quoteData[$datacount]->time.' '.$quoteData[$datacount]->minute.'
            
          </div>
          <div class="col-md-12 mb-3">
            <div class="number-1">Price:</div>'.$quoteData[$datacount]->price.'
          </div>
         
         <div class="col-md-12 mb-3">
           <div class="number-1">Date:</div>'.$quoteData[$datacount]->etc.'
         </div>

         <div class="col-md-12 mb-3">
         <a class="btn add-btn-yellow w-100 viewinvoice" data-id="'.$quoteData[$datacount]->id.'" data-duedate="'.$quoteData[$datacount]->duedate.'" data-invoicenote="'.$quoteData[$datacount]->invoicenote.'" data-bs-toggle="modal" data-bs-target="#view-invoice" style="'.$tstatus.'">Invoice</a>
         </div>
         </div></div>';
      } else {
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber','personnel.phone','personnel.personnelname','services.image as serviceimage')->join('customer', 'customer.id', '=', 'quote.customerid')->leftjoin('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.id',$request->ticketid)->first();
      if($quoteData!=null) {
        if($quoteData->serviceimage!=null) {
          $imagepath = url('/').'/uploads/services/'.$quoteData->serviceimage;
        } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }
      }

      $productname = "";
      if($quoteData->product_id!="") {
        $product_id = explode(',', $quoteData->product_id);

        $productdetails = Inventory::select('productname')->whereIn('id', $product_id)->get();

        foreach($productdetails as $key => $value) {
          $pname[] = $value['productname'];
        } 
        $productname = implode(',', $pname);
      } 

      $serviceid = explode(',', $quoteData->serviceid);

       $servicedetails = Service::select('servicename')->whereIn('id', $serviceid)->get();

      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 
      $servicename = implode(',', $sname);
      
      if($quoteData->ticket_status == "3") {
        $ticketstatus = "Completed";
      }
      if($quoteData->ticket_status == "2") {
        $ticketstatus = "Assigned";
      }
      if($quoteData->ticket_status == "1") {
        $ticketstatus = "Not Assign";
      }
      if($quoteData->ticket_status == "4") {
        $ticketstatus = "Picked";
      }
      if($quoteData->ticket_status == "0") {
        $ticketstatus = "--";
      }

      if($quoteData->ticket_status == 1) {
        
        $tstatus = "pointer-events:none;background:#fee2002e;";
      } else {
        $tstatus = "";
      }

      $viewinvoiceurl = url('/').'/company/customer/viewinvoice/'.$quoteData->id;

      $html =

      '<div class="row"><h5 class="mb-2">Ticket Info #'.$quoteData->id.'</h5>
      <div class="col-md-12">
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <div class="number-1">Ticket Status:</div>
                '.$ticketstatus.'
              </div>
          </div>
        </div>
        </div>';

        if($quoteData->payment_mode != null) {
          $html .='<div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
                <div class="number-1">Payment Mode:</div>
                  '.$quoteData->payment_mode.'
                </div>
              </div>
            </div>
          </div>';
        }
          $html .='<div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <div class="number-1">Customer Address:</div>
                '.$quoteData->address.'
              </div>
          </div>
        </div>
        </div>
        <div class="col-md-12 mb-3">
        <div class="number-1">Personnel Info:</div>
           <div class="">'.$quoteData->personnelname.', '.$quoteData->phone.'</div>
         </div>
         
         <div class="col-md-12 mb-3">
           <div class="number-1">Service Name:</div> '.$servicename.'
           
         </div>';
          if($productname !="") {
            $html .='<div class="col-md-12 mb-3">
              <div class="number-1">Product Name:</div>'.$productname.'</div>';
          }
          $html .='<div class="col-md-12 mb-2">
            <div class="number-1">Frequency:</div>'.$quoteData->frequency.'
          </div>
          <div class="col-md-12 mb-2">
            <div class="number-1">Default Time:</div>'.$quoteData->time.' '.$quoteData->minute.'
            
          </div>
          <div class="col-md-12 mb-3">
            <div class="number-1">Price:</div>'.$quoteData->price.'
          </div>
         
         <div class="col-md-12 mb-3">
           <div class="number-1">Date:</div> '.$quoteData->etc.'
         </div>
         <div class="col-md-12 mb-3">
         <a class="btn add-btn-yellow w-100 mb-4 viewinvoice" data-id="'.$quoteData->id.'" data-duedate="'.$quoteData->duedate.'" data-invoicenote="'.$quoteData->invoicenote.'" data-bs-toggle="modal" data-bs-target="#view-invoice" style="'.$tstatus.'">Invoice</a>
         </div>
         </div></div>';
      }
      
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function deleteAddress(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;
      $addressinfo = DB::table('address')->select('customerid','address')->where('id',$id)->first();
      $alreadyexist = DB::table('quote')->select('id','customerid','address')->where('customerid',$addressinfo->customerid)->where('address',$addressinfo->address)->first();
      if($alreadyexist!=null) {
        echo "0";
      } else {
        DB::table('address')->delete($id);
        echo "1";
      }
    }

    public function viewcustomermodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       
      $customer = Customer::where('id', $request->id)->get();
      //$serviceids =  $customer[0]->serviceid;
     // $serviceids =explode(",", $customer[0]->serviceid);
      $serviceData = Service::where('userid',$auth_id)->orderBy('id','ASC')->get();
      $productData = Inventory::where('user_id',$auth_id)->orderBy('id','DESC')->get();
      
      if($customer[0]->image != null) {
        $userimage = url('uploads/customer/'.$customer[0]->image);
        } else {
          $userimage = url('/').'/uploads/servicebolt-noimage.png';
        }

       $customerAddress = Address::where('authid',$auth_id)->get();   
       
       $html ='<div class="add-customer-modal">
                  <h5>Edit Customer</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="customerid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Customer Name</label>
            <input type="text" class="form-control" placeholder="Customer Name" name="customername" id="customername" value="'.$customer[0]->customername.'" required>
          </div>
          </div>

          <div class="col-md-12 mb-2">
           <label>Billing Address</label>
            <input type="text" class="form-control" placeholder="Billing Address" name="billingaddress" id="billingaddress1" value="'.$customer[0]->billingaddress.'">
          </div>

          <div class="col-md-12 mb-2">
          <label>Mailing Address</label>
            <input type="text" class="form-control" placeholder="Mailing Address" name="mailingaddress" id="mailingaddress1" value="'.$customer[0]->mailingaddress.'">
          </div>

          <div class="col-md-12 mb-2">
            <label>Phone Number</label>

            <input type="text" class="form-control" placeholder="Phone Number" value="'.$customer[0]->phonenumber.'" name="phonenumber" id="phonenumber" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onpaste="return false" required>
          </div>

          <div class="col-md-12 mb-2">
            <label>Email</label>

            <input type="email" class="form-control email" placeholder="Email" value="'.$customer[0]->email.'" name="email" data-id="'.$customer[0]->id.'">
          </div>
          <div class="col-md-12 mb-3 email_msg " style="display:none;">
     
          </div>
          <div class="col-md-12 mb-3">
          <label>Select Services</label>
      <div class="d-flex align-items-center">
        <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" style="height:auto;">';

              foreach($serviceData as $key => $value) {
                 //$serviceids =  $customer[0]->serviceid;
                 $serviceids =explode(",", $customer[0]->serviceid);
                 if(in_array($value->id, $serviceids)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->servicename.'</option>';
              }
        $html .='</select>
          </div>

          <div class="col-md-12 mb-3">
          <label>Select Products</label>
      <div class="d-flex align-items-center">
        <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="productid[]" id="productid" style="height:auto;" data-placeholder="Select Products">';

          foreach($productData as $key => $value) {
            $productids =explode(",", $customer[0]->productid);
            if(in_array($value->id, $productids)) {
              $selectedp1 = "selected";
            } else {
              $selectedp1 = "";
            }
            $html .='<option value="'.$value->id.'" '.@$selectedp1.'>'.$value->productname.'</option>';
          }
        $html .='</select>
          </div>
          <div class="col-md-12 mb-2">
            <label>Company Name</label>

            <input type="text" class="form-control" placeholder="Company Name" value="'.$customer[0]->companyname.'" name="companyname" id="companyname">
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
          </div>
        </div></div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function update(Request $request)
    {
      $customer = Customer::where('id', $request->customerid)->get()->first();
      $customer->customername = $request->customername;
      $customer->phonenumber = $request->phonenumber;
      $customer->email = $request->email;
      $customer->companyname = $request->companyname;
      if(isset($request->serviceid)) {
        $customer->serviceid = implode(',', $request->serviceid);
      } else {
        $customer->serviceid = null;
      }

      if(isset($request->productid)) {
        $customer->productid = implode(',', $request->productid);
      } else {
        $customer->productid = null;
      }

      if(isset($request->billingaddress)) {
        $customer->billingaddress = $request->billingaddress;
      } else {
        $customer->billingaddress = null;
      }

      if(isset($request->mailingaddress)) {
        $customer->mailingaddress = $request->mailingaddress;
      } else {
        $customer->mailingaddress = null;
      }

      $logofile = $request->file('image');
      if (isset($logofile)) {
           $new_file = $logofile;
           $path = 'uploads/customer/';
           $thumbnailpath = 'uploads/customer/thumbnail/';
           $old_file_name = $customer->image;
           $imageName = custom_fileupload1($new_file,$path,$thumbnailpath,$old_file_name);
           $customer->image = $imageName;
      }
      $customer->save();
      $request->session()->flash('success', 'Customer Updated successfully');
      return redirect()->route('company.customer');
    }

    public function deleteCustomer(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;
      $alreadyexist = DB::table('quote')->where('customerid',$id)->first();
      if($alreadyexist!=null){
        echo "0";
      } else {
        DB::table('customer')->delete($id);
        echo "1";
      }
      
    }

    public function savefieldpage(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;

      if($request->checkcolumn==null) {
        DB::table('tablecolumnlist')->where('page',"companycustomer")->where('userid',$auth_id)->delete();
      } else {
        DB::table('tablecolumnlist')->where('page',"companycustomer")->where('userid',$auth_id)->delete();
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

    public function calculateprice(Request $request) {
      $json = array();
      $serviceidarray = explode(',', $request->servicename);
      $servicedetails = Service::select('servicename','price')->whereIn('id', $serviceidarray)->get();
      $sum = 0;
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $sum+= (int)$value['price'];
      }

      $totalprice = $sum;

      return json_encode(['totalprice' =>$totalprice]);
        die;
    }


    public function checkemail(Request $request) {
      $givenemail = $_GET['email'];
      $givenid = $_GET['cid'];
      if($givenid == 0) {
        $emailData = Customer::where('email','=',$givenemail)->first();  
      } else {
        $emailData = Customer::where('email','=',$givenemail)->whereNotIn('id', $givenid)->first();  
      }
      
      if($emailData) {
        echo 1;
      }
      else {
        echo 0;
      }
    }

    public function viewinvoice(Request $request)
    {
      $tdata = Quote::where('id', $request->ticketid)->get()->first();
      $tdata->duedate = $request->duedate;
      $tdata->invoicenote = $request->description;
      $tdata->save();

      $cinfo = Customer::select('customername','phonenumber','email','companyname','billingaddress')->where('id',$tdata->customerid)->first();
      $serviceid = explode(',', $tdata->serviceid);

      $servicedetails = Service::select('servicename','productid','description')->whereIn('id', $serviceid)->get();
             
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
      } 

      $servicename = implode(',', $sname);
      $productids = explode(',', $tdata->product_id);
      $pdetails = Inventory::select('productname','id','description')->whereIn('id', $productids)->get();
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

      $cdefaultimage = url('').'/uploads/servicebolt-noimage.png';
      $givendate = $tdata->givendate;
      if($request->invoicetype == "viewinvoice") {
        return view('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'billingaddress'=>$cinfo->billingaddress,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'invoicenote'=>$tdata->customernotes,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate]); 
      }

      if($request->invoicetype == "downloadinvoice") {
        $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'billingaddress'=>$cinfo->billingaddress,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'invoicenote'=>$tdata->customernotes,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate]);
          return $pdf->download($tdata->id .'_invoice.pdf');
      }

      if($request->invoicetype == "sendinvoice") {
        $app_name = 'ServiceBolt';
        $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
        $cinfo = Customer::select('customername','phonenumber','email','companyname')->where('id',$tdata->customerid)->first();
        if($cinfo->email!=null) {

          $tdata1 = Quote::where('id', $request->ticketid)->get()->first();
          $tdata1->invoiced = 1;
          $tdata1->save();
          $user_exist = Customer::where('email', $cinfo->email)->first();

            $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'billingaddress'=>$cinfo->billingaddress,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'invoicenote'=>$tdata->customernotes,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate]);

            Mail::send('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'invoicenote'=>$tdata->customernotes,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate], function($message) use ($user_exist,$app_name,$app_email,$pdf) {
            $message->to($user_exist->email);
            $message->subject('Invoice PDF!');
            //$message->from($app_email,$app_name);
            $message->attachData($pdf->output(), "invoice.pdf");
          });

          return redirect()->back()->withSuccess('Invoice sent');
        } else {
          return redirect()->back()->withSuccess('Customer Email id not exist.');
        }
      }
    }

    public function leftbarviewinvoice(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      
      if($request->duedate!="") {
        $duedate =   $request->duedate;
      }  else {
        $duedate = "";
      }
      if($request->invoicenote!="") {
        $invoicenote =   $request->invoicenote;
      }  else {
        $invoicenote = "";
      }
      $html ='<div class="add-customer-modal">
                  <h5>Invoice</h5>
                 </div><input type="hidden" name="ticketid" id="ticketid" value="'.$request->id.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>Select Due Date</label>
                  <input type="date" class="form-control" placeholder="Due Date" name="duedate" id="duedate" value="'.$duedate.'" style="position:relative;">
                </div>
            </div>
          </div>
          <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>Invoice Notes</label>
                  <textarea class="form-control height-110" placeholder="Invoice Note" name="description" id="description">'.$invoicenote.'</textarea>
                </div>
            </div>
          </div>
        <div class="row">
          <div class="col-lg-4 mb-3 mx-auto">
            <button class="btn btn-add btn-block" type="submit" name="invoicetype" value="viewinvoice">View Invoice</button>
          </div>
          <div class="col-lg-4 mb-3 mx-auto">
            <button class="btn btn-add btn-block" type="submit" name="invoicetype" value="downloadinvoice">Download</button>
          </div>
          <div class="col-lg-4 mb-3 mx-auto">
            <button class="btn btn-add btn-block" type="submit" name="invoicetype" value="sendinvoice">Send to Customer</button>
          </div>
        </div>';
        return json_encode(['html' =>$html]);
          die;
    }
}
