@extends('layouts.header')
@section('content')
<style type="text/css">
	
  .input-container input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: .75rem;
    position: relative;
    width: 100%;
}
.col-lg-3.col-md-12.text-center.mb-2 {
    position: absolute;
    right: 1%;
}
input[type="date"]::-webkit-calendar-picker-indicator {
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
}
.modal-ul-box {
    padding: 0;
    margin: 10px;
    overflow-y: scroll;
}
.modal-ul-box li {
    list-style: none;
    margin: 14px;
   
    padding:10 0px;
   
}
.btn.btn-sve {
    background: #FEE200;
    padding: 8px 34px;
    box-shadow: 0px 0px 10px #ccc;
}

a .hidequote{
position: relative;
}

i.fa.fa-plus.first {
    position: absolute !important;
    top: 80px !important;
    height: 30px !important;
}

i.fa.fa-plus.second {
    position: absolute;
    top: 253px;
    right: 35px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}

i.fa.fa-plus.third {
    position: absolute;
    top: 305px;
    right: 35px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}

i.fa.fa-plus.next-two {
    position: absolute;
    top: 305px;
    right: 13px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}

i.fa.fa-plus.select-customer {
    position: absolute;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
    right: 33px;
    top: 169px;
}

i.fa.fa-plus.next-one {
    position: absolute;
    top: 250px;
    right: 13px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}

	i.fa.fa-plus.first-one {
    position: absolute;
    top: 80px;
    right: 12px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}

i.fa.fa-plus.category-one {
    position: absolute;
    top: 170px;
    right: 13px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}

select#servicename {
    height: 150px;
}

</style>

<div class="ticket-page">
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Ticket and Quotes</h3>
     </div>
     </div>
	 @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
<div class="col-lg-12">
<div class="card mb-3 h-auto">
<div class="card-body">
<div class="row align-items-center mb-3">
	<div class="col-lg-2 mb-2">
		<h5>Quotes</h5>
	</div>
	<div class="col-lg-2 mb-2" style="visibility: : hidden;">
	   <div class="show-fillter" style="display: none;">
	    <select id="inputState" class="form-select">
			<option>Show: A to Z</option>
			<option>Show: Z to A</option>
		</select>
	   </div>
	</div>
	
	<div class="col-lg-5 mb-2" style="visibility:: hidden;">
	   <div class="show-fillter" style="display: none;">
	   	   <input type="text" class="form-control" placeholder="Search">
		   <button class="search-icon">
		   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
		   </button>
	   </div>
	</div>
	   
	   <div class="col-lg-3 text-center mb-2">
	   <a href="#" data-bs-toggle="modal" data-bs-target="#add-tickets" class="add-btn-yellow btn-block">
	   Add Quote +
	   </a>
	   </div>
	   
	   </div>
	  
		@php
	      $pagedata = App\Models\Managefield::select('*')
	      ->where('page','companyquote')->where('userid',$auth_id)->get();
	      $cpagedta = count($pagedata);
	    @endphp 
	  <div class="table-responsive">
	  <table id="example" class="table no-wrap table-new table-list align-items-center">
	  <thead>
	  <tr>
		  <th>Quote number</th>
		@if($cpagedta==0)
		  <th>Customer Name</th>
		  <th>Frequency</th>
		  <th>Price</th>
		  <th>Service Name</th>
		@else
		@foreach($pagedata as $key => $pagecolumn)
          @if($pagecolumn->columname=="customername")
            <th>Customer Name</th>
          @endif
          @if($pagecolumn->columname=="frequency")
            <th>Frequency</th>
          @endif
          @if($pagecolumn->columname=="price")
            <th>Price</th>
          @endif
          @if($pagecolumn->columname=="servicename")
            <th>Service Name</th>
          @endif
        @endforeach
		@endif
		  <th>Create ticket</th>
		  <th>Action</th>
		<div class="heading-dot pull-right" data-bs-toggle="modal" data-bs-target="#exampleModal">    
        	<i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
       </div>  
	  </tr>
	  </thead>
	  <tbody>
	  	@php
          $i = 1;
        @endphp
     	@foreach($quoteData as $quote)
     	@php
		  $explode_id = explode(',', $quote->serviceid);
		  $servicedata = App\Models\Service::select('servicename')
		    ->whereIn('services.id',$explode_id)->get();
		@endphp


	  <tr>
	  <td>#{{$quote->id}}</td>
	  @if($cpagedta==0)
	  <td>{{$quote->customername}}</td>
	  <td>{{$quote->frequency}}</td>
	  <td>{{$quote->price}}</td>
	  <td>
	  	@php
		  $i=0;
		@endphp
		@foreach($servicedata as $servicename)
		    @php
		      if(count($servicedata) == 0){
		        $servicename = '-';
		      } else {
		        $servicename = $servicename->servicename;
		      }
		    @endphp

		    {{$servicename}}
		  @if(count($servicedata)>1) 
		    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $quote->serviceid }}">
		      <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
		    </svg>
		    @endif
		    @php
		    $i=1; break;
		  @endphp
		@endforeach
	</td>
	  @else
	  @foreach($pagedata as $key => $pagecolumn)
        @if($pagecolumn->columname=="customername")
          <td>{{$quote->customername}}</td>
        @endif
        @if($pagecolumn->columname=="frequency")
          <td>{{$quote->frequency}}</td>
        @endif
        @if($pagecolumn->columname=="price")
          <td>{{$quote->price}}</td>
        @endif
        @if($pagecolumn->columname=="servicename")
          <td>@php
		  $i=0;
		@endphp
		@foreach($servicedata as $servicename)
		    @php
		      if(count($servicedata) == 0){
		        $servicename = '-';
		      } else {
		        $servicename = $servicename->servicename;
		      }
		    @endphp

		    {{$servicename}}
		  @if(count($servicedata)>1) 
		    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $quote->serviceid }}">
		      <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
		    </svg>
		    @endif
		    @php
		    $i=1; break;
		  @endphp
		@endforeach</td>
        @endif
      @endforeach
	  @endif
	  <td><a class="btn btn-dark btn-block btn-lg add-ticket-alert" data-id="{{$quote->id}}" style="padding: 7px 0;
    font-size: 16px;
    border-radius: 15px;
    width: 114px!important;
">Ticket +</a></td>
	  <td><a class=" btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="{{$quote->id}}">Edit</a>
	  <a href="javascript:void(0);" class="info_link1 btn btn-edit p-3 w-auto" dataval="{{$quote->id}}">Delete</a>
	  <a class=" btn btn-edit p-3 w-auto emailinvoice" data-id="{{$quote->id}}" data-email="{{$quote->email}}" data-bs-toggle="modal" data-bs-target="#edit-address">Share</a>
	  </td>
	  </tr>
	  	@php
          $i++;
        @endphp
        @endforeach
	  </tbody>
	  </table>
	  
	 </div>
	   
	   
</div>
</div>

<div class="card mb-3 h-auto">
<div class="card-body">
<div class="row align-items-center mb-3">
<div class="col-lg-2 mb-2">
<h5>Tickets</h5></div>
	   <div class="col-lg-2 mb-2" style="display: none;">
	   <div class="show-fillter">
	    <select id="inputState" class="form-select">
											<option>Show: A to Z</option>
											<option>Show: Z to A</option>
										</select>
	   </div>
	   </div>
	   
	   <div class="col-lg-5 mb-2 offset-lg-3" style="display: none;">
	   <div class="show-fillter">
	   <input type="text" class="form-control" placeholder="Search Quotes">
	   <button class="search-icon">
	   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
	   </button>
	   </div>
	   
	   </div>
	   <!-- Button trigger modal -->


<!-- Modal -->

	  <div class="col-lg-3 col-md-12 text-center mb-2">
	  	<div>
	   <a href="#" data-bs-toggle="modal" data-bs-target="#add-tickets1" class="add-btn-yellow btn-block">
	   Add Ticket +
	   </a></div>
	   </div>
	   
	   </div>
		@php
	      $pagedata1 = App\Models\Managefield::select('*')
	      ->where('page','companyticket')->where('userid',$auth_id)->get();
	      $cpagedta1 = count($pagedata1);
	    @endphp    
	  <div class="table-responsive">
	  <table id="example1" class="table no-wrap table-new table-list align-items-center">
	  <thead>
	  <tr>
	  <th>Ticket number</th>
	  	@if($cpagedta1==0)
		  <th>Customer Name</th>
		  <th>Frequency</th>
		  <th>Price</th>
		  <th>Service Name</th>
		@else
		   @foreach($pagedata1 as $key => $pagecolumn)
	          @if($pagecolumn->columname=="customername")
	            <th>Customer Name</th>
	          @endif
	          @if($pagecolumn->columname=="frequency")
	            <th>Frequency</th>
	          @endif
	          @if($pagecolumn->columname=="price")
	            <th>Price</th>
	          @endif
	          @if($pagecolumn->columname=="servicename")
	            <th>Service Name</th>
	          @endif
          @endforeach
		@endif
		  <th>Schedule</th>
	  	  <th>Action</th>
  		<div class="heading-dot pull-right" data-bs-toggle="modal" data-bs-target="#exampleModal1">    
        	<i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
       </div>
	  </tr>
	  </thead>
	  <tbody>
	  	@php
		  $i = 1;
		@endphp
			@foreach($ticketData as $ticket)
			@php
			  $explode_id = explode(',', $ticket->serviceid);
			  $servicedata = App\Models\Service::select('servicename')
			    ->whereIn('services.id',$explode_id)->get();
			@endphp
	  <tr>
	  <td>#{{$ticket->id}}</td>
	  @if($cpagedta1==0)
		  <td>{{$ticket->customername}}</td>
		  <td>{{$ticket->frequency}}</td>
		  <td>{{$ticket->price}}</td>
		  <td>@php
		  $i=0;
		@endphp
		@foreach($servicedata as $servicename)
		    @php
		      if(count($servicedata) == 0){
		        $servicename = '-';
		      } else {
		        $servicename = $servicename->servicename;
		      }
		    @endphp

		    {{$servicename}}
		  @if(count($servicedata)>1) 
		    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $ticket->serviceid }}">
		      <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
		    </svg>
		    @endif
		    @php
		    $i=1; break;
		  @endphp
		@endforeach</td>
	  @else
	  	@foreach($pagedata1 as $key => $pagecolumn)
	        @if($pagecolumn->columname=="customername")
	          <td>{{$ticket->customername}}</td>
	        @endif
	        @if($pagecolumn->columname=="frequency")
	          <td>{{$ticket->frequency}}</td>
	        @endif
	        @if($pagecolumn->columname=="price")
	          <td>{{$ticket->price}}</td>
	        @endif
	        @if($pagecolumn->columname=="servicename")
	          <td>@php
		  $i=0;
		@endphp
		@foreach($servicedata as $servicename)
		    @php
		      if(count($servicedata) == 0){
		        $servicename = '-';
		      } else {
		        $servicename = $servicename->servicename;
		      }
		    @endphp

		    {{$servicename}}
		  @if(count($servicedata)>1) 
		    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $ticket->serviceid }}">
		      <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
		    </svg>
		    @endif
		    @php
		    $i=1; break;
		  @endphp
		@endforeach</td>
	        @endif
      	@endforeach
	  @endif
	  <td><a href="{{route('company.scheduler')}}" class="btn btn-dark btn-block btn-lg" style="padding: 7px 0;
    font-size: 16px;
    border-radius: 15px;
    width: 114px!important;
