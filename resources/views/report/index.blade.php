@extends('layouts.header')
@section('content')
<!-- for datepicker -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
<style type="text/css">
  #loader1 {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
.loadershow1 {
    height: 100%;
    position: absolute;
    left: 0;
    width: 100%;
    z-index: 10;
    background: rgb(35 35 34 / 43%);
    padding-top: 15%;
    display: flex;
    justify-content: center;
}

.searchBtnDown {
    outline: none;
    border: none;
    background: transparent;
}
/*table.dataTable th, table.dataTable td {
    white-space : initial;
}*/

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.modal-ul-box {
    padding: 0;
    margin: 10px;
    height: auto;
    overflow-y: scroll;
}
.modal-ul-box li {
    list-style: none;
    margin: 14px;
   
    padding:10 0px;
   
}
.btn.btn-sve {
    background: #FEE200;
    padding: 8px 34px;
    box-shadow: 0px 0px 10px #ccc;
}

 i.fa.fa-cog {
        color: gray;
        font-size: 25px;
    }

    input#flexCheckDefault {
        outline: navajowhite;
        width: 20px;
        height: 20px;
        margin: 2px 5px;
        box-shadow: none;
        border: 1px solid gray;
        border-radius: 0;
    }

    .table>thead>tr>th {
        border: navajowhite;
    }

    th,td{
        border: none !important;
    }

    button.exploder {
        background: white;
        outline: none;
        color: black;
        border: none;
        box-shadow: none;
    }

    .btn-danger:hover {
        background: white;
        color: black;
        outline: none;
        border: none;
        box-shadow: none;
    }

    .btn-danger.focus, .btn-danger:focus {
        background: white;
        color: black;
        outline: none;
        border: none;
    }

    .table-new tbody td,
    .table-new thead th {
        z-index: 1;
        padding: 15px 6px !important;
    }

    .glyphicon.glyphicon-plus.plusIcon {
        background: yellow;
        width: 25px;
        height: 25px;
        /* align-items: center; */
        justify-content: center;
        display: flex;
        margin: 0px 4px;
        border-radius: 50px;
        font-weight: 700;
        cursor: pointer;
    }

    .glyphicon.glyphicon-minus.plusIcon {
        background: yellow;
        width: 25px;
        height: 25px;
        /* align-items: center; */
        justify-content: center;
        display: flex;
        margin: 0px 4px;
        border-radius: 50px;
        font-weight: 700;
        cursor: pointer;
    }

    input#flexCheckDefault::before {
        background-color: red;
    }

    input#flexCheckDefault {
        border-radius: 5px;
        outline: none;
        border: 1px solid gray;
    }

    .table-new tbody tr::after {
        content: '';
        width: 100%;
        position: absolute;
        left: 0;
        right: 0;
        background-color: #fff;
        height: 100%;
        z-index: 0;
        /* border: 1px solid yellow; */
        border-radius: 15px;
    }

    /* .checkbox:checked:before {
        background-color: yellow;
        outline: none;
        border: none;
        
    }

    input#flexCheckDefault {
        background-color: yellow;
        outline: none;
        border: none;
        
    } */

    tr.explode.hide {
    border: 2px solid yellow;
}

.table-new .tbody-1:after {
        content: '';
        width: 100%;
        position: absolute;
        left: 0;
        right: 0;
        background-color: #fff;
        height: 100%;
        z-index: 0;
        border: 1px solid yellow;
        border-radius: 15px;
        top:0px;
    }
</style>
      
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Reports</h3>
     </div>
     </div>

     

<div class="col-lg-12">
<div class="card mb-3 h-auto">
<div class="card-body">
<div class="row align-items-center mb-3">
<div class="col-lg-2 mb-2">
</div>
	   <div class="col-lg-3 mb-2" style="display: none;">
	   <div class="show-fillter">
	    <select id="inputState" class="form-select">
				<option>Show: A to Z</option>
				<option>Show: Z to A</option>
			</select>
	   </div>
	   </div>
	   
	   <div class="col-lg-5 mb-2 offset-lg-2" style="margin-left:415px;">
	   <div class="show-fillter" style="display:none;">
	   <input type="text" class="form-control" placeholder="Search Reports">
	   <button class="search-icon">
	   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
	   </button>
	   </div>
	   
	   </div>
	   
	   
	   
	   </div>
	   
	   
	   <ul class="nav report-tabs mb-3" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Service Report</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Inventory Report</button>
  </li>
   <li class="nav-item" role="presentation">
    <button class="nav-link" id="sale-tab" data-bs-toggle="tab" data-bs-target="#sale" type="button" role="tab" aria-controls="sele" aria-selected="false">Sales Report</button>
  </li>
  
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission" type="button" role="tab" aria-controls="commission" aria-selected="false">Commission Report</button>
  </li>

  <li class="nav-item" role="presentation">
    <button class="nav-link" id="recurring-tab" data-bs-toggle="tab" data-bs-target="#recurring" type="button" role="tab" aria-controls="recurring" aria-selected="false">Reoccuring Jobs Report</button>
  </li>

  <li class="nav-item" role="presentation">
    <button class="nav-link" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="false">Customer Report</button>
  </li>

  <li class="nav-item" role="presentation">
    <button class="nav-link" id="payroll-tab" data-bs-toggle="tab" data-bs-target="#payroll" type="button" role="tab" aria-controls="payroll" aria-selected="false">Payroll Report</button>
  </li>
  
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    
    <form action="{{ route('company.report') }}" method="post">
      @csrf
       @php
            if($sinceservice!=null) {
                $sinceservice = $sinceservice;
            }  else {
                $sinceservice = "";
            }
            if($untilservice!=null) {
                $untilservice = $untilservice;
            }  else {
                $untilservice = "";
            }
        @endphp
        <div class="row">
            <div class="col-md-3" style="padding:7px;">
              <label style="visibility:hidden;">Select Date Range</label>
              <input type="text" id="sinceservice" name="sinceservice" value="{{$sinceservice}}" class="form-control date1" placeholder="mm/dd/yyyy" readonly>
            </div>
            <div class="col-md-3" style="padding:7px;">
              <label style="visibility:hidden;">To Date</label>
              <input type="text" id="untilservice" name="untilservice" value="{{$untilservice}}" class="form-control date2" placeholder="mm/dd/yyyy" readonly>
            </div>
            <div class="col-md-3">
              <div class="side-h3">
                <button type="submit" class="btn btn-block button" style="width:100%;height: 40px;">Run</button>
              </div>
            </div>
    </form>
    <div class="col-md-3">
        <form id="comsearchservice" action="{{ route('company.servicefilter') }}" method="post">
          @csrf
          <input type="hidden" name="sinceservices" id="sinceservices" value="">
          <input type="hidden" name="untilservices" id="untilservices" value="">
          <div class="row">
          <div class="col-md-4">
          </div><div class="col-md-3">
          </div><div class="col-md-3">
          </div>
          <div class="col-md-3">
            <div class="side-h3">
            <button class="btn add-btn-yellow py-2 px-5 searchBtnDownservice" type="button" name="search" value="excel">{{ __('Export') }}</button>
            </div>
          </div>
        </div>
         </form>
    </div>
