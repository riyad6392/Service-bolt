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

  .table-new tbody tr.selectedrow:after {
    /*background: #FDFBEC;*/
    background: #FAED61 !important;
  }
  .table-new tbody td, .table-new thead th
  {
    z-index: 1;
    padding: 6px 12px;
    height: 2.5em;
  }
  
  .card-body.customer-scroll-div {
    height: 450px;
    overflow-y: auto;
}

table.dataTable td {
    white-space: normal;
}

i.fa.fa-plus.second {
    position: absolute;
    top: 222px;
    right: 35px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}

i.fa.fa-plus.third {
    position: absolute;
    top: 284px;
    right: 35px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
}
</style>
<div class="">
<div class="content">
  <div class="row">
    <div class="col-md-12">
	  <a href="{{route('company.customer')}}" class="back-btn">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
	   Back</a>
        <div class="side-h3">
       <h3 class="info">Customer Info</h3>
      </div>
     </div>

     <div class="col-md-4 mb-4">
      <div class="card">
        <div class="card-body">
          @foreach($customerData as $key => $value)
          <div class="placeholder">
            @if($value->image!=null)
            <img src="{{url('uploads/customer/')}}/{{$value->image}}" alt="" style="object-fit: contain;">
            @else 
              <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="" style="object-fit: cover;">
            @endif
            <h4 class="thomas-img">{{$value->customername}}</h4>
          </div>
          <div>
           <p class="number-1">Phone Number</p>
           <h6 class="heading-h6">{{$value->phonenumber}}</h6>
          </div>
          <div>
            <p class="number-1">Email</p>
            <h6 class="heading-h6">{{$value->email}}</h6>
          </div>
          <div>
            <p class="number-1">Company Name</p>
            <h6 class="heading-h6">{{$value->companyname}}</h6>
          </div>
          @endforeach
        </div>
      </div>
    </div>

<div class="col-md-8 mb-4">
<div class="card">
<div class="card-body p-3">
  <div class="card-box">
  <h5 class="ms-2">
  Connected Addresses
  </h5>
  @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
  <div class="row">

<form class="form-material m-t-40 row form-valide" method="post" action="{{route('company.customeraddresscreate')}}">
  @csrf
  <input type="hidden" name="customerid" id="customerid" value="{{@$customerData[0]->id}}">
  <div class="col-lg-8 mb-2">
   <div class="show-fillter">
	   <input type="text" class="form-control" placeholder="Enter Address" name="address" id="address" required="">
    </div>
  </div>
  <div class="col-lg-3 offset-lg-1 mb-3">
    <!-- <button class="btn btn-add btn-block" type="submit">Add Address</button> -->
    <a class="btn btn-add btn-block" data-bs-toggle="modal" data-bs-target="#add-note" id="addnote" data-id="{{@$customerData[0]->id}}" style="padding: 10px; width:max-content; height:unset; ">Add Address</a>
  </div>
</form>


@if(count($customerAddress)>0)
          @else
         <div>Not found any addresses.</div>
         @endif
 <!--  <div class="col-lg-3 offset-lg-1 mb-3">
  <a href="#" class="add-btn-yellow btn-block text-center" data-bs-toggle="modal" data-bs-target="#add-customer">
	   Add New +
	   </a>
	   </div> -->
<div class="modal fade" id="create-ctickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
        <form method="post" action="{{ route('company.customercreatequote') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewcustomerquotemodaldata"></div>
      </form>
      </div>
  </div>
</div>
</div>
<input type="hidden" name="customerid" id="customerid" value="last(request()->segments())"> 	
<!--edit address modal open-->
<div class="modal fade" id="edit-address" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.updateaddress') }}" enctype="multipart/form-data">
          @csrf
          <div id="vieweditaddressmodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>