">
@if($ticket->ticket_status == 2)  Assigned @elseif($ticket->ticket_status == 1) Schedule + @elseif($ticket->ticket_status == 4) Picked @endif </a></td>
	  <td><a class="btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="{{$ticket->id}}" data-type="ticket">Edit</a>
	  	<a href="javascript:void(0);" class="info_link1 btn btn-edit p-3 w-auto" dataval="{{$ticket->id}}">Delete</a>
	  	<a class=" btn btn-edit p-3 w-auto emailinvoice" data-id="{{$ticket->id}}" data-email="{{$ticket->email}}" data-bs-toggle="modal" data-bs-target="#edit-address">Share</a>
	  </td>
	  
	  </tr>
	  	@php
          $i++;
        @endphp
        @endforeach
	  </tbody>
	  </table>
	  
	 </div>
	 

	   
</div>
</div>

<!-- Completed ticket section start -->
<div class="card mb-3 h-auto">
<div class="card-body">
	<div class="row align-items-center mb-3">
		<div>
			<h5>Completed Tickets</h5>
		</div>
	</div>
	<div class="table-responsive">
	  <table id="example2" class="table no-wrap table-new table-list align-items-center">
	  <thead>
	  <tr>
	  <th>Ticket #</th>
	  <th>Customer Name</th>
	  <th>Price</th>
	  <th>Service Name</th>
	  <th>Paid Status</th>
	  <th>Action</th>
	  
	  </tr>
	  </thead>
	  <tbody>
	  	@php
		  $i = 1;
		@endphp
			@foreach($completedticketData as $ticket)
			@php
			  $explode_id = explode(',', $ticket->serviceid);
			  $servicedata = App\Models\Service::select('servicename')
			    ->whereIn('services.id',$explode_id)->get();
			  if($ticket->payment_status!=null || $ticket->payment_mode!=null) {
			  	$payment_status = "Completed";
			  } else {
			  	$payment_status = "Pending";
			  }
			  if($ticket->payment_mode!=null) {
			  	$paid_status = $ticket->payment_mode;
			  } else {
			  	$paid_status = "--";
			  }
			@endphp
	  <tr>
	  <td>#{{$ticket->id}}</td>
	  <td>{{$ticket->customername}}</td>
	  <td>{{$ticket->price}}</td>
	  <td>@php
		  $i=0;
		@endphp
		@foreach($servicedata as $servicename)
		    @php
		      if(count($servicedata) == 0){
		        $servicename = '-';
		      } else {
		        $servicename = $servicename->servicename;
		      }
		    @endphp

		    {{$servicename}}
		  @if(count($servicedata)>1) 
		    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $ticket->serviceid }}">
		      <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
		    </svg>
		    @endif
		    @php
		    $i=1; break;
		  @endphp
		@endforeach</td>
		<td>{{$payment_status}} ({{$paid_status}})</td>
	  <td><a class="btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$ticket->id}}" style="display: none;">View</a>
	  	<a href="{{url('company/quote/ticketdetail/')}}/{{$ticket->id}}" class="btn btn-edit p-3 w-auto">View</a>
	  	<a class="btn btn-edit p-3 w-auto repoenticket" data-id="{{$ticket->id}}">Reopen</a>
	 </td>
	 </tr>
	  	@php
          $i++;
        @endphp
        @endforeach
	  </tbody>
	  </table>
	 </div>
</div>
</div>
<!-- Completed ticket end -->

</div>

    
     


     </div>
   </div>



          </div>
     
<!-- Modal -->





<div class="modal fade" id="add-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal d-flex justify-content-between align-items-center">
		   <h5>Add A New Quote</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
	 @if(count($services)>0)
      @else
      	@if(count($productData)==0)
     		<div style="color: red;">Step2: Please add a service in the services section.</div>
     		
     	@else
     		<div style="color: red;">Step1: Please add a service in the services section.</div>
     		
     	@endif
     @endif

	  @if(count($customer)>0)
      @else
      	@if(count($productData)==0 && count($services)==0)
     		<div style="color: red;">Step3: Please add a customer in the customer section.</div>
     		
     	@elseif(count($productData)>0 && count($services)>0)
     		<div style="color: red;">Step1: Please add a customer in the customer section.</div>
     	@elseif(count($productData)>0)
     		<div style="color: red;">Step2: Please add a customer in the customer section.</div>	
     		
     	@elseif(count($services)>0)
     		<div style="color: red;">Step1: Please add a customer in the customer section.</div>
     		<div style="color: red;">Step2: Then please create a new tickets/quote here.</div>
     		
     	@endif
     @endif
    
	   <form class="form-material m-t-40 row form-valide" method="post" action="{{route('company.quotecreate')}}" enctype="multipart/form-data">
        @csrf
        @php
	 		if(count($customer)>0) {
	 			$custmername = "";
	 		}
	 		else {
	 			$custmername = "active-focus";
	 		}
	 	@endphp
	 	<input type="hidden" name="ticketprice" id="ticketprice" value="">
	   <div class="row customer-form">
	   <div class="col-md-11 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<select class="form-select {{$custmername}}" name="customerid" id="customerid" required>
			      	  <option selected="" value="">Select a customer </option>
				      @foreach($customer as $key => $value)
				      	<option value="{{$value->id}}">{{$value->customername}}</option>
				      @endforeach
			  		</select>

			  		<div class="d-flex align-items-center justify-content-end pe-3 mt-3">
			  			<a class="addanew" href="#"  data-bs-toggle="modal" data-bs-target="#add-customer" class="" id="hidequote"><i class="fa fa-plus first"></i></a>
			  		</div>
					</div>
			</div>
	 	</div>

	 	<div class="col-md-11 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<select class="form-select" name="address" id="address1" required>
			    		<option value="">Select Customer Address</option>
			      	</select>
			      	<div id="addressicon"></div>
			    </div>
			</div>
	 	</div>
	 	@php
	 		if(count($services)>0) {
	 			$cname = "";
	 		}
	 		else {
	 			$cname = "active-focus";
	 		}
	 		if(count($worker)>0) {
	 			$wname = "";
	 		}
	 		else {
	 			$wname = "active-focus";
	 		}
	 	@endphp
	  <div class="col-md-11 mb-3">
	  	<select class="selectpicker form-control {{$cname}}" name="servicename[]" id="servicename" required="" multiple aria-label="Default select example" data-live-search="true">
	  		@foreach($services as $key =>$value)
					<option value="{{$value->id}}" data-hour="{{$value->time}}" data-min="{{$value->minute}}" data-price="{{$value->price}}" data-frequency="{{$value->frequency}}">{{$value->servicename}}</option>
			@endforeach
			</select>
			<div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a href="#"  data-bs-toggle="modal" data-bs-target="#add-services" class="" id="hidequoteservice"><i class="fa fa-plus second"></i></a>
        </div>
	  </div>

	  <div class="col-md-11 mb-3">
	  	<select class="selectpickerp1 form-control {{$cname}}" name="productname[]" id="productname" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Select Products" class="productclass">
	  		@foreach($productData as $key =>$value)
				<option value="{{$value->id}}" data-price="{{$value->price}}">{{$value->productname}}</option>
			@endforeach
			</select>
		<div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a href="#"  data-bs-toggle="modal" data-bs-target="#add-products" class="" id="hidequoteproduct"><i class="fa fa-plus third"></i></a>
        </div>
	  </div>
	   
	   <div class="col-md-6 mb-3" style="display: none;">
	  	<select class="form-select {{$wname}}" name="personnelid" id="personnelid">
			<option selected="" value="">Select a Personnel </option>
			@foreach($worker as $key => $value)
				<option value="{{$value->id}}">{{$value->personnelname}}</option>
			@endforeach
		</select>
	   </div>
		
		<div class="col-md-12 mb-3">
		   <div class="align-items-center justify-content-lg-between d-flex services-list">
		  	<label class="container-checkbox">Per hour
			  <input type="radio" id="test1" name="radiogroup" value="perhour" class="radiogroup" checked>
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Flate rate
			  <input type="radio" id="test2" name="radiogroup" value="flatrate" class="radiogroup">
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Reccuring
			  <input type="radio" id="test3" name="radiogroup" value="recurring" class="radiogroup">
			  <span class="checkmark"></span>
			</label>
		  </div>
	    </div>
	   
	   <div class="col-md-6 mb-2">
	  	<select class="form-select" name="frequency" id="frequency" required="">
		  <option selected="" value="">Service Frequency</option>
		  @foreach($tenture as $key=>$value)
		  	<option name="{{$value->tenturename}}" value="{{$value->tenturename}}">{{$value->tenturename}}</option>
          @endforeach
          <!-- <option name="Weekly" value="Weekly">Weekly</option>
          <option name="Be weekly" value="Be weekly">Bi-weekly</option>
          <option name="Monthly" value="Monthly">Monthly</option> -->
		</select>
	   </div>
	   
	    <div class="col-md-6 mb-2">
		<label>Default Service Time</label><br>
            <div class="timepicker timepicker1 form-control" style="display: flex;align-items: center;">
            <input type="text" class="hh N popfields" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N popfields" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div>
        </div>

	   <div class="col-md-12 mb-3 position-relative">
		<i class="fa fa-dollar" style="position: absolute;margin: 18px;"></i>
	   	<input type="text" class="form-control" placeholder="Price" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
	   		<input type="hidden" name="hiddenprice" id="hiddenprice">
	   </div>
	   
	   <div class="col-md-12 mb-3">
	   	<label style="position: relative;left: 12px;margin-bottom: 11px;">ETC</label>
	   <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" required>
	   </div>
	   <div class="col-md-12 mb-3">
		   <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required></textarea>
	   </div>
	   <div class="col-lg-6 mb-3">
	   	<button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6 mb-3">
	   	<button type="submit" class="btn btn-add btn-block" type="submit" name="quote" value="quote">Add a Quote</button>
	   </div>
	   
	   <div class="col-lg-12">
	   <button type="submit" class="btn btn-dark btn-block btn-lg p-2" name="share" value="share"><img src="images/share-2.png"  alt=""/> Share</button>
	   </div>
	   </div>
	  </form> 
	</div>
  </div>
