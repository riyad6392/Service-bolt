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
      DB::enableQueryLog();
      // $totalbillingData = DB::table('quote')
      //   ->select(DB::raw('givenstartdate as date'),'customer.customername','personnel.personnelname', DB::raw('sum(price) as totalprice'),DB::raw('sum(tickettotal) as tickettotalprice'),'quote.id',DB::raw('COUNT(quote.id) as totalticket'))
      //   ->join('customer', 'customer.id', '=', 'quote.customerid')
      //   ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
      //   ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5','4'])->where('quote.givenstartdate','!=',null)->orderBy('quote.id','desc')
      //   ->groupBy(DB::raw('date') )
      //   ->get();

       //dd(DB::getQueryLog());

        $totalbillingData = DB::table('quote')
        ->select(DB::raw('givenstartdate as date'),'quote.id','quote.updated_at','customer.customername','personnel.personnelname', DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN price END) as totalprice'),DB::raw('SUM(CASE WHEN quote.personnelid = quote.primaryname THEN tickettotal END) as tickettotalprice'),DB::raw('COUNT(CASE WHEN quote.personnelid = quote.primaryname THEN quote.id END) as totalticket'))
        ->join('customer', 'customer.id', '=', 'quote.customerid')
        ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
        ->where('quote.userid',$auth_id)->whereIn('quote.ticket_status',['3','5','4'])->where('quote.givenstartdate','!=',null)
        ->groupBy(DB::raw('date'))
        ->get();
        //dd($totalbillingData);
       //dd(DB::getQueryLog());
        
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
      if($request->personnelid) {
        Session::put('personnelid',$request->personnelid);
      }
      $ticketID = Session::get('ticketid');
      $personnelid = Session::get('personnelid');

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
    if($quoteData->parentid!=0) {
      $ticketIDnumber = $quoteData->parentid;
    } else {
      $ticketIDnumber = $ticketID;
    }
    //dd($quoteData);
    if($quoteData->payment_status !=""){
      $paymentpaid = "1";
    } else {
      $paymentpaid = "0";
    }
    $tax = 0;
    if($quoteData->tax!="") {
      $tax = $quoteData->tax;
    }
  
    
      return view('billing.paynow',compact('ticketID','quoteData','paymentpaid','customerid','customername','customer','price','servicename','productname','tax','ticketIDnumber','personnelid')); 
    }

    
    public function billingview(Request $request) {
        $date = $request->from;
        if(!isset($request->to)) {
          $todate = $date;
        } else {
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
         DB::enableQueryLog();
        $billingData = DB::table('quote')->select('quote.id','quote.parentid','quote.serviceid','quote.product_id','quote.price','quote.tickettotal','quote.givendate','quote.etc','quote.payment_status','quote.personnelid','quote.primaryname','quote.tax', 'customer.customername', 'customer.email','personnel.personnelname','services.servicename')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereColumn('quote.personnelid','quote.primaryname')->whereIn('quote.ticket_status',['3','5','4'])->whereBetween('quote.givenstartdate', [$date, $todate]);

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
        $billingData = DB::table('quote')->select('quote.id','quote.parentid','quote.customerid','quote.price','quote.tickettotal','quote.givendate','quote.payment_mode','quote.payment_status','quote.invoiceid','quote.invoicenote','quote.personnelid','quote.primaryname','quote.ticket_status','quote.duedate','quote.tax', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',$auth_id)->whereColumn('quote.personnelid','quote.primaryname')->whereIn('quote.ticket_status',['3','4','5'])->whereBetween('quote.givenstartdate', [$date, $todate]);



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

      $ids = $billingData[$datacount]->id;
      if(!empty($billingData[$datacount]->parentid))
      {
          $ids=$billingData[$datacount]->parentid;

      }

      $billingdatapinfo = Quote::select('personnelid')->where('id',$ids)->orWhere('parentid',$ids)->get()->pluck('personnelid')->toArray();
            $pinfo =  Personnel::select(DB::raw("(GROUP_CONCAT(personnel.personnelname SEPARATOR ',')) as `personnelname`"))->whereIn('personnel.id',$billingdatapinfo)->get();
            $pname = $pinfo[0]->personnelname;
            
      if($billingData[$datacount]->invoiceid!=null) {
        $invoiceid = '#'.$billingData[$datacount]->invoiceid;
      } else {
        $invoiceid = "-";
      }
      $ticketstatus = "";
      
      if($billingData[$datacount]->ticket_status==5) {
        $ticketstatus = "(Direct Ticket)";
      }

      if($billingData[$datacount]->payment_status==null && $billingData[$datacount]->payment_mode==null) {
        $pstatus1 = 'Pending';           
      } else {
        $pstatus1 = 'Completed'; 
      }
           
      if($pstatus1 =='Completed')  {
        $style1 = "disabled";
      } else {
          $style1 = "";
      }

    if($billingData[$datacount]->tickettotal==null || $billingData[$datacount]->tickettotal=="" || $billingData[$datacount]->tickettotal == "0") {
        $newprice = $billingData[$datacount]->price;
    } else {
        $newprice = $billingData[$datacount]->tickettotal;
    }
    $taxtotal = 0;
    if($billingData[$datacount]->tax!="") {
      $taxtotal = $billingData[$datacount]->tax;
    }

      $html ='<div>
          <div class="card">
            <div class="card-body p-4">
              <div class="product-card">
                <img src="'.$imagepath.'" alt=""/>
                <div class="product-info-list">
                  <div class="mb-4">
                    <p class="number-1">Ticket Id</p>
                    <h6 class="heading-h6">#'.$ids.' '.$ticketstatus.'</h6>
                    <input type="hidden" name="personnelid" id="personnelid" value="'.$billingData[$datacount]->personnelid.'">
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Invoice Id</p>
                    <h6 class="heading-h6">'.$invoiceid.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Billing Price</p>
                    <h6 class="heading-h6">$'.$billingData[$datacount]->price.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Tax</p>
                    <h6 class="heading-h6">$'.$taxtotal.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Ticket Total Price</p>
                    <h6 class="heading-h6">$'.$newprice.'</h6>
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
                    <h6 class="heading-h6">'.$pname.'</h6>
                  </div><div class="mb-4">
                    <p class="number-1">Payment Status</p>
                    <h6 class="heading-h6">'.$pstatus1.'</h6>
                  </div>
                  <button type="submit" class="btn add-btn-yellow w-100 mb-4" name="payment" value="payment" '.$style1.'>Collect Payment</button>

                  <a class="btn btn-edit w-100 p-3 mb-3 viewinvoice" data-id="'.$billingData[$datacount]->id.'" data-duedate="'.$billingData[$datacount]->duedate.'" data-invoicenote="'.$billingData[$datacount]->invoicenote.'"data-bs-toggle="modal" data-bs-target="#view-invoice">Invoice</a>

                  <a class="btn btn-dark w-100 p-3 emailinvoice" data-id="'.$billingData[$datacount]->id.'" data-email="'.$billingData[$datacount]->email.'" data-bs-toggle="modal" data-bs-target="#edit-address"><img class=" m-0 me-2" style="width:auto;" src="images/share-2.png" alt="">Email Invoice</a>
                </div>
              </div>
            </div>
          </div>
        </div>';
      } else {
        $billingData = DB::table('quote')->select('quote.id','quote.parentid','quote.customerid','quote.price','quote.tickettotal','quote.givendate','quote.payment_status','quote.payment_mode','quote.ticket_status','quote.invoiceid','quote.personnelid','quote.duedate','quote.invoicenote','quote.tax','quote.primaryname', 'customer.customername','customer.email','personnel.personnelname','services.servicename','services.image')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.id',$request->serviceid)->get();

        $ids = $billingData[0]->id;
        if(!empty($billingData[0]->parentid))
        {
          $ids=$billingData[0]->parentid;
        }

        $billingdatapinfo = Quote::select('personnelid')->where('id',$ids)->orWhere('parentid',$ids)->get()->pluck('personnelid')->toArray();
            $pinfo =  Personnel::select(DB::raw("(GROUP_CONCAT(personnel.personnelname SEPARATOR ',')) as `personnelname`"))->whereIn('personnel.id',$billingdatapinfo)->get();
            $pname = $pinfo[0]->personnelname;
        if($billingData[0]->tickettotal==null || $billingData[0]->tickettotal=="" || $billingData[0]->tickettotal == "0") {
            $newprice = $billingData[0]->price;
        } else {
            $newprice = $billingData[0]->tickettotal;
        }
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

      
      if($billingData[0]->payment_status==null && $billingData[0]->payment_mode==null) {
          $pstatus = 'Pending';           
      } else {
        $pstatus = 'Completed';  
      }
           
      if($pstatus =='Completed')  {
        $style = "disabled";
    } else {
        $style = "";
    }
    $taxtotal = 0;
    if($billingData[0]->tax!="") {
      $taxtotal = $billingData[0]->tax;
    }

      $ids = $billingData[0]->id;
      if(!empty($billingData[0]->parentid))
      {
        $ids=$billingData[0]->parentid;
      }
      
      $html ='<div>
          <div class="card">
            <div class="card-body p-4">
              <div class="product-card">
                <img src="'.$imagepath.'" alt=""/>
                <div class="product-info-list">
                  <div class="mb-4">
                    <p class="number-1">Ticket Id</p>
                    <h6 class="heading-h6">#'.$ids.' '.$ticketstatus.'</h6>
                    <input type="hidden" name="personnelid" id="personnelid" value="'.$billingData[0]->personnelid.'">
                  </div>
                  <div class="mb-4">
                    <p class="number-1">InvoiceId</p>
                    <h6 class="heading-h6">'.$invoiceid.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Billing Price</p>
                    <h6 class="heading-h6">$'.$billingData[0]->price.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Tax</p>
                    <h6 class="heading-h6">$'.$taxtotal.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Ticket Total Price</p>
                    <h6 class="heading-h6">$'.$newprice.'</h6>
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
                    <h6 class="heading-h6">'.$pname.'</h6>
                  </div>
                  <div class="mb-4">
                    <p class="number-1">Payment Status</p>
                    <h6 class="heading-h6">'.$pstatus.'</h6>
                  </div>';
                  
                  $html .='<button type="submit"  class="btn add-btn-yellow w-100 mb-4" name="payment" value="payment" '.$style.'>Collect Payment</button>

                    <a class="btn btn-edit w-100 p-3 mb-3 viewinvoice" data-id="'.$billingData[0]->id.'" data-duedate="'.$billingData[0]->duedate.'" data-invoicenote="'.$billingData[0]->invoicenote.'" data-bs-toggle="modal" data-bs-target="#view-invoice">Invoice</a>

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
      $cinfo = Customer::select('id','customername','phonenumber','email','companyname','billingaddress')->where('id',$request->customerid)->first();
      $url = 'https://x1.cardknox.com/gatewayjson';
      if($request->method == "Credit Card") {
        if($auth_id == "68") {
            $data = array(
              'xCardNum' => $request->card_number,
              'xExp' => $request->expiration_date,
              'xKey' => 'serviceboltdev63cf6781c560436fa9f052cafa45a5d',
              'xVersion' => '4.5.9',
              "xSoftwareName" => 'ServiceBolt',
              'xSoftwareVersion' => '1.0.0',
              "xCommand"=>'cc:sale',
              "xAmount"=>$request->amount,
              "xCVV" =>$request->cvv,
              "xBillFirstName"=>$cinfo->customername,
              "xBillCompany"=>$cinfo->companyname,
              "xBillStreet"=>$cinfo->billingaddress,
              "xBillPhone"=>$cinfo->phonenumber,
              "xEmail"=>$cinfo->email,
              "xCurrency"=> "USD"
          );

          $headers = array(
            'Content-Type: application/json',
          );

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://x1.cardknox.com/gatewayjson');
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          $result = curl_exec($ch);
          curl_close($ch);
          $finalresult = json_decode($result);
         
          if($finalresult->xStatus == "Approved") {
            $id = DB::table('balancesheet')->insertGetId([
              'userid' => $auth_id,
              'workerid' => $request->personnelid,
              'ticketid' => $request->ticketIDnumber,
              'amount' => $request->amount,
              'customername' => $request->customername,
              'paymentmethod' => $request->method,
              'status' => "Completed"
            ]);
            DB::table('quote')->where('id','=',$request->ticketIDnumber)->orWhere('parentid','=',$request->ticketIDnumber)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->amount","payment_amount"=>"$request->amount","payment_mode"=>"$request->method","checknumber"=>"$request->check_no","card_number"=>"$request->card_number","expiration_date"=>"$request->expiration_date","cvv"=>"$request->cvv"
          ]);
          $request->session()->flash('success', 'Payment Completed Successfully');
            return redirect()->back();
          } else {
            $request->session()->flash('error', $finalresult->xError);
            return redirect()->back();
          } 
        } else {
          //   $data = array(
          //     'xCardNum' => $request->card_number,
          //     'xExp' => $request->expiration_date,
          //     'xKey' => 'serviceboltdev63cf6781c560436fa9f052cafa45a5d',
          //     'xVersion' => '4.5.9',
          //     "xSoftwareName" => 'ServiceBolt',
          //     'xSoftwareVersion' => '1.0.0',
          //     "xCommand"=>'cc:sale',
          //     "xAmount"=>$request->amount,
          //     "xCVV" =>$request->cvv
          // );

          // $headers = array(
          //   'Content-Type: application/json',
          // );

          // $ch = curl_init();
          // curl_setopt($ch, CURLOPT_URL, 'https://x1.cardknox.com/gatewayjson');
          // curl_setopt($ch, CURLOPT_POST, true);
          // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          // $result = curl_exec($ch);
          // curl_close($ch);
          // $finalresult = json_decode($result);
         
          // if($finalresult->xStatus == "Approved") {
            $id = DB::table('balancesheet')->insertGetId([
              'userid' => $auth_id,
              'workerid' => $request->personnelid,
              'ticketid' => $request->ticketIDnumber,
              'amount' => $request->amount,
              'customername' => $request->customername,
              'paymentmethod' => $request->method,
              'status' => "Completed"
            ]);
            DB::table('quote')->where('id','=',$request->ticketIDnumber)->orWhere('parentid','=',$request->ticketIDnumber)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->amount","payment_amount"=>"$request->amount","payment_mode"=>"$request->method","checknumber"=>"$request->check_no","card_number"=>"$request->card_number","expiration_date"=>"$request->expiration_date","cvv"=>"$request->cvv"
          ]);
          $request->session()->flash('success', 'Payment Completed Successfully');
            return redirect()->back();
          // } else {
          //   $request->session()->flash('error', $finalresult->xError);
          //   return redirect()->back();
          // }  
        }
          
      }

      if($request->method == "Check") {
        if($auth_id == "68") {
          $data = array(
          'xKey' => 'serviceboltdev63cf6781c560436fa9f052cafa45a5d',
          'xVersion' => '4.5.9',
          "xSoftwareName" => 'ServiceBolt',
          'xSoftwareVersion' => '1.0.0',
          "xCommand"=>'check:sale',
          "xAmount"=>$request->amount,
          "xAccount" =>$request->check_no,
          "xAccountType" =>'Checking',
          "xCurrency" =>'USD',
          "xBillFirstName"=>$cinfo->customername,
          "xBillCompany"=>$cinfo->companyname,
          "xBillStreet"=>$cinfo->billingaddress,
          "xBillPhone"=>$cinfo->phonenumber,
          "xEmail"=>$cinfo->email
        );

          $headers = array(
            'Content-Type: application/json',
          );

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://x1.cardknox.com/gatewayjson');
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          $result = curl_exec($ch);
          curl_close($ch);
          $finalresult = json_decode($result);
         
          if($finalresult->xStatus == "Approved") {
            $id = DB::table('balancesheet')->insertGetId([
              'userid' => $auth_id,
              'workerid' => $request->personnelid,
              'ticketid' => $request->ticketIDnumber,
              'amount' => $request->amount,
              'customername' => $request->customername,
              'paymentmethod' => $request->method,
              'status' => "Completed"
            ]);

            DB::table('quote')->where('id','=',$request->ticketIDnumber)->orWhere('parentid','=',$request->ticketIDnumber)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->amount","payment_amount"=>"$request->amount","payment_mode"=>"$request->method","checknumber"=>"$request->check_no","card_number"=>"$request->card_number","expiration_date"=>"$request->expiration_date","cvv"=>"$request->cvv"
          ]);

           $request->session()->flash('success', 'Payment Completed Successfully');
            return redirect()->back();
          } else {
            $request->session()->flash('error', $finalresult->xError);
            return redirect()->back();
          }  
        } else {
          // $data = array(
        //   'xKey' => 'serviceboltdev63cf6781c560436fa9f052cafa45a5d',
        //   'xVersion' => '4.5.9',
        //   "xSoftwareName" => 'ServiceBolt',
        //   'xSoftwareVersion' => '1.0.0',
        //   "xCommand"=>'check:sale',
        //   "xAmount"=>$request->amount,
        //   "xCustom01" =>$request->customername,
        //   "xAccount" =>$request->check_no,
        //   "xAccountType" =>'Checking',
        //   "xCurrency" =>'USD'
        // );

        //   $headers = array(
        //     'Content-Type: application/json',
        //   );

        //   $ch = curl_init();
        //   curl_setopt($ch, CURLOPT_URL, 'https://x1.cardknox.com/gatewayjson');
        //   curl_setopt($ch, CURLOPT_POST, true);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        //   $result = curl_exec($ch);
        //   curl_close($ch);
        //   $finalresult = json_decode($result);
         
        //   if($finalresult->xStatus == "Approved") {
            $id = DB::table('balancesheet')->insertGetId([
              'userid' => $auth_id,
              'workerid' => $request->personnelid,
              'ticketid' => $request->ticketIDnumber,
              'amount' => $request->amount,
              'customername' => $request->customername,
              'paymentmethod' => $request->method,
              'status' => "Completed"
            ]);

            DB::table('quote')->where('id','=',$request->ticketIDnumber)->orWhere('parentid','=',$request->ticketIDnumber)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->amount","payment_amount"=>"$request->amount","payment_mode"=>"$request->method","checknumber"=>"$request->check_no","card_number"=>"$request->card_number","expiration_date"=>"$request->expiration_date","cvv"=>"$request->cvv"
          ]);

           $request->session()->flash('success', 'Payment Completed Successfully');
            return redirect()->back();
          // } else {
          //   $request->session()->flash('error', $finalresult->xError);
          //   return redirect()->back();
          // }  
        }
        
      }

      if($request->method == "Cash") {
        $id = DB::table('balancesheet')->insertGetId([
              'userid' => $auth_id,
              'workerid' => $request->personnelid,
              'ticketid' => $request->ticketIDnumber,
              'amount' => $request->amount,
              'customername' => $request->customername,
              'paymentmethod' => $request->method,
              'status' => "Completed"
            ]);

          DB::table('quote')->where('id','=',$request->ticketIDnumber)->orWhere('parentid','=',$request->ticketIDnumber)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->amount","payment_amount"=>"$request->amount","payment_mode"=>"$request->method"
          ]);

            $request->session()->flash('success', 'Payment Completed Successfully');
            return redirect()->back();
      }
      
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

    public function leftbarinvoiceemail(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
         
      $html ='<div class="add-customer-modal">
                  <h5>Email Invoice</h5>
                 </div><input type="hidden" name="ticketid" id="ticketid" value="'.$request->id.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>To</label>
                  <input type="email" class="form-control" placeholder="to" name="to" id="to" value="'.$request->email.'" required="">
                </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">Send</button>
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

      if($request->invoicenote!="") {
        $invoicenote =   $request->invoicenote;
      }  else {
        $invoicenote = "";
      }

      if($request->invoicenote!="") {
        $invoicenote =   $request->invoicenote;
      }  else {
        $invoicenote = "";
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
          <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                  <label>Invoice Notes</label>
                  <textarea class="form-control height-110" placeholder="Invoice Note" name="description" id="description">'.$invoicenote.'</textarea>
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
      
       Mail::send('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'ticketid'=>$tdata->id,'customername'=>$tdata->customername,'address'=>$tdata->address,'servicename'=>$servicename,'price'=>$tdata->price,'date'=>$tdata->givenstartdate], function($message) use ($contactList,$app_name,$app_email,$contactbccList,$cc) {
          $message->to($contactList);
          if($cc!=null) {
            $message->cc($contactbccList);
          }
          $message->subject('Billing Invoice!');
          //$message->from($app_email,$app_name);
        });

       $request->session()->flash('success', 'Billing Invoice shared successfully');
       return redirect()->back();
    }

    public function sendbillinginvoice(Request $request)
    {
      
      $tdata = Quote::where('id', $request->ticketid)->get()->first();
      $tdata->invoiced = 1;
      $tdata->save();
      
      $cinfo = Customer::select('customername','phonenumber','email','companyname')->where('id',$tdata->customerid)->first();

      $serviceid = explode(',', $tdata->serviceid);
      //dd($serviceid);
      $servicedetails = Service::select('servicename','productid')->whereIn('id', $serviceid)->get();
       
      foreach ($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
      } 

      $servicename = implode(',', $sname);
      $productids = explode(',', $tdata->product_id);

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
      $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'invoicenote'=>$tdata->customernotes,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate,'payment_mode'=>$tdata->payment_mode]);

       Mail::send('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'invoicenote'=>$tdata->customernotes,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate,'payment_mode'=>$tdata->payment_mode], function($message) use ($contactList,$app_name,$app_email,$contactbccList,$cc,$pdf) {
        
          $message->to($contactList);
          if($cc!=null) {
            $message->cc($contactbccList);
          }
          $message->subject('Billing Invoice!');
          //$message->from($app_email,$app_name);
          $message->attachData($pdf->output(), "invoice.pdf");
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

         $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'invoicenote'=>$tdata->customernotes,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate]);
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
      $ticketprice = $request->ticketprice;
      return view('billing.directpaynow',compact('customerid','customer','amount','servicename','productname','serviceid','pid','ticketprice')); 
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
            'tickettotal' => $request->ticketprice,
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
        $tdata->invoicenote = $request->description;
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
        $productids = explode(',', $tdata->product_id);

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
        
        $cinfo = Customer::select('customername','phonenumber','email','companyname','billingaddress')->where('id',$tdata->customerid)->first();

        $pdf = PDF::loadView('mail_templates.sendbillinginvoice', ['invoiceId'=>$tdata->invoiceid,'address'=>$tdata->address,'billingaddress'=>$cinfo->billingaddress,'ticketid'=>$tdata->id,'customername'=>$cinfo->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$tdata->price,'time'=>$tdata->giventime,'date'=>$tdata->givenstartdate,'description'=>$tdata->description,'invoicenote'=>$tdata->customernotes,'companyname'=>$cinfo->companyname,'phone'=>$cinfo->phonenumber,'email'=>$cinfo->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productids,'duedate'=>$tdata->duedate,'payment_mode'=>$tdata->payment_mode]);

        
        return $pdf->download($tdata->id .'_invoice.pdf');
    }

    public function viewallticket(Request $request)
    {
        
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
      
      $totalbillingData = DB::table('quote')
        ->select(DB::raw('givenstartdate as date'),'customer.customername','personnel.personnelname','quote.id','quote.price','quote.payment_status','quote.payment_mode','quote.invoiceid','quote.invoiced','quote.duedate','quote.invoicenote','quote.personnelid','quote.primaryname','quote.parentid','quote.address')
        ->join('customer', 'customer.id', '=', 'quote.customerid')
        ->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')
        ->where('quote.userid',$auth_id)->whereColumn('quote.personnelid','quote.primaryname')->whereIn('quote.ticket_status',['2','3','4','5'])->where('quote.givenstartdate','!=',null);
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
        $totalbillingData = $totalbillingData->orderBy('quote.id','desc')->get();

        $personnelUser = Personnel::select('*')->where('userid',$auth_id)->orderBy('id','DESC')->get();

        if(isset($request->pid)) {
          $pid = $request->pid;
        } else {
          $pid = "";
        }

        if($request->search == "excel") 
        {
        $fileName = date('d-m-Y').'_order.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Invoice Id','Date','Customer Name','Personnel Name','Amount', 'Payment Status');

          $callback = function() use($totalbillingData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

              foreach ($totalbillingData as $key =>$value) {
                 if($value->parentid!=0) {
                  $ids= $value->parentid;
                } else {
                  $ids = $value->id;
                }
                if($value->payment_status!="" || $value->payment_mode!="") {
                  $pstatus = "Paid";
                } 
                elseif($value->invoiced=="1") {
                  $pstatus = "invoiced";
                }
                elseif($value->invoiced=="0" && $value->payment_mode=="") {
                  $pstatus = "Pending";
                }
                $newdate  = date("M, d Y", strtotime($value->date));
                fputcsv($file, array($ids, $newdate, $value->customername, $value->personnelname,$value->price, $pstatus));
              }

              fclose($file);
          };
          return response()->stream($callback, 200, $headers);
        }
        return view('billing.viewallticket',compact('auth_id','totalbillingData','personnelUser','pid'));
    }

    public function viewallticketfilter(Request $request) {
      $auth_id = auth()->user()->id;
      if(auth()->user()->role == 'company') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }
    
    $totalbillingData = DB::table('quote')
      ->select(DB::raw('givenstartdate as date'),'customer.customername','personnel.personnelname','quote.id','quote.price','quote.payment_status','quote.payment_mode','quote.invoiceid','quote.invoiced')
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
      $totalbillingData = $totalbillingData->orderBy('quote.id','desc')->get();
        $fileName = date('d-m-Y').'_order.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Invoice Id','Date','Customer Name','Personnel Name','Amount', 'Payment Status');

        $callback = function() use($totalbillingData, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

          foreach ($totalbillingData as $key =>$value) {
            if($value->payment_status!="" || $value->payment_mode!="") {
              $pstatus = "Paid";
            } 
            elseif($value->invoiced=="1") {
              $pstatus = "invoiced";
            }
            elseif($value->invoiced=="0" && $value->payment_mode=="") {
              $pstatus = "Pending";
            }
            $newdate  = date("M, d Y", strtotime($value->date));
            fputcsv($file, array($value->id, $newdate, $value->customername, $value->personnelname,$value->price, $pstatus));
          }

          fclose($file);
      };
        return response()->stream($callback, 200, $headers);
    }

}
