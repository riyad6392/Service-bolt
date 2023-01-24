<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Personnel;
use App\Models\Inventory;
use App\Models\Address;
use App\Models\User;
use DB;
use App\Models\Schedulerhours;
use DateTime;
use Mail;
use App\Models\Tenture;
use App\Models\Notification;
use App\Events\MyEvent;
use Image;

class WorkerTicketController extends Controller
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
        $ticketdata = DB::table('quote')->where('personnelid',$worker->workerid)->whereIn('ticket_status',array('2','4'))->orderBy('id','desc')->get();
//dd($ticketdata);
        //$workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->whereDate('created_at', DB::raw('CURDATE()'))->first();

        $workerh = DB::table('workerhour')->where('workerid',$worker->workerid)->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->first();
       
        return view('personnel.myticket',compact('auth_id','ticketdata','workerh'));
    }

  public function completedticket(Request $request) 
  {
     $auth_id = auth()->user()->id;
       
        if(auth()->user()->role == 'worker') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $worker = DB::table('users')->select('workerid')->where('id',$auth_id)->first();
        
        $ticketdata = DB::table('quote')->where('personnelid',$worker->workerid)->whereIn('ticket_status',array('3'))->orderBy('id','desc')->get();
       
        return view('personnel.completeticket',compact('auth_id','ticketdata')); 
    }

    public function viewticketmodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $ticketid = $request->id;
       $ticketData = DB::table('quote')->select('address','time','ticket_status')->where('id',$ticketid)->first();
       $sdata = Schedulerhours::where('ticketid', $ticketid)->get()->first();
       $urlview = url('personnel/myticket/view/').'/'.$ticketid;
       $html ="";
       $html .='<input type="hidden" name="ticketid" value="'.$ticketid.'">';
      if($ticketData->ticket_status=="2") {
        $html .='<button type="submit" class="btn add-btn-yellow w-100 mb-4" name="Pickup" value="Pickup">Pickup</button>';
        $html .='<button type="submit" class="btn add-btn-yellow w-100 mb-4" name="closeout" value="closeout" style="pointer-events:none;">Close Out</button></button>';
      }

      if($ticketData->ticket_status=="4") {
         $html .='<button type="submit" class="btn add-btn-yellow w-100 mb-4" name="picked" value="picked" style="pointer-events:none;">Picked</button>';
         $html .='<button type="submit" class="btn add-btn-yellow w-100 mb-4" name="closeout" value="closeout" style="">Close Out</button></button>';
      }

      if($ticketData->ticket_status=="3") {
        $html .='<button type="submit" class="btn add-btn-yellow w-100 mb-4" name="completed" value="completed" style="pointer-events:none;">Completed</button>';

        $html .='<button type="submit" class="btn add-btn-yellow w-100 mb-4" name="unclose" value="unclose" style="">UnClose</button>';
      }

      
       $html .='<a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</a>';
        return json_encode(['html' =>$html]);
        die;
    }

    public function viewticketmodal1(Request $request)
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
        $personeldata =Quote::select('quote.personnelid','personnel.personnelname')->leftjoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.id', $request->ticketid)->get()->first();

        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $currentDate = date('l - F d, Y');
        if($request->Pickup == "Pickup") {
          $ticket = Quote::where('id', $request->ticketid)->first();
          $ticket->ticket_status = 4;
          $ticket->save();

          $ticket1 = Quote::where('parentid', $request->ticketid)->get();
          if(count($ticket1)>0) {
            foreach($ticket1 as $key => $value) {
              DB::table('quote')->where('parentid','=',$value->parentid)
              ->update([ 
                  "ticket_status"=>4,
              ]);    
            }
          }
          //$pidarray = explode(',', $ticket->product_id);

          // if(!empty($ticket->product_id)) {
          //   foreach($pidarray as $key => $pid) {
          //     $productd = Inventory::where('id', $pid)->first();
          //     if(!empty($productd)) {
          //       $productd->quantity = (@$productd->quantity) - 1;
          //       $productd->save();
          //     }

          //   }
          // }
          date_default_timezone_set('Asia/Kolkata');
          $currentDateTime=date('m/d/Y H:i:s');
          $date1=date('Y-m-d');
          $starttime = date('h:i A', strtotime($currentDateTime));

          $data['workerid'] = $worker->workerid;
          $data['ticketid'] = $request->ticketid;
          $data['starttime'] = $starttime;
          $data['date'] = $currentDate;
          $data['date1'] = $date1;

          Schedulerhours::create($data);

          $app_name = 'ServiceBolt';
          $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
          
          $user_exist = DB::table('users')->select('email','firstname')->where('id',$ticket->userid)->first();
          $ticketid ='#'.$request->ticketid;
          $ticketsub = "Ticket $ticketid picked up by $personeldata->personnelname";
          $ticketheading = "The ticket below has been picked successfully.";

          Mail::send('mail_templates.sendpickup', ['ticketId'=>$ticket->id,'address'=>$ticket->address, 'customername'=>$ticket->customername,'price'=>$ticket->price,'hours'=>$ticket->time,'minutes'=>$ticket->minute,'starttime'=>$ticket->giventime,'date'=>$ticket->givendate,'name'=>$user_exist->firstname,'heading'=>$ticketheading], function($message) use ($user_exist,$app_name,$app_email,$ticketsub) {
            $message->to($user_exist->email)
            ->subject($ticketsub);
            //$message->from($app_email,$app_name);
          });

          $data1['uid'] = $worker->userid;
          $data1['pid'] = $worker->workerid;
          $data1['ticketid'] = $request->ticketid;
          $data1['message'] = $ticketsub;

          Notification::create($data1);
          event(new MyEvent($ticketsub));
          $request->session()->flash('success', 'Ticket Pickup successfully');
          return redirect()->route('worker.myticket');
        }
        if($request->closeout == "closeout") {
            $ticket = Quote::where('id', $request->ticketid)->first();
            $ticket->ticket_status = 3;
            $ticket->save();
            
            $ticket1 = Quote::where('parentid', $request->ticketid)->get();
            if(count($ticket1)>0) {
              foreach($ticket1 as $key => $value){
                DB::table('quote')->where('parentid','=',$value->parentid)
                ->update([ 
                    "ticket_status"=>3,
                ]);    
              }
            }

            date_default_timezone_set('Asia/Kolkata');
            $currentDateTime=date('m/d/Y H:i:s');
            $date1=date('Y-m-d');
            $endtime = date('h:i A', strtotime($currentDateTime));

            $sdata = Schedulerhours::where('ticketid', $request->ticketid)->first();
            if($sdata) {
              $satrttime  = $sdata->starttime;
              $datetime1 = new DateTime($satrttime);
              $datetime2 = new DateTime($endtime);
              $interval = $datetime1->diff($datetime2);
              $totalhours = $interval->format('%hh %im');

              $sdata->endtime = $endtime;
              $sdata->totalhours = $totalhours;
              $sdata->date1 = $date1;
              $sdata->save();
            }

            $app_name = 'ServiceBolt';
            $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
            
            $user_exist = DB::table('users')->select('email','firstname')->where('id',$ticket->userid)->first();
            $ticketid ='#'.$request->ticketid;
            $ticketsub = "Ticket $ticketid has been closed";
            $ticketheading = "The ticket below has been completed successfully.";
            
            Mail::send('mail_templates.sendpickup', ['ticketId'=>$ticket->id,'address'=>$ticket->address, 'customername'=>$ticket->customername,'price'=>$ticket->price,'hours'=>$ticket->time,'minutes'=>$ticket->minute,'starttime'=>$ticket->giventime,'date'=>$ticket->givendate,'name'=>$user_exist->firstname,'heading'=>$ticketheading], function($message) use ($user_exist,$app_name,$app_email,$ticketsub) {
              $message->to($user_exist->email)
              ->subject($ticketsub);
              //$message->from($app_email,$app_name);
            });

            $data1['uid'] = $worker->userid;
            $data1['pid'] = $worker->workerid;
            $data1['ticketid'] = $request->ticketid;
            $data1['message'] = $ticketsub;

            Notification::create($data1);
            event(new MyEvent($ticketsub));
            $request->session()->flash('success', 'Ticket completed successfully');
            return redirect()->route('worker.myticket');
        }

        if($request->unclose == "unclose") {
          $ticket = Quote::where('id', $request->ticketid)->get()->first();
          $ticket->ticket_status = 4;
          $ticket->save();

          $ticket1 = Quote::where('parentid', $request->ticketid)->get();
          if(count($ticket1)>0) {
            foreach($ticket1 as $key => $value){
              DB::table('quote')->where('parentid','=',$value->parentid)
              ->update([ 
                  "ticket_status"=>4,
              ]);    
            }
          }

          $request->session()->flash('success', 'Ticket Unclose successfully');
          return redirect()->back();
        }
        
        return redirect()->route('worker.myticket');
    }

    public function update1(Request $request)
    {

       $personeldata =Quote::select('quote.personnelid','personnel.personnelname')->leftjoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.id', $request->ticketid)->get()->first();

        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
        $currentDate = date('l - F d, Y');
        if($request->Pickup == "Pickup") {
          $ticket = Quote::where('id', $request->ticketid)->get()->first();
          //new logic

          $ticket->ticket_status = 4;
          $ticket->save();

          $ticket1 = Quote::where('parentid', $request->ticketid)->get();
          //new logic
          if(count($ticket1)>0) {
            foreach($ticket1 as $key => $value) {
              DB::table('quote')->where('parentid','=',$value->parentid)
              ->update([ 
                  "ticket_status"=>4,
              ]);    
            }
          }

        // if(!empty($ticket->product_id)) {
        //   $pidarray = explode(',', $ticket->product_id);
        //   //dd($pidarray);
        //   foreach($pidarray as $key => $pid) {
        //     $productd = Inventory::where('id', $pid)->first();
        //     if($productd!=null) {
        //       $productd->quantity = @$productd->quantity - 1;
        //       $productd->save();  
        //     }
        //   }
        // }


          date_default_timezone_set('Asia/Kolkata');
          $currentDateTime=date('m/d/Y H:i:s');
          $date1=date('Y-m-d');
          $starttime = date('h:i A', strtotime($currentDateTime));

          $data['workerid'] = $worker->workerid;
          $data['ticketid'] = $request->ticketid;
          $data['starttime'] = $starttime;
          $data['date'] = $currentDate;
          $data['date1'] = $date1;

          Schedulerhours::create($data);

          $app_name = 'ServiceBolt';
          $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');

          $user_exist = DB::table('users')->select('email','firstname')->where('id',$ticket->userid)->first();


          $ticketid ='#'.$request->ticketid;
          $ticketsub = "Ticket $ticketid picked up by $personeldata->personnelname";
          $ticketheading = "The ticket below has been picked successfully.";

          Mail::send('mail_templates.sendpickup', ['ticketId'=>$ticket->id,'address'=>$ticket->address, 'customername'=>$ticket->customername,'price'=>$ticket->price,'hours'=>$ticket->time,'minutes'=>$ticket->minute,'starttime'=>$ticket->giventime,'date'=>$ticket->givendate,'name'=>$user_exist->firstname,'heading'=>$ticketheading], function($message) use ($user_exist,$app_name,$app_email,$ticketsub) {
            $message->to($user_exist->email)
            ->subject($ticketsub);
            //$message->from($app_email,$app_name);
          });

          $data1['uid'] = $worker->userid;
          $data1['pid'] = $worker->workerid;
          $data1['ticketid'] = $request->ticketid;
          $data1['message'] = $ticketsub;

          Notification::create($data1);
          event(new MyEvent($ticketsub));
          $request->session()->flash('success', 'Ticket Pickup successfully');
          return redirect()->back();
        }
        if($request->closeout == "closeout") {
            $ticket = Quote::where('id', $request->ticketid)->get()->first();
            
            $ticket->ticket_status = 3;
            $ticket->ticketdate = date('Y-m-d');
            $ticket->save();

            

            $ticket1 = Quote::where('parentid', $request->ticketid)->get();
              if($request->pointckbox) {
                  $cheklist =implode(",", $request->pointckbox);
              } else {
                $cheklist = null;
              }
            if(count($ticket1)>0) {
              foreach($ticket1 as $key => $value) {
                DB::table('quote')->where('parentid','=',$value->parentid)
                ->update([ 
                      "ticket_status"=>3,
                      "ticketdate"=>date('Y-m-d'),
                      "checklist"=>$cheklist,
                ]);    
              }
            }


            date_default_timezone_set('Asia/Kolkata');
            $currentDateTime=date('m/d/Y H:i:s');
            $date1=date('Y-m-d');
            $endtime = date('h:i A', strtotime($currentDateTime));

            $sdata = Schedulerhours::where('ticketid', $request->ticketid)->get()->first();
            if($sdata) {
              $satrttime  = $sdata->starttime;
              $datetime1 = new DateTime($satrttime);
              $datetime2 = new DateTime($endtime);
              $interval = $datetime1->diff($datetime2);
              $totalhours = $interval->format('%hh %im');

              $sdata->endtime = $endtime;
              $sdata->totalhours = $totalhours;
              $sdata->date1 = $date1;
              $sdata->save();
            }

            $app_name = 'ServiceBolt';
            $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');

            $user_exist = DB::table('users')->select('email','firstname')->where('id',$ticket->userid)->first();
            $ticketid ='#'.$request->ticketid;
            $ticketsub = "Ticket $ticketid has been closed";
            $ticketheading = "The ticket below has been completed successfully.";

            Mail::send('mail_templates.sendpickup', ['ticketId'=>$ticket->id,'address'=>$ticket->address, 'customername'=>$ticket->customername,'price'=>$ticket->price,'hours'=>$ticket->time,'minutes'=>$ticket->minute,'starttime'=>$ticket->giventime,'date'=>$ticket->givendate,'name'=>$user_exist->firstname,'heading'=>$ticketheading], function($message) use ($user_exist,$app_name,$app_email,$ticketsub) {
              $message->to($user_exist->email)
              ->subject($ticketsub);
              //$message->from($app_email,$app_name);
            });

          $data1['uid'] = $worker->userid;
          $data1['pid'] = $worker->workerid;
          $data1['ticketid'] = $request->ticketid;
          $data1['message'] = $ticketsub;

          Notification::create($data1);
          event(new MyEvent($ticketsub));

          $request->session()->flash('success', 'Ticket completed successfully');
          return redirect()->back();
        }

        if($request->unclose == "unclose") {
          $ticket = Quote::where('id', $request->ticketid)->get()->first();
          $ticket->ticket_status = 4;
          $ticket->save();

          $ticket1 = Quote::where('parentid', $request->ticketid)->get();
          if(count($ticket1)>0) {
            foreach($ticket1 as $key => $value){
              DB::table('quote')->where('parentid','=',$value->parentid)
              ->update([ 
                  "ticket_status"=>4,
              ]);    
            }
          }
          $request->session()->flash('success', 'Ticket Unclose successfully');
          return redirect()->back();
        }

        if($request->closeout == null) {

          $ticket = Quote::where('id', $request->ticketid)->first();
          if($request->pointckbox) {
            $cheklist =implode(",", $request->pointckbox);
            $checklist =  $cheklist;
          } else {
            $checklist = null;
          }

          if($request->cnotes) {
            $customernotes =  $request->cnotes;
          }
        //for image upload
        $files=array();
        if(!empty($request->file('image'))) {
          foreach ($request->file('image') as $media) {
              if (!empty($media)) {
                  $datetime = date('YmdHis');
                  $image = $media->getClientOriginalName();
                  $imageName = $datetime . '_' . $image;
                  $media->move(public_path('uploads/ticketnote/'), $imageName);
                  array_push($files,$imageName);
              }
          }
        }
          $olddataarray = explode(',',$ticket->imagelist);
          //dd($olddataarray);
          if(!empty($ticket->imagelist)) {
            $oldnewarray = array();
            if(!empty($request->oldimage)) {
              $oldnewarray = $request->oldimage;
            }
            
           // dd($oldnewarray);
            $result=array_diff($olddataarray,$oldnewarray);

            if(count($result)>0) {
              foreach($result as $image)
              {   
                $path = 'uploads/ticketnote/';
                
                $stories_path=$path.$image;;
                @unlink($stories_path);
              }
            }

            foreach($oldnewarray as $name) {
               array_push($files,$name);
            }
          }
          
          $newimagestring = implode(',',$files);
          // $ticket->imagelist = $newimagestring;
        
          // $ticket->save();
          DB::table('quote')->where('id','=',$request->ticketid)->orWhere('parentid','=',$request->ticketid)
          ->update([ 
              "checklist"=>"$checklist","customernotes"=>"$customernotes","imagelist"=>"$newimagestring"
          ]);

          $request->session()->flash('success', 'Updated successfully');
          return redirect()->back();
        }
        return redirect()->back();
    }

    public function view(Request $request ,$id) {
      $auth_id = auth()->user()->id;
     
      if(auth()->user()->role == 'worker') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }

      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $productData = Inventory::where('user_id',$worker->userid)->where('workerid',$worker->workerid)->orderBy('id','ASC')->get();

      $tenture = Tenture::where('status','Active')->get();

      $sdata = Quote::select('serviceid')->where('id',$id)->first();
        $quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber')->leftjoin('customer', 'customer.id', '=', 'quote.customerid')->where('quote.id',$id)->first();
        if($sdata->serviceid!="") {
          $serviceidarrays = explode(',', $sdata->serviceid);
        } else {
          $serviceidarrays = array();
        }
        
        

        $checklistData = DB::table('checklist')->select('*')->whereIn('serviceid',$serviceidarrays)->get();


        $sdata = Schedulerhours::where('ticketid', $quoteData->id)->get()->first();
        
      @$workersdata = Personnel::where('id',$worker->workerid)->first();
      @$permissonarray = explode(',',$workersdata->ticketid);
      $prequoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber')->leftjoin('customer', 'customer.id', '=', 'quote.customerid')->where('quote.customerid',$quoteData->customerid)->where('quote.parentid','=','')->whereIn('quote.ticket_status',array('3','4'))->get();
        $sum = 0;
        if($quoteData->serviceid!="") {
          $serviceidarray = explode(',', $quoteData->serviceid);

        $servicedetails = Service::select('servicename','price')->whereIn('id', $serviceidarray)->get();
        
        foreach ($servicedetails as $key => $value) {
          $sname[] = $value['servicename'];
          $sum+= (int)$value['price'];
        } 
        
        $servicename = implode(',', $sname);
        } else {
          $servicename = "";
        }
        
        $sum1 = 0;
        if(!empty($quoteData->product_id)) {
          $pidarray = explode(',', $quoteData->product_id);
          $pdetails = Inventory::select('productname','id','price')->whereIn('id', $pidarray)->get();
          foreach ($pdetails as $key => $value) {
            @$pname[] = $value['productname'];
            $sum1+= (int)$value['price'];
          }

          $totalprice = @$sum+@$sum1;
          @$productname = implode(',', @$pname);
        } else {
          @$productname = "";
          $totalprice = $quoteData->price;
        }
        
        $cid = $quoteData->customerid;

        $addressinfo = Address::select('checklistid')->where('customerid',$cid)->where('address',$quoteData->address)->first();
        $ckinfo = array();
        if($addressinfo->checklistid!="") {
          $ckids = explode(',',$addressinfo->checklistid);
          $ckinfo = DB::table('checklist')->select('serviceid','checklistname','checklist','userid')->whereIn('serviceid',$ckids)->where('userid',$worker->userid)->groupBy('serviceid')->get();
          if(count($ckinfo)>0) {
            $ckinfo = $ckinfo; 
          }
        }
        
        return view('personnel.ticketview',compact('quoteData','checklistData','sdata','prequoteData','servicename','productname','totalprice','productData','tenture','cid','ckinfo','permissonarray'));
    }

    public function viewmap(Request $request ,$id) {
      //$quoteData = DB::table('quote')->select('quote.*', 'customer.phonenumber')->leftjoin('customer', 'customer.id', '=', 'quote.customerid')->where('quote.id',$id)->first();
       
       return view('personnel.ticketviewmap',compact('id'));
    }

    public function mapdata(Request $request) {
      $id =  $request->id;
      $auth_id = auth()->user()->id;
      
      $scheduleData = DB::table('quote')->select('quote.*', 'customer.image','customer.customername')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.id',$id)->get();
      $json = array();
      $data = [];
          foreach($scheduleData as $key => $value) {
            array_push($data, [$value->customername,$value->latitude,$value->longitude,$value->address,$value->image,$value->id,$value->giventime]);

          }
      return json_encode(['html' =>$data]);
    }

    public function paynow(Request $request ,$id) {
        $quoteData = DB::table('quote')->select('*')->where('id',$id)->first();
        if($quoteData->payment_mode !=""){
          $paymentpaid = "1";
        } else {
          $paymentpaid = "0";
        }
        return view('personnel.paynow',compact('quoteData','paymentpaid'));
    }

    public function vieweditinvoicemodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       $personnelid = auth()->user()->workerid;
       $quote = Quote::where('id', $request->id)->first();
       $customer = Customer::where('id', $quote->customerid)->get();

       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
       $sid = explode(',',$customer[0]->serviceid);
       $serviceData = Service::where('userid', $worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','desc')->get();

       $getprimary = $quote->primaryname;

       //$serviceData = Service::whereIn('id',$sid)->orderBy('id','ASC')->get();
       

       $userData =  User::select('workerid','userid')->where('id',$auth_id)->first();
       $workers = Personnel::where('id',$userData->workerid)->first();

       $productData = DB::table('products')->where('user_id',$userData->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','desc')->get();

       $permissonarray = explode(',',$workers->ticketid);

      $paynowurl = url('personnel/myticket/paynow/').'/'.$request->id;
       
       $html ='<div class="add-customer-modal">
                  <h5>Create Invoice</h5>
                </div>';
       $html .='<input type="hidden" name="ticketprice" id="ticketprice" value="'.$quote->tickettotal.'"><div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$customer[0]->id.'" name="customerid">
       <input type="hidden" value="'.$request->id.'" name="qid" id="qid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Customer Name</label>
            <input type="text" class="form-control" placeholder="Customer" name="customername" id="customername" value="'.$customer[0]->customername.'" readonly>
          </div>
          </div>
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
                $serviceids =explode(",", $quote->serviceid);
                 if(in_array($value->id, $serviceids)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->servicename.' ($'.$value->price.')</option>';
              }
        $html .='</select>
        <a href="#" data-bs-toggle="modal" data-bs-target="#add-services" id="sclick"><i class="fa fa-plus"></i></a>
          </div>
          <div class="col-md-12 mb-3">
          <label>Select Products</label>
      <div class="d-flex align-items-center">
        <select class="form-control selectpicker" data-live-search="true" multiple="" data-placeholder="Select Products" style="width: 100%;height:auto;" tabindex="-1" aria-hidden="true" name="productid[]" id="productid" style="height:auto;">';

              foreach($productData as $key => $value) {
                $productids =explode(",", $quote->product_id);
                 if(in_array($value->id, $productids)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->productname.' ($'.$value->price.')</option>';
              }
        $html .='</select>
        <a href="#" data-bs-toggle="modal" data-bs-target="#add-product" id="pclick"><i class="fa fa-plus"></i></a>
          </div><div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Price</label>
            <input type="text" class="form-control" placeholder="Price" name="price" id="price12" value="'.$request->price.'" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" required>
          </div>
          </div><div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" placeholder="Notes" name="description" id="description" value="'.$quote->description.'">
          </div>
          </div>
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Personnel Notes</label>
            <input type="text" class="form-control" placeholder="Notes" name="customernotes" id="customernotes" value="'.strip_tags($quote->customernotes).'" readonly>
          </div>
          </div><input type="hidden" name="id" id="id" value="'.$quote->id.'">';
      $html .= '<div class="row">
          <div class="col-lg-6">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal" style="height:42px;">Cancel</span>
          </div>
          <div class="col-lg-6 mb-2">
            <button type="submit" class="add-btn-yellow w-100" style="text-align: center;border:0;height:42px;" name="type" value="save">Save</button>
          </div>
          </div>';
      if($getprimary == $personnelid) {
        if(in_array("Create Invoice for payment", $permissonarray) || in_array("Administrator", $permissonarray)) {
         $html .= '<div class="row"><div class="col-lg-6 mb-2">
              <button type="submit" class="add-btn-yellow w-100" style="text-align: center;border:0;" name="type" value="sendinvoice">Send Invoice</button>
            </div>
            <div class="col-lg-6">
              <button type="submit" class="add-btn-yellow w-100" style="text-align: center;border:0;" name="type" value="paynow">Pay Now</button>
            </div></div>';
        }
        elseif(in_array("Generate PDF for invoice", $permissonarray) || in_array("Administrator", $permissonarray)) {
         $html .= '<div class="row"><div class="col-lg-6 mb-2">
              <button type="submit" class="add-btn-yellow w-100" style="text-align: center;border:0;" name="type" value="sendinvoice">Send Invoice</button>
            </div>
            </div>';
          }
      } else {
        $html .= '<div class="row"><div class="col-lg-6 mb-2">
              <button type="submit" class="add-btn-yellow w-100" style="text-align: center;border:0;pointer-events: none;background:#fee2002e;" name="type" value="sendinvoice">Send Invoice</button>
            </div>
            <div class="col-lg-6">
              <button type="submit" class="add-btn-yellow w-100" style="text-align: center;border:0;pointer-events: none;background:#fee2002e;" name="type" value="paynow">Pay Now</button>
            </div></div>';
      }
        $html .= '</div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function ticketupdate(Request $request)
    {
      //dd($request->all());
      $quoteid = $request->quoteid;

      $quote = Quote::where('id', $quoteid)->get()->first();
      if($request->pointckbox) {
        $cheklist =implode(",", $request->pointckbox);
        $quote->checklist =  $cheklist;
      } else {
        $quote->checklist = null;
      }
      $quote->customernotes =  $request->cnotes;
      
      $quote->save();
      
      $request->session()->flash('success', 'Updated successfully');
      return redirect()->back();
    }

    public function sendinvoice(Request $request)
    {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $customerid = $request->customerid;

      $customer = Customer::where('id', $customerid)->get()->first();

      $serviceid = implode(',', $request->serviceid);

      $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', $worker->userid)->first();

      $servicedetails = Service::select('servicename','productid','price')->whereIn('id', $request->serviceid)->get();
      $servicenames = $servicedetails[0]->servicename;
      $sum = 0;
      foreach($servicedetails as $key => $value) {
        $pid[] = $value['productid'];
        $sname[] = $value['servicename'];
        $txvalue = 0;
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
          if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
              $txvalue = $value['price']*$userdetails->servicevalue/100; 
          } else {
              $txvalue = 0;
          }
        }
        $sum+= $txvalue;
      }
      $servicename = implode(',', $sname);
      
      $quote = Quote::where('id', $request->id)->get()->first();

      if($request->productid=="") {
        $request->productid = array();
      }
      if($quote->product_id=="") {
        $productids = array();
      }

      $productids = explode(',',$quote->product_id);

      $removedataid = array_diff($productids,$request->productid);
        if($removedataid!="") {
          foreach($removedataid as $key => $value) {
            $productd = Inventory::where('id', $value)->first();
            if(!empty($productd)) {
              $productd->quantity = (@$productd->quantity) + 1;
              $productd->save();
            }
          }
        }
      if($request->productid!=null) {
        $reqpids = $request->productid;
        $plusdataids= array_diff($reqpids,$productids); 
        if($plusdataids!="") {
          foreach($plusdataids as $key => $value) {
            $productd = Inventory::where('id', $value)->first();
            if(!empty($productd)) {
              $productd->quantity = (@$productd->quantity) - 1;
              $productd->save();
            }
          }
        }
      }

      $productid = "";
      $productname = "";
      $sum1 = 0;
      $txvalue1 = 0;
      if(count($request->productid)>0) {
        $productid = implode(',', $request->productid);

        $pdetails = Inventory::select('productname','id','price')->whereIn('id', $request->productid)->get();
          foreach ($pdetails as $key => $value) {
            $pname[] = $value['productname'];
            if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
              if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
                  $txvalue1 = $value['price']*$userdetails->productvalue/100; 
              } else {
                  $txvalue1 = 0;
              }
              }
              $sum1+= $txvalue1;
          } 
          $productname = implode(',', $pname);
      } else {
        $productid = null;
      }

      $totaltax = $sum+$sum1;
      $totaltax = number_format($totaltax,2);
      $totaltax = preg_replace('/[^\d.]/', '', $totaltax);
      
      $quote = Quote::where('id', $request->id)->get()->first();

      //$pids = rtrim($productid, ',');
      $pids = $productid;

      $company = User::where('id', $quote->userid)->get()->first();
      if($company->image!=null) {
        $companyimage = url('').'/userimage/'.$company->image;
      } else {
        $companyimage = url('').'/uploads/servicebolt-noimage.png';
      }
      $cdefaultimage = url('').'/uploads/servicebolt-noimage.png';
      if($request->description) {
        $description =  $request->description;
      } else {
        $description = null;
      }

      DB::table('quote')->where('id','=',$request->id)->orWhere('parentid','=',$request->id)
          ->update([ 
              "description"=>"$description","serviceid"=>"$serviceid","servicename"=>"$servicenames","product_id"=>"$pids","price"=>"$request->price","tickettotal"=>"$request->ticketprice","tax"=>"$totaltax"
      ]);

      // $quote->serviceid = $serviceid;
      // $quote->servicename = $servicedetails[0]->servicename;
      // $quote->product_id = rtrim($productid, ',');
      // $quote->price = $request->price;
      // $quote->tickettotal = $request->ticketprice;
      // $quote->tax = $totaltax;
      // $quote->save();
      if($request->type=="paynow") {
        $paynowurl = url('personnel/myticket/paynow/').'/'.$request->id;
        return redirect($paynowurl);
      }
      if($request->type=="save") {
        $request->session()->flash('success', 'Invoice has been Save successfully');
        return redirect()->back();
      }
      if($request->type=="sendinvoice") {
        if($customer->email!=null) {
          // $tdata1 = Quote::where('id', $request->id)->get()->first();
          // $tdata1->invoiced = 1;
          // $tdata1->save();
          DB::table('quote')->where('id','=',$request->id)->orWhere('parentid','=',$request->id)
          ->update([ 
              "invoiced"=>1
          ]);

          $app_name = 'ServiceBolt';
          $app_email = env('MAIL_FROM_ADDRESS','ServiceBolt');
          $email = $customer->email;
          $user_exist = Customer::where('email', $email)->first();
          Mail::send('mail_templates.sendinvoice', ['invoiceId'=>$quote->invoiceid,'address'=>$quote->address,'billingaddress'=>$customer->billingaddress,'ticketid'=>$quote->id, 'customername'=>$customer->customername,'servicename'=>$servicename,'productname'=>$productname,'price'=>$request->price,'time'=>$quote->giventime,'date'=>$quote->givenstartdate,'description'=>$quote->customernotes,'companyname'=>$customer->companyname,'phone'=>$customer->phonenumber,'email'=>$customer->email,'cimage'=>$companyimage,'cdimage'=>$cdefaultimage,'serviceid'=>$serviceid,'productid'=>$productid,'duedate'=>$quote->duedate,'quoteuserid'=>$quote->userid], function($message) use ($user_exist,$app_name,$app_email) {
              $message->to($user_exist->email)
              ->subject('Invoice details!');
            });
        }
      }

    
      $request->session()->flash('success', 'Invoice has been sent successfully');
      return redirect()->back();
    }

    public function createticket(Request $request)
    {
      $auth_id = auth()->user()->id;
      if(auth()->user()->role == 'worker') {
          $auth_id = auth()->user()->id;
      } else {
         return redirect()->back();
      }
      
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      $customer = Customer::where('userid',$worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','DESC')->get();
      $services = Service::where('userid', $worker->userid)->orWhere('workerid',$worker->workerid)->get();
      $products = Inventory::where('user_id', $worker->userid)->orWhere('workerid',$worker->workerid)->get();

      //$workerlist = Personnel::where('userid', $worker->userid)->where('id','!=',$worker->workerid)->get();
      $workerlist = Personnel::where('userid', $worker->userid)->get();
      
      $personnel = Personnel::where('id',$worker->workerid)->first();
    
      $permissonarray = explode(',',$personnel->ticketid);
      $tenture = Tenture::where('status','Active')->get();
      if(in_array("Create Ticket", $permissonarray)) {
        return view('personnel.createticket',compact('auth_id','customer','services','workerlist','tenture','products'));
      } else {
        return redirect()->back();
      }
    }

    public function getaddressbyid(Request $request)
    {
        $data['address'] = Address::where("customerid",$request->customerid)
                    ->get(["address","id"]);

      $customer = Customer::where('id', $request->customerid)->get();

      $sid = explode(',',$customer[0]->serviceid);

       $data['serviceData'] = Service::whereIn('id',$sid)->get(["servicename","id","time","minute","frequency","price"]);
       
        $data['address'] = Address::where("customerid",$request->customerid)
                    ->get(["address","id"]);
        return response()->json($data);
    }

    public function ticketcreate(Request $request)
    {
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', $worker->userid)->first();

        $customer = Customer::select('customername','email')->where('id', $request->customerid)->first();

        $serviceid = implode(',', $request->servicename);

        $servicedetails = Service::select('servicename','productid','price')->whereIn('id', $request->servicename)->get();
        $sum = 0;
        foreach($servicedetails as $key => $value) {
          $pid[] = $value['productid'];
          $sname[] = $value['servicename'];
          $txvalue = 0;
          if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
                $txvalue = $value['price']*$userdetails->servicevalue/100; 
            } else {
                $txvalue = 0;
            }
          }
          $sum+= $txvalue;
        }
        $servicename = implode(',', $sname);

        $productid = "";
        $productname = "";
        $productnames = "";

        if(isset($request->productname)) {
          $productid = implode(',', $request->productname);
        }
        $sum1 = 0;
        $txvalue1 = 0;
        if($request->productname!="") {
          $productdetails = Inventory::select('id','productname','price')->whereIn('id', $request->productname)->get();
        
               
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

          $productd = Inventory::where('id', $value['id'])->first();
          if(!empty($productd)) {
            $productd->quantity = (@$productd->quantity) - 1;
            $productd->save();
          }
        }
        $productname = $productdetails[0]->productname;

        $productnames = implode(',', $pname);
      }

      $totaltax = $sum+$sum1;
      $totaltax = number_format($totaltax,2);
      $totaltax = preg_replace('/[^\d.]/', '', $totaltax);
        
        $auth_id = auth()->user()->id;
        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $data['userid'] = $worker->userid;
        $data['workerid'] = $worker->workerid;
        $data['customerid'] = $request->customerid;
        $data['serviceid'] =  $serviceid;
        $data['servicename'] = $servicedetails[0]->servicename;
        $data['product_name'] = $productname;
        $data['product_id'] = $productid;
        $data['personnelid'] = $request->personnelid;
        $data['radiogroup'] = $request->radiogroup;
        $data['frequency'] = $request->frequency;
        //$data['time'] = $request->time;
        if($request->time!=null || $request->time!=0) {
          $data['time'] = $request->time.' Hours';
        }
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';;
        }
        $data['price'] = $request->price;
        $data['tickettotal'] = $request->ticketprice;
        $data['etc'] = $request->etc;
        $data['description'] = $request->description;
        $data['customername'] =  $customer->customername;
        $data['address'] = $request->address;
        $data['tax'] = $totaltax;

        $formattedAddr = str_replace(' ','+',$request->address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc'); 
        $output = json_decode($geocodeFromAddr);
        $latitude  = $output->results[0]->geometry->location->lat; 
        $longitude = $output->results[0]->geometry->location->lng;

        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;

        if($request->scheduledtime) {
          $data['giventime'] = $request->scheduledtime;
          $date=date_create($request->etc);
          $dateetc = date_format($date,"l - F d, Y");
          $data['givendate'] = $dateetc;
          $data['ticket_status'] = '2';
        } else {
          $data['ticket_status'] = 1;
        }

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
        
      Mail::send('mail_templates.sharequote', ['name'=>'service ticket','address'=>$request->address, 'servicename'=>$servicename,'productname'=>$productnames,'type'=>$request->radiogroup,'frequency'=>$request->frequency,'time'=>$quotelastid->time,'minute'=>$quotelastid->minute,'price'=>$request->price,'etc'=>$request->etc,'description'=>$request->description], function($message) use ($user_exist,$app_name,$app_email) {
          $message->to($user_exist->email)
          ->subject('Ticket details!');
          //$message->from($app_email,$app_name);
        });
    }
          $request->session()->flash('success', 'Ticket added successfully');
            
          return redirect()->route('worker.myticket');
    }

   public function viewticketpopup(Request $request)
   {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
       $json = array();
       $quotedetailsnew = Quote::where('id', $request->id)->get()->toArray();
       $quotedetails = Quote::where('id', $request->id)->get();

       $tenture = Tenture::where('status','Active')->get();

       $customer = Customer::where('id', $quotedetails[0]->customerid)->get();

      $sid = explode(',',$customer[0]->serviceid);

      //$serviceData = Service::whereIn('id',$sid)->orderBy('id','ASC')->get();
      $serviceData = Service::where('userid', $worker->userid)->orWhere('workerid',$worker->workerid)->orderBy('id','desc')->get();


      //$productData = DB::table('products')->orderBy('id','ASC')->get();
      $productData = Inventory::where('user_id', $worker->userid)->orWhere('workerid',$worker->workerid)->get();
      
      $time =  explode(" ", $quotedetails[0]->time);
      $minute =  explode(" ", $quotedetails[0]->minute);

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
       
       $html ='<div class="add-customer-modal">
                  <h5>Schedule Next Appointment</h5>
                </div>';
       $html .='<input type="hidden" name="ticketprice" id="ticketprice" value="'.$quotedetails[0]->tickettotal.'"><div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="quoteid">
          <div class="col-md-12 mb-2">
            <label>Customer</label>
            <select class="form-select" name="customerid" id="customerid2" required="" disabled>';
             $html .='<option value="'.$quotedetails[0]->customerid.'" selected>'.$quotedetails[0]->customername.'</option></select>
          </div>
          <div class="col-md-12 mb-2">
           <div class="input_fields_wrap">
              <div class="mb-3">
              <label>Customer Address</label>
                <select class="form-select" name="address" id="address3" required disabled>
                  <option value="'.$quotedetails[0]->address.'">'.$quotedetails[0]->address.'</option>
                  </select>
              </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <label>Service</label>
            <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" required="" style="height:auto;">';

              foreach($serviceData as $key => $value) {
                $serviceids =explode(",", $quotedetails[0]->serviceid);
                 if(in_array($value->id, $serviceids)){
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
            <select class="form-control selectpicker" data-live-search="false" multiple="" data-placeholder="Select Produts" style="width: 100%;height:auto;" tabindex="-1" aria-hidden="true" name="productid[]" id="productid" style="height:auto;">';

              foreach($productData as $key => $value) {
                $productids =explode(",", $quotedetails[0]->product_id);
                 if(in_array($value->id, $productids)){
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
              <option value="">Service Frequency</option>';
          foreach ($tenture as $key => $value) {
                if($value->tenturename== $quotedetailsnew[0]['frequency']) {
                  $selectedsf = "selected";
                } else {
                  $selectedsf = "";
                }
                $html .='<option name="'.$value->tenturename.'" value="'.$value->tenturename.'" '.@$selectedsf.'>'.$value->tenturename.'</option>';
            }
            $html .='</select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
           <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" value="'.$time[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" value="'.$minute[0].'" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div></div>
          <div class="col-md-12 mb-3">
            <label>Price</label>
            <input type="text" class="form-control" placeholder="Price" name="price" id="price12" value="'.$quotedetails[0]->price.'" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" required>
           </div>
           <div class="col-md-12 mb-3">
            <label style="position: relative;left: 0px;margin-bottom: 11px;">ETC</label>
           <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" value="'.$quotedetails[0]->etc.'" required>
           </div>
           <div class="col-md-12 mb-3">
             <label>Description</label>
             <textarea class="form-control height-180" placeholder="Description" name="description" id="description" style="color:#000;" required>'.$quotedetails[0]->description.'</textarea>
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

    public function schedulecreate(Request $request)
    {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $userdetails = User::select('taxtype','taxvalue','servicevalue','productvalue')->where('id', $worker->userid)->first();

      $quote = Quote::where('id', $request->quoteid)->get()->first();

      $serviceid = implode(',', $request->serviceid);

      $servicedetails = Service::select('servicename','price')->whereIn('id', $request->serviceid)->get();
      $sum = 0; 
      foreach($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $txvalue = 0;
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
          if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
              $txvalue = $value['price']*$userdetails->servicevalue/100; 
          } else {
              $txvalue = 0;
          }
        }
        $sum+= $txvalue;
      } 
      $productname = "";
      $productid = "";
      $sum1 = "0";
      if(isset($request->productid)) {
        $productid = implode(',', $request->productid);
      
      //$productid = implode(',', $request->productid);

      $pdetails = Inventory::select('productname','price')->whereIn('id', $request->productid)->get();
      $sum1 = 0;
      $txvalue1 = 0; 
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
        if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
            $txvalue1 = $value['price']*$userdetails->productvalue/100; 
        } else {
            $txvalue1 = 0;
        }
        }
        $sum1+= $txvalue1;
      } 
      $productname = implode(',', $pname);
    }

      $servicename = implode(',', $sname);
      

      $totaltax = $sum+$sum1;
      $totaltax = number_format($totaltax,2);
      $totaltax = preg_replace('/[^\d.]/', '', $totaltax);

      $data['customerid'] =  $quote->customerid;
      $data['userid'] =  $quote->userid;
      $data['serviceid'] = $serviceid;
      $data['servicename'] = $servicedetails[0]->servicename;
      $data['product_id'] = $productid;
      $data['product_name'] = $productname;
      $data['personnelid'] = $quote->personnelid;
      $data['radiogroup'] = $request->radiogroup;
      $data['frequency'] = $request->frequency;
      if($request->time!=null || $request->time!=0) {
          $data['time'] = $request->time.' Hours';
        }
        if($request->minute!=null || $request->minute!=0) {
          $data['minute'] = $request->minute.' Minutes';;
        }
      $data['price'] = $request->price;
      $data['tickettotal'] = $request->ticketprice;
      $data['tax'] = $totaltax;
      
      $data['etc'] = $request->etc;
      $data['description'] = $request->description;
      $data['customername'] =  $quote->customername;
      $data['address'] = $quote->address;
      $data['latitude'] = $quote->latitude;
      $data['longitude'] = $quote->longitude;
      $data['checklist'] = $quote->checklist;
      $data['ticket_status'] = 1;
      Quote::create($data);
      $request->session()->flash('success', 'Ticket has been Scheduled successfully');
      return redirect()->back();
    }

    public function calculateprice(Request $request) {
      $json = array();
      $serviceidarray = explode(',', $request->serviceid);
      $servicedetails = Service::select('servicename','price')->whereIn('id', $serviceidarray)->get();
      $sum = 0;
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $sum+= (float)$value['price'];
      } 

      $pidarray = explode(',', $request->productid);
      $pdetails = Inventory::select('productname','id','price')->whereIn('id', $pidarray)->get();
      $sum1 = 0;
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
        $sum1+= (float)$value['price'];
      }
      $totalprice = $sum+$sum1;
      $totalprice = number_format($totalprice,2);
      $totalprice = preg_replace('/[^\d.]/', '', $totalprice);
      if($request->qid != null) {
         $quote = Quote::where('id', $request->qid)->first();
          $quote->tickettotal = $totalprice;
      }
      
      return json_encode(['totalprice' =>$totalprice]);
        die;
    }

    public function addaddress(Request $request)
    {
        $cid = $request->customerid;
        $auth_id = auth()->user()->id;
        $data['authid'] = $auth_id;
        $data['customerid'] = $cid;
        $data['address'] = $request->address;
        Address::create($data);
        
        return json_encode(['address' =>$request->address]);
        die;
    }

    public function sendpayment(Request $request)
    {
      $quote = Quote::where('id', $request->tid)->orWhere('parentid',$request->tid)->get();
      if(count($quote)>0) {
        foreach($quote as $key => $value) {
          if($value->primaryname == $value->personnelid) {
             $personnelid = $value->personnelid;
             $userid = $value->userid;
             $customername = $value->customername;

          }
        }
      if($request->method == "Credit Card") {
      $id = DB::table('balancesheet')->insertGetId([
          'userid' => $userid,
          'workerid' => $personnelid,
          'ticketid' => $request->tid,
          'amount' => $request->amount,
          'customername' => $customername,
          'paymentmethod' => $request->method,
          'status' => "Completed"
        ]);
      
      DB::table('quote')->where('id','=',$request->tid)->orWhere('parentid','=',$request->tid)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->amount","payment_amount"=>"$request->amount","payment_mode"=>"$request->method","card_number"=>"$request->card_number","expiration_date"=>"$request->expiration_date","cvv"=>"$request->cvv"
          ]);
      $request->session()->flash('success', 'Payment has been successfully');
      return redirect()->back();
    }

    if($request->method == "Check") {
      $id = DB::table('balancesheet')->insertGetId([
         'userid' => $userid,
          'workerid' => $personnelid,
          'ticketid' => $request->tid,
          'amount' => $request->payment_amount,
          'customername' => $customername,
          'paymentmethod' => $request->method,
          'status' => "Completed"
        ]);

      DB::table('quote')->where('id','=',$request->tid)->orWhere('parentid','=',$request->tid)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->payment_amount","payment_amount"=>"$request->payment_amount","payment_mode"=>"$request->method","checknumber"=>"$request->checknumber"
          ]);
      $request->session()->flash('success', 'Payment has been successfully');
      return redirect()->back();
    }

    if($request->method == "Cash") {
      $id = DB::table('balancesheet')->insertGetId([
          'userid' => $userid,
          'workerid' => $personnelid,
          'ticketid' => $request->tid,
          'amount' => $request->payment_amount,
          'customername' => $customername,
          'paymentmethod' => $request->method,
          'status' => "Completed"
        ]);
      
      DB::table('quote')->where('id','=',$request->tid)->orWhere('parentid','=',$request->tid)
          ->update([ 
              "payment_status"=>"Completed","price"=>"$request->payment_amount","payment_amount"=>"$request->payment_amount","payment_mode"=>"$request->method"
          ]);
      $request->session()->flash('success', 'Payment has been successfully');
      return redirect()->back();
    }
  }

  }
  
}