</div>
</div>
<!-- Add Direct Ticket's -->
<div class="modal fade" id="add-tickets1" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
      
	   <div class="add-customer-modal d-flex justify-content-between align-items-center">
	   <h5>Add A New Ticket</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
	 

     @if(count($services)>0)
      @else
      	@if(count($productData)==0)
     		<div style="color: red;">Step2: Please add a service in the services section.</div>
     		
     	@else
     		<div style="color: red;">Step1: Please add a service in the services section.</div>
     		
     	@endif
     @endif

	  @if(count($customer)>0)
      @else
      	@if(count($productData)==0 && count($services)==0)
     		<div style="color: red;">Step3: Please add a customer in the customer section.</div>
     		
     	@elseif(count($productData)>0 && count($services)>0)
     		<div style="color: red;">Step1: Please add a customer in the customer section.</div>
     	@elseif(count($productData)>0)
     		<div style="color: red;">Step2: Please add a customer in the customer section.</div>	
     		
     	@elseif(count($services)>0)
     		<div style="color: red;">Step1: Please add a customer in the customer section.</div>
     		<div style="color: red;">Step2: Then please create a new tickets/quote here.</div>
     		
     	@endif
     @endif
    
	   <form class="form-material m-t-40 row form-valide" method="post" action="{{route('company.ticketcreate')}}" enctype="multipart/form-data">
        @csrf
        @php
	 		if(count($customer)>0) {
	 			$custmername = "";
	 		}
	 		else {
	 			$custmername = "active-focus";
	 		}
	 	@endphp
	 	<input type="hidden" name="ticketprice1" id="ticketprice1" value="">
	   <div class="row customer-form">
	   <div class="col-md-12 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<select class="form-select {{$custmername}}" name="customerid" id="customerid1" required>
			      	  <option selected="" value="">Select a customer </option>
				      @foreach($customer as $key => $value)
				      	<option value="{{$value->id}}">{{$value->customername}}</option>
				      @endforeach
			  		</select>
			  		<div class="d-flex align-items-center justify-content-end pe-3 mt-3">
			  			<a href="#"  data-bs-toggle="modal" data-bs-target="#add-customer2" class="" id="hideticket"><i class="fa fa-plus first-one"></i></a>
			  		</div>
					</div>
			    </div>
			</div>

	 	<div class="col-md-12 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<select class="form-select" name="address" id="address2" required>
			    		<option value="">Select Customer Address</option>
			      	</select>
			      	<div id="addressicon1"></div>
			    </div>
			</div>
	 	</div>
	 	@php
	 		if(count($services)>0) {
	 			$cname = "";
	 		}
	 		else {
	 			$cname = "active-focus";
	 		}
	 		if(count($worker)>0) {
	 			$wname = "";
	 		}
	 		else {
	 			$wname = "active-focus";
	 		}
	 	@endphp
		<div class="col-md-11 mb-3">
	  	<select class="selectpicker1 form-control {{$cname}}" name="servicename[]" id="servicenamet1" class="service" required="" multiple aria-label="Default select example" data-live-search="true">
	  		@foreach($services as $key =>$value)
				<option value="{{$value->id}}" data-hour="{{$value->time}}" data-min="{{$value->minute}}" data-price1="{{$value->price}}" data-frequency="{{$value->frequency}}">{{$value->servicename}}</option>
			@endforeach
		</select>
		<div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a href="#"  data-bs-toggle="modal" data-bs-target="#add-services1" class="" id="hidequoteservice2"><i class="fa fa-plus next-one"></i></a>
        </div>
	   </div>

	   	<div class="col-md-11 mb-3">
			<select class="selectpickert1 form-control {{$cname}}" name="productname[]" id="productnamet1" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Select Products">
				@foreach($productData as $key =>$value)
				<option value="{{$value->id}}" data-price="{{$value->price}}">{{$value->productname}}</option>
			@endforeach
			</select>
			<div class="d-flex align-items-center justify-content-end pe-3 mt-3">
	          <a href="#"  data-bs-toggle="modal" data-bs-target="#add-products1" class="" id="hidequoteservice2"><i class="fa fa-plus next-two"></i></a>
	        </div>
		</div>

	    <div class="col-md-12 mb-3">
        <select class="form-select" name="personnelid" id="personnelid">
          <option selected="" value="">Select Personnel</option>
          @foreach($worker as $key => $value) {
            <option value="{{$value->id}}">{{$value->personnelname}}</option>
           @endforeach
        </select>
      </div>
     @php
     	if($userData->openingtime!="" || $userData->openingtime!=null) {
           if($userData->openingtime<$userData->closingtime) {
            	$mintime = $userData->openingtime;
            	$maxtime = $userData->closingtime; 
	         } else {
	            $maxtime = $userData->openingtime;
	            $mintime = $userData->closingtime;
	         }

	          $mintime = date('h a', strtotime($mintime.':00'));
	          $maxtime = date('h a', strtotime($maxtime.':00'));
	    } else {
	          $mintime= "12 am";
	          $maxtime= "11 pm";
	    }
	    $inc   = 30 * 60;
        $start = (strtotime($mintime));
        $end   = (strtotime($maxtime)); 
     @endphp
    <div class="form-group col-md-6 mb-3 time" style="display:none;">
        <label style="position: relative;left: 12px;margin-bottom: 11px;">Time</label>
        <select class="form-control selectpicker" aria-label="Default select example" data-placeholder="Select Time" data-live-search="true" name="giventime" id="timedefault" style="height:auto;">
        	@for( $i = $start; $i <= $end; $i += $inc)
        		@php
        			$range = date( 'h:i a', $i);
        		@endphp
        		<option value="{{$range}}">{{$range}}</option>
        	@endfor
	    </select>
	</div>
		<div class="col-md-6 mb-3 date" style="display:none;">
	     <label style="position: relative;left: 12px;margin-bottom: 11px;">Date</label>
	      <input type="date" class="form-control etc" placeholder="Date" name="date" id="date" onkeydown="return false" style="position: relative;">
	     </div>
		<div class="col-md-12 mb-3">
		   <div class="align-items-center justify-content-lg-between d-flex services-list">
		  	<label class="container-checkbox">Per hour
			  <input type="radio" id="test1" name="radiogroup" class="radiogroup1" value="perhour" checked>
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Flate rate
			  <input type="radio" id="test2" name="radiogroup" class="radiogroup1" value="flatrate">
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Reccuring
			  <input type="radio" id="test3" name="radiogroup" class="radiogroup1" value="recurring">
			  <span class="checkmark"></span>
			</label>
		  </div>
	    </div>
	   
	   <div class="col-md-6 mb-2">
	  	<select class="form-select" name="frequency" id="frequency" required="">
		  <option selected="" value="">Service Frequency</option>
           @foreach($tenture as $key=>$value)
		  	<option name="{{$value->tenturename}}" value="{{$value->tenturename}}">{{$value->tenturename}}</option>
          @endforeach
		</select>
	   </div>
	   
	   <div class="col-md-6 mb-2">
	   <label>Default Service Time</label><br>
            <div class="timepicker timepicker1 form-control" style="display: flex;align-items: center;">
            <input type="text" class="hh N popfields1" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N popfields1" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div>
        </div>

	   <div class="col-md-12 mb-3 position-relative">
	   	<i class="fa fa-dollar" style="position: absolute;
    margin: 18px;"></i>
	   	<input type="text" class="form-control" placeholder="Price" name="price" id="price1" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
	   		<input type="hidden" name="hiddenprice" id="hiddenprice1">
	   </div>
	   
	   <div class="col-md-12 mb-3">
	   	<label style="position: relative;left: 12px;margin-bottom: 11px;">ETC</label>
	   <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" required>
	   </div>
	   <div class="col-md-12 mb-3">
		   <textarea class="form-control height-180" placeholder="Description" name="description" id="description" required></textarea>
	   </div>
	   <div class="col-lg-6 mb-3">
	   	<button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6 mb-3">
	   	<button type="submit" class="btn btn-add btn-block" name="ticket" value="ticket">Add a Ticket</button>
	   </div>
	   
	   <div class="col-lg-12">
	   <button type="submit" class="btn btn-dark btn-block btn-lg p-2" name="share" value="share"><img src="images/share-2.png"  alt=""/> Share</button>
	   </div>
	   </div>
	  </form> 
	</div>
  </div>
</div>
		</div>




<!----------------------Update form------------>
<div class="modal fade" id="edit-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('company.ticketupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata1"></div>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="view-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <div id="viewcompletedmodal"></div>
      </div>
    </div>
  </div>
</div>
<!-- Dots modal start -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      	<div style="width: 100%; text-align: center;">Quote Field Name</div>
      	
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" id="my-form">
      <div class="modal-body">
     <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div>
            <ul class="modal-ul-box">
              @foreach($fields as $key=>$value)
               @php
                $pagedata = App\Models\Managefield::select('*')
                ->where('page','companyquote')->where('userid',$auth_id)->where('columname',$value)->first();
                
               @endphp
                @if($value=="customername" || $value=="frequency" || $value=="price" || $value=="servicename")
                <li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata->columname) == $value ? 'checked' : '' }}>{{quote_filter($value)}} </label></li>
                  
                @endif
              @endforeach
            </ul>

          </div>
          <div class="text-center">
            <input type="submit" value="Submit" id="btnSubmit" class="btn btn-sve">
          </div>
        </div>
      </div>
     </div>
      </div>
    </form>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
