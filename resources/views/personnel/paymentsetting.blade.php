@extends('layouts.header')
@section('content')

 <style type="">
          .labesls {
    display: flex;
    align-items: center;
}
.labesls {
    display: flex;
    align-items: center;
    padding: 1px 0 26px;
    border-bottom: 1px solid #B0B7C3;
}
.dates_picker {
    padding: 16px;
    width: 320px;
    border-radius: 14px;
}
.container-checkbox .checkmark:after {
    left: 13px;
    top: 9px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
.settings-payement h3 {
    font-size: 22px;
}
.side-section .amounts {
    margin: 0 24px;
}
.side-section h6 {
    font-size: 18px;
    position: relative;
    bottom: 12px;
}
.side-section {
    display: flex;
    align-items: end;
    padding: 20px;
    margin: 0 0 0 178px;
}
.amounts h5 {
    color: #B0B7C3;
    font-size: 18px;
    margin: 0;
    padding: 14px 0;
}
.amounts input {
    width: 150px;
    padding: 18px;
    border-radius: 16px;
}
.container-checkbox {
    display: block;
    position: relative;
    padding-left: 46px;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #B0B7C3;
    font-size: 18px;
    position: relative;
    top: 24px;
}
.container-checkbox .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 30px;
    width: 30px;
    background: #F3F3F3;
    border-radius: 8px;
}
.second-labels {
    display: flex;
    align-items: center;
}
.side-section_1 {
    display: flex;
    align-items: end;
    padding-bottom: 14px;
}
.side-section_1 .amounts {
    margin: 0 26px;
}

.side-section_2 {
    display: flex;
    align-items: end;
    padding-bottom: 14px;
}
.personal-setting {
    background: #fff;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0px 0px 5px #ccc;
    margin-bottom: 30px;
}
.side-section_2 .amounts {
    margin: 0 26px;
}

.side-section_2 h6 {
    margin: 0 0px 0 92px;
    position: relative;
    bottom: 20px;
    font-size: 18px;
}

.side-section_1 h6 {
    margin: 0 2px;
    position: relative;
    bottom: 20px;
    font-size: 18px;
}
.second-labels {
    display: flex;
    align-items: center;
    padding: 12px 0 28px 0px;
    border-bottom: 1px solid #B0B7C3;
}
.third_labels {
    display: flex;
    border-bottom: 1px solid #ccc;
    height: 94px;
    padding-top: 9px;
}

        </style>

   <div class="">
      
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Lorem Ipsum</h3>
       <p style="color: #B0B7C3;">Lorem ipsum dolor sit amet</p>
     </div>
     </div>

     <div class="personal-setting">
      <div class="settings-payement">
        <h3>Personnal Payment Settings</h3>
      </div>
      <div class="labesls">
        
          <label class="container-checkbox me-5">Hourly
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<div class="amounts">
  <div class="d-block">
  <h5>Amont</h5>
  <input type="" name="" class="form-control" placeholder="$12">
</div>
</div>
      </div>

<div class="second-labels">
  <label class="container-checkbox me-5">Salary
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox me-5">Monthly
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox me-5">Weekly
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox me-5">Bi Monthly
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox me-5">Bi Weekly
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<div class="amounts">
  <div class="d-block">
  <h5>Amount</h5>
  <input type="" name="" class="form-control" placeholder="$12">
</div>
</div>


     </div>

     <div class="third_labels">
      <label class="container-checkbox me-5">Commission
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox me-5">Flat Rate Per Service/Product
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox me-5">Percentage
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
     </div>

<div class="forth_labels">
  <div class="side-section">
    <h6>All</h6>
  <div class="amounts">
  <div class="d-block">
  <h5>Amont</h5>
  <input type="" name="" class="form-control" placeholder="$12">
</div>
</div>
<div class="amounts">
  <div class="d-block">
  <h5>Percentage</h5>
  <input type="" name="" class="form-control" placeholder="20%">
</div>
</div>
  </div>

<div class="side-section_1">
    <h6>Service or Prdouct name</h6>
  <div class="amounts">
  <div class="d-block">
  <h5>Amont</h5>
  <input type="" name="" class="form-control" placeholder="$12">
</div>
</div>
<div class="amounts">
  <div class="d-block">
  <h5>Percentage</h5>
  <input type="" name="" class="form-control" placeholder="20%">
</div>
</div>
  </div>

  <div class="side-section_2">
    <h6>Service Name</h6>
  <div class="amounts">
  <div class="d-block">
  <h5>Amont</h5>
  <input type="" name="" class="form-control" placeholder="$12">
</div>
</div>
<div class="amounts">
  <div class="d-block">
  <h5>Percentage</h5>
  <input type="" name="" class="form-control" placeholder="20%">
</div>
</div>
  </div>

</div>

     </div>

<div class="personal-setting">
  <div class="settings-payement">
    <h3>Other Employment  Information</h3>
    <p style="    color: #B0B7C3;
    font-size: 18px;
    width: 100%;margin: 0;
    padding-top: 10px;
    padding-bottom: 12px;">1. Hire Date:</p>
    <input type="date" name="" class="form-control dates_picker" >
  </div>
</div>


   </div>



          </div>
     
<!-- Modal -->





<div class="modal fade" id="add-services" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal">
     <h5>Lorem Ipsum</h5>
     <p class="lead">Lorem ipsum dolar si amen</p>
     </div>
     
   
     
     
     <div class="row customer-form" id="product-box-tabs">
     <div class="col-md-12 mb-2">
     
     <input type="text" class="form-control" placeholder="Service Name" />
  
     </div>
     
     <div class="col-md-12 mb-2">
   <input type="text" class="form-control" placeholder="Service Default Price" />
     </div>
     
     
     
   
     <div class="col-md-12 mb-2">
    
     <select class="form-select">
        <option selected="">Default Product</option>
        <option>Default Product 1</option>
        <option>Default Product 2</option>
        </select>
     
     </div>
  
     
     <div class="col-md-12 mb-2">
    
    <div class="align-items-center justify-content-lg-between d-flex services-list">
    <label class="container-checkbox">Per hour
  <input type="checkbox" checked="checked">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox">Flate rate
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox">Reoccuring
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
    </div>
     
     </div>
     
     
      <div class="col-md-6 mb-2">
    
    <select class="form-select">
    <option selected="">Service Frequency</option>
    <option>Service 1</option>
    <option>Service 2</option>
    </select>
     
     </div>
     
     <div class="col-md-6 mb-2">
    
     <select class="form-select">
    <option selected="">Default Time</option>
    <option>Default Time</option>
    <option>Default Time</option>
    </select>
     
     </div>
     
    <div class="col-lg-12 mb-2">
      <div class="drop-zone h-160">
    <span class="drop-zone__prompt text-center">
  <small><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></small>
  Drop file here or click to upload</span>
    <input type="file" name="myFile" class="drop-zone__input">
  </div>
     </div>
     
     <div class="col-md-12 mb-2">
    <p class="create-gray mb-2">Create default checklist </p>
    <div class="align-items-center  d-flex services-list">
    <label class="container-checkbox me-3">Point 1
  <input type="checkbox" checked="checked">
  <span class="checkmark"></span>
</label>
<label class="container-checkbox me-3">Point 1
  <input type="checkbox">
  <span class="checkmark"></span>
</label>
<span>+</span>
    </div>
     
     </div>
    
     
     
     <div class="col-lg-6 mb-2">
     <button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
     </div>
     <div class="col-lg-6">
     <button class="btn btn-add btn-block">Add a Service</button>
     </div>
     
     </div>
     
    
     
    </div>
  </div>
</div>

@endsection
@section('script')

@endsection