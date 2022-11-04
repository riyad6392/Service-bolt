<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use DB;
use Image;
use Session;
use App\Models\Balancesheet;
use App\Models\Quote;
use App\Models\Managefield;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Personnel;
use App\Models\Inventory;
use App\Models\User;

use Mail;

class BillingController extends Controller
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
      
      $totalbillingData = DB::table('quote')
        ->select(DB::raw('givenstartdate as date'),'customer.customername','personnel.personnelname', DB::raw('sum(price) as totalprice'),'quote.id',DB::raw('COUNT(quote.id) as totalticket'))
        ->join('customer', 'customer.id', '=', 'quote.customerid')
        ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
        ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5'])->where('quote.givenstartdate','!=',null)
        ->groupBy(DB::raw('date') )
        ->get();
       //dd($totalbillingData);
        
        $billingData = DB::table('quote')
        ->select('quote.id','quote.serviceid','quote.price','quote.givendate','customer.customername','quote.payment_status','quote.personnelid','personnel.personnelname','services.servicename')
        ->join('customer', 'customer.id', '=', 'quote.customerid')
        ->join('services', 'services.id', '=', 'quote.serviceid')
        ->join('personnel', 'personnel.id', '=', 'quote.personnelid')
        ->where('quote.userid',$auth_id)->where('quote.ticket_status',"3")->orderBy('quote.id','desc')->get();
        //dd($billingData);
        $table="quote";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);

        $customer = Customer::where('userid',$auth_id)->orderBy('id','DESC')->get();
        $services = Service::where('userid', $auth_id)->get();
        $worker = Personnel::where('userid', $auth_id)->get();
        $productData = Inventory::where('user_id',$auth_id)->get();

        return view('billing.index1',compact('auth_id','billingData','fields','totalbillingData','customer','services','worker','productData'));
    }

    public function paynow(Request $request) {
      if( $request->ticketid) {
      Session::put('ticketid', $request->ticketid);
      }

      if( $request->amount) {
      Session::put('amount', $request->amount);
      } 

      if( $request->customername) {
      Session::put('customername',$request->customername);
      }
      $ticketID = Session::get('ticketid');
      $customer = Customer::select('id','customername')->where('id',$request->customerid)->first();
      //dd($request->all());
      //$ticketID = $request->ticketid;
      $price = Session::get('amount');
      $customername = Session::get('customername');
      $customerid = $request->customerid;
      if(isset($request->servicename)){
      $serviceidarray = $request->servicename;
      $servicedetails = Service::select('servicename')->whereIn('id', $serviceidarray)->get();
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 
      
      $servicename = implode(',', $sname);
    } else {
      $servicename = "";
    }
    if(isset($request->productname)){
      $pidarray = $request->productname;
      $pdetails = Inventory::select('productname','id')->whereIn('id', $pidarray)->get();
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
      }

      $productname = implode(',', $pname);
    } else {
      $productname = "";
    }

   
    $quoteData = DB::table('quote')->select('*')->where('id',$ticketID)->first();
    //dd($quoteData);
    if($quoteData->payment_status !=""){
      $paymentpaid = "1";
    } else {
      $paymentpaid = "0";
    }
  
    
      return view('billing.paynow',compact('ticketID','quoteData','paymentpaid','customerid','customername','customer','price','servicename','productname')); 
    }

    
    public function billingview(Request $request) {
        $date = $request->from;
        if(!isset($request->to)) {
          $todate = $date;
        } else{
          $todate = $request->to;
        }
        
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
         $sdate = strtotime($date);
         $datef = date('l - F d, Y',$sdate);
         
        $billingData = DB::table('quote')->select('quote.id','quote.serviceid','quote.price','quote.givendate','quote.etc','quote.payment_status','quote.personnelid', 'customer.customername', 'customer.email','personnel.personnelname','services.servicename')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5'])->whereBetween('quote.givenstartdate', [$date, $todate]);

        if(isset($request->pid)) {
            $pid = $request->pid;
            $billingData->where('quote.personnelid',$pid);
        }
        $billingData= $billingData->orderBy('quote.id','desc')->get();

//->where('quote.personnelid',$pid)
        $table="quote";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);

        $personnelUser = Personnel::select('*')->where('userid',$auth_id)->orderBy('id','DESC')->get();
        if(isset($request->pid)) {
          $pid = $request->pid;
        } else {
          $pid = "";
        }
        return view('billing.index2',compact('auth_id','billingData','fields','datef','date','personnelUser','pid'));
    }

    public function leftbarbillingdata(Request $request) {
      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      $date = $request->date;
      if(!isset($request->to)) {
          $todate = $date;
        } else{
          $todate = $request->to;
        }
      $json = array();

      if($targetid == 0) {
        $auth_id = auth()->user()->id;
        $billingData = DB::table('quote')->select('quote.id','quote.customerid','quote.price','quote.givendate','quote.payment_mode','quote.payment_status','quote.invoiceid','quote.personnelid','quote.ticket_status','quote.duedate', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5'])->whereBetween('quote.givenstartdate', [$date, $todate]);

        if($request->pid!="") {
            $pids = $request->pid;
            $billingData->where('quote.personnelid',$pids);
        }

        $billingData= $billingData->orderBy('quote.id','asc')->get();
        
        $countdata = count($billingData);
       // dd($billingData);
         $datacount = $countdata-1;
         $url = url('/').'/company/billing/downloadinvoice/'.@$billingData[$datacount]->id;
        $url_pay = url('/').'/company/billing/paynow/'.@$billingData[$datacount]->customerid;

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
      $ticketstatus = "";
      if($billingData[$datacount]->ticket_status==5) {
        $ticketstatus = "(Direct Ticket)";
      }

     
     
      $pstatus1 = 'Completed';  
      if($billingData[0]->payment_status==null && $billingData[0]->payment_mode==null) {
          $pstatus1 = 'Pending';           
      } 
           
      if($pstatus1 =='Completed')  {
        $style1 = "disabled";
    } else {
        $style1 = "";
    }

      $html ='<div>
          <div class="card">
            <div class="card-body p-4">
              <div class="product-card">
                <img src="'.$imagepath.'" alt=""/>
                <div class="product-info-list">
                  <div class="mb-4">
                    <p class="number-1">Ticket Id</p>
                    <h6 class="heading-h6">#'.$billingData[$datacount]->id.' '.$ticketstatus.'</h6>
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
                  <input type="hidden" name="customerid" id="customerid" value="'.$billingData[$datacount]->customerid.'">
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
                  <button type="submit" class="btn add-btn-yellow w-100 mb-4" name="payment" value="payment" '.$style1.'>Collect Payment</button>

                  <a class="btn btn-edit w-100 p-3 mb-3 viewinvoice" data-id="'.$billingData[$datacount]->id.'" data-duedate="'.$billingData[$datacount]->duedate.'" data-bs-toggle="modal" data-bs-target="#view-invoice">Invoice</a>

                  <a class="btn btn-dark w-100 p-3 emailinvoice" data-id="'.$billingData[$datacount]->id.'" data-email="'.$billingData[$datacount]->email.'" data-bs-toggle="modal" data-bs-target="#edit-address"><img class=" m-0 me-2" style="width:auto;" src="images/share-2.png" alt="">Email Invoice</a>
                </div>
              </div>
            </div>
          </div>
        </div>';
      } else {
        $billingData = DB::table('quote')->select('quote.id','quote.customerid','quote.price','quote.givendate','quote.payment_status','quote.payment_mode','quote.ticket_status','quote.invoiceid','quote.personnelid','quote.duedate', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.id',$request->serviceid)->get();
        
        if($billingData[0]->invoiceid=="") {
          $quote = Quote::where('id',$request->serviceid)->first();
          $randomid = rand(100,199);
          $quote->invoiceid = $randomid.''.$request->serviceid;
          $quote->save();
        }

        $url = url('/').'/company/billing/downloadinvoice/'.$billingData[0]->id;
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
      $ticketstatus = "";
      if($billingData[0]->ticket_status==5) {
        $ticketstatus = "(Direct Tiket)";
      }

      $pstatus = 'Completed';  
      if($billingData[0]->payment_status==null && $billingData[0]->payment_mode==null) {
          $pstatus = 'Pending';           
      } 
           
      if($pstatus =='Completed')  {
        $style = "disabled";
    } else {
        $style = "";
    }
        
      $html ='<div>
          <div class="card">
            <div class="card-body p-4">
              <div class="product-card">
                <img src="'.$imagepath.'" alt=""/>
                <div class="product-info-list">
                  <div class="mb-4">
                    <p class="number-1">Ticket Id</p>
                    <h6 class="heading-h6">#'.$billingData[0]->id.' '.$ticketstatus.'</h6>
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
                  <input type="hidden" name="customerid" id="customerid" value="'.$billingData[0]->customerid.'">
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
                  
                  $html .='<button type="submit"  class="btn add-btn-yellow w-100 mb-4" name="payment" value="payment" '.$style.'>Collect Payment</button>

                    <a class="btn btn-edit w-100 p-3 mb-3 viewinvoice" data-id="'.$billingData[0]->id.'" data-duedate="'.$billingData[0]->duedate.'" data-bs-toggle="modal" data-bs-target="#view-invoice">Invoice</a>

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
      //dd($request->all());
        $auth_id = auth()->user()->id;

          $id = DB::table('balancesheet')->insertGetId([
            'userid' => $auth_id,
            'workerid' => $request->personnelid,
            'ticketid' => $request->ticketid,
            'amount' => $request->amount,
            'customername' => $request->customername,
            'paymentmethod' => $request->method,
            'status' => "Completed"
        ]);

          //Balancesheet::create($data);
       
        $tdata = Quote::where('id', $request->ticketid)->get()->first();
        $tdata->payment_status = "Completed";
        $tdata->price =  $request->amount;
        $tdata->payment_mode = $request->method;
        $tdata->checknumber = $request->check_no;
        //$tdata->invoiceid = $id;
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

    public function leftbarviewinvoice(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      
      if($request->duedate!="") {
        $duedate =   $request->duedate;
      }  else {
        $duedate = "";
      }
      $html ='<div class="add-customer-modal">
                  <h5>Invoice</h5>
                 </div><input type="hidden" name="ticketid" id="ticketid" value="'.$request->id.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>Select Due Date</label>
                  <input type="date" class="form-control" placeholder="Due Date" name="duedate" id="duedate" value="'.$duedate.'">
                </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">Download</button>
        </div>';
        return json_encode(['html' =>$html]);
          die;
    }

    public function sendbillinginvoiceold(Request $request)
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
      
       Mail::send('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'ticketid'=>$tdata->id,'customername'=>$tdata->customername,'address'=>$tdata->address,'servicename'=>$servicename,'price'=>$tdata->price,'date'=>$tdata->givendate], function($message) use ($contactList,$app_name,$app_email,$contactbccList,$cc) {
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

    public function sendbillinginvoice(Request $request)
    {
      
      $tdata = Quote::where('id', $request->ticketid)->get()->first();

      $cinfo = Customer::select('customername','phonenumber','email','companyname')->where('id',$tdata->customerid)->first();

      $serviceid = explode(',', $tdata->serviceid);
      //dd($serviceid);
      $servicedetails = Service::select('servicename','productid')->whereIn('id', $serviceid)->get();
       
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
      } 

      $servicename = implode(',', $sname);
      $productids = explode(',', $tdata->productid);

      $pdetails = Inventory::select('productname','id')->whereIn('id', $productids)->get();
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
    
       Mail::send('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givendate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate], function($message) use ($contactList,$app_name,$app_email,$contactbccList,$cc) {
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

    public function downloadinvoice(Request $request,$id)
    {
      
      $tdata = Quote::where('id', $id)->get()->first();

      $serviceid = explode(',', $tdata->serviceid);
      //dd($serviceid);
      $servicedetails = Service::select('servicename','productid')->whereIn('id', $serviceid)->get();
       
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
      } 

      $servicename = implode(',', $sname);
      $productids = explode(',', $tdata->productid);

      $pdetails = Inventory::select('productname','id')->whereIn('id', $productids)->get();
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

      // $app_name = 'ServiceBolt';
      // $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');

      // $contacttoemail = explode(',', $request->to);
      
      // foreach($contacttoemail as $key => $contact) {
      //   $contactList[] = $contact;
      // }
      // $cc = $request->cc;
      // $contactbccemail = explode(',', $request->cc);
      // foreach($contactbccemail as $key => $contactbcc) {
      //   $contactbccList[] = $contactbcc;
      // }
        
        $cinfo = Customer::select('customername','phonenumber','email','companyname')->where('id',$tdata->customerid)->first();

         $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givendate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate]);
        // dd($pdf);
 
         return $pdf->download($id .'_invoice.pdf');
    }

    public function directpaynow(Request $request)
    {
      $customer = Customer::select('id','customername')->where('id',$request->customerid)->first();
      
      $amount =$request->price;
      $customerid = $request->customerid;
      if(isset($request->servicename)){
      $serviceidarray = $request->servicename;
      $servicedetails = Service::select('servicename')->whereIn('id', $serviceidarray)->get();
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
      } 
      
      $servicename = implode(',', $sname);
      $serviceid = implode(',', $serviceidarray);
      } else {
        $servicename = "";
        $serviceid = "";
      }

      if(isset($request->productname)){
        $pidarray = $request->productname;
        $pdetails = Inventory::select('productname','id')->whereIn('id', $pidarray)->get();
        foreach ($pdetails as $key => $value) {
          $pname[] = $value['productname'];
        }

        $productname = implode(',', $pname);
        $pid = implode(',', $pidarray);
      } else {
        $productname = "";
        $pid = "";
      }
      return view('billing.directpaynow',compact('customerid','customer','amount','servicename','productname','serviceid','pid')); 
    }


    public function directicketsave(Request $request)
    {
      
          $auth_id = auth()->user()->id;
          $etc = date('Y-m-d');

           $id = DB::table('quote')->insertGetId([
            'userid' => $auth_id,
            'customerid' => $request->customerid,
            'customername' => $request->customername,
            'price' => $request->amount,
            'radiogroup' => 'flatrate',
            'frequency' => 'One Time',
            'payment_mode' => $request->method,
            'ticket_status' => "5",
            'etc' => $etc,
            'serviceid' => $request->sid,
            'product_id' => $request->pid
          ]);

          DB::table('balancesheet')->insertGetId([
            'userid' => $auth_id,
            'ticketid' => $id,
            'amount' => $request->amount,
            'customername' => $request->customername,
            'paymentmethod' => $request->method,
            'status' => "Completed"
        ]);
        $request->session()->flash('success', 'Payment Completed Successfully');
        
        return redirect('/company/billing');
    }

    public function downloadinvoiceview(Request $request)
    {
        $tdata = Quote::where('id', $request->ticketid)->get()->first();
        $tdata->duedate = $request->duedate;
        $tdata->save();
        
        if($tdata->duedate != "") {
          $duedate = $tdata->duedate;
        } else {
          $duedate = "";
        }
        $serviceid = explode(',', $tdata->serviceid);
        $servicedetails = Service::select('servicename','productid')->whereIn('id', $serviceid)->get();
         
        foreach ($servicedetails as $key => $value) {
          $pid[] = $value['productid'];
          $sname[] = $value['servicename'];
        } 

        $servicename = implode(',', $sname);
        $productids = explode(',', $tdata->productid);

        $pdetails = Inventory::select('productname','id')->whereIn('id', $productids)->get();
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
        
        $cinfo = Customer::select('customername','phonenumber','email','companyname')->where('id',$tdata->customerid)->first();

        $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givendate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate]);
   
        return $pdf->download($tdata->id .'_invoice.pdf');
    }

    public function viewallticket(Request $request)
    {
        //dd($request->to);
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
      
      $totalbillingData = DB::table('quote')
        ->select(DB::raw('givenstartdate as date'),'customer.customername','personnel.personnelname','quote.id','quote.price','quote.payment_status','quote.invoiceid')
        ->join('customer', 'customer.id', '=', 'quote.customerid')
        ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
        ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['2','3','4','5'])->where('quote.givenstartdate','!=',null);
        if(isset($request->pid)) {
            $pid = $request->pid;
            $totalbillingData->where('quote.personnelid',$pid);
        }
        if(isset($request->from)) {
          if(!isset($request->to)) {
            $todate = $request->from;
          } else {
            $todate = $request->to;
          }
          $totalbillingData->whereBetween('quote.givenstartdate', [$request->from, $todate]);
        }
        $totalbillingData = $totalbillingData->get();

        $personnelUser = Personnel::select('*')->where('userid',$auth_id)->orderBy('id','DESC')->get();

        if(isset($request->pid)) {
          $pid = $request->pid;
        } else {
          $pid = "";
        }
        return view('billing.viewallticket',compact('auth_id','totalbillingData','personnelUser','pid'));
    }

}
