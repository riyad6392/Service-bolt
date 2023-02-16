@extends('layouts.header')
@section('content')
<div class="content">

<div class="row">
	<div class="col-md-12 mt-4">
	
	   @if(Session::has('success'))
<div class="alert alert-success" id="selector">
	{{Session::get('success')}}
</div>
@endif
	<div class="tabs">
	
  <ul id="tabs-nav" class="tabs-paynow-card">
    <li><a href="#tab1">
	<div class="tabs-paynow">
	<i class="fa fa-credit-card-alt"></i>
	<p>Credit Card</p>
	</div>
	</a>
	</li>
    <li><a href="#tab2">
	<div class="tabs-paynow">
	<i class="fa fa-envelope-open-o"></i>
	<p>Cash & Checkes</p>
	</div>
	</a></li>
    <li><a href="#tab3">
		<div class="tabs-paynow">
	<i class="fa fa-paypal"></i>
	<p>PayPal</p>
	</div>

	</a></li>
   
  </ul> <!-- END tabs-nav -->
 
  
  </div>
	  
  <div id="tabs-content" class="p-3">
  	
    <div id="tab1" class="tab-content">
    	<div class="card card-pay mb-3">
	 
	  
	  
	  </div>
      <div class="card admin-setting mb-3">
	  <div class="card-body">
	  <h5 class="mb-4">Credit Card Info</h5>
	  <div class="mb-3">
<label class="form-label">Credit Card Number</label>
  <input type="text" class="border form-control form-control-2" placeholder="Credit Card Number" name="card_number" id="card_number" onkeypress="return checkDigit(event)" required>
</div>

<div class="row">
<div class="col-md-6">
  <div class="mb-3">
<label class="form-label">Expiration Date</label>
  <input type="text" class="border form-control form-control-2" name="expiration_date" placeholder="MMYY" onkeypress="return checkDigit(event)" maxlength="4" required>
</div>
</div>

<div class="col-md-6">
  <div class="mb-3">
<label class="form-label">Security Code</label>
  <input type="text" class="border form-control form-control-2" name="cvv" placeholder="Security Code" onkeypress="return checkDigit(event)" maxlength="5" required>
</div>
</div>

</div>
	  
	  </div>
	  </div>
	  
	  
	     <div class="col-lg-12 text-center mt-3">
<button type="submit" class="btn btn-add w-100 fw-bold">Pay</button>
</div>
	  
	  
	  
    </div>
    <div id="tab2" class="tab-content">
	
      <div class="card card-pay mb-3">
	  <div class="card-body">
	  <p>@if($servicename!="") Service Name - {{$servicename}}@endif</p>
	  <p>@if($productname!="") Product Name - {{$productname}} @endif</p>
	  <h4>You have to pay</h4>
	  <h4>Total: <span>${{number_format((float)$amount, 2, '.', '')}}</span></h4>
	  
	  
	
	  </div>
	  
	  
	  
	  </div>
	  
	  <div class="according-cash admin-setting">
	  <div class="accordion accordion-flush" id="accordionFlushExample">
							<div class="accordion-item shadow">
								<h2 class="accordion-header" id="flush-headingOne">
						  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
							Cash
						  </button>
						</h2>
						<form method="put" action="{{ url('company/billing/directicketsave') }}" enctype="multipart/form-data">
					        <input type="hidden" name="customername" id="customername" value="{{$customer->customername}}">
                  <input type="hidden" name="customerid" id="customerid" value="{{$customer->id}}">
                  <input type="hidden" name="sid" id="sid" value="{{$serviceid}}">
                  <input type="hidden" name="pid" id="pid" value="{{$pid}}">
                  <input type="hidden" name="ticketprice" id="ticketprice" value="{{$ticketprice}}">
                  
							@csrf 
								<div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
									<div class="mb-3">
									<label class="form-label">Enter Cash Amount</label>
									<input type="hidden" class="form-control form-control-2" name="method" id="method"  value="Cash" placeholder="">
									<input type="text" class="form-control form-control-2" name="amount" required id="amount"  value="{{$amount}}" placeholder="">
									
									</div>
									<div class="mt-4 text-center">
						<button class="btn btn-add w-100 fw-bold">Pay</button>
									</div>
									</form>
									
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-headingTwo">
						  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
							Checks
						  </button>
						</h2>
						<form method="put" action="{{ url('company/billing/directicketsave') }}" enctype="multipart/form-data">
						
                  <input type="hidden" name="customername" id="customername" value="{{$customer->customername}}">
                  <input type="hidden" name="customerid" id="customerid" value="{{$customer->id}}">
                  <input type="hidden" name="sid" id="sid" value="{{$serviceid}}">
                  <input type="hidden" name="pid" id="pid" value="{{$pid}}">
							@csrf 
								<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
									<div class="mb-3">
									<label class="form-label">Check Number</label>
									<input type="hidden" class="form-control form-control-2" name="method" id="method"  value="Check" placeholder="" >
									<input type="number" class="form-control form-control-2" name="check_no" id="check_no"  placeholder="" required>

									</div>
									
										<div class="mb-3">
									<label class="form-label">Amount</label>
									<input type="text" class="form-control form-control-2" name="amount" id="amount" required  value="{{$amount}}" placeholder="" required>

									</div>
								<div class="mt-4 text-center">
								<button type="submit" class="btn btn-add w-100 fw-bold">Pay</button>
								</div>
</form>
									</div>
								</div>
							</div>
							
	  </div>
	  
	  
	  
    </div>
	</div>
    <div id="tab3" class="tab-content">
       <div class="card mb-3">
	  <div class="card-body">
	  <h5 class="mb-4">PayPal Account</h5>
	
									
									<input type="text" class="form-control form-control-2" placeholder="email">
									
									 

								
																		
	  </div>
	  </div>
	  
	  <div class="card card-pay mb-3">
	  <div class="card-body">
	  <h5 class="mb-4">Account Due</h5>
	  <h5 class="mb-4">Total: <span>${{number_format((float)$amount, 2, '.', '')}}</span></h5>
	  </div>
	  </div>
	  <div class="text-center">
<button type="submit" class="btn btn-add w-100 fw-bold">Pay</button>
</div>
	  
	  
	  
    </div>
  
  </div> <!-- END tabs-content -->

</div>

	</div>
	</div>
   </div>



          </div>
     
<!-- Modal -->
@endsection
@section('script')
<script>
// Show the first tab and hide the rest
$('#tabs-nav li:first-child').addClass('active');
$('.tab-content').hide();
$('.tab-content:first').show();

// Click function
$('#tabs-nav li').click(function(){
  $('#tabs-nav li').removeClass('active');
  $(this).addClass('active');
  $('.tab-content').hide();
  
  var activeTab = $(this).find('a').attr('href');
  $(activeTab).fadeIn();
  return false;
});

function checkDigit(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
}

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
  document.getElementById('card_number').oninput = function() {
    this.value = cc_format(this.value)
  }
}

</script>
@endsection