@extends('layouts.header')
@section('content')
<style type="text/css">
  #loader1 {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
.loadershow1 {
    height: 100%;
    position: absolute;
    left: 0;
    width: 100%;
    z-index: 10;
    background: rgb(35 35 34 / 43%);
    padding-top: 15%;
    display: flex;
    justify-content: center;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.modal-ul-box {
    padding: 0;
    margin: 10px;
    height: auto;
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

i.dots.fa.fa-ellipsis-v.fa-2x.pull-right {
    margin-right: 6px;
}

</style>

<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Customers</h3>
        @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
     </div>
     </div>


     <div class="col-md-12">
      <div class="card">
     <div class="card-body">
     <div class="row">
     <div class="col-lg-3 mb-2" style="visibility: hidden;">
     <div class="show-fillter">
     <select id="inputState" class="form-select">
                      <option>Show: A to Z</option>
                      <!-- <option>By Service</option>
                      <option>By Frequency</option>
                      <option>By Company</option> -->
                    </select>
     </div>
     </div>
     
     <div class="col-lg-5 offset-lg-1 mb-2" style="visibility: hidden;">
     <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search customers by name"/>
     <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button>
     </div>
     
     </div>
     
     <div class="col-lg-3 text-end mb-2">
     <a href="#"  data-bs-toggle="modal" data-bs-target="#add-customer" class="add-btn-yellow">
     Add Customer +
     </a>
     </div>
     
     </div>
     @php
      $pagedata = App\Models\Managefield::select('*')
      ->where('page','companycustomer')->where('userid',$auth_id)->get();
     $cpagedta = count($pagedata);
     @endphp
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th style="display: none;">Id</th>
              @if($cpagedta==0)
              <th>CUSTOMER NAME</th>
              <th>PHONE NUMBER</th>
              <th>EMAIL</th>
              <th>COMPANY NAME</th>
              <th>SERVICE</th>
              @else
                @foreach($pagedata as $key => $pagecolumn)
                 @if($pagecolumn->columname=="customername")
                    <th>CUSTOMER NAME</th>
                  @endif
                  @if($pagecolumn->columname=="phonenumber")
                    <th>PHONE NUMBER</th>
                  @endif
                  @if($pagecolumn->columname=="email")
                    <th>EMAIL</th>
                  @endif
                  @if($pagecolumn->columname=="companyname")
                    <th>COMPANY NAME</th>
                  @endif
                  @if($pagecolumn->columname=="serviceid")
                    <th>SERVICE</th>
                  @endif
                @endforeach
              @endif
              <th>Action</th><div class="heading-dot pull-right" data-bs-toggle="modal" data-bs-target="#exampleModal">             
          <i class="dots fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
        </div>
            </tr>
          </thead>
          <tbody>
            @foreach($customerData as $customer)
              
            @php
              $explode_id = explode(',', $customer->serviceid);
              $servicedata = App\Models\Service::select('servicename')
                ->whereIn('services.id',$explode_id)->get();
            @endphp
            <tr>
              <td style="display: none;">{{$customer->id}}</td>
            @if($cpagedta==0)
            <td>
               <a href="{{url('company/customer/view/')}}/{{$customer->id}}" class="user-hover">
                 <div class="user-descption align-items-center d-flex">
                   <div class="user-img me-3">
                    @if($customer->image!=null)
                    <img src="{{url('uploads/customer/thumbnail')}}/{{$customer->image}}" alt="" style="object-fit:none;">
                    @else
                    <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="">
                    @endif
                   </div>
                   <div class="user-content">
                    <h5 class="m-0">{{$customer->customername}}</h5>
                   </div>
                 </div>
               </a>
            </td>
           
              <td>{{$customer->phonenumber}}</td>
              <td>@if($customer->email) {{$customer->email}} @else ---- @endif</td>
              <td>{{$customer->companyname}}</td>
              @php
              $i=0;
              @endphp
              
              <td>
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
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $customer->serviceid }}" data-name="{{$customer->customername}}">
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
                  <td><a href="{{url('company/customer/view/')}}/{{$customer->id}}" class="user-hover">
                     <div class="user-descption align-items-center d-flex">
                       <div class="user-img me-3">
                        @if($customer->image!=null)
                        <img src="{{url('uploads/customer/thumbnail')}}/{{$customer->image}}" alt="" style="object-fit:none;">
                        @else
                        <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="">
                        @endif
                       </div>
                       <div class="user-content">
                        <h5 class="m-0">{{$customer->customername}}</h5>
                       </div>
                     </div>
                   </a></td>
                   @endif
                  @if($pagecolumn->columname=="phonenumber")
                  <td>{{$customer->phonenumber}}</td>
                  @endif
                  @if($pagecolumn->columname=="email")
                  <td>@if($customer->email) {{$customer->email}} @else ---- @endif</td>
                  @endif
                  @if($pagecolumn->columname=="companyname")
                  <td>@if($customer->companyname) {{$customer->companyname}} @else --- @endif</td>
                  @endif
                  @if($pagecolumn->columname=="serviceid")
                  @php
                    $i=0;
                  @endphp
                  <td>
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
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $customer->serviceid }}" data-name="{{$customer->customername}}">
                      <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
                    </svg>
                    @endif
                    @php
                $i=1; break;
              @endphp
                    @endforeach
              </td>
              
                  @endif
                @endforeach  
              @endif
              <td><a class="btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#edit-customer" id="editCustomer" data-id="{{$customer->id}}">Edit</a>
              <a href="javascript:void(0);" class="info_link1 btn btn-edit p-3 w-auto" dataval="{{$customer->id}}">Delete</a>
            </td>
               
              <!-- <td><a href="#" class="red-light-bg btn-common">Weekly</a></td> -->
            </tr>
            @endforeach
          </tbody>
        </table>
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
  <div class="modal-dialog modal-dialog-centered">
    <div class="loadershow1">
      <div id="loader1" style="display: block;"></div>
    </div>
    <div class="modal-content customer-modal-box  overflow-hidden">
     <form class="form-material m-t-40  form-valide" method="post" action="{{route('company.customercreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
       <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add a new customer</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     
     <div class="row customer-form">
     <div class="col-md-12 mb-3">
     
     <input type="text" class="form-control" placeholder="Customer Full Name" name="customername" id="customername" required="">
  
     </div>

     <div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="Address" name="address" id="address" required="">
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
    
     <input type="email" class="form-control email" placeholder="Email" name="email" id="email" data-id="">
     </div>
     <div class="col-md-12 mb-3 email_msg " style="display:none;">
     
                  </div>
     
     <div class="col-md-12 mb-3">
    
     <input type="text" class="form-control" placeholder="Company Name" name="companyname" id="companyname">
     
     </div>
     <div class="col-md-11 mb-3">
      <div class="d-flex align-items-center">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid">
          @foreach ($services as $service)
            <option value="{{$service->id}}">{{$service->servicename}}</option>
          @endforeach
        </select>
        <div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a class="add-person" href="#"  data-bs-toggle="modal" data-bs-target="#add-services" class="add-coustomar" id="hidequote"><i class="add-coustomar fa fa-plus"></i></a>
        </div>
        <div class="wrapper" style="display: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
          <div class="tooltip">If you are not seeing the services in dropdown then create some services in service section then select here.</div>
        </div>
     </div>
   </div>

   <div class="col-md-11 mb-3">
      <div class="d-flex align-items-center">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="productid[]" id="productid" data-placeholder="Select Products">
          @foreach ($products as $product)
            <option value="{{$product->id}}">{{$product->productname}}</option>
          @endforeach
        </select>
        <div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a class="add-person" href="#"  data-bs-toggle="modal" data-bs-target="#add-products" class="add-coustomar" id="hidequote"><i class="add-coustomar fa fa-plus"></i></a>
        </div>
     </div>
   </div>
     
     <div class="col-md-12">
      <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
      <input type="file" class="dropify" name="image" id="image"data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
     </div>
     
     
     <div class="row mt-3"><div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
     <button type="submit" class="btn btn-add btn-block">Add Customer</button>
     </div></div>
     
     </div>
      </div>
     </form>
    </div>
  </div>
