@extends('layouts.workerheader')
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-12 mt-4">
		<a href="{{ url('personnel/myticket/view/') }}/{{$quoteData->id}}" class="back-btn">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
	   Back</a>
	   <p>
       @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
          @if(Session::has('error'))
						<div class="alert alert-danger" id="selector">
							{{Session::get('error')}}
						</div>
				  @endif
     </p>
   @php
	  	$totalprice = $quoteData->price + $quoteData->tax;
	  @endphp
	<div class="tabs">
	@if($paymentpaid==1)
  @else
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
  @endif
  @if($paymentpaid==1)
  	<p>TicketId: #{{$quoteData->id}} </p>
  	<p>Customer Name: {{$quoteData->customername}}</p>
  	<p>Payment Mode: {{$quoteData->payment_mode}}</p>
  	@if($quoteData->checknumber!="")
  		<p>Check Number: {{$quoteData->checknumber}}</p>
  	@endif
    <div id="tab1" class="tab-content">
    	<div class="card card-pay mb-3">
	  <div class="card-body">
	  <h4>Paid succesfully.</h4>
	  <h4>Total: <span>${{number_format((float)$quoteData->price, 2, '.', '')}}</span></h4>
	  
	  
	  
	
	  </div>
	  @else
  <div id="tabs-content" class="p-3">
  	<p>TicketId: #{{$quoteData->id}} </p>
  	<p>Customer Name: {{$quoteData->customername}}</p>
    <div id="tab1" class="tab-content">
    	<div class="card card-pay mb-3">
	  <div class="card-body">
	  <h4>You have to pay</h4>
	  <h4>Total: <span>${{number_format((float)$totalprice, 2, '.', '')}}</span></h4>
	  (Price: <span>${{number_format((float)$quoteData->price, 2, '.', '')}}</span>, Tax: <span>${{number_format((float)$quoteData->tax, 2, '.', '')}}</span>)
	  
	  
	
	  </div>
	  
	  
	  
	  </div>
	   <form method="post" action="{{route('worker.sendpayment')}}">
	   	@csrf
	   	<input type="hidden" class="form-control form-control-2" name="method" id="method"  value="Credit Card" placeholder="">
	   	<input type="hidden" class="form-control form-control-2" name="amount" required id="amount"  value="{{$totalprice}}" placeholder="">
	   	<input type="hidden" value="{{$quoteData->id}}" name="tid">
	   	<input type="hidden" value="{{$quoteData->customerid}}" name="customerid">
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
  <input type="text" class="border form-control form-control-2" name="expiration_date" placeholder="MMYY" required>
</div>
</div>

<div class="col-md-6">
  <div class="mb-3">
<label class="form-label">Security Code</label>
  <input type="text" class="border form-control form-control-2" name="cvv" placeholder="Security Code" required>
</div>
</div>

</div>
	  
	  </div>
	   	<div class="col-lg-12 text-center mt-3">
				<button type="submit" class="btn btn-add w-100 fw-bold">Pay</button>
			</div>
	  </div>
	
	  
	    
	  </form>  
	  
	  
    </div>
    <div id="tab2" class="tab-content">
      <div class="card card-pay mb-3">
	  <div class="card-body">
	  <h4>You have to pay</h4>
	  <h4>Total: <span>${{number_format((float)$totalprice, 2, '.', '')}}</span></h4>
	  (Price: <span>${{number_format((float)$quoteData->price, 2, '.', '')}}</span>, Tax: <span>${{number_format((float)$quoteData->tax, 2, '.', '')}}</span>)
	  
	  
	
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
						<form method="post" action="{{route('worker.sendpayment')}}">
							@csrf
							<input type="hidden" class="form-control form-control-2" name="method" id="method"  value="Cash">
	   					<input type="hidden" value="{{$quoteData->customerid}}" name="customerid">

								<div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
									<div class="mb-3">
									<label class="form-label">Enter Cash Ammount</label>
									<input type="text" class="form-control form-control-2" value="{{number_format((float)$totalprice, 2, '.', '')}}" placeholder="Cash Amount" name="payment_amount" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
									</div>
									<input type="hidden" name="payment_mode" value="Cash">
									<input type="hidden" value="{{$quoteData->id}}" name="tid">
									<div class="mt-4 text-center">
											<button class="btn btn-add w-100 fw-bold" type="submit">Pay</button>
									</div>
									
									</div>
								</div>
							</form>
							</div>

							<form method="post" action="{{route('worker.sendpayment')}}">
								@csrf
								<input type="hidden" class="form-control form-control-2" name="method" id="method"  value="Check" placeholder="" >
	   						<input type="hidden" value="{{$quoteData->customerid}}" name="customerid">

							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-headingTwo">
						  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
							Checks
						  </button>
						</h2>
								<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
									<div class="mb-3">
										<label class="form-label">Check Number</label>
										<input type="text" class="form-control form-control-2" placeholder="Check Number" name="checknumber" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" required>
									</div>
									<input type="hidden" value="By Check" name="payment_mode">

									<input type="hidden" value="{{$quoteData->id}}" name="tid">
									<div class="mb-3">
										<label class="form-label">Amount</label>
										<input type="text" class="form-control form-control-2" placeholder="Amount" name="payment_amount" value="{{number_format((float)$totalprice, 2, '.', '')}}" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
									</div>
								<div class="mt-4 text-center">
									<button class="btn btn-add w-100 fw-bold" type="submit">Pay</button>
								</div>
									</div>
								</div>
							</div>
						</form>
							
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
	  <h5 class="mb-4">Total: <span>${{number_format((float)$totalprice, 2, '.', '')}}</span></h5>
	  (Price: <span>${{number_format((float)$quoteData->price, 2, '.', '')}}</span>, Tax: <span>${{number_format((float)$quoteData->tax, 2, '.', '')}}</span>)
	  </div>
	  </div>
	  <div class="text-center">
<button class="btn btn-add w-100 fw-bold">Pay</button>
</div>
	  
	  
	  
    </div>
  
  </div> <!-- END tabs-content -->

@endif

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