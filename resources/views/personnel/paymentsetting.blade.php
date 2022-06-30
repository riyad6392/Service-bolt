@extends('layouts.header')
 @section('content')
  <style type=""> .labesls {
    display: flex;
    align-items: center;
}
.labesls {
    display: flex;
    align-items: center;
    padding: 1px 0 26px;
    border-bottom: 1px solid #B0B7C3;
}
.pointerevent{
  pointer-events: none;
}
.dates_picker {
    padding: 16px;
    width: 320px;
    border-radius: 14px;
}
.container-checkbox .checkmark:after {
    left: 7px!important;
    top: 4px!important;
    width: 5px!important;
    height: 10px!important;
    border-radius: initial!important;
    background-color: transparent!important;
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
}
.container-checkbox .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background: #F3F3F3;
    border-radius:100%;
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

.selection-div li .radio-div,.selection-div li .radio-div1,.selection-div li .radio-div2,.selection-div li .radio-div3,.selection-div li .radio-div4,.selection-div li .radio-div11 {
    font-size: 18px!important;
    font-weight: 500;
    width: 29%;
}
.selection-div li .container-checkbox{
   font-size: 18px!important;
    font-weight: 500;
    width: 57%;
}

.selection-div1 li .radio-div,.selection-div1 li .radio-div1,.selection-div1 li .radio-div2,.selection-div1 li .radio-div3,.selection-div1 li .radio-div4,.selection-div1 li .radio-div11 {
    font-size: 18px!important;
    font-weight: 500;
   width: 29%;
}
.selection-div1 li .container-checkbox{
   font-size: 18px!important;
    font-weight: 500;
    width: 57%;
}

.selection-div3 li .radio-div,.selection-div3 li .radio-div1,.selection-div3 li .radio-div2,.selection-div3 li .radio-div3,.selection-div3 li .radio-div4,.selection-div3 li .radio-div11 {
    font-size: 18px!important;
    font-weight: 500;
    width: 35%;
}
.selection-div3 li .container-checkbox{
   font-size: 18px!important;
    font-weight: 500;
    width: 57%;
}

.selection-div4 li .radio-div,.selection-div4 li .radio-div1,.selection-div4 li .radio-div2,.selection-div4 li .radio-div3,.selection-div4 li .radio-div4,.selection-div4 li .radio-div11 {
    font-size: 18px!important;
    font-weight: 500;
    width: 35%;
}
.selection-div4 li .container-checkbox{
   font-size: 18px!important;
    font-weight: 500;
    width: 57%;
}

