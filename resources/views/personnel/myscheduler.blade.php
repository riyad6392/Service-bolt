@extends('layouts.workerheader')
@section('content')
<style type="text/css">
  .error {
    color: red;
  }

.bootstrap-datetimepicker-widget.dropdown-menu {
  border: 1px solid #34495e;
  border-radius: 0;
  box-shadow: none;
  margin: 10px 0 0 0;
  padding: 0;
  min-width: 300px;
  max-width: 100%;
  width: auto;
}
.bootstrap-datetimepicker-widget.dropdown-menu.bottom:before, .bootstrap-datetimepicker-widget.dropdown-menu.bottom:after {
  display: none;
}
.bootstrap-datetimepicker-widget.dropdown-menu table td,
.bootstrap-datetimepicker-widget.dropdown-menu table th {
  border-radius: 0;
}
.bootstrap-datetimepicker-widget.dropdown-menu table td.old, .bootstrap-datetimepicker-widget.dropdown-menu table td.new {
  color: #bbb;
}
.bootstrap-datetimepicker-widget.dropdown-menu table td.today:before {
  border-bottom-color: #0095ff;
}
.bootstrap-datetimepicker-widget.dropdown-menu table td.active,
.bootstrap-datetimepicker-widget.dropdown-menu table td.active:hover,
.bootstrap-datetimepicker-widget.dropdown-menu table td span.active {
  background-color: #0095ff;
  text-shadow: none;
}
.bootstrap-datetimepicker-widget.dropdown-menu table td.active.today:before,
.bootstrap-datetimepicker-widget.dropdown-menu table td.active:hover.today:before,
.bootstrap-datetimepicker-widget.dropdown-menu table td span.active.today:before {
  border-bottom-color: #fff;
}
.bootstrap-datetimepicker-widget.dropdown-menu table th {
  height: 40px;
  padding: 0;
  width: 40px;
}
.bootstrap-datetimepicker-widget.dropdown-menu table th.picker-switch {
  width: auto;
}
.bootstrap-datetimepicker-widget.dropdown-menu table tr:first-of-type th {
  border-bottom: 1px solid #34495e;
}
.bootstrap-datetimepicker-widget.dropdown-menu table td.day {
  height: 32px;
  line-height: 32px;
  padding: 0;
  width: auto;
}
.bootstrap-datetimepicker-widget.dropdown-menu table td span {
  border-radius: 0;
  height: 77px;
  line-height: 77px;
  margin: 0;
  width: 25%;
}
.bootstrap-datetimepicker-widget.dropdown-menu .datepicker-months tbody tr td,
.bootstrap-datetimepicker-widget.dropdown-menu .datepicker-years tbody tr td,
.bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td {
  padding: 0;
}
.bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td {
  height: 27px;
  line-height: 27px;
}
.bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td span {
  display: block;
  float: left;
  width: 50%;
  height: 46px;
  line-height: 46px !important;
  padding: 0;
}
.bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td span:not(.decade) {
  display: none;
}
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td {
  padding: 0;
  width: 30%;
  height: 20px;
  line-height: 20px;
}
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td:nth-child(2) {
  width: 10%;
}
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td a,
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td span,
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button {
  border: none;
  border-radius: 0;
  height: 56px;
  line-height: 56px;
  padding: 0;
  width: 100%;
}
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td span {
  color: #333;
  margin-top: -1px;
}
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button {
  background-color: #fff;
  color: #333;
  font-weight: bold;
  font-size: 1.2em;
}
.bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button:hover {
  background-color: #eee;
}

.bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td {
  border-top: 1px solid #34495e;
}
.bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td a, .bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td span {
  display: block;
  height: 40px;
  line-height: 40px;
  padding: 0;
  width: 100%;
}

