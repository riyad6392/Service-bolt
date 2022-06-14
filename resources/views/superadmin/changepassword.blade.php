@extends('layouts.superadminheader')
@section('content')
<div class="content-page">
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
	   		<form method="post" action="{{ route('superadmin.updatepassword') }}" enctype="multipart/form-data">
        		@csrf
				<div class="form-group clearfix">
                <label for="password">Current Password</label><span class="required">*</span><br> 
                <input class="form-control @error('old_password') is-invalid @enderror" id="password" placeholder="Password" name="old_password" type="password" value="{{ old('old_password') }}" required="">
                <br>
                <span class="text-danger"></span>
                @error('old_password')
                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
               @enderror
            </div>


            <div class="form-group clearfix">
                <label for="new_password">New Password</label><span class="required">*</span><br> 
				<input class="form-control @error('new_password') is-invalid @enderror" id="cpassword" placeholder="New Password" name="new_password" type="password" value="{{ old('new_password') }}" required="">
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
<script src="{{ asset('js/jquery.min.js')}}"></script> 
<script type="text/javascript">
 $(document).ready(function() {
    $('#example').DataTable();
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
</script>