<!-- edit notes modal open -->
<div class="modal fade" id="edit-note" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.updatenotes') }}" enctype="multipart/form-data">
          @csrf
          <div id="vieweditnotemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

	   
	   <div class="col-lg-12 mb-2" style="display: none;">
	   <input type="text" placeholder=" Address" class="form-control input-gray" readonly style="box-shadow: none;"/>
	   </div>
     <table id="example1" class="table no-wrap table-new table-list" style="
    position: relative;">
    <thead>
      <tr>
        <th style="display: none;">Id</th>
        <th>Addresses</th>
        </tr>
          </thead>
          <tbody>
     @foreach($customerAddress as $key=>$value)
     <tr>
      <td style="display: none;"></td>
      <td>
  	   <div class="col-lg-12 mb-2">
    	   <div class="d-flex align-items-center justify-content-between ps-4 pt-2 pe-2 pb-2 address-line2 address-line">
    	     <div class="d-flex align-items-center addressdata"><a href="javascript:void(0);" class="info_link1" dataval="{{$value->id}}"><i class="fa fa-trash"></i></a><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="me-3"><path d="M12 18a6 6 0 100-12 6 6 0 000 12z" fill="currentColor"></path></svg> <a class="" data-bs-toggle="modal" data-bs-target="#edit-address" id="editaddress" data-id="{{$value->id}}" data-address="{{$value->address}}">{{wordwrap($value->address, 10, "\n")}}</a></div>
           <a class="" data-bs-toggle="modal" data-bs-target="#edit-note" id="editnote" data-id="{{$value->id}}" data-note="{{$value->notes}}"><img src="{{url('/')}}/images/writing.png" style="width:30px;"></a>
    	  <!--  <button class="btn btn-save confirm">Service Ticket</button> -->
        <a class="btn btn-save confirm" data-bs-toggle="modal" data-bs-target="#create-ctickets" id="createctickets" data-id="{{$value->customerid}}" data-addressid="{{$value->id}}" data-address="{{$value->address}}" data-type="quote" style="width: auto;font-size: 15px;white-space: nowrap;">Create Quote</a>

        <a class="btn btn-save confirm" data-bs-toggle="modal" data-bs-target="#create-ctickets" id="createctickets" data-id="{{$value->customerid}}" data-addressid="{{$value->id}}" data-address="{{$value->address}}" data-type="ticket" style="width: auto;font-size: 15px;white-space: nowrap;">Create Ticket</a>
        <a href="{{url('company/customer/ticketviewall/')}}/{{$value->customerid}}/{{encrypt($value->address)}}" class="btn btn-save confirm" style="width: auto;font-size: 15px;white-space: nowrap;">View Tickets</a>
    	   </div>
  	   </div>
     </td>
     </tr>
	   @endforeach
	</tbody>
        </table>
	   
	   
	   
  
  </div>
  </div>
</div>
</div>

     </div>

     <div class="col-md-8 mb-4">
             <div class="card">
       <div class="card-body customer-scroll-div">
	    <h5 class="mb-4">Recent Tickets</h5>
		 
		<div class="">
    <table id="example" class="table no-wrap table-new table-list align-items-center">
    <thead>
    <tr>
    <th>Ticket number</th>
    <th>Price</th>
    <th>Service Name</th>
    <th>Action</th>

    </tr>
    </thead>
    <tbody>
      @php
      $i = 1;
    @endphp
      @foreach($recentTicket as $ticket)
      @php
        $explode_id = explode(',', $ticket->serviceid);
        $servicedata = App\Models\Service::select('servicename')
          ->whereIn('services.id',$explode_id)->get();
      @endphp
    <tr target="{{$i}}">
    <td>#{{$ticket->id}}</td>
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
    <td><a class=" showSingle" target="{{$i}}" data-id="{{$ticket->customerid}}" datat-id="{{$ticket->id}}">View</a>
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
     </div>
     <div class="col-md-4 mb-4" style="display: block;">
       <div class="card">
        <div class="card-body">
		  <div class="row">
         <div id="viewleftservicemodal">
           
         </div>
     </div>
		</div>
   </div>
     </div>


     </div>
   </div>



          </div>
     
	 
	 
	 <!-- Modal -->
