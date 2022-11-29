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
.pac-container {
	z-index: 9999;
}
</style>
<div class="">
<div class="content">
     <div class="row">
    
<div class="col-md-12">
    <div class="side-h3">
        <h3>Create Ticket </h3>
       	@if(Session::has('success'))
		  <div class="alert alert-success" id="selector">
			{{Session::get('success')}}
		  </div>
		@endif
 	</div>
</div>
<div class="col-lg-12">
	<div class="card mb-3 h-auto">
		<div class="card-body">

	<form class="form-material m-t-40 row form-valide" method="post" action="{{route('worker.ticketcreate1')}}" enctype="multipart/form-data">
        	@csrf
      <input type="hidden" name="ticketprice" id="ticketprice" value="">
			<div class="row customer-form">

				<div class="col-md-6 mb-2">
					<div class="input_fields_wrap">
	    	 			<div class="mb-3">
	    	 				<select class="form-select" name="customerid" id="customerid1" required>
					      	  <option selected="" value="">Select a customer </option>
						      @foreach($customer as $key => $value)
						      	<option value="{{$value->id}}">{{$value->customername}}</option>
						      @endforeach
					  		</select>
					  		<div class="d-flex align-items-center justify-content-end pe-3 mt-3">
					  			<a href="#"  data-bs-toggle="modal" data-bs-target="#add-customer" class=""><i class="fa fa-plus"></i></a>
					  		</div>
	    	 			</div>
					</div>
				</div>

				<div class="col-md-6 mb-2">
				   <div class="input_fields_wrap">
			    		<div class="mb-3">
					    	<select class="form-select" name="address" id="address2" required>
					    		<option value="">Select Customer Address</option>
					      	</select>
					      	<div id="addressicon"></div>
					    </div>
					</div>
			 	</div>
			 	<div class="col-md-6 mb-3">
	        <select class="selectpicker form-control" name="servicename[]" id="servicename" required="" multiple aria-label="Default select example" data-live-search="true">
	        	@foreach($services as $key => $value)
	        		<option value="{{$value->id}}" data-hour="{{$value->time}}" data-min="{{$value->minute}}" data-price="{{$value->price}}" data-frequency="{{$value->frequency}}">{{$value->servicename}}</option>
	        	@endforeach
	        </select>
			   </div>

			   <div class="col-md-6 mb-3">
	        <select class="selectpicker1 form-control" name="productname[]" id="productname" required="" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Select Products">
	        	@foreach($products as $key => $value)
	        		<option value="{{$value->id}}" data-hour="{{$value->time}}" data-min="{{$value->minute}}" data-price="{{$value->price}}" data-frequency="{{$value->frequency}}">{{$value->productname}}</option>
	        	@endforeach
	        </select>
			   </div>
	   
	    <div class="col-md-6 mb-3" style="display: none;">
		  	<select class="form-select" name="personnelid" id="personnelid">
			<option selected="" value="">Select a Personnel </option>
			@foreach($workerlist as $key => $value)
				<option value="{{$value->id}}">{{$value->personnelname}}</option>
			@endforeach
		</select>
	    </div>
	    <div class="col-md-6 mb-3">
	  		<div class="align-items-center justify-content-lg-start d-flex services-list">
		    <label class="container-checkbox">Per hour
	  			<input type="radio" id="test1" name="radiogroup" value="perhour" checked>
	  			<span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Flate rate
	  			<input type="radio" id="test2" name="radiogroup" value="flatrate">
	  			<span class="checkmark"></span>
			</label>
			<label class="container-checkbox">Reoccuring
		  		<input type="radio" id="test3" name="radiogroup" value="recurring">
		  		<span class="checkmark"></span>
			</label>
		   </div>
	   	</div>
		
	   <div class="col-md-6 mb-3">
	  	<select class="form-select" name="frequency" id="frequency" required="">
		  <option selected="" value="">Service Frequency</option>
		  	@foreach($tenture as $key=>$value)
          		<option name="{{$value->tenturename}}" value="{{$value->tenturename}}">{{$value->tenturename}}</option>
        	@endforeach
		</select>
	   </div>
	   <div class="col-md-6 mb-2">
	   	    <div class="timepicker timepicker1" style="display:inline-block;width: 145px;">
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div>
	  	<!-- <select class="form-select" name="time" required="">
	        <option selected="" value="">Default Time</option>
	        <option value="15 Minutes">15 Minutes</option>
	        <option value="30 Minutes">30 Minutes</option>
	        <option value="45 Minutes">45 Minutes</option>
	        <option value="1 Hours">1 Hours</option>
      	</select> -->
	   </div>
	   @php
	   	$timearray = array(
	   		"09:00 AM" =>"09:00 AM",
	   		"10:00 AM" =>"10:00 AM",
	   		"11:00 AM" =>"11:00 AM",
	   		"12:00 PM" =>"12:00 PM",
	   		"13:00 AM" =>"13:00 PM",
	   		"14:00 PM" =>"14:00 PM",
	   		"15:00 PM" =>"15:00 PM",
	   		"16:00 PM" =>"16:00 PM",
	   		"17:00 PM" =>"17:00 PM",
	   		"18:00 PM" =>"18:00 PM",
	   	)
	   @endphp
	   <div class="col-md-6 mb-3">
	   	<input type="text" class="form-control" placeholder="Price" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" required>
	   </div>
	   
	   <div class="col-md-6 mb-3">
	   <input type="date" class="form-control etc" placeholder="ETC" name="etc" id="etc" onkeydown="return false" style="position: relative;" required>
	   </div>
	   <div class="col-md-12 mb-3">
	   	<textarea class="form-control height-180" placeholder="Description" name="description" id="description" style="color:#212529;" required></textarea>
	   </div>
	   <div class="col-lg-4 mb-3">
	   	<button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-4 mb-3">
	   	<button type="submit" class="btn btn-add btn-block" type="submit">Add a Ticket</button>
	   </div>
	   
	   <div class="col-lg-4">
	   	<button type="submit" class="btn btn-dark btn-block btn-lg p-2"><img src="images/share-2.png" alt=""> Share</button>
	   </div>
	</div>
