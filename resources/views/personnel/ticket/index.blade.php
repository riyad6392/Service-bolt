@extends('layouts.workerheader')
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
    height: 340px;
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
</style>
<div class="">
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Ticket and Quotes</h3>
     </div>
     </div>
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
	  <td><a class="btn btn-dark btn-block btn-lg add-ticket-alert" data-id="{{$quote->id}}">Ticket +</a></td>
	  <td><a class=" btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="{{$quote->id}}">Edit</a>
	  <a href="javascript:void(0);" class="info_link1 btn btn-edit p-3 w-auto" dataval="{{$quote->id}}">Delete</a>
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
	  <td><a href="{{route('worker.managescheduler')}}" class="btn btn-dark btn-block btn-lg">Schedule +</a></td>
	  <td><a class="btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="{{$ticket->id}}">Edit</a>
	  	<a href="javascript:void(0);" class="info_link1 btn btn-edit p-3 w-auto" dataval="{{$ticket->id}}">Delete</a>
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
	  <th>Ticket number</th>
	  <th>Customer Name</th>
	  <th>Frequency</th>
	  <th>Price</th>
	  <th>Service Name</th>
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
			@endphp
	  <tr>
	  <td>#{{$ticket->id}}</td>
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
	  <td><a class="btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$ticket->id}}">View</a>
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





<div class="modal fade" id="add-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal">
	   	<h5>Add A New Quote</h5>
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
    
	   <form class="form-material m-t-40 row form-valide" method="post" action="{{route('worker.quotecreate')}}" enctype="multipart/form-data">
        @csrf
        @php
	 		if(count($customer)>0) {
	 			$custmername = "";
	 		}
	 		else {
	 			$custmername = "active-focus";
	 		}
	 	@endphp
	   <div class="row customer-form">
	   <div class="col-md-12 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<select class="form-select {{$custmername}}" name="customerid" id="customerid" required>
			      	  <option selected="" value="">Select a customer </option>
				      @foreach($customer as $key => $value)
				      	<option value="{{$value->id}}">{{$value->customername}}</option>
				      @endforeach
			  		</select>
			    </div>
			</div>
	 	</div>

	 	<div class="col-md-12 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<select class="form-select" name="address" id="address1" required>
			    		<option value="">Select Customer Address</option>
			      	</select>
			    </div>
			</div>
	 	</div>

	 	<!-- <div class="col-md-12 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address" required="">
			    </div>
			</div>
	 	</div> -->
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
	  <div class="col-md-12 mb-3">
	  	<select class="selectpicker form-control {{$cname}}" name="servicename[]" id="servicename" required="" multiple aria-label="Default select example" data-live-search="true">
	  		@foreach($services as $key =>$value)
				<option value="{{$value->id}}">{{$value->servicename}}</option>
			@endforeach
		</select>
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
			  <input type="radio" id="test1" name="radiogroup" value="perhour" checked>
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Flate rate
			  <input type="radio" id="test2" name="radiogroup" value="flatrate">
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Reccuring
			  <input type="radio" id="test3" name="radiogroup" value="recurring">
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
	  	 <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div>
        </div>

	   <div class="col-md-12 mb-3">
	   	<input type="text" class="form-control" placeholder="Price" name="price" id="price" onkeypress="return onlyNumberKey(event)" required>
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
	   	<button type="submit" class="btn btn-add btn-block" type="submit">Add a Quote</button>
	   </div>
	   
	   <div class="col-lg-12">
	   <button class="btn btn-dark btn-block btn-lg p-2"><img src="images/share-2.png"  alt=""/> Share</button>
	   </div>
	   </div>
	  </form> 
	</div>
  </div>
