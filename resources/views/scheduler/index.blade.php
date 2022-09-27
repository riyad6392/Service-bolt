@extends('layouts.header')
@section('content')
  <link rel="stylesheet" type="text/css" href="https://fullcalendar.io/releases/fullcalendar/3.10.0/fullcalendar.min.css">
   <link rel="stylesheet" type="text/css" href="https://fullcalendar.io/releases/fullcalendar-scheduler/1.9.4/scheduler.min.css">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">

<style type="">
  body{
        overflow-x: hidden;
        overflow-y: hidden;
    }
#calendar{display: block;}
.fc-center {
    position: absolute;
    left: 5px;
    top: 12px;
}
.fc-center h2{
    font-size: 16px;
}
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
  span.icon-btn .fa-edit {
    color: deepskyblue;
}
span.icon-btn .fa-user-plus{
  color: green;
}
span.icon-btn .fa-trash {
    color: red;
}
  span.icon-btn {
    margin: 0 auto;
    cursor: pointer;
    height: 30px;
    width: 30px;
    /*background-color: red;*/
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 100%;
    border:1px solid #ccc;
}
.popover-design p {
    font-weight: 600;
}
.popover .popover-body {
    padding: 0;}
    .popover .popover-body .popover-design{
      padding: 1rem 1rem;
    }
  .popover{
    
    width: 400px;
    max-width: 400px!important;
    /*min-height:200px;*/
  }
    #calendar.fulldayShow {
    overflow-y: scroll!important;
    height: 100%;
}
.fulldayShow .adses {
    height: 100%!important;
}
.fulldayShow .fc-view-container {
    height: 100%;
    overflow-y: scroll;
}

.fc-view-container {
    height: 345px;
    overflow-y: scroll;
}


     #header {
  background: #fff;
  padding: 3px;
  padding: 10px 20px;
  color: #fff;
}

.datess {
    display: flex;
    align-items: center;
}
.datess span {
    width: 196px;
    color: #000;
    font-size: 20px;
}

.slider ul {
    position: relative;
    width: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    list-style: none;
}

#external-events {
    z-index: 2;
    top: 0;
    left: 20px;
    width: auto!important;
    padding: 0 10px;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    justify-content: space-around;
    background: transparent;
}
  .column .sticky {
  position: -webkit-sticky;
  position: sticky;
  top: 15px;
}
.modal-content {
    border-radius: 15px;
    padding: 13px!important;
}
.modal-dialog {
    max-width: 560px!important;
  }


.card {
  height: auto;
  border-radius: 0!important;
}
/*.fixed-top {
    position: fixed;
    top: 65px;
    z-index: 9;
    width: 78%;
    left: auto;
    right: auto;

}*/

.inner p {
    color: #fff!important;
}
.heierts {
  min-height: 300px!important;
  overflow:auto!important;
}
.heierts::-webkit-scrollbar {
  width: 10px;
}

/* Track */
.heierts::-webkit-scrollbar-track {
  background: transparent; 
}
 
/* Handle */
.heierts::-webkit-scrollbar-thumb {
  background: transparent; 
}

/* Handle on hover */
.heierts::-webkit-scrollbar-thumb:hover {
  background: transparent; 
}


a.next.control {
    transform: rotate(180deg);
}
a.prev.control {
    transform: rotate(180deg);
}
/*.modal-body {
    height: 600px;
    overflow-x: hidden;
    overflow-y: scroll;
}*/
.add-customer-modal h5 {
    font-size: 32px;
    font-weight: 500;
}
.use.new {
    top: 43%!important;
}

.card.fixed-top.new {
    width: 100%!important;
    left: 0;
    top: 0;
}
.customer-form .form-select {
    border: 1px solid #F3F3F3;
    box-sizing: border-box;
    border-radius: 15px;
    height: 50px;
    outline: none;
}
.contents {
    overflow: hidden!important;
    position: fixed;
    width: 100%;
}
textarea.form-control.height-180 {
    height: 180px;
    resize: none;
    color: #212529;
}
.modal-header {
    padding-bottom: 0;
    border-bottom: none!important;
}
.customer-form .form-control {
    border: 1px solid #F3F3F3;
    box-sizing: border-box;
    border-radius: 15px;
    height: 50px;
    outline: none;
}
.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: 100%!important;
}

.timepicker {
    border: 1px solid rgb(163, 175, 251);
    text-align: center;
    display: inline;
    border-radius: 4px;
          padding:2px;
}

.timepicker .hh, .timepicker .mm {
    width: 44%;
    outline: none;
    border: none;
    text-align: center;
}

.timepicker.valid {
    border: solid 1px springgreen;
}

.timepicker.invalid {
    border: solid 1px red;
}

