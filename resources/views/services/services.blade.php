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
    background: #FAED61 !important;
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
.bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
    position: absolute;
    display: inline-block;
    right: 15px;
    top: 5px;
    color: black;
}

i.fa.fa-plus.new-services {
    position: relative;
    top: -40px;
    left: 400px;
}

i.fa.fa-plus.true-condition {
    position: absolute;
    top: 185px;
    color: black;
    background: yellow;
    padding: 7px 9px;
    border-radius: 100px;
    right: 25px;
}

</style>
<div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
          <h3>Services</h3>
        </div>
      </div>
      @if(Session::has('success'))

                    <div class="alert alert-success" id="selector">

                        {{Session::get('success')}}

                    </div>

                @endif
      @if(count($serviceData)>0)
      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body p-4">
            <div>
              <div id="viewleftservicemodal"></div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @php
        if(count($serviceData)>0) {
          $class = "col-md-8 mb-4";
        } else {
          $class = "col-md-12 mb-4";
        }
      @endphp
      <div class="{{$class}}">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-9 mb-2">
                
                <h5 class="mb-4">Your Services</h5>
                <div class="show-fillter" style="display: none;">
                  <input type="text" class="form-control" placeholder="Search Services" />
                  <button class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                      <path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="col-lg-3  text-center mb-2"> <a href="#" data-bs-toggle="modal" data-bs-target="#add-services" class="add-btn-yellow">
     Add Service +
     </a>
              </div>
            </div>
            @php
              $pagedata = App\Models\Managefield::select('*')
              ->where('page','companyservice')->where('userid',$auth_id)->get();
              $cpagedta = count($pagedata);
            @endphp
            <div class="col-lg-12 mt-2">
              <div class="table-responsive">
                <table id="example" class="table no-wrap table-new table-list align-items-center">
                  <thead>
                    <tr>
                      <th style="display: none;">Id</th>
                      
                      <th></th>
                      @if($cpagedta==0)
                      <th>Service Name</th>

                      <th>Price</th>
                      <th>Frequency</th>
                      <th>Default Time</th>
                      @else
                        @foreach($pagedata as $key => $pagecolumn)
                          @if($pagecolumn->columname=="servicename")
                            <th>Service Name</th>
                          @endif
                          @if($pagecolumn->columname=="price")
                            <th>Price</th>
                          @endif
                          @if($pagecolumn->columname=="frequency")
                            <th>Frequency</th>
                          @endif
                          @if($pagecolumn->columname=="time")
                            <th>Default Time</th>
                          @endif
                        @endforeach
                      @endif
                      <div class="heading-dot pull-right" data-bs-toggle="modal" data-bs-target="#exampleModal">    
                        <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
                      </div>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $i = 1;
                    @endphp
                    @foreach($serviceData as $service)
                    <tr class="user-hover showSingle" target="{{$i}}" data-id="{{$service->id}}" data-bs-toggle="modal" data-bs-target="#edit-services" id="editService">
                      <td style="display: none;">{{$service->id}}</td>
                      <td><div class="user-img me-3" style="background: {{$service->color}};border-radius:48px;width: 20px;height: 20px;"></div></td>
                    @if($cpagedta==0)  
                      <td>{{$service->servicename}}</td>
                      
                      <td>${{$service->price}}</td>
                      <td>{{$service->frequency}}</td>
                      <td>@if($service->time!=0 || $service->time!=null){{$service->time}}
                      @endif
                      @if($service->minute!=0 || $service->minute!=null){{$service->minute}}
                      @endif</td>
                    @else
                      @foreach($pagedata as $key => $pagecolumn)
                        @if($pagecolumn->columname=="servicename")
                          <td>{{$service->servicename}}</td>
                        @endif
                        @if($pagecolumn->columname=="price")
                          <td>{{$service->price}}</td>
                        @endif
                        @if($pagecolumn->columname=="frequency")
                          <td>{{$service->frequency}}</td>
                        @endif
                        @if($pagecolumn->columname=="time")
                          <td>
                            @if($service->time!=0 || $service->time!=null){{$service->time}}
                           @endif
                           @if($service->minute!=0 || $service->minute!=null){{$service->minute}}
                           @endif
                          </td>
                        @endif
                      @endforeach
                    @endif
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
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="add-services" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
      <form class="form-material m-t-40 form-valide" method="post" action="{{route('company.servicecreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
        
        <div class="add-customer-modal d-flex justify-content-between align-items-center">
        <h5>Add a new Service</h5>
     <button class="btn-close" type="button" onclick="refreshPage()"></button>
          
        </div>
        @php
        if(count($productData)>0) {
            $pname= "";
        } else {
            $pname= "active-focus";
        }
        
        @endphp
        <div class="row customer-form" id="product-box-tabs">
          <div class="col-md-11 mb-2">
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
                <input type="radio" id="test1" name="radiogroup" value="perhour" checked>
                <label for="test1">Per Hour</label>
              </p>
              <p>
                <input type="radio" id="test2" name="radiogroup" value="flatrate">
                <label for="test2">Flate Rate</label>
              </p>
              <p>
                <input type="radio" id="test3" name="radiogroup" value="recurring">
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
              <!-- <option name="Weekly" value="Weekly">Weekly</option>
              <option name="Be weekly" value="Be weekly">Bi-Weekly</option>
              <option name="Monthly" value="Monthly">Monthly</option> -->
            </select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Service Time</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
              <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
              <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
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
          <div class="col-md-11 mb-2" style="display: none;">
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
            <button class="btn btn-cancel btn-block" type="button" onclick="refreshPage()">Cancel</button>
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

