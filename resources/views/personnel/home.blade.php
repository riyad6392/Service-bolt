@extends('layouts.workerheader')
@section('content')
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
         
       <h3 style="font-weight: bold;" class="align-items-center d-flex justify-content-between">Hello, {{$workername->personnelname}}!

        @if(empty($workerh))
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @elseif(!empty($workerh) && $workerh->date1==null)
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockout" data-id="{{$auth_id}}">Clock Out</a>
        @else
        <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @endif
      </h3>
       <span style="color: #B0B7C3;">{{ date('l (F d, Y)') }}</span>
     </div>
     </div>

     <div class="col-md-6 mb-4">
       <div class="card">
     <div class="card-body">
     <div class="card-content">
     <h5 class="mb-4">Today’s Tickets</h5>
     @if(count($todayservicecall)>0)
     <div class="table-responsive mb-4">
      
        <table class="table no-wrap table-new table-list" style="
    position: relative;
">
          <thead>
            <tr>
              <th>TicketId</th>
              <th>Service</th>
              <th>Customer</th>
              <!-- <th>Category</th> -->
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($todayservicecall as $key =>$value)
            @php
              $ticketid = $value->id;
              if(!empty($value->parentid))
              {
                $ticketid=$value->parentid;
              }
            @endphp
            <tr>
              <td><a href="{{url('personnel/myticket/view/')}}/{{$ticketid}}" style="text-decoration: none;">#{{$ticketid}}</a></td>
             <td><div class="ex-date"><h6>{{$value->servicename}}</h6> <span>{{$value->giventime}}</span></div></td>
              <td>{{$value->customername}}</td>
              <!-- <td>Example</td> -->
              <td>${{$value->price}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        
      </div>
   
    <div class="viewBox">
    <a href="{{url('personnel/myticket')}}" class="btn btn-edit w-100">View All</a>
    </div>
     
    @else
        <div style="text-align: center;">No record Found</div>
     @endif
     
     
     </div>
     </div>
     </div>
     </div>

     <div class="col-md-6 mb-4">

       <div class="card">
        <div class="card-body text-left pt-4">
        <h5 class="mb-4 align-items-center d-flex justify-content-between">Daily Progress <a href="{{route('worker.scheduler')}}" class="btn add-btn-yellow">See Schedule</a></h5>
    <div class="text-center">
    <div class="circle_percent text-center mx-auto" data-percent="{{$dailyprogress}}">
      <div class="circle_inner">
          <div class="round_per"></div>
        </div>
    </div>
    <!-- <div class="watermark">No Record Found</div> -->
    </div>
    
    </div>
          
       </div>
     
     </div>

     <div class="col-md-6 mb-4">
       <div class="card">
      <div class="card-body">
      <div class="card-content">
      <h5 class="mb-4">Customers</h5>
      
      <div class="table-responsive">
        @if(count($customerData)>0)
        <table class="table no-wrap table-new table-list" style="
    position: relative;
">
          <thead>
            <tr>
              
              <th>Customer </th>
              <th>Appointment Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
           
            @foreach($customerData as $key =>$value)
            <tr>
              
              <td>
           <div class="user-descption d-flex">
      <div class="user-img me-2">
        @if($value->image!=null)
          <img src="{{url('uploads/customer/thumbnail')}}/{{$value->image}}" alt="">
        @else
          <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="">
        @endif
      </div>
      <div class="user-content">
      <a href="{{url('company/customer/view/')}}/{{$value->id}}" style="pointer-events: none;">
      <h5>{{$value->customername}}</h5>
      <p>{{$value->servicename}}</p>
      </a>
      </div>
      </div></td>
              <td><div class="user-date">
      <h5>{{$value->givendate}}</h5>
      <p class="ps-md-5">{{$value->giventime}}</p>
      </div></td>
              <td>
                <a href="tel:{{$value->phonenumber}}"><span class="user-icon phone-personnal">
      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="phone-alt" class="svg-inline--fa fa-phone-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"></path></svg>
      </span></a>
    </td>

            </tr>
         
         @endforeach
       
          </tbody>
        </table>
        @else
        <div style="text-align: center;">No record Found</div>
     @endif
      </div>
      

      
      
      </div>
      </div>
      </div>
     </div>

<div class="col-md-6 mb-4">
       <div class="card">
        <div class="card-body">
       
  <div class="custom-calender">
   <div class="ev-calender-title">
   <h3><span id="spanid" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h3>
   <div class="ev-arrow">
   <a class="ev-left" data-id="{{$auth_id}}"></a>
   <a class="ev-right" data-id="{{$auth_id}}"></a>
   </div>
   </div>
   
   <div class="ev-calender-list">
   <div id="showdata"></div>
   <ul class="hidedata">
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
              $ticketid = $value->id;
              if(!empty($value->parentid))
              {
                $ticketid=$value->parentid;
              }
                            
              $f= $i+1;
              $m =   ":00";
              $settimes = date("h:i a", strtotime($times));

              $gtime = explode(":",$value->giventime);
              $gtimeampm = explode(" ",$gtime[1]);

              $giventime = $gtime[0].':00'.' '.$gtimeampm[1];
            
              if($giventime == $settimes) {
                @endphp
                <li class="inner yellow-slide" id="drop_{{$ticketid}}">
        <div class="card">
          <div class="card-body" style="background-color: {{$value->color}};border-radius: 12px;">
            <div class="imgslider" style="display: none;">
              <img src="{{url('uploads/customer/')}}/{{$value->image}}" alt=""/>
            </div>
            <input type="hidden" name="customerid" id="customerid" value="{{$value->customerid}}">
            <input type="hidden" name="quoteid" id="quoteid_{{$value->id}}" value="{{$value->id}}">
            <span style="color: #fff;">#{{$ticketid}}</span>
            <h5 style="color: #fff;">{{$value->customername}}</h5>
            <p>{{$value->servicename}}</p>
            <p>Time : {{$value->giventime}} @if($value->givenendtime!="") to {{$value->givenendtime}}@endif</p>

            <div class="grinding" style="display: block;color: #fff;">
              <a href="#" class="btn btn-edit m-1 w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
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
   <input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">
   @endsection

@section('script')
<script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

  $(document).ready(function() {
      var APP_URL = {!! json_encode(url('/')) !!}

      var fulldate = $("#dateval").val();

      $.ajax({
            url:"{{url('company/home/mapdata')}}",
            data: {
              fulldate: fulldate
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
                var map = new google.maps.Map(document.getElementById('map'), {
                  zoom: 10,
                  center: new google.maps.LatLng(data.html[0][1], data.html[0][2]),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var infowindow = new google.maps.InfoWindow();

                var marker, i;

                for (i = 0; i < data.html.length; i++) {  
                  marker = new google.maps.Marker({
                    position: new google.maps.LatLng(data.html[i][1], data.html[i][2]),
                    map: map
                  });
                  
                  google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                       if(data.html[i][4]!=null){
                        var imgeurl = APP_URL+'/uploads/personnel/'+data.html[i][4];
                      } else {
                        var imgeurl = APP_URL+'/uploads/servicebolt-noimage.png';
                      }
                    var contentString =
                    '<div class="user-box">' +
                    '<div>' +
                    '<img class="mb-2" src="'+imgeurl+'" alt="" style="width:60px;height:60px;border-radius:100%">' +
                    '<span>'+data.html[i][0]+'</span>' +
                    '<p style="margin:0px;font-size:12px;color:#B0B7C3;">Ticket #'+data.html[i][5]+'<span style="font-size:12px;color:black;"> '+data.html[i][6]+' </span></p>' +
                    "</div>" +
                    "</div>";
                      infowindow.setContent(contentString);
                      //infowindow.setContent(data.html[i][0]);
                      infowindow.open(map, marker);
                    }
                  })(marker, i));
                }
            }
        })
    });

  $(document).on('click','.ev-right',function(e) {
    var id = $(this).data('id');
    var date1 = $("#dateval").val();
    var date = new Date(date1);
    var newdate = date.setDate(date.getDate() + 1);
    var d = new Date(newdate);
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
    const dayName = days[d.getDay()];
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
      $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;color:#B0B7C3;width:400px;">');
      getschedulerdata(fulldate,id);
    });

  $(document).on('click','.ev-left',function(e) {
      var id = $(this).data('id');
      var date1 = $("#dateval").val();
      var date = new Date(date1);
      var newdate = date.setDate(date.getDate() - 1);
    var d = new Date(newdate);
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
    const dayName = days[d.getDay()];
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
      $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;color:#B0B7C3;width:400px;">');
      getschedulerdata(fulldate,id);
    });

  function getschedulerdata(fulldate,id) {
    $.ajax({
            url:"{{url('personnel/home/leftbarwdashboardschedulerdata')}}",
            data: {
              fulldate: fulldate,
              id:id
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
               $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;color:#B0B7C3;width:400px;">');
              $('.ev-arrow').hide();
              $('.hidedata').hide();
              $('#showdata').html(data.html);


            }
        })
  }

  $(document).on('click','.clockin',function(e) {
    
  var id = $(this).data('id');
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
          id:id,
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
   
  var id = $(this).data('id');
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
          id:id,
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
</script>
@endsection


