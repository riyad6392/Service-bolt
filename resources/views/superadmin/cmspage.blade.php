@extends('layouts.superadminheader')
@section('content')
<div class="content-page">
      <div class="content p-3">
     <div class="row">
     	<h5>Manage CMS Page</h5>
      <div class="col-md-12">
     </div>
	<div class="col-md-12 mb-4">
	<div class="card">
	   <div class="card-body">
	   <div class="row">
	   <div class="col-lg-12 mt-2">
	   <div class="table-responsive">
	   <table class="table no-wrap table-new table-list align-items-center" id="example">
	  <thead>
	  <tr>
	  <th>Page Name</th>
	  <th>Description</th>
	  <th>Last Edited</th>
	  <th>Status</th>
	  <th>Action</th>
	  </tr>
	  </thead>
	  <tbody>
	@foreach($cmspage as $key=> $value)
	  	<tr>
		  <td>{{$value->pagename}}</td>
		  <td>{{Str::limit($value->description, 50) }}</td>
		  <td>{{$value->updated_at}}</td>
		  <td>{{$value->status}}</td>
		  <td>
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
	<form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.cmspageupdate')}}">
		@csrf
		<div class="modal fade" id="manage-users" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered">
		    <div class="modal-content">
		      <div class="modal-body">
		        <div id="viewcmspagemodal"></div>
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
                url:"{{route('superadmin.cmspagestatus')}}",
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
                url:"{{route('superadmin.cmspagestatus')}}",
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

	$(document).on('click','#manageusers',function(e) {
	   var id = $(this).data('id');
	   $.ajax({
	        url:'{{route('superadmin.viewcmspagemodal')}}',
	        data: {
	          'id':id,
	           '_token': '{{csrf_token()}}',
	        },
	        method: 'post',
	        dataType: 'json',
	        refresh: true,
	        success:function(data) {
	          console.log(data.html);
	          $('#viewcmspagemodal').html(data.html);
	        }
	    })
	});
</script>