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
      <h5>Manage Product Feature Content</h5>
     	<div class="col-md-12 mb-3 mt-3 text-end">
     		<button  data-bs-toggle="modal" data-bs-target="#add-product" class="btn btn-primary">Add</button></div>
      <div class="col-md-12">
	   <table class="table no-wrap table-new table-list align-items-center" id="example">
	  <thead>
	  <tr>
	  <th>Sr. Nu.</th>
	  <th>Product Feature</th>
    <th>Image</th>
	  <th>Status</th>
	  <th>Action</th>
	  </tr>
	  </thead>
	  <tbody>
	  	@php
	  		$i=1;
	  	@endphp
	  	@foreach($productfeature  as $key => $value)
      @php
        if($value->image!=null) {
              $imagepath = url('/').'/uploads/productchecklist/thumbnail/'.$value->image;
        } else {
            $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }
      @endphp
	  <tr>
	  <td>{{$i}}</td>
	  <td>{{Str::limit($value->productfeature, 80) }}</td>
    <td><img src="{{$imagepath}}" style="width:50px;height:50px;"></td>
	  <td>{{$value->status}}</td>
	  <td>
	  	<i class="fa fa-trash-o text-danger" aria-hidden="true" id="delete" class="user-hover" data-id="{{$value->id}}"></i>
	  	<i class="fa fa-pencil-square-o text-success" data-bs-toggle="modal" data-bs-target="#edit-feature" id="editfeature" class="user-hover" data-id="{{$value->id}}"></i>
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
<form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.productfeaturestore')}}" enctype="multipart/form-data">
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
	   Product Feature Description
	   <textarea class="form-control" name="description" id="description" required="" cols="10" rows="5"></textarea>
	   </div> 
     <div class="col-md-12 mb-3">
      <div style="">Image</div>
      <input type="file" class="dropify" name="image" id="image" data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg" required>
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

	<form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.productfeatureupdate')}}" enctype="multipart/form-data">
		@csrf
		<div class="modal fade" id="edit-feature" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered">
		    <div class="modal-content">
		      <div class="modal-body">
		        <div id="viewpmodal"></div>
		      </div>
		    </div>
		  </div>
		</div>
	</form>
</div>
@endsection
<script src="{{ asset('js/jquery.min.js')}}"></script>
 <script src="{{ asset('js/dropify.js')}}"></script>
  
<script type="text/javascript">
  $('.dropify').dropify();
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
$(document).on('click','#editfeature',function(e) {
   var id = $(this).data('id');
   $.ajax({
        url:'{{route('superadmin.viewproductfeaturemodal')}}',
        data: {
          'id':id,
           '_token': '{{csrf_token()}}',
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          console.log(data.html);
          $('#viewpmodal').html(data.html);
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
                url:"{{route('superadmin.productfeaturedelete')}}",
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