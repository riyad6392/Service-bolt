@extends('layouts.superadminheader')
@section('content')

<style type="">
  .table-list h5 {
    margin: 0px 0px 1px;
    font-size: 16px;
    color: #29DBBA;
}
</style>

<div class="content-page">
  <div class="content p-3">
     <div class="row">
      <h5>Manage Users</h5>
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
        <table id="example" class="table no-wrap table-new table-list" style="position: relative;">
          <thead>
          <tr>
            <th style="display: none;"></th>      
            <th>Reg.Date</th>
            <th>Company Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Expiring on</th>
            <th></th>
            <th>Subscription</th>
            <th>Status</th>
            </tr>
        </thead>
     <tbody>
    @foreach($users as $user)  
 <tr>
  <td style="display: none;">{{$user->id}}</td>
 <td>{{date('j M Y', strtotime($user->created_at))}}</td>
 <td>
  <a href="#" data-bs-toggle="modal" data-bs-target="#manage-users" id="manageusers" class="user-hover" data-id="{{$user->id}}"> 
  <!-- <a class="user-hover" data-bs-toggle="modal" data-bs-target="#manage-users" id="manageusers" data-id="{{$user->id}}"> -->
  <div class="user-descption align-items-center d-flex">
    <div class="user-content">
      <h5 class="m-0">{{$user->companyname}}</h5>
    </div>
  </div>
     </a>
  </td>
    <td>{{$user->email}}</td>
    <td>{{$user->phone}}</td>
       @php
        $time = strtotime($user->created_at);
       @endphp
       <td>{{date("j M Y", strtotime("+1 year", $time))}}</td>
       <td><a href="{{url('superadmin/manageUser/userlogin/')}}/{{$user->id}}" title="login" class="btn btn-primary">Account Login</a></td>
       <td>Active</td>
       <td>
      <div class="form-switch">
        @if($user->status == "Active")
         <a href="#" id="cactive" class="user-hover" data-id="{{$user->id}}">
         <input class="form-check-input" type="checkbox" checked>
         </a>
        @else
         <a href="#" id="cinactive" class="user-hover" data-id="{{$user->id}}">
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
  <!-- start here -->
    <div class="modal fade" id="manage-users" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div id="viewusermodal"></div>
      </div>
    </div>
  </div>
</div>
  <!-- end here -->
  </div>
 </div>
  @endsection
<script src="{{ asset('js/jquery.min.js')}}"></script>  
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


  $(document).on('click','#manageusers',function(e) {
   var userid = $(this).data('id');
   $.ajax({
        url:'{{route('superadmin.viewusermodal')}}',
        data: {
          'userid':userid,
           '_token': '{{csrf_token()}}',
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success:function(data) {
          $('#viewusermodal').html(data.html);
        }
    })
  });

  $(document).on('click','#cinactive',function(e) {
    var userid = $(this).data('id');
    var status = "Active";
     swal({
          title: "Are you sure?",
          text: "Are you sure you want to activate this user!",
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
                url:"{{route('superadmin.userstatus')}}",
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
            location.reload(); //swal("Cancelled", "Your customer is safe :)", "error");
          }
        }
      );
  });

  $(document).on('click','#cactive',function(e) {
    var userid = $(this).data('id');
    var status = "InActive";
    swal({
          title: "Are you sure?",
          text: "Are you sure you want to Inactivate this user!",
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
                url:"{{route('superadmin.userstatus')}}",
                data: {
                'userid':userid,
                'status':'InActive',
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

  $(document).on('click','#deleteuser',function(e) {
    var userid = $(this).data('id');
    swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this user!",
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
                url:"{{route('superadmin.userdelete')}}",
                data: {
                'userid':userid,
                 '_token': '{{csrf_token()}}',
              },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","User Deleted succesfully!","success");
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


