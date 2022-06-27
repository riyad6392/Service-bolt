@extends('layouts.workerheader')
@section('content')
<style type="text/css">
  .form-control.form-control-2 input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: .75rem;
    position: relative;
    width: 100%;
    display: block;
}

.form-control.form-control-2[type="date"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
    display: block;
}
</style>
<div class="">
<div class="content">
     <div class="row">
      	<div class="col-md-12">
          <div class="side-h3">
            <h3>Settings</h3>
          </div>
        </div>
        @if(Session::has('success'))
          <div class="alert alert-success" id="selector">
            {{Session::get('success')}}
          </div>
        @endif
<form method="post" action="{{ route('worker.updatesetting') }}" enctype="multipart/form-data">
  @csrf
<div class="col-lg-12 mb-4">
<div class="card admin-setting">
<div class="card-body">
<h5 class="mb-4">Basic Info</h5>
<div class="row">
<div class="col-lg-9">

<div class="row">
  <div class="col-lg-6 mb-3">
    <label class="form-label">Name</label>
    <input type="text" class="form-control form-control-2" placeholder="Name" value="{{$workerData->personnelname}}" name="personnelname" required="">
  </div>
  <div class="col-lg-6 mb-3">
    <label class="form-label">Address</label>
    <input type="text" class="form-control form-control-2" placeholder="Address" value="{{$workerData->address}}" name="address" required="">
  </div>

  <div class="col-lg-12 mb-3">
    <label class="form-label">Phone</label>
    <input type="text" class="form-control form-control-2" placeholder="Phone" value="{{$workerData->phone}}" name="phone" required="" onkeypress="return checkPhone(event)" maxlength="12">
  </div>
  <div class="col-lg-6 mb-3">
    <label class="form-label">Email</label>
    <input type="email" class="form-control form-control-2" placeholder="Email Id" value="{{$workerData->email}}" name="email" readonly="">
  </div>
  <div class="col-lg-6 mb-3">
    <label class="form-label">Hired Date</label>
    <input type="date" class="form-control form-control-2" placeholder="Hired Date" value="" name="hiredate" readonly="">
  </div>
 
  <label class="form-label">Total Earning</label>
  <div class="col-lg-6 mb-3">
    <select name="earning" id="earning" name="earning" class="form-control">
      <option value="">Select Duration</option>
      <option value="1week" selected>One Week</option>
      <option value="1month">One Month</option>
      <option value="6month">Six Month</option>
      <option value="1year">One Year</option>
    </select>
  </div>
  
  <div class="col-lg-6 mb-3">
    <!-- <label class="form-label">Amount</label> -->
    <input type="text" class="form-control form-control-2" placeholder="Amount" value="$1000" name="amount" readonly="">
  </div>

</div>


</div>
<div class="col-lg-3">
<div class="avatar-upload">
        <div class="avatar-edit">
            <input type='file' id="imageUpload" name="imageUpload" accept=".png, .jpg, .jpeg">

            <label for="imageUpload"></label>
        </div>

        <div class="avatar-preview">
            @if($workerData->image!=null)
              <div id="imagePreview" style="background-image: url('{{url('uploads/personnel/thumbnail/')}}/{{$workerData->image}}');">
              </div>
            @else
              @php
               $dimage = url('/').'/uploads/servicebolt-noimage.png';
              @endphp
              <div id="imagePreview" style="background-image: url('{{$dimage}}');">
              </div>
            @endif
        </div>
        <div style="color: #999999;margin-bottom: 6px;position: relative;left: 10px;width: 100px;">Approximate Image Size : 122 * 122</div>

    </div>
</div>
</div>
<hr/>
<div class="row">
  <div class="col-lg-12 mt-4 text-center">
    <input class="btn btn-add w-25 w-none" type="submit" value="Save Changes">
  </div>
</div>

</div>
</div>
</div>
</form>
</div>
</div>
</div>
@endsection
@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc&callback=initAutocomplete&libraries=places"
      async
    ></script>
<script>
  function initAutocomplete() {
    var input = document.getElementById('address');
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function() {
         var place = autocomplete.getPlace();
          autocomplete.setComponentRestrictions(
      {'country': ['us']});
    });
  }
</script>
<script type="text/javascript">
  function checkPhone(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
  }
  $('#selector').delay(2000).fadeOut('slow');

  $(document).ready(function() {       
    $('#imageUpload').bind('change', function() {
        var a=(this.files[0].size);
        //alert(a);
        if(a > 2000000) {
           swal({
            title: "Image Large?",
            text: "Ïmage should not be larger than 2 mb!",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ok",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function (isConfirm) {
            if (isConfirm) {
               location.reload();
        }}
      );
         }
});
    });
</script>
@endsection