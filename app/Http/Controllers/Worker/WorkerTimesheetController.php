<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Workerhour;
use DB;

class WorkerTimesheetController extends Controller
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

        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
        //echo $worker->workerid; die; 
        // $ticketdata = DB::table('quote.*','personnel.phone','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->whereIn('quote.ticket_status',array('2','3'))->limit(3)->orderBy('quote.id','desc')->get();
        $todaydate = date('l - F d, Y');
         //$ticketdata = DB::table('quote')->select('quote.givendate', 'personnel.phone','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$worker->workerid)->where('quote.ticket_status',array('2','3'))->limit(3)->orderBy('quote.id','ASC')->get();

        $ticketdata = DB::table('quote')->where('personnelid',$worker->workerid)->where('ticket_status','2')->limit('2')->orderBy('id','DESC')->get();

        //dd($ticketdata);
        //$timesheetdata = DB::table('schedulertimesheet')->where('workerid',$worker->workerid)->where('date',$todaydate)->orderBy('id','desc')->get();

        $timesheetdata = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date',$todaydate)->orderBy('id','desc')->get();

        //$workhours = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date',$todaydate)->first();
        $workhours = DB::table('workerhour')->where('workerid',$worker->workerid)->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->first();

        return view('personnel.timesheet',compact('auth_id','ticketdata','timesheetdata','workhours'));
    }

    public function viewticketmodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $ticketid = $request->id;
       $ticketData = DB::table('quote')->select('address','time')->where('id',$ticketid)->first();
       $urlview = url('personnel/myticket/view/').'/'.$ticketid;
       $html ="";
       $html .='<p style="text-align:center;">Ticket #'.$ticketid.'</p><hr><p>Service Location<br> '.$ticketData->address.'</p><p>Default Time<br> '.$ticketData->time.'</p>';
       $html .='<input type="hidden" name="ticketid" value="'.$ticketid.'"><button type="submit" class="btn add-btn-yellow w-100 mb-4" name="pickup" value="pickup">Pickup</button><a href="'.$urlview.'" class="btn add-btn-yellow w-100 mb-4">View</a></button><a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</button>';
        return json_encode(['html' =>$html]);
        die;
    }

    public function update(Request $request)
    {
        if($request->pickup == "pickup") {
            $ticket = Quote::where('id', $request->ticketid)->get()->first();
            $ticket->ticket_status = 3;
            $ticket->save();
            $request->session()->flash('success', 'Ticket Pickup successfully');
            return redirect()->route('worker.myticket');
        } else {
            return redirect()->route('worker.myticket');
        }
    }
    

    public function view(Request $request ,$id) {
        //$quoteData = Quote::where('id',$id)->first();
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.id',$id)->first();
        return view('personnel.ticketview',compact('quoteData'));
    }

    public function vieweditinvoicemodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $quote = Quote::where('id', $request->id)->first();
       $customer = Customer::where('id', $quote->customerid)->get();
       $serviceData = Service::orderBy('id','ASC')->get();
       
       $html ='<div class="add-customer-modal">
                  <h5>Create Invoice</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="customerid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Address</label>
            <input type="text" class="form-control" placeholder="Address" name="address" id="address" value="'.$quote->address.'" readonly>
          </div>
          </div><input type="hidden" value="'.$customer[0]->email.'" name="email" id="email">

          <div class="col-md-12 mb-3">
          <label>Select Services</label>
      <div class="d-flex align-items-center">
        <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" required="" style="height:auto;">';

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
          </div><div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Price</label>
            <input type="text" class="form-control" placeholder="Price" name="price" id="price" value="'.$quote->price.'" readonly>
          </div>
          </div><div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Notes</label>
            <input type="text" class="form-control" placeholder="Notes" name="description" id="description" value="'.$quote->description.'" required>
          </div>
          </div>';
       
       $html .= '<div class="row"><div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Send Invoice</span>
          </div>
          <div class="col-lg-6">
            <button type="" class="btn btn-add btn-block">Pay Now</button>
          </div>
        </div></div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function leftbartimesheet(Request $request)
    {
      $fulldate =  $request->fulldate;
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
      //$timesheetdata = DB::table('schedulertimesheet')->where('workerid',$worker->workerid)->where('date',$fulldate)->orderBy('id','desc')->get();
      $timesheetdata = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date',$fulldate)->orderBy('id','desc')->get();

      $workhours = DB::table('workerhour')->where('workerid',$worker->workerid)->where('date',$fulldate)->orderBy('id','desc')->first();

      $json = array();
      $htmltimecontent = "";
        $html = "";
              $html .='<div class="card h-auto showtimesheet">
                  <div class="card-body">
                   <div class="card-content">
                   <h5 class="mb-4">Time Sheet Overview</h5>';
                   if(count($timesheetdata)>0) {
                   $html .='<div class="table-responsive" style="overflow-y: auto;height: 300px;">
                    <table class="table no-wrap table-new table-list" style="position: relative;">
                      <thead>
                        <tr>
                          <th>Sr. Nu.</th>
                          <th>Time In</th>
                          <th>Time Out</th>
                          <th>Hours</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $i=1;

                      foreach($timesheetdata as $key => $value) {
                        $html .='<tr>
                          <td>#'.$i.'</td>
                          <td class="blue-light">'.$value->starttime.'</td>
                          <td class="light-color">'.$value->endtime.'</td>
                          <td>'.$value->totalhours.'</td>
                        </tr>';
                        $i++;
                      }
                   
                      $html .='</tbody>
                    </table>
                    </div>';
                     } else {
                      $html .='<span style="text-align:center;">No record found.</span>';
                    }
                  $html .='</div>
                </div>
              </div>';

              $htmltimecontent .='<div class="card-content">
                <div class="show-time mb-4 text-center">
                  <h6>'.$fulldate.'</h6>
                  <h3>'.@$workhours->totalhours.'</h3>
                </div><div class="time-inout text-center row justify-content-center">
                  <div class="col-lg-4 mb-4">
                    <div class="time-in p-4">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.5 7.25a.75.75 0 00-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 00.744-1.302L12.5 12.315V7.25z" fill="currentColor"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z" fill="currentColor"></path></svg>
                      <p>Time In</p>
                    </div>
                  </div>
                  <div class="col-lg-4 mb-4">
                    <div class="time-out p-4">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.5 7.25a.75.75 0 00-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 00.744-1.302L12.5 12.315V7.25z" fill="currentColor"></path>
                      <path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z" fill="currentColor"></path></svg>
                      <p>Time Out</p>
                    </div>
                  </div>
                </div>

                <div class="row justify-content-center" id="time-in-toogle" style="display:flex;">
                  <div class="col-lg-4 mb-4">
                    <div class="text-center time-in-toogle">
                      <h3>'.@$workhours->starttime.'</h3>
                      <p>Time In </p>
                    </div>
                  </div>
                  <div class="col-lg-4 mb-4">
                    <div class="text-center time-out-toogle">
                      <h3>'.@$workhours->endtime.'</h3>
                      <p>Time Out </p>
                    </div>
                  </div>
                </div>
                <div class="row justify-content-center" id="time-out-toogle" style="display:none;">
                <div class="col-lg-4 mb-4">
                  <div class="text-center time-out-toogle">
                    <h3>'.@$workhours->endtime.'</h3>
                    <p>Time Out </p>
                  </div>
                </div>
                <div class="col-lg-4 mb-4">
                  <div class="text-center hours">
                    <h3>'.@$workhours->totalhours.'</h3>
                  </div>
                </div>
              </div>
              </div>';
      return json_encode(['html' =>$html,'htmltimecontent'=>$htmltimecontent]);
    }

    public function noteupdate(Request $request)
    {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
      $todaydate = date('l - F d, Y');
            $whours = Workerhour::where('date', $todaydate)->where('workerid', $worker->workerid)->get()->first();
            $whours->note = $request->note;
            $whours->save();
            $request->session()->flash('success', 'Note Updated successfully');
            return redirect()->route('worker.timesheet');
      }
}
