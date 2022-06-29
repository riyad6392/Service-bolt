<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Quote;
use Image;


class WorkerCustomerController extends Controller
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

      $customerids = DB::table('quote')->select('customerid')->where('personnelid',$worker->workerid)->groupBy('customerid')->get();
    //   if(count($customerids)>0) {
    //   foreach($customerids as $key => $value) {
    //       $customeridss[] = $value->customerid;
    //   }

    //   $customerData = DB::table('customer')->whereIn('id',$customeridss)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();
    // } else {
    //   $customerData = array();
    // }
      $services = Service::where('userid', $worker->userid)->orWhere('workerid',$worker->workerid)->get();

      $customerData = DB::table('customer')->where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();
      //$customerData = DB::table('customer')->where('workerid',$worker->workerid)->orderBy('id','DESC')->get(); 
      return view('personnel.mycustomer',compact('auth_id','customerData','services'));
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

    public function customerview(Request $request,$id) 
    {
      $auth_id = auth()->user()->id;
     
      if(auth()->user()->role == 'worker') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }

      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
      $customerData = Customer::where('id',$id)->get(); 
      $customerAddress = Address::where('customerid',$id)->get();
      $recentTicket = Quote::where('customerid',$id)->where('personnelid','!=',null)->orderBy('id','DESC')->get();
      return view('personnel.customerview',compact('customerData','customerAddress','recentTicket'));
    }

    public function deleteAddress(Request $request)
    {
      $id = $request->id;
      DB::table('address')->delete($id);
      echo "1";
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
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber','personnel.phone','personnel.personnelname','services.image as serviceimage')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.customerid',$request->customerid1)->get();
        $countdata = count($quoteData);
         $datacount = $countdata-1;

      if($quoteData[$datacount]->serviceimage!=null) {
        $imagepath = url('/').'/uploads/services/'.$quoteData[$datacount]->serviceimage;
      } else {
        $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }

      $html ='<div class="row"><h5 class="mb-4">Ticket Info</h5><div class="col-md-7">
         <div class="padding-tree">
           <h5>#'.$quoteData[$datacount]->id.' <span style="color: #B0B7C3;">'.$quoteData[$datacount]->servicename.'</span></h5>
         </div>
         <div>
           <p class="cstmr">Personnel Name</p>
           <h6 class="billy">'.$quoteData[$datacount]->personnelname.'</h6>
         </div>
         <div>
           <p class="cstmr">Personnel Phone</p>
           <h6 class="billy">'.$quoteData[$datacount]->phone.'</h6>
         </div>
         <div>
           <p class="cstmr">Date</p>
           <h6  class="billy">'.$quoteData[$datacount]->etc.'</h6>
         </div>
         </div>
         <div class="col-md-5">
        <div>
          <img src="'.$imagepath.'" class="ticket-img">
       </div>
       </div></div>';
      } else {
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber','personnel.phone','personnel.personnelname','services.image as serviceimage')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->join('services', 'services.id', '=', 'quote.serviceid')->where('quote.id',$request->ticketid)->first();

        if(@$quoteData->serviceimage!=null) {
          $imagepath = url('/').'/uploads/services/'.$quoteData->serviceimage;
        } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }
        
      $html =

      '<div class="row">
      <h5 class="mb-4">Ticket Info</h5>
      <div class="col-md-7">
         <div class="padding-tree">
           <h5>#'.$quoteData->id.' <span style="color: #B0B7C3;">'.$quoteData->servicename.'</span></h5>
         </div>
         <div>
           <p class="cstmr">Personnel Name</p>
           <h6 class="billy">'.$quoteData->personnelname.'</h6>
         </div>
         <div>
           <p class="cstmr">Personnel Phone</p>
           <h6 class="billy">'.$quoteData->phone.'</h6>
         </div>
         <div>
           <p class="cstmr">Date</p>
           <h6  class="billy">'.$quoteData->etc.'</h6>
         </div>
         </div>
         <div class="col-md-5">
        <div>
         <img src="'.$imagepath.'" class="ticket-img">
       </div>
       </div></div>';
      }
      
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function create(Request $request)
    {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $logofile = $request->file('image');
      if (isset($logofile)) {
        $new_file = $logofile;
        $path = 'uploads/customer/';
        $thumbnailpath = 'uploads/customer/thumbnail/';
        $imageName = custom_fileupload1($new_file,$path,$thumbnailpath);

        $data['image'] = $imageName; 
      }
       $data['userid'] = $worker->userid;
        $data['workerid'] = $worker->workerid;
      $data['customername'] = $request->customername;
      $data['phonenumber'] = $request->phonenumber;
      $data['email'] = $request->email;
      $data['companyname'] = $request->companyname;
      if(isset($request->serviceid)) {
          $data['serviceid'] = implode(',', $request->serviceid);
      }
      Customer::create($data);
      $request->session()->flash('success', 'Customer added successfully');
      return redirect()->route('worker.customer');
    }

    public function create1(Request $request)
    {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $logofile = $request->file('image');
      if (isset($logofile)) {
        $new_file = $logofile;
         $path = 'uploads/customer/';
         $thumbnailpath = 'uploads/customer/thumbnail/';
         $imageName = custom_fileupload1($new_file,$path,$thumbnailpath);

         $data['image'] = $imageName; 
      }
       $data['userid'] = $worker->userid;
        $data['workerid'] = $worker->workerid;
      $data['customername'] = $request->customername;
      $data['phonenumber'] = $request->phonenumber;
      $data['email'] = $request->email;
      $data['companyname'] = $request->companyname;
      if(isset($request->serviceid)) {
          $data['serviceid'] = implode(',', $request->serviceid);
      }
      Customer::create($data);
      $request->session()->flash('success', 'Customer added successfully');
      return redirect()->back();
    }

    public function viewcustomermodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;

       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      $customer = Customer::where('id', $request->id)->get();
      
      $serviceData = Service::where('userid',$worker->userid)->orderBy('id','ASC')->get();
      
      if($customer[0]->image != null) {
        $userimage = url('uploads/customer/'.$customer[0]->image);
      } else {
        $userimage = url('/').'/uploads/servicebolt-noimage.png';
      }
       
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

            <input type="text" class="form-control" placeholder="Phone Number" value="'.$customer[0]->phonenumber.'" name="phonenumber" id="phonenumber" required>
          </div>

          <div class="col-md-12 mb-2">
            <label>Email</label>

            <input type="text" class="form-control" placeholder="Email" value="'.$customer[0]->email.'" name="email" id="email" required>
          </div>
          <div class="col-md-12 mb-3">
          <label>Select Services</label>
      <div class="d-flex align-items-center">
        <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" style="height:auto;">';

              foreach($serviceData as $key => $value) {
                 $serviceids =explode(",", $customer[0]->serviceid);
                 if(in_array($value->id, $serviceids)) {
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
           $old_file_name = $customer->image;
           $thumbnailpath = 'uploads/customer/thumbnail/';
           $imageName = custom_fileupload1($new_file,$path,$thumbnailpath,$old_file_name);
           $customer->image = $imageName;
      }
      $customer->save();
      $request->session()->flash('success', 'Customer Updated successfully');
      return redirect()->route('worker.customer');
    }
    
}