<!-- dots Modal End -->
<!-- Dots modal start -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      	<div style="width: 100%; text-align: center;">Ticket Field Name</div>
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" id="my-form1">
      <div class="modal-body">
     <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div>
            <ul class="modal-ul-box">
              @foreach($fields1 as $key=>$value)
               @php
                $pagedata1 = App\Models\Managefield::select('*')
                ->where('page','companyticket')->where('columname',$value)->where('userid',$auth_id)->first();
                
               @endphp
                @if($value=="customername" || $value=="frequency" || $value=="price" || $value=="servicename")
                <li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata1->columname) == $value ? 'checked' : '' }}>{{quote_filter($value)}} </label></li>
                  
                @endif
              @endforeach
            </ul>

          </div>
          <div class="text-center">
            <input type="submit" value="Submit" id="btnSubmit1" class="btn btn-sve">
          </div>
        </div>
      </div>
     </div>
      </div>
    </form>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
<!-- dots Modal End -->

<div class="modal fade" id="service-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       <div id="viewservicelistdata"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-address" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.sharequote') }}" enctype="multipart/form-data">
          @csrf
          <div id="viewinvoicemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>
<!-- Add address modal -->
<div class="modal fade" id="add-address" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
      <div class="modal-body">
      
  
	 <div class="add-customer-modal d-flex justify-content-between align-items-center">
	 <h5>Add Address</h5>
     <button class="btn-close"  data-bs-dismiss="modal" aria-label="Close" id="quotecancel1212"></button>
     </div>
     
     
    <div class="row customer-form">
     
    <div class="col-md-12 mb-3">
     	<input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address5" required="">
		 <div class="find_msg" style="display:none;"></div>
  	</div>

		<div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal" aria-label="Close" id="quotecancel12">Cancel</button>
    </div>
    <div class="col-lg-6 mb-3">
     	<button id="saveaddress" class="btn btn-add btn-block">Add Address</button>
    </div>
    </div>
    </div>
    </div>
  </div>
</div>

<!-- Add address2 modal -->
<div class="modal fade" id="add-address2" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
      <div class="modal-body">
   
	 <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add Address</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" id="ticketcancel1" aria-label="Close"></button>
     </div>
     
    <div class="row customer-form">
     
    <div class="col-md-12 mb-3">
     	<input type="text" class="form-control"  placeholder="Search Addresses" name="address" id="address6" required="">
  	<div class="find_msg" style="display:none;"></div>
	</div>

		<div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal" id="ticketcancel12">Cancel</button>
    </div>
    <div class="col-lg-6 mb-3">
     	<button id="saveaddress2" class="btn btn-add btn-block">Add Address</button>
    </div>
    </div>
    </div>
    </div>
  </div>
</div>

<!-- Add customer modal -->
<div class="modal fade" id="add-customer" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
     <form class="form-material m-t-40  form-valide" method="post" id="createserviceticket"  enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
	  <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add a new customer</h5>
	 
     <button type="button" class="btn-close"  id="quotecancel"  data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     
    <div class="row customer-form">
     
    <div class="col-md-12 mb-3">
     	<input type="text" class="form-control" placeholder="Customer Full Name" name="customername" id="customername" required="">
  	</div>

  	<div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="Address" name="address" id="addressq1" required="">
    </div>

    <div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="Billing Address" name="billingaddress" id="billingaddress">
    </div>

    <div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="Mailing Address" name="mailingaddress" id="mailingaddress">
    </div>
     
     <div class="col-md-6 mb-3">
     
     <input type="text" class="form-control" placeholder="Phone Number" name="phonenumber" id="phonenumber" required="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onpaste="return false">
     
     </div>
     
     <div class="col-md-6 mb-3">
    
     <input type="email" class="form-control" placeholder="Email" name="email" id="email">
     
     </div>
     
     <div class="col-md-12 mb-3">
    
     <input type="text" class="form-control" placeholder="Company Name" name="companyname" id="companyname">
     
     </div>
     <div class="col-md-12 mb-3">
      <div class="d-flex align-items-center">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid">
          @foreach ($services as $service)
            <option value="{{$service->id}}">{{$service->servicename}}</option>
          @endforeach
        </select>
     </div>
   </div>

   <div class="col-md-12 mb-3" style="display:none;">
      <div class="d-flex align-items-center">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="productid[]" id="productid" data-placeholder="Select Products">
          @foreach ($productData as $product)
            <option value="{{$product->id}}">{{$product->productname}}</option>
          @endforeach
        </select>
     </div>
   </div>

   <div class="col-lg-12 mb-3">
      <div style="color: #999999;margin-bottom: 6px;position: relative;left: 10px;">Approximate Image Size : 285 * 195</div>
      <div class="drop-zone">
    <span class="drop-zone__prompt text-center">
  <small><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></small>
  Drop file here or click to upload</span>
    <input type="file" name="image" id="image" class="drop-zone__input" accept="image/png, image/gif, image/jpeg">
  </div>
     </div>
     
     
     <div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal" id="quotecancel_1">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
     <button type="submit" class="btn btn-add btn-block">Add Customer</button>
     </div>
     
     </div>
      </div>
     </form>
    </div>
  </div>
</div>

<!-- Add customer2 modal -->
<div class="modal fade" id="add-customer2" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
    <form class="form-material m-t-40  form-valide" method="post" id="createserviceticket1"  enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
	  <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add a new customer</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" id="ticketcancel" aria-label="Close"></button>
     </div>
     
    <div class="row customer-form">
     
    <div class="col-md-12 mb-3">
     	<input type="text" class="form-control" placeholder="Customer Full Name" name="customername" id="customername" required="">
  	</div>

  	<div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="Address" name="address" id="addresst1" required="">
    </div>
     
     <div class="col-md-12 mb-3">
	  <input type="text" class="form-control" placeholder="Billing Address" name="billingaddress" id="billingaddress">
	</div>

	<div class="col-md-12 mb-3">
	  <input type="text" class="form-control" placeholder="Mailing Address" name="mailingaddress" id="mailingaddress">
	</div>

     <div class="col-md-6 mb-3">
     
     <input type="text" class="form-control" placeholder="Phone Number" name="phonenumber" id="phonenumber" required="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onpaste="return false">
     
     </div>
     
     <div class="col-md-6 mb-3">
    
     <input type="email" class="form-control" placeholder="Email" name="email" id="email">
     
     </div>
     
     <div class="col-md-12 mb-3">
    
     <input type="text" class="form-control" placeholder="Company Name" name="companyname" id="companyname">
     
     </div>
     <div class="col-md-12 mb-3">
      <div class="d-flex align-items-center">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid">
          @foreach ($services as $service)
            <option value="{{$service->id}}">{{$service->servicename}}</option>
          @endforeach
        </select>
     </div>
   </div>
   <div class="col-md-12 mb-3" style="display:none;">
  <div class="d-flex align-items-center">
    <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="productid[]" id="productid" data-placeholder="Select Products">
      @foreach ($productData as $product)
        <option value="{{$product->id}}">{{$product->productname}}</option>
      @endforeach
    </select>
 </div>
</div>

   <div class="col-lg-12 mb-3">
      <div style="color: #999999;margin-bottom: 6px;position: relative;left: 10px;">Approximate Image Size : 285 * 195</div>
      <div class="drop-zone">
    <span class="drop-zone__prompt text-center">
  <small><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></small>
  Drop file here or click to upload</span>
    <input type="file" name="image" id="image" class="drop-zone__input" accept="image/png, image/gif, image/jpeg">
  </div>
     </div>
     
     
     <div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal" id="ticketcancelcustomer">Cancel</button>

     </div>
     <div class="col-lg-6 mb-3">
     <button type="submit" class="btn btn-add btn-block">Add Customer</button>
     </div>
     
     </div>
      </div>
     </form>
    </div>
  </div>
