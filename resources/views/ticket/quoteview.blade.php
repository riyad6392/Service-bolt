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
</style>

<div class="content">
     <div class="row">
      <div class="col-md-12">
        <a href="{{url('/company/quote')}}" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
     Back</a>
        <div class="side-h3">
       <h3 class="align-items-center d-flex justify-content-between">Quote Details 
       
     </h3>
     </div>
  </div>
  @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
<div class="col-md-12">
<div class="card overflow-hidden">


<div class="card-body">
<div class="card-content">
<h5>

#{{$quotedetails[0]->id}} <span class="ms-2">
@php
  $datef = date('F d, Y', strtotime($quotedetails[0]->etc));
  $addressnote = App\Models\Address::select('notes')->where('customerid',$quotedetails[0]->customerid)->where('address',$quotedetails[0]->address)->first();
  $pname = App\Models\Personnel::select('personnelname')->where('id',$quotedetails[0]->personnelid)->first();
 
@endphp
@if(isset($quotedetails[0]->givendate))
  {{$quotedetails[0]->givendate}} - {{$quotedetails[0]->giventime}}</span>
@endif 
</h5>
<div class="text-lead mb-3">
    <p>Customer name: {{$quotedetails[0]->customername}}</p>
    <p>Customer Address: {{$quotedetails[0]->address}}</p>
  @if(isset($pname->personnelname))
    <p>Personnel Name: {{$pname->personnelname}}</p>
  @endif
  <div class="row" style="background: #eff3f5;padding: 10px;border-radius: 6px;">
    <div class="col-md-6">
      <div>
        <p>Start Time: {{@$quotedetails[0]->giventime}}</p>
      </div>
    </div>
    @if($quotedetails[0]->givenendtime!="")
    <div class="col-md-6">
      <div>
        <p>End Time: {{@$quotedetails[0]->givenendtime}}</p>
      </div>
    </div>
    @endif
    <div class="col-md-6">
      <div>
        <p>Time: {{@$quotedetails[0]->time}} {{@$quotedetails[0]->minute}}</p>
      </div>
    </div>
   
    <div class="col-md-6">
      <div>
        <p>Ticket Total Price: ${{@$quotedetails[0]->tickettotal}}</p>
      </div>
    </div>
    
    <div class="col-md-6">
      <div>
<p>Service: {{$servicename}}</p></div></div>
@if($productname!="")
   <div class="col-md-6">
      <div>
<p>Product: {{$productname}}</p></div></div>
@endif
<div class="col-md-6">
  <div>
<p>@if($quotedetails[0]->description!="")Company Notes: {{$quotedetails[0]->description}}@endif</p></div></div>
<div class="col-md-6">
    <div>
        <p>@if($addressnote->notes!=null) Address Notes: {{$addressnote->notes}} @endif</p>
    </div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="view-invoice" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form class="post-4" method="post" action="{{ route('company.downloadinvoiceview') }}" enctype="multipart/form-data">
        @csrf
            <input type="hidden" name="ticketid" id="ticketid" value="{{$quotedetails[0]->id}}">
            <div class="col-md-12">
                <div>
                    <button class="btn add-btn-yellow w-50 viewinvoice" type="submit">Download Invoice</button>
                </div>

            </div>
        </form>
        <br>
        @php
            $cinfo = App\Models\Customer::select('email')->where('id',$quotedetails[0]->customerid)->first();
        @endphp
        <form class="post-4" method="post" action="{{ route('company.sendticketinvoice') }}" enctype="multipart/form-data">
        @csrf
            <input type="hidden" name="ticketid" id="ticketid" value="{{$quotedetails[0]->id}}">
            <input type="hidden" name="tickettype" id="tickettype" value="sendcustomer">
            <div class="col-md-12">
                <div>
                    <a class="btn add-btn-yellow w-50 sendtocustomer" data-email="{{$cinfo->email}}" data-id="{{$quotedetails[0]->id}}" data-bs-toggle="modal" data-bs-target="#edit-address">Send to Customer</a>
                </div>

            </div>
        </form>
      </div>
  </div>
</div>
</div>

   </div>
 </div>
 <div class="modal fade" id="edit-address" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.sendticketinvoice') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="tickettype" id="tickettype" value="sendcustomer">
          <div id="viewinvoicemodaldata"></div>
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
    $(document).on('click','.sendtocustomer',function(e) {
        var id = $(this).data('id');
        var email = $(this).data('email');
         $.ajax({
          url:"{{url('company/billing/leftbarinvoiceemail')}}",
          data: {
            id: id,
            email :email
          },
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#viewinvoicemodaldata').html(data.html);
            
          }
        });
       return false;
    })
 </script>
 @endsection