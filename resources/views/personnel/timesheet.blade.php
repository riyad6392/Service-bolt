@extends('layouts.workerheader')
@section('content')
<style type="text/css">
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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<div class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="side-h3">
        <h3 class="align-items-center d-flex justify-content-between">Time Sheet
          <div class="d-flex align-items-center ">
           
    <div class="me-3">
   <div class="card blue-badge h-auto">
   <div class="card-body p-2">
    
   <a href="#" data-bs-toggle="modal" data-bs-target="#available-modal">
   <div class="shechudle-box d-flex align-items-center">
   <div class="icon icon-badge-blue me-3">
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.5 7.25a.75.75 0 00-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 00.744-1.302L12.5 12.315V7.25z" fill="currentColor"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z" fill="currentColor"></path></svg>
   </div>
   
   <div class="icon-text fs-6">
   Manual Login
   </div>
   </div>
   </a>
   </div>
   </div>
   </div>
        @if(empty($workhours))
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @elseif(!empty($workhours) && $workhours->date1==null)
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockout" data-id="{{$auth_id}}">Clock Out</a>
        @else
        <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @endif
          </div>
        </h3>
      </div>
    </div>
<div class="col-lg-6 mb-4">
  <div class="card h-auto mb-4">
    <div class="card-body">
     <div class="card-content">
      <h5 class="mb-4">Pending Tickets</h5>
      <div class="table-responsive">
        <table class="table no-wrap table-new table-list" style="position: relative;">
          <thead>
            <tr>
              <th>TicketId</th>
              <th>Service</th>
              <th>Customer</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ticketdata as $value)
            @php
              $ticketid = $value->id;
              if(!empty($value->parentid))
              {
                $ticketid=$value->parentid;
              }
            @endphp
            <tr>
             <td><a href="{{url('personnel/myticket/view/')}}/{{$ticketid}}" style="color:#29DBBA;text-decoration:none;">#{{$ticketid}}</a></td>
             <td><a href="{{url('personnel/myticket/view/')}}/{{$value->id}}" style="color:#000;text-decoration:none;"><div class="ex-date"><h6>{{$value->servicename}}</h6> <span>{{$value->giventime}}</span></div></a></td>
              <td><a href="{{url('personnel/myticket/view/')}}/{{$value->id}}" style="color:#000;text-decoration:none;">{{$value->customername}}</a></td>
              <!-- <td>Example</td> -->
              <td><a href="{{url('personnel/myticket/view/')}}/{{$value->id}}" style="color:#000;text-decoration:none;">${{$value->price}}</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    </div>
  </div>
  <div class="card h-auto">
    <div class="card-body">
    <div class="body-content">
      <div id="datepicker" class="calendar"></div>
        <div class="available-header my-2 text-center">
          <h4 style="display: none;"><span id="spanid" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h4>
        </div>
    </div>
    </div>
  </div>
</div>
<div class="col-lg-6 mb-4">
<div class="card h-auto mb-4">
<div class="card-body">
<form method="post" action="{{ route('worker.noteupdate') }}" enctype="multipart/form-data">
        @csrf
<div class="card-content showtime">
  <div class="show-time mb-4 text-center">
    <h6>{{ date('F d, Y') }}</h6>
    
    <h3>{{@$workhours->totalhours}}</h3>
  </div>
  <div class="time-inout text-center row justify-content-center">
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
        <h3> {{@$workhours->starttime}}</h3>
        <p>Time In </p>
      </div>
    </div>
    <div class="col-lg-4 mb-4">
      <div class="text-center time-out-toogle">
        <h3>{{@$workhours->endtime}}</h3>
        <p>Time Out </p>
      </div>
    </div>
  </div>
</div>

  <div class="row justify-content-center" id="time-out-toogle" style="display:none;">
  <div class="col-lg-4 mb-4">
    <div class="text-center time-out-toogle">
      <h3> {{@$workhours->endtime}}</h3>
      <p>Time Out </p>
    </div>
  </div>
  <div class="col-lg-4 mb-4">
    <div class="text-center hours">
      <h3>{{@$workhours->totalhours}}</h3>
      <!-- <p>Hours </p> -->
    </div>
  </div>
