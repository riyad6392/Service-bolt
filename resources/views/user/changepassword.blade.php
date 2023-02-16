@extends('layouts.header')
@section('content')
<div class="">
<div class="content">
     <div class="row">
      	<div class="col-md-12">
	        <div class="side-h3">
	       		<h3>Change Password</h3>
	      	</div>
 		</div>
 		

<div class="col-md-12 mb-4">
<div class="card">
	   <div class="card-body">
	   <div class="row">
	   		@if(Session::has('success'))
			<div class="alert alert-success">
		    	{{Session::get('success')}}
			</div>
			@endif
			
			@if(session('error'))
    			<div class="alert alert-danger">{{session('error')}}</div>
			@endif
	   		<form method="post" action="{{ route('company.updatepassword') }}" enctype="multipart/form-data">
        		@csrf
				<div class="form-group clearfix">
                <label for="password">Current Password</label><span class="required">*</span><br> 
                <input class="form-control @error('old_password') is-invalid @enderror" id="password" placeholder="Password" name="old_password" type="password" value="{{ old('old_password') }}" required="">
                <span id="toggle_pwd" class="fa fa-fw fa-eye field_icon" style="position: absolute;
    right: 4%;top:58px;"></span>
                <br>
                <span class="text-danger"></span>
                @error('old_password')
                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
               @enderror
            </div>


            <div class="form-group clearfix">
                <label for="new_password">New Password</label><span class="required">*</span><br> 
				<input class="form-control @error('new_password') is-invalid @enderror" id="cpassword" placeholder="New Password" name="new_password" type="password" value="{{ old('new_password') }}" required=""><span id="toggle_pwd1" class="fa fa-fw fa-eye field_icon" style="position: absolute;
    right: 4%;top:144px;"></span>
        <span style="color: #0f5132;">Note: Password contain at-least 1 special character</span>
                <br>
                <span class="text-danger"></span>
                @error('new_password')
                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
               @enderror
               <br>
            </div>
            <div class="form-group clearfix">
                <label for="change_password">Confirm New Password</label><span class="required">*</span><br>
                <input class="form-control @error('change_password') is-invalid @enderror" id="cpassword" placeholder="Confirm New Password" name="change_password" type="password" value="{{ old('change_password') }}" required="">

               @error('change_password')
                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
               @enderror
               <br>
			</div>
            <div class="box-footer">
                <input class="btn btn-primary" type="submit" value="Submit">
            </div>
          </form>
	   </div>
	   </div>
	</div>
</div>
</div>
</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
 
  $(document).ready(function() {
    $('#example').DataTable();
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

  jQuery(function() {
   jQuery('.showSingle').click(function() {
        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        $.ajax({
            url:"{{url('company/inventory/leftbarinventorydata')}}",
            data: {
              targetid: targetid,
              serviceid: serviceid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewleftservicemodal').html(data.html);
            }
        })
    });

    $.ajax({
            url:"{{url('company/inventory/leftbarinventorydata')}}",
            data: {
              targetid: 0,
              serviceid: 0 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewleftservicemodal').html(data.html);
            }
        })

  });
$(document).on('click','#editProduct',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.editviewinventorymodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewmodaldata').html(data.html);
            }
        })
  });

$("#toggle_pwd").click(function () {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
  $("#password").attr("type", type);
});

$("#toggle_pwd1").click(function () {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
  $("#cpassword").attr("type", type);
});
</script>
@endsection