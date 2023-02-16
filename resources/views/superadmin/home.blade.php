@extends('layouts.superadminheader')
@section('content')
<div class="content-page">
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3 style="font-weight: bold;">DashBoard</h3>
     </div>
     </div>

     <div class="col-lg-6">
           <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                       <i class="fa fa-user-o widget-icon" aria-hidden="true"></i>
                    </div>
                    <h5 class="header-title m-0" title="Number of Customers">Total Clients {{$totalclientlast}}</h5>
                    <h6 class=" mb-3">(In Last 7 Days)</h6>
                    <p class="mb-0 text-success">
                     <span class="float-end">Total Clients {{$totalclient}}</span>
                    </p>
                </div>
            </div>
            </div>

         <div class="col-lg-6">
           <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                       <i class="fa fa-usd widget-icon" aria-hidden="true"></i>
                    </div>
                    <h5 class="header-title m-0" title="Number of Customers">Total Subscription {{$totalbalancesheetlast}}</h5>
                    <h6 class=" mb-3">(In Last 7 Days)</h6>
                    <p class="mb-0 text-success">
                     <span class="float-end">Total Subscription {{$balancesheettotal}} </span>
                    </p>
                </div>
            </div>
            </div>


     <div class="col-md-12  mt-4 mb-4">
      <div class="card">
    <div class="card-body">
    <div class="card-header transperent">
    <h5 class="d-flex align-items-center justify-content-between m-0">Revenue Graph 
    <ul class="line-chart-weekly list-inline">
    
    </ul></h5>
    </div>
     <div id="line-chart" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
    </div>
    </div>
     </div>

     </div>
   </div>
 </div>
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
                        var imgeurl = APP_URL+'/uploads/personnel/thumbnail/'+data.html[i][4];
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
            url:"{{url('worker/home/leftbarwdashboardschedulerdata')}}",
            data: {
              fulldate: fulldate,
              id:id
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
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
        url:"{{url('worker/home/workerclockhoursin')}}",
        data: {
          starttime: current_time,
          id:id,
          fulldate: fulldate,
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
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
        url:"{{url('worker/home/workerclockhoursout')}}",
        data: {
          endtime: current_time,
          id:id,
          fulldate: fulldate,
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $('.clockin').show();
          $('.clockout').hide();
          location.reload();
        }
    }) 
  });
</script>
@endsection