.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
    color: #999;
    padding: 12px;
    font-size: 16px;
}
div#bs-select-1 {
    width: 100%;
    text-align: left!important;

}
.blues {
    padding: 0!important;
}
.counter {
    font-size: 16px;
    background: red;
    padding: 6px 7px;
    color: #fff;
    border-radius: 30px;
    position: relative;
    bottom: 3px;
    width: 25px;
    height: 25px;
    display: inline-block;
    line-height: 15px;
}
.fc-highlight {
    background: #87CEEB!important;
    opacity: 1!important;
    filter: alpha(opacity=100)!important;
}
.side-h3 {
    padding: 0px 24px 8px!important;
}
/*.fc-scroller {
   overflow-y: hidden !important;
}*/
.tickets_div p {
    font-size: 15px;
    margin: 0;
}
.tickets_div h6 {
    font-size: 15px;
    margin: 0;
}
/*span.closeon i {
    color: red;
    background: #fff;
    padding: 4px 5px;
    border-radius: 24px;
    font-size: 15px;
    box-shadow: 0px 0px #ccc;
        margin-top: 25px;
          
    left: 0;

}*/

.tickets_div {
    text-align: center;
    padding: 4px 0;
}

.use {
    position: absolute;
    top: 68%;
    display: flex;
    justify-content: space-between;
    width: 100%;
    z-index: 0;
    padding: 0 10px;
}

.card-body {
    padding: 14px 25px 14px 25px;
}
.fc-event {
    border: none!important;
    padding: 2px;
    height: auto;
    width: auto;
    border-radius: 12px;
    z-index: 99;
}
.fc-time-grid .fc-bgevent, .fc-time-grid .fc-event {
    border-radius: 5px!important;
 
    padding: 4px!important;
     margin-right: 1px;
    margin-left: 1px;
}
th.fc-resource-cell {
   
    padding: 0px;
    text-transform: capitalize;
    text-align: left;
}

a.fc-time-grid-event {
    box-shadow: 0px 0px 5px #ccc;
    border-radius: 5px !important;
}


.fc-icon-left-single-arrow:after {
top: -52%!important;
}
.fc-icon-right-single-arrow:after {

top: -52%!important;
}

button.fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right {
    display: none!important;
}
button.fc-month-button.fc-button.fc-state-default.fc-corner-left {
    display: none!important;
}
button.fc-agendaWeek-button.fc-button.fc-state-default {
    display: none!important;
}
button.fc-agendaDay-button.fc-button.fc-state-default.fc-corner-right.fc-state-active {
    display: none!important;
}
.slider ul li {
width: 188px;
}
.fc .fc-toolbar.fc-header-toolbar {
    margin-bottom: 0.5em;
    justify-content: end!important;
}

.adses {
    height: 470px!important;
}
#external-events .fc-event {
  margin: 1em 0;
  cursor: move;
}

#calendar-container {
  position: relative;
  z-index: 1;
  margin-left: 200px;
}

#calendar {
    margin: 12px 0 0 auto;
    padding: 6px 2px;
    border-radius: 4px;
    background-color: #fff;
}
.fc {
    display: flex;
    flex-direction: inherit!important;
    }
/*.fc-time-grid .fc-slats td {
  background: #fff;

}*/


th.fc-resource-cell img {
    width: 30px;
    padding: 8px;
    border-radius: 100%;
}
.fc-unthemed td.fc-today {
    background: #f7f9fa;
}

.text-start.assigndiv {
    right: 5px;
    color: green;
    background-color: #fff;
    border-radius: 100%;
    padding: 2px 3px;
    font-size: 15px;
}

