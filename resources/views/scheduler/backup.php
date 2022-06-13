@extends('layouts.header')
@section('content')
<style type="text/css">
  .input-container input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: .75rem;
    position: relative;
    width: 100%;
}
i.fa.fa-circle {
    color: #FEE200;
    font-size: 11px;
    margin: 0 10px;
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
</style>
<div class="">
<div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
          <h3>Scheduler & Dispatch</h3>
        </div>
     </div>
  <div class="col-lg-12 mb-4">
    <div class="card">
    <div class="card-body">
      <h5 class="mb-4"></h5>
      <div class="row mb-3">
        <div class="col-lg-12" style="display:flex;justify-content: space-between;align-items: center;">
        <p style="margin: 0px;">Tickets Pending to Assign @if(count($ticketData)>0) ({{count($ticketData)}}) @else (0) @endif</p>
        <a href="#" data-bs-toggle="modal" data-bs-target="#add-tickets" class="add-btn-yellow">
            New Ticket Assign +
          </a>
        </div>
         
        
      </div>


    <div class="card-personal">
       <div class="gallery portfolio_slider slider">
        <ul id="sortable1" class="connectedSortable" style="width:50000px">
        @if(count($ticketData)>0)  
        @foreach($ticketData as $key => $value)
        <li class="inner yellow-slide" id="drop_{{$value->id}}">
        <div class="card">
          <div class="card-body">
            <div class="imgslider">
              @if($value->image!=null)
              <img src="{{url('uploads/customer/thumbnail')}}/{{$value->image}}" alt="" style="width: 80px!important;" />
              @else
              <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="" style="width: 80px!important;">
              @endif
            </div>
            <input type="hidden" name="customerid" id="customerid" value="{{$value->customerid}}">
            <input type="hidden" name="quoteid" id="quoteid_{{$value->id}}" value="{{$value->id}}">
            <!-- <span>#{{$value->id}}</span> -->
            <h5>#{{$value->id}} {{$value->customername}}</h5>
            <p>{{$value->servicename}}</p>
            <div class="grinding">
              <!-- <a href="#" class="btn btn-edit w-auto mb-3"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="5" cy="5" r="5" fill="currentColor" style="display: none;">
              </svg> </a> -->
              {{$value->time}}
              @php
                $date=date_create($value->etc);
                $dateetc = date_format($date,"F d, Y");
              @endphp
              - {{$dateetc}}
              <!-- <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="5" cy="5" r="5" fill="currentColor" style="display: none;">
              </svg>  -->
              
            </div>
          </div>
        </div>
      </li>
      @endforeach

      @endif
    </ul>
      </div>
    </div>
    @if(count($ticketData)==0)  
    <div style="text-align: center;">
        Not found any schedule ticket
    </div>
    @endif
    @if(count($ticketData)>0)
    <div class="use">
  <a href="#0" class="next control"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
  <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
</svg></a>
  <a href="#0" class="prev control">
  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
  <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
</svg></a>
  </div>
@endif


  
    </div>
    </div>
  </div>
  <!-- new logic here -->
    <div class="col-lg-12 mb-4">
<div class="card">
<div class="card-body">
<!--<div id='calendar'></div>-->
<div class="custom-calender">
  <div class="ev-calender-title">
     <h3><span id="spanid" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h3>
     <span id="spanid1" style="pointer-events: none;position: absolute;top: 2px;background: #fb6794;
      color: #fff;border-radius: 17px;padding: 0 8px;">Total Ticket Count : <input type="hidden" name="countval" id="countval" value="{{ count($scheduleData) }}">{{ count($scheduleData) }}</span>

     <div class="ev-arrow" style="display: block;">
       <a class="ev-left"></a>
       <a class="ev-right"></a>
     </div>
  </div>
 <!-- Personel Section -->
  <div class="col-lg-12 bg-white">
    <div class="row py-4" style="margin-left: 112px">
      @foreach($worker as $key => $value)
       @php
        if($value->image!=null) {
          $imageurl = url('uploads/personnel/thumbnail/'.$value->image);
         } else {
          $imageurl = url('uploads/servicebolt-noimage.png');
        }
       @endphp
        <div class="col-lg-2" id="{{$value->id}}">
          <img class="sub" src="{{$imageurl}}">
          <h5 class="dew"><a href="{{url('company/scheduler/detail/')}}/{{$value->id}}">{{$value->personnelname}}</a></h5> 
        </div>
      @endforeach
    </div>
  </div>
 <!-- personel section end -->
 <div id="ajaxd"></div>
 <div class="bg-gray showdata">
   <div class="ev-calender-list">
       <ul class=" ">
        @for($i=8; $i<=23; $i++)
        <li class="">
          
             @php
              if($i>=12) {
                $ampm = "PM";
              } else {
                $ampm = "AM";
              }
             @endphp
          <div class="ev-calender-hours">{{$i+1}}:00 <span>{{$ampm}}</span></div>
          <ul class="d-flex w-100 connectedSortable ui-sortable sortable2" id="">
            @php
              $f= $i+1;
              $m =   ":00";
              $timev =  $f.$m .' '.$ampm;
            @endphp
            @foreach($worker as $key => $value1)
            <li class="d-inline-block ui-sortable-handle border-1 radius-5 slidescroll" style="width: 20%; border-radius:5px; border:1px solid #ccc; background:#fff; margin-right:10px;" id="{{$value1->id}}">
              <div style="display: none;">
                <span style="visibility: hidden;"><p>{{$value1->id}}</p></span>
                <span style="visibility: hidden;"><b>{{$timev}}</b></span>
              </div>
               <input type="hidden" name="workerid" id="workerid" value="{{$value1->id}}"> 
              <input type="hidden" name="timev" id="timev" value="{{$timev}}"> 
               @foreach($scheduleData as $key => $value) 
            @php

              $f= $i+1;
              $m =   ":00";
              if($value->giventime == $f.$m .' '.$ampm) {
                if($value->personnelid == $value1->id) {
                @endphp
            <div>
              <input type="hidden" id="drop_{{$value->id}}">
              <input type="hidden" name="customerid" id="customerid" value="{{$value->customerid}}">
              <input type="hidden" name="quoteid" id="quoteid_{{$value->id}}" value="{{$value->id}}">
              <span>#{{$value->id}}</span> 
              <h6 class="">{{$value->customername}}</h6>
              <a href="javascript:void(0);" class="info_link" dataval="{{$value->id}}"><i class="fa fa-trash" style="position: absolute;right: 56px;top: 20px;"></i></a>
              <p>{{$value->servicename}}</p>
            </div>
             @php
             
              }
            }
            @endphp

          @endforeach 
            
            @endforeach
            </li>
          </ul>
          
        </li>
         @endfor
      </ul>
    </div>
  </div>
 </div>
</div>
</div>
</div>
  <!-- end here -->
 <div class="col-lg-12 mb-4">
<div class="card">
<div class="card-body">
<ul class="nav report-tabs mb-3 justify-content-around" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Calendar</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Map</button>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
<div id="datepicker" class="calendar"></div>
</div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <div id="map" style="height: 400px;"></div>
  <div class="profile-box" style="display: none;">
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3813.5373570383163!2d-84.46274413661!3d39.172793711310554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xfeeb142a2997f69f!2sFive%20Stars%20mediterranean%20restaurant!5e0!3m2!1sen!2sin!4v1627981141372!5m2!1sen!2sin" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  <div class="user-box">
  <div class="card">
  <div class="card-body">
  <img class="mb-2" src="images/placeholder-1.png" alt=""/>
  <h3 class="mb-3">Jeson Smith</h3>
  
  <div>
  <a href="#" class="add-btn-yellow btn-block mb-3">Contact</a>
  <a href="#" class="btn btn-edit w-100 mb-3">See Schedule</a>
  </div>
  </div>
  </div>
  </div>
  </div>
  
  </div>
</div>

<div class="text-center mt-4">
<a class="btn btn-edit mb-3 w-75">Dispatch Settings</a>
</div>
</div>
</div>
</div> 

<div class="col-lg-6 mb-4">
<div class="card">
<div class="card-body">
<!--<div id='calendar'></div>-->
<!-- <div class="custom-calender">
   <div class="ev-calender-title">
   <h3><span id="spanid" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h3>
   <span id="spanid1" style="pointer-events: none;position: absolute;top: 2px;background: #fb6794;
    color: #fff;border-radius: 17px;padding: 0 8px;"><input type="hidden" name="countval" id="countval" value="{{ count($scheduleData) }}">{{ count($scheduleData) }}</span>

   <div class="ev-arrow" style="display: none;">
   <a class="ev-left"></a>
   <a class="ev-right"></a>
   </div>
   </div>
   
   <div class="ev-calender-list">
    
     <ul class="connectedSortable showdata" id="sortable2">
      @for($i=8; $i<=23; $i++)
         @php
          if($i>=12) {
            $ampm = "PM";
          } else {
            $ampm = "AM";
          }
         @endphp
          <li><div class="ev-calender-hours">{{$i+1}}:00 <span>{{$ampm}}</span></div></li>
          @foreach($scheduleData as $key => $value) 
            @php

              $f= $i+1;
              $m =   ":00";
              if($value->giventime == $f.$m .' '.$ampm) {
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
            <h5><i class="fa fa-circle" aria-hidden="true"></i>{{$value->customername}}</h5>
            <a href="javascript:void(0);" class="info_link" dataval="{{$value->id}}"><i class="fa fa-trash" style="position: absolute;right: 56px;top: 30px;"></i></a>
            <p><i class="fa fa-circle" aria-hidden="true"></i>{{$value->servicename}}</p>
            <p>Personnel Name - {{$value->personnelname}}</p>
            <div class="grinding" style="display: block;">
              <a href="#" class="btn btn-edit w-auto"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="5" cy="5" r="5" fill="currentColor" style="display: none;">
              </svg> {{$value->time}}</a>
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

  </div> -->
</div>
</div>
</div>

</div>
   </div>
</div>
<!--  new ticket assign modal -->
      <div class="modal fade" id="add-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal">
      <h5>Add A New Ticket</h5>
     </div>
      @if(count($productData)>0)
      @else
     <div style="color: red;">Step1: Please add a product in the products section.</div>
     @endif

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
     @if(count($worker)>0)
     
      @else
        @if(count($productData)==0 && count($services)==0 && count($customer)==0)
        <div style="color: red;">Step4: Please add a personnel in the personnel section.</div>
        <div style="color: red;">Step5: Then please create a new tickets/quote here.</div> 
      @elseif(count($productData)>0)
        <div style="color: red;">Step3: Please add a personnel in the personnel section.</div>
        <div style="color: red;">Step4: Then please create a new tickets/quote here.</div>
      @elseif(count($services)>0)
        <div style="color: red;">Step2: Please add a personnel in the personnel section.</div>
        <div style="color: red;">Step3: Then please create a new tickets/quote here.</div>
      @elseif(count($customer)>0)
        <div style="color: red;">Step1: Please add a personnel in the personnel section.</div>
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
    <div class="col-md-6 mb-3">
      <select class="form-select {{$cname}}" name="servicename" id="servicename" required="">
      <option selected="" value="">Select a Service </option>
      @foreach($services as $key =>$value)
        <option value="{{$value->id}}">{{$value->servicename}}</option>
      @endforeach
    </select>
     </div>
     
     <div class="col-md-6 mb-3">
      <select class="form-select {{$wname}}" name="personnelid" id="personnelid" required>
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
          <option name="Weekly" value="Weekly">Weekly</option>
          <option name="Be weekly" value="Be weekly">Be weekly</option>
          <option name="Monthly" value="Monthly">Monthly</option>
    </select>
     </div>
     
     <div class="col-md-6 mb-2">
      <select class="form-select" name="time" required="">
          <option selected="" value="">Default Time</option>
          <option value="15 Minutes">15 Minutes</option>
          <option value="30 Minutes">30 Minutes</option>
          <option value="45 Minutes">45 Minutes</option>
          <option value="1 Hours">1 Hours</option>
        </select>
     </div>

     <div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="Price" name="price" id="price" required>
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
      <button type="submit" class="btn btn-add btn-block" type="submit">Add a Service</button>
     </div>
     
     <div class="col-lg-12">
     <button class="btn btn-dark btn-block btn-lg p-2"><img src="images/share-2.png"  alt=""/> Share</button>
     </div>
     </div>
    </form> 
  </div>
  </div>
</div>
    <!-- end ticket assign modal  -->
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

   jQuery(function() {
   jQuery('.showSingle').click(function() {
        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        $.ajax({
            url:"{{url('company/personnel/leftbarservicedata')}}",
            data: {
              targetid: targetid,
              serviceid: serviceid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
            }
        })
    });

    $.ajax({
            url:"{{url('company/personnel/leftbarservicedata')}}",
            data: {
              targetid: 0,
              serviceid: 0 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
            }
        })

  });

  //$(function() {
    $("#sortable1, .sortable2").sortable({
      connectWith: ".connectedSortable"
      // update: function(event, ui) {
      //       var ids = $(this).sortable('toArray').toString();
      //       alert(ids);
            
      //   }
    }).disableSelection();
     $(".sortable2").sortable({

        update: function(event, ui) {
          //alert($("#workerid").val());
          var ids = $(this).sortable('toArray').toString();
          var drops =  ids.split('drop_');
          console.log('dropid',drops);
          var lastItem = drops.pop();
          var quoteid = lastItem.split(',');
          var customerid = $("#customerid").val();
          var workerid =  $("#drop_"+quoteid[0]).prev().find('span p').text();
          var timev =  $("#drop_"+quoteid[0]).prev().find('span b').text();
         // var timev = $(".timev").val(); 
          var dateval = $("#dateval").val();
          // alert(timev);
          // alert(workerid);
          //return false;
          if(timev == "") {
            location.reload();
            return false;
          }

          if(workerid == "") {
            location.reload();
            return false;
          }

          form_data = new FormData();
          form_data.append('quoteid',quoteid[0]);
          form_data.append('time',timev);
          form_data.append('date',dateval);
          form_data.append('workerid',workerid);
          $.ajax({
            url:"{{url('company/scheduler/sortdata')}}",
            method:"POST",
            data:form_data,
            cache:false,
            contentType:false,
            processData:false,
            success:function() {
              location.reload();
            }
          });
        }
    });
  //});

  $(function() {
  
      var slideCount =  $(".slider ul li").length;
      var slideWidth =  $(".slider ul li").width();
      var slideHeight =  $(".slider ul li").height();
      var slideUlWidth =  slideCount * slideWidth;
      
      /*$(".slider").css({"max-width":slideWidth, "height": slideHeight});*/
      //$(".slider ul").css({"width":slideUlWidth});
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
            url:"{{url('company/scheduler/leftbarschedulerdata')}}",
            data: {
              fulldate: fulldate
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {

              console.log(data.html);
               $("#spanid1").html('Total Ticket Count : <input type="text" value="'+data.countsdata+'" name="countval" id="countval" style="pointer-events: none;position: absolute;top: -1px;background: #fb6794;color: #fff;border-radius:17px;padding: 0 8px;width: 25px;border: 0px;height: 25px;margin:0 10px;">');
              $('#ajaxd').html(data.html);
              $('.showdata').hide();
               // $("#sortable1, .sortable2").sortable();   
               //     $( "#sortable1, .sortable2" ).disableSelection();    

               //$("#sortable1, .sortable2").sortable
               $("#sortable1, .sortable2").sortable({
      connectWith: ".connectedSortable"
      // update: function(event, ui) {
      //       var ids = $(this).sortable('toArray').toString();
      //       alert(ids);
            
      //   }
    }).disableSelection();
     $(".sortable2").sortable({

        update: function(event, ui) {
          //alert($("#workerid").val());
          var ids = $(this).sortable('toArray').toString();
          var drops =  ids.split('drop_');
          console.log('dropid',drops);
          var lastItem = drops.pop();
          var quoteid = lastItem.split(',');
          var customerid = $("#customerid").val();
          var workerid =  $("#drop_"+quoteid[0]).prev().find('span p').text();
          var timev =  $("#drop_"+quoteid[0]).prev().find('span b').text();
         // var timev = $(".timev").val(); 
          var dateval = $("#dateval").val();
          // alert(timev);
          // alert(workerid);
          //return false;
          if(timev == "") {
            location.reload();
            return false;
          }

          if(workerid == "") {
            location.reload();
            return false;
          }

          form_data = new FormData();
          form_data.append('quoteid',quoteid[0]);
          form_data.append('time',timev);
          form_data.append('date',dateval);
          form_data.append('workerid',workerid);
          $.ajax({
            url:"{{url('company/scheduler/sortdata')}}",
            method:"POST",
            data:form_data,
            cache:false,
            contentType:false,
            processData:false,
            success:function() {
              location.reload();
            }
          });
        }
    });
            }
        })
  }

  $("#profile-tab").on("click",function() {
      var APP_URL = {!! json_encode(url('/')) !!}

      var fulldate = $("#dateval").val();
      $.ajax({
            url:"{{url('company/scheduler/mapdata')}}",
            data: {
              fulldate: fulldate
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
               //console.log(data.html.length==0);
               // var locations = [
               //              ['California', 38.296452, -76.508827],
               //              ['Maryland', 39.045753, -76.641273]
               //            ];
               //            console.log(locations);
                // $.each(data, function(key, value) {
                //   // var aaa = split(',',value);
                //   // var locations = [
                //   //           ['California', 38.296452, -76.508827],
                //   //           ['Maryland', 39.045753, -76.641273]
                //   //         ];
                //           //console.log(aaa);
                // });
                //           // var locations = [
                          //   ['California', 38.296452, -76.508827],
                          //   ['Maryland', 39.045753, -76.641273]
                          // ];
                          if(data.html.length==0) {
                            var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 10,
                            //center: new google.maps.LatLng(38.892059, -77.019913),
                            center: new google.maps.LatLng(38.892059, -77.019913),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                          });
                          } else {
                            var map = new google.maps.Map(document.getElementById('map'), {
                              zoom: 10,
                              //center: new google.maps.LatLng(38.892059, -77.019913),
                              center: new google.maps.LatLng(data.html[0][1], data.html[0][2]),
                              mapTypeId: google.maps.MapTypeId.ROADMAP
                            });
                        }

                          var infowindow = new google.maps.InfoWindow();

                          var marker, i;

                          for (i = 0; i < data.html.length; i++) {  
                            marker = new google.maps.Marker({
                              position: new google.maps.LatLng(data.html[i][1], data.html[i][2]),
                              map: map,
                              icon: {
                                 url: APP_URL+'/uploads/personnel/thumbnail/'+data.html[i][3],
                                 size: new google.maps.Size(36, 36),
                                 scaledSize: new google.maps.Size(36, 36),
                                 anchor: new google.maps.Point(0, 50),
                              }
                            });
                            
                            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                              return function() {
                                if(data.html[i][3]!=null){
                                  var imgeurl = APP_URL+'/uploads/personnel/thumbnail/'+data.html[i][3];
                              } else {
                                  var imgeurl = APP_URL+'/uploads/servicebolt-noimage.png';
                              }
                              var contentString =
                              '<div class="user-box">' +
                              '<div class="card">' +
                              '<div class="card-body">' +
                              '<img class="mb-2" src="'+imgeurl+'" alt="">' +
                              '<h3 class="mb-3">'+data.html[i][0]+'</h3>' +
                              '<div>' +
                              '<a href="tel:'+data.html[i][4]+'" class="add-btn-yellow btn-block mb-3">Contact</a>' + 
                              '<a href="#" class="btn btn-edit w-100 mb-3">See Schedule</a>' +
                              "</div>" +
                              "</div>" +
                              "</div>" +
                              "</div>";
                                infowindow.setContent(contentString);
                                //infowindow.setContent(data.html[i][0]);
                                infowindow.open(map, marker);
                              }
                            })(marker, i));
                          }
              // console.log(data.html[0]);
              // console.log(data.html[1]);
              //$('.showdata').html(data.html);
            }
        })
    });
  $('html').on('click','.etc',function() {
  var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
    $('#etc').attr('min', maxDate);
  
 });

  $('html').on('click','.info_link1',function() {
      var ticketid = $(this).attr('dataval');
      swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this schedule!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
           $.ajax({
            url:"{{url('company/scheduler/deleteTicket')}}",
            data: {
              targetid: ticketid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
             swal("Done!","It was succesfully deleted!","success");
              location.reload();
            }
        })
          } 
          else {
            location.reload();
          }
        }
      );
  
 });

  

 $(function(){
  $('.info_link').click(function() {
    var ticketid = $(this).attr('dataval');
      swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this schedule!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
           $.ajax({
            url:"{{url('company/scheduler/deleteTicket')}}",
            data: {
              targetid: ticketid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
             swal("Done!","It was succesfully deleted!","success");
              location.reload();
             // $('#viewleftservicemodal').html(data.html);
            }
        })
          } 
          else {
            location.reload();
          }
        }
      );
    
    });
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
@endsection