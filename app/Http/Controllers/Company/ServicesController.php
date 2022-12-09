<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Personnel;
use App\Models\Quote;
use App\Models\Managefield;
use App\Models\Tenture;
use App\Models\User;

use Mail;
use Illuminate\Support\Str;
use DB;
use Image;


class ServicesController extends Controller
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

        $days = $request->get('f');
        $dateS = Carbon::now()->subDay($days);
        $dateE = Carbon::now();
        $serviceData = Service::where('userid',$auth_id)->orderBy('id','ASC')->get();
        $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
        $table="services";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);
        $tenture = Tenture::where('status','Active')->get();
        return view('services.services',compact('auth_id','serviceData','productData','fields','tenture'));
    }

    public function create(Request $request)
    {
        $auth_id = auth()->user()->id;
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
          $data['userid'] = $auth_id;
          $data['servicename'] = $request->servicename;
          $data['price'] = $request->price;
          //$data['productid'] = $request->defaultproduct;
          $data['type'] = $request->radiogroup;
          $data['frequency'] = $request->frequency;
          if($request->time!=null || $request->time!=0) {
            $data['time'] = $request->time.' Hours';
          }
          if($request->minute!=null || $request->minute!=0) {
            $data['minute'] = $request->minute.' Minutes';;
          }
          if(isset($request->pointckbox)) {
              $data['checklist'] = implode(',', $request->pointckbox);
          }

          if(isset($request->serviceid)) {
              $data['serviceid'] = implode(',', $request->serviceid);
            } else {
               $data['serviceid'] = null;
            }
      //$color = substr(md5(rand()), 0, 6);
      //$data['color'] = "#".$color;
      $data['color'] = $request->colorcode;
      $data['description'] = $request->description;
      $serviceData = Service::create($data);
      if($request->ajax()){
        return json_encode(['id' => $serviceData->id,'time' => $serviceData->time,'minute' => $serviceData->minute,
        'frequency' => $serviceData->frequency,'price' => $serviceData->price,'servicename' =>$serviceData->servicename]);
    }
      $request->session()->flash('success', 'Service added successfully');
      
      return redirect()->route('company.services');
    }

    public function createservice(Request $request)
    {
        $auth_id = auth()->user()->id;
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
          $data['userid'] = $auth_id;
          $data['servicename'] = $request->servicename;
          $data['price'] = $request->price;
          //$data['productid'] = $request->defaultproduct;
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
      //$color = substr(md5(rand()), 0, 6);
      //$data['color'] = "#".$color;
      $data['color'] = $request->colorcode;
      $servicedata = Service::create($data);
      return json_encode(['id' =>$servicedata->id,'servicename' =>$request->servicename]);
        die;
    }

    public function viewservicemodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
       $services = Service::where('id', $request->id)->get()->toArray();
       $inventory = Inventory::where('user_id', $auth_id)->get();
       $checklistData = DB::table('checklist')->select('*')->where('serviceid',$request->id)->get();
       $tenture = Tenture::where('status','Active')->get();
       if($services[0]['image'] != null) {
        $userimage = url('uploads/services/'.$services[0]['image']);
        } else {
          $userimage = url('/').'/uploads/servicebolt-noimage.png';
        }
        if($services[0]['productid'] ==1) {
            $selected = "selected";
        } else {
            $selected = "";
        }
        if($services[0]['productid'] ==2) {
            $selected1 = "selected";
        } else {
            $selected1 = "";
        }

        if($services[0]['type'] =='perhour') {
            $checked = "checked";
        }
        if($services[0]['type'] =='recurring') {
            $checked3 = "checked";
        }
        if($services[0]['type'] =='flatrate') {
            $checked2 = "checked";
        }

        // if($services[0]->frequency =='One Time') {
        //     $selectedfo = "selected";
        // }

        // if($services[0]->frequency =='Weekly') {
        //     $selectedf = "selected";
        // }
        // if($services[0]->frequency =='Be weekly') {
        //     $selectedf1 = "selected";
        // }
        // if($services[0]->frequency =='Monthly') {
        //     $selectedf2 = "selected";
        // }
        // if($services[0]['time'] =='15 Minutes') {
        //     $selectedt1 = "selected";
        // }
        // if($services[0]->time =='30 Minutes') {
        //     $selectedt2 = "selected";
        // }
        // if($services[0]->time =='45 Minutes') {
        //     $selectedt3 = "selected";
        // }
        // if($services[0]->time =='1 Hours') {
        //     $selectedt4 = "selected";
        // }
       $time =  explode(" ", $services[0]['time']);
       $minute =  explode(" ", $services[0]['minute']); 
        // $cheklist =explode (",", $services[0]->checklist); 
        // if(in_array('point1', $cheklist)) {
        //     $checkeds = "checked";
        // }
        // if(in_array('point2', $cheklist)) {
        //     $checkeds1 = "checked";
        // }
       $html ='<div class="add-customer-modal d-flex justify-content-between align-items-center">
                  <h5>Edit Service</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="serviceid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Service Name</label>
            <input type="text" class="form-control" placeholder="Service Name" name="servicename" id="servicename" value="'.$services[0]['servicename'].'" required>
          </div>
          </div>
          <div class="col-md-12 mb-2 position-relative">
            <label>Service Default Price</label>
            <i class="fa fa-dollar" style="position: absolute;top: 40px;left: 27px;"></i>
            <input type="text" class="form-control" placeholder="Service Default Price" value="'.$services[0]['price'].'" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||  
   event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required>
          </div>
          <div class="col-md-12 mb-2">
            <label>Select Product</label>
            <select class="form-control selectpicker" name="defaultproduct[]" id="defaultproduct" multiple aria-label="Default select example" data-placeholder="Select Products" data-live-search="true">
              ';

              foreach($productData as $key => $value) {
                $productids =explode(",", $services[0]['productid']);
                if(in_array($value->id, $productids)) {
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }

                 /* if($value->id == $services[0]['productid']) {
                    $selectedp = "selected";
                  } else {
                    $selectedp = "";
                }*/
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->productname.'</option>';
              }
        $html .='</select>
          </div>
          <div class="col-md-12">
            <label>Choose Color</label><br>
            <span class="color-picker">
              <label for="colorPicker">
                <input type="color" value="'.$services[0]['color'].'" id="colorPicker1" name="colorcode" style="width:235px;">
              </label>
            </span>
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
                if(in_array($value->tenturename, $services[0])) {
                    $selectedf = "selected";
                } else {
                  $selectedf = "";
                }
              
                $html .='<option name="'.$value->tenturename.'" value="'.$value->tenturename.'" '.@$selectedf.'>'.$value->tenturename.'</option>';
              }
            $html .='</select>
          </div>
          <div class="col-md-6 mb-2">
          <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
           <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" value="'.$time[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" value="'.$minute[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false" required>
            </div>
          </div>
          <div class="col-lg-12 mb-2">
            <textarea class="form-control height-180" name="description" id="description" placeholder="Description">'.$services[0]['description'].'</textarea>
          </div>
          <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 122 * 122</div>
          <div class="col-lg-12 mb-2 relative">
               <input type="file" class="dropify" name="image" id="image" data-max-file-size="2M" data-allowed-file-extensions="jpg jpeg png gif svg bmp" accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg" data-default-file="'.$userimage.'" data-show-remove="false">
          
          </div>
          </div>';

          
          if(count($checklistData)>0) {
            $html .= '<div class="col-md-12 mb-2">
              <p class="create-gray mb-2">Create default checklist</p>
              <div class="align-items-center  d-flex services-list" style="flex-flow:wrap;white-space: pre-line;">';
                foreach($checklistData as $key => $value) {
                    $cheklist =explode (",", $services[0]['checklist']); 
                    if(in_array($value->id, $cheklist)) {
                        $checkeds = "checked";
                    }
                   else {
                    $checkeds = "";
                  }
                $html .= '<label class="container-checkbox me-3">'.$value->checklist.'
                  <input type="checkbox" name="pointckbox[]" id="pointckbox" value="'.$value->id.'" '.@$checkeds.'> <span class="checkmark"></span>
                </label>';
              }
            $html .= '</div>
            </div>';
          }

          $html .= '<div class="row"><div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block">Update</button>
          </div></div>
        </div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function update(Request $request)
    {
           
            $service = Service::where('id', $request->serviceid)->get()->first();
            $service->servicename = $request->servicename;
            $service->price = $request->price;
            //$service->productid = $request->defaultproduct;
            if(isset($request->defaultproduct)) {
             $service->productid = implode(',', $request->defaultproduct);
            } else {
              $service->productid = null;
            }
            $service->type = $request->radiogroup;
            $service->frequency = $request->frequency;
            if(isset($request->pointckbox)) {
              $service->checklist = implode(',', $request->pointckbox);
            }

            if($request->time!=null || $request->time!=0) {
                  $service->time = $request->time.' Hours';
            }
            if($request->minute!=null || $request->minute!=0) {
              $service->minute = $request->minute.' Minutes';;
            }

            $logofile = $request->file('image');
            if (isset($logofile)) {
             $new_file = $logofile;
             $path = 'uploads/services/';
             $old_file_name = $service->image;
             $imageName = custom_fileupload($new_file,$path,$old_file_name);
              $service->image = $imageName;
            }
            $service->color = $request->colorcode;
            $service->description = $request->description;
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
        $auth_id = auth()->user()->id;
        $services = Service::where('userid',$auth_id)->orderBy('id','ASC')->get();
        $countdata = count($services);
         $datacount = $countdata-1;
      if($services[$datacount]->image!=null) {
        $imagepath = url('/').'/uploads/services/'.$services[$datacount]->image;
      } else {
        $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }
      $html ='<div class="product-card targetDiv" id="div1">
              <img src="'.$imagepath.'" alt="">
              <h2>'.$services[$datacount]->servicename.'</h2>
              <div class="product-info-list">
                <div class="mb-4">
                  <p class="number-1">Price</p>
                  <h6 class="heading-h6">'.$services[$datacount]->price.'</h6>
                </div>
                <div class="mb-4">
                  <p class="number-1">Default Time</p>
                  <h6 class="heading-h6">'.$services[$datacount]->time.' '.$services[$datacount]->minute.'</h6>
                </div>
                <div class="mb-5">
                 <p class="number-1">Description</p>
                 <h6 class="heading-h6">'.Str::limit($services[$datacount]->description, 150).'</h6>
                </div>
                  <a class="btn add-btn-yellow w-100 p-3 mb-3" data-bs-toggle="modal" data-bs-target="#create-tickets" id="createtickets" data-id="'.$services[$datacount]->id.'">Create a Quote</a>
                <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-services" id="editService" data-id="'.$services[$datacount]->id.'">Edit</a>
              </div>
            </div>';
      } else {
        $services = Service::where('id', $request->serviceid)->get();
        if($services[0]->image!=null){
        $imagepath = url('/').'/uploads/services/'.$services[0]->image;
      } else {
        $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }
        
      $html ='<div class="product-card targetDiv" id="div1">
              <img src="'.$imagepath.'" alt="">
              <h2>'.$services[0]->servicename.'</h2>
              <div class="product-info-list">
                <div class="mb-4">
                  <p class="number-1">Price</p>
                  <h6 class="heading-h6">'.$services[0]->price.'</h6>
                </div>
                <div class="mb-4">
                  <p class="number-1">Default Time</p>
                  <h6 class="heading-h6">'.$services[0]->time.' '.$services[0]->minute.'</h6>
                </div> 
                <div class="mb-5">
                 <p class="number-1">Description</p>
                 <h6 class="heading-h6">'.Str::limit($services[0]->description, 150).'</h6>
                </div>
                 <a class="btn add-btn-yellow w-100 p-3 mb-3" data-bs-toggle="modal" data-bs-target="#create-tickets" id="createtickets" data-id="'.$services[0]->id.'">Create a Quote</a>
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
       if($services[0]->type =='perhour') {
            $checked = "checked";
        }
        if($services[0]->type =='recurring') {
            $checked3 = "checked";
        }
        if($services[0]->type =='flatrate') {
            $checked2 = "checked";
        }
        $html ='<div class="add-customer-modal d-flex justify-content-between align-items-center">
                <h5>Create New Quote </h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>';
               if(count($customer)>0) {
                   $cname = "";
                } else {
                   $cname = "active-focus";
         $html .='<div style="color:red;">Step: Please create customer in customer section.</div>';           
                }
                
                if(count($customer)>0) {

                } else {
            $html .='<div style="color:red;">Final Step: Then add a new quote.</div>';    
                }
        $html .='<div class="row customer-form"><div class="col-md-11 mb-2">';
        $html .='
        <input type="hidden" name="serviceid" id="serviceid" value="'.$request->id.'"><div class="input_fields_wrap">
          <div class="mb-3">
            <select class="form-select '.$cname.'" name="customerid" id="customerid_service" required>
              <option selected="" value="">Select a customer </option>';
              foreach($customer as $key => $value) {
                $html .='<option value="'.$value->id.'">'.$value->customername.'</option>';
              }
        $html .='</select>
        
			  			<a href="#"  data-bs-toggle="modal" data-bs-target="#add-customer" class="" id="hidequote"><i class="fa fa-plus new-services"></i></a>
			  		
          </div>
        </div>
      </div>
      <div class="col-md-11 mb-2">
       <div class="input_fields_wrap">
          <div class="mb-3">
            <select class="form-select" name="address" id="address_service" required>
              <option value="">Select Customer Address</option>
              </select>
              <div id="addressicon"></div>
          </div>
      </div>
    </div>
    <div class="col-md-12 mb-3">
        <input type="text" class="form-control" readonly="" name="servicename" placeholder="service One" value="'.$services[0]->servicename.'">
        <input type="hidden" class="form-control" readonly="" name="serviceid" id="serviceid" value="'.$services[0]->id.'">
      </div><div class="col-md-12 mb-3">
        <div class="align-items-center justify-content-lg-between d-flex services-list">
          <label class="container-checkbox" style="pointer-events:none;">Per hour
            <input type="radio" id="test1" name="radiogroup" value="perhour" '.@$checked.' readonly="">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox" style="pointer-events:none;">Flate rate
            <input type="radio" id="test2" name="radiogroup" value="flatrate" '.@$checked2.' readonly="">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox" style="pointer-events:none;">Recurring
            <input type="radio" id="test3" name="radiogroup" value="recurring" '.@$checked3.' readonly="">
            <span class="checkmark"></span>
          </label>
        </div>
      </div><div class="col-md-6 mb-2">
       <input type="text" class="form-control" readonly="" name="frequency" placeholder="Service Frequency" value="'.$services[0]->frequency.'">
      </div><div class="col-md-6 mb-2">
      <label>Default Service Time</label><br>
      <div class="timepicker timepicker1" style="display:inline-block;">
      <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" value="'.$services[0]->time.'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false" readonly>:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" value="'.$services[0]->minute.'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false" readonly></div>
    </div><div class="col-md-12 mb-3 position-relative">
    <i class="fa fa-dollar" style="position: absolute;top: 17px;left: 27px;"></i>
    <input type="text" class="form-control" placeholder="Price" name="price" id="price" value="'.$services[0]->price.'" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||  
   event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required>
     </div><div class="col-md-12 mb-3">
     <label style="position: relative;left: 12px;margin-bottom: 11px;">ETC</label>
      <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false;" style="position: relative;" required>
     </div><div class="col-md-12 mb-3">
      <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required></textarea>
     </div> <div class="col-lg-6 mb-3">
      <button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
    </div><div class="col-lg-6 mb-3">
      <button class="btn btn-add btn-block" type="submit" name="ticket" value="ticket">Add a Quote</button>
    </div><div class="col-lg-12">
     <button class="btn btn-dark btn-block btn-lg p-2" type="submit" name="share" value="share"><img src="images/share-2.png"  alt=""/> Share</button>
    </div></div>';
      return json_encode(['html' =>$html]);
        die;
   }

   public function createquote(Request $request)
   {

      $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

      $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', auth()->user()->id)->first();

      $servicedetails = Service::select('servicename','productid','price')->where('id', $request->serviceid)->first();

      $sum = 0;
      $txvalue = 0;
      if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
        if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
            $txvalue = $servicedetails->price *$userdetails->servicevalue/100; 
        } else { 
            $txvalue = 0;
        }
      }

      $sum = $txvalue;
      $tprice1 = $servicedetails->price;

      $productd = DB::table('products')->select('productname','price')->where('id', $servicedetails->productid)->first();

      if($productd!="") {
        $pname = $productd->productname;
      } else {
        $pname = "";
      }

      $sum1 = 0;
      $txvalue1 = 0;
      $tprice = 0;
      $productname = "";
      $productname1 = "";
      if($servicedetails->productid!="") {
        $pids = explode(',',$servicedetails->productid);
        $productdetails = Inventory::select('productname','price')->whereIn('id', $pids)->get();
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
            $tprice+= $value['price'];
        }
        $productname = implode(',', $pname);
        $productname1 = $productdetails[0]->productname;
      }

      $totlticketprice = $tprice1+$tprice;
      $totlticketprice = number_format($totlticketprice,2);
      $totlticketprice = preg_replace('/[^\d.]/', '', $totlticketprice);

      $totaltax = $sum+$sum1;
      $totaltax = number_format($totaltax,2);
      $totaltax = preg_replace('/[^\d.]/', '', $totaltax);


      $auth_id = auth()->user()->id;
      
      $data['userid'] = $auth_id;
      $data['customerid'] = $request->customerid;
      $data['serviceid'] = $request->serviceid;
      $data['customername'] =  $customer->customername;
      $data['servicename'] = $servicedetails->servicename;
      $data['product_id'] = $servicedetails->productid;
      $data['product_name'] = $productname1;
      //$data['personnelid'] = $request->personnelid;
      $data['radiogroup'] = $request->radiogroup;
      $data['frequency'] = $request->frequency;
      $data['time'] = $request->time;
      $data['minute'] = $request->minute;
      $data['price'] = $request->price;
      $data['etc'] = $request->etc;
      $data['description'] = $request->description;
      $data['address'] = $request->address;
      $data['tickettotal'] = $totlticketprice;
      $data['tax'] = $totaltax;
      
      $formattedAddr = str_replace(' ','+',$request->address);
      //Send request and receive json data by address
      $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
      $output = json_decode($geocodeFromAddr);
      //Get latitude and longitute from json data
      //print_r($output->results[0]->geometry->location->lat); die;
      if($output->results!=NULL) {
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;
      }
      else {
        $latitude  = 0; 
        $longitude = 0;
      }
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
      
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
      $subjetline ='Service Quote from ' . auth()->user()->companyname;
      Mail::send('mail_templates.sharequote', ['name'=>'service quote','address'=>$request->address, 'servicename'=>$servicedetails->servicename,'productname'=>$productname,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email,$subjetline) {
          $message->to($user_exist->email)
          ->subject($subjetline);
          //$message->from($app_email,$app_name);
      });
    }
        if($request->share =='share') {
          $request->session()->flash('success', 'Quote share successfully');
        } else {
          $request->session()->flash('success', 'Quote added successfully');
        }
      return redirect()->route('company.services');
    }

    public function savefieldservice(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;

      if($request->checkcolumn==null) {
        DB::table('tablecolumnlist')->where('page',"companyservice")->where('userid',$auth_id)->delete();
      } else {
        DB::table('tablecolumnlist')->where('page',"companyservice")->where('userid',$auth_id)->delete();
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
    
    public function createserviceticket(Request $request)
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

     
      return json_encode(['id' =>$cinfo->id,'customername' =>$cinfo->customername]);
        
     
  }

  public function create_service_address(Request $request)
    {
        $cid = $request->customerid;
        $auth_id = auth()->user()->id;
        $data['authid'] = $auth_id;
        $data['customerid'] = $cid;
        $data['address'] = $request->address;
        Address::create($data);
        
        return json_encode(['address' =>$request->address]);
    }

    
}