.add-btn-day {
    display: inline-block;
    background: #F7F9FA;
    border-radius: 15px;
    text-decoration: none;
    color: #232322;
    padding: 10.5px 16px;
    font-size: 16px;
    font-weight: 300;
}
span.date-icon {
    position: absolute;
    right: 10px;
    width: auto;
}
@media screen and (min-width: 1600px) {
    .fc-view-container {
    height: 660px;
    overflow-y: scroll;
}
.heierts {
    height: 660px!important;
    overflow: auto!important;
}
#calendar{
    height: 680px;
}
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="col-lg-12 mb-4">
            <div class="col-md-12">
                <div class="card position-sticky">
                    <div class="card-body">
                        <div class="row mb-0">
                            <div class="col-md-6">
                                <div class="side-h3">
                                    <h3 style="font-weight: 500;color: #000;">Scheduler & Dispatch 
                                        <span class="counter" style="display:none;">{{count($scheduleData)}}</span>
                                    </h3>

                                
                            
                                    <p style="margin: 0; font-weight: 500;color: #000;">Tickets to Assign</p>
                                </div>
                            </div>
                            
                            @if($userData->openingtime!="")
                            <input type="hidden" name="openingtime" id="openingtime" value="{{$userData->openingtime}}:00">
                            <input type="hidden" name="closingtime" id="closingtime" value="{{$userData->closingtime}}:00">
                            @else
                            <input type="hidden" name="openingtime" id="openingtime" value="00:00">
                            <input type="hidden" name="closingtime" id="closingtime" value="24:00">
                            @endif

                            <div class="col-md-3 ms-auto" style="display:none;">
                                <div class="datess position-relative">
                                    @if(request()->date)
                                      @php
                                        $todaydate = request()->date;
                                        $newdate = Carbon\Carbon::createFromFormat('Y-m-d', $todaydate)->format('m/d/Y');
                                        $requestdate = request()->date;
                                      @endphp
                                        <input type="text" placeholder="{{ date('d/m/Y') }}" onfocus="(this.type='date')" class="form-control" id="dateval" name="dateval" value="{{ date('d-m-Y', strtotime(request()->date ))}}">
                                        <span class="date-icon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    @else
                                    @php
                                        $requestdate = date('Y-m-d');
                                    @endphp
                                        <input type="text" placeholder="{{ date('d/m/Y') }}" onfocus="(this.type='date')" class="form-control" id="dateval" name="dateval" value="{{ date('m/d/y') }}">
                                         <span class="date-icon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-8" id="srcoll-here">
                                <div class="show-fillter" style="display: flex;justify-content:space-between;">
                                    <button class="menubar" id="hide-top" style="background: #FEE200;color: #000;border: none;padding: 10px 20px;border-radius: 15px;">Full Screen Day View</button>
                                    <a href='{{url("/")}}/company/scheduler/detailweek' id="weekAll" class="add-btn-yellow" style="background: #FEE200;color: #000;border: none;padding: 10px 20px;border-radius: 15px;">Full Screen Week View</a>
                                    <a href='{{url("/")}}/company/scheduler/detailmonth' class="add-btn-yellow" style="background: #FEE200;color: #000;border: none;padding: 10px 20px;border-radius: 15px;">Full Screen Month View</a>
                                    <span id="close"></span>
                                </div>

                            </div>

                            <div class="col-lg-3 mb-2 ms-auto text-end">
                                
                                <span id="close"></span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#add-tickets" class="add-btn-yellow">New Ticket Assign + </a>
                            </div>
                        </div>
                       
                        <div class="card-personal" id="external-events">
                            <div class="gallery portfolio_slider  slider">
                                @if(count($ticketData)>0)  
                                <ul id="sortable1" class="connectedSortable" style="width:50000px">
                                    @foreach($ticketData as $key => $value)
                                       @php
                                          $servicecolor = App\Models\Service::select('color')
                                            ->where('services.id',$value->serviceid)->get()->first();
                                        @endphp
                                        <li class="inner red-slide">
                                            <div class='fc-event' style="background-color: {{$servicecolor->color}};" data-color='{{$servicecolor->color}}' data-id='{{$value->id}}' data-bs-toggle='modal' data-bs-target='#exampleModal' id="editsticket">
                                                <div class="tickets_div">
                                                    <h6>#{{$value->id}}</h6>
                                                    <p> {{$value->customername}}</p>
                                                    <p>{{$value->servicename}}</p>
                                                </div>
                                            </div>
                                        </li>
                                   @endforeach 
                                </ul>
                                @else
                                    <div style="text-align: center;">
                                        No Tickets To Schedule
                                    </div>
                                @endif
                                <div style="display:none;">
                                <input type='checkbox' id='drop-remove' checked>
                            </div>
                            </div>
                        </div>
                    </div>
                    @if(count($ticketData)>0)
                        <div class="use">
                            <a href="#0" class="prev control">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"  class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                                </svg>
                            </a>
                            <a href="#0" class="next control">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"  class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <div id='calendar' style="position: relative; overflow:scroll;">
                    <input type="hidden" id="suggest_trip_start" value="6">
                    <input type="hidden" name="wcount" id="wcount" value="{{$wcount}}">
                    @if(request()->start)
                        <i class="fa fa-chevron-left" id="suggest_trip_prev" style="  cursor: pointer;  font-size: 24px;position: absolute;top: 57px;left: 6px; z-index: 9;"></i>
                    @else
                    
                    @endif
                    <i class="fa fa-chevron-right" id="suggest_trip_next" style="cursor: pointer;  font-size: 24px;position: absolute;top: 57px; left: 30px; z-index: 9;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
      <form method="post" action="{{ route('company.sticketupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="sviewmodaldata"></div>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body" style="height: 500px;">
      <form method="post" action="{{ route('company.ticketadded') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata1"></div>
      </form>
      </div>
    </div>
  </div>
</div>