</div>

<!-------------------More Service--------------------->
<div class="modal fade" id="service-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       
      
        <div id="viewservicelistdata"></div>
       
      
     
    </div>
  </div>
</div>

<!----------------------edit form------------>
<div class="modal fade" id="edit-customer" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('company.customerupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata"></div>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Dots modal start -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
       <div style="width: 100%; text-align: center;"> Customer Field Name</div>
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
                ->where('page','companycustomer')->where('columname',$value)->where('userid',$auth_id)->first();
                
               @endphp
                @if($value=="customername" || $value=="phonenumber" || $value=="email" || $value=="companyname" || $value=="serviceid")
                  @php
                    
                  @endphp
               
                  <li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata->columname) == $value ? 'checked' : '' }}>{{company_filter($value)}} </label></li>
                  
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
<!-- Modal -->
<div class="modal fade" id="add-services" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
      <form class="form-material m-t-40 form-valide" id="serviceform" method="post" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
        <div class="add-customer-modal d-flex justify-content-between align-items-center">
          <h5>Add a new Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @php
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
  <input type="text" class="form-control" placeholder="Service Default Price" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
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
              <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
              <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
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
            <button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
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
          @foreach ($serviceData as $service)
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
@endsection

@section('script')
<script type="text/javascript">

$(document).ready(function() {
    $(document).on('blur', '.email', function() {
        email = $(this).val();
        cid = $(this).data('id');
        if(cid=="") {
          cid = 0;
        } else {
          cid = cid;
        }
        var url = "{{url('/company/customer/checkemail')}}" + '?' + 'email=' + email+ '&' +'cid=' + cid;
        //alert('f');

        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            refresh: true,
            success: function(data) {

                if (data == 1) {
                    // $("#email_msg").remove();
                    $(".email_msg").empty();
                    $(".email_msg").append("<div class='alert alert-danger'<strong>Data Duplicate!</strong> Email is already exits.</div>");
                    $(".email_msg").show();
                    $('.btn-add').attr("disabled", true);
                } else {
                    $(".email_msg").empty();
                    $(".email_msg").hide();
                    $('.btn-add').attr("disabled", false);

                }

            }
        });
    });
});

  $('.dropify').dropify();
  $(document).ready(function() {
    $('#example').DataTable({
      
      "order": [[ 0, "desc" ]]
    });
    //   $('#example').DataTable( {
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'csv'
    //     ]
    // } );

  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
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

              console.log(data.html);
              $('#viewservicelistdata').html(data.html);
            }
        })
  });
  
  $('#serviceform').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('company/services/createservice')}}";
       $.ajax({
            url:url,
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {
              
              $("#add-services").modal('hide');
              $("#serviceid").append("<option value="+data.id+" selected>"+data.servicename+"</option>");
              $("#serviceid").selectpicker('refresh');
              $("#add-customer").show();
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
                $("#productid").append("<option value="+data.id+" data-price="+data.price+" selected>"+data.productname+"</option>");
                $("#productid").selectpicker('refresh');
                $("#add-customer").show();
              }
          })
    });
  
  $(document).on('click','#serviceform1',function(e) {

      var servicename = $('#servicename').val();
      var price = $('#price').val();
      var defaultproduct = $('#defaultproduct').val();
      var radiogroup = $(".radiogrp:checked").val();
      var frequency = $('#frequency').val();
      var time = $('#time').val();
      var minute = $('#minute').val();
      var pointckbox = $('#pointckbox').val();
      var serviceid = $('#serviceid').val();
      var colorcode = $('#colorPicker').val();
      var image = $('#image').attr('src');

    
          $.ajax({
            url:"{{url('company/services/createservice')}}",
            data: {
              servicename: servicename,
              price: price,
              defaultproduct: defaultproduct,
              radiogroup: radiogroup,
              frequency: frequency,
              time: time,
              minute: minute,
              pointckbox: pointckbox,
              serviceid: serviceid,
              colorcode: colorcode,

        },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              
              $("#add-services").modal('hide');
              $("#serviceid").append("<option value="+data.id+">"+data.servicename+"</option>");
              $("#add-customer").show();
            }
        })
  })

