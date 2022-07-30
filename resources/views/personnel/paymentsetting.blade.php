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
        @php
            $uri_path = $_SERVER['REQUEST_URI']; 
            $uri_parts = explode('/', $uri_path);
            $request_url = end($uri_parts);
        @endphp
<div class="">
 <form method="post" action="{{route('company.paymentsettingcreate')}}">
    @csrf
    <input type="hidden" name="pid" value="{{$request_url}}">
  <div class="content payment-page">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
          <h3>Payment Settings</h3>
        </div>
      </div>
      <div class="personal-setting">
        <div class="settings-payement">
          <h3>Personnel Payment Settings</h3>
        </div>
        <hr>
        <div class="first-section">
          <label class="radio-div11 active container-checkbox me-4">Hourly Payments 
            <input type="checkbox" checked="checked" name="hourly" class="custom-radio firstradio">
            <span class="checkmark"></span>
          </label>
          <ul class="selection-div">
            <li class="d-flex">
              <label class="radio-div me-2">Amount Per Hour : 
                <input type="radio" checked="checked" name="hourlypayment" class="custom-radio firstradio">
                <span class="checkmark"></span>
              </label>
               <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="hourlypaymentamount">
</div>
            </li>
          </ul>
        </div>
        <hr>
        <div class="second-section">
          <label class="radio-div1 container-checkbox me-4">Fixed Salary 
            <input type="checkbox" name="fixedsalary" class="custom-radio secondradio">
            <span class="checkmark"></span>
          </label>
          <ul class="selection-div1">
            <li class="d-flex">
              <label class="radio-div2 me-2">Monthly Salary Amount : 
                <input type="radio" checked="checked" name="salary" class="custom-radio secondradio" value="monthlysalaryamount">
                <span class="checkmark"></span>
              </label>
              <div class="input-group mb-3">
                  <span class="input-group-text">$</span>
                  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="monthlysalaryamount">
              </div>
            </li>
            <li class="d-flex">
              <label class="radio-div2 me-2">Bi Monthly Salary Amount : 
                <input type="radio" name="salary" class="custom-radio secondradio" value="bimonthlysalaryamount">
                <span class="checkmark"></span>
              </label>
                 <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="bimonthlysalaryamount">
</div>
            </li>
            <li class="d-flex">
              <label class="radio-div2 me-2">Weekly Salary Amount : 
                <input type="radio" name="salary" class="custom-radio secondradio" value="weeklysalaryamount">
                <span class="checkmark"></span>
              </label>
                <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="weeklysalaryamount">
</div>
            </li>
            <li class="d-flex">
              <label class="radio-div2 me-2">Bi Weekly Salary Amount : 
                <input type="radio" name="salary" class="custom-radio secondradio" value="biweeklysalaryamount">
                <span class="checkmark"></span>
              </label>
               <div class="input-group mb-3">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="biweeklysalaryamount">
