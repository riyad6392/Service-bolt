@extends('layouts.header')
@section('content')
<style type="text/css">
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
</style>

<div class="bland-service-page">
<div class="content">
     <div class="row">
      	<div class="col-md-12">
	        <div class="side-h3">
	       		<h3>Products</h3>
	      	</div>
 		</div>
 @if(count($inventoryData)>0)
	<div class="col-md-4 mb-4">
       <div class="card">
	<div class="card-body p-4">
	<div id="viewleftservicemodal"></div>
 </div>
 </div>
</div >
@endif
@php
	if(count($inventoryData)>0) {
		$class = "col-md-8 mb-4";
	} else {
		$class = "col-md-12 mb-4";
	}
@endphp
<div class="{{$class}}">
<div class="card">
	   <div class="card-body">
	   <h5 class="mb-4 d-flex align-items-center justify-content-between">Products <a href="#"  data-bs-toggle="modal" data-bs-target="#add-product " class="add-btn-yellow">
	   Add Product +
	   </a></h5>
	   <div class="row">

	   
	   <div class="col-lg-9 mb-2">
	   	@if(Session::has('success'))
			<div class="alert alert-success" id="selector">
		    	{{Session::get('success')}}
			</div>
		@endif
	<!--    <div class="show-fillter" style="visibility: hidden;">
	   <input type="text" class="form-control" placeholder="Search Product"/>
	   <button class="search-icon">
	   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
	   </button>
	   </div> -->
	   
	   </div>
	   
	  <!--  <div class="col-lg-3 text-center mb-2">
	   
	   </div> -->
	   
	   </div>
	   	@php
	       $pagedata = App\Models\Managefield::select('*')
	      ->where('page','companyproduct')->where('userid',$auth_id)->get();
	     	$cpagedta = count($pagedata);
	    @endphp
	   <div class="col-lg-12 mt-2">
	   <div class="table-responsive">
  			<table id="example" class="table no-wrap table-new table-list align-items-center">
			  <thead>
			  <tr>
				  <th style="display: none;">Id</th>
				  @if($cpagedta==0)
			  	  <th>Sku</th>
				  <th>Product Name</th>
				  @endif
				  <th>Status</th>
				  @if($cpagedta==0)
				  <th>Quantity</th>
				  <th>Price</th>
				  <th>Category</th>
				  @endif
				 @if($cpagedta!=0)
				  @foreach($pagedata as $key => $pagecolumn)
	                  @if($pagecolumn->columname=="sku")
	                    <th>Sku</th>
	                  @endif
	                  @if($pagecolumn->columname=="productname")
	                    <th>Product Name</th>
	                  @endif
	                  @if($pagecolumn->columname=="quantity")
	                    <th>Quantity</th>
	                  @endif
	                  @if($pagecolumn->columname=="price")
	                    <th>Price</th>
	                  @endif
	                  @if($pagecolumn->columname=="category")
	                    <th>Category</th>
	                  @endif
                  @endforeach
                @endif
			<div class="heading-dot pull-right" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display:none;">    
				<i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
			</div>
			</tr>
			  </thead>
			  <tbody>

			  	@php
                  $i = 1;
                @endphp
                @foreach($inventoryData as $inventory)
                  	@php
			          $fifypercent = $inventory->pquantity*50/100;
			          $twentyfivepercent = $inventory->pquantity*25/100;
			       	@endphp
				  <tr target="{{$i}}" data-id="{{$inventory->id}}" class="user-hover showSingle" data-bs-toggle="modal" data-bs-target="#edit-product" id="editProduct">
				  	  <td style="display: none;">{{$inventory->id}}</td>
				  	@if($cpagedta==0)
					  <td>{{$inventory->sku}}</td>
					  <td>{{$inventory->productname}}</td>
					@endif
					  <td>
					  	@if($inventory->quantity>=$fifypercent)
					  		Good
					  	@elseif($inventory->quantity>=$twentyfivepercent)
					  	 Low
					  	@elseif($inventory->quantity<=$twentyfivepercent)
					  	 Needs to be restocked	
					  	@endif
					  </td>
					@if($cpagedta==0)
					  <td>{{$inventory->quantity}}</td>
					  <td>${{$inventory->price}}</td>
					  <td>@if($inventory->category!=null){{$inventory->category}} @else - @endif</td>
					@endif

					@if($cpagedta!=0)
						@foreach($pagedata as $key => $pagecolumn)
	                  		@if($pagecolumn->columname=="sku")
	                  			<td>{{$inventory->sku}}</td>
	                  		@endif
	                  		@if($pagecolumn->columname=="productname")
	                  			<td>{{$inventory->productname}}</td>
	                  		@endif
	                  		@if($pagecolumn->columname=="quantity")
	                  			<td>{{$inventory->quantity}}</td>
	                  		@endif
	                  		@if($pagecolumn->columname=="price")
	                  			<td>{{$inventory->price}}</td>
	                  		@endif
	                  		@if($pagecolumn->columname=="category")
	                  			<td>{{$inventory->category}}</td>
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
<!--------------------edit --modal----------------------->
<div class="modal fade" id="edit-product" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       	<form method="post" action="{{ route('company.inventoryupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata"></div>
      </form>
     </div>
  </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="add-product" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
     <form class="form-material m-t-40 form-valide" method="post" action="{{route('company.inventorycreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
       <div class="add-customer-modal">
	  
     <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add a new Product/Part</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	   
	   </div>
	   
	   <div class="tabs-product row mb-4">
	   <div class="col-lg-5">
	   <button class="btn btn-product information-tabs" id="" type="button">Information</button>
	   </div>
	   <div class="col-lg-7">
	   <button class="btn btn-desc description-product" id="" type="button">Description and Images</button>
	   </div>
	   </div>
	   
	   
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
	   
	   
	   
	   <!-- <div class="col-md-12 mb-3 text-end">
	   <a id="Array_name" class="add_fields">+ Add more services</a>
	   </div> -->
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
	   	<i class="fa fa-dollar" style="position: absolute;top:406px;left: 35px;"></i>
	  	<input type="text" class="form-control" placeholder="Price" name="price" id="price" required="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
	   </div>
	   
	   <div class="col-md-11 mb-5">
	  	<select class="form-select" name="category" id="category">
	  		<option value="">Select Category</option>
			@foreach($categoryData as $key=>$value)
				<option value="{{$value->category_name}}">{{$value->category_name}}</option>
			@endforeach
		</select>
    	<a href="#" data-bs-toggle="modal" data-bs-target="#add-category" class="in-plus"><i class="fa fa-plus yellow-icon"></i></a>
	   </div>
	   <div class="col-lg-6 ">
	   <button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6">
	   <button class="btn btn-add btn-block description-product">Next</button>
	   </div>
	   
	   </div>
	   
	   <div class="row customer-form" id="product-desc-tabs" style="display:none;">
	    <div class="col-lg-12 mb-3">
		<textarea class="form-control height-180" name="description" id="description" required="" placeholder="Description"></textarea>
		</div>
	   <div class="col-md-12">
	   	<div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
	    <input type="file" class="dropify" name="image" id="image" data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
	   </div>
	   
	   <div class="row mt-3"><div class="col-lg-6 mb-3">
	   	<button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6 mb-3">
	   	<button class="btn btn-add btn-block" type="submit">Complete</button>
	   </div></div>
	  </div>
     </div>
 </form>
  </div>
