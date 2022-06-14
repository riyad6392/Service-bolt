@extends('layouts.workerheader')
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-12 mt-4">
		<a href="{{ url()->previous() }}" class="back-btn">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
	   Back</a>
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
  <div id="tabs-content" class="p-3">
  	<p>TicketId: #{{$quoteData->id}} </p>
  	<p>Customer Name: {{$quoteData->customername}}</p>
    <div id="tab1" class="tab-content">
    	<div class="card card-pay mb-3">
	  <div class="card-body">
	  <h4>You have to pay</h4>
	  <h4>Total: <span>${{number_format((float)$quoteData->price, 2, '.', '')}}</span></h4>
	  
	  
	
	  </div>
	  
	  
	  
	  </div>
      <div class="card admin-setting mb-3">
	  <div class="card-body">
	  <h5 class="mb-4">Credit Card Info</h5>
	  <div class="mb-3">
<label class="form-label">Credit Card Number</label>
  <input type="text" class="border form-control form-control-2" placeholder="Credit Card Number">
</div>

<div class="row">
<div class="col-md-6">
  <div class="mb-3">
<label class="form-label">Expiration Date</label>
  <input type="text" class="border form-control form-control-2" placeholder="Expiration Date">
</div>
</div>

<div class="col-md-6">
  <div class="mb-3">
<label class="form-label">Security Code</label>
  <input type="text" class="border form-control form-control-2" placeholder="Security Code">
</div>
</div>

</div>
	  
	  </div>
	  </div>
	  
	  
	     <div class="col-lg-12 text-center mt-3">
<button class="btn btn-add w-100 fw-bold">Pay</button>
</div>
	  
	  
	  
    </div>
    <div id="tab2" class="tab-content">
      <div class="card card-pay mb-3">
	  <div class="card-body">
	  <h4>You have to pay</h4>
	  <h4>Total: <span>${{number_format((float)$quoteData->price, 2, '.', '')}}</span></h4>
	  
	  
	
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
								<div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
									<div class="mb-3">
									<label class="form-label">Enter Cash Ammount</label>
									<input type="text" class="form-control form-control-2" placeholder="">

									</div>
																		<div class="mt-4 text-center">
<button class="btn btn-add w-100 fw-bold">Pay</button>
</div>
									
									</div>
								</div>
							</div>
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
									<input type="text" class="form-control form-control-2" placeholder="">

									</div>
									
										<div class="mb-3">
									<label class="form-label">Ammount</label>
									<input type="text" class="form-control form-control-2" placeholder="">

									</div>
																											<div class="mt-4 text-center">
								<button class="btn btn-add w-100 fw-bold">Pay</button>
								</div>
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
	  <h5 class="mb-4">Total: <span>${{number_format((float)$quoteData->price, 2, '.', '')}}</span></h5>
	  </div>
	  </div>
	  <div class="text-center">
<button class="btn btn-add w-100 fw-bold">Pay</button>
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
</script>
@endsection