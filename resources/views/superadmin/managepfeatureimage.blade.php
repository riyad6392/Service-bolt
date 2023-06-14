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
      <h5>Manage Product Feature Image</h5>
     	<div class="col-md-12">
	   <table class="table no-wrap table-new table-list align-items-center">
	  <thead>
	  <tr>
	  <th>Title</th>
	  <th>Image</th>
	  <th>Action</th>
	  </tr>
	  </thead>
	  <tbody>
	  	@php
	  		$i=1;
	  	@endphp
	  	@foreach($featureData as $key => $value)
	  	@php
	  	if($value->feature_img!=null) {
            $imagepath = url('/').'/uploads/featureimg/thumbnail/'.$value->feature_img;
         } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }
        @endphp
	  <tr>
	  <td>Product Feature Image</td>
	  <td><img src="{{$imagepath}}" style="width:100px;height:100px;"></td>
	  <td>
	  	<a class="" data-bs-toggle="modal" data-bs-target="#edit-pfeatureimg" id="editpfeatureimg" data-name="{{$value->feature_img}}"><i class="fa fa-edit"></i></a>
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

	<div class="modal fade" id="edit-pfeatureimg" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content customer-modal-box">
          <div class="modal-body">
            <form method="post" action="{{ route('superadmin.updatepimage') }}" enctype="multipart/form-data">
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

$(document).on('click','#editpfeatureimg',function() {
      var cname = $(this).data('name');
        $.ajax({
            url:'{{route('superadmin.vieweditmodal')}}',
            data: {
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