.todayText:before {
  content: "Today's Date";
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
         <h3 class="align-items-center d-flex justify-content-between">My Schedule
          <div>
           <a href="{{route('worker.timesheet')}}" class="btn btn-dark py-2 px-5">Time Sheet</a>
        @if(empty($workerh))
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @elseif(!empty($workerh) && $workerh->date1==null)
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockout" data-id="{{$auth_id}}">Clock Out</a>
        @else
        <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @endif
          </div>
        </h3>
        </div>
      </div>
    <div class="col-md-12">
    <div class="row">
   <div class="col-lg-3 mb-3">
   <div class="card yellow-badge h-auto">
   <div class="card-body p-2">
   <a href="{{route('worker.myticket')}}">
   <div class="shechudle-box d-flex align-items-center">
   <div class="icon icon-badge-yellow me-3">
   <svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0)">
<path d="M0.327487 8.89696L1.14336 9.75505C1.30747 9.70168 1.47995 9.67265 1.65816 9.67265H14.8723L9.3126 3.82537L8.13383 5.40269L7.34109 4.56893L8.51986 2.99162L6.40387 0.766187C6.2191 0.571836 5.98867 0.476273 5.75922 0.476273C5.49039 0.476273 5.22284 0.607321 5.03086 0.864138L3.80632 2.5027L3.95991 2.66425C4.31261 3.03519 4.52245 3.5469 4.55074 4.10511C4.57903 4.66332 4.4224 5.20117 4.10972 5.61958C3.79699 6.03803 3.36565 6.28693 2.8951 6.32049C2.8589 6.32308 2.82273 6.32434 2.78682 6.32434C2.35533 6.32434 1.94407 6.13971 1.61846 5.79721L1.46491 5.63571L0.244953 7.26815C0.0725762 7.49879 -0.0137359 7.7952 0.00183842 8.10283C0.0174127 8.41047 0.133072 8.69251 0.327487 8.89696ZM6.59811 5.56306L7.39085 6.39686L6.64773 7.3912L5.85499 6.5574L6.59811 5.56306ZM5.10948 7.55497L5.90221 8.38873L5.15909 9.38307L4.36636 8.54931L5.10948 7.55497Z" fill="currentColor"></path>
<path d="M17.1637 10.9295H5.36134V12.8875H4.30187V10.9295H1.65805C1.16715 10.9295 0.767731 11.4032 0.767731 11.9856V14.0172H1.10496C2.04073 14.0172 2.80207 14.9203 2.80207 16.0304C2.80207 17.1405 2.04073 18.0437 1.10496 18.0437H0.767731V20.0752C0.767731 20.6576 1.16715 21.1314 1.65805 21.1314H4.30183V19.1734H5.36131V21.1314H17.1636C17.6248 21.1314 17.9999 20.6863 17.9999 20.1394V11.9215C18 11.3745 17.6248 10.9295 17.1637 10.9295ZM5.36134 17.9156H4.30187V16.6595H5.36134V17.9156ZM5.36134 15.3993H4.30187V14.1433H5.36134V15.3993ZM14.9425 16.3336H13.0682L12.0386 18.6019L11.1058 18.0061L11.8649 16.3336H10.3281L9.644 17.4698L8.78187 16.7392L9.36363 15.7731L8.66985 14.95L9.419 14.0613L10.275 15.0767H11.8649L11.1058 13.4042L12.0386 12.8084L13.0682 15.0767H14.9425V16.3336H14.9425Z" fill="currentColor"></path>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="21.3534" fill="white" transform="translate(0 0.127167)"></rect>
</clipPath>
</defs>
</svg>
   </div>
   
   <div class="icon-text">
   My Tickets
   </div>
   </div>
   </a>
   </div>
   </div>
   </div>
   
   
   
   <div class="col-lg-3 mb-3">
   <div class="card blue-badge h-auto">
   <div class="card-body p-2">
    
   <a href="#" data-bs-toggle="modal" data-bs-target="#available-modal">
   <div class="shechudle-box d-flex align-items-center">
   <div class="icon icon-badge-blue me-3">
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.5 7.25a.75.75 0 00-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 00.744-1.302L12.5 12.315V7.25z" fill="currentColor"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z" fill="currentColor"></path></svg>
   </div>
   
   <div class="icon-text">
    Manual Login
   </div>
   </div>
   </a>
   </div>
   </div>
   </div>

    <div class="col-lg-3 mb-3">
   <div class="card blue-badge h-auto">
   <div class="card-body p-2">
    
   <a href="#" data-bs-toggle="modal" data-bs-target="#setavailable-modal">
   <div class="shechudle-box d-flex align-items-center">
   <div class="icon icon-badge-blue me-3">
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.5 7.25a.75.75 0 00-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 00.744-1.302L12.5 12.315V7.25z" fill="currentColor"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z" fill="currentColor"></path></svg>
   </div>
   
   <div class="icon-text">
    Set Available Hours
   </div>
   </div>
   </a>
   </div>
   </div>
   </div>
   
   
   <div class="col-lg-3 mb-3">
   <div class="card red-badge h-auto">
   <div class="card-body p-2">
   <a href="{{url('personnel/timeoff#t')}}">
   <div class="shechudle-box d-flex align-items-center">
   <div class="icon icon-badge-red me-3">
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.5 7.25a.75.75 0 00-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 00.744-1.302L12.5 12.315V7.25z" fill="currentColor"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z" fill="currentColor"></path></svg>
   </div>
   
   <div class="icon-text">
   Request Time Off
   </div>
   </div>
   </a>
   </div>
   </div>
   </div>
   </div>
   </div>

  <div class="col-lg-6 mb-4">
    <div class="card mb-4">
    <div class="card-body">
    <div class="body-content">

    <h5 class="mb-4">Calendar</h5>
    <div id="datepicker"></div>
    </div>
    </div>
    </div>
  </div>

  <div class="col-lg-6 mb-4">