</div>
<!-- end modal -->
<!-- Modal -->
<div class="modal fade" id="add-services" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
      <form class="form-material m-t-40 form-valide" id="serviceform" method="post" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
	  <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add a new Service</h5>
     <button type="button" class="btn-close" id="quotecancel3" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
        @php
		$productData = App\Models\Inventory::where('user_id',auth()->user()->id)->orderBy('id','ASC')->get();
        if(count($productData)>0) {
            $pname= "";
        } else {
            $pname= "active-focus";
        }
        
        @endphp
        <div class="row customer-form" id="product-box-tabs">
          <div class="col-md-12 mb-2">
            <input type="text" class="form-control" placeholder="Service Name" name="servicename" id="servicename" required="">
          </div>
          <div class="col-md-12 mb-2 position-relative">
            <i class="fa fa-dollar" style="position: absolute;top:18px;left: 27px;"></i>
  <input type="text" class="form-control" placeholder="Service Default Price" name="price" id="price10" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
          </div>
          <div class="col-md-12 mb-2">
            <div class="d-flex align-items-center">
            <select class="selectpicker form-control me-2 {{$pname}}" multiple aria-label="Default select example" data-placeholder="Select Products" data-live-search="true" name="defaultproduct[]" id="defaultproduct" >
              <!-- <option selected="" value="">Select Product</option> -->
              @foreach($productData as $product)
                <option value="{{$product->id}}">{{$product->productname}}</option>
              @endforeach
            </select>
           <div class="wrapper" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <div class="tooltip">If you are not seeing the products in dropdown then add it in inventory section then select here.</div>
          </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <div class="align-items-center justify-content-lg-between d-flex services-list">
              <p>
                <input type="radio" id="test1" name="radiogroup" value="perhour" class="radiogrp" checked>
                <label for="test1">Per Hour</label>
              </p>
              <p>
                <input type="radio" id="test2" name="radiogroup" value="flatrate" class="radiogrp">
                <label for="test2">Flate Rate</label>
              </p>
              <p>
                <input type="radio" id="test3" name="radiogroup" value="recurring" class="radiogrp">
                <label for="test3">Recurring</label>
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-2">
            <select class="form-select" name="frequency" id="frequency10" required="">
              <option selected="" value="">Service Frequency</option>
			  @php $tenture = App\Models\Tenture::where('status','Active')->get(); @endphp
              @foreach($tenture as $key=>$value)
                <option name="{{$value->tenturename}}" value="{{$value->tenturename}}">{{$value->tenturename}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
              <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
              <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div>
          </div>
          <div class="col-md-12">
            <label>Choose Color</label><br>
            <span class="color-picker">
              <label for="colorPicker">
                <input type="color" value="" id="colorPicker" name="colorcode" style="width:235px;">
              </label>
            </span>
          </div>
          <div class="col-md-12">
            <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
              <input type="file" class="dropify" name="image" id="image"data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
          </div>
          <div class="col-md-12 mb-2" style="display: none;">
            <p class="create-gray mb-2">Create default checklist</p>
            <div class="align-items-center  d-flex services-list" style="flex-flow:wrap;">
              <label class="container-checkbox me-3">Point 1
                <input type="checkbox" name="pointckbox[]" id="pointckbox" value="point1"> <span class="checkmark"></span>
              </label>
              <label class="container-checkbox me-3">Point 2
                <input type="checkbox" name="pointckbox[]" id="pointckbox" value="point2"> <span class="checkmark"></span>
              </label>
              
            </div>
          </div>
          <div class="row mt-3">
          <div class="col-lg-6 mb-2">
            <button type="button" class="btn btn-cancel btn-block" id="quotecancel31" data-bs-dismiss="modal" aria-label="Close">Cancel</button>

          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block">Add a Service</button>
          </div>
        </div>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal -->

<!-- product Modal -->
<div class="modal fade" id="add-products" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
     <form class="form-material m-t-40 form-valide" id="productform" method="post" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
        <div class="add-customer-modal d-flex justify-content-between align-items-center">
	     	<h5>Add a new Product/Part</h5>
	    	<button type="button" class="btn-close" id="quotecancel3" data-bs-dismiss="modal" aria-label="Close"></button>
	    </div>

     <input type="hidden" name="cid" id="cid" value="">
  <div class="row customer-form" id="product-box-tabs">
    <div class="col-md-12 mb-3">
     <input type="text" class="form-control" placeholder="Product/Part Name" name="productname" id="productname" required="">
    </div>

    <div class="col-md-12 mb-3">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid">
          @foreach ($services as $service)
            <option value="{{$service->id}}">{{$service->servicename}}</option>
          @endforeach
        </select>
          
       </div>
    
    <div class="col-md-6 mb-3">
     <input type="text" class="form-control" placeholder="Quantity" name="quantity" id="quantity" required="">
    
     
     </div>
     <div class="col-md-6 mb-3">
    
     <input type="text" class="form-control" placeholder="Preferred Quantity" name="pquantity" id="pquantity" required="">
     
     </div>
     
     <div class="col-md-6 mb-3">
    	<input type="text" class="form-control" placeholder="SKU #" name="sku" id="sku" required="">
     </div>

     <div class="col-md-6 mb-3">
	   <input type="text" class="form-control" placeholder="Unit" name="unit" id="unit">
	 </div>

     <div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="$ Price" name="price" id="price" required="" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
     </div>

      <div class="col-lg-12 mb-3">
    <textarea class="form-control height-180" name="description" id="description" required="" placeholder="Description"></textarea>
    </div>
      <div class="col-md-12">
      <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
      <input type="file" class="dropify" name="image" id="image" data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
     </div>
     
     <div class="row mt-3">
     <div class="col-lg-6 mb-3">
      <button type="button" class="btn btn-cancel btn-block" id="quotecancel31" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
      <button class="btn btn-add btn-block" type="submit">Complete</button>
     </div>
     </div>
    </div>
    </div>
 </form>
  </div>
</div>
</div>
<!-- end product modal -->

<!-- product1 Modal -->
<div class="modal fade" id="add-products1" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
     <form class="form-material m-t-40 form-valide" id="productform1" method="post" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
        <div class="add-customer-modal d-flex justify-content-between align-items-center">
	     	<h5>Add a new Product/Part</h5>
	    	<button type="button" class="btn-close" id="quotecancel3" data-bs-dismiss="modal" aria-label="Close"></button>
	    </div>
   <div class="row customer-form" id="product-box-tabs">
    <div class="col-md-12 mb-3">
     <input type="text" class="form-control" placeholder="Product/Part Name" name="productname" id="productname" required="">
    </div>

    <div class="col-md-12 mb-3">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid">
          @foreach ($services as $service)
            <option value="{{$service->id}}">{{$service->servicename}}</option>
          @endforeach
        </select>
          
       </div>
    
    <div class="col-md-6 mb-3">
     <input type="text" class="form-control" placeholder="Quantity" name="quantity" id="quantity" required="">
    
     
     </div>
     <div class="col-md-6 mb-3">
    
     <input type="text" class="form-control" placeholder="Preferred Quantity" name="pquantity" id="pquantity" required="">
     
     </div>
     
     <div class="col-md-12 mb-3">
    	<input type="text" class="form-control" placeholder="SKU #" name="sku" id="sku" required="">
     </div>
     
     <div class="col-md-6 mb-3">
	   <input type="text" class="form-control" placeholder="Unit" name="unit" id="unit">
	 </div>
     
     <div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="$ Price" name="price" id="price" required="" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false">
     </div>

      <div class="col-lg-12 mb-3">
    <textarea class="form-control height-180" name="description" id="description" required="" placeholder="Description"></textarea>
    </div>
      <div class="col-md-12">
      <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
      <input type="file" class="dropify" name="image" id="image" data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
     </div>
     
     <div class="row mt-3">
     <div class="col-lg-6 mb-3">
      <button type="button" class="btn btn-cancel btn-block" id="quotecancel31" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
      <button class="btn btn-add btn-block" type="submit">Complete</button>
     </div>
     </div>
    </div>
    </div>
 </form>
  </div>
</div>
</div>
<!-- end product1 modal -->

<div class="modal fade" id="add-services1" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
      <form class="form-material m-t-40 form-valide" id="serviceform1" method="post" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
	  <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add a new Service</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="quotecancel4"></button>
     </div>
        @php
		$productData = App\Models\Inventory::where('user_id',auth()->user()->id)->orderBy('id','ASC')->get();
        if(count($productData)>0) {
            $pname= "";
        } else {
            $pname= "active-focus";
        }
        
        @endphp
        <div class="row customer-form" id="product-box-tabs">
          <div class="col-md-12 mb-2">
            <input type="text" class="form-control" placeholder="Service Name" name="servicename" id="servicename" required="">
          </div>
          <div class="col-md-12 mb-2 position-relative">
            <i class="fa fa-dollar" style="position: absolute;top:18px;left: 27px;"></i>
  <input type="text" class="form-control" placeholder="Service Default Price" name="price" id="price11" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
          </div>
          <div class="col-md-12 mb-2">
            <div class="d-flex align-items-center">
            <select class="selectpicker form-control me-2 {{$pname}}" multiple aria-label="Default select example" data-placeholder="Select Products" data-live-search="true" name="defaultproduct[]" id="defaultproduct" >
              <!-- <option selected="" value="">Select Product</option> -->
              @foreach($productData as $product)
                <option value="{{$product->id}}">{{$product->productname}}</option>
              @endforeach
            </select>
           <div class="wrapper" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <div class="tooltip">If you are not seeing the products in dropdown then add it in inventory section then select here.</div>
          </div>
          </div>
        </div>
          <div class="col-md-12 mb-2">
            <div class="align-items-center justify-content-lg-between d-flex services-list">
              <p>
                <input type="radio" id="test1" name="radiogroup" value="perhour" class="radiogrp" checked>
                <label for="test1">Per Hour</label>
              </p>
              <p>
                <input type="radio" id="test2" name="radiogroup" value="flatrate" class="radiogrp">
                <label for="test2">Flate Rate</label>
              </p>
              <p>
                <input type="radio" id="test3" name="radiogroup" value="recurring" class="radiogrp">
                <label for="test3">Recurring</label>
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-2">
            <select class="form-select" name="frequency" id="frequency11" required="">
              <option selected="" value="">Service Frequency</option>
			  @php $tenture = App\Models\Tenture::where('status','Active')->get(); @endphp
              @foreach($tenture as $key=>$value)
                <option name="{{$value->tenturename}}" value="{{$value->tenturename}}">{{$value->tenturename}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
              <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time11" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
              <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute11" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
            </div>
          </div>
          <div class="col-md-12">
            <label>Choose Color</label><br>
            <span class="color-picker">
              <label for="colorPicker">
                <input type="color" value="" id="colorPicker" name="colorcode" style="width:235px;">
              </label>
            </span>
          </div>
          <div class="col-md-12">
            <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
              <input type="file" class="dropify" name="image" id="image"data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
          </div>
          <div class="col-md-12 mb-2" style="display: none;">
            <p class="create-gray mb-2">Create default checklist</p>
            <div class="align-items-center  d-flex services-list" style="flex-flow:wrap;">
              <label class="container-checkbox me-3">Point 1
                <input type="checkbox" name="pointckbox[]" id="pointckbox" value="point1"> <span class="checkmark"></span>
              </label>
              <label class="container-checkbox me-3">Point 2
                <input type="checkbox" name="pointckbox[]" id="pointckbox" value="point2"> <span class="checkmark"></span>
              </label>
              
            </div>
          </div>
          <div class="row mt-3">
          <div class="col-lg-6 mb-2">
            <button class="btn btn-cancel btn-block" id="quotecancel41" data-bs-dismiss="modal">Cancel</button>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block">Add a Service</button>
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
<script type="text/javascript">
$('.dropify').dropify();

$('#createserviceticket').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('company/customer/createcticket')}}";
       $.ajax({
            url:url,
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {
			  swal("Done!", "Customer Created Successfully!", "success");
              $("#add-customer").modal('hide');
              $("#customerid").append("<option value="+data.id+">"+data.customername+"</option>");
              //$("#customerid_service").selectpicker('refresh');
              $("#add-tickets").show();
            }
        })
  });

	$('#createserviceticket1').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('company/customer/createcticket')}}";
       $.ajax({
            url:url,
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {
			  swal("Done!", "Customer Created Successfully!", "success");
              $("#add-customer2").modal('hide');
              $("#customerid1").append("<option value="+data.id+">"+data.customername+"</option>");
              //$("#customerid_service").selectpicker('refresh');
              $("#add-tickets1").show();
            }
        })
  	});
  
  