<div class="modal fade" id="add-customer" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal">
	   <h5>Add a new Address</h5>
	   </div>
	   
	   <div class="row customer-form">
	   <div class="col-md-12 mb-3">
	     
       <input
      id="pac-input1"
      class="form-control"
      type="text"
      placeholder="Search Box"
    />
	   </div>


	   <div class="col-lg-6 mb-3">
	   <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6 mb-3">
	   <button class="btn btn-add btn-block">Add Address</button>
	   </div>
	   
	   </div>
      </div>
     
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="viewimage" tabindex="-1" aria-labelledby="viewimageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-body">
       <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/gallery-1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/gallery-1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/gallery-1.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
      </div>
     
    </div>
  </div>
</div>

<div class="modal fade" id="send-emailinvoice" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.viewinvoice') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="invoicetype" id="invoicetype" value="sendinvoice">
          <div id="viewinvoicemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

<!-- Due invoice modal -->
<div class="modal fade" id="view-invoice" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.viewinvoice') }}" enctype="multipart/form-data" target="_blank">
          @csrf
          <div id="viewdueinvoicemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

<div class="modal fade" id="add-note" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
        <form method="post" action="{{route('company.customeraddresscreate')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="saddress" id="saddress" value="">
        <input type="hidden" name="customerid" id="customerid" value="{{@$customerData[0]->id}}">
        <div class="add-customer-modal">
                  <div style="font-size:25px;">Add Notes</div>
                 </div>
               <div class="col-md-12 mb-2">
                <div class="input_fields_wrap">
                  <select class="form-control selectpicker " multiple="" data-placeholder="Select Checklist" data-live-search="true" style="width: 100%;" tabindex="-1" aria-hidden="true" name="adminck[]" id="adminck">
                    @foreach($adminchecklist as $key =>$value1)
                      <option value="{{$value1->serviceid}}">{{$value1->checklistname}}</option>';
                    @endforeach

                 </select>
                </div>
              </div> 
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                <textarea class="form-control" name="note" id="note" placeholder="Notes" cols="45" rows="5"></textarea>
                  </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">Save</button>
        </div>
      </form>
      </div>
  </div>
</div>
</div>

<!-- Start services Modal -->
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
      $productData = App\Models\Inventory::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
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
                <input type="radio" id="test11" name="radiogroup" value="perhour" class="radiogrp" checked>
                <label for="test11">Per Hour</label>
              </p>
              <p>
                <input type="radio" id="test22" name="radiogroup" value="flatrate" class="radiogrp">
                <label for="test22">Flate Rate</label>
              </p>
              <p>
                <input type="radio" id="test33" name="radiogroup" value="recurring" class="radiogrp">
                <label for="test33">Recurring</label>
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
      @php
        $services = App\Models\Service::where('userid',auth()->user()->id)->orderBy('id','desc')->get();
      @endphp
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
@endsection

