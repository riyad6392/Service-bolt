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
      <h5>Manage Checklist</h5>
     	<div class="col-md-12 mb-3 mt-3 text-end">
     		<button  data-bs-toggle="modal" data-bs-target="#new-list" class="btn btn-primary">Add</button>
     	</div>
      <div class="col-md-12">
	   <table class="table no-wrap table-new table-list align-items-center" id="example">
	  <thead>
	  <tr>
	  <th>Sr. Nu.</th>
	  <th>Checklist</th>
	  <th>Image</th>
	  <th>Status</th>
	  <th>Action</th>
	  </tr>
	  </thead>
	  <tbody>
	  	@php
	  		$i=1;
	  	@endphp
	  	@foreach($checklistdata as $key => $value)
	  	@php
	  	if($value->image!=null) {
            $imagepath = url('/').'/uploads/checklist/thumbnail/'.$value->image;
         } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }
        @endphp
	  <tr>
	  <td>{{$i}}</td>
	  <td>{{$value->checklist}}</td>
	  <td><img src="{{$imagepath}}" style="width:50px;height:50px;"></td>
	  <td>{{$value->status}}</td>
	  <td>
	  	<i class="fa fa-trash-o text-danger" aria-hidden="true" id="delete" class="user-hover" data-id="{{$value->id}}"></i>
	  	<a class="" data-bs-toggle="modal" data-bs-target="#edit-checklist" id="editchecklist" data-id="{{$value->id}}" data-name="{{$value->checklist}}"><i class="fa fa-edit"></i></a>
	  	<div class="form-switch" style="display: inline;">
        @if($value->status == "Active")
         <a href="#" id="cactive" class="user-hover" data-id="{{$value->id}}" style="display: none;">
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
<form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.manageChecklistcreate')}}" enctype="multipart/form-data">
@csrf
<div class="modal fade" id="new-list" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <div class="modal-body">
       <div class="add-customer-modal">
	   <h5>Add a new Checklist</h5>
	   <!-- <p class="lead">Lorem ipsum dolar si amen</p> -->
	   </div>
	   <div class="row customer-form" id="product-box-tabs">
	   <div class="col-md-12">
	   	 	<div class="row" style="align-items: center;">
			  <div class="col-md-8 dynamic-field" id="dynamic-field-1">
			    <div class="row" >
			        <div class="col-md-12">
			        <div class="staresd">
			          <div class="imgup">
			            <input type="text" placeholder="text here" class="form-control" style="margin-bottom: 5px;" name="point[]" required="">
			            <input type="file" class="form-control" style="margin-bottom: 5px;" name="image[]" accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
					  </div>
			        </div>
			      </div>
			    </div>
			  </div>
			  <div class="col-md-4 append-buttons" style="margin-top: 5px">
			    <div class="clearfix">
			      <button type="button" id="add-button" class="btn btn-secondary float-left text-uppercase shadow-sm"><i class="fa fa-plus fa-fw"></i>
			      </button>
			      <button type="button" id="remove-button" class="btn btn-secondary float-left text-uppercase ml-1" disabled="disabled"><i class="fa fa-minus fa-fw"></i>
			      </button>
			    </div>
			  </div>
			</div>
	    </div>
	   <div class="col-lg-6 mb-3">
	   	<button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6 mb-3">
	   	<button type="submit" class="btn btn-add btn-block">Submit</button>
	   </div>
	</div>
   </div>
  </div>
</div>
</div>
</form>

	<form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.featureupdate')}}">
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

	<div class="modal fade" id="edit-checklist" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('superadmin.updatechecklist') }}" enctype="multipart/form-data">
          @csrf
          <div id="vieweditaddressmodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>
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

$(document).on('click','#editchecklist',function() {
      var cid = $(this).data('id');
      var cname = $(this).data('name');
        $.ajax({
            url:'{{route('superadmin.vieweditchecklistmodal')}}',
            data: {
              'cid':cid,
              'cname':cname,
              '_token': '{{csrf_token()}}',
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#vieweditaddressmodaldata').html(data.html);
            }
        })
    });

$(document).on('click','#manageusers',function(e) {
   var id = $(this).data('id');
   $.ajax({
        url:'{{route('superadmin.viewfeaturemodal')}}',
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
                url:"{{route('superadmin.featurestatus')}}",
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
    var status = "Inactive";
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
                url:"{{route('superadmin.featurestatus')}}",
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
                url:"{{route('superadmin.ckdelete')}}",
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