<div class="modal fade" id="create-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
        <form method="post" action="{{ route('company.servicecreatequote') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewquotemodaldata"></div>
      </form>
      </div>
  </div>
</div>
</div>

<!----------------------edit form------------>
<div class="modal fade" id="edit-services" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('company.serviceupdate') }}" enctype="multipart/form-data">
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
        <div style="width: 100%; text-align: center;"> Service Field Name</div>
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
                ->where('page','companyservice')->where('columname',$value)->where('userid',$auth_id)->first();
                
               @endphp
                @if($value=="servicename" || $value=="price" || $value=="frequency" || $value=="time")
                <li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata->columname) == $value ? 'checked' : '' }}>{{service_filter($value)}} </label></li>
                  
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

<!-- Add customer modal -->
<div class="modal fade" id="add-customer" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
     <form class="form-material m-t-40  form-valide" id="createserviceticket" method="post"  enctype="multipart/form-data">
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
     
     <div class="col-md-6 mb-3">
     
     <input type="text" class="form-control" placeholder="Phone Number" name="phonenumber" id="phonenumber" required="">
     
     </div>
     
     <div class="col-md-6 mb-3">
    
     <input type="email" class="form-control" placeholder="Email" name="email" id="email" required="">
     
     </div>
     
     <div class="col-md-12 mb-3">
    
     <input type="text" class="form-control" placeholder="Company Name" name="companyname" id="companyname" required="">
     
     </div>
     <div class="col-md-11 mb-3">
      <div class="d-flex align-items-center">
        <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid">
          @php $services = App\Models\Service::select('id','servicename')->where('userid', auth()->user()->id)->get(); @endphp
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
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal" id="quotecancel">Cancel</button>
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
<!-- end modAL -->
<!-- Add address modal -->
<div class="modal fade" id="add-address" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
      <div class="modal-body">
      <div class="add-customer-modal d-flex justify-content-between align-items-center">
	 <h5>Add Address</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" id="quotecancel1" aria-label="Close"></button>
     </div>
     
    <div class="row customer-form">
     
    <div class="col-md-12 mb-3">
     	<input type="text" class="form-control" placeholder="Addresses" name="address" id="address5" required="">
		 <div class="find_msg" style="display:none;"></div>
  	</div>
  	</div>

		<div class="col-md-12 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal" id="quotecancel1">Cancel</button>
    </div>
    <div class="col-md-12 mb-3">
     	<button id="saveaddress" class="btn btn-add btn-block">Add Address</button>
    </div>
    </div>
    </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
  $('.dropify').dropify();
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });
    $("#example tbody > tr:first-child").addClass('selectedrow');
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $(document).on('keyup','#address5',function(e) {
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
				console.log(data);
				if(data.status=='success') {
				$("#saveaddress").attr("disabled", true);
				$(".find_msg").html(data.msg);
				$('.find_msg').css('color','red');
				$('.find_msg').css('display','block');
				
             // $("#add-address").modal('hide');
              //$("#address1").append("<option value="+data.address+">"+data.address+"</option>");
             // $("#add-tickets").show();
				}
				else {
					//alert('fdgnk');
					$("#saveaddress").attr("disabled", false);
					$('.find_msg').css('display','none');
				}
            }
        })
    });

  jQuery(function() {
   $(document).on('click','.showSingle',function(e) {

        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        showeditview(serviceid);
        $.ajax({
            url:"{{url('company/services/leftbarservicedata')}}",
            data: {
              targetid: targetid,
              serviceid: serviceid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
            }
        })
    });

    function showeditview(id) {
      var id = id;
      var dataString =  'id='+ id;
      $.ajax({
          url:'{{route('company.viewservicemodal')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#viewmodaldata').html(data.html);
            $('.dropify').dropify();
            $('.selectpicker').selectpicker();
          }
      })
    }

    $.ajax({
            url:"{{url('company/services/leftbarservicedata')}}",
            data: {
              targetid: 0,
              serviceid: 0 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
            }
        })

  });
