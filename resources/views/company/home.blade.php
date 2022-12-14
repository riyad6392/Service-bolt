@extends('layouts.header')
@section('content')
<style type="text/css">
  img[src$="#custom_marker"]{
    /*border: 2px solid #000 !important;*/
    border-radius:50%;
}
</style>
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3 style="font-weight: bold;">Hello, {{Auth::user()->firstname}}  {{Auth::user()->lastname}}!</h3>
       <span style="color: #B0B7C3;">{{ date('l (F d, Y)') }}</span>
     </div>
     </div>

     <div class="col-md-7 mb-4">
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
      <a href="{{url('company/customer/view/')}}/{{$value->id}}">
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
                <a href="tel:{{$value->phonenumber}}"><span class="user-icon">
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

<div class="col-md-5 mb-4">
       <div class="card">
      <div class="card-body">
      <div class="card-content">
      <h5 class="mb-4 d-flex align-items-center justify-content-between ref-icon">Inventory Status <a href=""><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sync-alt" class="svg-inline--fa fa-sync-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path></svg></a></h5>
      @if(count($inventoryData)>0)  
     <div class="table-row mb-4">
      <div class="row">
      <div class="col-8">
      Item Name
      </div>
       <div class="col-4 text-truncate">
    Status
      </div>
      </div>
      </div>
    
    {{-- @foreach($inventoryinfo as $key => $value)--}}  
      <div class="row align-items-center mb-4">
        <div class="col-2 text-center">
         @if(!empty($goodproduct[0]))
          <span class="icon-arrow mb-2">
             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
             <path fill-rule="evenodd" d="M21.03 5.72a.75.75 0 010 1.06l-11.5 11.5a.75.75 0 01-1.072-.012l-5.5-5.75a.75.75 0 111.084-1.036l4.97 5.195L19.97 5.72a.75.75 0 011.06 0z" fill="currentColor"></path></svg>
          </span>
        @endif
        @if(!empty($lowproduct[0]))
          <span class="icon-arrow mb-2 red-light">
             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
             <path fill-rule="evenodd" d="M21.03 5.72a.75.75 0 010 1.06l-11.5 11.5a.75.75 0 01-1.072-.012l-5.5-5.75a.75.75 0 111.084-1.036l4.97 5.195L19.97 5.72a.75.75 0 011.06 0z" fill="currentColor"></path></svg>
          </span>
        @endif
         @if(!empty($restockproduct[0]))
          <span class="icon-arrow mb-2 red-light">
             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
             <path fill-rule="evenodd" d="M21.03 5.72a.75.75 0 010 1.06l-11.5 11.5a.75.75 0 01-1.072-.012l-5.5-5.75a.75.75 0 111.084-1.036l4.97 5.195L19.97 5.72a.75.75 0 011.06 0z" fill="currentColor"></path></svg>
          </span>
        @endif
        </div>
        <div class="col-6">
          <div class="clsoo text-truncate">
            @if(!empty($goodproduct[0]))
              <h5><a href="{{route('company.inventory')}}" target="_blank" style="color:#000;text-decoration:none;">{{$goodproduct[0]}}</a></h5>
              <p class="mb-2">{{ date('l d, H:i a', time()) }}</p>
            @endif
            @if(!empty($lowproduct[0]))
              <h5><a href="{{route('company.inventory')}}" target="_blank" style="color:#000;text-decoration:none;">{{$lowproduct[0]}}</a></h5>
              <p class="mb-2">{{ date('l d, H:i a', time()) }}</p>
            @endif
            @if(!empty($restockproduct[0]))
              <h5><a href="{{route('company.inventory')}}" target="_blank" style="color:#000;text-decoration:none;">{{$restockproduct[0]}}</a></h5>
              <p class="mb-2"> {{ date('l d, H:i a', time()) }}</p>
            @endif
          </div>
        </div>
        <div class="col-4">
          @if(!empty($goodproduct[0]))
           <a href="#" class="btn btn-green truncate mb-3 w-85">Good</a>
          @endif
          
          @if(!empty($lowproduct[0]))
           <a href="#" class="btn red-light-bg w-85 mb-3  truncate">Low</a>
          @endif
          
          @if(!empty($restockproduct[0]))
           <a href="#" class="btn red-light-bg mb-3 " style="width: 125px;">Needs to be restocked</a>
          @endif
        </div>
      </div>
    {{--@endforeach--}}
      @else
      <div style="text-align: center;">No Record Found</div>
      @endif
      <div class="row align-items-center mb-4" style="display: none;">
      <div class="col-2 text-center">
      <span class="icon-arrow">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
      <path fill-rule="evenodd" d="M21.03 5.72a.75.75 0 010 1.06l-11.5 11.5a.75.75 0 01-1.072-.012l-5.5-5.75a.75.75 0 111.084-1.036l4.97 5.195L19.97 5.72a.75.75 0 011.06 0z" fill="currentColor"></path></svg>
      </span>
      </div>
      
      <div class="col-6">
      <div class="clsoo text-truncate">
      <h5>Gasoline</h5>
      <p>Today, 11:54 AM</p>
      </div>
      </div>
      <div class="col-4">
      <a href="#" class="btn btn-green truncate w-85">All good</a>
      </div>
      
      </div>
      
      
      <div class="row align-items-center mb-4" style="display: none;">
      <div class="col-2 text-center">
      <span class="icon-arrow red-light">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M5.75 8.5a.75.75 0 00-.75.75v9c0 .414.336.75.75.75h9a.75.75 0 000-1.5H7.56L17.78 7.28a.75.75 0 00-1.06-1.06L6.5 16.44V9.25a.75.75 0 00-.75-.75z"></path></svg>
      </span>
      </div>
      
      <div class="col-6">
        <div class="clsoo text-truncate">
          <h5>Weed Eater String</h5>
        <p>May 5, 11:54 AM</p>
        </div>
      </div>
      <div class="col-4">
      <a href="#" class="btn red-light-bg w-85 truncate">Low</a>
      </div>
      
      </div>
      
      <div class="row align-items-center mb-4" style="display: none;">
      <div class="col-2 text-center">
      <span class="icon-arrow red-light">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M4.97 13.22a.75.75 0 000 1.06l6.25 6.25a.75.75 0 001.06 0l6.25-6.25a.75.75 0 10-1.06-1.06l-4.97 4.97V3.75a.75.75 0 00-1.5 0v14.44l-4.97-4.97a.75.75 0 00-1.06 0z"></path></svg>
      </span>
      </div>
      
      <div class="col-5">
      <div class="clsoo text-truncate">
      <h5>Lawn Mower Blades</h5>
      <p>May 5, 11:54 AM</p>
      </div>
      </div>
      <div class="col-5">
      <a href="#" class="btn red-light-bg truncate">Needs to be restocked</a>
      </div>
      
      </div>
      
      
      </div>
      </div>
      </div>
     </div>

     <div class="col-md-7 mb-4">
       <div class="card">
      <div class="card-body">
        
        <h5 class="mb-4 d-flex align-items-center justify-content-between ref-icon">Personnel Location <a class="livelocationupdate"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sync-alt" class="svg-inline--fa fa-sync-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path></svg></a></h5>
        @if($usersaddress->company_address!="")
          Office Address : {{$usersaddress->company_address}}<br><br>
        @endif
        @if(count($scheduleData)>0)
            <div id="map"></div>
        @else
          @if($usersaddress->company_address!="")
          <div id="map"></div>
          <input type="hidden" name="lat" id="lat" value="{{$usersaddress->latitude}}">
          <input type="hidden" name="long" id="long" value="{{$usersaddress->longitude}}">
          @else
           <div class="position-relative">
            <div id="map"></div>
            <div class="watermark">No Record Found</div>
          </div>
          @endif
        @endif
       </div>
       </div>
     </div>
     <div class="col-md-5 mb-4">
       <div class="card">
        <div class="card-body">
        <h5 class="mb-4">Top Services</h5>
  
  <!-- Canvas for Chart in added footer -->
  <div class="position-relative">
  <canvas id="canvas" height="147"></canvas>
  <!-- <div class="watermark">No Record Found</div> -->
  </div>

  <div class="row mt-4">

    
    <input type="hidden" name="cservice" id="cservice" value="{{count($serviceinfo)}}">
    
  @foreach($serviceinfo as $key => $value)
   
    <div class="col-lg-6 mb-3">
      <div class="serices">
        <a href="#" style="white-space: normal;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          
          <path fill="{{$value->color}}" d="M12 18a6 6 0 100-12 6 6 0 000 12z"></path>
         
        </svg> {{$value->servicename}}</a>
      </div>
    </div>
    
  @endforeach
  </div>
 
  