$('#serviceform').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('company/services/create')}}";
       $.ajax({
            url:url,
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {
			  swal("Done!", "Service Created Successfully!", "success");
              $("#add-services").modal('hide');
              $("#servicename").append("<option value="+data.id+"  data-hour="+data.time+" data-min="+data.minute+" data-price="+data.price+" data-frequency="+data.frequency+">"+data.servicename+"</option>");
              $("#servicename").selectpicker('refresh');
              $("#add-tickets").show();
            }
        })
  });

  $('#serviceform1').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('company/services/create')}}";
       $.ajax({
            url:url,
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {
			  swal("Done!", "Service Created Successfully!", "success");
              $("#add-services1").modal('hide');
              $(".service").append("<option value="+data.id+"  data-hour="+data.time+" data-min="+data.minute+" data-price="+data.price+" data-frequency="+data.frequency+">"+data.servicename+"</option>");
              $(".service").selectpicker('refresh');
              $("#add-tickets1").show();
            }
        })
  });

  $('#productform').on('submit', function(event) {
	      event.preventDefault();
	      var url = "{{url('company/inventory/create')}}";
	       $.ajax({
	            url:url,
	            data: new FormData(this),
	            method: 'POST',
	            dataType: 'JSON',
	            contentType: false,
	            cache: false,
	            processData: false,
	            success:function(data) {
				  swal("Done!", "Product Created Successfully!", "success");
	              $("#add-products").modal('hide');
	              $("#productname").append("<option value="+data.id+" data-price="+data.price+">"+data.productname+"</option>");
	              $("#productname").selectpicker('refresh');
              	  $("#add-tickets").show();
	            }
	        })
	  });

	$('#productform1').on('submit', function(event) {
	      event.preventDefault();
	      var url = "{{url('company/inventory/create')}}";
	       $.ajax({
	            url:url,
	            data: new FormData(this),
	            method: 'POST',
	            dataType: 'JSON',
	            contentType: false,
	            cache: false,
	            processData: false,
	            success:function(data) {
				  swal("Done!", "Product Created Successfully!", "success");
	              $("#add-products1").modal('hide');
	              $("#productnamet1").append("<option value="+data.id+" data-price="+data.price+">"+data.productname+"</option>");
	              $("#productnamet1").selectpicker('refresh');
              	  $("#add-tickets1").show();
	            }
	        })
	  });
	
  $(document).on('click','.emailinvoice',function(e) {
   var id = $(this).data('id');
   var email = $(this).data('email');
   $.ajax({
      url:"{{url('company/quote/leftbarinvoice')}}",
      data: {
        id: id,
        email :email
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {
        $('#viewinvoicemodaldata').html(data.html);
        
      }
    });
  }) 

   $("#hideticket").click(function() {
  	$("#add-tickets1").hide();
	});

  $("#hidequote").click(function() {
  	$("#add-tickets").hide();
	});

	$("#hidequoteservice").click(function() {
  	$("#add-tickets").hide();
	});
	$("#hidequoteservice1").click(function() {
  	$("#add-tickets1").hide();
	});

	$("#hidequoteservice2").click(function() {
  	$("#add-tickets1").hide();
	});

	$("#ticketcancel").click(function() {
		$("#add-tickets1").show();
  		$("#add-customer2").hide();
	});

	$("#ticketcancelcustomer").click(function() {
		$("#add-tickets1").show();
  		$("#add-customer2").hide();
	});
	

  $("#quotecancel").click(function() {
		$("#add-tickets").show();
  		$("#add-customer").hide();
	});
  $("#quotecancel_1").click(function() {
		$("#add-tickets").show();
  		$("#add-customer").hide();
	});

	$("#quotecancel3").click(function() {
		$("#add-tickets").show();
  	$("#add-customer").hide();
	});
	$("#quotecancel31").click(function() {
		$("#add-tickets").show();
  	$("#add-customer").hide();
	});

	$("#quotecancel4").click(function() {
		$("#add-tickets1").show();
  		$("#add-services1").hide();
	});

	$("#quotecancel41").click(function() {
		$("#add-tickets1").show();
  		$("#add-services1").hide();
	});
	
	$('html').on('click','#hidequote1',function() {
  	$("#add-tickets").hide();
	});

  $('html').on('click','#hideticket1',function() {
  	$("#add-tickets1").hide();
	});

	$("#quotecancel1").click(function() {
		$("#add-tickets").show();
  	$("#add-address").hide();
	});
	$("#quotecancel12").click(function() {
		$("#add-tickets").show();
  	$("#add-address").hide();
	});
	$("#quotecancel1212").click(function() {
		$("#add-tickets").show();
  	$("#add-address").hide();
	});

	$("#ticketcancel1").click(function() {
		$("#add-tickets1").show();
  	$("#add-address2").hide();
	});
	$("#ticketcancel12").click(function() {
		$("#add-tickets1").show();
  	$("#add-address2").hide();
	});
  
  $(document).ready(function() {
     $('#example').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
     displayDatePickerIfBrowserDoesNotSupportDateType();
  });

  $(".etc").on('click', function(event) {
   displayDatePickerIfBrowserDoesNotSupportDateType();
});

  	function displayDatePickerIfBrowserDoesNotSupportDateType() {
	  var datePicker = document.querySelector('.etc');
	  if (datePicker && datePicker.type !== 'date') {
	    $('.etc').datepicker();
	  }
	}

  $(document).ready(function() {
  	 $('#example1').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
    
  });
  $(document).ready(function() {
  	 $('#example2').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
    
  });

   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $(document).on('keyup','#address51',function(e) {
	var address = $('#address5').val();

	if(address=="") {
		   	//alert('address field is required');
			return false;
		   }
	   $.ajax({
            url:"{{url('company/quote/checklatitude')}}",
            data: {
              address: address,
     		},
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
				if(data.status=='success') {
				$("#saveaddress").attr("disabled", true);
				$(".find_msg").html(data.msg);
				$('.find_msg').css('color','red');
				$('.find_msg').css('display','block');
				}
				else {
					$("#saveaddress").attr("disabled", false);
					$('.find_msg').css('display','none');
				}
            }
        })
    });

   $(document).on('click','#saveaddress',function(e) {
      var customerid = $('#customerid').val();
	    var address = $('#address5').val();
		  if(address=="") {
		   	alert('address field is required');
		   }
	   $.ajax({
            url:"{{url('personnel/myticket/addaddress')}}",
            data: {
              address: address,
              customerid: customerid,
     		},
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
            	
              $("#add-address").modal('hide');
              $("#address1").append("<option value="+data.address+" selected>"+data.address+"</option>");
              $("#add-tickets").show();
            }
        })
	})

   	$(document).on('click','#saveaddress2',function(e) {
      var customerid = $('#customerid1').val();
	    var address = $('#address6').val();
		  if(address=="") {
		   	alert('address field is required');
			return false;
		   }
	   $.ajax({
            url:"{{url('personnel/myticket/addaddress')}}",
            data: {
              address: address,
              customerid: customerid,
     		},
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
            	
              $("#add-address2").modal('hide');
              $("#address2").append("<option value="+data.address+" selected>"+data.address+"</option>");
               $("#add-tickets1").show();
            }
        })
		})
   $(".add-ticket-alert").click(function() {
   		var id = $(this).data('id');
   		var name="";
   		$.ajax({
            url:"{{url('company/quote/updateticket')}}",
            data: {
              quoteid: id,
              name: name 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
            	
            	swal({
		            title: "Success!",
		            text: "The ticket has been created!",
		            type: "success"
		        }, function() {
		            location.reload();
		        });
            	
              
            }
        })
	});

   $(".repoenticket").click(function() {
   		var id = $(this).data('id');
   		var name="reopen";
   		$.ajax({
            url:"{{url('company/quote/updateticket')}}",
            data: {
              quoteid: id,
              name: name 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
            	
            	swal({
		            title: "Success!",
		            text: "The ticket has been reopen Successfully!",
		            type: "success"
		        }, function() {
		            location.reload();
		        });
            	
              
            }
        })
	});
  
  $(document).on('click','#createtickets',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewquotemodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewquotemodaldata').html(data.html);
            }
        })
  });
  $('html').on('click','.etc',function() {
  var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
    $('#etc').attr('min', maxDate);
  
 });
  function gethoursajax() {
  			var h=0;
				var m=0;
				$('select.selectpicker2').find('option:selected').each(function() {
			   	h += parseInt($(this).data('hour'));
				  m += parseInt($(this).data('min'));
				  
				});
				var realmin = m % 60;
    		var hours = Math.floor(m / 60);
    		h = h+hours;
				
		    $("#time").val(h);
				$("#minute").val(realmin);
	    }

	    function getpriceajax() {
  			var price=0;
				$('select.selectpicker2').find('option:selected').each(function() {
			   	price += parseFloat($(this).data('price'));
				});
				
				$("#price").val(price.toFixed(2));
	    }
		
		$(document).on('change', 'select.selectpicker2',function() {
			gethoursajax();
			getpriceajax();
		});

		 
  	$(document).ready(function() {
	   	h = "1";
	   	realmin = "00";
		$("#time").val(h);
		$("#minute").val(realmin);

		$("#time1").val(h);
		$("#minute1").val(realmin);

		// $('select.selectpicker').on('change', function() {
		// 	gethours();
		// 	getprice();
		// 	getfrequency();
		// });
		$('.selectpicker1').selectpicker();
		
		
			// $('select.selectpicker1').on('change', function() {
			// 	gethours1();
			// 	getprice1();
			// 	getfrequency1();
			// });

		$('#customerid').on('change', function() {
			var customerid = this.value;
			$("#address1").html('');
			$("#addressicon").html('');
				$.ajax({
					url:"{{url('company/quote/getaddressbyid')}}",
					type: "POST",
					data: {
					customerid: customerid,
					_token: '{{csrf_token()}}' 
					},
					dataType : 'json',
					success: function(result) {

					$('#address1').html('<option value="">Select Customer Address</option>'); 
						$.each(result.address,function(key,value) {
							$("#address1").append('<option value="'+value.address+'">'+value.address+'</option>');
						});
						$('#addressicon').html('<div class="d-flex align-items-center justify-content-end pe-3 mt-3"><a href="#"  data-bs-toggle="modal" data-bs-target="#add-address" id="hidequote1" class=""><i class="fa fa-plus select-customer"></i></a></div>');
					}
			});
		});

		$('#customerid1').on('change', function() {
			var customerid = this.value;
			$("#address2").html('');
			$("#addressicon").html('');

				$.ajax({
					url:"{{url('company/quote/getaddressbyid')}}",
					type: "POST",
					data: {
					customerid: customerid,
					_token: '{{csrf_token()}}' 
					},
					dataType : 'json',
					success: function(result) {
					$('#address2').html('<option value="">Select Customer Address</option>'); 
						$.each(result.address,function(key,value) {
							$("#address2").append('<option value="'+value.address+'">'+value.address+'</option>');
						});
						$('#addressicon1').html('<div class="d-flex align-items-center justify-content-end pe-3 mt-3"><a href="#"  data-bs-toggle="modal" data-bs-target="#add-address2" class="" id="hideticket1"><i class="fa fa-plus category-one"></i></a></div>');
					}
			});
		});    
	});

	$(document).on('click','#customerid2',function(e) {
		var customerid = this.value;
			$("#address2").html('');
				$.ajax({
					url:"{{url('company/quote/getaddressbyid')}}",
					type: "POST",
					data: {
					customerid: customerid,
					_token: '{{csrf_token()}}' 
					},
					dataType : 'json',
					success: function(result) {
					$('#address3').html('<option value="">Select Customer Address</option>'); 
						$.each(result.address,function(key,value) {
							$("#address3").append('<option value="'+value.address+'">'+value.address+'</option>');
						});
					}
			});
	}); 

 $('.selectpicker2').selectpicker();
		function gethours2() {
				var h=0;
				var m=0;
				$('select.selectpicker2').find('option:selected').each(function() {
			   	h += parseInt($(this).data('hour'));
				  m += parseInt($(this).data('min'));
				  
				});
				var realmin = m % 60;
    		var hours = Math.floor(m / 60);
    		h = h+hours;
				
		    $("#time").val(h);
				$("#minute").val(realmin);
	    }
		
		$('select.selectpicker2').on('change', function() {
			gethours2();
		});
 $(document).on('click','#editTickets',function(e) {
   $('.selectpicker2').selectpicker();
   var id = $(this).data('id');
   
   var type = $(this).data('type');
   if(type==undefined) {
   	var type = "quote";
   }
   var dataString =  'id='+ id+ '&type='+ type;
   $.ajax({
            url:'{{route('company.vieweditticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewmodaldata1').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
              $(".selectpickerp1").selectpicker();
              var hiddenprice = $("#priceticketedit").val();
              $("#edithiddenprice").val(hiddenprice);

            }
        })
  });

 	$('html').on('click','.info_link1',function() {
	    var tid = $(this).attr('dataval');
	    swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this ticket!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
            $.ajax({
              url:"{{url('company/quote/deleteQuote')}}",
              data: {
                id: tid 
              },
              method: 'post',
              dataType: 'json',
              refresh: true,
              success:function(data) {
               swal("Done!","It was succesfully deleted!","success");
              location.reload();
              }
            })
          } 
          else {
            location.reload();
          }
        }
      );
  	});
  	function onlyNumberKey(evt) {
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }

   $(document).on('click','#viewTickets',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewcompleteticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewcompletedmodal').html(data.html);
            }
        })
  });

	  $(document).ready(function () {
	    $("#btnSubmit").click(function (event) {
	      //stop submit the form, we will post it manually.
	      event.preventDefault();
	      // Get form
	      var form = $('#my-form')[0];
	      // FormData object 
	      var data = new FormData(form);
	      // If you want to add an extra field for the FormData
	      data.append("page", "companyquote");
	      // disabled the submit button
	      $("#btnSubmit").prop("disabled", true);
	      $.ajax({
	        url:'{{route('company.savefieldquote')}}',
	        data: data,
	        processData: false,
	        contentType: false,
	        cache: false,
	        timeout: 800000,
	        method: 'post',
	        dataType: 'json',
	        success: function (data) {
	            $("#output").text(data);
	            $("#btnSubmit").prop("disabled", false);
	            location.reload();
	        },
	        error: function (e) {
	            $("#output").text(e.responseText);
	            $("#btnSubmit").prop("disabled", false);
	        }
	      });
	    });

	    $("#btnSubmit1").click(function (event) {
	      //stop submit the form, we will post it manually.
	      event.preventDefault();
	      // Get form
	      var form = $('#my-form1')[0];
	      // FormData object 
	      var data = new FormData(form);
	      // If you want to add an extra field for the FormData
	      data.append("page", "companyticket");
	      // disabled the submit button
	      $("#btnSubmit1").prop("disabled", true);
	      $.ajax({
	        url:'{{route('company.savefieldticket')}}',
	        data: data,
	        processData: false,
	        contentType: false,
	        cache: false,
	        timeout: 800000,
	        method: 'post',
	        dataType: 'json',
	        success: function (data) {
	            $("#output").text(data);
	            $("#btnSubmit1").prop("disabled", false);
	            location.reload();
	        },
	        error: function (e) {
	            $("#output").text(e.responseText);
	            $("#btnSubmit1").prop("disabled", false);
	        }
	      });
	    });
	  });
