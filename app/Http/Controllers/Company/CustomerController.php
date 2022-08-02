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
use Mail;
use Illuminate\Support\Str;
use DB;
use Image;

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
        return view('customer.index',compact('auth_id','services','customerData','products','customerAddress','fields'));
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
            $cinfo = Customer::create($data);

            $lastId = $cinfo->id;

            $cid = $lastId;
            $data['authid'] = $auth_id;
            $data['customerid'] = $cid;
            $data['address'] = $request->address;
            Address::create($data);

            $request->session()->flash('success', 'Customer added successfully');
            
            return redirect()->route('company.customer');
    }

    public function address(Request $request)
    {
        $cid = $request->customerid;
        $auth_id = auth()->user()->id;
        $data['authid'] = $auth_id;
        $data['customerid'] = $cid;
        $data['address'] = $request->address;
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
        $recentTicket = Quote::where('customerid',$id)->orderBy('id','DESC')->get();
        return view('customer.view',compact('customerData','customerAddress','recentTicket'));
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
       if(count($worker)>0) {
        $wclass = "";
       } else {
        $wclass = "active-focus";
       }
      $html ='<div class="add-customer-modal">
                <h5>Create New Quote</h5>
               </div>';
         
             
      $html  .='<div class="row customer-form"><div class="col-md-12 mb-2">';
        $html .='<input type="hidden" name="customername" id="customername" value="'.$customer[0]->customername.'"><div class="input_fields_wrap">
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
    <div class="col-md-12 mb-3">
        <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="servicename[]" id="servicename" style="height:auto;">';
              foreach($services as $key => $value) {
                $html .='<option value="'.$value->id.'" data-hour="'.$value->time.'" data-min="'.$value->minute.'">'.$value->servicename.'</option>';
              }
        $html .='</select>
      </div><div class="col-md-6 mb-3" style="display:none;">
        <select class="form-select '.$wclass.'" name="personnelid" id="personnelid">
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
      <button class="btn btn-add btn-block" type="submit">Add a Quote</button>
    </div><div class="col-lg-12">
     <button class="btn btn-dark btn-block btn-lg p-2" type="submit"><img src="images/share-2.png"  alt=""/> Share</button>
    </div></div>';
      return json_encode(['html' =>$html]);
        die;
   }

   public function customercreatequote(Request $request)
   {
     // dd($request->all());
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
      $data['serviceid'] = $serviceid;
      $data['customername'] =  $request->customername;
      $data['servicename'] = $servicedetails[0]->servicename;
      $data['product_id'] = $productid;
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
      Quote::create($data);

      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        
      Mail::send('mail_templates.sharequote', ['address'=>$request->address, 'servicename'=>$servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$request->time,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Quot details!');
          $message->from($app_email,$app_name);
        });

      $request->session()->flash('success', 'Quote added successfully');
      
      return redirect()->route('company.quote');
    }

    public function vieweditaddressmodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      //$customer = Customer::where('id', $request->cid)->get();
         
      $html ='<div class="add-customer-modal">
                  <h5>Edit Address</h5>
                 </div><input type="hidden" name="customerid" id="customerid" value="'.$request->cid.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address" value="'.$request->address.'" required="">
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
    $customer->address = $request->address;
    $customer->save();
    $request->session()->flash('success', 'Address updated successfully');
    
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
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber','personnel.phone','personnel.personnelname','services.image as serviceimage')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.customerid',$request->customerid1)->orderBy('quote.id','asc')->get();
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

      $html ='<div class="row"><h5 class="mb-4">Ticket Info #'.$quoteData[$datacount]->id.'</h5>
      <div class="col-md-12">
         
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label><strong>Customer Address</strong>:&nbsp;</label>
                '.$quoteData[$datacount]->address.'
              </div>
          </div>
        </div>
        </div>
        <div class="col-md-12 mb-3">
        <div><strong>Personnel Info:</strong></div>
           <div class="">Name: '.$quoteData[$datacount]->personnelname.'</div>
           <div class="">Phone: '.$quoteData[$datacount]->phone.'</div>
         </div>
         
         <div class="col-md-12 mb-3">
           <div class=""><strong>Service Name:</strong> '.$servicename.'</div>
           
         </div>
         <div class="col-md-12 mb-2">
            <strong><label>Frequency:&nbsp;</label></strong>'.$quoteData[$datacount]->frequency.'
          </div>
          <div class="col-md-12 mb-2">
            <strong><label>Default Time: &nbsp;</label></strong>'.$quoteData[$datacount]->time.' '.$quoteData[$datacount]->minute.'
            
          </div>
          <div class="col-md-12 mb-3">
            <strong><label>Price:&nbsp;</label></strong>'.$quoteData[$datacount]->price.'
          </div>
         
         <div class="col-md-12 mb-3">
           <div class=""><strong>Date:</strong> '.$quoteData[$datacount]->etc.'</div>
         </div>
         </div></div>';
      } else {
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber','personnel.phone','personnel.personnelname','services.image as serviceimage')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.id',$request->ticketid)->first();
      if($quoteData!=null) {
        if($quoteData->serviceimage!=null) {
          $imagepath = url('/').'/uploads/services/'.$quoteData->serviceimage;
        } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }
      } 

      $serviceid = explode(',', $quoteData->serviceid);

       $servicedetails = Service::select('servicename')->whereIn('id', $serviceid)->get();

      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 
      $servicename = implode(',', $sname);
        
      $html =

      '<div class="row"><h5 class="mb-4">Ticket Info #'.$quoteData->id.'</h5>
      <div class="col-md-12">
         
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label><strong>Customer Address</strong>:&nbsp;</label>
                '.$quoteData->address.'
              </div>
          </div>
        </div>
        </div>
        <div class="col-md-12 mb-3">
        <div><strong>Personnel Info:</strong></div>
           <div class="">Name: '.$quoteData->personnelname.'</div>
           <div class="">Phone: '.$quoteData->phone.'</div>
         </div>
         
         <div class="col-md-12 mb-3">
           <div class=""><strong>Service Name:</strong> '.$servicename.'</div>
           
         </div>
         <div class="col-md-12 mb-2">
            <strong><label>Frequency:&nbsp;</label></strong>'.$quoteData->frequency.'
          </div>
          <div class="col-md-12 mb-2">
            <strong><label>Default Time: &nbsp;</label></strong>'.$quoteData->time.' '.$quoteData->minute.'
            
          </div>
          <div class="col-md-12 mb-3">
            <strong><label>Price:&nbsp;</label></strong>'.$quoteData->price.'
          </div>
         
         <div class="col-md-12 mb-3">
           <div class=""><strong>Date:</strong> '.$quoteData->etc.'</div>
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
      DB::table('address')->delete($id);
      echo "1";
    }

    public function viewcustomermodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       
      $customer = Customer::where('id', $request->id)->get();
      //$serviceids =  $customer[0]->serviceid;
     // $serviceids =explode(",", $customer[0]->serviceid);
      $serviceData = Service::where('userid',$auth_id)->orderBy('id','ASC')->get();
   
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
            <label>Phone Number</label>

            <input type="text" class="form-control" placeholder="Phone Number" value="'.$customer[0]->phonenumber.'" name="phonenumber" id="phonenumber" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onpaste="return false" required>
          </div>

          <div class="col-md-12 mb-2">
            <label>Email</label>

            <input type="email" class="form-control" placeholder="Email" value="'.$customer[0]->email.'" name="email" id="email" required>
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
          <div class="col-md-12 mb-2">
            <label>Company Name</label>

            <input type="text" class="form-control" placeholder="Company Name" value="'.$customer[0]->companyname.'" name="companyname" id="companyname" required>
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
      DB::table('customer')->delete($id);
      echo "1";
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
}