</div>
  
 
  
 

        
       </div>
     </div>

     <div class="col-md-7 mb-4">
    
    <div class="card">
    <div class="card-body">
     <h5 class="mb-4">Tickets</h5>
     <div class="table-responsive">
    @if(count($ticket)>0)
     <table class="table no-wrap table-new table-list align-items-center" style="
    position: relative;
"><thead>
            <tr>
              <th>No</th>
              <th class="text-truncate">Service Name</th>
              <th>Date</th>
              <th>Price</th>
              <th>Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ticket as $key =>$value)
            
            <tr>
            
            <td><a class="btn w-auto" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$value->id}}">{{$value->id}}</a></td>
              <td class="text-truncate"><a class="btn w-auto" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$value->id}}">{{$value->servicename}}</a></td>
              <td>{{date("m-d-Y", strtotime($value->etc));}}</td>
              <td>${{$value->price}}</td>
              <td>{{$value->time}} {{$value->minute}}</td>
              @if($value->ticket_status == "3")
                <td><a class="btn-pending btn-block" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$value->id}}">Completed</a></td>
              @elseif($value->ticket_status == "4")
              <td><a class="btn-pending btn-block" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$value->id}}">Picked</a></td>
              @else
                <td><a class="btn-pending btn-block" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$value->id}}">Pending</a></td>
              @endif
      
            </tr>
      
            @endforeach
          </tbody>
        </table>
      
     @else 
     <div style="text-align: center;position: absolute;top: 50%;left: 38%;">No Record Found</div>
    @endif
     
     
     
     
    </div>
    
    </div>
    </div>
   
    
    
     
     </div>

     <div class="col-md-5 mb-4">

       <div class="card">
        <div class="card-body text-center pt-4">
        <h5 class="mb-4" style="text-align: left;">Daily Progress</h5>
      <div class="position-relative">
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

     <div class="col-md-12 mb-4 d-none">
      <div class="card">
     <div class="card-body">
     <div class="card-header transperent">
     <h5 class="d-flex align-items-center justify-content-between m-0">Reports 
     <ul class="line-chart-weekly list-inline">
     <li><a href="javascript:void(0)">Daily</a></li>
     <li><a href="javascript:void(0)">Weekly</a></li>
     <li><a href="javascript:void(0)" class="active">Monthly</a></li>
     </ul></h5>
     </div>
     <div class="position-relative">
      <div id="line-chart" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
      <div class="watermark">No Record Found</div>
     </div>
     </div>
     </div>
     </div>

     </div>
   </div>
   <input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">
   
