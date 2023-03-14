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
<div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
          <h3>Services</h3>
        </div>
      </div>
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
                @if(Session::has('success'))

                    <div class="alert alert-success" id="selector">

                        {{Session::get('success')}}

                    </div>

                @endif
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
                      <div class="heading-dot pull-right" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display: none;">    
                        <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
                      </div>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $i = 1;
                    @endphp
                    @foreach($serviceData as $service)
                    <tr class="user-hover showSingle" target="{{$i}}" data-id="{{$service->id}}">
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
      <form class="form-material m-t-40 form-valide" method="post" action="{{route('worker.servicecreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
        <div class="add-customer-modal">
          <h5>Add a new Service</h5>
        </div>
        @php
        if(count($productData)>0){
            $pname= "";
        } else {
            $pname= "active-focus";
        }
        
        @endphp
        <div class="row customer-form" id="product-box-tabs">
          <div class="col-md-12 mb-2">
            <input type="text" class="form-control" placeholder="Service Name" name="servicename" id="servicename" required="">
          </div>
          <div class="col-md-12 mb-2">
            <input type="number" class="form-control" placeholder="Service Default Price" name="price" id="price" required="">
          </div>
          <div class="col-md-12 mb-3">
            <select class="selectpicker form-control" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Select Products" name="defaultproduct[]" id="defaultproduct">
            @foreach ($productData as $product)
                  <option value="{{$product->id}}">{{$product->productname}}</option>
                @endforeach
            </select>
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
            <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div></div>
          <div class="col-md-12 mb-2">
            <label>Choose Color</label><br>
            <span class="color-picker">
              <label for="colorPicker">
                <input type="color" value="#faed61" id="colorPicker" name="colorcode" style="width:235px;">
              </label>
            </span>
          </div>
          <div class="col-lg-12 mb-2">
            <textarea class="form-control height-180" name="description" id="description" placeholder="Description" required></textarea>
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

<div class="modal fade" id="create-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
        <form method="post" action="{{ route('worker.servicecreatequote') }}" enctype="multipart/form-data">
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
      <form method="post" action="{{ route('worker.serviceupdate') }}" enctype="multipart/form-data">
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
@endsection

@section('script')
<script type="text/javascript">
  $('.dropify').dropify();
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });
    $("#example tbody > tr:first-child").addClass('selectedrow');
    $("#time").val('1');
    $("#minute").val('0');
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
  //$('.selectpicker').selectpicker();
  $('.selectpicker').selectpicker({
    size: 3
  });
  jQuery(function() {
   $(document).on('click','.showSingle',function(e) {
        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        $.ajax({
            url:"{{url('personnel/manageservices/leftbarservicedata')}}",
            data: {
              targetid: targetid,
              serviceid: serviceid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewleftservicemodal').html(data.html);
            }
        })
    });

    $.ajax({
            url:"{{url('personnel/manageservices/leftbarservicedata')}}",
            data: {
              targetid: 0,
              serviceid: 0 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewleftservicemodal').html(data.html);
            }
        })

  });
$(document).on('click','#editService',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('worker.viewservicemodal')}}',
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
            url:'{{route('worker.viewquotemodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
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
        $.ajax({
          url:"{{url('personnel/myticket/getaddressbyid')}}",
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
        url:'{{route('worker.savefieldservice')}}',
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
  });
</script>
@endsection