</div>
     <br>
    <div class="table-responsive">
	    <table id="example" class="table no-wrap table-new table-list align-items-center">
    	    <thead>
        	    <tr>
            	  <th>Ticket number</th>
            	  <th>Customer Name</th>
            	  <th>Service location</th>
            	  <th>Personnel</th>
            	  <th>Service Provided</th>
            	  <th>Cost</th>
            	  <th>Status</th>
        	    </tr>
    	    </thead>
	    <tbody>
        @foreach($servicereport as $key => $ticket)
            @php
              $explode_id = explode(',', $ticket->serviceid);
              $servicedata = App\Models\Service::select('servicename')
                ->whereIn('services.id',$explode_id)->get();
              if($ticket->payment_status!=null || $ticket->payment_mode!=null) {
                $payment_status = "Completed";
              } else {
                $payment_status = "Pending";
              }
              if($ticket->payment_mode!=null) {
                $paid_status = '-'.$ticket->payment_mode;
              } else {
                $paid_status = "";
              }
            @endphp
	    <tr>
    	  <td><a href="{{url('company/quote/ticketdetail/')}}/{{$ticket->id}}" target="_blank">#{{$ticket->id}}</a></td>
    	  <td style="white-space: initial;">{{$ticket->customername}}</td>
    	  <td style="white-space: initial;">{{$ticket->address}}</td>
    	  <td>{{$ticket->personnelname}}</td>
    	  <td>@php
              $i=0;
            @endphp
            @foreach($servicedata as $servicename)
                @php
                  if(count($servicedata) == 0){
                    $servicename = '-';
                  } else {
                    $servicename = $servicename->servicename;
                  }
                @endphp

                {{$servicename}}
              @if(count($servicedata)>1) 
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $ticket->serviceid }}">
                  <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
                </svg>
                @endif
                @php
                $i=1; break;
              @endphp
            @endforeach</td>
    	  <td>{{$ticket->price}}</td>
    	  <td><a href="#" class="btn-incompleted btn-common">{{$payment_status}} {{$paid_status}}</a>
          </td>
	  
	    </tr>
	   @php
          $i++;
        @endphp
    @endforeach
	  </tbody>
	</table>
	</div>
  </div>

  <!-- recurring job report start -->
    <div class="tab-pane fade show" id="recurring" role="tabpanel" aria-labelledby="recurring-tab">
    <form id="recurringreport" action="{{ route('company.report') }}" method="post">
     @csrf
    <div class="row">
        <!-- <input type="hidden" name="fhiddenid" id="fhiddenid" value=""> -->
        @php
            if($sincerecur!=null) {
                $sincerecur = $sincerecur;
            }  else {
                $sincerecur = "";
            }
            if($untilrecur!=null) {
                $untilrecur = $untilrecur;
            }  else {
                $untilrecur = "";
            }
        @endphp
        <div class="col-md-3" style="padding:7px;">
         <label style="visibility:hidden;">Select Date Range</label>
         <input type="text" id="sincerecur" name="sincerecur" value="{{$sincerecur}}" class="form-control date1" placeholder="mm/dd/yyyy" readonly>
       </div>
       <div class="col-md-3" style="padding:7px;">
         <label style="visibility:hidden;">To Date</label>
         <input type="text" id="untilrecur" name="untilrecur" value="{{$untilrecur}}" class="form-control date2" placeholder="mm/dd/yyyy" readonly>
       </div>
        <div class="col-md-3">
           <div class="side-h3">
            <select class="form-select precurring" name="frequencyid" id="frequencyid" required="">
               <option value="All"> All </option>
               @foreach($frequency as $key => $value)
                    <option value="{{$value->tenturename}}" @if(@$frequencyid ==  $value->tenturename) selected @endif> {{$value->tenturename}}</option>
                    @endforeach
            </select>
           </div>
        </div>
      <div class="col-md-3">
      <div class="side-h3">
        <button type="submit" class="btn btn-block button" style="width:45%;height: 40px;">Run</button>
      </div>
    </div>
    </div>
    </form>
    <form id="comsearch2" action="{{ route('company.recuringfilter') }}" method="post">
      @csrf
      <div class="row">
      <div class="col-md-4">
      </div><div class="col-md-3">
      </div><div class="col-md-3">
      </div>
      <input type="hidden" name="frequencytype" id="frequencytype" value="">
      <input type="hidden" name="sincerecuring" id="sincerecuring" value="">
      <input type="hidden" name="untilrecuring" id="untilrecuring" value="">
      <div class="col-md-2">
        <button class="btn add-btn-yellow py-2 px-4 searchBtnDown2" type="button" name="search" value="excel" style="margin-top:-127px;margin-left:47px;">{{ __('Export') }}</button>
       </div>
    </div>
     </form>
    <div class="table-responsive">
        <table id="examplerecurring" class="table no-wrap table-new table-list align-items-center">
            <thead>
                <tr>
                  <th>Ticket Number</th>
                  <th>Date</th>
                  <th>Frequency</th>
                  <th>Service Address</th>
                </tr>
            </thead>
        <tbody>
        @foreach($recurringreport as $key => $ticket)
        <tr>
          <td><a href="{{url('company/quote/ticketdetail/')}}/{{$ticket->id}}" target="_blank">#{{$ticket->id}}</a></td>
          <td>{{$ticket->givenstartdate}}</td>
          <td>{{$ticket->frequency}}</td>
          <td style="">{{$ticket->address}}</td>
        </tr>
      
    @endforeach
      </tbody>
    </table>
    </div>
  </div>
  <!-- end -->

  <!-- Customer Report start -->
    <div class="tab-pane fade show" id="customer" role="tabpanel" aria-labelledby="customer-tab">
    <form id="customerreport" action="{{ route('company.report') }}" method="post">
     @csrf
     <input type="hidden" name="customerids" id="customerids" value="">
    <div class="row">
        <div class="col-md-3"><div class="side-h3">Customer Report</div></div><div class="col-md-3"></div>
        <div class="col-md-3">
           <div class="side-h3">
            <select class="form-control customerreport selectpicker" data-placeholder="Select customer or Type in" data-live-search="true" name="customerid" id="customerid" required="">
                @foreach($customerData as $key => $value)
                    <option value="{{$value->id}}" @if(@$cids ==  $value->id) selected @endif> {{$value->customername}}</option>
                @endforeach
            </select>
           </div>
        </div>
      <div class="col-md-3">
      <div class="side-h3">
        <button type="submit" class="btn btn-block button searchbtncustomer" style="width:45%;height: 40px;">Run</button>
      </div>
    </div>
    </div>
    </form>
    <div class="table-responsive">
        <table id="examplerecurring" class="table no-wrap table-new table-list align-items-center">
            <thead>
                <tr>
                  <th>Connected Addresses<th>
                  <th>Open Invoices</th>
                  <th>Balance (amount owed)</th>
                </tr>
            </thead>
        <tbody>
        @foreach($customerreport as $key => $value)
        <tr>
          <td>{{$value->address}}</td>
          <td></td>
          <td><a href="{{url('company/report/view/')}}/{{$value->customerid}}/{{encrypt($value->address)}}" class="user-hover" target="_blank">{{$value->counttotal}}</a></td>
          <td>{{$value->quoteprice}}</td>
        </tr>
      
    @endforeach
      </tbody>
    </table>
    </div>
  </div>
  <!-- end -->

