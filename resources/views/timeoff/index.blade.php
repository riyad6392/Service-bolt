@extends('layouts.header')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
<style type="text/css">
  #loader1 {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
.loadershow1 {
    height: 100%;
    position: absolute;
    left: 0;
    width: 100%;
    z-index: 10;
    background: rgb(35 35 34 / 43%);
    padding-top: 15%;
    display: flex;
    justify-content: center;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.modal-ul-box {
    padding: 0;
    margin: 10px;
    height: 340px;
    overflow-y: scroll;
}
.modal-ul-box li {
    list-style: none;
    margin: 14px;
   
    padding:10 0px;
   
}
.btn.btn-sve {
    background: #FEE200;
    padding: 8px 34px;
    box-shadow: 0px 0px 10px #ccc;
}
.accept-btn {
    background-color: limegreen;
    color: #fff;
    padding: 5px 8px!important;
}
.reject-btn {
    background-color: red;
    color: #fff;
    padding: 5px 8px!important;
}

tr.rejected-row:after {
    background-color: #FFCCCB !important;
     width: 72%!important;
}


tr.accepted-row:after {
    background-color: #90EE90 !important;
     width: 72%!important;
}
</style>
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3 d-flex justify-content-between">
       <h3>PTO</h3>
     
       
     </div>
     </div>
     @if(Session::has('success'))

<div class="alert alert-success" id="selector">

    {{Session::get('success')}}

</div>

@endif

     <div class="col-md-12">
      <div class="card">
     <div class="card-body">
     <div class="row">
      <div class="col-md-12 text-end mb-4">
           <a href="#" data-bs-toggle="modal" data-bs-target="#request-modal" style="background: #FEE200;color: #000;
    margin: 0;
    padding: 10px 20px;
    border-radius: 13px;
    text-decoration: none;
    position: relative;
    ">
        Add PTO +
      </a>
      </div>
      <form method="post" action="{{route('company.searchtimeoff') }}" class="row pe-0">
      @csrf
      <input type="hidden" name="phiddenid" id="phiddenid" value="">
      <div class="col-lg-3 mb-3">
        <select class="form-select puser" name="pid" id="pid" required="">
          <option value="all">All</option> 
          @foreach($personnelUser as $key => $value)
            <option value="{{$value->id}}" @if(@$personnelid ==  $value->id) selected @endif>{{$value->personnelname}}</option>
          @endforeach
        </select>
      </div>
      @if(isset($from))
        <div class="col-lg-3 mb-3">
          <input type="text" id="since1" value="{{$from}}" name="since" class="form-control" readonly>
        </div>
        <div class="col-lg-3 mb-3">
          <input type="text" id="until1" value="{{$to}}" name="until" class="form-control" readonly>
        </div>
      @else
        <div class="col-lg-3 mb-3">
          <input type="text" id="since" value="{{$currentdate}}" name="since" class="form-control" readonly>
        </div>
        <div class="col-lg-3 mb-3">
          <input type="text" id="until" value="{{$currentdate}}" name="until" class="form-control" readonly>
        </div>
      @endif
      <div class="col-lg-3 mb-3 pe-0" >
       <button class="btn btn-block button" type="submit" id="search1">Search</button>
      </div>

     </div>
     </form>
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th style="display: none;">Id</th>
              <th>NAME</th>
              <th>Day</th>
              <th style="width:300px!important">Notes</th>
              <th style="">Req. By</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($stimesheetData))
            @foreach($stimesheetData as $key => $value)
            @php
              if($value->submitted_by!="") {
                 $submittedby = $value->submitted_by; 
              } else {
                $submittedby = "-";
              }
              $classname = "";
              if($value->status == "Rejected") {
                $classname = "rejected-row";
              }
              if($value->status == "Accepted") {
                $classname = "accepted-row";
              }
            @endphp
            <tr class="{{$classname}}">
              <td style="display: none;">{{$value->id}}</td>
              <td>{{Str::limit($value->personnelname, 15)}}</td>
              <td>{{--$value->date1--}}{{$value->counttotal}} Day
              <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#date-list-dot" id="date_list_dot" data-id="{{$value->ids}}">
		      <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
		    </svg>
              </td>
              <td style="white-space:break-spaces;width:300px!important">{{Str::limit($value->notes, 60)}}</td>
              <td>{{$submittedby}}</td>
              <td>
                @if($value->status!=null)
                  @if($value->status == 'Accepted')
                  
                  <a class="btn btn-edit accept-btn p-3 w-auto" id="accept" data-id="{{$value->ids}}" style="pointer-events:none;">{{$value->status}}</a>

                  <a class="btn btn-edit reject-btn p-3 w-auto" data-bs-toggle="modal" data-bs-target="#date-list-edit" id="date_list_edit" data-id="{{$value->ids}}" data-dates="{{$value->selectdates}}" data-notes="{{$value->notes}}" data-workeid="{{$value->workerid}}">Edit</a>

                  <a class="btn btn-edit reject-btn p-3 w-auto" id="delete" data-id="{{$value->ids}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                  
                  @else
                <a class="btn btn-edit reject-btn p-3 w-auto" id="accept" data-id="{{$value->ids}}" style="pointer-events:none;">{{$value->status}}</a>
                  @endif
                @else  
                  <a class="btn btn-edit accept-btn p-3 w-auto" id="accept" data-id="{{$value->ids}}">Accept</a>
                  <!-- <a class="btn btn-edit reject-btn p-3 w-auto" id="reject" data-id="{{$value->ids}}">Reject</a> -->
                  <a  class="btn btn-edit reject-btn p-3 w-auto" id="rejectpop" data-id="{{$value->ids}}">Reject</a>
                  <!-- <a class="123 btn btn-edit reject-btn p-3 w-auto" id="delete" data-id="{{$value->ids}}">Delete</a> -->
                  
                  <a class="btn btn-edit reject-btn p-3 w-auto" id="delete" data-id="{{$value->ids}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                @endif
              </td>
              
            </tr>
            @endforeach
            @endif
          </tbody>
        </table>
      </div>
     </div>
     </div>
     </div>
     </div>
    </div>
  </div>
  <form method="post" action="{{ route('company.timeoffpto') }}" enctype="multipart/form-data">
  @csrf     