@section('script')
  <script>

  $('.dropify').dropify();
  $(document).ready(function() {
    // $('#example').DataTable({
    //   //"order": [[ 1, "desc" ]]
    // });
    $("#example tbody > tr:first-child").addClass('selectedrow');
    $('#example1').DataTable({
      //"order": [[ 1, "desc" ]]
    });
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   
    
    // $(document).on('change', 'select.selectpicker',function() {
    //   gethours();
    // });

$(document).on('click','#service_list_dot',function(e) {
   var id = $(this).data('id');
   var name = $(this).data('name');
   //var dataString =  'id='+ id,'name='+ name;
   //alert(dataString);
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


$(document).on('click','#createctickets',function(e) {
  $('.selectpicker').selectpicker();
   var cid = $(this).data('id');
   var address = $(this).data('address');
   var addressid = $(this).data('addressid');
   var type = $(this).data('type'); 
   $.ajax({
            url:'{{route('company.viewcustomerquotemodal')}}',
            data: {
              'cid':cid,
              'address':address,
              'addressid':addressid,
              'type':type,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewcustomerquotemodaldata').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
              $(".selectpickerc1").selectpicker();

              var firstOpen = true;
              var time;
              $('#timePicker').datetimepicker({
                useCurrent: false,
                format: "hh:mm A"
              }).on('dp.show', function() {
                if(firstOpen) {
                  time = moment().startOf('day');
                  firstOpen = false;
                }
              });
            }
        })
  });

 $(document).on('click','#editaddress',function(e) {
   var cid = $(this).data('id');
   var address = $(this).data('address');
   $.ajax({
            url:'{{route('company.vieweditaddressmodal')}}',
            data: {
              'cid':cid,
              'address':address,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#vieweditaddressmodaldata').html(data.html);
              initAutocomplete();
            }
        })
  });

 $(document).on('change','#date',function(e) {
    var datev = $("#date").val();
    $("#etc").val(datev);
 });

$(document).on('click','#addnote',function(e) {
  var address = $("#saddress").val();
  if(address=="") {
    swal({
        title: "Enter address?",
        text: "Can you please first enter address!",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "ok",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function (isConfirm) {
        if (isConfirm) {
          location.reload();
        }
      }
    )
    }
    var cid = $(this).data('id');
    $.ajax({
            url:'{{route('company.duplicateaddress')}}',
            data: {
              'cid':cid,
              'address':address,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              if(data.duplicateaddress == 1) {
                swal({
                    title: "Duplicate Address",
                    text: "This address already exist!",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "ok",
                    closeOnConfirm: false,
                    closeOnCancel: false
                  },
                  function (isConfirm) {
                    if (isConfirm) {
                      location.reload();
                    }
                  }
                )
              }
            }
        })    
});
 $(document).on('click','#editnote',function(e) {
   $('.selectpicker').selectpicker();
   var cid = $(this).data('id');
   var note = $(this).data('note');
   $.ajax({
            url:'{{route('company.vieweditnotemodal')}}',
            data: {
              'cid':cid,
              'note':note,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#vieweditnotemodaldata').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
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
 $('#selector').delay(2000).fadeOut('slow');

 jQuery(function() {
   jQuery('.showSingle').click(function() {
        var targetid = $(this).attr('target');
        var customerid = $(this).attr('data-id');
        var ticketid = $(this).attr('datat-id');
        $.ajax({
            url:"{{url('company/customer/leftbarticketdata')}}",
            data: {
              targetid: targetid,
              customerid: customerid,
              ticketid: ticketid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewleftservicemodal').html(data.html);
            }
        })
    });
    var customerid1 = $("#customerid").val();
    $.ajax({
            url:"{{url('company/customer/leftbarticketdata')}}",
            data: {
              targetid: 0,
              customerid1: customerid1 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewleftservicemodal').html(data.html);
            }
        })
 });

  $(document).on('click','.etc',function(e) {
    displayDatePickerIfBrowserDoesNotSupportDateType();
  });

  function displayDatePickerIfBrowserDoesNotSupportDateType() {
    var datePicker = document.querySelector('.etc');
    if (datePicker && datePicker.type !== 'date') {
      $('#datePicker').datepicker();
    }
  }

  $(document).on('click','#time',function(e) {
    displayTimePickerIfBrowserDoesNotSupportDateType();
  });

  function displayTimePickerIfBrowserDoesNotSupportDateType() {
    var timePicker = document.querySelector('#time');
    if(timePicker && timePicker.type !== 'time') {

      $('#time').datetimepicker({
        format: 'hh:mm A'
      });
  }
}

 $(document).on('click','.viewinvoice',function(e) {
   var id = $(this).data('id');
  var duedate = $(this).data('duedate');
  var invoicenote = $(this).data('invoicenote');


   $.ajax({
      url:"{{url('company/customer/leftbarviewinvoice')}}",
      data: {
        id: id,
        duedate: duedate,
        invoicenote: invoicenote,
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {
        $('#viewdueinvoicemodaldata').html(data.html);
        $("#duedate").datepicker({ 
          autoclose: true, 
          todayHighlight: true
        });
        
      }
    });
  })

 $('html').on('click','.info_link1',function() {
      var addressid = $(this).attr('dataval');
      swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this address!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if(isConfirm) {
             $.ajax({
              url:"{{url('company/customer/deleteAddress')}}",
              data: {
                id: addressid 
              },
              method: 'post',
              dataType: 'json',
              refresh: true,
              success:function(data) {
                if(data == 0) {
                    swal({
                      title: "Can not delete!",
                      text: "This Address already exist in ticket!",
                      type: "warning",
                    });
                } else {
                  swal("Done!","It was succesfully deleted!","success");
                  location.reload();
                }
              }
            })
          } 
          else {
            location.reload();
          }
        }
      );
  
 });



$('table tr').each(function(a,b) {
    $(b).click(function() {
         $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
    });
  });


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
      $('select.selectpickerc1').find('option:selected').each(function() {
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
            $('#tickettotal').val(data.totalprice);
          }
      })
});

 $(document).on('change','#servicename',function(e) {
    //gethours();
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
            $('#tickettotal').val(data.totalprice);
            
          }
      })
   });

 function onlyNumberKey(evt) {
   // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
          return false;
      return true;
  }

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
              $("#create-ctickets").show();
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
  $(document).on('change','#personnelid',function(e) {
    var pvalue = $(this).val();
    if(pvalue=="") {
     $(".time").hide();
     $(".date").hide();
     $("#time").attr('required',false);
     $("#date").attr('required',false);
    } else {
      $(".time").show();
     $(".date").show();
     $("#time").attr('required',true);
     $("#date").attr('required',true);
    }
  });

  $(document).on('click','.sendtocustomer',function(e) {
        $("#view-invoice").hide();
        $("#send-emailinvoice").show();
        var duedate = $(this).data('duedate');
        var invoicenote = $(this).data('invoicenote');
        var id = $(this).data('id');
        var email = $(this).data('email');

         $.ajax({
          url:"{{url('company/customer/leftbarviewinvoiceemail')}}",
          data: {
            id: id,
            email :email,
            duedate: duedate,
            invoicenote: invoicenote,
          },
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#viewinvoicemodaldata').html(data.html);
            $("#duedate").datepicker({ 
              autoclose: true, 
              todayHighlight: true
            });
          }
        });
       return false;
    })
  $(document).on('click','.cancelpopup',function(e) {
   location.reload();
  });

  $(document).on('click','#editTickets',function(e) {
   $('.selectpicker2').selectpicker();
   var id = $(this).data('id');

   var pvalue = $(this).data('pid');
   
   var type = $(this).data('type');
   if(type==undefined) {
    var type = "quote";
   }
   var dataString =  'id='+ id+ '&type='+ type;
     $.ajax({
      url:'{{route('company.billingvieweditticketmodal')}}',
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
  $(document).on('click','.btn-close',function(e) {
    $("#view-invoice").css('display','block');
    $("#send-emailinvoice").css('display','none');
  });
   $(document).on('click','.btn-cancel',function(e) {
    $("#view-invoice").css('display','block');
    $("#send-emailinvoice").css('display','none');
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
          $('#address3').html('<option value="">Select Customer Address or Begin Typing a Name</option>'); 
            $.each(result.address,function(key,value) {
              var addressid = value.id+'#id#'+value.address;
              $("#address3").append('<option value="'+addressid+'">'+value.address+'</option>');
            });
          }
      });
  }); 

  $(document).on('change','#serviceid',function(e) {
  gethours();
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
            console.log(data.totalprice);
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

function gethours() {
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
</script>
@endsection