<!-- view tickets on popup -->
   <div class="modal fade" id="view-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <div id="viewcompletedmodal"></div>
      </div>
    </div>
  </div>
</div>
<!-- end -->
   @endsection

@section('script')
<script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function() {
    
    $(document).on('click','#viewTickets',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewcompleteticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewcompletedmodal').html(data.html);
            }
        })
  });

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
      //  if (navigator.geolocation) {
      //   navigator.geolocation.getCurrentPosition(getPosition);
      // } else {
      //   x.innerHTML = "Geolocation is not supported by this browser.";
      // }

      // function getPosition(position) {
      //   var lat = position.coords.latitude;
      //   var long = position.coords.longitude;
      //   $("#lat").val(lat);
      //   $("#long").val(long);     
      // }

      //center: new google.maps.LatLng(36.778259, -119.417931),
      $.ajax({
            url:"{{url('company/home/mapdata')}}",
            data: {
              fulldate: fulldate
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              if(data.html.length == "0") {
                      var lat = $("#lat").val();
                      var long = $("#long").val();

                      if(lat == "0") {
                        var lat = "36.778259";
                        var long = "-119.417931";
                      }
                          var locations = [
                          ['', lat, long,4]
                         
                        ];
                          var map = new google.maps.Map(document.getElementById('map'), {
                              zoom: 6,
                              center: new google.maps.LatLng(lat, long),
                              mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            var infowindow = new google.maps.InfoWindow();

                            var marker, i;

                            for (i = 0; i < locations.length; i++) {  
                              marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                map: map
                              });

                              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                return function() {
                                  infowindow.setContent(locations[i][0]);
                                  infowindow.open(map, marker);
                                }
                              })(marker, i));
                            }
                    } else {
                          //console.log("latlong",data);
                          var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 10,
                            center: new google.maps.LatLng(data.html[0][1], data.html[0][2]),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                           
                          });

                          var infowindow = new google.maps.InfoWindow();

                          var marker, i;

                          for (i = 0; i < data.html.length; i++) { 
                            //var imgestatic = "https://cdn-icons-png.flaticon.com/512/1023/1023448.png"; 
                            if(data.html[i][7] == "online") {
                              var imgestatic = APP_URL+'/images/greenmapimage.png';
                            } else {
                              var imgestatic = APP_URL+'/images/graymapimage.png';
                            }
                            marker = new google.maps.Marker({
                              position: new google.maps.LatLng(data.html[i][1], data.html[i][2]),
                              map: map,
                              icon: {
                                 //url: APP_URL+'/uploads/personnel/thumbnail/'+data.html[i][4]+ '#custom_marker',
                                 url: imgestatic,
                                 size: new google.maps.Size(36, 36),
                                 scaledSize: new google.maps.Size(36, 36),
                                 anchor: new google.maps.Point(0, 50),
                                
                              }
                            });
                            
                            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                              return function() {
                                 if(data.html[i][4]!=null){
                                  var imgeurl = APP_URL+'/uploads/personnel/thumbnail/'+data.html[i][4];
                                } else {
                                  var imgeurl = APP_URL+'/uploads/servicebolt-noimage.png';
                                }
                            if(data.html[i][5]!=null) {
                              var contentString =
                              '<div class="user-box">' +
                              '<div>' +
                              '<img class="mb-2" src="'+imgeurl+'" alt="" style="width:60px;height:60px;border-radius:100%">' +
                              '<span style="font-weight:bold"> '+data.html[i][0]+'</span>' +
                              '<p style="margin:0px;font-size:12px;color:#;font-weight:bold;">Ticket #'+data.html[i][5]+'<span style="font-size:12px;color:black;"> '+data.html[i][6]+' </span></p>' +
                              "</div>" +
                              "</div>";

                                infowindow.setContent(contentString);
                            } else {
                              var contentString =
                              '<div class="user-box">' +
                              '<div>' +
                              '<img class="mb-2" src="'+imgeurl+'" alt="" style="width:60px;height:60px;border-radius:100%">' +
                              '<span style="font-weight:bold"> '+data.html[i][0]+'</span>' +
                              "</div>" +
                              "</div>";

                                infowindow.setContent(contentString);
                            }
                                //infowindow.setContent(data.html[i][0]);
                                infowindow.open(map, marker);
                              }
                            })(marker, i));
                          }
                }

          //end setimrout      
            }
        })
    });


        // function updatePosition(newlat,newlong) {
        //   const latlong = {lat:newlat ,long:newlong};
        //   marker.setPosition(latlong);
        //   map.setCenter(latlong);
        // }
          // Pusher.logToConsole = true;
          // var pusher = new Pusher('21ccb81c25e86ff20bc3', {
          //     cluster: 'ap2'
          //   });

          //   var channel = pusher.subscribe('my-channel');
            
          //   channel.bind('send-location', function(data) {
          //     console.log(data);
          //     console.log("hello");
          //     //updatePosition(data.lat,data.long);  
          //   });

