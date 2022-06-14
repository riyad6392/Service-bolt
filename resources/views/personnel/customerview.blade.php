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
</style>
<div class="">
<div class="content">
  <div class="row">
    <div class="col-md-12">
    <a href="{{route('worker.customer')}}" class="back-btn">
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
            <img src="{{url('uploads/customer/')}}/{{$value->image}}" alt="" style="object-fit: cover;">
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
<div class="card-body p-4">
  <div class="card-box">
  <h5 class="m-0">
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
  <input type="hidden" name="customerid" id="customerid" value="{{$customerData[0]->id}}">
  <div class="col-lg-8 mb-2" style="display: block;">
   <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address" required="">
      <!-- <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button> -->
     </div>
  </div>
  <div class="col-lg-3 offset-lg-1 mb-3" style="display: block;">
    <button class="btn btn-add btn-block" type="submit">Add Address</button>
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

     
     <div class="col-lg-12 mb-2" style="display: block;">
     <input type="text" placeholder=" Address" class="form-control input-gray" readonly style="box-shadow: none;"/>
     </div>
     @foreach($customerAddress as $key=>$value)
       <div class="col-lg-12 mb-2">
         <div class="d-flex align-items-center justify-content-between ps-4 pt-2 pe-4 pb-2 address-line2 address-line">
           <div class="d-flex align-items-center"><a href="javascript:void(0);" class="info_link1" dataval="{{$value->id}}"><i class="fa fa-trash"></i></a><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="me-3"><path d="M12 18a6 6 0 100-12 6 6 0 000 12z" fill="currentColor"></path></svg> <a class="" data-bs-toggle="modal" data-bs-target="#edit-address" id="editaddress" data-id="{{$value->id}}" data-address="{{$value->address}}">{{$value->address}}</a></div>
        <!--  <button class="btn btn-save confirm">Service Ticket</button> -->
        <a class="btn btn-save confirm" data-bs-toggle="modal" data-bs-target="#create-ctickets" id="createctickets" data-id="{{$value->customerid}}" data-address="{{$value->address}}" style="display: none;">Service Ticket</a>
         </div>
       </div>
     @endforeach
  
     
     
     
  
  </div>
  </div>
</div>
</div>

     </div>

     <div class="col-md-8 mb-4">
             <div class="card">
       <div class="card-body">
      <h5 class="mb-4">Recent Tickets</h5>
    
    <div class="table-responsive">
    <div class="th-listbox customers-list">
    <ul class="list-group list-group-horizontal th-gray text-uppercase font-color mb-4 ">
  <li>Ticket number</li>
  <li>Service</li>
  <li>Total</li>
 <li></li>
</ul>
@if(count($recentTicket)>0)
@php
  $i = 1;
@endphp
@foreach($recentTicket as $key => $value)
  <ul class="list-group list-group-horizontal th-border mb-4 align-items-center list-group-active">
    <li class="text-truncate"> #{{$value['id']}} </li>
    <li> {{$value['servicename']}} </li>
    <li> ${{$value['price']}} </li>
    <li><a class="btn btn-ticket showSingle" target="{{$i}}" data-id="{{$value['customerid']}}" datat-id="{{$value['id']}}">View Ticket</a></li>
  </ul>
  @php
    $i++;
  @endphp
@endforeach
@else
<div style="text-align: center;">No Record Found</div>
@endif
</div>
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
@endsection

@section('script')
<script type="text/javascript">
	$.ajaxSetup({
      	headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	$(document).on('click','#editTickets',function(e) {
		$('.selectpicker').selectpicker();
		var id = $(this).data('id');
   		var dataString =  'id='+ id;
   		$.ajax({
            url:'{{route('worker.vieweditinvoicemodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewinvoicemodal').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
            }
        })
    });
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
          if (isConfirm) {
             $.ajax({
              url:"{{url('personnel/customer/deleteAddress')}}",
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

  $(document).on('click','#editaddress',function(e) {
   var cid = $(this).data('id');
   var address = $(this).data('address');
   $.ajax({
            url:'{{route('worker.vieweditaddressmodal')}}',
            data: {
              'cid':cid,
              'address':address,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#vieweditaddressmodaldata').html(data.html);
            }
        })
  });

  jQuery(function() {
   jQuery('.showSingle').click(function() {
        var targetid = $(this).attr('target');
        var customerid = $(this).attr('data-id');
        var ticketid = $(this).attr('datat-id');
        $.ajax({
            url:"{{url('personnel/customer/leftbarticketdata')}}",
            data: {
              targetid: targetid,
              customerid: customerid,
              ticketid: ticketid 
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
    var customerid1 = $("#customerid").val();
    $.ajax({
            url:"{{url('personnel/customer/leftbarticketdata')}}",
            data: {
              targetid: 0,
              customerid1: customerid1 
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
</script>
@endsection