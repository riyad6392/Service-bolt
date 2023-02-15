<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Service;
use App\Models\Tenture;
use App\Models\Customer;

class WorkerServicesController extends Controller
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

     $servicedetails = DB::table('services')->select('*')->where('userid',$worker->userid)->get();

//       $serviceids = DB::table('quote')->select('serviceid','customerid')->where('personnelid',$worker->workerid)->get();
// if(count($serviceids)>0) {
//       foreach($serviceids as $key => $value) {
//           $serviceidss[] = $value->serviceid;
//       }

//       foreach($serviceids as $key => $value) {
//           $customerids[] = $value->customerid;
//       }

//       $servicedetails = DB::table('quote')->whereIn('serviceid',$serviceidss)->whereIn('customerid',$customerids)->get();
//     } else {
//       $servicedetails = array();
//     }
      //dd($servicedetails);
      return view('personnel.myservices',compact('auth_id','servicedetails'));
    }

    public function viewserviceticketmodal(Request $request) {
       $json = array();
       
       $auth_id = auth()->user()->id;
       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
       $customer = Customer::where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();

       $serviceData = Service::where('id',$request->id)->orderBy('id','ASC')->get();

      $productData = DB::table('products')->where('user_id',$worker->userid)->orderBy('id','desc')->get();
       $tenture = Tenture::where('status','Active')->get();
       $html ='<div class="add-customer-modal">
                  <h5>Create Ticket</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
            
            <div class="col-md-12 mb-2">
            <label>Customer</label>
            <select class="form-select" name="customerid" id="customerid1" required=""><option value="">Select Customer</option>';

             foreach($customer as $key => $value) {

               
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->customername.'</option>';
              }
             
            $html .='</select>
          </div>
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label>Customer Address</label>
                <select class="form-select" name="address" id="address2" required>
                  <option value="">Select Customer Address</option>
                  </select>
              </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <label>Service</label>
            <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="servicename[]" id="servicename" required="" style="height:auto;">';
            foreach($serviceData as $key => $value) {
                //$serviceids =explode(",", $quote->serviceid);
                $ids[] = $request->id;
                 if(in_array($value->id, $ids)) {
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->servicename.'</option>';
              }
             
            $html .='</select>
          </div>
         
         <div class="col-md-12 mb-2">
            <label>Product</label>
            <select class="form-control selectpicker" data-live-search="false" multiple="" data-placeholder="Select Products" style="width: 100%;height:auto;" tabindex="-1" aria-hidden="true" name="productid[]" id="productid" style="height:auto;">';

              foreach($productData as $key => $value) {
                $html .='<option value="'.$value->id.'">'.$value->productname.'</option>';
              }
            $html .='</select>
          </div>

          <div class="col-md-12 mb-2">
            <div class="align-items-center justify-content-lg-between d-flex services-list">
               <p>
                <input type="radio" id="test4" name="radiogroup" value="perhour" checked>
                <label for="test4">Per Hour</label>
              </p>
              <p>
                <input type="radio" id="test5" name="radiogroup" value="flatrate">
                <label for="test5">Flate Rate</label>
              </p>
              <p>
                <input type="radio" id="test6" name="radiogroup" value="recurring">
                <label for="test6">Recurring</label>
              </p>
            </div>
          </div>

          <div class="col-md-6 mb-2">
            <label>Service Frequency</label>
            <select class="form-select" name="frequency" id="frequency" required="">
              <option value="">Service Frequency</option>';
            foreach ($tenture as $key => $value) {
                $html .='<option name="'.$value->tenturename.'" value="'.$value->tenturename.'">'.$value->tenturename.'</option>';
            }
            $html .='</select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
           <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div></div>
          <div class="col-md-12 mb-3">
            <label>Price</label>
            <input type="text" class="form-control" placeholder="Price" name="price" id="price" value="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" required>
           </div>
           <div class="col-md-12 mb-3">
            <label style="position: relative;left: 0px;margin-bottom: 11px;">ETC</label>
           <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" value="" required>
           </div>
           <div class="col-md-12 mb-3">
             <label>Description</label>
             <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required></textarea>
           </div>';

          $html .= '<div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block"">Create</button>
          </div>
        </div>';
        return json_encode(['html' =>$html]);
        die;
    }

    public function createservice(Request $request)
    {

       $auth_id = auth()->user()->id;

       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
       

        $logofile = $request->file('image');
        if (isset($logofile)) {
          $new_file = $logofile;
          $path = 'uploads/services/';
          $imageName = custom_fileupload($new_file,$path);

          $data['image'] = $imageName;
        } 
          if(isset($request->defaultproduct)) {
            $data['productid'] = implode(',', $request->defaultproduct);
          } else {
             $data['productid'] = null;
          }
          //dd($data['productid']);
          $data['userid'] = $worker->userid;
          $data['workerid'] = $worker->workerid;

          $data['servicename'] = $request->servicename;
          $data['price'] = $request->price;
         // $data['productid'] = $request->defaultproduct;
          $data['type'] = $request->radiogroup;
          $data['frequency'] = $request->frequency;
          if($request->time!=null || $request->time!=0) {
            $data['time'] = $request->time.' Hours';
          }
          if($request->minute!=null || $request->minute!=0) {
            $data['minute'] = $request->minute.' Minutes';;
          }
          
          if(isset($request->serviceid)) {
              $data['serviceid'] = implode(',', $request->serviceid);
          } else {
             $data['serviceid'] = null;
          }
      $data['color'] = $request->colorcode;
      //dd($data);
      $servicedata = Service::create($data);
      return json_encode(['id' =>$servicedata->id,'servicename' =>$request->servicename]);
        die;
    }
}