<!-- new ticket assign modal -->
<div class="modal fade" id="add-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal">
      <h5>Add A New Ticket</h5>
     </div>
      

     @if(count($services)>0)
      @else
        @if(count($productData)==0)
        <div style="color: red;">Step2: Please add a service in the services section.</div>
        
      @else
        <div style="color: red;">Step1: Please add a service in the services section.</div>
        
      @endif
     @endif

    @if(count($customer)>0)
      @else
        @if(count($productData)==0 && count($services)==0)
        <div style="color: red;">Step3: Please add a customer in the customer section.</div>
        
      @elseif(count($productData)>0 && count($services)>0)
        <div style="color: red;">Step1: Please add a customer in the customer section.</div>
      @elseif(count($productData)>0)
        <div style="color: red;">Step2: Please add a customer in the customer section.</div>  
        
      @elseif(count($services)>0)
        <div style="color: red;">Step1: Please add a customer in the customer section.</div>
        <div style="color: red;">Step2: Then please create a new tickets/quote here.</div>
        
      @endif
     @endif
     
     <form class="form-material m-t-40 row form-valide" method="post" action="{{route('company.directquotecreate')}}" enctype="multipart/form-data">
        @csrf
        @php
        if(count($customer)>0) {
          $custmername = "";
        }
        else {
          $custmername = "active-focus";
        }
      @endphp
     <div class="row customer-form">
     <div class="col-md-12 mb-2">
       <div class="input_fields_wrap">
          <div class="mb-3">
            <select class="form-select {{$custmername}}" name="customerid" id="customerid1" required>
                <option selected="" value="">Select a customer </option>
              @foreach($customer as $key => $value)
                <option value="{{$value->id}}">{{$value->customername}}</option>
              @endforeach
            </select>
          </div>
      </div>
    </div>

    <div class="col-md-12 mb-2">
       <div class="input_fields_wrap">
          <div class="mb-3">
            <select class="form-select" name="address" id="address_scheduler" required>
              <option value="">Select Customer Address</option>
              </select>
          </div>
      </div>
    </div>

    @php
      if(count($services)>0) {
        $cname = "";
      }
      else {
        $cname = "active-focus";
      }
      if(count($worker)>0) {
        $wname = "";
      }
      else {
        $wname = "active-focus";
      }
    @endphp
     <div class="col-md-12 mb-3">
        <select class="selectpicker1 form-control {{$cname}}" name="servicename[]" id="servicename" required="" multiple aria-label="Default select example" data-live-search="true">
          @foreach($services as $key =>$value)
          <option value="{{$value->id}}" data-hour="{{$value->time}}" data-min="{{$value->minute}}" data-price="{{$value->price}}" data-frequency="{{$value->frequency}}">{{$value->servicename}}</option>
        @endforeach
       </select>
     </div>
     
     <div class="col-md-6 mb-3" style="display: none;">
      <select class="form-select" name="personnelid" id="personnelid">
      <option selected="" value="">Select a Personnel </option>
      @foreach($worker as $key => $value)
        <option value="{{$value->id}}">{{$value->personnelname}}</option>
      @endforeach
    </select>
     </div>
    
    <div class="col-md-12 mb-3">
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
      </div>
     
     <div class="col-md-6 mb-2">
      <select class="form-select" name="frequency" id="frequency" required="">
      <option selected="" value="">Service Frequency</option>
        @foreach($tenture as $key=>$value)
          <option name="{{$value->tenturename}}" value="{{$value->tenturename}}">{{$value->tenturename}}</option>
        @endforeach
    </select>
     </div>
     
     <div class="col-md-6 mb-2">
     <label>Default Service Time</label><br>
            <div class="timepicker timepicker1 form-control" style="display: flex;align-items: center;">
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div>
        </div>

     <div class="col-md-12 mb-3 position-relative">
      <i class="fa fa-dollar" style="position: absolute;top:18px;left: 27px;"></i>
      <input type="text" class="form-control" placeholder="Price" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
     </div>
     
     <div class="col-md-12 mb-3">
      <label style="position: relative;left: 12px;margin-bottom: 11px;">ETC</label>
     <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;"  required>
     </div>
     <div class="col-md-12 mb-3">
       <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required></textarea>
     </div>
     <div class="col-lg-6 mb-3">
      <button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
      <button type="submit" class="btn btn-add btn-block" name="ticket" value="ticket">Add a Ticket</button>
     </div>
     
     <div class="col-lg-12">
     <button type="submit" class="btn btn-dark btn-block btn-lg p-2" name="share" value="share"> Share</button>
     </div>
     </div>
    </form> 
  </div>
  </div>
</div>
</div>
<!-- end ticket assign modal  -->
<!-------event hover---modal---->


@endsection

@section('script')
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/lib/jquery.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/lib/moment.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/fullcalendar.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar-scheduler/1.9.4/scheduler.min.js"></script>
<script  src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
<script async defer src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
<script src="http://localhost/servicebolt/public/js/bootstrap-select.min.js"></script>


<script type="">
      $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
    $(".menubar").click(function() {
        setTimeout(function() {
            $("div").removeClass("heierts");
        },)
        $(".fc-scroller.fc-time-grid-container").addClass("adses");
        $(".datess").addClass("d-none");
        $(".col-lg-4").toggleClass("col-lg-12 text-end");
        $(".card-body").toggleClass("padeings");
        $(".show-fillter").toggleClass("new-filters");
        $(".content").toggleClass("contents");
        $(".fixed-top").toggleClass("new");
        $(".card.fixed").toggleClass("new-1");
        $(".add-btn-yellow").toggleClass("d-none");
        $(".side-h3").toggleClass("d-none");
        $(".use").toggleClass("new");
        $(".fc.fc-unthemed.fc-ltr").toggleClass("new");
    });

    $("#dateval").on("change",function() {
        var selected = $(this).val();
        window.location.href = "?date="+selected;
    });
