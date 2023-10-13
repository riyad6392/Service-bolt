@extends('layouts.header')
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
ul {
    list-style: none;
}

.avatar-upload .avatar-preview > div {
    width: 100%;
    height: 100%;
    border-radius: 100%;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}
label.credit {
    display: block;
}
label.credit img {
    margin: 28px 0px;
    width: 160px;
}

.note-popover.popover {
    display: none;
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
<div class="">
<div class="content">
     <div class="row">
      	<div class="col-md-12">
          <div class="side-h3">
            <h3>Admin / Setup</h3>
          </div>
        </div>
        @if(Session::has('success'))

                    <div class="alert alert-success" id="selector">

                        {{Session::get('success')}}

                    </div>

                @endif
<form method="post" action="{{ route('company.updatesetting') }}" enctype="multipart/form-data">
  @csrf
<div class="col-lg-12 mb-4">
<div class="card admin-setting">
<div class="card-body">
<h5 class="mb-4">Manage Basic Info</h5>
<div class="row">
<div class="col-lg-9">

<div class="row">
<div class="col-lg-6 mb-3">
<label class="form-label">First Name</label>
  <input type="text" class="form-control form-control-2" placeholder="First Name" value="{{$userData->firstname}}" name="firstname" required="">
</div>
<div class="col-lg-6 mb-3">
<label class="form-label">Last Name</label>
  <input type="text" class="form-control form-control-2" placeholder="Last Name" value="{{$userData->lastname}}" name="lastname" required="">
</div>

<div class="col-lg-6 mb-3">
<label class="form-label">Company Name</label>
  <input type="text" class="form-control form-control-2" placeholder="Company Name" value="{{$userData->companyname}}" name="companyname" required="">
</div>

<div class="col-lg-6 mb-3">
<label class="form-label">Company Address</label>
  <input type="text" class="form-control form-control-2" placeholder="Company Address" value="{{$userData->company_address}}" name="address" id="address" required="">
</div>

<div class="col-lg-6 mb-3">
<label class="form-label">Phone Number</label>
  <input type="text" class="form-control form-control-2" placeholder="Phone Number" value="{{$userData->phone}}" name="phone" required="" onkeypress="return checkPhone(event)" maxlength="12">
</div>

<div class="col-lg-6 mb-3">
<label class="form-label">Email</label>
  <input type="email" class="form-control form-control-2" placeholder="Email Id" value="{{$userData->email}}" name="email" readonly="">
</div>

<div class="col-lg-6 mb-3">
<label class="form-label">Select Workday hour</label>
  <select class="form-control" name="per_day_hours">
    <option value="1" {{ ($userData->per_day_hours) == '1' ? 'selected' : '' }}>1 hour</option>
    <option value="2" {{ ($userData->per_day_hours) == '2' ? 'selected' : '' }}>2 hour</option>
    <option value="3" {{ ($userData->per_day_hours) == '3' ? 'selected' : '' }}>3 hour</option>
    <option value="4" {{ ($userData->per_day_hours) == '4' ? 'selected' : '' }}>4 hour</option>
    <option value="5" {{ ($userData->per_day_hours) == '5' ? 'selected' : '' }}>5 hour</option>
    <option value="6" {{ ($userData->per_day_hours) == '6' ? 'selected' : '' }}>6 hour</option>
    <option value="7" {{ ($userData->per_day_hours) == '7' ? 'selected' : '' }}>7 hour</option>
    <option value="8" {{ ($userData->per_day_hours) == '8' ? 'selected' : '' }}>8 hour</option>
    <option value="9" {{ ($userData->per_day_hours) == '9' ? 'selected' : '' }}>9 hour</option>
    <option value="10" {{ ($userData->per_day_hours) == '10' ? 'selected' : '' }}>10 hour</option>
     <option value="11" {{ ($userData->per_day_hours) == '11' ? 'selected' : '' }}>11 hour</option>
    <option value="12" {{ ($userData->per_day_hours) == '12' ? 'selected' : '' }}>12 hour</option>
  </select>
</div>

<!-- <div class="col-lg-6 mb-3">
<label class="form-label">password</label>
  <input type="password" class="form-control form-control-2" placeholder="password" value="" name="password">
</div>
 -->
<h5 class="my-4">Manage Invoice Info</h5>
<div class="row">
<div class="col-lg-6 mb-3">
  <label>Invoice Header Color</label><br>
  <span class="color-picker">
    <label for="colorPicker">
      @php
        if($userData->color!=null) {
          $usrheadercolor = $userData->color;
        } else {
          $usrheadercolor = "#faed61";
        }
      @endphp
      <input type="color" value="{{$usrheadercolor}}" id="colorPicker" name="color" style="width:235px;">
    </label>
  </span>
</div>
<div class="col-lg-6 mb-3">
  <label>Invoice Text Color</label><br>
  <span class="color-picker">
    <label for="colorPicker">
      @php
        if($userData->txtcolor!=null) {
          $usrtxtcolorcolor = $userData->txtcolor;
        } else {
          $usrtxtcolorcolor = "#000";
        }
      @endphp
      <input type="color" value="{{$usrtxtcolorcolor}}" id="txtcolorPicker" name="txtcolor" style="width:235px;">
    </label>
  </span>
</div>
<div class="col-lg-12 mb-3">
  <label>Invoice Footer content (255 Words)</label><br>
  <textarea class="form-control height-110 words-left" placeholder="Description" name="description" id="description" onkeyup="checkWordLen(this);">{{$userData->footercontent}}</textarea>
</div>
<div class="col-lg-12 mb-3">
  <label>Invoice Email Subject</label><br>
  <input type="text" value="{{$userData->subject}}" id="subject" name="subject" placeholder="Invoice Email Subject" class="form-control form-control-2">
</div>
<div class="col-lg-12 mb-3">
  <label>Invoice Email Body Description
</label><br>
  <textarea class="summernote form-control customer-height mb-4" placeholder="Invoice Email Body Description" name="bodytext" id="bodytext">{{$userData->bodytext}}</textarea>
</div>

</div>
<h5 class="my-4">Manage Stock Percentage (%)</h5>
<div class="col-lg-6 mb-3">
<label class="form-label">Good Stock (%)</label>
  <input type="text" class="form-control form-control-2" placeholder="Good Stock" value="{{$userData->goodproduct}}" name="goodproduct" onkeypress="return checkPhone(event)">
</div>
<div class="col-lg-6 mb-3">
<label class="form-label">Low Stock (%)</label>
  <input type="text" class="form-control form-control-2" placeholder="Low Stock" value="{{$userData->lowproduct}}" name="lowproduct" onkeypress="return checkPhone(event)">
</div>
<div class="col-lg-6 mb-3" style="display:none;">
<label class="form-label">Less Restock (%)</label>
  <input type="text" class="form-control form-control-2" placeholder="Less Restock" value="{{$userData->restockproduct}}" name="restockproduct" onkeypress="return checkPhone(event)">
</div>
@php
  $schecked = "";
  $allservicevalue = "";

   $pchecked = "";
   $allproductvalue = "";

   $bchecked = "";
   $bothvalue = "";
   $spchecked = "";

  if($userData->taxtype!="") {
    if($userData->taxtype == "service_products") {
      $spchecked = "checked";
      if($userData->servicevalue!=null) {
        $allservicevalue = $userData->servicevalue;
        $schecked = "checked";  
      } 
       if($userData->productvalue!=null) {
        $allproductvalue = $userData->productvalue;
        $pchecked = "checked";
      }
    }
    
    if($userData->taxtype == "both") {
      if($userData->servicevalue!=null) {
        $bothvalue = $userData->servicevalue;
        $bchecked = "checked";
      }
      if($userData->productvalue!=null) {
        $bothvalue = $userData->productvalue;
        $bchecked = "checked";
      }
    }
  }
@endphp
<h5 class="my-4">Manage Tax Percentage (%)</h5>
<div class="col-lg-6">
  <ul>
    <label class="radio-div2 me-2  mx-3">
      <input type="radio" name="taxtype" class="custom-radio serviceprodutradio" value="service_products" id="service_products" {{@$spchecked}}>
      <span class="checkmark">Services/Products</span>
    </label>
    <li class="dots" style="margin-left:18px;">
      <label class="radio-div2 me-2"> 
        <input type="checkbox" name="taxtype1" class="custom-radio secondradio" value="allservice" id="s1" {{@$schecked}}>
        <span class="checkmark">All Services</span>
      </label>
      <div class="mb-3">
          <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="allservicevalue" value="{{@$allservicevalue}}" id="s2">
      </div>
    </li>

    <li class="dots" style="margin-left:18px;">
      <label class="radio-div2 me-2">
        <input type="checkbox" name="taxtype1" class="custom-radio secondradio" value="allproduct" id="p1" {{@$pchecked}}>
        <span class="checkmark">All Products</span>
      </label>
          <div class="mb-3">
            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="allproductvalue" value="{{@$allproductvalue}}" id="p2">
          </div>
      </li>
   
  </ul>
</div>
<div class="col-lg-6">
  <ul>
     <li class="dots">
      <label class="radio-div2 me-2">
        <input type="radio" name="taxtype" class="custom-radio secondradio" id="both" value="both" {{@$bchecked}}>
        <span class="checkmark">Both (All Services/Products)</span>
      </label>
        <div class="mb-3 mt-4">
<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" name="bothvalue" value="{{@$bothvalue}}" id="both1">
</div>
    </li>
  </ul>
</div>
  
<h5 class="my-4">Manage Available Hours</h5>
<div class="col-lg-6 mb-3">
<label class="form-label">Opening Time</label>
<select class="form-control" name="openingtime">
    <option value="00" {{ ($userData->openingtime) == '00' ? 'selected' : '' }}>12.00 AM</option>
    <option value="01" {{ ($userData->openingtime) == '01' ? 'selected' : '' }}>01.00 AM</option>
    <option value="02" {{ ($userData->openingtime) == '02' ? 'selected' : '' }}>02.00 AM</option>
    <option value="03" {{ ($userData->openingtime) == '03' ? 'selected' : '' }}>03.00 AM</option>
    <option value="04" {{ ($userData->openingtime) == '04' ? 'selected' : '' }}>04.00 AM</option>
    <option value="05" {{ ($userData->openingtime) == '05' ? 'selected' : '' }}>05.00 AM</option>
    <option value="06" {{ ($userData->openingtime) == '06' ? 'selected' : '' }}>06.00 AM</option>
    <option value="07" {{ ($userData->openingtime) == '07' ? 'selected' : '' }}>07.00 AM</option>
    <option value="08" {{ ($userData->openingtime) == '08' ? 'selected' : '' }}>08.00 AM</option>
    <option value="09" {{ ($userData->openingtime) == '09' ? 'selected' : '' }}>09.00 AM</option>
    <option value="10" {{ ($userData->openingtime) == '10' ? 'selected' : '' }}>10.00 AM</option>
    <option value="11" {{ ($userData->openingtime) == '11' ? 'selected' : '' }}>11.00 AM</option>
    <option value="12" {{ ($userData->openingtime) == '12' ? 'selected' : '' }}>12.00 PM</option>
    <option value="13" {{ ($userData->openingtime) == '13' ? 'selected' : '' }}>01.00 PM</option>
    <option value="14" {{ ($userData->openingtime) == '14' ? 'selected' : '' }}>02.00 PM</option>
    <option value="15" {{ ($userData->openingtime) == '15' ? 'selected' : '' }}>03.00 PM</option>
    <option value="16" {{ ($userData->openingtime) == '16' ? 'selected' : '' }}>04.00 PM</option>
    <option value="17" {{ ($userData->openingtime) == '17' ? 'selected' : '' }}>05.00 PM</option>
    <option value="18" {{ ($userData->openingtime) == '18' ? 'selected' : '' }}>06.00 PM</option>
    <option value="19" {{ ($userData->openingtime) == '19' ? 'selected' : '' }}>07.00 PM</option>
    <option value="20" {{ ($userData->openingtime) == '20' ? 'selected' : '' }}>08.00 PM</option>
    <option value="21" {{ ($userData->openingtime) == '21' ? 'selected' : '' }}>09.00 PM</option>
    <option value="22" {{ ($userData->openingtime) == '22' ? 'selected' : '' }}>10.00 PM</option>
    <option value="23" {{ ($userData->openingtime) == '23' ? 'selected' : '' }}>11.00 PM</option>
 </select>
  
</div>
<div class="col-lg-6 mb-3">
<label class="form-label">Closing Time</label>
  <select class="form-control" name="closingtime">
    <option value="00" {{ ($userData->closingtime) == '00' ? 'selected' : '' }}>12.00 AM</option>
    <option value="01" {{ ($userData->closingtime) == '01' ? 'selected' : '' }}>01.00 AM</option>
    <option value="02" {{ ($userData->closingtime) == '02' ? 'selected' : '' }}>02.00 AM</option>
    <option value="03" {{ ($userData->closingtime) == '03' ? 'selected' : '' }}>03.00 AM</option>
    <option value="04" {{ ($userData->closingtime) == '04' ? 'selected' : '' }}>04.00 AM</option>
    <option value="05" {{ ($userData->closingtime) == '05' ? 'selected' : '' }}>05.00 AM</option>
    <option value="06" {{ ($userData->closingtime) == '06' ? 'selected' : '' }}>06.00 AM</option>
    <option value="07" {{ ($userData->closingtime) == '07' ? 'selected' : '' }}>07.00 AM</option>
    <option value="08" {{ ($userData->closingtime) == '08' ? 'selected' : '' }}>08.00 AM</option>
    <option value="09" {{ ($userData->closingtime) == '09' ? 'selected' : '' }}>09.00 AM</option>
    <option value="10" {{ ($userData->closingtime) == '10' ? 'selected' : '' }}>10.00 AM</option>
    <option value="11" {{ ($userData->closingtime) == '11' ? 'selected' : '' }}>11.00 AM</option>
    <option value="12" {{ ($userData->closingtime) == '12' ? 'selected' : '' }}>12.00 PM</option>
    <option value="13" {{ ($userData->closingtime) == '13' ? 'selected' : '' }}>01.00 PM</option>
    <option value="14" {{ ($userData->closingtime) == '14' ? 'selected' : '' }}>02.00 PM</option>
    <option value="15" {{ ($userData->closingtime) == '15' ? 'selected' : '' }}>03.00 PM</option>
    <option value="16" {{ ($userData->closingtime) == '16' ? 'selected' : '' }}>04.00 PM</option>
    <option value="17" {{ ($userData->closingtime) == '17' ? 'selected' : '' }}>05.00 PM</option>
    <option value="18" {{ ($userData->closingtime) == '18' ? 'selected' : '' }}>06.00 PM</option>
    <option value="19" {{ ($userData->closingtime) == '19' ? 'selected' : '' }}>07.00 PM</option>
    <option value="20" {{ ($userData->closingtime) == '20' ? 'selected' : '' }}>08.00 PM</option>
    <option value="21" {{ ($userData->closingtime) == '21' ? 'selected' : '' }}>09.00 PM</option>
    <option value="22" {{ ($userData->closingtime) == '22' ? 'selected' : '' }}>10.00 PM</option>
    <option value="23" {{ ($userData->closingtime) == '23' ? 'selected' : '' }}>11.00 PM</option>
 </select>
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
            @if($userData->image!=null)
              <div id="imagePreview" style="background-image: url('{{url('userimage/')}}/{{$userData->image}}');">
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

<h5 class="my-4">Manage Billing Info</h5>
<div class="row">
<div class="col-md-8">
<div class="row">
<div class="col-lg-12 mb-3">
  @php
    $ccNum          = $userData->cardnumber;
    $last4Digits    = preg_replace( "#(.*?)(\d{4})$#", "$2", $ccNum);
    $firstDigits    = preg_replace( "#(.*?)(\d{4})$#", "$1", $ccNum);
    $ccX            = preg_replace("#(\d)#", "*", $firstDigits);
    $ccX           .= $last4Digits;
  @endphp
<label class="form-label">Credit Card Number</label>
<input type="text" class="form-control form-control-2" placeholder="2598 xxxx xxxx 1073" value="{{$ccX}}" name="cardnumber" id="cardnumber" required="" onkeypress="return checkDigit(event)" readonly>


</div>

<div class="col-lg-6 mb-3">
<label class="form-label">Expiration Month (MM)</label>
<input type="text" class="form-control form-control-2" name="expmonth" value="{{$userData->expmonth}}" autocomplete="expmonth" autofocus  placeholder="Expiration Month (MM)" maxlength="2" style="padding:0px 0px 0px 10px;" onkeypress="return checkDigit1(event)" readonly>
</div>

<div class="col-lg-6 mb-3">
<label class="form-label">Expiration Year (YY)</label>
<input type="text" class="form-control form-control-2" name="expyear" value="{{$userData->expyear}}" autocomplete="expyear" autofocus  placeholder="Expiration Year (YY)" maxlength="2" style="padding:0px 0px 0px 10px;" onkeypress="return checkDigit1(event)" readonly>
</div>

<div class="col-lg-6 mb-3" style="display:none">
<label class="form-label">Expiration Date</label>
<input type="date" class="form-control form-control-2 date1" placeholder="Exp. Date" value="{{$userData->date}}" name="date" id="date" onkeydown="return false;" style="    position: relative;">
</div>
<div class="col-lg-6 mb-3">
<label class="form-label">Security Code</label>
<input type="text" class="form-control form-control-2" placeholder="xxx" value="{{$userData->securitycode}}" name="securitycode" required="" maxlength="3" onkeypress="return checkDigit1(event)" readonly>
</div>

</div>
</div>

<div class="col-lg-3 offset-lg-1">
<img src="images/credit-card.jpg" alt=""/>
</div>

</div>

<hr/>

<h5 class="my-4">Select payment types available</h5>

<div class="row">
  @php
    $paymenttype = array (
      '1'=>"Cash",
      '2'=>"Credit Card",
      '3'=>"Check",
      '4'=>"Invoice (Pay later)",
    );
    
  @endphp
<div class="col-lg-6 ps-lg-4">
<div class="services-list">

  @foreach($paymenttype as $key => $value)
    @php
      $ptypearray =  explode(',',$userData->paymenttype);
      if(in_array($value, $ptypearray)) {
        $checked = "checked";
       } else {
        $checked = "";
      }
    @endphp
    <label class="container-checkbox mb-4">{{$value}}
      <input type="checkbox" name="paymenttype[]" id="paymenttype" value="{{$value}}" {{$checked}}>
      <span class="checkmark"></span>
    </label>
  @endforeach
    </div>
</div>
<div class="col-lg-12 mt-4 text-center">
<!-- <button class="btn btn-add w-25 w-none">Save Changes</button> -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.summernote').summernote({
      height: 300,
       toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'clear'] ],
            //[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            //[ 'table', [ 'table' ] ],
            [ 'insert', [ 'link'] ],
            //[ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
        ]
    });
  });
  function cc_format(value) {
  var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
  var matches = v.match(/\d{4,16}/g);
  var match = matches && matches[0] || ''
  var parts = []
  for (i=0, len=match.length; i<len; i+=4) {
    parts.push(match.substring(i, i+4))
  }
  if (parts.length) {
    return parts.join(' ')
  } else {
    return value
  }
}