</div>
</div>



<div class="modal fade" id="duplicate-Product" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
     <form class="form-material m-t-40 form-valide" method="post" action="{{route('company.inventorycreate')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="duplicate" id="duplicate" value="duplicate">
        <div id="viewmodalduplicatedata"></div>
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
      	<div style="width: 100%; text-align: center;"> Inventory Field Name</div>
      	
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
                ->where('page','companyproduct')->where('columname',$value)->where('userid',$auth_id)->first();
                
               @endphp
                @if($value=="sku" || $value=="productname" || $value=="quantity" || $value=="price" || $value=="category")
           			<li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata->columname) == $value ? 'checked' : '' }}>{{product_filter($value)}} </label></li>
                  
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
<div class="modal fade" id="add-category" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <!-- <form class="form-material m-t-40  form-valide" method="post" action="{{route('company.catcreate')}}" enctype="multipart/form-data">
        @csrf -->
      <div class="modal-body">
       <div class="add-customer-modal">
     
     <div class="add-customer-modal d-flex justify-content-between align-items-center">
     <h5>Add a new category</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     
     
     <div class="row customer-form">
      
     <div class="col-md-12 mb-3">
     
     <input type="text" class="form-control" placeholder="Category Name" name="category_name" id="category_name" required="">
  
     </div>
     
     <div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
     <button id="savecat" class="btn btn-add btn-block">Save</button>
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

  jQuery(function() {
   $(document).on('click','.showSingle',function(e) {
        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        showeditview(serviceid);
        $.ajax({
            url:"{{url('company/inventory/leftbarinventorydata')}}",
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

    $.ajax({
            url:"{{url('company/inventory/leftbarinventorydata')}}",
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
$(document).on('click','#editProduct',function(e) {
  $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.editviewinventorymodal')}}',
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

	function showeditview(id) {
		var id = $(this).data('id');
   		var dataString =  'id='+ id;
   		$.ajax({
            url:'{{route('company.editviewinventorymodal')}}',
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
	}

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

 $('table tr').each(function(a,b) {
    $(b).click(function() {
         $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
    });
  });

 $('html').on('click','.info_link1',function() {
        var productid = $(this).attr('dataval');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this Product!",
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
                url:"{{route('company.deleteProduct')}}",
                data: {
                  id: productid 
                },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Product deleted succesfully!","success");
                location.reload();
                }
            })
          } 
          else {
            location.reload(); //swal("Cancelled", "Your customer is safe :)", "error");
          }
        }
      );
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
      data.append("page", "companyproduct");
      // disabled the submit button
      $("#btnSubmit").prop("disabled", true);
      $.ajax({
        url:'{{route('company.savefieldproduct')}}',
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

	$(document).on('click','#savecat',function(e) {
	   var category_name = $('#category_name').val();
	   if(category_name=="") {
	   	alert('category name field is required');
	   }
	   $.ajax({
            url:"{{url('company/inventory/catcreate')}}",
            data: {
              category_name: category_name
     		},
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $("#add-category").modal('hide');
              $("#category").append("<option value="+data.category_name+">"+data.category_name+"</option>");
            }
        })
	})

	$('html').on('click','.info_link3',function() {
        var productid = $(this).attr('dataval');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to duplicate this Product!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, duplicate it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
           $.ajax({
                url:"{{route('company.duplicateproduct')}}",
                data: {
                  id: productid 
                },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Product duplicate succesfully!","success");
                location.reload();
                }
            })
          } 
          else {
            location.reload(); //swal("Cancelled", "Your customer is safe :)", "error");
          }
        }
      );
    });

	$(document).on('click','#duplicateProduct',function(e) {
	  $('.selectpicker').selectpicker();
	   var id = $(this).data('id');
	   var dataString =  'id='+ id;
	   $.ajax({
            url:'{{route('company.duplicateproduct')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
            	location.reload();
              // $('#viewmodalduplicatedata').html(data.html);
              // $('.dropify').dropify();
              // $('.selectpicker').selectpicker({
              //   size: 3
              // });
            }
	    })
  	});
</script>
@endsection