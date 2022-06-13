@extends('layouts.header')
@section('content')
<style type="">
  .bolt-div {
    display: flex;
    align-items: center;
    justify-content: space-evenly;
}
</style>

<div>
  <div class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="side-h3">
         <h3>Scheduler &amp; Dispatch</h3>
         <a href="{{route('company.scheduler')}}" class="back-btn">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
           Back</a>
        </div>
       </div>
       
    </div>
  </div>
</div>


<div class="col-lg-12 mb-4">
  <div class="card">
    <div class="card-body">
      <div class="custom-calender">
       <div class="ev-calender-title">
        <h3><span id="spanid" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y')  }}</span></h3>
        
        <span id="spanid1" style="pointer-events: none;position: absolute;top: 2px;background: green;
      color: #fff;border-radius: 17px;padding: 0 8px;display: none;">Total Ticket Count : <input type="hidden" name="countval" id="countval" value="{{ count($scheduleData) }}">{{ count($scheduleData) }}</span>
         <div class="ev-arrow" style="position: relative;
    right: 200px;">
           <a class="ev-left" data-id="{{$pname->id}}"></a>
           <a class="ev-right" data-id="{{$pname->id}}"></a>
         </div>
         <p><a href="{{route('company.scheduler')}}" class="back-btn">Back To Small Calendar</a></p>
       </div>

 <div class="mt-3" >
  <div class="bolt-div">

            @if($pname->image!=null)
              <img src="{{url('uploads/personnel/thumbnail')}}/{{$pname->image}}" alt="" style="width: 80px!important;border-radius:64px;margin: 0 10px; height: 80px;">
              @else
              <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="" style="width: 80px!important;border-radius:64px;margin: 0 10px; height: 80px;">
              @endif
            <h5 class="mb-0">{{$pname->personnelname}}</h5>

            <span id="spanidhour" style="pointer-events: none;background: #F6F8F9;
    padding: 10px;">Estimated Time: <input type="hidden" name="sethour" id="sethour" value="{{ $sethour }}">{{ $sethour }}</span>
      <span id="spanidminute" style="pointer-events: none;background: #F6F8F9;    padding: 10px;
    position: relative;
    right: 31px;"><input type="hidden" name="sethour" id="sethour" value="    padding: 10px;{{ $setminute }}">{{ $setminute }}</span>
      <span id="spanidprice" style="    pointer-events: none;
    background: #F6F8F9;
    padding: 10px;">Estimated Revenue: <input type="hidden" name="setprice" id="setprice" value="{{ $setprice }}">{{ $setprice }}</span></div>
          </div>



      @php
        $dayarray = array("Mon"=>"MON","Tue"=>"TUE","Wed"=>"WED","Thu"=>"THU","Fri"=>"FRI","Sat"=>"SAT"); 
      @endphp
        <ul class="dew">
          @foreach($dayarray as $daykey =>$dayname)
            <li>{{$dayname}}</li>
          @endforeach
        </ul>
        <div id="ajaxd1"></div>
        <div class="bg-gray showdata">
          <div class="ev-calender-list ">
            <ul class="connectedSortable ui-sortable" id="sortable2">
             @for($i=$userData->openingtime; $i<=$userData->closingtime; $i++)
             <li class="">
               @php
              if($i>=12) {
                $ampm = "PM";
              } else {
                $ampm = "AM";
              }
              $times = $i.":00";
             @endphp
              <div class="ev-calender-hours">{{strtoupper(date("h:i a", strtotime($times)))}}</div>
                <ul class="d-flex w-100">
                 @php
                  $f= $i+1;
                  $m =   ":00";
                  $timev =  $f.$m .' '.$ampm;
                 @endphp
                @foreach($dayarray as $daykey => $dayname)
                  <li class="d-inline-block ui-sortable-handle border-1 radius-5 slidescroll">
                    <input type="hidden" name="timev" id="timev" value="{{date('h:i a', strtotime($times))}}"> 
                  @foreach($scheduleData as $key => $value) 
                    @php
                    $servicecolor = App\Models\Service::select('color')
                      ->where('services.servicename',$value->servicename)->get()->first();
                      $f= $i+1;
                      $m =   ":00";
                      $settimes = date("h:i a", strtotime($times));
                      if($value->giventime == $settimes) {
                         $getdayname = substr($value->givendate, 0, 3);
                        if($getdayname == $daykey) {
                        @endphp
                        <div style="background:{{$servicecolor->color}}!important;color:#fff;padding: 10px;
    border-radius: 10px;">
                          <span>#{{$value->id}}</span> 
                          <h6 class="">{{$value->customername}}</h6>
                          <p>{{$value->servicename}}</p>
                          @if($value->time!=null)
                          {{$value->time}}
                          @endif
                          @if($value->minute!=null)
                          {{$value->minute}}
                          @endif
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
@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
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
            url:"{{url('company/scheduler/personnelschedulerdata')}}",
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
               $("#spanid1").html('Total Ticket Count : <input type="text" value="'+data.countsdata+'" name="countval" id="countval" style="pointer-events: none;position: absolute;top: -1px;background: green;color: #fff;border-radius:17px;padding: 0 8px;width: 25px;border: 0px;height: 25px;margin:0 10px;">');

               $("#spanidhour").html('Estimated Time : <input type="text" value="'+data.hour+'" name="sethour" id="sethour" style="border:none;color:#B0B7C3;width:100px!important;background: transparent;">');
               $("#spanidminute").html('<input type="text" value="'+data.minute+'" name="setminute" id="setminute" style="border:none;color:#B0B7C3;width:100px!important;background: transparent;">');
               $("#spanidprice").html('Estimated Revenue: <input type="text" value="'+data.price+'" name="setprice" id="setprice" style="border:none;color:#B0B7C3;width:100px!important;background: transparent;">');
               //$('.ev-arrow').hide();
              $('#ajaxd1').html(data.html);
              $('.showdata').hide();
            }
        })
    }
</script>
@endsection