<div class="card mb-4">
<div class="card-body">
<div class="card-content">
  <div id='calendar'></div>
<div class="custom-calender">
   <div class="ev-calender-title">
   <h3><span id="spanid" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h3>
   <span id="spanid1" style="pointer-events: none;position: absolute;top: 2px;background: green;
    color: #fff;border-radius: 17px;padding: 0 8px;"><input type="hidden" name="countval" id="countval" value="{{ count($scheduleData) }}">{{ count($scheduleData) }}</span>
   <div class="ev-arrow" style="display: none;">
   <a class="ev-left"></a>
   <a class="ev-right"></a>
   </div>
   </div>
   
   <div class="ev-calender-list">
    
     <ul class="connectedSortable showdata" id="sortable2">
      @for($i=$userData->openingtime; $i<=$userData->closingtime; $i++)
         @php
          if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
          
          $times = $i.":00";
         @endphp
          <li><div class="ev-calender-hours">{{strtoupper(date("h:i a", strtotime($times)))}}</div></li>
          @foreach($scheduleData as $key => $value) 
            @php

              $f= $i+1;
              $m =   ":00";
              $settimes = date("h:i a", strtotime($times));

              $gtime = explode(":",$value->giventime);
              $gtimeampm = explode(" ",$gtime[1]);

              $giventime = $gtime[0].':00'.' '.$gtimeampm[1];
            
              if($giventime == $settimes) {
                @endphp
                <li class="inner yellow-slide" id="drop_{{$value->id}}">
        <div class="card">
          <div class="card-body">
            <div class="imgslider" style="display: none;">
              <img src="{{url('uploads/customer/')}}/{{$value->image}}" alt=""/>
            </div>
            <input type="hidden" name="customerid" id="customerid" value="{{$value->customerid}}">
            <input type="hidden" name="quoteid" id="quoteid_{{$value->id}}" value="{{$value->id}}">
            <span>#{{$value->id}}</span>
            <h5>{{$value->customername}}</h5>
            <p>{{$value->servicename}}</p>
            <p>Time : {{$value->giventime}} @if($value->givenendtime!="") to {{$value->givenendtime}}@endif</p>
            <div class="grinding" style="display: block;">
              <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="5" cy="5" r="5" fill="currentColor" style="display: none;">
              </svg> {{$value->time}} {{$value->minute}} </a>
              <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="5" cy="5" r="5" fill="currentColor" style="display: none;">
              </svg> 
              @php
                $date=date_create($value->etc);
                $dateetc = date_format($date,"F d, Y");
              @endphp
            ETC : {{$dateetc}}</a>
            </div>
          </div>
        </div>
      </li>
                @php
              }
            @endphp
          @endforeach
      @endfor
     </ul>
   </div>
</div>
  </div>
</div>
</div>
</div>
   </div>
    </div>
