@extends('layouts.superadminheader')
@section('content')
<style type="text/css">
  .modal-content.customer-modal-box .modal-body {
    padding: 0!important;
}
</style>
<div class="content-page">
<div class="content p-3">
     <div class="row">
      <h5>Manage Tenure</h5>
     	<div class="col-md-12 mb-3 mt-3 text-end">
     		<button  data-bs-toggle="modal" data-bs-target="#add-product" class="btn btn-primary">Add</button></div>
      <div class="col-md-12">
	   <table class="table no-wrap table-new table-list align-items-center" id="example">
	  <thead>
	  <tr>
	  <th>Sr. Nu.</th>
	  <th>Tenure Name</th>
	  <th>Days</th>
	  <th>Status</th>
	  <th>Action</th>
	  </tr>
	  </thead>
	  <tbody>
	  	@php
	  		$i=1;
	  	@endphp
	  	@foreach($tentureData as $key => $value)
	  <tr>
	  <td>{{$i}}</td>
	  <td>{{$value->tenturename}}</td>
	  <td>{{$value->day}}</td>
	  <td>{{$value->status}}</td>
	  <td>
	  	<i class="fa fa-trash-o text-danger" aria-hidden="true" id="delete" class="user-hover" data-id="{{$value->id}}"></i>
	  	<i class="fa fa-pencil-square-o text-success" data-bs-toggle="modal" data-bs-target="#manage-users" id="manageusers" class="user-hover" data-id="{{$value->id}}"></i>
	  	<div class="form-switch" style="display: inline;">
        @if($value->status == "Active")
         <a href="#" id="cactive" class="user-hover" data-id="{{$value->id}}">
         <input class="form-check-input" type="checkbox" checked>
         </a>
        @else
         <a href="#" id="cinactive" class="user-hover" data-id="{{$value->id}}">
          <input class="form-check-input" type="checkbox">
         </a>
        @endif
      	</div>
	  </td>
	  </tr>
	  	@php
	  		$i++;
	  	@endphp
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
 </div>   
<!-- Modal -->
<form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.managetenturecreate')}}">
@csrf
<div class="modal fade" id="add-product" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
	   </div>
     <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
	  <div class="row" style="margin: 0 30px;padding: 13px;">
	   <div class="col-md-12 mb-3">
	   Tenure name
	   <input type="text" class="form-control" placeholder="Tenure name" name="tenturename" id="tenturename" required="">
	   </div>
	   <div class="col-md-12 mb-3">Days
	   	<input type="text" class="form-control" placeholder="Days" name="day" id="day" required="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onpaste="return false">
	   </div>
     <div style="text-align: -webkit-center;">
	   <div class="col-lg-6">
	   	<button type="submit" class="btn btn-add btn-block">Save</button>
	   </div>
   </div>
   </div>
	</div>
   </div>
  </div>
</form>

	<form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.tentureupdate')}}">
		@csrf
		<div class="modal fade" id="manage-users" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered">
		    <div class="modal-content">
		      <div class="modal-body">
		        <div id="viewtenturemodal"></div>
		      </div>
		    </div>
		  </div>
		</div>
	</form>
</div>
@endsection
<script src="{{ asset('js/jquery.min.js')}}"></script>  
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "asc" ]]
    });
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
$(document).on('click','#manageusers',function(e) {
   var id = $(this).data('id');
   $.ajax({
        url:'{{route('superadmin.viewtenturemodal')}}',
        data: {
          'id':id,
           '_token': '{{csrf_token()}}',
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $('#viewtenturemodal').html(data.html);
        }
    })
  });

$(document).on('click','#cinactive',function(e) {
    var userid = $(this).data('id');
    var status = "Active";
     swal({
          title: "Are you sure?",
          text: "Are you sure you want to activate this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, active it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
           $.ajax({
                url:"{{route('superadmin.tenturestatus')}}",
                data: {
                'userid':userid,
                'status':'Active',
                 '_token': '{{csrf_token()}}',
              },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Status Activate succesfully!","success");
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

  $(document).on('click','#cactive',function(e) {
    var userid = $(this).data('id');
    var status = "InActive";
    swal({
          title: "Are you sure?",
          text: "Are you sure you want to Inactivate this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, Inactive it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
           $.ajax({
                url:"{{route('superadmin.tenturestatus')}}",
                data: {
                'userid':userid,
                'status':'Inactive',
                 '_token': '{{csrf_token()}}',
              },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Status Inactivate succesfully!","success");
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

  $(document).on('click','#delete',function(e) {
   var id = $(this).data('id');
   swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this!",
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
                url:"{{route('superadmin.tenturedelete')}}",
                data: {
                'id':id,
                'status':'Active',
                 '_token': '{{csrf_token()}}',
              },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Deleted succesfully!","success");
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
</script>