</script>

 <script>
    $( function() {
        $( "#sortable1, #sortable2" ).sortable({
          connectWith: ".connectedSortable"
        }).disableSelection();
    });
  
    $(function() {
        var slideCount =  $(".slider ul li").length;
        var slideWidth =  $(".slider ul li").width();
        var slideHeight =  $(".slider ul li").height();
        var slideUlWidth =  slideCount * slideWidth;
        $(".slider ul li:last-child").prependTo($(".slider ul"));
        
        function moveLeft() {
            $(".slider ul").stop().animate({
              left: + slideWidth
            },700, function() {
              $(".slider ul li:last-child").prependTo($(".slider ul"));
              $(".slider ul").css("left","");
            });
        }
        function moveRight() {
            $(".slider ul").stop().animate({
              left: - slideWidth
            },700, function() {
              $(".slider ul li:first-child").appendTo($(".slider ul"));
              $(".slider ul").css("left","");
            });
        }
        $(".next").on("click",function() {
            moveRight();
        });
        $(".prev").on("click",function() {
            moveLeft();
        });
    });
  </script>

<script type="">
    $(document).ready(function () {
        $('.selectpicker1').selectpicker();
        function gethours1() {
                var h=0;
                var m=0;
                $('select.selectpicker1').find('option:selected').each(function() {
                h += parseInt($(this).data('hour'));
                  m += parseInt($(this).data('min'));
                  
                });
                var realmin = m % 60;
            var hours = Math.floor(m / 60);
            h = h+hours;
                
            $("#time").val(h);
                $("#minute").val(realmin);
        }

        function getprice1() {
            var price=0;
            $('select.selectpicker1').find('option:selected').each(function() {
                price += parseInt($(this).data('price'));
            });
                
            $("#price").val(price);
        }

        function getfrequency1() {
            var frequency = "";
            $("#frequency option").removeAttr('selected');
            $('select.selectpicker1').find('option:selected').each(function() {
                frequency = $(this).data('frequency');
            });
            $("#frequency option[value='"+frequency+"']").attr('selected', 'selected');
        }
        
        $(document).on('change', 'select.selectpicker1',function() {
            
            gethours1();
            getprice1();
            getfrequency1();
        });
        $('html, body').animate({
            scrollTop: $('#srcoll-here').offset().top
        }, 'slow');
       
        setTimeout(function() {
            $(".fc-scroller").addClass("heierts");
        },1000);
    });
    
    $(document).on("click",'.fc-time-grid-event',function() {
        $(".fc-time-grid-event").removeClass('intro');
        $(this).addClass('intro');
    });
    $("#hide-top").click(function() {
        $("#hide-top").hide();
        $("#close").html('<a href="" style="position: relative;left: 0;top:0px;color: black;"> <i class="fa fa-times" aria-hidden="true" style="font-size: 20px;position: fixed;right: 10px;z-index: 9999;color: black;"></i></a>');
        $(".top-bar").toggle();
        $(".content-page").toggleClass("blues");
        $("#calendar").addClass("fulldayShow");

    });