$('#selector').delay(2000).fadeOut('slow');

$(document).on('click','#editCustomer',function(e) {
  $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewcustomermodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewmodaldata').html(data.html);
              $('.dropify').dropify();
              $('.selectpicker').selectpicker({
                size: 3
              });
              initAutocomplete();
            }
        })
  });

  function readURL(input) {
     if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function(e) {
             $('#bannerPreview12').css('background-image', 'url('+e.target.result +')');
             $('#bannerPreview12').hide();
             $('#bannerPreview12').fadeIn(650);
         }
         reader.readAsDataURL(input.files[0]);
     }
   }
   $('html').on('change','.bannerUpload',function(){
   //$(document).on("change","#bannerUpload",function() {
      $('.defaultimage').hide();
       $('#bannerPreview12').show();
     readURL(this);
   });

  $('html').on('click','.info_link1',function() {
        var customerid = $(this).attr('dataval');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this customer!",
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
                url:"{{url('company/customer/deleteCustomer')}}",
                data: {
                  id: customerid 
                },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                  if(data == 0) {
                    swal({
                      title: "Can not delete!",
                      text: "This customer already exist in ticket!",
                      type: "warning",
                    });
                  } else {
                    swal("Done!","Customer deleted succesfully!","success");
                    location.reload();
                  }
                }
            })
          } 
          else {
            location.reload(); //swal("Cancelled", "Your customer is safe :)", "error");
          }
        }
      );
  });
  $(window).on('load', function () {
      $('.loadershow1').hide();
    })

  // $('#save_value').click(function() {
     
  //   $('.checkcolumn:checked').each(function() {
  //     alert($(this).val());
  //   });
  // });
  $(document).ready(function () {
    $("#btnSubmit").click(function (event) {
      //stop submit the form, we will post it manually.
      event.preventDefault();
      // Get form
      var form = $('#my-form')[0];
      // FormData object 
      var data = new FormData(form);
      // If you want to add an extra field for the FormData
      data.append("page", "companycustomer");
      // disabled the submit button
      $("#btnSubmit").prop("disabled", true);
      $.ajax({
        url:'{{route('company.savefieldpage')}}',
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
  });  
</script>
@endsection


