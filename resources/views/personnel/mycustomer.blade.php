@extends('layouts.workerheader')
@section('content')
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
      <div class="col-lg-2 mb-2" style="visibility: hidden;">
        Quick Look</div>
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
     
     <div class="col-lg-3 offset-lg-1 mb-2" style="visibility: hidden;">
     <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search customers by name"/>
     <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button>
     </div>
     
     </div>
    @php
      $auth_id = auth()->user()->id;
      $userData =  App\Models\User::select('workerid')->where('id',$auth_id)->first();
      $workers = App\Models\Personnel::where('id',$userData->workerid)->first();
      
      $permissonarray = explode(',',$workers->ticketid);
      if(in_array("Add Customer", $permissonarray)) {
    @endphp 
      <div class="col-lg-3 text-end mb-2">
     <a href="#"  data-bs-toggle="modal" data-bs-target="#add-customer" class="add-btn-yellow">
     Add Customer +
     </a>
     </div>
     @php
      }
     @endphp
     </div>
     
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th style="display: none;">ID</th>
              <th>CUSTOMER NAME</th>
              <th>PHONE NUMBER</th>
              <th>EMAIL</th>
              <th>COMPANY NAME</th>
              <th>SERVICE</th>
            @if(in_array("Edit Customer", $permissonarray)) 
              <th>Action</th>
            @endif
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
              <td>
                <div class="user-descption align-items-center d-flex">
                 <div class="user-img me-3">
                  @if($customer->image!=null)
                  <img src="{{url('uploads/customer/thumbnail')}}/{{$customer->image}}" alt="">
                  @else
                  <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="">
                  @endif
                 </div>
                 <div class="user-content">
                  <h5 class="m-0"><a href="{{url('personnel/customer/detail/')}}/{{$customer->id}}">{{$customer->customername}}</a></h5>
                 </div>
                </div>
              </td>
              <td>{{$customer->phonenumber}}</td>
              <td>{{$customer->email}}</td>
              <td>{{$customer->companyname}}</td>
              @php
              $i=0;
              @endphp
              
              <td>
                @foreach($servicedata as $servicename)
                {{$servicename->servicename}}
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
              @if(in_array("Edit Customer", $permissonarray))
              <td><a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-customer" id="editCustomer" data-id="{{$customer->id}}">Edit</a></td>
              @endif
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

<!-------------------More Service--------------------->
<div class="modal fade" id="service-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       
      
        <div id="viewservicelistdata"></div>
       
      
     
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-customer" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
     <form class="form-material m-t-40  form-valide" method="post" action="{{route('worker.customercreate')}}" enctype="multipart/form-data">
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
    
     <input type="email" class="form-control" placeholder="Email" name="email" id="email" required="">
     
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
        <div class="d-flex align-items-center justify-content-end pe-3 mt-3">
          <a href="#"  data-bs-toggle="modal" data-bs-target="#add-services" class="" id="hidequote"><i class="fa fa-plus"></i></a>
        </div>
        <div class="wrapper" style="display: none;">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
 <div class="tooltip">If you are not seeing the services in dropdown then create some services in service section then select here.</div>
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

<!----------------------edit form------------>
<div class="modal fade" id="edit-customer" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('worker.customerupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata"></div>
      </form>
      </div>
    </div>
  </div>
</div>

<!-- add service modal -->
<div class="modal fade" id="add-services" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
      <form class="form-material m-t-40 form-valide" id="serviceform" method="post" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
        <div class="add-customer-modal">
          <h5>Add a new Service</h5>
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
<!-- end modal -->
@endsection

@section('script')
<script type="text/javascript">
  $('.dropify').dropify();
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
	$(document).on('click','#service_list_dot',function(e) {
   		var id = $(this).data('id');
   		var name = $(this).data('name');
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

  $(document).on('click','#editCustomer',function(e) {
  $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('worker.viewcustomermodal')}}',
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
            }
        })
  });

  $('#serviceform').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('personnel/services/createservice')}}";
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
              $("#serviceid").append("<option value="+data.id+">"+data.servicename+"</option>");
              $("#serviceid").selectpicker('refresh');
              $("#add-customer").show();
            }
        })
  });
</script>
@endsection