</div>

<div class="time-note" style="display: none;">
  <textarea class="form-control mb-4" placeholder="Notes" name="note">{{@$workhours->note}}</textarea>
  @php
  if($workhours!=null) {
    $clash = "";
  } else {
    $clash = "disabled";
  }
  
  @endphp
  <div class="text-center">
    <button type="submit" class="btn add-btn-yellow py-2 px-5" {{$clash}}>Submit</button>
  </div>
  
</div>
</form>
</div>
</div>
<!-- timesheetoverview start -->
  <div class="card h-auto hidetimesheet">
    <div class="card-body">
     <div class="card-content">
     <h5 class="mb-4">Time Sheet Overview</h5>
     @if(count($timesheetdata)>0)
     <div class="table-responsive" style="overflow-y: auto;height: 300px;">
      <table class="table no-wrap table-new table-list" style="position: relative;">
        <thead>
          <tr>
            <th>Sr. Nu.</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Hours</th>
          </tr>
        </thead>
        <tbody>
          @php
            $i=1;
          @endphp
          @foreach($timesheetdata as $value)
          <tr>
            <td>#{{$i}}</td>
            <td class="blue-light">{{$value->starttime}}</td>
            <td class="light-color">{{$value->endtime}}</td>
            <td>{{$value->totalhours}}</td>
          </tr>
          @php
            $i++;
          @endphp
          @endforeach
        </tbody>
      </table>
      </div>
      @else
        <span>No record found.</span>
      @endif
    </div>
  </div>
</div>
<!-- timesheetoverview end -->
</div>
</div>
</div>
 <form id="form3" method="post" action="{{ route('worker.updatehours') }}" enctype="multipart/form-data">
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

          <div class="mb-2">
            <label>Clock in time</label>
            <input type="text" id="timepicker" name="timepicker" value="{{@$workhours->starttime}}" class="form-control form-control-2" placeholder="Clock in time" required="">
          </div>
          <div class="mb-2">
            <label>Clock out time</label>
            <input type="text" id="timepicker1" name="timepicker1" value="{{@$workhours->endtime}}" class="form-control form-control-2" placeholder="Clock out time" required="">
          </div>
          <div class="row">
            <div class="col-lg-6 mb-2">
              <a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</a>
            </div>
            <div class="col-lg-6 mb-2">
              <!-- button type="submit" class="btn btn-add btn-block">Submit Request</button> -->
              <input type="button" id="button3" value="Submit" class="btn btn-add btn-block">
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
    $( "#button3" ).click(function() {
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
    $( "#form3" ).submit();
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

$('#datepicker').datepicker({
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
           $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;width:400px;">');
           $('.bladedata').css('display','none');

          $.ajax({
            url:"{{url('personnel/timesheet/leftbartimesheet')}}",
            data: {
              fulldate: fulldate
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('.hidetimesheet').html(data.html);
              $('.showtime').html(data.htmltimecontent);
            }
          })
         
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
          gettimedata(fulldate);
        }
  });

  function gettimedata(fulldate) {
    $.ajax({
        url:"{{url('personnel/scheduler/gettimedata')}}",
        data: {
          fulldate: fulldate
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $("#timepicker").val(data.starttime);
          $("#timepicker1").val(data.endtime);
        }
    })
  }

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
// $(document).on('click','.time-in',function(e) {
// // $(".time-in").click(function() {
//   $("#time-in-toogle").show();
//   $("#time-out-toogle").hide();
// });

// $(document).on('click','.time-out',function(e) {
// // $(".time-out ").click(function(){
//  $("#time-in-toogle").hide();
//   $("#time-out-toogle").show();
// });

$('#selector').delay(2000).fadeOut('slow');
</script>
@endsection


