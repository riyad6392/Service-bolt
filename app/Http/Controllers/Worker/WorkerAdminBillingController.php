<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use DB;
use Image;
use App\Models\Balancesheet;
use App\Models\Quote;
use App\Models\Managefield;
use App\Models\Service;
use App\Models\Customer;
use Mail;

class WorkerAdminBillingController extends Controller
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
        
      $totalbillingData = DB::table('quote')
        ->select(DB::raw('etc as date'), DB::raw('sum(price) as totalprice'),'id')->where('quote.workerid',$worker->workerid)->where('quote.ticket_status',"3")
        ->groupBy(DB::raw('date') )
        ->get();
        $billingData = DB::table('quote')->select('quote.id','quote.serviceid','quote.price','quote.givendate','quote.payment_status','quote.personnelid', 'customer.customername', 'customer.email','personnel.personnelname','services.servicename')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.workerid',$worker->workerid)->where('quote.ticket_status',"3")->orderBy('quote.id','desc')->get();

        $table="quote";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);

        return view('personnel.billing.index1',compact('auth_id','billingData','fields','totalbillingData'));
    }

    public function billingview(Request $request ,$date) {
        $auth_id = auth()->user()->id;

        

        if(auth()->user()->role == 'worker') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
         $sdate = strtotime($date);
         $datef = date('l - F d, Y',$sdate);
         
         $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $billingData = DB::table('quote')->select('quote.id','quote.serviceid','quote.price','quote.givendate','quote.etc','quote.payment_status','quote.personnelid', 'customer.customername', 'customer.email','personnel.personnelname','services.servicename')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.workerid',$worker->workerid)->where('quote.ticket_status',"3")->where('quote.etc',$date)->orderBy('quote.id','desc')->get();
        $table="quote";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);
        return view('personnel.billing.index2',compact('auth_id','billingData','fields','datef'));
    }

    public function leftbarbillingdata(Request $request) {
      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      $auth_id = auth()->user()->id;
        
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $json = array();
      if($targetid == 0) {


        $billingData = DB::table('quote')->select('quote.id','quote.price','quote.givendate','quote.payment_status','quote.invoiceid','quote.personnelid', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.workerid',$worker->workerid)->where('quote.ticket_status',"3")->orderBy('quote.id','asc')->get();
        $countdata = count($billingData);
         $datacount = $countdata-1;
      if($billingData[$datacount]->image!=null) {
        $imagepath = url('/').'/uploads/services/'.$billingData[$datacount]->image;
      } else {
        $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }

      if($billingData[$datacount]->invoiceid!=null) {
        $invoiceid = '#'.$billingData[$datacount]->invoiceid;
      } else {
        $invoiceid = "-";
      }

      if($billingData[$datacount]->payment_status!=null) {
          $style = "disabled";
          $pstatus1 = 'Completed'; 
      } else {
          $style = "";
           $pstatus1 = 'Pending';
      }
     
      $html ='<div>
          <div class="card">
            <div class="card-body p-4">
              <div class="product-card">
                <img src="'.$imagepath.'" alt=""/>
                <div class="product-info-list">
                  <div class="mb-4">
                    <p class="number-1">Ticket Id</p>
                    <h6 class="heading-h6">#'.$billingData[$datacount]->id.'</h6>
                    <input type="hidden" name="personnelid" id="personnelid" value="'.$billingData[$datacount]->personnelid.'">
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Invoice Id</p>
                    <h6 class="heading-h6">'.$invoiceid.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Price</p>
                    <h6 class="heading-h6">$'.$billingData[$datacount]->price.'</h6>
                  </div>
                  <input type="hidden" name="ticketid" id="ticketid" value="'.$billingData[$datacount]->id.'">
                  <input type="hidden" name="amount" id="amount" value="'.$billingData[$datacount]->price.'">
                  <input type="hidden" name="customername" id="customername" value="'.$billingData[$datacount]->customername.'">
                  <div class="mb-4">
                    <p class="number-1">Date</p>
                    <h6 class="heading-h6">'.$billingData[$datacount]->givendate.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Customer Name</p>
                    <h6 class="heading-h6">'.$billingData[$datacount]->customername.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Personnel</p>
                    <h6 class="heading-h6">'.$billingData[$datacount]->personnelname.'</h6>
                  </div><div class="mb-4">
                    <p class="number-1">Payment Status</p>
                    <h6 class="heading-h6">'.$pstatus1.'</h6>
                  </div>
                  <button type="submit" class="btn add-btn-yellow w-100 mb-4" name="payment" value="payment" '.$style.'>Collect Payment</button>
                  <a class="btn btn-edit w-100 p-3 mb-3">Invoice</a>
                  <a class="btn btn-dark w-100 p-3 emailinvoice" data-id="'.$billingData[$datacount]->id.'" data-email="'.$billingData[$datacount]->email.'" data-bs-toggle="modal" data-bs-target="#edit-address"><img class=" m-0 me-2" style="width:auto;" src="images/share-2.png" alt="">Email Invoice</a>
                </div>
              </div>
            </div>
          </div>
        </div>';
      } else {
        $billingData = DB::table('quote')->select('quote.id','quote.price','quote.givendate','quote.payment_status','quote.invoiceid','quote.personnelid', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.id',$request->serviceid)->get();
        if($billingData[0]->image!=null) {
        $imagepath = url('/').'/uploads/services/'.$billingData[0]->image;
      } else {
        $imagepath = url('/').'/uploads/servicebolt-noimage.png';
      }
      if($billingData[0]->invoiceid!=null) {
        $invoiceid = '#'.$billingData[0]->invoiceid;
      } else {
        $invoiceid = "-";
      }
      if($billingData[0]->payment_status!=null) {
          $style = "disabled";
      } else {
          $style = "";
      }

      if($billingData[0]->payment_status==null) {
          $pstatus = 'Pending';           
      } else {
          $pstatus = 'Completed';  
      }
                 
        
      $html ='<div>
          <div class="card">
            <div class="card-body p-4">
              <div class="product-card">
                <img src="'.$imagepath.'" alt=""/>
                <div class="product-info-list">
                  <div class="mb-4">
                    <p class="number-1">Ticket Id</p>
                    <h6 class="heading-h6">#'.$billingData[0]->id.'</h6>
                    <input type="hidden" name="personnelid" id="personnelid" value="'.$billingData[0]->personnelid.'">
                  </div>
                  <div class="mb-4">
                    <p class="number-1">InvoiceId</p>
                    <h6 class="heading-h6">'.$invoiceid.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Price</p>
                    <h6 class="heading-h6">$'.$billingData[0]->price.'</h6>
                  </div>
                  <input type="hidden" name="ticketid" id="ticketid" value="'.$billingData[0]->id.'">
                  <input type="hidden" name="amount" id="amount" value="'.$billingData[0]->price.'">
                  <input type="hidden" name="customername" id="customername" value="'.$billingData[0]->customername.'">
                  <div class="mb-4">
                    <p class="number-1">Date</p>
                    <h6 class="heading-h6">'.$billingData[0]->givendate.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Customer Name</p>
                    <h6 class="heading-h6">'.$billingData[0]->customername.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Personnel</p>
                    <h6 class="heading-h6">'.$billingData[0]->personnelname.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Payment Status</p>
                    <h6 class="heading-h6">'.$pstatus.'</h6>
                  </div>';

                  $html .='<button type="submit" class="btn add-btn-yellow w-100 mb-4" name="payment" value="payment" '.$style.'>Collect Payment</button>
                  <a class="btn btn-edit w-100 p-3 mb-3">Invoice</a>
                  <a class="btn btn-dark w-100 p-3 emailinvoice" data-id="'.$billingData[0]->id.'" data-email="'.$billingData[0]->email.'" data-bs-toggle="modal" data-bs-target="#edit-address"><img class=" m-0 me-2" style="width:auto;" src="images/share-2.png" alt=""> Email Invoice</a>
                </div>
              </div>
            </div>
          </div>
        </div>';
      }
      
        return json_encode(['html' =>$html]);
        die;  
    }

    public function update(Request $request)
    {
        $auth_id = auth()->user()->id;
         
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

          $data['userid'] = $worker->userid;
          $data['ticketid'] = $request->ticketid;
          $data['amount'] = $request->amount;
          $data['customername'] = $request->customername;
          $data['workerid'] = $request->personnelid;
          $data['paymentmethod'] = "Check";
          $data['status'] = "Completed";
          
        $id = DB::table('balancesheet')->insertGetId([
            'userid' => $worker->userid,
            'workerid' => $request->personnelid,
            'ticketid' => $request->ticketid,
            'amount' => $request->amount,
            'customername' => $request->customername,
            'paymentmethod' => "Check",
            'status' => "Completed"
        ]);

          //Balancesheet::create($data);
       
        $tdata = Quote::where('id', $request->ticketid)->get()->first();
        $tdata->payment_status = "Completed";
        $tdata->invoiceid = $id;
        $tdata->save();
        $request->session()->flash('success', 'Payment Completed Successfully');
        return redirect()->back();
    }

    public function savefieldbilling(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;
      if($request->checkcolumn==null) {
        DB::table('tablecolumnlist')->where('page',"companybilling")->where('userid',$auth_id)->delete();
      } else {
        DB::table('tablecolumnlist')->where('page',"companybilling")->where('userid',$auth_id)->delete();
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

    public function leftbarinvoice(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      //$customer = Customer::where('id', $request->cid)->get();
         
      $html ='<div class="add-customer-modal">
                  <h5>Email Invoice</h5>
                 </div><input type="hidden" name="ticketid" id="ticketid" value="'.$request->id.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>To</label>
                  <input type="text" class="form-control" placeholder="to" name="to" id="to" value="'.$request->email.'" required="">
                </div>
            </div>
          </div>
          <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>cc</label>
                  <input type="text" class="form-control" placeholder="cc" name="cc" id="cc" value="">
                </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">share</button>
        </div>';
        return json_encode(['html' =>$html]);
          die;
    }

    public function sendbillinginvoice(Request $request)
    {
      $tdata = Quote::where('id', $request->ticketid)->get()->first();

      $serviceidarray = explode(',', $tdata->serviceid);
      $servicedetails = Service::select('servicename')->whereIn('id', $serviceidarray)->get();
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 

      $servicename = implode(',', $sname);

      $app_name = 'ServiceBolt';
      $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');

      $contacttoemail = explode(',', $request->to);
      
      foreach($contacttoemail as $key => $contact) {
        $contactList[] = $contact;
      }
      $cc = $request->cc;
      $contactbccemail = explode(',', $request->cc);
      foreach($contactbccemail as $key => $contactbcc) {
        $contactbccList[] = $contactbcc;
      }

      $cinfo = Customer::select('customername','phonenumber','email','companyname')->where('id',$tdata->customerid)->first();
      if($cinfo->email!=null){
        $cinfoemail = $cinfo->email;
      } else {
        $cinfoemail = "";
      }
       Mail::send('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfoemail,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate], function($message) use ($contactList,$app_name,$app_email,$contactbccList,$cc) {
          $message->to($contactList);
          if($cc!=null) {
            $message->cc($contactbccList);
          }
          $message->subject('Billing Invoice!');
          $message->from($app_email,$app_name);
        });

       $request->session()->flash('success', 'Billing Invoice shared successfully');
       return redirect()->back();
    }
}