<form method="post" action="{{ route('worker.timeoff') }}" enctype="multipart/form-data">
  @csrf     
<!-- Modal -->
<div class="modal fade" id="request-modal" tabindex="-1" aria-labelledby="request-modalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <div class="modal-body">
    <div class="request-title">
       <h5>Request time off</h5>
        <p>Choose dates needed off</p>
    </div><div class="col-md-12">
    <input type="text" id="datepicker2" name="datepicker2" placeholder="Choose Date" style="cursor: pointer;" class="mb-3 form-control" required></div>
    <div id="datepicker2" class="mb-3"></div>
    <div class="available-header my-2 text-center">
      <h4><span id="spanid4" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h4>
    </div>
    <input type="hidden" name="leavedate" id="leavedate" value="{{date('Y-m-d')}}">
  <div class="time-note mb-2">
    <textarea class="form-control mb-4" placeholder="Notes" name="notes" id="notes" required>{{@$timeoff->notes}}</textarea>
  </div>
<div class="row">
  <div class="col-lg-6 mb-3">
     <a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</a>
  </div>
  <div class="col-lg-6 mb-3">
     <button class="btn btn-add btn-block">Submit Request</button>
  </div>
  </div>
 </div>
  </div>
</div>
</div>
</form>
<form method="post" id="form" action="{{ route('worker.updatehours') }}" enctype="multipart/form-data">
  @csrf
  <div class="modal fade" id="available-modal" tabindex="-1" aria-labelledby="available-modalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content customer-modal-box">
        <div class="modal-body">
          <div class="request-title">
            <h5>Manual Login</h5>
            <p>Select date</p>
          </div>
          <div id="datepicker3" class="mb-2"></div>
          <div class="available-header my-2 text-center">
            <h4><span id="spanid3" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h4>
          </div>
          <input type="hidden" name="date1" id="date1" value="{{date('Y-m-d')}}">
          <div class="mb-2">
            <label>Clock in time</label>
            <input type="text" id="timepicker" name="timepicker" value="{{@$workerh->starttime}}" class="form-control form-control-2" placeholder="Clock in time" required="">
          </div>
          <div class="mb-2">
            <label>Clock out time</label>
            <input type="text" id="timepicker1" name="timepicker1" value="{{@$workerh->endtime}}" class="form-control form-control-2" placeholder="Clock out time" required="">
          </div>
          <div class="row">
            <div class="col-lg-6 mb-2">
              <a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</a>
            </div>
            <div class="col-lg-6 mb-2">
               <input type="button" id="button" value="Submit" class="btn btn-add btn-block">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<form id="form1" method="post" action="{{ route('worker.updatesethours') }}" enctype="multipart/form-data">
  @csrf
  <div class="modal fade" id="setavailable-modal" tabindex="-1" aria-labelledby="available-modalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content customer-modal-box">
        <div class="modal-body">
          <div class="request-title">
            <h5>Set Available Hours</h5>
            <p>Select date</p>
          </div>
          <div id="datepicker21" class="mb-2"></div>
          <div class="available-header my-2 text-center">
            <h4><span id="spanidsethours" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h4>
          </div>
          <input type="hidden" name="datenew1" id="datenew1" value="{{date('Y-m-d')}}">
          <div class="mb-2">
            <label>Clock in time</label>
            <input type="text" id="timepickernew" name="timepickernew" value="" class="form-control form-control-2" placeholder="Clock in time" required="">
          </div>
          <div class="mb-2">
            <label>Clock in time</label>
            <input type="text" id="timepickernew1" name="timepickernew1" value="" class="form-control form-control-2" placeholder="Clock out time" required="">
          </div>
          <div class="row">
            <div class="col-lg-6 mb-2">
              <a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</a>
            </div>
            <div class="col-lg-6 mb-2">
              <!-- <button type="submit" class="btn btn-add btn-block">Submit Request</button> -->
              <input type="button" id="button1" value="Submit" class="btn btn-add btn-block">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $(document).ready(function () {
    $( "#button" ).click(function() {
      var start_time = $("#timepicker").val();
      var end_time = $("#timepicker1").val();
      if(start_time==""){
        swal("Oops!", "Clock in time required!", "error");
        return false;
      }
      if(end_time==""){
        swal("Oops!", "Clock out time required!", "error");
        return false;
      }
      //convert both time into timestamp
  var stt = new Date("November 13, 2013 " + start_time);
  stt = stt.getTime();

  var endt = new Date("November 13, 2013 " + end_time);
  endt = endt.getTime();

  //by this you can see time stamp value in console via firebug
  console.log("Time1: "+ stt + " Time2: " + endt);
  
  if(stt > endt) {
    swal("Oops!", "Clock out time must be bigger then Clock in time!", "error");  
      // $("#timepicker").after('<span class="error"><br>Start Time must be smaller then End Time.</span>');
      // $("#timepicker1").after('<span class="error"><br>End Time must be bigger then Start Time.</span>');
          return false;
  } else {
    $( "#form" ).submit();
  }
  });

    $( "#button1" ).click(function() {
      var start_time = $("#timepickernew").val();
      var end_time = $("#timepickernew1").val();

      if(start_time=="") {
        swal("Oops!", "Clock in time required!", "error");
        return false;
      }
      if(end_time=="") {
        swal("Oops!", "Clock out time required!", "error");
        return false;
      }
      //convert both time into timestamp
  var stt = new Date("November 13, 2013 " + start_time);
  stt = stt.getTime();

  var endt = new Date("November 13, 2013 " + end_time);
  endt = endt.getTime();

  //by this you can see time stamp value in console via firebug
  console.log("Time1: "+ stt + " Time2: " + endt);
  
  if(stt > endt) {
    swal("Oops!", "Clock out time must be bigger then Clock in time!", "error");  
      // $("#timepicker").after('<span class="error"><br>Start Time must be smaller then End Time.</span>');
      // $("#timepicker1").after('<span class="error"><br>End Time must be bigger then Start Time.</span>');
          return false;
  } else {
    $( "#form1" ).submit();
  }
    });
});