$(document).on('click','#editService',function(e) {
  $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewservicemodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewmodaldata').html(data.html);
              $('.dropify').dropify();
              $('.selectpicker').selectpicker();
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
              console.log(data.html);
              $('#viewquotemodaldata').html(data.html);
            }
        })
  });
  $('#selector').delay(2000).fadeOut('slow');

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
   $('html').on('change','#customerid_service',function() {
      var customerid = this.value;
      $("#address_service").html('');
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
          $('#address_service').html('<option value="">Select Customer Address</option>'); 
            $.each(result.address,function(key,value) {
              $("#address_service").append('<option value="'+value.address+'">'+value.address+'</option>');
            });
            $('#addressicon').html('<div class="d-flex align-items-center justify-content-end pe-3 mt-3"><a href="#"  data-bs-toggle="modal" data-bs-target="#add-address" id="hidequote1" class=""><i class="fa fa-plus true-condition"></i></a></div>');
          }
          
      });
    });    
  });
  $('table tr').each(function(a,b) {
    $(b).click(function() {
         $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
    });
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
      data.append("page", "companyservice");
      // disabled the submit button
      $("#btnSubmit").prop("disabled", true);
      $.ajax({
        url:'{{route('company.savefieldservice')}}',
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

  
  $('#createserviceticket').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('company/services/create-service-ticket')}}";
       $.ajax({
            url:url,
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {
              console.log(data);
              $("#add-customer").modal('hide');
              $("#customerid_service").append("<option value="+data.id+">"+data.customername+"</option>");
              //$("#customerid_service").selectpicker('refresh');
              $("#create-tickets").show();
            }
        })
  });


  $('#createserviceticket').on('submit', function(event) {
      event.preventDefault();
      var url = "{{url('company/services/create-service-address')}}";
       $.ajax({
            url:url,
            data: new FormData(this),
            method: 'POST',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {
              console.log(data);
              $("#add-customer").modal('hide');
              $("#customerid_service").append("<option value="+data.id+">"+data.customername+"</option>");
              //$("#customerid_service").selectpicker('refresh');
              $("#create-tickets").show();
            }
        })
  });

  $(document).on('click','#saveaddress',function(e) {
      var customerid = $('#customerid_service').val();
	    var address = $('#address5').val();
		  if(address=="") {
		   	alert('address field is required');
        return false;
		   }
	   $.ajax({
            url:"{{url('company/services/create-service-address')}}",
            data: {
              address: address,
              customerid: customerid,
     		},
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
            	
              $("#add-address").modal('hide');
              $("#address_service").append("<option value="+data.address+">"+data.address+"</option>");
              $("#add-tickets").show();
            }
        })
	})


  $('html').on('click','#hidequote1',function() {
  	$("#create-tickets").hide();
	});

  $('html').on('click','#quotecancel1',function() {
    
    $("#add-address").hide();
  	$("#create-tickets").show();
	});

  function refreshPage() {
    window.location.reload();
  } 

</script>
@endsection