onload = function() {
  document.getElementById('cardnumber').oninput = function() {
    this.value = cc_format(this.value)
  }
}
function checkDigit(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
}

function checkDigit1(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
}

function checkPhone(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
}
 $('html').on('click','.date1',function() {
  var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
    $('#date').attr('min', maxDate);
  
 });
$('#selector').delay(2000).fadeOut('slow');

$(document).ready(function() {       
  $('#imageUpload').bind('change', function() {
      var a=(this.files[0].size);
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

$('#both').click(function() {
  $("#s1").attr("disabled", true);
  $("#s2").attr("disabled", true);
  $("#p1").attr("disabled", true);
  $("#p2").attr("disabled", true);
  $("#both1").attr("disabled", false);
});
$('#service_products').click(function() {
  $("#both1").attr("disabled", true);
  $("#s1").attr("disabled", false);
  $("#s2").attr("disabled", false);
  $("#p1").attr("disabled", false);
  $("#p2").attr("disabled", false);
});

var wordLen = 255,
    len; // Maximum word length
$('#description').keydown(function(event) {  
  len = $('#description').val().split(/[\s]+/);
  if (len.length > wordLen) { 
    if ( event.keyCode == 46 || event.keyCode == 8 ) {// Allow backspace and delete buttons
    } else if (event.keyCode < 48 || event.keyCode > 57 ) {//all other buttons
      event.preventDefault();
    }
  }
  console.log(len.length + " words are typed out of an available " + wordLen);
  wordsLeft = (wordLen) - len.length;
  $('.words-left').html(wordsLeft+ ' words left');
  
});
</script>
@endsection