@extends('layouts.workerheader')
@section('content')
<style type="text/css">
 
  .dataTable td.address-warp {
    white-space: normal;
    }
  }
</style>
<div class="content">
     <div class="row">
      <div class="col-md-12">
      <div class="side-h3">
       <h3 class="align-items-center d-flex justify-content-between">My Tickets 
     <div>
     <a href="{{route('worker.timesheet')}}" class="btn btn-dark py-2 px-5">Time Sheet</a>
        
         @if(empty($workerh))
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @elseif(!empty($workerh) && $workerh->date1==null)
          <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockout" data-id="{{$auth_id}}">Clock Out</a>
        @else
        <a href="javascript:void(0);" class="btn add-btn-yellow py-2 px-5 clockin" data-id="{{$auth_id}}">Clock In</a>
        @endif
      </div></h3>
      @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
      </div>

     </div>


     <div class="col-md-12">
       <div class="card">
     <div class="card-body">
     <div class="row" style="display: none;">
      <div class="col-lg-2 mb-2">
        Quick Look</div>
     <div class="col-lg-3 mb-2">
     <div class="show-fillter">
     <select id="inputState" class="form-select">
        <option>Show: A to Z</option>
        <!-- <option>By Service</option>
        <option>By Frequency</option>
        <option>By Company</option> -->
      </select>
     </div>
     </div>
     
     <div class="col-lg-5 offset-lg-1 mb-2" style="visibility: hidden;">
     <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search customers by name"/>
     <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button>
     </div>
     
     </div>
     
     </div>
     
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th>Ticket number</th>
              <th>Customer Name</th>
              <th>Address</th>
              <th>Service</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($ticketdata as $value)
            @php
              $explode_id = explode(',', $value->serviceid);
              $servicedata = App\Models\Service::select('servicename')
                ->whereIn('services.id',$explode_id)->get();
            @endphp
              <tr>
                <td>#{{$value->id}}</td>
                <td>{{$value->customername}}</td>
                <td class="address-warp">{{$value->address}}</td>
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
        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $value->serviceid }}">
          <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
        </svg>
        @endif
        @php
        $i=1; break;
      @endphp
    @endforeach</td>
                <td>
                  @php
                   $sdata = App\Models\Schedulerhours::where('ticketid', $value->id)->get()->first();

                    $quoteData = App\Models\Quote::where('id', $value->id)->get()->first();

                  @endphp
                  
                  @if($quoteData->ticket_status=="2")
                    <a href="{{url('personnel/myticket/view/')}}/{{$value->id}}" class="btn btn-personnal ps-4 pe-4 me-2">View/Edit</a>  
                    <a href="#" class="btn add-btn-yellow ps-4 pe-4" data-bs-toggle="modal" data-bs-target="#ticket-modal" data-id="{{$value->id}}" id="myticketid">Pickup</a>
                  @endif

                  @if($quoteData->ticket_status=="4")
                  <a href="{{url('personnel/myticket/view/')}}/{{$value->id}}" class="btn btn-personnal ps-4 pe-4 me-2">View/Edit</a>  
                     <a href="#" class="btn add-btn-yellow ps-4 pe-4" data-bs-toggle="modal" data-bs-target="#ticket-modal" data-id="{{$value->id}}" id="myticketid">Picked</a>
                  @endif
                  @if($quoteData->ticket_status=="3")
                    <a href="{{url('personnel/myticket/view/')}}/{{$value->id}}" class="btn btn-personnal ps-4 pe-4 me-2">View/Edit</a>
                    <a href="#" class="btn add-btn-yellow ps-4 pe-4" data-bs-toggle="modal" data-bs-target="#ticket-modal" data-id="{{$value->id}}" id="myticketid">Completed</a>
                  @endif
                </td>
              </tr>
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
<div class="modal fade" id="service-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       <div id="viewservicelistdata"></div>
    </div>
  </div>
</div>    
<!-- Modal -->
<div class="modal fade" id="ticket-modal" tabindex="-1" aria-labelledby="ticket-modalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body" style="height: auto;overflow-y: auto;">
       
    <form method="post" action="{{ route('worker.myticketupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata"></div>
      </form>
     </div>
  </div>
</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
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

        console.log(data.html);
        $('#viewservicelistdata').html(data.html);
      }
  })
 });
</script>
@endsection