setInterval(function() { 
  $(".livelocationupdate").trigger('click');
}, 600000);

$('.livelocationupdate').click(function() {
  var APP_URL = {!! json_encode(url('/')) !!}

      var fulldate = $("#dateval").val();
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getPosition);
      } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
      }

      function getPosition(position) {
        var lat = position.coords.latitude;
        var long = position.coords.longitude;
        $("#lat").val(lat);
        $("#long").val(long);     
      }

      $.ajax({
            url:"{{url('company/home/mapdata')}}",
            data: {
              fulldate: fulldate
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              if(data.html.length == "0") {
                  var lat = $("#lat").val();
                  var long = $("#long").val();
                   if(lat == "") {
                      var lat = "36.778259";
                      var long = "-119.417931";
                    }
                          var locations = [
                          ['', lat, long,4]
                         
                        ];
                          var map = new google.maps.Map(document.getElementById('map'), {
                              zoom: 6,
                              center: new google.maps.LatLng(lat, long),
                              mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            var infowindow = new google.maps.InfoWindow();

                            var marker, i;

                            for (i = 0; i < locations.length; i++) {  
                              marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                map: map
                              });

                              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                return function() {
                                  infowindow.setContent(locations[i][0]);
                                  infowindow.open(map, marker);
                                }
                              })(marker, i));
                            }
                    } else {
                          //console.log("latlong",data);
                          var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 10,
                            center: new google.maps.LatLng(data.html[0][1], data.html[0][2]),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                           
                          });

                          var infowindow = new google.maps.InfoWindow();

                          var marker, i;

                          for (i = 0; i < data.html.length; i++) {
                              if(data.html[i][7] == "online") {
                                var imgestatic = APP_URL+'/images/greenmapimage.png';
                              } else {
                                var imgestatic = APP_URL+'/images/graymapimage.png';
                              }  
                              marker = new google.maps.Marker({
                              position: new google.maps.LatLng(data.html[i][1], data.html[i][2]),
                              map: map,
                              icon: {
                                 //url: APP_URL+'/uploads/personnel/thumbnail/'+data.html[i][4]+ '#custom_marker',
                                 url: imgestatic,
                                 size: new google.maps.Size(36, 36),
                                 scaledSize: new google.maps.Size(36, 36),
                                 anchor: new google.maps.Point(0, 50),
                                
                              }
                            });
                            
                            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                              return function() {
                                 if(data.html[i][4]!=null){
                                  var imgeurl = APP_URL+'/uploads/personnel/thumbnail/'+data.html[i][4];
                                } else {
                                  var imgeurl = APP_URL+'/uploads/servicebolt-noimage.png';
                                }
                            if(data.html[i][5]!=null) {
                              var contentString =
                              '<div class="user-box">' +
                              '<div>' +
                              '<img class="mb-2" src="'+imgeurl+'" alt="" style="width:60px;height:60px;border-radius:100%">' +
                              '<span style="font-weight:bold"> '+data.html[i][0]+'</span>' +
                              '<p style="margin:0px;font-size:12px;color:#;font-weight:bold;">Ticket #'+data.html[i][5]+'<span style="font-size:12px;color:black;"> '+data.html[i][6]+' </span></p>' +
                              "</div>" +
                              "</div>";

                                infowindow.setContent(contentString);
                            } else {
                              var contentString =
                              '<div class="user-box">' +
                              '<div>' +
                              '<img class="mb-2" src="'+imgeurl+'" alt="" style="width:60px;height:60px;border-radius:100%">' +
                              '<span style="font-weight:bold"> '+data.html[i][0]+'</span>' +
                              "</div>" +
                              "</div>";

                                infowindow.setContent(contentString);
                            }
                                //infowindow.setContent(data.html[i][0]);
                                infowindow.open(map, marker);
                              }
                            })(marker, i));
                          }
            }

            }
        })
})