</script>
<script type="">

    @if(request()->start)
        //var start= $("#suggest_trip_start").val();
        var start = {{request()->start}};
        var offset = start;
        var limit="6"; // no of records to fetch/ get .
        var newoffsetvalue = parseInt(offset)+6;
        $("#suggest_trip_start").val(newoffsetvalue); 
    @endif

    $(document).on("click","#suggest_trip_next",function() {
        var start= $("#suggest_trip_start").val();
        var offset = start;
        var limit="6"; // no of records to fetch/ get .
        //var newoffsetvalue = parseInt(offset)+6;
        var newoffsetvalue = parseInt({{request()->start}})+6;
        //alert(newoffsetvalue);
        $("#suggest_trip_start").val(newoffsetvalue); 
        
        var wcount = {{$wcount}};
        
        if(parseInt(start) >= parseInt(wcount)) {
            start = start -6;
        } 
        @if(request()->date)
           window.location.href = "?date={{request()->date}}&start="+start;     
        @else
            window.location.href = "?start="+start;
        @endif
        
    });

    @if(request()->prev)
        var start= $("#suggest_trip_start").val();
        var offset = start;
        var offset = parseInt(offset) - 6;
        var limit="6"; // no of records to fetch/ get .
        var newoffsetvalue = offset;
        $("#suggest_trip_start").val(newoffsetvalue); 
    @endif

    $(document).on("click","#suggest_trip_prev",function() {
        var start= $("#suggest_trip_start").val();
        var offset = start;
        var offset = parseInt(offset) - 6;
        var limit="6"; // no of records to fetch/ get .
        var newoffsetvalue = offset;
        $("#suggest_trip_start").val(newoffsetvalue);
        var start = 0;
        @if(request()->date)
           window.location.href = "?date={{request()->date}}&prev="+start;     
        @else
            window.location.href = "?prev="+start;
        @endif
    });
    
    $(function() {
        $('#external-events .fc-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true, // maintain when user navigates (see docs on the renderEvent method)
                color: $(this).data('color'),
                //duration: '04:00'
            });
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 9999999,
                //duration: '04:00',
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
        
        
    });



  // $('#calendar').fullCalendar({

  //   displayEventTime: false,
  //   timeFormat: 'H(:mm)',

  //   header: {
  //     left: 'prev,next today',
  //     center: 'title',
  //     right: 'month,agendaWeek,agendaDay'
  //   },

  //   allDayDefault: false,
  //   editable: true,
  //   droppable: true,

  //   eventRender: function(event, el) {

  //     el.find('.fc-content').html("<i class='fa fa-camera-retro fa-3x'></i>");

  //   },
  //   events: [{
  //     title: 'event1',
  //     start: '2022-09-19 08:00:00'
  //   }, {
  //     title: 'event2',
  //     start: '2022-09-18 08:00:00',
  //   }, {
  //     title: 'event3',
  //     start: '2022-09-20 08:00:00',
  //   }, {
  //     title: 'event3',
  //     start: '2022-09-19 12:50:00',
  //   }, {
  //     title: 'event3',
  //     start: '2017-02-01 13:50:00',
  //   }, ]
  // });

    $('#calendar').fullCalendar({
            header: {
                left: 'prev',
                center: 'title', 
                right: 'next'
            },
            height: "auto",
            snapDuration: '00:05:00',
            minTime: $("#openingtime").val(),
            maxTime: $("#closingtime").val()+1, 
            //slotDuration: "00:30:01",
            allDaySlot: false,
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            defaultView: 'agendaDay',
            groupByResource: true,
            eventOverlap: true,
            slotEventOverlap:false,
            //defaultEventMinutes: 30, 
            //defaultTimedEventDuration: 04:00,
            forceEventDuration: true,
            //nextDayThreshold: '00:00',
            resources: function (callback) {
                @if(request()->start)
                    var start ="{{request()->start}}";

                @elseif(request()->prev)
                    var start ="{{request()->prev}}";
                @else
                    var start =0;
                @endif
                $.ajax({
                url:"{{url('company/scheduler/getworker')}}",
                method:"POST",
                data:{"start":start},
                dataType: 'json',
                refresh: true,
                    }).done(function(response) {
                  callback(response.resources); //return resource data to the calendar via the provided callback function
                });
            },

            events: '{{route("company.getschedulerdata")}}',
            
            eventRender: function(event, element, view) {
              
                var start = moment(event.start).format('h:mm a'); 
              
                var end = moment(event.end).format('h:mm a'); 
              
                var title = event.title.split("#");
                var tid = title[1].split("\n");
                var eventid = tid[0];


                element.popover({
                  title: '',
                  placement: 'right',
                  html:true,
                  sanitize:false,
                  content: '<div class="popover-design"><div class="row"><div class="col-md-7"><p>'+event.status+'</p><p>'+event.title+'</p></div><div class="col-md-5 text-center"><p>'+start+' -'+end+'</p></div><div class="col-md-4"><div class="text-start"><span class="icon-btn"><i class="fa fa-edit" data-bs-toggle="modal" data-bs-target="#exampleModal" id="editsticket" data-id="'+eventid+'"></i></span></div></div><div class="col-md-4 text-center"><div class="text-end"><span class=" icon-btn" id="closeonDelete" data-id="'+eventid+'"><i class="fa fa-trash" > </i></span></div></div> <div class="col-md-4 text-center"><div class="text-start"><span class="icon-btn" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id=" '+eventid+'"><i class="fa fa-user-plus"></i></span></div></div></div>',
                  container:'body',
                  trigger:'click',
                });

                $('body').on('click', function(e) {
                    if (!element.is(e.target) && element.has(e.target).length === 0 && $('.popover').has(e.target).length === 0)
                      element.popover('hide');
                });
                if (view.name == 'listDay') {
                    element.find(".fc-list-item-time").append("<div class='text-end'><span class='closeon'><i class='fa fa-trash' > </i></span></div>");
                } else {
                   // element.find(".fc-content").prepend("<div class='text-end'><span class='closeon'><i class='fa fa-trash' > </i></span></div>");
                } 
                element.find(".closeon").on('click', function() {
                  //var id = event.id;
                  var id = eventid;

                   swal({
                        title: "Are you sure!",
                        text: "Are you sure? you want to delete it!",
                        type: "error",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes!",
                        showCancelButton: true,
                    },
                function() {
                    $.ajax({
                           url:"{{url('company/scheduler/deleteTicket')}}",
                           type:"POST",
                           data:{id:id},
                           success:function()
                           {
                            location.reload(),
                            $('#calendar').fullCalendar('removeEvents',event._id);
                            swal({
                               title: "Done!", 
                               text: "Ticket Removed Successfully!", 
                               type: "success"
                            },
                            );
                           }
                          })
            });
                    //$('#calendar').fullCalendar('removeEvents',event._id);
                });
                
                if (view.name == 'listDay') {
                    element.find(".fc-list-item-time").append("<div class='text-start'><span class='fa fa-edit123' data-bs-toggle='modal' data-bs-target='#exampleModal'></span></div>");
                } else {
                   // element.find(".fc-content").prepend("<div class='text-start'><span class='fa fa-edit' data-bs-toggle='modal' data-bs-target='#exampleModal' id='editsticket' data-id='"+event.id+"'></span></div>");

                    //element.find(".fc-content").prepend("<div class='text-start assigndiv'><span class='fa fa-user-plus' data-bs-toggle='modal' data-bs-target='#edit-tickets' id='editTickets' data-id='"+event.id+"'></span></div>");
               } 

                element.find(".fa-edit").on('click', function() {
                    //console.log(event.id);
                    $('#calendar').fullCalendar('editEvents',event._id);
                    console.log('edit');
                });



            
            },
            resourceRender: function (dataTds, eventTd) {                 
                var datatitle = dataTds.title;
                var title = datatitle.split("#");
                if(title[1]!="") {
                    var url = "{{url('uploads/personnel/thumbnail/')}}/"+title[1];
                } else {
                    var url = "{{url('uploads/servicebolt-noimage.png')}}";
                }
                //var linkurl= "{{url('company/scheduler/detailweek/')}}/"+dataTds.id;
                var linkurl= "#";
                var textElement = eventTd.empty();
                textElement.append('<b><a href="'+ linkurl +'" style="color:inherit;text-decoration:none;pointer-events:none;"><img src="'+ url +'" alt=""/>' + title[0] + '</a></b>');
            },
            unselectAuto: false,
            selectable: true,
            selectHelper: false,
            editable: true,
            droppable: true,
            dayClick: function (date) {
            },
            select: function (startDate, endDate) {
                //alert('selected ' + startDate.format() + ' to ' + endDate.format());
            },

            eventClick: function (info) {
                //console.log(info);

            },
            eventMouseover : function(data, event, view) {
            //console.log(data);
            },

            eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) { 
                var title = event.title.split("#");
                var tid = title[1].split("\n");
                var eventid = tid[0];
                var hours = event.start._i[3];
                var minutes = event.start._i[4];
                const ampm = hours >= 12 ? 'pm' : 'am';

                hours %= 12;
                hours = hours || 12;    
                hours = hours < 10 ? `0${hours}` : hours;
                minutes = minutes < 10 ? `0${minutes}` : minutes;
                var giventime = `${hours}:${minutes} ${ampm}`;

                var hours1 = event.end._i[3];
                var minutes1 = event.end._i[4];
                const ampm1 = hours1 >= 12 ? 'pm' : 'am';

                hours1 %= 12;
                hours1 = hours1 || 12;    
                hours1 = hours1 < 10 ? `0${hours1}` : hours1;
                minutes1 = minutes1 < 10 ? `0${minutes1}` : minutes1;
                var givenendtime = `${hours1}:${minutes1} ${ampm1}`;

                //var ticketid = event.id;
                var ticketid = eventid;

                var workerid = event.resourceId;

                form_data = new FormData();
                form_data.append('quoteid',ticketid);
                form_data.append('time',giventime);
                form_data.append('endtime',givenendtime);
                form_data.append('workerid',workerid);
                $.ajax({
                    url:"{{url('company/scheduler/updatesortdata')}}",
                    method:"POST",
                    data:form_data,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success:function() {
                        location.reload();
                        // swal({
                        //    title: "Done!", 
                        //    text: "Ticket Updated Successfully!", 
                        //    type: "success"
                        // },
                        // function(){
                        //         //$('#calendar').fullCalendar('refetchEvents'); 
                        //     }
                        // );
                    }
                });
                
            },

            drop: function (date, jsEvent, ui, resourceId) {
                if ($('#drop-remove').is(':checked')) {
                  // if so, remove the element from the "Draggable Events" list
                  $(this).remove();
                }
               var hours = date._i[3];
                var minutes = date._i[4];
                //alert(date._i[3] + date._i[4]);
                const ampm = hours >= 12 ? 'pm' : 'am';

                hours %= 12;
                hours = hours || 12;    
                hours = hours < 10 ? `0${hours}` : hours;
                minutes = minutes < 10 ? `0${minutes}` : minutes;

                var ticketid = $(this).data('id');
                var workerid = resourceId;
                var giventime = `${hours}:${minutes} ${ampm}`;

                var dd = date._d;
                var date = moment(new Date(dd.toString().substr(0, 16)));
                //var fulldate = date.format("dddd - MMMM DD, YYYY");
                // console.log(date._i);
                // return false;
                //var fulldate = "{{$requestdate}}";
                var fulldate = moment(date._i).format('Y-MM-DD');
                
                form_data = new FormData();
                form_data.append('quoteid',ticketid);
                form_data.append('time',giventime);
                form_data.append('date',fulldate);
                form_data.append('workerid',workerid);
                $.ajax({
                    url:"{{url('company/scheduler/sortdata')}}",
                    method:"POST",
                    data:form_data,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success:function() {
                        //$('#calendar').fullCalendar('refetchEvents');
                        //location.reload();
                        // $("#calendar").fullCalendar('removeEvents', '231');
                      location.reload();
                        // swal({
                        //    title: "Done!", 
                        //    text: "Time slot allocated successfully, Click below to complete process!", 
                        //    type: "success"
                        // },
                        // function() { 
                        //     //$('#calendar').fullCalendar('refetchEvents');
                        //        location.reload();
                        //     }
                        // );
                     }
                });
            },  
            eventDrop: function(event,delta, revertFunc, jsEvent, ui, view ) {
                //console.log(event.start._d);
                var title = event.title.split("#");
                var tid = title[1].split("\n");
                var eventid = tid[0];
                var ticketid = eventid;

                //var ticketid = event.id;
                var resourceId = event.resourceId;
                
                if(event.start._i[0].length==1) {
                 
                    var datetime = event.start._i;
                    
                    var datetime = datetime.split(" ");
                    var time = datetime[1];
                    var timedata = time.split(":");
                    var hours = timedata[0];
                    var minutes = timedata[1];
                    const ampm = hours >= 12 ? 'pm' : 'am';

                    hours %= 12;
                    hours = hours || 12;    
                    hours = hours < 10 ? `0${hours}` : hours;
                    minutes = minutes < 10 ? `0${minutes}` : minutes;

                    var ticketid = ticketid;
                    var workerid = resourceId;
                    var giventime = `${hours}:${minutes} ${ampm}`

                    //var fulldate = "{{$requestdate}}";
                    var fulldate = moment(event.start._d).format('Y-MM-DD');
                } else {
                 
                    var hours = event.start._i[3];
                    var minutes = event.start._i[4];
                    //alert(minutes);
                    //alert(hours);
                    const ampm = hours >= 12 ? 'pm' : 'am';

                    hours %= 12;
                    hours = hours || 12;    
                    hours = hours < 10 ? `0${hours}` : hours;
                    minutes = minutes < 10 ? `0${minutes}` : minutes;

                    var ticketid = ticketid;
                    var workerid = resourceId;
                    var giventime = `${hours}:${minutes} ${ampm}`;

                    //var fulldate = "{{$requestdate}}";
                    var fulldate = moment(event.start._d).format('Y-MM-DD');
                }

                form_data = new FormData();
                form_data.append('quoteid',ticketid);
                form_data.append('time',giventime);
                form_data.append('date',fulldate);
                form_data.append('workerid',workerid);
                $.ajax({
                    url:"{{url('company/scheduler/sortdata')}}",
                    method:"POST",
                    data:form_data,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success:function() {
                        //$('#calendar').fullCalendar('refetchEvents');
                        location.reload();
                        // swal({
                        //    title: "Done!", 
                        //    text: "Time slot allocated successfully, Click below to complete process!", 
                        //    type: "success"
                        // },
                        // function() { 
                        //     //$('#calendar').fullCalendar('refetchEvents');
                        //        location.reload();
                        //     }
                        // );
                    }
                });
            },
        });

    $(document).on('click','#editsticket',function(e) {
         $('.selectpicker1').selectpicker();
        var id = $(this).data('id');
        var dataString =  'id='+ id;
        $.ajax({
            url:'{{route('company.vieweditschedulermodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#sviewmodaldata').html(data.html);
              $('.selectpicker1').selectpicker({
                size: 5
              });
            }
        })
    });

    $(document).ready(function() {
    $('#customerid1').on('change', function() {
      var customerid = this.value;
      $("#address_scheduler").html('');
        $.ajax({
          url:"{{url('company/quote/getaddressbyid')}}",
          type: "POST",
          data: {
          customerid: customerid,
          _token: '{{csrf_token()}}' 
          },
          dataType : 'json',
          success: function(result) {
          $('#address_scheduler').html('<option value="">Select Customer Address</option>'); 
            $.each(result.address,function(key,value) {
              $("#address_scheduler").append('<option value="'+value.address+'">'+value.address+'</option>');
            });
          }
      });
    });    
  });
