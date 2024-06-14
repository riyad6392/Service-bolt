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



.card-header.card-ticket-header {
  position: relative;
  overflow: hidden;
  text-align: center;
}

.paid-image {
    position: absolute;
    width: 80px;
    top: -10px;
    left: -11px;
    }

.paid-image svg {
    width: 80px;
}


.file-loading {
    top: 0;
    right: 0;
    width: 25px;
    height: 25px;
    font-size: 999px;
    text-align: right;
    color: #fff;
    background: transparent url('../img/loading.gif') top left no-repeat;
    border: none;
}

.file-object {
    margin: 0 0 -5px 0;
    padding: 0;
}

.btn-file {
    position: relative;
    overflow: hidden;
}

.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    text-align: right;
    opacity: 0;
    background: none repeat scroll 0 0 transparent;
    cursor: inherit;
    display: block;
}

.file-caption-name {
    display: inline-block;
    overflow: hidden;
    height: 20px;
    word-break: break-all;
}

.input-group-lg .file-caption-name {
    height: 25px;
}

.file-zoom-dialog {
    text-align: left;
}

.file-error-message {
    color: #a94442;
    background-color: #f2dede;
    margin: 5px;
    border: 1px solid #ebccd1;
    border-radius: 4px;
    padding: 15px;
}

.file-error-message pre, .file-error-message ul {
    margin: 0;
    text-align: left;
}

.file-error-message pre {
    margin: 5px 0;
}

.file-caption-disabled {
    background-color: #EEEEEE;
    cursor: not-allowed;
    opacity: 1;
}

/*.file-preview {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 5px;
    width: 100%;
    margin-bottom: 5px;
}*/

.kv-upload-progress .progress{
  display: none;
}

.krajee-default.file-preview-frame {
    position: relative;
    display: table;
    margin: 8px;
    border: 1px solid #ddd;
    box-shadow: 1px 1px 5px 0 #a2958a;
    padding: 6px;
    float: left;
    text-align: center;
}

.krajee-default.file-preview-frame:not(.file-preview-error):hover {
    box-shadow: 3px 3px 5px 0 #333;
}

.krajee-default.file-preview-frame .kv-file-content {
    height: 170px;
}

.krajee-default.file-preview-frame .file-thumbnail-footer {
    height: 5px;
}

.krajee-default .file-preview-image {
    vertical-align: middle;
    image-orientation: from-image;
}

.krajee-default .file-preview-text {
    display: block;
    color: #428bca;
    border: 1px solid #ddd;
    font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
    outline: none;
    padding: 8px;
    resize: none;
}

.krajee-default .file-preview-html {
    border: 1px solid #ddd;
    padding: 8px;
    overflow: auto;
}