</form>
	</div>
</div>
</div>
</div>
   </div>
	</div>
<!-- Add customer modal -->
<div class="modal fade" id="add-customer" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
     <form class="form-material m-t-40  form-valide" method="post" action="{{route('worker.customercreate1')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
       <div class="add-customer-modal">
     <h5>Add a new customer</h5>
     </div>
     
     <div class="row customer-form">
     <div class="col-md-12 mb-3">
     
     <input type="text" class="form-control" placeholder="Customer Full Name" name="customername" id="customername" required="">
  
     </div>
     
     <div class="col-md-6 mb-3">
     
     <input type="text" class="form-control" placeholder="Phone Number" name="phonenumber" id="phonenumber" required="">
     
     </div>
     
     <div class="col-md-6 mb-3">
    
     <input type="email" class="form-control" placeholder="Email" name="email" id="email">
     
     </div>
     
     <div class="col-md-12 mb-3">
    
     <input type="text" class="form-control" placeholder="Company Name" name="companyname" id="companyname" required="">
     
     </div>
     <div class="col-md-12 mb-3">
      <div class="d-flex align-items-center">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid">
          @foreach ($services as $service)
            <option value="{{$service->id}}">{{$service->servicename}}</option>
          @endforeach
        </select>
        <div class="wrapper" style="display: none;">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
 			<div class="tooltip">If you are not seeing the services in dropdown then create some services in service section then select here.</div>
		</div>
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
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
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

<div class="modal fade" id="add-address" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
     <!-- <form class="form-material m-t-40  form-valide" method="post" action="{{route('company.customeraddresscreate')}}" enctype="multipart/form-data">
        @csrf -->
      <div class="modal-body">
       <div class="add-customer-modal">
     <h5>Add Address</h5>
     </div>
     <input type="hidden" name="customerid" id="customerid" value="">
     <div class="row customer-form">
     <div class="col-md-12 mb-3">
     
     <input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address" required="">
  
     </div>
     <div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
     <button id="saveaddress" class="btn btn-add btn-block">Add Address</button>
     </div>
     
     </div>
      </div>
     <!-- </form> -->
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $(".selectpicker1").selectpicker();


   function gethours() {
        var h=0;
        var m=0;
        $('select.selectpicker').find('option:selected').each(function() {
          h += parseInt($(this).data('hour'));
          m += parseInt($(this).data('min'));
        });
        var realmin = m % 60;
        var hours = Math.floor(m / 60);
        h = h+hours;
        $("#time").val(h);
        $("#minute").val(realmin);
      }

    function getprice() {
	  	var price = 0;
	  	$('select.selectpicker').find('option:selected').each(function() {
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
				gethours();
				getfrequency();
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
			            $('#ticketprice').val(data.totalprice);
			          }
			      })
				})
				$(document).on('change','#productname',function(e) {
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
				            $('#ticketprice').val(data.totalprice);
						  }
				      })
				});
    
   //  $(document).on('change', 'select.selectpicker',function() {
   //    gethours();
   //    getprice();
			// getfrequency();
   //  });
   $('#customerid1').on('change', function() {
		var customerid = this.value;
		$("#address2").html('');
		$("#addressicon").html('');
			$.ajax({
				url:"{{url('personnel/myticket/getaddressbyid')}}",
				type: "POST",
				data: {
				customerid: customerid,
				_token: '{{csrf_token()}}' 
				},
				dataType : 'json',
				success: function(result) {
					$("#address2").empty();
					$('#address2').html('<option value="">Select Customer Address</option>'); 
					$.each(result.address,function(key,value) {
						$("#address2").append('<option value="'+value.address+'">'+value.address+'</option>');
					});

					$('#addressicon').html('<div class="d-flex align-items-center justify-content-end pe-3 mt-3"><a href="#"  data-bs-toggle="modal" data-bs-target="#add-address" class=""><i class="fa fa-plus"></i></a></div>');

					$('#customerid').val(customerid);
					//$("#servicename").empty();
					//console.log(result.serviceData);
					// $.each(result.serviceData,function(key,value) {
					// 	$("#servicename").append('<option value="'+value.id+'" data-hour="'+value.time+'" data-min="'+value.minute+'" data-price="'+value.price+'" data-frequency="'+value.frequency+'">'+value.servicename+'</option>');
					// });
					
					$('.selectpicker').selectpicker('refresh');
				}
		});
	});

	function onlyNumberKey(evt) {
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }

    $("#personnelid").change(function() {
    	var pname = this.value;
    	if(pname == "") {
    		$("#sptime").css("display", "none");
    		$('#scheduledtime').removeAttr('required');
    	} else {
    		$("#sptime").css("display", "block");
    	}
    })

    $(document).on('click','#saveaddress',function(e) {
       var customerid = $('#customerid').val();
	   var address = $('#address').val();
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
              $("#address2").append("<option value="+data.address+">"+data.address+"</option>");
            }
        })
	})

$('#selector').delay(2000).fadeOut('slow');
</script>
@endsection