$(document).on('click','.service_list_dot',function(e) {
   var id = $(this).data('id');
   var name = "Service Name";
   $.ajax({
	    url:'{{route('company.viewservicepopup')}}',
	    data: {
	      'id':id,
	      'name':name
	    },
	    method: 'post',
	    dataType: 'json',
	    refresh: true,
	    success:function(data) {
	      $('#viewservicelistdata').html(data.html);
	    }
	})
 });
$(".selectpickerp1").selectpicker();

function gethours() {
				var h=0;
				var m=0;
				$('select.selectpicker').find('option:selected').each(function() {
			   	h += parseInt($(this).data('hour'));
				  m += parseInt($(this).data('min'));

				  
				});
				//if(h == NaN) {
				var realmin = m % 60;
	    		var hours = Math.floor(m / 60);
	    		h = h+hours;
			    $("#time").val(h);
				$("#minute").val(realmin);
			//}
	    }

	function getprice() {
	  	var price = 0;
	  	$('select.selectpicker').find('option:selected').each(function() {
			   	price += parseFloat($(this).data('price'));
			});
			
			$("#price").val(price.toFixed(2));	
	}
	function getpricep1() {
	  	var price = parseFloat($("#price").val());
	  	$('select.selectpickerp1').find('option:selected').each(function() {
	  		price += parseFloat($(this).data('price'));
		});
		
		$("#price").val(price.toFixed(2));	
	  }

	  function getfrequency() {
	  	var frequency = "";
	  	$("#frequency option").removeAttr('selected');
	  	$('select.selectpicker').find('option:selected').each(function() {
			   	frequency = $(this).data('frequency');
			});
			$("#frequency option[value='"+frequency+"']").attr('selected', 'selected');
			
	  }

$(document).on('change','#servicename',function(e) {
	
	//gethours();
	getfrequency();
	var serviceid = $('#servicename').val();
	var productid = $('#productname').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid+ '&price='+ price;
    $.ajax({
          url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#price').val(data.totalprice);
            $('#hiddenprice').val(data.totalprice);
            $('#ticketprice').val(data.totalprice);
          }
      })


})
$(document).on('change','#productname',function(e) {
	//getpricep1();
	var serviceid = $('#servicename').val();
    var productid = $('#productname').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#price').val(data.totalprice);
            $('#hiddenprice').val(data.totalprice);
            $('#ticketprice').val(data.totalprice);
		  }
      })
});