<!-- Modal -->
<div class="modal fade" id="request-modal" tabindex="-1" aria-labelledby="request-modalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <div class="modal-body">
     
    <div class="request-title">
    <div class="add-customer-modal d-flex justify-content-between align-items-center">
    <h5>Request time off</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
       
        <div class="col-md-12 mb-3">
      <select class="form-control selectpicker" data-placeholder="Select Personnel" data-live-search="true" name="personnelid" id="personnelid" required="">
        @foreach($personnelUser as $key => $value)
          <option value="{{$value->id}}">{{$value->personnelname}}</option>
        @endforeach
      </select>
    </div>
      <p>Choose multiple dates needed off</p>
    </div>
    <div class="col-md-12">
    <input type="text" id="datepicker2" name="datepicker2" placeholder="Choose Date" style="cursor: pointer;" class="mb-3 form-control"></div>
    <div id="datepicker21" class="mb-3"></div>
    <div class="available-header my-2 text-center">
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


<div class="modal fade" id="add-reason" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
      <div class="modal-body">
       <div class="add-customer-modal">
     <h5>Add Reason</h5>
     </div>
     <form action="{{route('company.rejecttime')}}" method="post" >
      @csrf
    <div class="row customer-form">
     
    <div class="col-md-12 mb-3">
      <input type="hidden" id="ids" name="ids" value="" >
     	<textarea class="form-control" placeholder="Reason" col="5" row="10" name="reason" id="reason" required=""></textarea>
  	</div>

		<div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal" id="quotecancel1">Cancel</button>
    </div>
    <div class="col-lg-6 mb-3">
     	<button type="submit" class="btn btn-add btn-block">Add Reason</button>
    </div>
    </div>
          </form>
    </div>
    </div>
  </div>
</div>


<div class="modal fade" id="date-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       <div id="viewdatelistdata"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="date-list-edit" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('company.updatetimeoff') }}">
        @csrf
        <input type="hidden" name="workerid" id="workerid" value="">
        <div id="vieweditform"></div>
      </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });

  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
  $(document).on('click','#search12',function(e) {
   //var id = $(this).data('id');
   var id = $("#pid").val();
   var since = $("#since").val();
   var until = $("#until").val();
   $.ajax({
      url:"{{url('company/personnel/leftbarpersonneltimeoffdata')}}",
      data: {
        id: id,
        from :since,
        to :until,
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {
        $('#viewmodaldatatimeoff').html(data.html);
        
      }
    })
  });
$('#selector').delay(2000).fadeOut('slow');

  $(window).on('load', function () {
      $('.loadershow1').hide();
  })

  $('html').on('click','#accept',function() {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.accepttime')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
               swal("Done!","It was succesfully Accepted!","success");
               location.reload();
            }
        })
  });


  $('html').on('click','#rejectpop',function() {
  $('#add-reason').modal('show');
   var id = $(this).data('id');
   $('#ids').val(id);
   //var dataString =  'id='+ id;
  });


$('html').on('click','#delete',function() {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
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
        url:'{{route('company.deleterequest')}}',
        data: dataString,
        method: 'post',
        dataType: 'json',
        refresh: true,
       success:function()
       {
        swal({
           title: "Done!", 
           text: "Deleted Successfully!", 
           type: "success"
        },
        function() { 
               location.reload();
            }
        );
       }
      })
});
  });

$('.puser').on('change', function() {
  var pid = this.value;
  $("#phiddenid").val(pid);
  this.form.submit();
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
           //getleavenote(fulldate,date);
         
        }
  });

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
	      $('#viewdatelistdata').html(data.html);
	    }
	})
 });
$(function () {
  $("#since").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  }).datepicker('update', new Date());

   $("#until").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  }).datepicker('update', new Date());

  $("#since1").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  });

   $("#until1").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  });
});

$('html').on('click','#date_list_edit',function() {
   var id = $(this).data('id');
   var dates = $(this).data('dates');
   var notes = $(this).data('notes');
   var workeid = $(this).data('workeid');
   $("#workerid").val(workeid);
   $.ajax({
        url:"{{url('personnel/edittimeoff')}}",
        data: {
              id: id,
              dates: dates,
              notes: notes 

            },
        method: 'post',
        dataType: 'json',
        refresh: true,
      success:function(data) {
        $('#vieweditform').html(data.html);
          $("#datepicker3").datepicker({
            format: 'yyyy-mm-dd',
            inline: false,
            lang: 'en',
            //step: 5,
            multidate: true,
            closeOnDateSelect: true,
          });
      }
      })
  });
</script>
@endsection