<!-- Payroll Report start -->
    <div class="tab-pane fade show" id="payroll" role="tabpanel" aria-labelledby="payroll-tab">
    <form id="payroll" method="post" action="{{route('company.report') }}" class="row pe-0" id="text">
      @csrf

  <div class="row">
    @php
    if($from!=null) {
        $from = $from;
    }  else {
        $from = "";
    }
    if($to!=null) {
        $to = $to;
    }  else {
        $to = "";
    }
    @endphp
    <div class="col-md-3" style="padding:7px;">
     <label style="visibility:hidden;">Select Date Range</label>
     <input type="text" id="sincepayroll" name="sincepayroll" value="{{$sincepayroll}}" class="form-control date1" placeholder="mm/dd/yyyy" readonly>
   </div>
   <div class="col-md-3" style="padding:7px;">
     <label style="visibility:hidden;">To Date</label>
     <input type="text" id="untilpayroll" name="untilpayroll" value="{{$untilpayroll}}" class="form-control date2" placeholder="mm/dd/yyyy" readonly>
   </div>
   

 <input type="hidden" name="pyrollhiddenid" id="pyrollhiddenid" value="">
    <div class="col-md-3">
       <div class="side-h3">
        <select class="form-select pyrolluser" name="selectpayrollid" id="selectpayrollid" required="">
           <option value="All"> All </option>
           @foreach($pdata1 as $key => $value)
                <option value="{{$value->id}}" @if(@$selectpayrollid ==  $value->id) selected @endif> {{$value->personnelname}}</option>
                @endforeach
        </select>
       </div>
    </div>
  
    <div class="col-md-3">
      <div class="side-h3">
        <button type="submit" class="btn btn-block button" style="width:45%;height: 40px;">Run</button>
      </div>
    </div>
</div>
</form> 