$(document).on('change','#serviceid',function(e) {
	
	gethours();
	//getprice();
	getfrequency();
	var serviceid = $('#serviceid').val();
	var productid = $('#productid').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#priceticketedit').val(data.totalprice);
            $('#tickettotaledit').val(data.totalprice);
            $('#edithiddenprice').val(data.totalprice);
        }
      })


})
$(document).on('change','#productid',function(e) {
	//getpricep1();
	var serviceid = $('#serviceid').val();
    var productid = $('#productid').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#priceticketedit').val(data.totalprice);
            $('#tickettotaledit').val(data.totalprice);
            $('#edithiddenprice').val(data.totalprice);
          }
      })
});

	$(".selectpickert1").selectpicker();

	function gethours1() {
		var h=0;
		var m=0;
		$('select.selectpicker1').find('option:selected').each(function() {
	   	h += parseInt($(this).data('hour'));
		  m += parseInt($(this).data('min'));
		  
		});
		var realmin = m % 60;
	var hours = Math.floor(m / 60);
	h = h+hours;
		
	$("#time1").val(h);
		$("#minute1").val(realmin);
	}

    function getprice1() {
			var price = 0;
  		$('select.selectpicker1').find('option:selected').each(function() {
		   	price += parseFloat($(this).data('price1'));
			});
		
			$("#price1").val(price.toFixed(2));	
    }

    function getfrequency1() {
	  	var frequency = "";
	  	$("#frequency option").removeAttr('selected');
	  	$('select.selectpicker1').find('option:selected').each(function() {
			   	frequency = $(this).data('frequency');
			});
		$("#frequency option[value='"+frequency+"']").attr('selected', 'selected').change();
	}

	$(document).on('change','#servicenamet1',function(e) {
	
	//gethours1();
	getfrequency1();
	var serviceid = $('#servicenamet1').val();
    var productid = $('#productnamet1').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#price1').val(data.totalprice);
            $('#ticketprice1').val(data.totalprice);
            $('#hiddenprice1').val(data.totalprice);
           }
      })


})
$(document).on('change','#productnamet1',function(e) {
	var serviceid = $('#servicenamet1').val();
    var productid = $('#productnamet1').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#price1').val(data.totalprice);
            $('#ticketprice1').val(data.totalprice);
            $('#hiddenprice1').val(data.totalprice);
          }
      })
});

$(document).on('change','#personnelid',function(e) {
    var pvalue = $(this).val();
    if(pvalue=="") {
     $(".time").hide();
     $(".date").hide();
     $("#timedefault").attr('required',false);
     $("#date").attr('required',false);
    } else {
     $(".time").show();
     $(".date").show();
     $("#timedefault").attr('required',true);
     $("#date").attr('required',true);
    }
  });


// $('.popfields').change(function() { 
// 	var hiddenprice = $("#hiddenprice").val();
// 	var hours = $("#time").val();
// 	if(hours!="" || hours!=0 || hours != "00") {
// 		var hours = $("#time").val();
// 		var minutes = hours * 60;
// 	} else {
// 		var minutes = $("#time").val();
// 	}
// 	var fminute = minutes;
// 	if($("#minute").val()!=""){
// 	var secondmin = $("#minute").val();

// 	} else {
// 	var secondmin = 0;

// 	}
// 	var sum = parseInt(fminute) + parseInt(secondmin);
// 	var price = $("#hiddenprice").val();
// 	var persvalue = parseFloat(price/60);
// 	var totoalvalue = parseFloat(persvalue*sum).toFixed(2);
// 	var radiogroup = $('input[name="radiogroup"]:checked').val();
// 	if(radiogroup == "perhour") {
// 		$("#price").val(totoalvalue);
// 	} else {
// 		$("#price").val(hiddenprice);
// 	}
// });
// $(".radiogroup").change(function() { 
// 	var radiogroup = $('input[name="radiogroup"]:checked').val();
// 	var hours = $("#time").val();
// 	if(hours!="" || hours!=0 || hours != "00") {
// 		var hours = $("#time").val();
// 		var minutes = hours * 60;
// 	} else {
// 		var minutes = $("#time").val();
// 	}
// 	var fminute = minutes;
// 	if($("#minute").val()!=""){
// 	var secondmin = $("#minute").val();

// 	} else {
// 	var secondmin = 0;

// 	}
// 	var sum = parseInt(fminute) + parseInt(secondmin);
// 	var price = $("#hiddenprice").val();
// 	var persvalue = parseFloat(price/60);
// 	var totoalvalue = parseFloat(persvalue*sum).toFixed(2);
// 	if(radiogroup == "flatrate" || radiogroup == "recurring") {
// 		var hiddenprice = $("#hiddenprice").val();
// 		$("#price").val(hiddenprice);
// 	} else {
// 		$("#price").val(totoalvalue);
// 	}
// });

// // for ticket create section start
// 	$('.popfields1').change(function() { 
// 	var hiddenprice = $("#hiddenprice1").val();
// 	var hours = $("#time1").val();
// 	if(hours!="" || hours!=0 || hours != "00") {
// 		var hours = $("#time1").val();
// 		var minutes = hours * 60;
// 	} else {
// 		var minutes = $("#time1").val();
// 	}
// 	var fminute = minutes;
// 	if($("#minute1").val()!=""){
// 	var secondmin = $("#minute1").val();

// 	} else {
// 	var secondmin = 0;

// 	}
// 	var sum = parseInt(fminute) + parseInt(secondmin);
// 	var price = $("#hiddenprice1").val();
// 	var persvalue = parseFloat(price/60);
// 	var totoalvalue = parseFloat(persvalue*sum).toFixed(2);
// 	var radiogroup = $('input[class="radiogroup1"]:checked').val();
// 	if(radiogroup == "perhour") {
// 		$("#price1").val(totoalvalue);
// 	} else {
// 		$("#price1").val(hiddenprice);
// 	}
// });
// $(".radiogroup1").change(function() { 
// 	var radiogroup = $('input[class="radiogroup1"]:checked').val();
// 	var hours = $("#time1").val();
// 	if(hours!="" || hours!=0 || hours != "00") {
// 		var hours = $("#time1").val();
// 		var minutes = hours * 60;
// 	} else {
// 		var minutes = $("#time1").val();
// 	}
// 	var fminute = minutes;
// 	if($("#minute1").val()!="") {
// 	var secondmin = $("#minute1").val();

// 	} else {
// 	var secondmin = 0;

// 	}
// 	var sum = parseInt(fminute) + parseInt(secondmin);
// 	var price = $("#hiddenprice1").val();
// 	var persvalue = parseFloat(price/60);
// 	var totoalvalue = parseFloat(persvalue*sum).toFixed(2);
// 	if(radiogroup == "flatrate" || radiogroup == "recurring") {
// 		var hiddenprice = $("#hiddenprice1").val();
// 		$("#price1").val(hiddenprice);
// 	} else {
// 		$("#price1").val(totoalvalue);
// 	}
// });
// // ticket create section end

// $(document).on('change','.popfieldsedit',function(e) {
// 	var hiddenprice = $("#edithiddenprice").val();
// 	var hours = $("#timeedit").val();
// 	if(hours!="" || hours!=0 || hours != "00") {
// 		var hours = $("#timeedit").val();
// 		var minutes = hours * 60;
// 	} else {
// 		var minutes = $("#timeedit").val();
// 	}
// 	var fminute = minutes;
// 	if($("#minuteedit").val()!=""){
// 	var secondmin = $("#minuteedit").val();

// 	} else {
// 	var secondmin = 0;

// 	}
// 	var sum = parseInt(fminute) + parseInt(secondmin);
// 	var price = $("#edithiddenprice").val();
// 	var persvalue = parseFloat(price/60);
// 	var totoalvalue = parseFloat(persvalue*sum).toFixed(2);
// 	var radiogroup = $('input[class="radiogroupedit"]:checked').val();
// 	if(radiogroup == "perhour") {
// 		$("#priceticketedit").val(totoalvalue);
// 	} else {
// 		$("#priceticketedit").val(hiddenprice);
// 	}
// });

// $(document).on('change','.radiogroupedit',function(e) {
// 	var radiogroup = $('input[class="radiogroupedit"]:checked').val();
// 	var hours = $("#timeedit").val();
// 	if(hours!="" || hours!=0 || hours != "00") {
// 		var hours = $("#timeedit").val();
// 		var minutes = hours * 60;
// 	} else {
// 		var minutes = $("#timeedit").val();
// 	}
// 	var fminute = minutes;
// 	if($("#minuteedit").val()!="") {
// 	var secondmin = $("#minuteedit").val();

// 	} else {
// 	var secondmin = 0;

// 	}
// 	var sum = parseInt(fminute) + parseInt(secondmin);
// 	var price = $("#edithiddenprice").val();
// 	var persvalue = parseFloat(price/60);
// 	var totoalvalue = parseFloat(persvalue*sum).toFixed(2);
// 	if(radiogroup == "flatrate" || radiogroup == "recurring") {
// 		var hiddenprice = $("#edithiddenprice").val();
// 		$("#priceticketedit").val(hiddenprice);
// 	} else {
// 		$("#priceticketedit").val(totoalvalue);
// 	}
// });

// $(document).on('click','#priceticketedit',function(e) {
// 	var radiogroup = $('input[class="radiogroupedit"]:checked').val();
// 	var hours = $("#timeedit").val();
// 	if(hours!="" || hours!=0 || hours != "00") {
// 		var hours = $("#timeedit").val();
// 		var minutes = hours * 60;
// 	} else {
// 		var minutes = $("#timeedit").val();
// 	}
// 	var fminute = minutes;
// 	if($("#minuteedit").val()!="") {
// 	var secondmin = $("#minuteedit").val();

// 	} else {
// 	var secondmin = 0;

// 	}
// 	var sum = parseInt(fminute) + parseInt(secondmin);
// 	var price = $("#edithiddenprice").val();
// 	var persvalue = parseFloat(price/60);
// 	var totoalvalue = parseFloat(persvalue*sum).toFixed(2);
// 	if(radiogroup == "flatrate" || radiogroup == "recurring") {
// 		var hiddenprice = $("#edithiddenprice").val();
// 		$("#priceticketedit").val(hiddenprice);
// 	} else {
// 		$("#priceticketedit").val(totoalvalue);
// 	}
// });

$(document).on('click','.btn-close',function(e) {
	location.reload();
});

$(document).on('click','.btn-cancel',function(e) {
	location.reload();
});

</script>
@endsection