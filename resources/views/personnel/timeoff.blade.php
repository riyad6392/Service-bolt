@extends('layouts.workerheader')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>PTO</h3>
  <a href="#" data-bs-toggle="modal" data-bs-target="#request-modal" style="background: #FEE200;color: #000;margin: 0;padding: 10px 20px;border-radius: 13px;text-decoration: none;position: relative;left: 922px;bottom: 25px;">Add PTO</a>
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
              <th style="display: none;"></th>
              <th>Date</th>
              <th>Note</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($timeoff as $key => $value)
              <tr>
                <td style="display: none;">{{$value->id}}</td>
                <td>{{-- $value->date --}}{{$value->counttotal}} Day
                  <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#date-list-dot" id="date_list_dot" data-id="{{$value->ids}}">
                  <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
                  </svg>
                </td>


                <td style="white-space:break-spaces;">{{$value->notes}}</td>
                <td>
                  @if($value->status==null)
                  Pending
                  @else
                  {{$value->status}} @if($value->reason!="") ({{$value->reason}}) @endif
                @endif</td>
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
   <form method="post" action="{{ route('worker.timeoffpto') }}" enctype="multipart/form-data">
  @csrf     
<!-- Modal -->
<div class="modal fade" id="request-modal" tabindex="-1" aria-labelledby="request-modalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <div class="modal-body">
    <div class="request-title">
       <h5>PTO</h5>
        <p>Choose multiple dates needed off</p>
    </div>
     <div class="col-md-12">
    <input type="text" id="datepicker2" name="datepicker2" placeholder="Choose Date" style="cursor: pointer;" class="mb-3 form-control" required></div>
    <div id="datepicker2" class="mb-3"></div>
    <div class="available-header my-2 text-center" style="display: none;">
      <h4><span id="spanid4" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h4>
    </div>
    <input type="hidden" name="leavedate" id="leavedate" value="{{date('Y-m-d')}}">
  <div class="time-note mb-2">
    <textarea class="form-control mb-4" placeholder="Notes" name="notes" id="notes" required></textarea>
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
</div>
<div class="modal fade" id="date-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       <div id="viewdatelistdata"></div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });

  let text = window.location.href;
  const myArray = text.split("#");
  if(myArray[1] == "t") {
    $("#request-modal").modal('show');
  }
      
  });
  $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });




  $("#datepicker2").datepicker({
    format: 'yyyy-mm-dd',
    inline: false,
    lang: 'en',
    //step: 5,
    multidate: true,
    closeOnDateSelect: true,
    startDate: new Date()
  });

  $('#datepicker2old').datepicker({
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
           $("#spanid4").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;width:400px;">');
           $('.bladedata').css('display','none');
           getleavenote(fulldate,date);
         
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

  $(document).on('click','#date_list_dot',function(e) {
   var id = $(this).data('id');
   var name = "Dates";
  
    $.ajax({
      url:'{{route('company.viewdatepopup')}}',
      data: {
        'id':id,
        'name':name
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {
        //alert(data);
        console.log(data.html);
        $('#viewdatelistdata').html(data.html);
      }
    })
  });
</script>
@endsection