function CustomMarker(latlng, map, imageSrc) {
    this.latlng_ = latlng;
    this.imageSrc = imageSrc;
    // Once the LatLng and text are set, add the overlay to the map.  This will
    // trigger a call to panes_changed which should in turn call draw.
    this.setMap(map);
}
</script>
<script>
var colorcount = $("#cservice").val();
var canvas = document.getElementById("canvas");

var tooltipCanvas = document.getElementById("tooltip-canvas");
for(var i=1; i<=colorcount; i++) {
  if (i == "1") {
    var gradientBlue = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
    gradientBlue.addColorStop(0, '{{@$serviceinfo[0]->color}}');
  }

  if (i == "2") {
    var gradientRed = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
    gradientRed.addColorStop(0, '{{@$serviceinfo[1]->color}}');
  }

  if (i == "3") {
    var gradientGrey = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
    gradientGrey.addColorStop(0, '{{@$serviceinfo[2]->color}}');
  }
  if (i == "4") {
    var gradientGreen = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
    gradientGreen.addColorStop(0, '{{@$serviceinfo[3]->color}}');
  }
}



window.arcSpacing = 0.15;
window.segmentHovered = true;

function textInCenter(value, label) {
  var ctx = tooltipCanvas.getContext('2d');
  ctx.clearRect(0, 0, tooltipCanvas.width, tooltipCanvas.height)
  
   ctx.restore();
    
  // Draw value
  ctx.fillStyle = '#333333';
  ctx.font = '24px sans-serif';
  ctx.textBaseline = 'middle';

  // Define text position
  var textPosition = {
    x: Math.round((tooltipCanvas.width - ctx.measureText(value).width) / 2),
    y: tooltipCanvas.height / 2,
  };

  ctx.fillText(value, textPosition.x, textPosition.y);

  // Draw label
  ctx.fillStyle = '#AAAAAA';
  ctx.font = '8px sans-serif';

  // Define text position
  var labelTextPosition = {
    x: Math.round((tooltipCanvas.width - ctx.measureText(label).width) / 2),
    y: tooltipCanvas.height / 2,
  };

  ctx.fillText(label, labelTextPosition.x, labelTextPosition.y - 20);
  ctx.save();
}