</script>

<script type="">
 $(document).on('click','#editTickets',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewaddedticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldata1').html(data.html);
              $('.selectpicker').selectpicker({
                size: 5
              });
            }
        })
  });

  $(document).on('click','#closeonDelete',function() {
        var id = $(this).data('id');      
                   swal({
                        title: "Are you sure!",
                        text: "Are you sure? you want to delete it!",
                        type: "error",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes!",
                        showCancelButton: true,
                    },
                function() {
                    $.ajax({
                           url:"{{url('company/scheduler/deleteTicket')}}",
                           type:"POST",
                           data:{id:id},
                           success:function()
                           {
                            location.reload();
                            //$('#calendar').fullCalendar('removeEvents',event._id);
                            // swal({
                            //    title: "Done!", 
                            //    text: "Ticket Removed Successfully!", 
                            //    type: "success"
                            // },
                            // );
                           }
                          })
            });

          });

    $(document).on('change', '#personnelid',function() {
        var pid = "";
        var pname = "";
        $("#radiolist").empty();
        $("#cname").empty();  
        $('select.selectpicker').find('option:selected').each(function() {
            pid = $(this).data('value');
            pname = $(this).data('name');
            $("#radiolist").append('<input type="radio" name="primaryname" id="'+pid+'" value="'+pid+'"><label for="'+pid+'"> '+pname+'</label><br>');
        });
      $("#cname").append('Choose Any one primary personnel');
           
    });
 
 </script>
@endsection