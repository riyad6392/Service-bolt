<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Quote;
use App\Models\Tenture;
use App\Models\Personnel;
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

      $productData = Inventory::where('user_id',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','desc')->get();

      @$workersdata = Personnel::where('id',$worker->workerid)->first();
      @$permissonarray = explode(',',$workersdata->ticketid);
    if(in_array("View All Customers", $permissonarray)) {
      // $cdata = DB::table('quote')->select('customerid')->where('personnelid',$worker->workerid)->groupBy('customerid')->get();
      // if(count($cdata)>0) {
      //   foreach($cdata as $key=>$value) {
      //     $cids[] = $value->customerid;
      //   }
      //   $customerData = DB::table('customer')->where('userid',$worker->userid)->whereIn('id',$cids)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();   
      // } else {
      //   $customerData = array();
      // }
      $customerData = DB::table('customer')->where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();   
    } 
    else {
     //  $pdata = Quote::select('customerid')->where('personnelid',$worker->workerid)->get();
     //  $cids =  array();
     // // if(count($pdata)>0){
     //  foreach($pdata as $key => $value) {
     //    $cids[] = $value->customerid;
     //  }
    //}
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
    
    $tenture = Tenture::where('status','Active')->get(); 
      //$customerData = DB::table('customer')->where('workerid',$worker->workerid)->orderBy('id','DESC')->get(); 
      return view('personnel.mycustomer',compact('auth_id','customerData','services','productData','tenture'));
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

      $worker = DB::table('users')->select('workerid','userid')->where('id',$auth_id)->first();
      $customerData = Customer::where('id',$id)->get(); 
      //$customerAddress = Address::where('customerid',$id)->get();
      
      $customerAddress = array();
      $cdata = DB::table('quote')->select('customerid','address')->where('customerid',$id)->where('personnelid',$worker->workerid)->where('userid',$worker->userid)->groupBy('address')->get();
            foreach($cdata as $key=>$value) {
              $customerAddress[] = Address::select('id')->where('authid',$worker->userid)->where('customerid',$id)->where('address',$value->address)->first();
            }
          $cidss = array();
          foreach($customerAddress as $key=>$value) {
           $cidss[] =  @$value->id;
          }
          $customerAddress = Address::whereIn('id',$cidss)->where('customerid',$id)->get()->toArray();

          $cadd = Address::where('authid',$auth_id)->where('customerid',$id)->get()->toArray();
          
         $customerAddress  = array_merge($customerAddress,$cadd);
       //dd($customerAddress);
      @$workersdata = Personnel::where('id',$worker->workerid)->first();
      @$permissonarray = explode(',',$workersdata->ticketid);
      if(in_array("See Previous Tickets", $permissonarray)) {
        $recentTicket = Quote::where('customerid',$id)->where('personnelid','!=',null)->where('parentid','=',"")->orderBy('id','DESC')->get();
      } else {
        $recentTicket = Quote::where('customerid',$id)->where('personnelid','!=',null)->where('parentid','=',"")->whereIn('ticket_status',array('2','4'))->orderBy('id','DESC')->get();
      }
      $adminchecklist = DB::table('checklist')->select('serviceid','checklistname')->where('userid',$worker->userid)->groupBy('serviceid')->get();

      return view('personnel.customerview',compact('customerData','customerAddress','recentTicket','adminchecklist','permissonarray'));
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
      @$workersdata = Personnel::where('id',auth()->user()->workerid)->first();
      @$permissonarray = explode(',',$workersdata->ticketid);
      if(in_array("See Price of Previous Tickets", $permissonarray)) { 
        $sclass = "display:block";
      } else {
        $sclass = "display:none";
      }
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
          <div class="col-md-12 mb-3" style="'.$sclass.'">
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
          <div class="col-md-12 mb-3" style="'.$sclass.'">
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
           <label>Billing Address</label>
            <input type="text" class="form-control" placeholder="Billing Address" name="billingaddress" id="billingaddress" value="'.$customer[0]->billingaddress.'">
          </div>

          <div class="col-md-12 mb-2">
          <label>Mailing Address</label>
            <input type="text" class="form-control" placeholder="Mailing Address" name="mailingaddress" id="mailingaddress" value="'.$customer[0]->mailingaddress.'">
          </div>
          
          <div class="col-md-12 mb-2">
            <label>Phone Number</label>

            <input type="text" class="form-control" placeholder="Phone Number" value="'.$customer[0]->phonenumber.'" name="phonenumber" id="phonenumber" required>
          </div>

          <div class="col-md-12 mb-2">
            <label>Email</label>

            <input type="text" class="form-control" placeholder="Email" value="'.$customer[0]->email.'" name="email" id="email">
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
      $customer->save();
      $request->session()->flash('success', 'Customer Updated successfully');
      return redirect()->route('worker.customer');
    }

    public function vieweditnotemodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid','userid')->where('id',$auth_id)->first();
      $adminchecklist = DB::table('checklist')->select('serviceid','checklistname')->where('userid',$worker->userid)->groupBy('serviceid')->get();

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
    
}