Chart.elements.Arc.prototype.draw = function() {
  var ctx = this._chart.ctx;
  var vm = this._view;
  var sA = vm.startAngle;
  var eA = vm.endAngle;

  ctx.beginPath();
  ctx.arc(vm.x, vm.y, vm.outerRadius, sA + window.arcSpacing, eA - window.arcSpacing);
  ctx.strokeStyle = vm.backgroundColor;
  ctx.lineWidth = vm.borderWidth;
  ctx.lineCap = 'round';
  ctx.stroke();
  ctx.closePath();
};

var config = {
    type: 'doughnut',
    data: {
        labels: ['{{@$serviceinfo[0]->servicename}}', '{{@$serviceinfo[1]->servicename}}', '{{@$serviceinfo[2]->servicename}}', '{{@$serviceinfo[3]->servicename}}'],
        datasets: [
          {
              data: [{{@$serviceinfo[0]->total}}, {{@$serviceinfo[1]->total}}, {{@$serviceinfo[2]->total}}, {{@$serviceinfo[3]->total}}],
              backgroundColor: [
               gradientBlue,
               gradientRed,
               gradientGrey,
               gradientGreen,
              ],
          }
        ]
    },
    options: {
         cutoutPercentage: 80,
         elements: {
         arc: {
            borderWidth: 12,
          },
        },
        legend: {
         display: false,
        },
        animation: {
         onComplete: function(animation) {
            if (!window.segmentHovered) {
              var value = this.config.data.datasets[0].data.reduce(function(a, b) { 
                return a + b;
              }, 0);
              var label = 'T O T A L';

              textInCenter(value, label);
            }
          },
        },
        tooltips: {
          callbacks: {
            title: function(tooltipItem, data) {
              return data['labels'][tooltipItem[0]['index']];
            },
            label: function(tooltipItem, data) {
              return data['datasets'][0]['data'][tooltipItem['index']];
            },
            afterLabel: function(tooltipItem, data) {
              var dataset = data['datasets'][0];
              var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
              return '(' + percent + '%)';
            }
          },
          backgroundColor: '#FFF',
          titleFontSize: 16,
          titleFontColor: '#0066ff',
          bodyFontColor: '#000',
          bodyFontSize: 14,
          displayColors: false
        },
        // tooltips: {
        //  enabled: true,
        //  custom: function(tooltip) {
        //     if (tooltip.body) {
        //       var line = tooltip.body[0].lines[0],
        //        parts = line.split(': ');
        //       textInCenter(parts[1], parts[0].split('').join(' ').toUpperCase());
        //       window.segmentHovered = true;
        //     } else {
        //        window.segmentHovered = false;
        //     }
        //   },
        // },
    },
};

window.chart = new Chart(canvas, config);
</script>
@endsection