.payment-page input[type='text'] {
  width: 100px;
}
 .radio-div,.radio-div1,.radio-div2,.radio-div3,.radio-div4,.radio-div11{
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 20px;
    font-weight: 600;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #B0B7C3;
}
.payment-page input.custom-radio {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.payment-page:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.payment-page input:checked ~ .checkmark {
  background-color: #FEE200;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.payment-page input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.payment-page .checkmark:after {
    top: 6px;
    left: 6px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
}
.selection-div li{
    padding: 10px 0;
}

.selection-div1 li{
    padding: 10px 0;
}
.selection-div3 li{
    padding: 10px 0;
}
.selection-div4 li{
    padding: 10px 0;
}
.radio-div.active,.radio-div1.active,.radio-div2.active,.radio-div3.active,.radio-div4.active,.radio-div11.active{
    color: #232322;
}
.container-checkbox.active{
    color: #232322;
}
.third-section ul {
    padding: 0;
}
.payment-page .input-group{
  width: auto;
}
        </style>
<div class="">
  <div class="content payment-page">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
          <h3>Payment Settings</h3>
          <!-- <p style="color: #B0B7C3;">Lorem ipsum dolor sit amet</p> -->
        </div>
      </div>
      <div class="personal-setting">
        <div class="settings-payement">
          <h3>Personnel Payment Settings</h3>
        </div>
        <hr>
        <div class="first-section">
          <label class="radio-div11 active">Hourly Payments 
            <input type="radio" checked="checked" name="method" class="custom-radio firstradio">
            <span class="checkmark"></span>
          </label>
          <ul class="selection-div">
            <li class="d-flex">
              <label class="radio-div me-2">Amount Per Hour : 
                <input type="radio" checked="checked" name="hourly-payment" class="custom-radio firstradio">
                <span class="checkmark"></span>
              </label>
               <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
</div>
            </li>
          </ul>
        </div>
        <hr>
        <div class="second-section">
          <label class="radio-div1">Fixed Salary <input type="radio" name="method" class="custom-radio">
            <span class="checkmark"></span>
          </label>
          <ul class="selection-div1">
            <li class="d-flex">
              <label class="radio-div2 me-2">Monthly Salary Amount : <input type="radio" checked="checked" name="fixed-salary" class="custom-radio secondradio">
                <span class="checkmark"></span>
              </label>
             <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
</div>
            </li>
            <li class="d-flex">
              <label class="radio-div2 me-2">Bi Monthly Salary Amount : <input type="radio" name="fixed-salary" class="custom-radio secondradio">
                <span class="checkmark"></span>
              </label>
                 <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
</div>
            </li>
            <li class="d-flex">
              <label class="radio-div2 me-2">Weekly Salary Amount : <input type="radio" name="fixed-salary" class="custom-radio secondradio">
                <span class="checkmark"></span>
              </label>
                <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
</div>
            </li>
            <li class="d-flex">
              <label class="radio-div2 me-2">Bi Weekly Salary Amount : <input type="radio" name="fixed-salary" class="custom-radio secondradio">
                <span class="checkmark"></span>
              </label>
               <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
</div>
            </li>
          </ul>
        </div>
        <hr>
        <div class="third-section">
         <label class="radio-div3">Commission Basis <input type="radio" name="method" class="custom-radio commisionchekbox">
            <span class="checkmark"></span>
          </label>
          <div class="row">
            <div class="col-md-6">
                 <div style="padding-left:35px">
              <label class="radio-div3 ">Amount Wise <input type="radio" name="commission" class="custom-radio amountradio">
                <span class="checkmark"></span>
              </label>
            
                
                  <ul class="selection-div3">
                    <li class="d-flex">
                        <label class="container-checkbox me-4">All Services/Products  <input type="checkbox" name="amount-wise1" class="amount-wise1" id="ckbCheckAll">
                          <span class="checkmark"></span>
                        </label>   
                        <div class="input-group mb-3">
                          <span class="input-group-text">$</span>
                          <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
                        </div>
                    </li>
                     <li class="d-flex">
                     <label class="container-checkbox me-4">Service 1 :<input type="checkbox" name="amount-wise" class="amountall">
                    <span class="checkmark"></span>
                  </label>
                        <div class="input-group mb-3">
          <span class="input-group-text">$</span>
          <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
        </div>
                    </li>
                     <li class="d-flex">
                     <label class="container-checkbox me-4">Products 1 :<input type="checkbox" name="amount-wise" class="amountall">
                    <span class="checkmark"></span>
                  </label>
                       <div class="input-group mb-3">
          <span class="input-group-text">$</span>
          <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
        </div>
                    </li>
                  </ul>
                
            </div>
        </div>
            <div class="col-md-6">
               
              <label class="radio-div4">Percent Wise <input type="radio" name="commission" class="custom-radio percentradio">
                <span class="checkmark"></span>
              </label>
                <ul class="selection-div4">
                    <li class="d-flex">
          <label class="container-checkbox me-4">All Services/Products <input type="checkbox" name="percent-wise1" class="percent-wise1" id="ckbCheckAllpercent">
            <span class="checkmark"></span>
          </label>
                  <div class="input-group mb-3">

  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false"><span class="input-group-text">%</span>
</div>
            </li>
             <li class="d-flex">
             <label class="container-checkbox me-4">Service 1 : <input type="checkbox" name="percent-wise" class="allpercent">
            <span class="checkmark"></span>
          </label>
               <div class="input-group mb-3">
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false"><span class="input-group-text">%</span>
</div>
            </li>
             <li class="d-flex">
              

              <label class="container-checkbox me-4">Product 1 :<input type="checkbox" name="percent-wise" class="allpercent">
            <span class="checkmark"></span>
          </label>
                <div class="input-group mb-3">
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false"><span class="input-group-text">%</span>
</div>
            </li>
                  </ul>
          </div>
            
            
          </div>





        
         
        </div>







<!--         <div class="labesls">
          <label class="container-checkbox me-5">Hourly <input type="checkbox">
            <span class="checkmark"></span>
          </label>
          <div class="amounts">
            <div class="d-block">
              <h5>Amount</h5>
              <input type="" name="" class="form-control" placeholder="$12">
            </div>
          </div>
        </div>
        <div class="second-labels">
          <label class="container-checkbox me-5">Salary <input type="checkbox">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox me-5">Monthly <input type="checkbox">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox me-5">Weekly <input type="checkbox">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox me-5">Bi Monthly <input type="checkbox">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox me-5">Bi Weekly <input type="checkbox">
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
          <label class="container-checkbox me-5">Commission <input type="checkbox">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox me-5">Flat Rate Per Service/Product <input type="checkbox">
            <span class="checkmark"></span>
          </label>
          <label class="container-checkbox me-5">Percentage <input type="checkbox">
            <span class="checkmark"></span>
          </label>
        </div>
        <div class="forth_labels">
          <div class="side-section">
            <h6>All</h6>
            <div class="amounts">
              <div class="d-block">
                <h5>Amount</h5>
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
                <h5>Amount</h5>
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
                <h5>Amount</h5>
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
        </div> -->
      </div>
      <div class="personal-setting">
        <div class="settings-payement">
          <h3>Other Employment Information</h3>
          <p style="font-size: 18px;
    width: 100%;margin: 0;
    padding-top: 10px;
    padding-bottom: 12px;">1. Hire Date:</p>
          <input type="date" name="" class="form-control dates_picker">
        </div>
         <div class="col-md-12 text-center" ><button type="submit" class="btn btn-add px-5 text-center">Save</button></div>
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
                <label class="container-checkbox">Per hour <input type="checkbox" checked="checked">
                  <span class="checkmark"></span>
                </label>
                <label class="container-checkbox">Flate rate <input type="checkbox">
                  <span class="checkmark"></span>
                </label>
                <label class="container-checkbox">Reoccuring <input type="checkbox">
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
                  <small>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="17 8 12 3 7 8"></polyline>
                      <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                  </small> Drop file here or click to upload </span>
                <input type="file" name="myFile" class="drop-zone__input">
              </div>
            </div>
            <div class="col-md-12 mb-2">
              <p class="create-gray mb-2">Create default checklist </p>
              <div class="align-items-center  d-flex services-list">
                <label class="container-checkbox me-3">Point 1 <input type="checkbox" checked="checked">
                  <span class="checkmark"></span>
                </label>
                <label class="container-checkbox me-3">Point 1 <input type="checkbox">
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
    @endsection @section('script')
    <script type="text/javascript">
      $(document).ready(function() {
        $('.selection-div1').addClass('pointerevent');
         $('.selection-div4').addClass('pointerevent');
         $('.selection-div3').addClass('pointerevent');
         $(".secondradio").prop("checked",false);
      });

      $('.radio-div11').on('click', function() {
        $(".secondradio").prop("checked",false);
        $(".amountradio").prop("checked",false);
        $(".percentradio").prop("checked",false);

        $("#ckbCheckAll").prop("checked",false);
        $(".amountall").prop("checked",false);

        $("#ckbCheckAllpercent").prop("checked",false);
        $(".allpercent").prop("checked",false);

        $('.selection-div3').addClass('pointerevent');

        $(".secondradio").removeAttr("checked");

        $('.selection-div').removeClass('pointerevent');
        
        $('.selection-div1').addClass('pointerevent');

        $('.radio-div1').removeClass('active');
        $('.radio-div11').addClass('active');
         $(".firstradio").attr('checked', 'checked');

         $(".amountradio").removeAttr("checked");
         


      });

      $('.radio-div1').on('click', function() {
        $(".amountradio").prop("checked",false);
        $(".percentradio").prop("checked",false);

        $("#ckbCheckAll").prop("checked",false);
        $(".amountall").prop("checked",false);

        $("#ckbCheckAllpercent").prop("checked",false);
        $(".allpercent").prop("checked",false);

         $(".firstradio").removeAttr("checked");
          $('.selection-div1').removeClass('pointerevent');
          $('.selection-div').addClass('pointerevent');
          $('.radio-div1').addClass('active');
          $('.radio-div11').removeClass('active');

          $(".amountradio").removeAttr("checked");
      });

      $('.amount-wise').on('click', function() {
        if ($(this).is(':checked')) {
          $(".amountall").attr('checked', 'checked');
        } else {
         $(".amountall").removeAttr("checked");
        }
      });

      $('.percent-wise').on('click', function() {
        alert('aaa');
        if ($(this).is(':checked')) {
          $(".allpercent").attr('checked', 'checked');
        } else {
         $(".allpercent").removeAttr("checked");
        }
      });

      $('.radio-div4').on('click', function() {
        $('.radio-div1').prop("checked",false);
        $('.radio-div1').removeClass('active');
        $("#ckbCheckAll").prop("checked",false);
        $(".amountall").prop("checked",false);
        $(".firstradio").prop("checked",false);
         $(".secondradio").prop("checked",false);


        $(".amountall").removeAttr("checked");
        $(".amount-wise").removeAttr("checked");
        $('.selection-div4').removeClass('pointerevent');
        $('.selection-div3').addClass('pointerevent');

        $('.selection-div').addClass('pointerevent');
         $('.selection-div1').addClass('pointerevent');

         $(".commisionchekbox").attr('checked', 'checked');

         $(".firstradio").removeAttr("checked");

        $('.radio-div11').removeClass('active');

      });

      $('.radio-div3').on('click', function() {
        $('.radio-div1').prop("checked",false);
        $('.radio-div1').removeClass('active');
        $(".secondradio").prop("checked",false);
        $("#ckbCheckAllpercent").prop("checked",false);
        $(".allpercent").prop("checked",false);
        $(".firstradio").prop("checked",false);

        $(".allpercent").removeAttr("checked");
        $(".percent-wise").removeAttr("checked");
        
        $('.selection-div3').removeClass('pointerevent');
         $('.selection-div4').addClass('pointerevent');

         $('.selection-div').addClass('pointerevent');
         $('.selection-div1').addClass('pointerevent');
         $(".commisionchekbox").attr('checked', 'checked');

         $(".firstradio").removeAttr("checked");

          $('.radio-div11').removeClass('active');
      });

       $('.third-section').on('click', function() {
          $('.radio-div11').removeClass('active');
           $('.radio-div1').prop("checked",false);
       });

      
      $(document).ready(function () {
        $("#ckbCheckAll").click(function () {
            $(".amountall").prop('checked', $(this).prop('checked'));
        });
        
        $(".amountall").change(function(){
            if (!$(this).prop("checked")){
                $("#ckbCheckAll").prop("checked",false);
            }
        });

        $("#ckbCheckAllpercent").click(function () {
            $(".allpercent").prop('checked', $(this).prop('checked'));
        });
        
        $(".allpercent").change(function(){
            if (!$(this).prop("checked")){
                $("#ckbCheckAllpercent").prop("checked",false);
            }
        });

        
    });

    </script>
     @endsection