</div>
</div>
<!-- Add Direct Ticket's -->
<div class="modal fade" id="add-tickets1" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal">
	   	<h5>Add A New Ticket</h5>
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
    
	   <form class="form-material m-t-40 row form-valide" method="post" action="{{route('worker.ticketcreate')}}" enctype="multipart/form-data">
        @csrf
        @php
	 		if(count($customer)>0) {
	 			$custmername = "";
	 		}
	 		else {
	 			$custmername = "active-focus";
	 		}
	 	@endphp
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
			    </div>
			</div>
	 	</div>

	 	<div class="col-md-12 mb-2">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<select class="form-select" name="address" id="address2" required>
			    		<option value="">Select Customer Address</option>
			      	</select>
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
		<div class="col-md-12 mb-3">
	  	<select class="selectpicker form-control {{$cname}}" name="servicename[]" id="servicename" required="" multiple aria-label="Default select example" data-live-search="true">
	  		@foreach($services as $key =>$value)
				<option value="{{$value->id}}">{{$value->servicename}}</option>
			@endforeach
		</select>
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
			  <input type="radio" id="test1" name="radiogroup" value="perhour" checked>
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Flate rate
			  <input type="radio" id="test2" name="radiogroup" value="flatrate">
			  <span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Reccuring
			  <input type="radio" id="test3" name="radiogroup" value="recurring">
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
	  	<label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div>
        </div>

	   <div class="col-md-12 mb-3">
	   	<input type="text" class="form-control" placeholder="Price" name="price" id="price" onkeypress="return onlyNumberKey(event)" required>
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
	   	<button type="submit" class="btn btn-add btn-block" type="submit">Add a Ticket</button>
	   </div>
	   
	   <div class="col-lg-12">
	   <button class="btn btn-dark btn-block btn-lg p-2"><img src="images/share-2.png"  alt=""/> Share</button>
	   </div>
	   </div>
	  </form> 
	</div>
  </div>
</div>



@endsection
<!----------------------Update form------------>
<div class="modal fade" id="edit-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('worker.ticketupdate') }}" enctype="multipart/form-data">
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
                <li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata->columname) == $value ? 'checked' : '' }}>{{strtoupper($value)}} </label></li>
                  
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
                <li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata1->columname) == $value ? 'checked' : '' }}>{{strtoupper($value)}} </label></li>
                  
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
@section('script')
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc&callback=initAutocomplete&libraries=places"
      async
    ></script> -->
  <script>
//     function initAutocomplete() {

//   var input = document.getElementById('address');
//            var autocomplete = new google.maps.places.Autocomplete(input);
//             // autocomplete.setComponentRestrictions(
//             // {'country': ['us']});

//            autocomplete.addListener('place_changed', function() {
//                var place = autocomplete.getPlace();
//                 autocomplete.setComponentRestrictions(
//             {'country': ['us']});

           
//            });

  
// }
</script>
<script type="text/javascript">
 
  $(document).ready(function() {
     $('#example').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
  });
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
   $(".add-ticket-alert").click(function() {
   		var id = $(this).data('id');
   		$.ajax({
            url:"{{url('personnel/managequote/updateticket')}}",
            data: {
              quoteid: id 
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
  
  $(document).on('click','#createtickets',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('worker.viewquotemodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
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

  	$(document).ready(function() {
		$('#customerid').on('change', function() {
			var customerid = this.value;
			$("#address1").html('');
				$.ajax({
					url:"{{url('personnel/managequote/getaddressbyid')}}",
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
					}
			});
		});

		$('#customerid1').on('change', function() {
			var customerid = this.value;
			$("#address2").html('');
				$.ajax({
					url:"{{url('personnel/managequote/getaddressbyid')}}",
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

 $(document).on('click','#editTickets',function(e) {
   $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('worker.vieweditticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldata1').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
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
            url:'{{route('worker.viewcompleteticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
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
	        url:'{{route('worker.savefieldquote')}}',
	        data: data,
	        processData: false,
	        contentType: false,
	        cache: false,
	        timeout: 800000,
	        method: 'post',
	        dataType: 'json',
	        success: function (data) {
	            $("#output").text(data);
	            console.log("SUCCESS : ", data);
	            $("#btnSubmit").prop("disabled", false);
	            location.reload();
	        },
	        error: function (e) {
	            $("#output").text(e.responseText);
	            console.log("ERROR : ", e);
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
	        url:'{{route('worker.savefieldticket')}}',
	        data: data,
	        processData: false,
	        contentType: false,
	        cache: false,
	        timeout: 800000,
	        method: 'post',
	        dataType: 'json',
	        success: function (data) {
	            $("#output").text(data);
	            console.log("SUCCESS : ", data);
	            $("#btnSubmit1").prop("disabled", false);
	            location.reload();
	        },
	        error: function (e) {
	            $("#output").text(e.responseText);
	            console.log("ERROR : ", e);
	            $("#btnSubmit1").prop("disabled", false);
	        }
	      });
	    });
	  });
$(document).on('click','.service_list_dot',function(e) {
   var id = $(this).data('id');
   var name = "Service Name";
   $.ajax({
	    url:'{{route('worker.viewservicepopup')}}',
	    data: {
	      'id':id,
	      'name':name
	    },
	    method: 'post',
	    dataType: 'json',
	    refresh: true,
	    success:function(data) {

	      console.log(data.html);
	      $('#viewservicelistdata').html(data.html);
	    }
	})
 });
</script>
@endsection