</div>
            </li>
          </ul>
        </div>
        <hr>
        <div class="third-section">
         <label class="radio-div3 container-checkbox me-4">Commission Basis <input type="checkbox" name="method" class="custom-radio commisionchekbox">
            <span class="checkmark"></span>
          </label>
         
          <div class="row">
            <div class="col-md-6">
                 <div style="padding-left:35px">
              <label class="radio-div3 container-checkbox me-4">Amount Wise 
                <input type="checkbox" name="commission" class="custom-radio amountradio" value="amount">
                <span class="checkmark"></span>
              </label>
            
                
                  <ul class="selection-div3">
                    <li class="d-flex">
                        <label class="container-checkbox me-4">All Services/Products  
                            <input type="checkbox" name="amountall" class="amount-wise1" id="ckbCheckAll">
                          <span class="checkmark"></span>
                        </label>   
                        <div class="input-group mb-3">
                          <span class="input-group-text">$</span>
                          <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="amountallamount">
                        </div>
                    </li>
                    @foreach($services as $key => $value)
                     <li class="d-flex">
                        <label class="container-checkbox me-4">{{$value->servicename}} :
                            <input type="checkbox" name="amountwise[]" class="amountall" value="{{$value->servicename}}">
                            <span class="checkmark"></span>
                        </label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="amountvalue[]">
                        </div>
                    </li>
                    @endforeach
                    @foreach($products as $key1 => $product)
                     <li class="d-flex">
                     <label class="container-checkbox me-4">{{$product->productname}} :
                        <input type="checkbox" name="amountwise[]" class="amountall" name="{{$product->productname}}" value="{{$product->productname}}">
                    <span class="checkmark"></span>
                  </label>
                       <div class="input-group mb-3">
          <span class="input-group-text">$</span>
          <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="amountvalue[]">
        </div>
                    </li>
                    @endforeach
                  </ul>
                
            </div>
        </div>
            <div class="col-md-6">
               
              <label class="radio-div4 container-checkbox me-4">Percent Wise 
                <input type="checkbox" name="commission" class="custom-radio percentradio" value="percent">
                <span class="checkmark"></span>
              </label>
                <ul class="selection-div4">
                    <li class="d-flex">
          <label class="container-checkbox me-4">All Services/Products 
            <input type="checkbox" name="percentall" class="percent-wise1" id="ckbCheckAllpercent">
            <span class="checkmark"></span>
          </label>
                  <div class="input-group mb-3">

  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="percentallamount"><span class="input-group-text">%</span>
</div>
            </li>

            @foreach($services as $key => $value)
             <li class="d-flex">
                <label class="container-checkbox me-4">{{$value->servicename}} : 
                    <input type="checkbox" name="percent-wise" class="allpercent" value="{{$value->servicename}}">
                    <span class="checkmark"></span>
                </label>
               <div class="input-group mb-3">
                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false"><span class="input-group-text">%</span>
                </div>
            </li>
            @endforeach
             @foreach($products as $key1 => $product)
             <li class="d-flex">
              <label class="container-checkbox me-4">{{$product->productname}} :
                <input type="checkbox" name="percent-wise" class="allpercent" value="{{$product->productname}}">
                <span class="checkmark"></span>
             </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false"><span class="input-group-text">%</span>
                </div>
            </li>
            @endforeach
        </ul>
          </div>
            
            
          </div>





        
         
        </div>
    </div>
      <div class="personal-setting">
        <div class="settings-payement">
          <h3>Other Employment Information</h3>
          <p style="font-size: 18px;
    width: 100%;margin: 0;
    padding-top: 10px;
    padding-bottom: 12px;">1. Hire Date:</p>
          <input type="date" name="hiredate" class="form-control dates_picker">
        </div>
         <div class="col-md-12 text-center" >
            <button type="submit" class="btn btn-add px-5 text-center" style="pointer-events: none;">Save</button></div>
      </div>

    </div>
  </div>
 </form>   
    @endsection @section('script')
    <script type="text/javascript">
    $('.radio-div3').on('click', function() {
        $('.selection-div3').removeClass('pointerevent');
    });

    $('.radio-div4').on('click', function() {
        $('.selection-div4').removeClass('pointerevent');
    });

     $(document).ready(function () {
        $(".commisionchekbox").attr('checked', 'checked');
        $("#ckbCheckAll").click(function () {
            $(".amountall").prop('checked', $(this).prop('checked'));
             //$(".amountall").closest('li').find('input[type=text]').prop('disabled', !$(this).prop('checked'));
           
        });
        
        $(".amountall").change(function(){
            if (!$(this).prop("checked")){
                $("#ckbCheckAll").prop("checked",false);
            }
        });

        $("#ckbCheckAllpercent").click(function () {
            $(".allpercent").prop('checked', $(this).prop('checked'));
            //$(".allpercent").closest('li').find('input[type=text]').prop('disabled', !$(this).prop('checked'));
        });
        
        $(".allpercent").change(function() {
            if (!$(this).prop("checked")){
                $("#ckbCheckAllpercent").prop("checked",false);
            }
        });

        $(document).on('change','.amountall',function(){

            //$(this).closest('li').find('input[type=text]').prop('disabled', !$(this).is(':checked'));
        });
    });
</script>
     @endsection