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
       <h3>PTO Report</h3>
     
       
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
     
      
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th style="display: none;">Id</th>
              <th>NAME</th>
              <th style="width:300px!important">Assigned</th>
              <th style="width:300px!important">Accepted</th>
              <th style="width:300px!important">Rejected</th>
              <th style="">Total Day</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($stimesheetData))
            @foreach($stimesheetData as $key => $value)
            <tr class="">
              <td style="display: none;">{{$value->id}}</td>
              <td>{{Str::limit($value->personnelname, 15)}}</td>
              <td>@if($value->assigned == 0) -- @else {{$value->assigned}} Day @endif</td>
              <td>@if($value->accepted == 0) -- @else {{$value->accepted}} Day  @endif</td>
              <td>@if($value->rejected == 0) -- @else {{$value->rejected}} Day @endif</td>
              <td>{{$value->counttotal}}</td>
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
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
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