.krajee-default[data-template="audio"] .file-preview-audio {
    display: table-cell;
    vertical-align: middle;
    height: 170px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.krajee-default .file-preview-audio audio {
    vertical-align: middle;
}

.krajee-default .file-zoom-dialog .file-preview-text {
    font-size: 1.2em;
}

.krajee-default .file-preview-other {
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    text-align: center;
    vertical-align: middle;
    padding: 10px;
}

.krajee-default .file-preview-other:hover {
    opacity: 0.8;
}

.krajee-default .file-actions, .krajee-default .file-other-error {
    text-align: left;
}

.krajee-default .file-other-icon {
    font-size: 8em;
}

.krajee-default .file-actions {
    margin-top: 15px;
}

.krajee-default .file-footer-buttons {
    float: right;
}

.krajee-default .file-footer-caption {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 160px;
    text-align: center;
    padding-top: 4px;
    font-size: 11px;
    color: #777;
    margin: 5px auto;
}

.krajee-default .file-preview-error {
    opacity: 0.65;
    box-shadow: none;
}

.krajee-default .file-preview-frame:not(.file-preview-error) .file-footer-caption:hover {
    color: #000;
}

.krajee-default .file-drag-handle, .krajee-default .file-upload-indicator {
    position: absolute;
    text-align: center;
    bottom: -6px;
    left: -6px;
    padding: 8px 8px 1px 3px;
    border-left: none;
    border-bottom: none;
    border-right: 1px solid #8a6d3b;
    border-top: 1px solid #8a6d3b;
    border-top-right-radius: 24px;
    font-size: 12px;
}

.krajee-default .file-drag-handle {
    background-color: #d9edf7;
    border-color: #bce8f1;
}

.krajee-default .file-upload-indicator {
    font-size: 13px;
    background-color: #fcf8e3;
    border-color: #faebcc;
    padding-bottom: 0;
}

.krajee-default.file-preview-error .file-upload-indicator {
    background-color: #f2dede;
    border-color: #ebccd1;
}

.krajee-default.file-preview-success .file-upload-indicator {
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.krajee-default.file-preview-loading .file-upload-indicator {
    background-color: #e5e5e5;
    border-color: #777;
}

.krajee-default .file-thumb-progress {
    height: 10px;
}

.krajee-default .file-thumb-progress .progress, .krajee-default .file-thumb-progress .progress-bar {
    height: 10px;
    font-size: 9px;
    line-height: 10px;
}

.krajee-default .file-thumbnail-footer {
    position: relative;
}

.krajee-default .file-thumb-progress {
    position: absolute;
    top: 35px;
    left: 0;
    right: 0;
}

.krajee-default.kvsortable-ghost {
    background: #e1edf7;
    border: 2px solid #a1abff;
}

/* noinspection CssOverwrittenProperties */
.file-zoom-dialog .file-other-icon {
    font-size: 22em;
    font-size: 50vmin;
}

.file-input-new .file-preview, .file-input-new .close, .file-input-new .glyphicon-file,
.file-input-new .fileinput-remove-button, .file-input-new .fileinput-upload-button,
.file-input-ajax-new .fileinput-remove-button, .file-input-ajax-new .fileinput-upload-button {
    display: none;
}

.file-caption-main {
    width: 100%;
}

.file-input-ajax-new .no-browse .input-group-btn,
.file-input-new .no-browse .input-group-btn {
    display: none;
}

.file-input-ajax-new .no-browse .form-control,
.file-input-new .no-browse .form-control {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

.file-thumb-loading {
    background: transparent url('../img/loading.gif') no-repeat scroll center center content-box !important;
}

.file-sortable .file-drag-handle {
    cursor: move;
    cursor: -webkit-grabbing;
    opacity: 1;
}

.file-sortable .file-drag-handle:hover {
    opacity: 0.7;
}

.file-drop-zone {
    border: 1px dashed #aaa;
    border-radius: 4px;
    height: 100%;
    text-align: center;
    vertical-align: middle;
    margin: 12px 15px 12px 12px;
    padding: 5px;
}

.file-drop-zone-title {
    color: #aaa;
    font-size: 1.6em;
    padding: 85px 10px;
    cursor: default;
}

.file-preview .clickable,
.clickable .file-drop-zone-title {
    cursor: pointer;
}

.file-drop-zone.clickable:hover {
    border: 2px dashed #999;
}

.file-drop-zone.clickable:focus {
    border: 2px solid #5acde2;
}

.file-drop-zone .file-preview-thumbnails {
    cursor: default;
}

.file-highlighted {
    border: 2px dashed #999 !important;
    background-color: #f0f0f0;
}

.file-uploading {
    background: url('../img/loading-sm.gif') no-repeat center bottom 10px;
    opacity: 0.65;
}

.file-zoom-fullscreen.modal {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}

.file-zoom-fullscreen .modal-dialog {
    position: fixed;
    margin: 0;
    width: 100%;
    height: 100%;
    padding: 0;
}

.file-zoom-fullscreen .modal-content {
    border-radius: 0;
    box-shadow: none;
}

.file-zoom-fullscreen .modal-body {
    overflow-y: auto;
}

.file-zoom-dialog .modal-body {
    position: relative !important;
}

.file-zoom-dialog .btn-navigate {
    position: absolute;
    padding: 0;
    margin: 0;
    background: transparent;
    text-decoration: none;
    outline: none;
    opacity: 0.7;
    top: 45%;
    font-size: 4em;
    color: #1c94c4;
}

.file-zoom-dialog .floating-buttons {
    position: absolute;
    top: 5px;
    right: 10px;
}

.floating-buttons, .floating-buttons .btn {
    z-index: 3000;
}

.file-zoom-dialog .kv-zoom-actions .btn,
.floating-buttons .btn {
    margin-left: 3px;
}

.file-zoom-dialog .btn-navigate:not([disabled]):hover,
.file-zoom-dialog .btn-navigate:not([disabled]):focus {
    outline: none;
    box-shadow: none;
    opacity: 0.5;
}

.file-zoom-dialog .btn-navigate[disabled] {
    opacity: 0.3;
}

.file-zoom-dialog .btn-prev {
    left: 1px;
}

.file-zoom-dialog .btn-next {
    right: 1px;
}

.file-zoom-content {
    height: 480px;
    text-align: center;
}

.file-zoom-content .file-preview-image, .file-preview-
.file-zoom-content .file-preview-video {
    max-height: 100%
}

.file-preview-initial.sortable-chosen {
    background-color: #d9edf7;
}

/* IE 10 fix */
.btn-file ::-ms-browse {
    width: 100%;
    height: 100%;
}
.add-btn-yellow1 {
    display: inline-block;
    background: #fee200;
    border-radius: 10px;
    text-decoration: none;
    color: #232322;
    padding: 10.5px 16px;
    font-size: 16px;
    font-weight: 300;
    width: 150px;
    height: 43px;
}
.modal-content.customer-modal-box {
    width: 85%;
    position: relative;
    left: 27%;
}
.img-fluid {
    max-width: 80% !important;
    height: 82% !important;
    padding: 12px;
}
section.promo_section {
    background: #fff !important;
    padding: 14px;
}
</style>

<div class="content">
     <div class="row">
      <div class="col-md-12">
        <a href="{{ url()->previous() }}" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
     Back</a>
        <div class="side-h3">
       <h3 class="align-items-center d-flex justify-content-between">Ticket Details

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
  if($quotedetails[0]->payment_status!=null || $quotedetails[0]->payment_mode!=null) {
    $payment_status = "Completed";
  } else {
    $payment_status = "Pending";
  }

@endphp
  {{$quotedetails[0]->givendate}} - {{$quotedetails[0]->giventime}}</span>
  <a class="btn add-btn-yellow1 w-40 viewinvoice" data-id="{{$quotedetails[0]->id}}" data-bs-toggle="modal" data-bs-target="#view-invoice" style="margin-left: 70px;">Invoice</a>
  <a class="btn add-btn-yellow1 w-40 sendtocustomer" data-bs-toggle="modal" data-bs-target="#edit-tickets" id="editTickets" data-id="{{$quotedetails[0]->id}}" data-pid="{{$quotedetails[0]->personnelid}}" data-view="showview"style="margin-left: 70px;">Edit Invoice</a>
</h5>
<div class="text-lead mb-3">
    <p>Customer name: {{$quotedetails[0]->customername}}</p>
  <p>Customer Address: {{$quotedetails[0]->address}}</p>

  <div class="row" style="background: #eff3f5;padding: 10px;border-radius: 6px;">
    <div class="col-md-6">
      <div>
        <p>Start Time: {{$quotedetails[0]->giventime}}</p>
      </div>
    </div>
    @if($quotedetails[0]->givenendtime!="")
    <div class="col-md-6">
      <div>
        <p>End Time: {{$quotedetails[0]->givenendtime}}</p>
      </div>
    </div>
    @endif
    <div class="col-md-6">
      <div>
        <p>Time: {{$quotedetails[0]->time}} {{$quotedetails[0]->minute}}</p>
      </div>
    </div>
    <div class="col-md-6">
      <div>
        <p>Billing Price: ${{$quotedetails[0]->price}}</p>
      </div>
    </div>
    <div class="col-md-6">
      <div>
        <p>Ticket Total Price: ${{$quotedetails[0]->tickettotal}}</p>
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
        <p>Payment Status: {{$payment_status}}</p>
    </div>
</div>
<div class="col-md-6">
    <div>
        <p>@if(@$addressnote->notes!=null) Address Notes: {{@$addressnote->notes}} @endif</p>
    </div>
</div>

<div class="col-md-6">
    <div>
        <p>@if($quotedetails[0]->customernotes!="") Personnel Notes:{!!$quotedetails[0]->customernotes!!}@endif</p>
    </div>
</div>
<div class="col-md-6">
    <div>
        <p>@if($quotedetails[0]->invoicenote!="") Invoice Notes:{!!$quotedetails[0]->invoicenote!!}@endif</p>
    </div>
</div>


@php
    if($quotedetails[0]->imagelist!="")
    {
      $imagelist = explode(',',$quotedetails[0]->imagelist);
      @endphp
      <div class="row" style="margin-top: 45px;">
      <section class="promo_section">
    <div class="container ">
      <div class="row">
      @php
      foreach($imagelist as $key =>$image) {
         $imgtype= explode('.',strtolower($image));
        @endphp
          @if($imgtype[1]=="mp4" || $imgtype[1]=="3gp" || $imgtype[1]=="mov" || $imgtype[1]=="avi" || $imgtype[1]=="wmv" || $imgtype[1]=="flv" || $imgtype[1]=="m3u8")
          <div class="col-lg-4 col-sm-4">
            <div class="removediv">
              <div class="images position-relative" style="">
                  <a href="{{url('/')}}/uploads/ticketnote/{{$image}}" target="_blank"><video width="250" height="200" controls>
                    <source src="{{url('/')}}/uploads/ticketnote/{{$image}}" type="video/mp4">
                  </video></a>
              </div>
            </div>
          </div>
         @else
          <div class="col-lg-4 col-sm-4">
            <div class="removediv">
                <div class="images position-relative">
                 <a href="{{url('/')}}/uploads/ticketnote/{{$image}}" target="_blank"><img src="{{url('/')}}/uploads/ticketnote/{{$image}}"class="img-fluid"></a>
                </div>
            </div>
          </div>

          @endif


        @php
      }
      @endphp
       </div>
    </div>
  </section>
</div>
      @php
    }
  @endphp

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
        <form class="post-4" method="post" action="{{ route('company.viewinvoice') }}" enctype="multipart/form-data">
        @csrf
            <input type="hidden" name="ticketid" id="ticketid" value="{{$quotedetails[0]->id}}">
            <div class="col-md-12">
                <div>
                    <button class="btn add-btn-yellow w-50 viewinvoice" type="submit" name="invoicetype" value="viewinvoice" target="_blank">View Invoice</button>
                </div>

            </div>
        </form><br>
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

<div class="modal fade" id="edit-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box" style="width: 100%!important;">
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

    $(document).on('click','#editTickets',function(e) {
   $('.selectpicker2').selectpicker();
   var id = $(this).data('id');
   var view = $(this).data('view');
   var pvalue = $(this).data('pid');

   var type = $(this).data('type');
   if(type==undefined) {
    var type = "quote";
   }
   var dataString =  'id='+ id+ '&type='+ type+ '&view='+ view;
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
    $(document).find('#testprice').empty('');
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
            $('#testprice').append(data.hourpricehtml);

        }
      })


})
$(document).on('change','#productid',function(e) {
  //getpricep1();
    $(document).find('#testprice1').empty('');
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
            $('#testprice1').append(data.hourproducthtml);
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