@extends('layouts.superadminheader')
@section('content')

<style type="">
  label.form-label {
    color: #212529!important;
}
.manage-home h3 {
    color: #232322;
    font-size: 20px;
}
.add-tag {
    background: #fff;
    width: 100%;
    padding: 15px;
    border-radius: 10px;
    height: 100%;
  }
</style>

   <div class="content-page">
      <div class="content p-3">
        <div class="row">
        <h5>Manage Settings</h5>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
  <div class="add-tag">      
  <form class="form-material m-t-40  form-valide" method="post" action="{{route('superadmin.manageSettingupdate')}}">
    @csrf
    <div class="row">
      <div class="manage-home">
        <h3>Email Setting</h3>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Mail HOST</label>
          <input type="text" class="form-control" placeholder="SMTP HOST" value="{{$userData->host}}" name="host" id="host" required="">
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Mail Username</label>
          <input type="text" class="form-control" placeholder="SMTP User Name" value="{{$userData->smtpusername}}" name="smtpusername" id="smtpusername" required="">
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Mail Password</label>
  
          <input type="password" class="form-control" placeholder="SMTP Password" value="{{$userData->smtppassword}}" name="smtppassword" id="smtppassword" required="">
        </div>
      </div>
</div>
   
  <div class="row">
    <div class="manage-home">
      <h3>Other Settings</h3>
    </div>
    <div class="col-md-12">
     <div class="mb-3">
      <label class="form-label"> Firebase Key </label>
        <input type="text" name="firebase" id="firebase" class="form-control" placeholder="Firebase Key" value="{{$userData->firebase}}">
      </div>
    </div>
    <div class="col-md-12">
     <div class="mb-3">
      <label class="form-label"> Google Place Key </label>
        <input type="text" name="googleplace" id="googleplace" class="form-control" placeholder="Google Place Key" value="{{$userData->googleplace}}">
      </div>
    </div>
    <div class="col-md-6">
     <div class="mb-3">
      <label class="form-label"> Currency </label>
        <input type="text" name="currency" id="currency" class="form-control" placeholder="Currency" value="{{$userData->currency}}" required="">
      </div>
    </div>
    <div class="col-md-6">
      <label class="form-label">Time zone</label>
      <input type="text" name="timezone" id="timezone" class="form-control" placeholder="Time Zone" value="{{$userData->timezone}}" required="">
    </div>
    <div class="col-md-6">
      <label class="form-label">Feature Price</label>
      <input type="text" name="featureprice" id="featureprice" class="form-control number-only" placeholder="Feature Price" value="{{$userData->featureprice}}" required="">
    </div>
    <div style="text-align: -webkit-center;">
      <div class="col-lg-6">
        <button class="btn btn-add btn-block" type="submit" style="width: 200px;height: 40px;margin-top: 10px;">Save changes</button>
      </div>
    </div>
  </div>
</form>

</div>
</div>
</div>
  </div>
</div>
</div>
@endsection
<script src="{{ asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
  onload =function(){ 
  var ele = document.querySelectorAll('.number-only')[0];
  ele.onkeypress = function(e) {
     if(isNaN(this.value+""+String.fromCharCode(e.charCode)))
        return false;
  }
  ele.onpaste = function(e){
     e.preventDefault();
  }
}
</script>
     