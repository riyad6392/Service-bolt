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
use App\Models\User;
use App\Models\Tenture;
use DateTime;
use App\Models\Address;
use App\Models\AppNotification;

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

        $ticketData = DB::table('quote')->select('quote.*', 'customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"1")->orderBy('quote.id','ASC')->get();

        if(isset($_REQUEST['date'])) {
            $todaydate = Carbon::createFromFormat('Y-m-d', $_REQUEST['date'])->format('l - F d, Y');
        } else {
            $todaydate = date('l - F d, Y');
        }
        
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();

        $customer = Customer::where('userid',$auth_id)->orderBy('id','DESC')->get();
        $services = Service::where('userid', $auth_id)->get();
        $worker = Personnel::where('userid', $auth_id)->offset(0)->limit(6)->get();
        $workercount = Personnel::where('userid', $auth_id)->get();
        $wcount = count($workercount);
        $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
        $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
        $tenture = Tenture::where('status','Active')->get();
        return view('scheduler.index',compact('auth_id','ticketData','scheduleData','customer','services','worker','productData','wcount','userData','tenture'));
    }

    public function indexnew(Request $request)
    {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        //$ticketData = Quote::where('userid',$auth_id)->where('ticket_status',"1")->orderBy('id','ASC')->get();

        $ticketData = DB::table('quote')->select('quote.*', 'customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"1")->orderBy('quote.id','ASC')->get();
        $todaydate = date('l - F d, Y');
        $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();

        $customer = Customer::where('userid',$auth_id)->orderBy('id','DESC')->get();
        $services = Service::where('userid', $auth_id)->get();
        $worker = Personnel::where('userid', $auth_id)->offset(0)->limit(6)->get();
        $workercount = Personnel::where('userid', $auth_id)->get();
        $wcount = count($workercount);
        $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
        $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
        $tenture = Tenture::where('status','Active')->get();
        return view('scheduler.indexnew',compact('auth_id','ticketData','scheduleData','customer','services','worker','productData','wcount','userData','tenture'));
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
      
      $offset = $request->start; // start row index.
      $limit="6"; // no of records to fetch/ get .
      $newoffsetvalue = $offset+6;

      $worker = Personnel::where('userid', $auth_id)->offset($offset)->limit($limit)->get();
      
      $workercount = Personnel::where('userid', $auth_id)->get();
      $wcount = count($workercount);

      $json = array();
      $countsdata = count($scheduleData);
      $datacount = $countsdata;
      $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
      $html = "";
      $html .='<input type="hidden" id="suggest_trip_start" value="'.$newoffsetvalue.'"><div class="rightsidebox">
         <div class="wt-150 border-end d-flex align-items-center justify-content-center">
          <i class="fa fa-angle-left fa-2x" aria-hidden="true" id="suggest_trip_prev" style="cursor: pointer;"></i><span style="margin-left:14px;">'.$wcount.'</span><i class="fa fa-angle-right fa-2x ms-3" aria-hidden="true" id="suggest_trip_next" style="cursor: pointer;"></i>
        </div>';
      foreach($worker as $key => $value) {
        $detailurl = url('/').'/company/scheduler/detail/'.$value->id;
        if($value->image!=null) {
          $imageurl = url('uploads/personnel/'.$value->image);
        } else {
          $imageurl = url('uploads/servicebolt-noimage.png');
        }
        $html .='<div class="ticketbox border-end border-top" id="'.$value->id.'" class="text-center"><a style="color: #000!important;  text-decoration: none!important;font-size: 15px!important; display:block; margin-bottom:5px" href="'.$detailurl.'"> <img class="sub" src="'.$imageurl.'" style="margin:0px;border-radius: 39px;height: 70px;width: 70px;"></a><h5 style="text-align: center;font-size: 18px;color: #fff;"><a style="color: #000!important;  text-decoration: none!important;font-size: 15px!important;" href="'.$detailurl.'">'.$value->personnelname.'</a></h5></div>';
      }
      $html .='</div>';
      $html .='<div class="ticket-date bg-gray">';
      for($i=$userData->openingtime; $i<=$userData->closingtime; $i++) {
        $html .='<div class="align-content-center d-flex"><div class="date-bolt p-3 border-end">';
        $plusone = $i+1;
        $colon = ":00";
        if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
          
          $times = $i.":00";
          $html .='<div class="ev-calender-hours">'.strtoupper(date("h:i a", strtotime($times))).'</div></div><div class="connectedSortable ui-sortable sortable2 short-ticket">';
          $f= $i+1;
              $m =   ":00";
          $timev = $f.$m .' '.$ampm;
          foreach($worker as $key => $value1) {
            $html .='<div class="ui-sortable-handle border-1 radius-5 slidescroll new2w" id="'.$value1->id.'" width="143px" style="position: relative;overflow:auto;">
              <div style="display: none;">
                <span style="visibility: hidden;"><p>'.$value1->id.'</p></span>
                <span style="visibility: hidden;"><b>'.date("h:i a", strtotime($times)).'</b></span>
              </div>
               <input type="hidden" name="workerid" id="workerid" value="'.$value1->id.'"> 
              <input type="hidden" name="timev" id="timev" value="'.date("h:i a", strtotime($times)).'">';
          foreach($scheduleData as $key => $value) {
              $f= $i+1;
              $m =   ":00";
              $settimes = date("h:i a", strtotime($times));
              $servicecolor = Service::select('color')
                ->where('servicename',$value->servicename)->get()->first();
              if($value->giventime == $settimes) {
                  if($value->personnelid == $value1->id) {
                    $html .='<div style="position: relative;"><input type="hidden" id="drop_'.$value->id.'"><input type="hidden" name="customerid" id="customerid" value="'.$value->customerid.'"><input type="hidden" name="quoteid" id="quoteid_'.$value->id.'" value="'.$value->id.'"><a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="'.$value->id.'" style="color:#fff;background:'.$servicecolor->color.'"><span>#'.$value->id.'</span><h6 class="m-0">'.$value->customername.'</h6><p class="m-0">'.$value->servicename.'</p><div class="grinding" style="font-size: 14px!important;">'.$value->time.' '.$value->minute;
                      $date=date_create($value->etc);
                      $dateetc = date_format($date,"F d, Y");
                      $html.='- '.$dateetc.'</div></a><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"><i class="fa fa-trash" style="position: absolute;right: 15px;top: 2px;pointer-events: auto;color:#fff;"></i></a><a href="javascript:void(0);" class="editsticket" data-bs-toggle="modal" data-bs-target="#edit-stickets" id="editsticket" data-id="'.$value->id.'"><i class="fa fa-edit" style="position: absolute;right: 32px;top: 2px;pointer-events: auto;color:#fff;"></i></a></div>';
                $imagepath = url('/').'/uploads/customer/'.$value->image;
              }
            }
            
          }
          $html .='</div>';
        }
        $html .='</div></div>';
   
      }
      $html .='</div></div>';
      return json_encode(['html' =>$html,'countsdata'=>$countsdata]);
       
    }

    public function leftbarschedulerdataprev(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;

      $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();
      
      $offset = $request->start; // start row index.
      $offset = $offset - 6;
      $limit="6"; // no of records to fetch/ get .
      $newoffsetvalue = $offset+6;

      $worker = Personnel::where('userid', $auth_id)->offset($offset)->limit($limit)->get();
      $workercount = Personnel::where('userid', $auth_id)->get();
      $wcount = count($workercount);

      $json = array();
      $countsdata = count($scheduleData);
      $datacount = $countsdata;
      $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
      $html = "";
      $html .='<input type="hidden" id="suggest_trip_start" value="'.$newoffsetvalue.'"><div class="rightsidebox">
         <div class="wt-150 border-end d-flex align-items-center justify-content-center">
          <i class="fa fa-angle-left fa-2x" aria-hidden="true" id="suggest_trip_prev" style="cursor: pointer;"></i><span style="margin-left:14px;">'.$wcount.'</span>
    <i class="fa fa-angle-right fa-2x ms-3" aria-hidden="true" id="suggest_trip_next" style="cursor: pointer;"></i>
        </div>';
      foreach($worker as $key => $value) {
        $detailurl = url('/').'/company/scheduler/detail/'.$value->id;
        if($value->image!=null) {
          $imageurl = url('uploads/personnel/'.$value->image);
        } else {
          $imageurl = url('uploads/servicebolt-noimage.png');
        }
        $html .='<div class="ticketbox border-end border-top" id="'.$value->id.'" class="text-center"><a style="color: #000!important;  text-decoration: none!important;font-size: 15px!important; display:block; margin-bottom:5px" href="'.$detailurl.'"> <img class="sub" src="'.$imageurl.'" style="margin:0px;border-radius: 39px;height: 70px;width: 70px;"></a><h5 style="text-align: center;font-size: 18px;color: #fff;"><a style="color: #000!important;  text-decoration: none!important;font-size: 15px!important;" href="'.$detailurl.'">'.$value->personnelname.'</a></h5></div>';
      }
      $html .='</div>';
      $html .='<div class="ticket-date bg-gray">';
      for($i=$userData->openingtime; $i<=$userData->closingtime; $i++) {
        $html .='<div class="align-content-center d-flex"><div class="date-bolt p-3 border-end">';
        $plusone = $i+1;
        $colon = ":00";
        if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
        $times = $i.":00";
          $html .='<div class="ev-calender-hours">'.strtoupper(date("h:i a", strtotime($times))).'</span></div></div><div class="connectedSortable ui-sortable sortable2 short-ticket">';
          $f= $i+1;
              $m =   ":00";
          $timev = $f.$m .' '.$ampm;
          foreach($worker as $key => $value1) {
            $html .='<div class="ui-sortable-handle border-1 radius-5 slidescroll new2w" id="'.$value1->id.'" width="143px" style="position: relative;overflow:auto;">
              <div style="display: none;">
                <span style="visibility: hidden;"><p>'.$value1->id.'</p></span>
                <span style="visibility: hidden;"><b>'.date("h:i a", strtotime($times)).'</b></span>
              </div>
               <input type="hidden" name="workerid" id="workerid" value="'.$value1->id.'"> 
              <input type="hidden" name="timev" id="timev" value="'.date("h:i a", strtotime($times)).'">';
          foreach($scheduleData as $key => $value) {
              $f= $i+1;
              $m =   ":00";
              $settimes = date("h:i a", strtotime($times));
              $servicecolor = Service::select('color')
                ->where('servicename',$value->servicename)->get()->first();
              if($value->giventime == $settimes) {
                  if($value->personnelid == $value1->id) {
                    $html .='<div><input type="hidden" id="drop_'.$value->id.'"><input type="hidden" name="customerid" id="customerid" value="'.$value->customerid.'"><input type="hidden" name="quoteid" id="quoteid_'.$value->id.'" value="'.$value->id.'"><a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="'.$value->id.'" style="color:#fff;background:'.$servicecolor->color.'"><span>#'.$value->id.'</span><h6 class="m-0">'.$value->customername.'</h6><p >'.$value->servicename.'</p><div class="grinding" style="font-size: 14px!important;">'.$value->time.' '.$value->minute;
                      $date=date_create($value->etc);
                      $dateetc = date_format($date,"F d, Y");
                      $html.='- '.$dateetc.'</div></a><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"><i class="fa fa-trash" style="position: absolute;right: 30px;top: 5px;pointer-events: auto;color:#fff;"></i></a><a href="javascript:void(0);" class="editsticket" data-bs-toggle="modal" data-bs-target="#edit-stickets" id="editsticket" data-id="'.$value->id.'"><i class="fa fa-edit" style="position: absolute;right: 5px;top: 5px;pointer-events: auto;color:#fff;"></i></a></div>';
                $imagepath = url('/').'/uploads/customer/'.$value->image;
              }
            }
            
          }
          $html .='</div>';
        }
        $html .='</div></div>';
   
      }
      $html .='</div></div>';
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
        //DB::table('sethours')->where('workerid',$request->workerid)
        $auth_id = auth()->user()->id;
        $quoteid = $request->quoteid;
        $time = $request->time;

        $date = Carbon::createFromFormat('Y-m-d', $request->date)->format('l - F d, Y');


        //$date = $request->date;
        $workerid = $request->workerid;
        $tstatus = 2;
        $created_at = Carbon::now();
        DB::table('quote')->where('id','=',$quoteid)
          ->update([ 
              "ticket_status"=>"$tstatus","giventime"=>"$time","givendate"=>"$date","personnelid"=>"$workerid","created_at"=>"$created_at"
          ]);

        $appnotifiction = AppNotification::where('pid',$workerid)->where('ticketid',$quoteid)->get(); 
        //if(count($appnotifiction)==0) {

            $notification = new AppNotification;
            $notification->uid = $auth_id;
            $notification->pid = $workerid;
            $notification->ticketid = $quoteid;
            $notification->message =  "A new ticket #" .$quoteid. " has been assigned";
            $notification->save();

            $puser = Personnel::select('device_token')->where("id", $workerid)->first();

            $msgarray = array (
                'title' => 'Ticket Assign',
                'msg' => "A new ticket #" .$quoteid. " has been assigned",
                'type' => 'ticketassign',
            );

            $fcmData = array(
                'message' => $msgarray['msg'],
                'body' => $msgarray['title'],
            );

            $this->sendFirebaseNotification($puser, $msgarray, $fcmData); 
        //}

    }

    public function sortdataweekview(Request $request)
    {
        //DB::table('sethours')->where('workerid',$request->workerid)
        $auth_id = auth()->user()->id;
        $quoteid = $request->quoteid;
        $time = $request->time;
        
        //$date = Carbon::createFromFormat('Y-m-d', $request->date)->format('l - F d, Y');


        //$date = $request->date;
        $workerid = $request->workerid;
        $tstatus = 2;
        $created_at = Carbon::now();
        DB::table('quote')->where('id','=',$quoteid)
          ->update([ 
              "ticket_status"=>"$tstatus","giventime"=>"$time","givendate"=>"$request->date","personnelid"=>"$workerid","created_at"=>"$created_at"
          ]);

        $appnotifiction = AppNotification::where('pid',$workerid)->where('ticketid',$quoteid)->get(); 
        if(count($appnotifiction)==0) {

            $notification = new AppNotification;
            $notification->uid = $auth_id;
            $notification->pid = $workerid;
            $notification->ticketid = $quoteid;
            $notification->message =  "A new ticket #" .$quoteid. " has been assigned";
            $notification->save();

            $puser = Personnel::select('device_token')->where("id", $workerid)->first();

            $msgarray = array (
                'title' => 'Ticket Assign',
                'msg' => "A new ticket #" .$quoteid. " has been assigned",
                'type' => 'ticketassign',
            );

            $fcmData = array(
                'message' => $msgarray['msg'],
                'body' => $msgarray['title'],
            );

            $this->sendFirebaseNotification($puser, $msgarray, $fcmData); 
       }
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
        $data['customername'] =  $customer->customername;
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
        $data['ticket_status'] = 1;
        
        Quote::create($data);

      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
      $email = $customer->email;
      $user_exist = Customer::where('email', $email)->first();
        $name = 'service ticket';
      Mail::send('mail_templates.sharequote',
       ['address'=>$request->address,'name'=>$name, 'servicename'=>$servicename,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$request->time,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description],
        function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Service Quote from ' .  auth()->user()->companyname);
          $message->from($app_email,$app_name);
        });

          $request->session()->flash('success', 'Ticket added successfully');
            
          return redirect()->route('company.scheduler');
    }

    public function deleteTicket(Request $request)
    {
        //$ticketid = $request->targetid;
        $ticketid = $request->id;
        $tstatus = 1;

        DB::table('quote')->where('id','=',$ticketid)
          ->update([ 
              "ticket_status"=>"$tstatus"
          ]);
      echo "1";
    }

  public function view(Request $request ,$id) 
  {
    $auth_id = auth()->user()->id;
    $fulldate = date('l - F d, Y');
    $pname = DB::table('personnel')->select('personnelname','image','id')->where('id',$id)->first();
    $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$id)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->get();
    $sethour = 0;
    $setminute = 0;
    $setprice = 0;
    foreach($scheduleData as $key => $value) {
      if($value->time!=null) {
        $timearr = explode(" ",$value->time);
        $sethour+= $timearr[0];
      }
      if($value->minute!=null){
        $minutearr = explode(" ",$value->minute);
        $setminute+= $minutearr[0];
      }
      $setprice+= $value->price;
    }

    if($sethour!=0) {
      $sethour = $sethour .' Hours';
    }

    if($setminute!=0) {
      $setminute = $setminute .' Minutes';
    }


    $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
    return view('scheduler.view',compact('scheduleData','pname','userData','sethour','setminute','setprice'));
  }

  public function weekview(Request $request ,$id) 
  {
    $auth_id = auth()->user()->id;
    if(auth()->user()->role == 'company') {
        $auth_id = auth()->user()->id;
    } else {
       return redirect()->back();
    }
    //$ticketData = Quote::where('userid',$auth_id)->where('ticket_status',"1")->orderBy('id','ASC')->get();

    $ticketData = DB::table('quote')->select('quote.*', 'customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"1")->orderBy('quote.id','ASC')->get();

    if(isset($_REQUEST['date'])) {
        $todaydate = Carbon::createFromFormat('Y-m-d', $_REQUEST['date'])->format('l - F d, Y');
    } else {
        $todaydate = date('l - F d, Y');
    }
    
    $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();

    $customer = Customer::where('userid',$auth_id)->orderBy('id','DESC')->get();
    $services = Service::where('userid', $auth_id)->get();
    $worker = Personnel::where('userid', $auth_id)->offset(0)->limit(6)->get();
    $workercount = Personnel::where('userid', $auth_id)->get();
    $wcount = count($workercount);
    $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
    $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
    $tenture = Tenture::where('status','Active')->get();
    
    $allworker = Personnel::where('userid', $auth_id)->get();

    return view('scheduler.weekview',compact('auth_id','ticketData','scheduleData','customer','services','worker','productData','wcount','userData','tenture','id','allworker'));
  }

  //new calendar function
    public function weekviewall(Request $request) 
    {
    $auth_id = auth()->user()->id;
    if(auth()->user()->role == 'company') {
        $auth_id = auth()->user()->id;
    } else {
       return redirect()->back();
    }
    //$ticketData = Quote::where('userid',$auth_id)->where('ticket_status',"1")->orderBy('id','ASC')->get();

    $ticketData = DB::table('quote')->select('quote.*', 'customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"1")->orderBy('quote.id','ASC')->get();

    if(isset($_REQUEST['date'])) {
        $todaydate = Carbon::createFromFormat('Y-m-d', $_REQUEST['date'])->format('l - F d, Y');
    } else {
        $todaydate = date('l - F d, Y');
    }
    
    $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();

    $customer = Customer::where('userid',$auth_id)->orderBy('id','DESC')->get();
    $services = Service::where('userid', $auth_id)->get();
    $worker = Personnel::where('userid', $auth_id)->offset(0)->limit(6)->get();
    $workercount = Personnel::where('userid', $auth_id)->get();
    $wcount = count($workercount);
    $productData = Inventory::where('user_id',$auth_id)->orderBy('id','ASC')->get();
    $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();
    $tenture = Tenture::where('status','Active')->get();
    
    $allworker = Personnel::where('userid', $auth_id)->get();
    $id = "15";
    return view('scheduler.weekviewall',compact('auth_id','ticketData','scheduleData','customer','services','worker','productData','wcount','userData','tenture','id','allworker'));
  }
  //end

  public function personnelschedulerdata(Request $request)
  {
   $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      $personnelid = $request->id;
      $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','personnel.phone','personnel.personnelname')->join('customer', 'customer.id', '=', 'quote.customerid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$personnelid)->where('quote.ticket_status',"2")->where('quote.givendate',$fulldate)->get();

    $sethour = 0;
    $setminute = 0;
    $setprice = 0;
    foreach($scheduleData as $key => $value) {
      if($value->time!=null) {
        $timearr = explode(" ",$value->time);
        $sethour+= $timearr[0];
      }
      if($value->minute!=null){
        $minutearr = explode(" ",$value->minute);
        $setminute+= $minutearr[0];
      }
      $setprice+= $value->price;
    }

    if($sethour!=0) {
      $sethour = $sethour .' Hours';
    }

    if($setminute!=0) {
      $setminute = $setminute .' Minutes';
    }

      $userData = User::select('openingtime','closingtime')->where('id',$auth_id)->first();

      $dayarray = array("Mon"=>"MON","Tue"=>"TUE","Wed"=>"WED","Thu"=>"THU","Fri"=>"FRI","Sat"=>"SAT"); 
      //dd($scheduleData);
      $json = array();
      $countsdata = count($scheduleData);
      $datacount = $countsdata;
      $html = "";
      $html .='<div class="bg-gray"><div class="ev-calender-list"><ul class="connectedSortable ui-sortable" id="sortable2">';
      for($i=$userData->openingtime; $i<=$userData->closingtime; $i++) {
        $plusone = $i+1;
        $colon = ":00";
        $times = $i.":00";
        if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
          $html .='<li class=""><div class="ev-calender-hours">'.strtoupper(date("h:i a", strtotime($times))).'</div><ul class="d-flex w-100">';
            $f= $i+1;
            $m =   ":00";
            $timev =  $f.$m .' '.$ampm;
          foreach($dayarray as $daykey => $dayname) {
              $html .='<li class="d-inline-block ui-sortable-handle border-1 radius-5 slidescroll">';
              foreach($scheduleData as $key => $value) {
              $servicecolor = Service::select('color')
                ->where('servicename',$value->servicename)->get()->first();
              $f= $i+1;
              $m =   ":00";
              $settimes = date("h:i a", strtotime($times));
              if($value->giventime == $settimes) {
                $getdayname = substr($value->givendate, 0, 3);
                if($getdayname == $daykey) {
                  $html .='<div style="padding: 10px;
    border-radius: 10px;color:#fff;background:'.$servicecolor->color.'">
                          <span>#'.$value->id.'</span> 
                          <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="'.$value->id.'" style="color:#fff;background:'.$servicecolor->color.'"><h6 class="">'.$value->customername.'</h6></a>
                          <p>'.$value->servicename.'</p>';
                          if($value->time!=null) {
                            $html .='<span>'.$value->time.'</span>';
                          }
                          if($value->minute!=null) {
                            $html .='<p>'.$value->minute.'</p>';
                          }
                        $html .='</div>';
                }
              }
            }
          } 
          $html .='</li></ul></li>';
          }
          $html .='</ul></div></div>';
          return json_encode(['html' =>$html,'countsdata'=>$countsdata,'hour' =>$sethour,'minute' =>$setminute,'price' =>$setprice]);
    }

    public function viewaddedticketmodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $pid = Quote::select('personnelid')->where('id', $request->id)->first();
       $allworker = Personnel::where('userid', $auth_id)->where('id','!=',$pid->personnelid)->get();
       $html ='<div class="add-customer-modal d-flex justify-content-between">
                  <h6>Assign Ticket to Personnel</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>';

       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="quoteid">
          <div class="col-md-12 mb-2">
            <label class="mb-3">Select Personnel</label>
            <select class="selectpicker form-control" data-live-search="true" multiple="" data-placeholder="Select Personnel" style="width: 100%;height:auto;" tabindex="-1" aria-hidden="true" name="personnelid[]" id="personnelid" required="">';

              foreach($allworker as $key => $value) {
                $html .='<option value="'.$value->id.'">'.$value->personnelname.'</option>';
              }
        $html .='</select>
          </div>';
          
          $html .= '<div class="col-lg-6 mb-2 mt-4">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6 mt-4">
            <button type="submit" class="btn btn-add btn-block">Assign</button>
          </div>
        </div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function ticketadded(Request $request)
    {
      $quote = Quote::where('id', $request->quoteid)->first();
      foreach ($request->personnelid as $pid) {
        $data['userid'] =  $quote->userid;
        $data['customerid'] =  $quote->customerid;
        $data['customername'] =  $quote->customername;
        $data['address'] = $quote->address;
        $data['latitude'] = $quote->latitude;
        $data['longitude'] = $quote->longitude;
        $data['serviceid'] = $quote->serviceid;
        $data['servicename'] = $quote->servicename;
        $data['product_id'] = $quote->product_id;
        $data['product_name'] = $quote->product_name;
        $data['personnelid'] = $pid;
        $data['radiogroup'] = $quote->radiogroup;
        $data['frequency'] = $quote->frequency;
        $data['time'] = $quote->time;
        $data['minute'] = $quote->minute;
        $data['price'] = $quote->price;
        $data['etc'] = $quote->etc;
        $data['description'] = $quote->description;
        $data['giventime'] = $quote->giventime;
        $data['givenendtime'] = $quote->givenendtime;
        $data['givendate'] = $quote->givendate;
        $data['ticket_status'] = 2;
        Quote::create($data);
      } 
      

      $request->session()->flash('success', 'Added successfully');
      return redirect()->route('company.scheduler');
    }

    public function vieweditschedulermodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $allservices = Service::where('userid', $auth_id)->get();
       $allworker = Personnel::where('userid', $auth_id)->get();

       $quotedetails = Quote::where('id', $request->id)->get();
       $quotedetailsnew = Quote::where('id', $request->id)->get()->toArray();

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

        $time =  explode(" ", $quotedetails[0]->time);
        $minute =  explode(" ", $quotedetails[0]->minute);

        $address = Address::select('id','address')->where("customerid",$quotedetails[0]->customerid)->get();

       $html ='<div class="add-customer-modal">
                  <h5>Edit</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="quoteid">
          <div class="col-md-12 mb-2">
            <label>Customer Name</label>
            <input type="text" class="form-control" placeholder="Customer" name="customername" id="customername" value="'.$quotedetails[0]->customername.'" readonly>
          </div>
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label>Customer Address</label>
                <select class="form-select" name="address" id="address" required>';
                foreach($address as $key => $value) {
                  if($value->address == $quotedetails[0]->address) {
                    $selectecpa = "selected";
                  } else {
                    $selectecpa = "";
                }
                 $html .='<option value="'.$value->address.'" '.@$selectecpa.'>'.$value->address.'</option>';
              }
        $html .='</select>
              </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <label>Select a Service</label>
            <select class="form-control selectpicker1" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" style="height:auto;">';

              foreach($allservices as $key => $value) {
                  $serviceids =explode(",", $quotedetails[0]->serviceid);
                 if(in_array($value->id, $serviceids)) {
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }

                $html .='<option value="'.$value->id.'" '.@$selectedp.' data-hour="'.$value->time.'" data-min="'.$value->minute.'" data-price="'.$value->price.'">'.$value->servicename.'</option>';
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
            <label>Default Service Time </label><br>
            <div class="timepicker timepicker1 form-control" style="display: flex;align-items: center;">
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" value="'.$time[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" value="'.$minute[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div></div>
          <div class="col-md-12 mb-3 position-relative">
            <label>Price</label>
            <i class="fa fa-dollar" style="position: absolute;top:40px;left: 27px;"></i>
            <input type="text" class="form-control" placeholder="Price" name="price" id="price" value="'.$quotedetails[0]->price.'" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
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

    public function sticketupdate(Request $request)
    {
      //$customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

      $servicedetails = Service::select('servicename','productid')->whereIn('id', $request->serviceid)->get();
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
      } 
      $productid = implode(',', array_unique($pid));
             
      $servicename = implode(',', $sname); 
        
      // $productd = DB::table('products')->select('productname')->where('id', $servicedetails->productid)->first();  
      // if($productd!="") {
      //     $pname = $productd->productname;
      //   } else {
      //     $pname = "";
      //   }
      $quote = Quote::where('id', $request->quoteid)->get()->first();
      //$quote->customerid =  $request->customerid;



      if(isset($request->serviceid)) {
        $quote->serviceid = implode(',', $request->serviceid);
      } else {
        $quote->serviceid = null;
      }
      $quote->servicename = $servicedetails[0]->servicename;
      $quote->product_id = $productid;
      //$quote->product_name = $pname;
      $quote->radiogroup = $request->radiogroup;
      $quote->frequency = $request->frequency;
      if($request->time!=null || $request->time!=0) {
        $quote->time = $request->time.' Hours';
      } else {
        $quote->time = null;
      }
      if($request->minute!=null || $request->minute!=0) {
        $quote->minute = $request->minute.' Minutes';
      } else {
        $quote->minute = null;
      }
      $quote->address = $request->address;
      $quote->price = $request->price;
      $quote->etc = $request->etc;
      $quote->description = $request->description;
      //$quote->customername =  $customer->customername;
      $quote->address = $request->address;

      $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
      $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
      $output = json_decode($geocodeFromAddr);
      $latitude  = $output->results[0]->geometry->location->lat; 
      $longitude = $output->results[0]->geometry->location->lng;

      $quote->latitude = $latitude;
      $quote->longitude = $longitude;

      $quote->save();

      $auth_id = auth()->user()->id;
      if($quote->personnelid!="") {
        $notification = new AppNotification;
        $notification->uid = $auth_id;
        $notification->pid = $quote->personnelid;
        $notification->ticketid = $quote->id;
        $notification->message =  "Ticket #" .$quote->id. " details has been changed";
        $notification->save();

        $puser = Personnel::select('device_token')->where("id", $quote->personnelid)->first();

        $msgarray = array (
            'title' => 'Ticket Detail changed',
            'msg' => "Ticket #" .$quote->id. " details has been changed",
            'type' => 'ticketdetailchanges',
        );

        $fcmData = array(
            'message' => $msgarray['msg'],
            'body' => $msgarray['title'],
        );

        $this->sendFirebaseNotification($puser, $msgarray, $fcmData); 
      }

      $request->session()->flash('success', 'Updated successfully');
      return redirect()->back();
    }

    public function getworker(Request $request)
    {
      $auth_id = auth()->user()->id;

      $offset = $request->start; // start row index.
      $limit="6"; // no of records to fetch/ get .
      $newoffsetvalue = $offset+6;

      $worker = Personnel::where('userid', $auth_id)->offset($offset)->limit($limit)->get();
      $optiontitle1 = array();
      foreach($worker as $key => $value) {
       $assav =  $value->personnelname.'#'.$value->image;
        $pid =  $value->id;
        $name = $assav;
        $optiontitle2 = array (
            'id'=>$pid,
            'title'=>$name,
        );
        array_push($optiontitle1,$optiontitle2);
      }

      return json_encode(['resources' =>$optiontitle1]);
    }

    public function getworkerweekview(Request $request)
    {
      $auth_id = auth()->user()->id;

      $offset = $request->start; // start row index.
      $limit="6"; // no of records to fetch/ get .
      $newoffsetvalue = $offset+6;

      $worker = Personnel::where('userid', $auth_id)->where('id',$request->workerid)->get();
      $optiontitle1 = array();
      foreach($worker as $key => $value) {
       $assav =  $value->personnelname.'#'.$value->image;
        $pid =  $value->id;
        $name = $assav;
        $optiontitle2 = array (
            'id'=>$pid,
            'title'=>$name,
        );
        array_push($optiontitle1,$optiontitle2);
      }

      return json_encode(['resources' =>$optiontitle1]);
    }

    public function getschedulerdata(Request $request,$date)
    {
        $auth_id = auth()->user()->id;
        $todaydate = date('l - F d, Y');
        $newdate = Carbon::createFromFormat('l - F d, Y', $todaydate)->format('Y-m-d');

        $fulldate = Carbon::createFromFormat('Y-m-d', $date)->format('l - F d, Y');

        
        $scheduleData = DB::table('quote')->select('quote.*','personnel.phone','personnel.personnelname','services.color')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.servicename', '=', 'quote.servicename')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',[2,3,4])->where('quote.givendate',$fulldate)->orderBy('quote.id','ASC')->get();
        
        $data=[];
        foreach ($scheduleData as $key => $row) {
            $newTime = date('H:i', strtotime($row->giventime));
            $startdatetime = $newdate.' '.$newTime;
            if($row->givenendtime!=null) {  
                $newTime = date('H:i', strtotime($row->givenendtime));
                $enddatetime = $newdate.' '.$newTime;
            } else {
                $enddatetime = "";
            }

            $data[] = array (
                'id'=>$row->id,
                'title'   =>'#'.$row->id."\n".$row->customername."\n".$row->servicename,
                'start'   => $startdatetime,
                'end' => $enddatetime,
                'resourceId'=>$row->personnelid,
                'backgroundColor'   => $row->color,
            );
        }
      echo json_encode($data);
    }

    public function getschedulerdataweekview(Request $request)
    {
        $auth_id = auth()->user()->id;
        //->where('personnel.id','15')
        $scheduleData = DB::table('quote')->select('quote.*','personnel.phone','personnel.personnelname','services.color')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.servicename', '=', 'quote.servicename')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',[2,3,4])->orderBy('quote.id','ASC')->get();
        
        $data=[];
        foreach ($scheduleData as $key => $row) {
            
            $newdate = Carbon::createFromFormat('l - F d, Y', $row->givendate)->format('Y-m-d');
            $newTime = date('H:i', strtotime($row->giventime));
            $startdatetime = $newdate.' '.$newTime;
            if($row->givenendtime!=null) {  
                $newTime = date('H:i', strtotime($row->givenendtime));
                $enddatetime = $newdate.' '.$newTime;
            } else {
                $enddatetime = "";
            }

            $data[] = array (
                'id'=>$row->id,
                'title'   =>'#'.$row->id."\n".$row->customername."\n".$row->servicename,
                'start'   => $startdatetime,
                'end' => $enddatetime,
                'resourceId'=>$row->personnelid,
                'backgroundColor'   => $row->color,
            );
        }
      echo json_encode($data);
    }

    public function updatesortdata(Request $request)
    {
        $auth_id = auth()->user()->id;
        $quoteid = $request->quoteid;
        $time = $request->time;
        $endtime = $request->endtime;

        $datetime1 = new DateTime($time);
        $datetime2 = new DateTime($endtime);
        $interval = $datetime1->diff($datetime2);
        $hours = $interval->format('%h Hours');
        $minutes = $interval->format('%i Minutes');

        DB::table('quote')->where('id','=',$quoteid)
          ->update([ 
              "giventime"=>"$time","givenendtime"=>"$request->endtime","time"=>"$hours","minute"=>"$minutes"
          ]);
        $workerid = $request->workerid;
        $appnotifiction = AppNotification::where('pid',$workerid)->where('ticketid',$quoteid)->get(); 

            $notification = new AppNotification;
            $notification->uid = $auth_id;
            $notification->pid = $workerid;
            $notification->ticketid = $quoteid;
            $notification->message =  "Ticket #" .$quoteid. " has been changed";
            $notification->save();

            $puser = Personnel::select('device_token')->where("id", $workerid)->first();

            $msgarray = array (
                'title' => 'Ticket Changed',
                'msg' => "Ticket #" .$quoteid. " has been changed",
                'type' => 'ticketchanged',
            );

            $fcmData = array(
                'message' => $msgarray['msg'],
                'body' => $msgarray['title'],
            );

            $this->sendFirebaseNotification($puser, $msgarray, $fcmData); 
    }

    public function sendFirebaseNotification($puser, $msgarray, $fcmData) 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fcmApiKey = "AAAARQKM8JU:APA91bGX3j2-L9qPoU7PhhTrxIZzjUDDUa8XFkyMsyYHUVr8uqC5yHofDtQR73vlmUarnbQDAexn2TQVRGlWhf99gVkD8UcCvSIX_1DcqX5ZdLy8xu3JOfAMgJmN3Zl6NZ-H3WBKXJDl";

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
            'to' => $puser->device_token,
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
