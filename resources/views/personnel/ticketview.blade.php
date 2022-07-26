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
.note-popover.popover {
  display: none;
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <a href="{{url()->previous()}}" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
     Back</a>
        <div class="side-h3">
       <h3 class="align-items-center d-flex justify-content-between">Ticket View 
         
     <div>

     <a href="tel:{{$quoteData->phonenumber}}" class="btn btn-dark py-2 px-5">
     <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" class="me-2">
<path d="M15.3625 2.63508C11.8473 -0.879104 6.14878 -0.87826 2.63459 2.63701C-0.879592 6.15229 -0.878748 11.8508 2.63653 15.3649C6.1518 18.8791 11.8503 18.8783 15.3645 15.363C17.0522 13.6748 18.0001 11.3852 17.9995 8.99808C17.999 6.61132 17.0505 4.32251 15.3625 2.63508ZM13.6297 12.5369C13.6293 12.5373 13.6289 12.5377 13.6285 12.5381V12.5351L13.1725 12.9881C12.5828 13.5853 11.7239 13.831 10.9075 13.6361C10.085 13.416 9.30314 13.0655 8.5915 12.5981C7.93035 12.1756 7.31765 11.6816 6.7645 11.1251C6.25555 10.6199 5.7983 10.0651 5.39949 9.4691C4.96327 8.82778 4.618 8.12916 4.37349 7.39309C4.09319 6.52839 4.32547 5.57956 4.9735 4.9421L5.50749 4.40811C5.65596 4.25898 5.8972 4.25845 6.0463 4.40692C6.04668 4.40731 6.0471 4.40769 6.04749 4.40811L7.73348 6.0941C7.88261 6.24257 7.88314 6.48381 7.73467 6.63291C7.73429 6.6333 7.7339 6.63368 7.73348 6.6341L6.74348 7.6241C6.45942 7.90507 6.4237 8.35156 6.65949 8.67412C7.01756 9.16553 7.4138 9.62794 7.8445 10.0571C8.3247 10.5394 8.84674 10.9781 9.40449 11.3681C9.72677 11.5929 10.1637 11.555 10.4425 11.2781L11.3995 10.3061C11.5479 10.157 11.7892 10.1565 11.9383 10.3049C11.9387 10.3053 11.939 10.3057 11.9395 10.3061L13.6285 11.9981C13.7776 12.1466 13.7782 12.3878 13.6297 12.5369Z" fill="currentColor"/>
</svg>
 Call Customer</a>
     <a href="{{url('personnel/myticket/map/')}}/{{$quoteData->id}}" class="btn btn-dark py-2 px-5">
     <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" class="me-2">
<g clip-path="url(#clip0)">
<path d="M17.7332 8.36324L9.63322 0.26325C9.28222 -0.0877499 8.7107 -0.0877499 8.36422 0.26325L0.264226 8.36324C-0.0867734 8.71424 -0.0867734 9.28576 0.264226 9.63676L8.36422 17.7322V17.7367C8.71522 18.0877 9.28673 18.0877 9.63773 17.7367L17.7377 9.63676C18.0887 9.28124 18.0887 8.71424 17.7332 8.36324ZM10.7987 11.2477V8.99774H7.19871V11.6977H5.39869V8.09776C5.39869 7.59826 5.79918 7.19777 6.29868 7.19777H10.7987V4.94775L13.9487 8.09776L10.7987 11.2477Z" fill="currentColor"/>
</g>
<defs>
<clipPath id="clip0">
<rect width="18" height="18" fill="currentColor"/>
</clipPath>
</defs>
</svg>
Map / Directions</a>
     </div>
     </h3>
     <p>
       @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
     </p>
     </div>
  </div>
 <form id="forminout" method="post" class="row" action="{{ route('worker.myticketupdate1') }}" enctype="multipart/form-data">
        @csrf
  <div class="col-md-7 offset-md-2 ms-auto">
   
   
  <input type="hidden" name="ticketid" value="{{$quoteData->id}}">
 
  @if($quoteData->ticket_status=="2")
   <div class="row w-75" style="position: relative;left: 130px;">
   <div class="col-lg-6 pick_btn">
      <button type="submit" class="btn add-btn-yellow mb-4 w-100" name="Pickup" value="Pickup">Pickup</button>
    </div>
    <div class="col-lg-6">
      <!-- <button type="submit" class="btn add-btn-yellow mb-4 w-100" name="closeout" value="closeout" style="pointer-events:none;">Close Out</button> -->
      <input type="button" class="btn add-btn-yellow mb-4 w-100" name="closeout" value="closeout" style="pointer-events:none;">
    </div>
    </div>
  @endif
  @if($quoteData->ticket_status=="4")
  <div class="row w-75" style="position: relative;left: 130px;">
   <div class="col-lg-6 pick_btn">
       <button type="submit" class="btn add-btn-yellow mb-4 w-100" name="picked" value="picked" style="pointer-events:none;">Picked</button>
     </div>
      <div class="col-lg-6 pick_btn">
        <input type="button" id="buttonin" class="btn add-btn-yellow mb-4 w-100" name="closeout" value="closeout">
        <input type="hidden" name="closeout" value="" id="closeout">
      <!-- <button type="submit" class="btn add-btn-yellow mb-4 w-100" name="closeout" value="closeout">Close Out</button> -->
    </div>
  </div>
  @endif
  @if($quoteData->ticket_status=="3")
  <div class="row w-75" style="position: relative;left: 130px;">
   <div class="col-lg-6 pick_btn">
       <button type="submit" class="btn add-btn-yellow mb-4 w-100" name="completed" value="completed" style="pointer-events:none;">Completed</button>
     </div>
      <div class="col-lg-6">
       <button type="submit" class="btn add-btn-yellow mb-4 w-100" name="unclose" value="unclose" style="">Unclose</button>
     </div>
  </div>
  @endif
  
 <!-- </form> -->
  </div>
  
<!-- <form name="myForm" method="post" action="{{route('worker.ticketupdate123')}}" class="row">
  @csrf -->
  <input type="hidden" name="quoteid" id="quoteid" value="{{$quoteData->id}}">
<div class="col-lg-6 mb-3">
<div class="card overflow-hidden">
<div class="card-header card-ticket-header py-3">
#{{$quoteData->id}} <span class="ms-2">
@php
  $datef = date('F d, Y', strtotime($quoteData->etc));
@endphp
  {{$quoteData->givendate}} - {{$quoteData->giventime}}</span>
</div>
<div class="card-body">
<div class="card-content">
<h5 class="mb-4 text-center">Ticket Details</h5>
<h4 class="mb-3">{{$quoteData->customername}}</h4>
<div class="text-lead mb-3">
  <p>{{$quoteData->address}}</p>

  <div class="row" style="background: #eff3f5;padding: 10px;border-radius: 6px;">
    <div class="col-md-6">
      <div>
        <p>Start Time: {{$quoteData->giventime}}</p>
      </div>
    </div>
    @if($quoteData->givenendtime!="")
    <div class="col-md-6">
      <div>
        <p>End Time: {{$quoteData->givenendtime}}</p>
      </div>
    </div>
    @endif
    <div class="col-md-6">
      <div>
        <p>Price: ${{$totalprice}}</p>
      </div>
    </div>
    
    <div class="col-md-6">
      <div>
<p>{{$servicename}}</p></div></div>
<div class="col-md-6">
  <div>
<p>{{@$productname}}</p></div></div>

</div>

<p>{{$quoteData->description}}</p>
@if(count($checklistData)>0)
<label>Service Checklist</label>
@php
  $ckcount = count($checklistData);
@endphp
<input type="hidden" name="checklistcount" id="checklistcount" value="{{$ckcount}}">
  @foreach($checklistData as $key =>$value)
    @php
      $cheklist =explode(",", $quoteData->checklist); 
        if(in_array($value->id, $cheklist)) {
            $checkeds = "checked";
        }
       else {
        $checkeds = "";
      }
    @endphp
    <label class="container-checkbox me-3" style="color:gray;">{{$value->checklist}}
      <input type="checkbox" name="pointckbox[]" id="pointckbox" value="{{$value->id}}" {{$checkeds}}> <span class="checkmark"></span>
    </label>
  @endforeach
@endif
</div>
<div class="row">
<!-- <div class="col-lg-6 mb-3">
<a href="javascript:void(0)" class="btn btn-personnal w-100" onClick="setFocus()">Customer Notes</a>
</div> -->
<div class="col-lg-12 mb-3">
<a class="btn add-btn-yellow w-100" data-bs-toggle="modal" data-bs-target="#add-tickets" id="editTickets" data-id="{{$quoteData->id}}" data-price="{{$totalprice}}">Create Invoice</a>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="col-lg-6 mb-3">
<div class="card">
<div class="card-body">
<div class="card-content">
<h5 class="mb-4">Customer Notes</h5>
<!-- <textarea name="cnotes" class="form-control customer-height mb-4" placeholder="Customer Notes" style="height: 280px;color: gray;">{{$quoteData->customernotes}}</textarea> -->
<div class="mb-3">
<textarea class="summernote form-control customer-height mb-4" name="cnotes" name="description" placeholder="Customer Notes">{{$quoteData->customernotes}}</textarea>
</div>
<div class="row">

<div class="col-lg-6 mb-3">
<button type="submit" class="add-btn-yellow w-100" style="text-align: center;border:0;">
@if($quoteData->customernotes!=null)
Update
@else
Save
@endif
</button>
</div>
<div class="col-lg-6 mb-3">
@if($quoteData->frequency == "One Time")
  <a href="javascript:void(0)" class="btn add-btn-yellow w-100" data-bs-toggle="modal" data-bs-target="#ticket-popup" id="ticketpopup" data-id="{{$quoteData->id}}">Schedule Next Appt.</a>
@else 
  <a href="javascript:void(0)" class="btn add-btn-yellow w-100" data-bs-toggle="modal" data-bs-target="#ticket-popup" id="ticketpopup" data-id="{{$quoteData->id}}" style="pointer-events: none;background:#fee2002e;">Schedule Next Appt.</a>
@endif
</div>

</div>
</div>
</div>
</div>
</div>
</form>    
     
     <div class="col-md-12">
       <!-- Completed ticket section start -->
<div class="card mb-3 h-auto">
<div class="card-body">
  <div class="row align-items-center mb-3">
    <div>
      <h5>Previous Tickets</h5>
    </div>
  </div>
  <div class="table-responsive">
    <table id="example2" class="table no-wrap table-new table-list align-items-center">
    <thead>
    <tr>
    <th>Ticket number</th>
    <th>Frequency</th>
    <th>Price</th>
    <th>Service Name</th>
    </tr>
    </thead>
    <tbody>
      @php
      $i = 1;
    @endphp
      @foreach($prequoteData as $ticket)
    <tr>
    <td><a href="{{url('personnel/myticket/view/')}}/{{$ticket->id}}">#{{$ticket->id}}</a></td>
    <td>{{$ticket->frequency}}</td>
    <td>${{$ticket->price}}</td>
    <td>{{$ticket->servicename}}</td>
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

<!-- start service modal -->
<div class="modal fade" id="add-services" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
      <form class="form-material m-t-40 form-valide" method="post" action="{{route('worker.servicecreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
        <div class="add-customer-modal">
          <h5>Add a new Service</h5>
          <input type="hidden" name="cid" id="cid" value="{{$cid}}">
        </div>
        <div class="row customer-form" id="product-box-tabs">
          <div class="col-md-12 mb-2">
            <input type="text" class="form-control" placeholder="Service Name" name="servicename" id="servicename" required="">
          </div>
          <div class="col-md-12 mb-2 position-relative">
            <i class="fa fa-dollar" style="position: absolute;top:18px;left: 27px;"></i>
  <input type="text" class="form-control" placeholder="Service Default Price" name="price" id="price" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" onpaste="return false" style="padding: 0 35px;" required="">
          </div>
          <div class="col-md-12 mb-2" style="display: none;">
            <div class="d-flex align-items-center">
            <select class="selectpicker form-control me-2" multiple aria-label="Default select example" data-placeholder="Select Products" data-live-search="true" name="defaultproduct[]" id="defaultproduct" >
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
              
            </select>
          </div>
          <div class="col-md-6 mb-2">
            <label>Default Time (hh:mm)</label><br>
            <div class="timepicker timepicker1" style="display:inline-block;">
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
          </div></div>
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
            <button class="btn btn-cancel btn-block" type="button" onClick="refreshPage()">Cancel</button>
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

<!-- product modal open -->
<!-- Modal -->
<div class="modal fade" id="add-product" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box overflow-hidden">
     <form class="form-material m-t-40 form-valide" method="post" action="{{route('worker.inventorycreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
       <div class="add-customer-modal">
     <h5>Add a new Product/Part</h5>
     
     </div>
     <input type="hidden" name="cid" id="cid" value="{{$cid}}">
  <div class="row customer-form" id="product-box-tabs">
    <div class="col-md-12 mb-3">
     <input type="text" class="form-control" placeholder="Product/Part Name" name="productname" id="productname" required="">
    </div>
    
    <div class="col-md-6 mb-3">
     <input type="text" class="form-control" placeholder="Quantity" name="quantity" id="quantity" required="">
    
     
     </div>
     <div class="col-md-6 mb-3">
    
     <input type="text" class="form-control" placeholder="Preferred Quantity" name="pquantity" id="pquantity" required="">
     
     </div>
     
     <div class="col-md-12 mb-3">
    
     <input type="text" class="form-control" placeholder="SKU #" name="sku" id="sku" required="">
     
     </div>
     
     <div class="col-md-12 mb-3">
      <input type="text" class="form-control" placeholder="Price" name="price" id="price" required="">
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
      <button class="btn btn-cancel btn-block" type="button" onClick="refreshPage()">Cancel</button>
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
     </div>


     </div>
   </div>



          </div>
     




<!-- Modal -->




<form method="post" action="{{route('worker.sendinvoice')}}">
  @csrf
<div class="modal fade" id="add-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <div class="modal-body">
      <div id="viewinvoicemodal"></div>
      </div>
  </div>
</div>
</div>
</form>

<form method="post" action="{{route('worker.schedulecreate')}}">
  @csrf
<div class="modal fade" id="ticket-popup" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <div class="modal-body">
      <div id="viewticketpopup"></div>
      </div>
  </div>
</div>
</form>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>
<script type="text/javascript">
  function refreshPage() {
    window.location.reload();
  } 
  $('.dropify').dropify();
  $(document).ready(function() {
    $('#example2').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
  });
  $(document).ready(function() {
    $('.summernote').summernote({
      height: 300,
    });
    
    $("#add-product").modal({
      show:false,
      backdrop:'static'
    });
    $("#add-services").modal({
      show:false,
      backdrop:'static'
    });
  });
  $.ajaxSetup({
        headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $(document).on('click','#sclick',function(e) {
    $("#add-tickets").hide();
  });
  $(document).on('click','#pclick',function(e) {
    $("#add-tickets").hide();
  });
  $(document).on('click','#editTickets',function(e) {
    $('.selectpicker').selectpicker();
    var id = $(this).data('id');
    var price = $(this).data('price');
    var dataString =  'id='+ id+ '&price='+ price;
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

  function setFocus() {
    document.myForm.cnotes.focus();
  }

  $(document).on('click','#ticketpopup',function(e) {
    $('.selectpicker').selectpicker();
    var id = $(this).data('id');
      var dataString =  'id='+ id;
      $.ajax({
            url:'{{route('worker.viewticketpopup')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewticketpopup').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
            }
        })
    });
  $('#selector').delay(2000).fadeOut('slow');
 $(document).on('change','#serviceid',function(e) {
    var serviceid = $('#serviceid').val();
    var productid = $('#productid').val(); 
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid;
    $.ajax({
          url:'{{route('worker.calculateprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            console.log(data.totalprice);
            $('#price12').val(data.totalprice);
          }
      })
   });
   $(document).on('change','#productid',function(e) {
      var serviceid = $('#serviceid').val();
      var productid = $('#productid').val(); 
      var dataString =  'serviceid='+ serviceid+ '&productid='+ productid;
      $.ajax({
            url:'{{route('worker.calculateprice')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.totalprice);
              $('#price12').val(data.totalprice);
            }
        })
     });
   $(document).ready(function () {
    $("#buttonin" ).click(function() {
       $("#closeout").val('closeout');
      if($("#checklistcount").val() == undefined) {
        $( "#forminout" ).submit();
      } else {
        if($('input[name="pointckbox[]"]:checked').length == $("#checklistcount").val()) {
          var allVals = [];
          $( "#forminout" ).submit();
        } else {
          swal({
             title: "Oops!", 
             text: "Please check all service checklist!", 
             type: "error"
           },
         function(){ 
             location.reload();
         }
      );
          // alert('Please check all service checklist!');
          // location.reload();
          // swal("Oops!", "Please check all service checklist!", "error").then(function() {
          //     location.reload();
          // });
        }
      }
    });
  });
</script>
@endsection