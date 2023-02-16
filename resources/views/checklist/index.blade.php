@extends('layouts.header')
@section('content')
<style type="text/css">
	li.list-data {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mb-4.points-div-list ul {
    padding: 0;
}

.table-new tbody tr.selectedrow:after {
    background: #FAED61 !important;
}
</style>
<div class="">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="side-h3">
					<h3>Checklists</h3>
					<span style="color: #B0B7C3;"></span>
				</div>
			</div>
	
	<div class="col-md-8 mb-4">
		<div class="card">
			<div class="card-body">
				<div class="card-content">
					<div class="row">
					 	<div class="col-lg-6">
					 	</div>
					 	<div class="col-lg-6 time-sheet" style="text-align: right;">
							<button class="btn mb-3 btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#new-list" style="width: 66%;">Add New +</button>
					 	</div>
						<div class="col-lg-6 mb-2" style="display: none;">
							<div class="show-fillter">
								<select id="inputState" class="form-select">
									<option>Show: A to Z</option>
									<!-- <option>By Service</option>
									<option>By Frequency</option>
									<option>By Company</option> -->
								</select>
							</div>
						</div>

						<div class="col-lg-6 mb-2" style="display: none;">
							<div class="show-fillter">
								<input type="text" class="form-control" placeholder="Search"/>
								<button class="search-icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
								</button>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						@if(Session::has('success'))
							<div class="alert alert-success" id="selector">
								{{Session::get('success')}}
							</div>
						@endif
						<table id="example" class="table no-wrap table-new table-list" style="position: relative;">
							<thead>
								<tr>
									<th style="display: none;">Id</th>
									<th>Checklist Name </th>
									<th>Service Name</th>
									<th>Action</th>
					
								</tr>
							</thead>
							<tbody>
							@php
				              $i = 1;
				            @endphp
				            @if(count($checklistData)>0)
				            	<input type="hidden" name="defalutid" id="defalutid" value="{{$checklistData[0]->serviceid}}">
								@foreach($checklistData as $key => $value)
									<tr target="{{$i}}" data-id="{{$value->serviceid}}" class="user-hover showSingle">
										<td style="display: none;">{{$value->id}}</td>
										<td>{{$value->checklistname}}</td>
										<td>{{$value->servicename}}</td>
										<td>
											View
										</td>
									</tr>
							@php
				              $i++;
				            @endphp
								@endforeach
							@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 mb-4">
    	<div class="card">
       		<div class="card-body p-4">
       			<div id="viewleftservicemodal"></div>
				<!-- <div class="product-card">
					<img src="{{url('/')}}/images/product-card.jpg" alt=""/>
					<h2>Lorem ipsum</h2>

					<div class="product-info-list time-sheet">
						<div class="mb-4 points-div-list">
							<ul>
								<li>this is trial</li>
								<li>New twin for check</li>
									<li>this is trial</li>
								<li>New twin for check</li>
									<li>this is trial</li>
								<li>New twin for check</li>
							</ul>    
						</div>
						<a class="btn  w-100 p-3  btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#edit-product ">Add Point</a>
					</div>
				</div> -->
    		</div>
		</div>
    </div>
	</div>
</div>
</div>
<div class="modal fade" id="new-list" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <div class="modal-body">
      
	   <div class="add-customer-modal d-flex justify-content-between align-items-center">
	   <h5>Add a new Checklist</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
   
	  

     @if(count($serviceData)>0)
      @else
      	     		<div style="color: red;">Step1: Please add a service in the services section.</div>
     		<div style="color: red;">Step2: Then add checklist for services here.</div>

     		
     	@endif
	 <form class="form-material m-t-40  form-valide" method="post" action="{{route('company.checklistcreate')}}" enctype="multipart/form-data">
        @csrf
	   <div class="row customer-form" id="product-box-tabs">
	   	
	  <div class="col-md-12">
		  <div class="">
			  <div class="mb-3">
			  	<label>Checklist Name</label>
			   	<input type="text" name="checklistname" id="checklistname" class="form-control" placeholder="Checklist Name" required>
			  </div>
		  </div>
	  </div>

		<div class="col-md-12">
	   <div class="">
	   <div class="mb-3">
	   <select class="form-select" name="serviceid" id="serviceid" required="">
	   		<option value="">Select a Service</option>
		   	@foreach($serviceData as $key =>$value)
		   		<option value="{{$value->id}}">{{$value->servicename}}</option>
		    @endforeach
	   </select>
	   </div>
	   </div>
	   </div>

	   <div class="col-md-12 mb-3">
	  	<div class="multbox-modal">
				<select class="form-control selectpicker " multiple="" data-placeholder="Select Admin Checklist" data-live-search="true" style="width: 100%;" tabindex="-1" aria-hidden="true" name="adminck[]" id="adminck">
					@foreach($adminchecklist as $key =>$value1)
	          <option value="{{$value1->id}}">{{$value1->checklist}}</option>
	        @endforeach
	      </select>
  		</div>
	   </div>
	   <div class="col-md-12">
	   	 	<div class="row" style="align-items: center;">
			  <div class="col-md-8 dynamic-field" id="dynamic-field-1">
			    <div class="row" >
			        <div class="col-md-12">
			        <div class="staresd">
			          <div class="imgup">
			            <input type="text" placeholder="Checklist Item" class="form-control" style="margin-bottom: 5px;" name="point[]" required="">
			            <input type="file" class="form-control" style="margin-bottom: 5px;" name="checklistimage[]" accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">
					  </div>
			        </div>
			      </div>
			    </div>
			  </div>
			  <div class="col-md-4 append-buttons" style="margin-top: 5px">
			    <div class="clearfix">
			      <button type="button" id="add-button" class="btn btn-secondary float-left text-uppercase shadow-sm"><i class="fa fa-plus fa-fw"></i>
			      </button>
			      <button type="button" id="remove-button" class="btn btn-secondary float-left text-uppercase ml-1" disabled="disabled"><i class="fa fa-minus fa-fw"></i>
			      </button>
			    </div>
			  </div>
			</div>
	    </div>
	   <div class="col-lg-6 mb-3">
	   	<button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6 mb-3">
	   	<button type="submit" class="btn btn-add btn-block">Submit</button>
	   </div>
	</div>
	</form>
   </div>
  </div>
</div>
</div>



<!--edit address modal open-->
<div class="modal fade" id="edit-checklist" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.updatechecklist') }}" enctype="multipart/form-data">
          @csrf
          <div id="vieweditaddressmodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

<div class="modal fade" id="new-listupdate" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
  <div class="modal-content customer-modal-box">
  <div class="modal-body">
   <div class="add-customer-modal">
	  <h5 id="servicetitle"></h5>
	  <!-- <p class="lead">Lorem ipsum dolar si amen</p> -->
	  </div>
	 

  @if(count($serviceData)>0)
   @else
   	   		<div style="color: red;">Step1: Please add a service in the services section.</div>
  		<div style="color: red;">Step2: Then add checklist for services here.</div>

  		
  	@endif
	 <form class="form-material m-t-40 form-valide" method="post" action="{{route('company.updateallchecklist')}}" enctype="multipart/form-data">
    @csrf
    <div class="row customer-form" id="product-box-tabs">
			<div class="col-md-12">
  	 	<div class="row" style="align-items: center;">

	  		<div id="allchecklist"></div>

	  <div class="col-md-12 text-end mb-2 append-buttons" style="margin-top: 5px">
		  <div class="clearfix">
		   <button type="button" id="add-button1" class="btn btn-secondary float-left text-uppercase shadow-sm"><i class="fa fa-plus fa-fw"></i>
		   </button>
		   <button type="button" id="remove-button1" class="btn btn-secondary float-left text-uppercase ml-1" disabled="disabled"><i class="fa fa-minus fa-fw"></i>
		   </button>
		  </div>
		 </div>
		</div>
	</div>
	<div class="col-lg-6 mb-3">
	  	<button class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</button>
	</div>
	<div class="col-lg-6 mb-3">
	  	<button type="submit" class="btn btn-add btn-block">Submit</button>
	</div>
</div>
	</form>
 </div>
 </div>
</div>
</div>

@endsection
@section('script')
<script type="text/javascript">
	$.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
	$(document).ready(function() {
		var defalutid = $('#defalutid').val();
		$.ajax({
  			url:"{{url('company/checklist/leftbarchecklistdata')}}",
            data: {
              targetid: 0,
              serviceid: defalutid
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewleftservicemodal').html(data.html);
            }
        })
    
  });

  $(document).ready(function() {
  	$('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });
    $("#example tbody > tr:first-child").addClass('selectedrow');
  });
   

   

   jQuery(function() {
   	$(document).on('click','.showSingle',function(e) {
        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        $.ajax({
            url:"{{url('company/checklist/leftbarchecklistdata')}}",
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

  });

  $('html').on('click','.info_link1',function() {
      var addressid = $(this).attr('dataval');
      swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this checklist item!",
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
            url:"{{url('company/checklist/deleteChecklist')}}",
            data: {
              id: addressid 
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

  	$('html').on('click','#updatednew',function() {
  		var sid = $(this).data('sid');
  		  var sname = $(this).data('sname');
         $("#serviceidnew").empty();
         $("#servicetitle").empty();
        //$("#new-listupdate").modal('show');
        
        //$("#servicetitle").append(sname);
        //$("#serviceidnew").append("<option value="+sid+">"+sname+"</option>");

        
        $.ajax({
            url:'{{route('company.vieweditallchecklistmodal')}}',
            data: {
              'sid':sid,
              'sname':sname,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#allchecklist').html(data.html);
              $("#new-listupdate").modal('show')
            }
        })
    });


    $('html').on('click','#editchecklist',function() {
      var cid = $(this).data('id');
      var cname = $(this).data('name');
        $.ajax({
            url:'{{route('company.vieweditchecklistmodal')}}',
            data: {
              'cid':cid,
              'cname':cname,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#vieweditaddressmodaldata').html(data.html);
            }
        })
    });

$('#selector').delay(2000).fadeOut('slow');
$('table tr').each(function(a,b) {
    $(b).click(function() {
         $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
    });
 })
</script>
@endsection