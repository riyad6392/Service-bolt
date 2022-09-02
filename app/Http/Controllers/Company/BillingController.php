<?php

namespace App\Http\Controllers\Company;

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
        ->select(DB::raw('etc as date'),'customer.customername','personnel.personnelname', DB::raw('sum(price) as totalprice'),'quote.id')
        ->join('customer', 'customer.id', '=', 'quote.customerid')
        ->join('personnel', 'personnel.id', '=', 'quote.personnelid')
        ->where('quote.userid',$auth_id)->where('quote.ticket_status',"3")
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
      $customer = Customer::where('id',$request->customerid)->first();
      $price = $request->price;
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
      return view('billing.paynow',compact('customer','price','servicename','productname')); 
    }

    public function billingview(Request $request ,$date) {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
         $sdate = strtotime($date);
         $datef = date('l - F d, Y',$sdate);
         
        $billingData = DB::table('quote')->select('quote.id','quote.serviceid','quote.price','quote.givendate','quote.etc','quote.payment_status','quote.personnelid', 'customer.customername', 'customer.email','personnel.personnelname','services.servicename')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"3")->where('quote.etc',$date)->orderBy('quote.id','desc')->get();

        $table="quote";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);
        return view('billing.index2',compact('auth_id','billingData','fields','datef','date'));
    }

    public function leftbarbillingdata(Request $request) {
      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      
      $json = array();
      if($targetid == 0) {
        $auth_id = auth()->user()->id;
        $billingData = DB::table('quote')->select('quote.id','quote.price','quote.givendate','quote.payment_mode','quote.payment_status','quote.invoiceid','quote.personnelid', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"3")->where('quote.etc',$request->date)->orderBy('quote.id','asc')->get();


        $countdata = count($billingData);
       // dd($billingData);
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
                  <button type="submit" class="btn add-btn-yellow w-100 mb-4" name="payment" value="payment" '.$style1.'>Collect Payment</button>
                  <a class="btn btn-edit w-100 p-3 mb-3">Invoice</a>
                  <a class="btn btn-dark w-100 p-3 emailinvoice" data-id="'.$billingData[$datacount]->id.'" data-email="'.$billingData[$datacount]->email.'" data-bs-toggle="modal" data-bs-target="#edit-address"><img class=" m-0 me-2" style="width:auto;" src="images/share-2.png" alt="">Email Invoice</a>
                </div>
              </div>
            </div>
          </div>
        </div>';
      } else {
        $billingData = DB::table('quote')->select('quote.id','quote.price','quote.givendate','quote.payment_status','quote.payment_mode','quote.invoiceid','quote.personnelid', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.id',$request->serviceid)->get();
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
        
        //   date_default_timezone_set('Asia/Kolkata');
        //   $currentDateTime=date('m/d/Y H:i:s');
        //   $date1=date('Y-m-d');
        //   $starttime = date('h:i A', strtotime($currentDateTime));
          $data['userid'] = $auth_id;
          $data['ticketid'] = $request->ticketid;
          $data['amount'] = $request->amount;
          $data['customername'] = $request->customername;
          $data['workerid'] = $request->personnelid;
          $data['paymentmethod'] = "Check";
          $data['status'] = "Completed";

          $id = DB::table('balancesheet')->insertGetId([
            'userid' => $auth_id,
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
      
       Mail::send('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$tdata->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givendate,'description'=>$tdata->description,'companyname'=>$company->companyname,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids], function($message) use ($contactList,$app_name,$app_email,$contactbccList,$cc) {
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