$(document).on('click','#myticketid',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('worker.viewticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldata').html(data.html);
            }
        })
  });

  $("#timepicker").datetimepicker({
    format: "LT",
    icons: {
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down"
    }
  });

  $("#timepicker1").datetimepicker({
    format: "LT",
    icons: {
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down"
    }
  });

  $("#timepickernew").datetimepicker({
    format: "LT",
    icons: {
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down"
    }
  });

  $("#timepickernew1").datetimepicker({
    format: "LT",
    icons: {
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down"
    }
  });
  $("#datepicker2").datepicker({
    format: 'yyyy-mm-dd',
    minDate: 0,
    inline: false,
    lang: 'en',
    //step: 5,
    multidate: true,
    closeOnDateSelect: true,
  });
  $('#datepicker2old').datepicker({
     dateFormat: 'yy-mm-dd',
      minDate: 0,
      onSelect: function (date, datepicker) {
          var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
          const d = new Date(date);
          const dayName = days[d.getDay()];
          const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
          const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
          const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
          var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
          //alert(fulldate);
           $("#spanid4").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;width:400px;">');
           $('.bladedata').css('display','none');
           getleavenote(fulldate,date);
         
        }
  });

  $('#datepicker21').datepicker({
     dateFormat: 'yy-mm-dd',
      onSelect: function (date, datepicker) {
          var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
          const d = new Date(date);
          const dayName = days[d.getDay()];
          const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
          const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
          const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
          var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
          //alert(fulldate);
           $("#spanidsethours").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;width:400px;">');
           $('.bladedata').css('display','none');
           gethourdata(fulldate,date);
          }
  });

  $('#datepicker3').datepicker({
      dateFormat: 'yy-mm-dd',
      maxDate: 0,
      onSelect: function (date, datepicker) {
          var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
          const d = new Date(date);

          const dayName = days[d.getDay()];
          const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
          const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
          const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
          var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
          //alert(fulldate);

           $("#spanid3").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;width:400px;">');
           $('.bladedata').css('display','none');
          gettimedata(fulldate,date);
        }
  });
   function getleavenote(fulldate,date) {
    $.ajax({
        url:"{{url('personnel/scheduler/getleavenote')}}",
        data: {
          fulldate: fulldate,
          leavedate: date
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $("#notes").val(data.notes);
          $("#leavedate").val(data.leavedate);
        }
    })
   }

   function gettimedata(fulldate,date) {
    $.ajax({
        url:"{{url('personnel/scheduler/gettimedata')}}",
        data: {
          fulldate: fulldate,
          date1: date
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $("#timepicker").val(data.starttime);
          $("#timepicker1").val(data.endtime);
          $("#date1").val(data.date1);
        }
    })
    
  }

  function gethourdata(fulldate,date) {
    $.ajax({
        url:"{{url('personnel/scheduler/gethourdata')}}",
        data: {
          fulldate: fulldate,
          date1: date
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $("#timepickernew").val(data.starttime);
          $("#timepickernew1").val(data.endtime);
          $("#datenew1").val(data.date1);
        }
    })
    
  }

  function gethournote(fulldate,date) {
    $.ajax({
        url:"{{url('personnel/scheduler/gettimedata')}}",
        data: {
          fulldate: fulldate,
          date1: date
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $("#timepicker").val(data.starttime);
          $("#timepicker1").val(data.endtime);
          $("#date1").val(data.date1);
        }
    })
    
  }

  $('#datepicker').datepicker(
   {
        dateFormat: 'yy-mm-dd',
        onSelect: function (date, datepicker) {
          var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
          const d = new Date(date);
          const dayName = days[d.getDay()];
          const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
          const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
          const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
          var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
          //alert(fulldate);
           $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;color:#B0B7C3;width:400px;">');
           $('.bladedata').css('display','none');
           getschedulerdata(fulldate);

        }
  });

  function getschedulerdata(fulldate) {
    $.ajax({
        url:"{{url('personnel/scheduler/leftbarschedulerdata')}}",
        data: {
          fulldate: fulldate
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          console.log(data.html);
          $("#spanid1").html('Total Ticket Count : <input type="text" value="'+data.countsdata+'" name="countval" id="countval" style="pointer-events: none;position: absolute;top: -1px;background: green;color: #fff;border-radius:17px;padding: 0 8px;width: 25px;border: 0px;height: 25px;margin:0 10px;">');
          $('.showdata').html(data.html);
        }
    })
  }

  $(document).on('click','.clockin',function(e) {
  
  var currentTime = new Date()
  var hours = currentTime.getHours()
  var minutes = currentTime.getMinutes()

  if (minutes < 10)
      minutes = "0" + minutes;

  var suffix = "AM";
  
  if (hours >= 12) {
      suffix = "PM";
      hours = hours - 12;
  }

  if (hours == 0) {
      hours = 12;
  }
  
  var current_time = hours + ":" + minutes + " " + suffix;
  var d = new Date();
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
    const dayName = days[d.getDay()];
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      var fulldate = `${dayName} - ${mo} ${da}, ${ye}`

  $.ajax({
        url:"{{url('personnel/home/workerclockhoursin')}}",
        data: {
          starttime: current_time,
          fulldate: fulldate,
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          console.log(data.html);
          
          $('.clockin').hide();
          $('.clockout').show();
          location.reload();
        }
    }) 
  });

  $(document).on('click','.clockout',function(e) {
  var currentTime = new Date();
  var hours = currentTime.getHours();
  var minutes = currentTime.getMinutes();

  if (minutes < 10)
      minutes = "0" + minutes;

  var suffix = "AM";
  if (hours >= 12) {
      suffix = "PM";
      hours = hours - 12;
  }
  if (hours == 0) {
      hours = 12;
  }
  var current_time = hours + ":" + minutes + " " + suffix;
  var d = new Date();
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
    const dayName = days[d.getDay()];
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      var fulldate = `${dayName} - ${mo} ${da}, ${ye}`

  $.ajax({
        url:"{{url('personnel/home/workerclockhoursout')}}",
        data: {
          endtime: current_time,
          fulldate: fulldate,
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          console.log(data.html);
          
          $('.clockin').show();
          $('.clockout').hide();
          location.reload();
        }
    }) 
  });

$('#selector').delay(2000).fadeOut('slow');
</script>
@endsection