<form id="payrollexport" action="{{ route('company.recuringfilter') }}" method="post">
      @csrf
      <input type="hidden" name="pyrollhiddenid1" id="pyrollhiddenid1" value="">
      <input type="hidden" name="sincepayroll1" id="sincepayroll1" value="">
      <input type="hidden" name="untilpayroll1" id="untilpayroll1" value="">

      <div class="row">
      <div class="col-md-4">
      </div><div class="col-md-3">
      </div><div class="col-md-3">
      </div>
      <div class="col-md-2" style="display:none;">
      <button class="btn add-btn-yellow py-2 px-5 searchBtnDownPyroll" type="button" name="search" value="" style="margin-top:-127px;margin-left:47px;">{{ __('Export') }}</button>
      </div>
      </div>
    </form>  
    <div class="">
        <table id="" class="table no-wrap table-new table-list align-items-center">
            <thead>
                <tr>
                  <th>Personnel <br>Name<th>
                  <th>Date<th>
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Hours</th>
                  <th>OT Hours</th>
                  <th>PTO Hours</th>
                  <th>Commission Amount</th>
                </tr>
            </thead>
        <tbody>
        @foreach($resultsPyroll as $key => $value)
            @php
            if($sincepayroll!=null && $untilpayroll!=null) {
                $startDatePayroll = date('Y-m-d', strtotime($sincepayroll));
                $endDatePayroll = date('Y-m-d', strtotime($untilpayroll));
                $pinfo = App\Models\Workerhour::select('workerhour.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'workerhour.workerid')->where('workerhour.workerid',$value->workerid)
                ->whereBetween('workerhour.date1', [$startDatePayroll, $endDatePayroll])->orderBy('workerhour.id','desc')->get();
            } else {
                $pinfo = App\Models\Workerhour::select('workerhour.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'workerhour.workerid')->where('workerhour.workerid',$value->workerid)
                ->orderBy('workerhour.id','desc')->get();
            }
                $totalHours = 0;
                $totalMinutes = 0;
             @endphp 
            @foreach($pinfo as $key1 => $value1)
                @php
                    $parts = explode(' ', $value1->totalhours);
                    $hours = 0;
                    $minutes = 0;
                    foreach ($parts as $part) {
                        if (strpos($part, 'h') !== false) {
                            $hours += (int) trim($part, 'h');
                        } elseif (strpos($part, 'm') !== false) {
                            $minutes += (int) trim($part, 'm');
                        }
                    }

                    $totalHours += $hours;
                    $totalMinutes += $minutes;
                @endphp
                <tr>
                  <td>{{$value1->personnelname}}</td>
                  <td></td>
                  <td>{{$value1->date1}}</td>
                  <td></td>
                  <td>{{$value1->starttime}}</td>
                  <td>{{$value1->endtime}}</td>
                  <td>{{$value1->totalhours}}</td>
                  <td>0h</td>
                  <td>0h</td>
                  <td>0</td>
                </tr>
            @endforeach
             @php
                // Convert any extra minutes to hours
                $extraHours = floor($totalMinutes / 60);
                $totalHours += $extraHours;
                $totalMinutes %= 60;
                @endphp
             <tr>
                <td></td>
                <td></td>
                <td><strong>Total</strong></td>
                <td></td><td></td><td></td>
                <td>{{ $totalHours }}h {{$totalMinutes}} m</td>
            </tr>
           
        @endforeach
      </tbody>
    </table>
    </div>
  </div>
  <!-- end -->
  
<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

    <form action="{{ route('company.report') }}" method="post">
      @csrf
       @php
            if($sinceproduct!=null) {
                $sinceproduct = $sinceproduct;
            }  else {
                $sinceproduct = "";
            }
            if($untilproduct!=null) {
                $untilproduct = $untilproduct;
            }  else {
                $untilproduct = "";
            }
        @endphp
        <div class="row">
        <div class="col-md-3" style="padding:7px;">
          <label style="visibility:hidden;">Select Date Range</label>
          <input type="text" id="sinceproduct" name="sinceproduct" value="{{$sinceproduct}}" class="form-control date1" placeholder="mm/dd/yyyy" readonly>
        </div>
        <div class="col-md-3" style="padding:7px;">
          <label style="visibility:hidden;">To Date</label>
          <input type="text" id="untilproduct" name="untilproduct" value="{{$untilproduct}}" class="form-control date2" placeholder="mm/dd/yyyy" readonly>
        </div>
        <div class="col-md-3">
          <div class="side-h3">
            <button type="submit" class="btn btn-block button" style="width:100%;height: 40px;">Run</button>
          </div>
        </div>
    </form>
    <div class="col-md-3">
    <form id="comsearchproduct" action="{{ route('company.productfilter') }}" method="post">
       @csrf
          <input type="hidden" name="sinceproducts" id="sinceproducts" value="">
          <input type="hidden" name="untilproducts" id="untilproducts" value="">
          <div class="row">
          <div class="col-md-4">
          </div><div class="col-md-3">
          </div><div class="col-md-3">
          </div>
          <div class="col-md-3">
            <div class="side-h3">
                <button class="btn add-btn-yellow py-2 px-5 searchBtnDownproduct" type="button" name="search" value="excel">{{ __('Export') }}</button>
            </div>
          </div>
        </div>
    </form>
     </div>
 </div>
    <div class="table-responsive">
        <table id="exampleproduct" class="table no-wrap table-new table-list align-items-center">
            <thead>
                <tr>
                  <th>Product</th>
                  <th>Units Sold</th>
                  <th>Date of Last Sale</th>
                  <th>Remain Stock</th>
                  <th>Total Cost</th>
                  <th>Top Seller (Personnel)</th>
                </tr>
            </thead>
        <tbody>
        @foreach($productinfo as $key => $ticket)
         @php
            $pinfo = App\Models\Personnel::select('personnelname')->where('id',@$personnelids[$key])->first();
            $lastdate = App\Models\Quote::whereRaw('FIND_IN_SET("'.$ticket->id.'",product_id)')->where('quote.userid',$auth_id)->whereIn('ticket_status',array('2','3','4'))->where('payment_status','!=',null)->where('payment_mode','!=',null)->where('parentid', '=',"")->orderBy('id','desc')->first();
         @endphp 
        <tr>
          <td>{{@$ticket->productname}}</td>
          <td>{{@$numerickey[$key]}}</td>
          <td>{{@$lastdate->updated_at}}</td>
          <td>{{@$ticket->quantity}}</td>
          <td>{{@$ticket->price*$numerickey[$key]}}</td>
          <td>{{@$pinfo->personnelname}}</td>
      </tr>
    @endforeach
      </tbody>
    </table>
    </div>
  </div>
  <div class="tab-pane fade" id="sale" role="tabpanel" aria-labelledby="sale-tab">
    <form action="{{ route('company.report') }}" method="post">
      @csrf
       @php
            if($sincesale!=null) {
                $sincesale = $sincesale;
            }  else {
                $sincesale = "";
            }
            if($untilsale!=null) {
                $untilsale = $untilsale;
            }  else {
                $untilsale = "";
            }
        @endphp
        <div class="row">
            <div class="col-md-3" style="padding:7px;">
              <label style="visibility:hidden;">Select Date Range</label>
              <input type="text" id="sincesale" name="sincesale" value="{{$sincesale}}" class="form-control date1" placeholder="mm/dd/yyyy" readonly>
            </div>
            <div class="col-md-3" style="padding:7px;">
              <label style="visibility:hidden;">To Date</label>
              <input type="text" id="untilsale" name="untilsale" value="{{$untilsale}}" class="form-control date2" placeholder="mm/dd/yyyy" readonly>
            </div>
        <div class="col-md-3">
          <div class="side-h3">
            <button type="submit" class="btn btn-block button" style="width:100%;height: 40px;">Run</button>
          </div>
        </div>
    </form>
    <div class="col-md-3">
        <form id="comsearchsales" action="{{ route('company.salesfilter') }}" method="post">
          @csrf
          <input type="hidden" name="sincesales" id="sincesales" value="">
          <input type="hidden" name="untilsales" id="untilsales" value="">

          <div class="row">
          <div class="col-md-4">
          </div><div class="col-md-3">
          </div><div class="col-md-3">
          </div>
            <div class="col-md-3">
              <div class="side-h3">
                <button class="btn add-btn-yellow py-2 px-5 searchBtnDownsales" type="button" name="search" value="excel">{{ __('Export') }}</button>
              </div>
            </div>
          </div>
        </form>
    </div>
    </div>
   <div class="col-md-12">
<div class="card">
<div class="card-body">
<div class="col-lg-12">
<div class="table-responsive">
 <table id="example111" class="table no-wrap table-new table-list no-footer" style="position: relative;">
     <thead>
           <tr>
              <th>Date</th>
              <th># of Tickets</th>
              <th>Service Sold <br>Total ($)</th>
              <th>Product Sold <br>Total ($)</th>
              <th>Ticket Total</th>
              <th>Billing Total</th>
            </tr>
     </thead>
        <tbody class="tbody-1">
            @foreach($salesreport as $key => $value)
         @php
            if($value->tickettotalprice==null || $value->tickettotalprice==0 || $value->tickettotalprice=="") {
              $newprice = $value->totalprice;
            } else {
              $newprice = $value->tickettotalprice;
            }

           $arrayv = explode(",",$value->serviceid);
           $countsf = array_count_values($arrayv);
           arsort($countsf);

           $totalssold = 0;
           foreach($countsf as $key1=>$value1) {
                $servicedata = App\Models\Service::select('price')
                    ->where('id',$key1)->get();
                $totalssold =  $totalssold+@$servicedata[0]->price;
           }

           $parrayv = explode(",",$value->product_id);
           $pcountsf = array_count_values($parrayv);
           arsort($pcountsf);
           
           $totalpsold = 0;
           foreach($pcountsf as $key11=>$value11) {
                $pdata = App\Models\Inventory::select('price')
                    ->where('id',$key11)->get();
                $totalpsold =  $totalpsold+@$pdata[0]->price;
           }

          @endphp
               <tr class="sub-container">
                    <td style="display: flex;">
                        <div class="glyphicon glyphicon-plus plusIcon">+</div>
                            <div class="glyphicon glyphicon-minus plusIcon" style="display:none">-</div>{{date('m-d-Y', strtotime($value->date))}}</td>
                      <td>{{$value->totalticket}}</td>
                      <td>{{$totalssold}}</td>
                      <td>{{$totalpsold}}</td>
                      <td>{{number_format((float)$newprice, 2, '.', '')}}</td>
                      <td>{{number_format((float)$value->totalprice, 2, '.', '')}}</td>
               </tr>
               
                <tr class="explode hide" style="display:none;">
                    <td colspan="8" id="toggle_text">
                        <table class="table table-condensed">
                            <thead>
                                <tr style="font-family: system-ui;">
                                    <th>Personnel</th>
                                    <th>Service Sold</th>
                                    <th>Product Sold</th>
                                    <th>Ticket Total</th>
                                    <th>Billing Total</th>
                                 </tr>
                            </thead>
                            <tbody>
                                 @php
                                    $billingData = \App\Models\Quote::select('quote.id','quote.parentid','quote.serviceid','quote.product_id','quote.price','quote.tickettotal','quote.givendate','quote.etc','quote.payment_status','quote.personnelid','quote.primaryname','quote.tax', 'customer.customername', 'customer.email','personnel.personnelname','services.servicename')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',auth()->user()->id)->whereColumn('quote.personnelid','quote.primaryname')->whereIn('quote.ticket_status',['3','5','4'])->whereBetween('quote.givenstartdate', [$value->date, $value->date])->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->orderBy('quote.id','desc')->get();
                                @endphp
                                @foreach($billingData as $key1=>$value1)
                                @php
                                    if($value1->tickettotal==null || $value1->tickettotal==0 || $value1->tickettotal=="") {
                                      $newprice = $value1->price;
                                    } else {
                                      $newprice = $value1->tickettotal;
                                    }
                                    $ids=$value1->id;
                                    if(!empty($value1->parentid))
                                    {
                                        $ids=$value1->parentid;

                                    }

                                    $arrayvs = explode(",",$value1->serviceid);
                                    $countsfs = array_count_values($arrayvs);
                                    arsort($countsfs);

                                    $totalssolds = 0;
                                    foreach($countsfs as $key2=>$value2) {
                                        $servicedata = App\Models\Service::select('price')
                                            ->where('id',$key2)->get();
                                        $totalssolds =  $totalssolds+@$servicedata[0]->price * $value2;
                                    }

                                    $parrayvp = explode(",",$value1->product_id);
                                    $pcountsfp = array_count_values($parrayvp);
                                    arsort($pcountsfp);

                                    $totalpsoldp = 0;
                                    foreach($pcountsfp as $key111=>$value111) {
                                        $pdata = App\Models\Inventory::select('price')
                                            ->where('id',$key111)->get();
                                        $totalpsoldp =  $totalpsoldp+@$pdata[0]->price * $value111;
                                    }


                                  @endphp
                                <tr style="font-size: 17px; border:none; background:white;">
                                    <td>@if($value1->personnelname!="")
                                            {{@$value1->personnelname}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{$totalssolds}}</td>
                                    <td>{{$totalpsoldp}}</td>
                                    <td>{{number_format((float)$newprice, 2, '.', '')}}</td>
                                    <td>{{number_format((float)$value1->price, 2, '.', '')}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
               @endforeach 
                </tbody>
                
           </tbody>
    </table>
 </div>
</div>
</div>
</div>
</div> 
    
  </div>
  <div class="tab-pane fade" id="commission" role="tabpanel" aria-labelledby="commission-tab">

  <form id="commission" method="post" action="{{route('company.report') }}" class="row pe-0" id="text">
      @csrf

  <div class="row">
    @php
    if($from!=null) {
        $from = $from;
    }  else {
        $from = "";
    }
    if($to!=null) {
        $to = $to;
    }  else {
        $to = "";
    }
    @endphp
    <div class="col-md-3" style="padding:7px;">
     <label style="visibility:hidden;">Select Date Range</label>
     <input type="text" id="since" name="since" value="{{$from}}" class="form-control date1" placeholder="mm/dd/yyyy" readonly>
   </div>
   <div class="col-md-3" style="padding:7px;">
     <label style="visibility:hidden;">To Date</label>
     <input type="text" id="until" name="until" value="{{$to}}" class="form-control date2" placeholder="mm/dd/yyyy" readonly>
   </div>
   

 <input type="hidden" name="phiddenid" id="phiddenid" value="">
    <div class="col-md-3">
       <div class="side-h3">
        <select class="form-select puser" name="frequencyid" id="pid" required="">
           <option value="All"> All </option>
           @foreach($pdata1 as $key => $value)
                <option value="{{$value->id}}" @if(@$personnelid ==  $value->id) selected @endif> {{$value->personnelname}}</option>
                @endforeach
        </select>
       </div>
    </div>
  
    <div class="col-md-3">
      <div class="side-h3">
        <button type="submit" class="btn btn-block button" style="width:45%;height: 40px;">Run</button>
      </div>
    </div>
</form>

    <form id="comsearch1" action="{{ route('company.commissionreport') }}" method="post">
      @csrf
      <input type="hidden" name="pidd" id="pidd" value="">
      <input type="hidden" name="sincedd" id="sincedd" value="">
      <input type="hidden" name="untildd" id="untildd" value="">

      <div class="row">
      <div class="col-md-4">
      </div><div class="col-md-3">
      </div><div class="col-md-3">
      </div>
      <div class="col-md-2">
      <button class="btn add-btn-yellow py-2 px-5 searchBtnDown1" type="button" name="search" value="" style="margin-top:-127px;margin-left:47px;">{{ __('Export') }}</button>
      </div>
      </div>
    </form>
<div class="col-md-12">
 <div class="card">
<div class="card-body">

<div class="col-lg-12">
<div class="table-responsive">
 <table id="example1" class="table no-wrap table-new table-list no-footer" style="position: relative;">
     <thead>
           <tr>
               <th style="font-weight:400;font-size:15px;">Personnel Name</th>
               <th style="font-weight:400;font-size:15px;">Tickets Worked</th>
               <th style="font-weight:400;font-size:15px;">Flat Amount</th>
               <th style="font-weight:400;font-size:15px;">Variable Amount</th>
               <th style="font-weight:400;font-size:15px;">Totol Payout</th>
               <th colspan="" style="font-weight:400;font-size:15px;">Action</th>
           </tr>
     </thead>
        @foreach($tickedata as $key => $value)
            <tbody class="tbody-1">
                @php
                  $ttlflat = 0;
                  $ptamounttotal = 0;
                  $explode_id = explode(',', $value->serviceid);
                  $pexplode_id = 0;
                  $servicedata = App\Models\Service::select('servicename','price')
                    ->whereIn('services.id',$explode_id)->get();
                    if($value->product_id!=null || $value->product_id!="") {
                        $pexplode_id = explode(',', $value->product_id);
                    }
                  if($pexplode_id!=0) {
                    $pdata = App\Models\Inventory::select('id','price')
                    ->whereIn('products.id',$pexplode_id)->get();
                  }
                  

                     $ttlflat=0;
                     
                     $ttlflat2 = 0;

                    if(count($amountall)>0) {
                        if($amountall[0]->allspvalue==null) {
                            foreach($explode_id as $servicekey =>$servicevalue) {

                              foreach($comisiondataamount->service as $key=>$sitem)
                              {
                                if($sitem->id==$servicevalue && $sitem->price!=0)
                                {
                                    //echo $servicevalue."==={$sitem->id} price==".  $sitem->price."<br>";
                                    $ttlflat2 += $sitem->price;
                                }

                              }
                            } 
                            
                            $ttlflat1 = 0;
                            if($pexplode_id!=0) {
                                foreach($pexplode_id as $servicekey =>$servicevalue) {
                                    foreach($comisiondataamount->product as $key=>$sitem1)
                                    {
                                        if($sitem1->id==$servicevalue && $sitem1->price!=0)
                                        {
                                                   //echo  $servicevalue."==={$sitem1->id} price==".  $sitem1->price."<br>";
                                            $ttlflat1 += $sitem1->price;
                                        }
                                    }  
                                } 
                            } 
                             $ttlflat =$ttlflat1+$ttlflat2;
                        } else {
                            $flatvalue = $amountall[0]->allspvalue;

                            $flatv = $flatvalue*count($explode_id);

                            if($value->product_id!=null || $value->product_id!="") {
                                $pvalue = $flatvalue *count($pexplode_id);
                            } else {
                                $pvalue = 0;
                            }
                            $ttlflat = $flatv+$pvalue;

                        }
                    }

                    if(count($percentall)>0) {
                        $ptamount = 0;
                        $ptamount1 = 0;
                        if($percentall[0]->allspvalue==null) {
                            foreach($explode_id as $servicekey =>$servicevalue) {
                                foreach($comisiondatapercent->service as $key=>$sitem)
                                {
                                  if($sitem->id==$servicevalue && $sitem->price!=0)
                                  {
                                    //echo $servicevalue."==={$sitem->id}price==".  $sitem->price."<br>";

                                    $servicedata = App\Models\Service::select('servicename','price')
                                    ->where('services.id',$sitem->id)->first();

                                    $ptamount += $servicedata->price*$sitem->price/100;               
                                  }
                                }
                            } 
                          if($pexplode_id!=0) { 
                            foreach($pexplode_id as $key=>$pid) {
                                foreach($comisiondatapercent->product as $key=>$sitem)
                                {
                                  if($sitem->id==$pid && $sitem->price!=0)
                                  {
                                    $pdata = App\Models\Inventory::select('id','price')->where('products.id',$sitem->id)->first();
                                    
                                    @$ptamount1 += @$pdata->price*@$sitem->price/100;               
                                  }
                                }
                            }
                         }
                        } else {
                            foreach($explode_id as $key=>$serviceid) {
                                $servicedata = App\Models\Service::select('servicename','price')
                                ->where('services.id',$serviceid)->first();
                                $ptamount += $servicedata->price*$percentall[0]->allspvalue/100;               
                             }   
                            $ptamount1 = 0;
                            if($pexplode_id!=0) {
                                foreach($pexplode_id as $key=>$pid) {
                                    $pdata = App\Models\Inventory::select('id','price')->where('products.id',$pid)->first();
                                    @$ptamount1 += @$pdata->price*@$percentall[0]->allspvalue/100;               
                                } 
                            }
                           
                        }  
                        $ptamounttotal =$ptamount+$ptamount1;
                    }
                @endphp
                <tr class="sub-container">
                    <td style="display: flex;">
                                                    <div class="glyphicon glyphicon-plus plusIcon">+</div>
                                                    <div class="glyphicon glyphicon-minus plusIcon" style="display:none">-</div>
                        {{$value->personnelname}}
                    </td>
                    <td>{{$value->counttotal}}</td>
                    <td>${{@$ttlflat}}</td>
                    <td>${{@$ptamounttotal}}</td>
                    <td>${{@$ttlflat+@$ptamounttotal}}</td>
                    <td>
                        <!-- <i class="fa fa-cog" aria-hidden="true"></i> -->
                        <form id="comsearch" action="{{ route('company.commissiondownload') }}" method="post">
                          @csrf
                          <input type="hidden" name="persid" id="persid" value="{{$value->personnelid}}">
                          <input type="hidden" name="sinced" id="sinced" value="">
                          <input type="hidden" name="untild" id="untild" value="">

                          <div class="row">
                          <div class="col-md-4">
                          </div><div class="col-md-3">
                          </div><div class="col-md-3">
                          </div>
                          <div class="col-md-2">
                           <button class="searchBtnDown" type="button" name="searchdownload" id="search" value="excel"><i class="fa fa-download" aria-hidden="true"></i></button>
                          </div>
                          </div>
                         </form>
                     </td>
                </tr>
                <tr class="explode hide" style="display:none;">
                    <td colspan="8" id="toggle_text">
                        <table class="table table-condensed">
                            <thead>
                                <tr style="font-family: system-ui;">
                                    <th>Date</th>
                                    <th>Ticket#</th>
                                    <th>Services</th>
                                    <th>Products</th>
                                    <th>Flat Amount</th>
                                    <th>Variable Amount</th>
                                    <th>Total Payout</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if($from!=null && $to!=null) {
                                        $since = date('Y-m-d', strtotime($from));
                                        $until = date('Y-m-d', strtotime($to));
                                        @$tickedatadetailsdata = \App\Models\Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$value->personnelid)->where('quote.ticket_status',3)->whereColumn('quote.personnelid','quote.primaryname')->whereBetween('quote.ticketdate', [$since, $until])->get();
                                    } else {
                                        @$tickedatadetailsdata = \App\Models\Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$value->personnelid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->get();
                                    }
                                @endphp
                                @foreach($tickedatadetailsdata as $key=>$value)
                                    @php
                                        $ids=$value->id;
                                        if(!empty($value->parentid))
                                        {
                                            $ids=$value->parentid;

                                        }
                        $explode_id = explode(',', $value->serviceid);
                        $servicedata = App\Models\Service::select('servicename','price')
                                        ->whereIn('services.id',$explode_id)->get();
                        $pexplode_id = 0;
                        $productname = "--";
                        if($value->product_id!=null || $value->product_id!="") {   
                          $pexplode_id = explode(',', $value->product_id);
                           $pdata = App\Models\Inventory::select('id','price','productname')
                            ->whereIn('products.id',$pexplode_id)->get();
                        }
                                     
                                        $ttlflat = 0;
                                        $ptamounttotal = 0;
                                        if(count($amountall)>0) {

                                            if($amountall[0]->allspvalue==null) {
                                                foreach($servicedata as $key1=>$value1) {
                                                  $sname[] = $value1->servicename;
                                                   $servname = implode(',',$sname);
                                                }
                                                if($pexplode_id!=0) {
                                                foreach($pdata as $key2=>$value2) {
                                                   @$pname[] = @$value2->productname;
                                                   $productname = implode(',',$pname);
                                                 }
                                                }

                                                $ttlflat1 = 0;

                                                foreach($explode_id as $servicekey =>$servicevalue) {
                                                    foreach($comisiondataamount->service as $key=>$sitem)
                                                    {
                                                        if($sitem->id==$servicevalue && $sitem->price!=0)
                                                        {
                                                        //echo $servicevalue."==={$sitem->id} price==".  $sitem->price."<br>";
                                                          $ttlflat1 += $sitem->price;
                                                        }
                                                    }
                                                }

                                                $ttlflat2 = 0;
                                            if($pexplode_id!=0) {
                                                foreach($pexplode_id as $servicekey =>$servicevalue) {
                                                    foreach($comisiondataamount->product as $key=>$sitem1)
                                                    {
                                                        if($sitem1->id==$servicevalue && $sitem1->price!=0)
                                                        {
                                                        //echo $servicevalue."==={$sitem1->id}price==".  $sitem1->price."<br>";
                                                          $ttlflat2 += $sitem1->price;
                                                        }
                                                    }
                                                }
                                            }
                                                $ttlflat = $ttlflat1 +$ttlflat2;
                                                
                                            } else {

                                                 foreach($servicedata as $key1=>$value1) {
                                                  $sname[] = $value1->servicename;
                                                   $servname = implode(',',$sname);
                                                }
                                                if($pexplode_id!=0) {
                                                foreach($pdata as $key2=>$value2) {
                                                   @$pname[] = @$value2->productname;
                                                   $productname = implode(',',$pname);
                                                 }
                                                }

                                        $flatvalue = $amountall[0]->allspvalue;

                                        $flatv = $flatvalue*count($explode_id); 
                                        if($pexplode_id!=0) {
                                            $pvalue  =  $flatvalue*count($pexplode_id);
                                        } else {
                                            $pvalue = 0;  
                                        }
                                        
                                        $ttlflat = $flatv+$pvalue;
                                    }
                                }

                                        if(count($percentall)>0) {
                                         $ptamount = 0;
                                         $sname = array();
                                         $ptamount1 = 0;
                                         $pname = array();

                                         if($percentall[0]->allspvalue == null) {
                                            
                                            foreach($explode_id as $servicekey =>$servicevalue) {
                                                foreach($comisiondatapercent->service as $key=>$sitem)
                                                {
                                                  if($sitem->id==$servicevalue && $sitem->price!=0)
                                                  {
                                                    $servicedata = App\Models\Service::select('servicename','price')
                                                    ->where('services.id',$sitem->id)->first();
                                                    $ptamount += $servicedata->price*$sitem->price/100;               
                                                  }
                                                }
                                            }
                                            if($pexplode_id!=0) {
                                                foreach($pexplode_id as $key=>$pid) {
                                                    foreach($comisiondatapercent->product as $key=>$sitem)
                                                    {
                                                      if($sitem->id==$pid && $sitem->price!=0)
                                                      {
                                                        $pdata = App\Models\Inventory::select('id','price')->where('products.id',$sitem->id)->first();
                                                        
                                                        @$ptamount1 += @$pdata->price*@$sitem->price/100;               
                                                      }
                                                    }
                                                }
                                            }
                                         } else {
                                         foreach($servicedata as $key1=>$value1) {
                                           $ptamount += $value1->price*$percentall[0]->allspvalue/100;
                                           $ptamount222 =  $ptamount *count($servicedata);
                                           $sname[] = $value1->servicename;
                                           $servname = implode(',',$sname);
                                         }
                                        if($pexplode_id!=0) { 
                                         foreach($pdata as $key2=>$value2) {

                                           @$ptamount1 += @$value2->price*@$percentall[0]->allspvalue/100;
                                           @$pname[] = @$value2->productname;
                                           $productname = implode(',',$pname);
                                         }
                                       }
                                     }
                                         $ptamounttotal =$ptamount+$ptamount1;
                                         
                                        }
                                    @endphp
                                    <tr style="font-size: 17px; border:none; background:white;">
                                        <td>{{$value->ticketdate}}</td>
                                        <td>{{$ids}}</td>
                                        <td >{{Str::limit(@$servname, 20)}}</td>
                                        <td>{{Str::limit(@$productname, 20)}}</td>
                                        <td>${{@$ttlflat}}</td>
                                        <td>${{@$ptamounttotal}}</td>
                                        <td>${{@$ttlflat+@$ptamounttotal}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            @endforeach
           </tbody>
                       
     
   </table>
 </div>
</div>


</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
     
<div class="modal fade" id="service-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       <div id="viewservicelistdata"></div>
    </div>
  </div>
</div>
@endsection

@section('script')
<!-- for datepicker -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $('.dropify').dropify();
    $(document).ready(function() {
      $('#example1').DataTable({
       "order": [[ 0, "desc" ]]
      });
      $('#exampleproduct').DataTable({
       "order": [[ 0, "desc" ]]
      });
      $('#examplesaletab').DataTable({
       "order": [[ 0, "desc" ]]
      });
      $("#examplerecurring").DataTable({
       "order": [[ 0, "desc" ]] 
      });
    });

    $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(".plusIcon").on("click",function() {
      var obj = $(this);
      if( obj.hasClass("glyphicon-plus") ){
        obj.hide();
        obj.next().show();            
        obj.parent().parent().next().show();
      }else{
         obj.hide();
         obj.prev().show();
         obj.parent().parent().next().hide();
      }
    });

    $(".exploder1").click(function () {
        //$('#toggle_icon').text('-');

        $(this).children("span").toggleClass("glyphicon-search glyphicon-zoom-out");

        $(this).closest("tr").next("tr").toggleClass("hide");

        if ($(this).closest("tr").next("tr").hasClass("hide")) {
            $(this).closest("tr").next("tr").children("td").slideUp();
        }
        else {
            $(this).closest("tr").next("tr").children("td").slideDown(350);
        }
    });

    $('.puser').on('change', function() {
      var pid = this.value;
      $("#phiddenid").val(pid);
      this.form.submit();
    });
    $('.precurring').on('change', function() {
      // var pid = this.value;
      // $("#fhiddenid").val(pid);
      $("#recurringreport").submit();
    });

    $('.customerreport').on('change', function() {
        cid = $("#customerid").val();
        $("#customerids").val(cid);
        $("#customerreport").submit();
    });
    
    $(function() {
        var lastTab = localStorage.getItem('lastTab');
        $('#myTab').removeClass('hidden');
        if (lastTab) {
            $('[data-bs-target="' + lastTab + '"]').tab('show');
            localStorage.removeItem('lastTab');
        }
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            localStorage.setItem('lastTab', $(this).data('bs-target'));
        });
    });

$(document).on('click','.service_list_dot',function(e) {
   var id = $(this).data('id');
   var name = "Service Name";
   $.ajax({
        url:'{{route('company.viewservicepopup')}}',
        data: {
          'id':id,
          'name':name
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $('#viewservicelistdata').html(data.html);
        }
    })
 });

$("#since").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#until").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#sincerecur").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#untilrecur").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#sincesale").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#untilsale").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#sinceservice").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#untilservice").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#sinceproduct").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });

  $("#untilproduct").datepicker({ 
    autoclose: true, 
    todayHighlight: true
  });


    $(document).on('click','.searchBtnDown',function(e) {
        since = $("#since").val();
        until = $("#until").val();
        var form = $(this).closest('form');
        var persid = $.trim(form.find("input[name='persid']").val());
        $("#persid").val(persid);
        $("#sinced").val(since);
        $("#untild").val(until);
        $("#comsearch").submit();
    });

    $(document).on('click','.searchBtnDown1',function(e) {
        since = $("#since").val();
        until = $("#until").val();
        pid = $("#pid").val();
        $("#sincedd").val(since);
        $("#untildd").val(until);
        $("#pidd").val(pid);
        $("#comsearch1").submit();
    });

    $(document).on('click','.searchBtnDown2',function(e) {
        since = $("#sincerecur").val();
        until = $("#untilrecur").val();
        $("#sincerecuring").val(since);
        $("#untilrecuring").val(until);

        pid = $("#frequencyid").val();
        $("#frequencytype").val(pid);
        $("#comsearch2").submit();
    });

    $(document).on('click','.searchBtnDown3',function(e) {
        since = $("#sincerecur").val();
        until = $("#untilrecur").val();
        $("#sincerecuring").val(since);
        $("#untilrecuring").val(until);

        pid = $("#frequencyid").val();
        $("#frequencytype").val(pid);
        $("#comsearch2").submit();
    });

    $(document).on('click','.searchBtnDownsales',function(e) {
        since = $("#sincesale").val();
        until = $("#untilsale").val();
        $("#sincesales").val(since);
        $("#untilsales").val(until);
        $("#comsearchsales").submit();
    });

    $(document).on('click','.searchBtnDownservice',function(e) {
        since = $("#sinceservice").val();
        until = $("#untilservice").val();
        $("#sinceservices").val(since);
        $("#untilservices").val(until);
        $("#comsearchservice").submit();
    });

    $(document).on('click','.searchBtnDownproduct',function(e) {
        since = $("#sinceproduct").val();
        until = $("#untilproduct").val();
        $("#sinceproducts").val(since);
        $("#untilproducts").val(until);
        $("#comsearchproduct").submit();
    });

    $(document).on('click','.searchbtncustomer',function(e) {
        cid = $("#customerid").val();
        $("#customerids").val(cid);
        $("#customerreport").submit();
    });


    $('.pyrolluser').on('change', function() {
      var pid = this.value;
      $("#pyrollhiddenid").val(pid);
      this.form.submit();
    });

    $("#sincepayroll").datepicker({ 
        autoclose: true, 
        todayHighlight: true
      });

      $("#untilpayroll").datepicker({ 
        autoclose: true, 
        todayHighlight: true
      });

    $(document).on('click','.searchBtnDownPyroll',function(e) {
        since = $("#sincepayroll").val();
        until = $("#untilpayroll").val();
        $("#sincepayroll1").val(since);
        $("#untilpayroll1").val(until);

        pid = $("#selectpayrollid").val();
        $("#pyrollhiddenid1").val(pid);
        $("#payrollexport").submit();
    });
</script>
@endsection

