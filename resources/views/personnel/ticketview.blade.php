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



/*narayan css start*/
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

p {
    font-size: 18px;
    font-weight: 400;
    line-height: 1.5;
    margin-top: 1rem;
    color: var(--white-color);
}

.promo_section {
    padding: 1rem 0 4rem;
}

.promo_section i.fa.fa-trash.icon.icon {
    position: absolute;
    top: 4px;
    right: 4px;
    background: red;
    padding: 5px;
    border-radius: 5px;
    color: white;
    width: 26px;
    height: 26px;
}

.promo_section .images img{
    border-radius: 10px;
    margin-bottom: 15px;
    height: 100px;
    width: 188px;
}


.promo_section .upload-btn {
    position: relative;
    overflow: hidden;
    background: yellow;
    display: block;
    width: fit-content;
    margin: auto;
}

 .promo_section i.fa.fa-cloud-upload {
    font-size: 26px;
    margin-bottom: 1px;
}

  .promo_section .main-upload input {
    position: absolute;
    font-size: 50px;
    opacity: 0;
    right: 0;
    top: 0;
  }

  .promo_section .main-upload.text-center {
    border: dashed;
    padding: 15px;
    border-radius: 17 px;
}

.main-upload input#image {
    background: #ffec51;
    padding: 9px;
    width: 72%;
    border-radius: 10px;
    color: black;
    margin-bottom: 15px;
    /* opacity: 0; */
    font-size: 14px;
}

.main-upload.text-center {
    border: dashed;
    margin-bottom: 14px;
    border-radius: 17px;
    color: darkgrey;
}

i.fa.fa-trash.icon.remove {
    color: yellow;
    font-size: 30px;
}


/*narayan css exit */

</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>

<div class="content">
     <div class="row">
      <div class="col-md-12">
        <a href="{{url('personnel/myticket')}}" class="back-btn">
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
    @php
    if(in_array("Unclose Ticket", $permissonarray)) {
        $sclass="";
    } else {
        $sclass="pointer-events: none;background:#fee2002e";
    }
    @endphp
      <div class="col-lg-6">
        @if($quoteData->payment_mode!="")
            <button type="button" class="btn add-btn-yellow mb-4 w-100" name="unclose" value="unclose" id="unclose" style="pointer-events: none;background:#fee2002e">Unclose</button>
        @else
            <button type="button" class="btn add-btn-yellow mb-4 w-100" name="unclose" value="unclose" id="unclose" style="{{$sclass}}">Unclose</button>
        @endif
       
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

  @if($quoteData->payment_mode!="")
<div class="paid-image">
  <svg viewBox="0 0 55 56" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0 56V43L40.5 0H55L0 56Z" fill="#FAED61"/>
<path d="M19.8812 34.2437L15.1635 29.7065L16.9536 27.8452C17.2977 27.4874 17.6592 27.2482 18.0381 27.1278C18.4155 27.006 18.7882 26.9949 19.1563 27.0947C19.5244 27.1915 19.8651 27.3906 20.1784 27.6919C20.4916 27.9932 20.7031 28.3266 20.8127 28.6921C20.9223 29.0577 20.9206 29.4329 20.8075 29.8179C20.6959 30.2013 20.4659 30.5743 20.1173 30.9367L18.9764 32.123L18.177 31.3543L19.1629 30.3292C19.3475 30.1372 19.4666 29.9473 19.5202 29.7594C19.5738 29.5685 19.571 29.3854 19.5118 29.2103C19.4526 29.0321 19.3454 28.8684 19.1903 28.7193C19.0336 28.5686 18.8667 28.4686 18.6894 28.4194C18.5121 28.3671 18.3306 28.3729 18.145 28.4368C17.9579 28.4992 17.7712 28.6271 17.5851 28.8206L16.9382 29.4933L20.8405 33.2462L19.8812 34.2437ZM24.8485 29.2124C24.6314 29.4382 24.3988 29.6017 24.1506 29.703C23.9009 29.8028 23.649 29.8295 23.3949 29.7833C23.1408 29.734 22.8971 29.5971 22.6636 29.3726C22.4671 29.1835 22.3367 28.9887 22.2725 28.788C22.2083 28.5873 22.1953 28.3857 22.2336 28.183C22.2718 27.9803 22.3463 27.7799 22.4569 27.5819C22.5691 27.3823 22.7009 27.1868 22.8523 26.9955C23.0288 26.7691 23.1683 26.584 23.271 26.4404C23.3721 26.2952 23.4308 26.1758 23.4471 26.082C23.4633 25.9883 23.4338 25.9052 23.3586 25.8329L23.3448 25.8196C23.1989 25.6793 23.0417 25.6168 22.8732 25.6321C22.7062 25.6459 22.5422 25.7365 22.3812 25.9039C22.2114 26.0805 22.1154 26.2587 22.0933 26.4385C22.0697 26.6167 22.0999 26.7789 22.1842 26.9249L21.2376 27.7616C21.0669 27.5088 20.9682 27.2395 20.9415 26.9537C20.9133 26.6664 20.9596 26.3754 21.0805 26.0807C21.2012 25.783 21.3975 25.4929 21.6693 25.2103C21.8583 25.0137 22.0623 24.8478 22.2812 24.7124C22.5015 24.5755 22.7273 24.4837 22.9584 24.437C23.191 24.3888 23.4225 24.397 23.6528 24.4619C23.8816 24.5252 24.1012 24.6581 24.3116 24.8604L26.6981 27.1556L25.803 28.0862L25.3124 27.6144L25.2858 27.642C25.3417 27.8052 25.3661 27.975 25.359 28.1514C25.3504 28.3264 25.3056 28.5036 25.2247 28.683C25.1423 28.8609 25.0169 29.0374 24.8485 29.2124ZM24.4416 28.2801C24.5804 28.1357 24.6746 27.9809 24.7241 27.8157C24.7721 27.649 24.7772 27.4869 24.7395 27.3294C24.7017 27.1719 24.6207 27.0334 24.4963 26.9137L24.1208 26.5526C24.1112 26.6025 24.089 26.6625 24.0542 26.7325C24.0194 26.7995 23.9771 26.8726 23.9274 26.952C23.8762 27.0298 23.8242 27.1069 23.7715 27.1833C23.7172 27.2582 23.668 27.3262 23.624 27.3873C23.5301 27.5188 23.4602 27.6452 23.4144 27.7666C23.3685 27.8881 23.3542 28.0029 23.3714 28.1111C23.387 28.2177 23.4409 28.3154 23.5331 28.404C23.6667 28.5325 23.8153 28.5824 23.979 28.5535C24.1426 28.5217 24.2968 28.4306 24.4416 28.2801ZM27.4303 26.3943L23.892 22.9914L24.8358 22.01L28.3741 25.413L27.4303 26.3943ZM23.91 22.0597C23.7697 22.2056 23.6009 22.2843 23.4038 22.2957C23.2065 22.304 23.0396 22.2425 22.9029 22.111C22.7677 21.9811 22.7013 21.8181 22.7035 21.6222C22.7056 21.4232 22.7769 21.2508 22.9172 21.1049C23.0575 20.959 23.2263 20.8819 23.4235 20.8735C23.6207 20.8621 23.7868 20.9214 23.922 21.0514C24.0587 21.1828 24.1259 21.348 24.1238 21.547C24.1216 21.7429 24.0503 21.9139 23.91 22.0597ZM30.4416 23.3784C30.1831 23.6472 29.8799 23.8241 29.532 23.9093C29.184 23.9914 28.8135 23.9662 28.4207 23.8338C28.0277 23.6983 27.6339 23.4407 27.2393 23.0611C26.8338 22.6712 26.5595 22.2818 26.4163 21.8928C26.2716 21.5023 26.2365 21.1345 26.3112 20.7895C26.3857 20.4415 26.5478 20.1377 26.7974 19.8781C26.988 19.68 27.1805 19.5474 27.3751 19.4803C27.5697 19.4103 27.756 19.3825 27.9341 19.3971C28.1121 19.4087 28.2694 19.4388 28.406 19.4874L28.4348 19.4574L26.661 17.7515L27.6026 16.7725L32.3203 21.3097L31.3898 22.2772L30.8231 21.7322L30.7833 21.7737C30.8372 21.9143 30.872 22.0733 30.8875 22.2509C30.903 22.4254 30.8779 22.6097 30.8124 22.8039C30.7483 22.9965 30.6247 23.188 30.4416 23.3784ZM29.9598 22.3164C30.1119 22.1582 30.1974 21.9833 30.2163 21.7915C30.2351 21.5968 30.1942 21.3949 30.0937 21.1859C29.9947 20.9753 29.8399 20.7689 29.6295 20.5666C29.4191 20.3642 29.2083 20.2176 28.9971 20.1268C28.7859 20.036 28.5849 20.0053 28.3941 20.0346C28.2032 20.0639 28.0318 20.1577 27.8797 20.3159C27.7246 20.4771 27.6376 20.6551 27.6188 20.8499C27.6 21.0447 27.6408 21.245 27.7413 21.451C27.8418 21.657 27.9941 21.8582 28.1984 22.0547C28.4042 22.2526 28.6135 22.3992 28.8263 22.4945C29.0391 22.5868 29.2424 22.6212 29.4363 22.5979C29.6302 22.5715 29.8047 22.4777 29.9598 22.3164Z" fill="black"/>
</svg>
</div>
@endif
#{{$quoteData->id}} <span class="ms-2">
@php
  $datef = date('F d, Y', strtotime($quoteData->etc));
@endphp
  {{$quoteData->givendate}} - {{$quoteData->giventime}}</span>
</div>
<div class="card-body">
<div class="card-content">
<h5 class="mb-4 text-center">Ticket Details @if($quoteData->primaryname == $pworkerid) (Primary) @endif</h5>
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
        <p>Time: {{$quoteData->time}} {{$quoteData->minute}}</p>
      </div>
    </div>
     @if($quoteData->payment_mode!="")
        @if(!in_array("See Price of Previous Tickets", $permissonarray))
            <div class="col-md-6">
              
            </div>
        @endif
    @endif
    @if($quoteData->payment_mode!="")
        @if(in_array("See Price of Previous Tickets", $permissonarray))
            <div class="col-md-6">
              <div>
                <p>Price: ${{$quoteData->price}}</p>
              </div>
            </div>
        @endif
    @endif
    
    @if($quoteData->payment_mode=="")
       <div class="col-md-6">
              <div>
                <p>Price: ${{$quoteData->price}}</p>
              </div>
            </div>     
    @endif
    <div class="col-md-6">
      <div>
<p>{{$servicename}}</p></div></div>
<div class="col-md-6">
  <div>
<p>{{@$productname}}</p></div></div>
@php
    $addressnote = App\Models\Address::select('notes')->where('customerid',$quoteData->customerid)->where('address',$quoteData->address)->first();
    
    if(@$addressnote->notes !=null) {
        $addressnote = $addressnote->notes;
    } else {
        $addressnote = "--";
    }
@endphp
<div class="col-md-6">
    <div>
        <p>Address Note : {{@$addressnote}}</p>
        @if(!empty($ckinfo) && count($ckinfo)>0)
            <ul>
                @foreach($ckinfo as $key=>$value) 
                    <li>{{@$value->checklistname}}</li>
                        <ul>
                            @php
                              $checklistdata  = App\Models\Checklist::select('checklist')->where('serviceid',$value->serviceid)->where('userid',$value->userid)->get();
                            @endphp
                            @foreach($checklistdata as $key => $value1)
                                <li>{{$value1->checklist}}</li>
                            @endforeach
                        </ul>
                @endforeach
            </ul>
        @endif

    </div>
</div>
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
@php
    if($quoteData->payment_mode!="") {
        $sclass = "pointer-events: none;background:#fee2002e";
    } else {
        $sclass = "";
    }
@endphp
<div class="col-lg-12 mb-3">
<a class="btn add-btn-yellow w-100" data-bs-toggle="modal" data-bs-target="#add-tickets" id="editTickets" data-id="{{$quoteData->id}}" data-price="{{$quoteData->price}}" style="{{$sclass}}">Create Invoice</a>
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
<h5 class="mb-4">Personnel Notes</h5>

    
<!-- <textarea name="cnotes" class="form-control customer-height mb-4" placeholder="Customer Notes" style="height: 280px;color: gray;">{{$quoteData->customernotes}}</textarea> -->
<div class="mb-3">
<textarea class="summernote form-control customer-height mb-4" name="cnotes" name="description" placeholder="Customer Notes">{{$quoteData->customernotes}}</textarea>
</div>

 @php
    if($quoteData->imagelist!="") 
    {
      $imagelist = explode(',',$quoteData->imagelist);
      @endphp
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
              <div class="images position-relative">
                  <a href="{{url('/')}}/uploads/ticketnote/{{$image}}" target="_blank"><video width="130" height="100" controls>
                    <source src="{{url('/')}}/uploads/ticketnote/{{$image}}" type="video/mp4">
                  </video></a>

                  <div class="delete-icon">
                      <i class="fa fa-trash icon delete"></i><input type="hidden" name="oldimage[]" value="{{$image}}">
                  </div>
              </div>
            </div>
          </div>
          
          @else
          <div class="col-lg-4 col-sm-4">
            <div class="removediv">
              <div class="images position-relative">
                 <a href="{{url('/')}}/uploads/ticketnote/{{$image}}" target="_blank"><img src="{{url('/')}}/uploads/ticketnote/{{$image}}"class="img-fluid"></a>

                  <div class="delete-icon ">
                      <i class="fa fa-trash icon delete"></i><input type="hidden" name="oldimage[]" value="{{$image}}"/>
                  </div>
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
      @php
    }
  @endphp
        <div class="col-12" style="margin-top: -7px;">
            <!-- <span>Note - Maximum Upload size of 2 MB</span> -->
            <div class="main-upload text-center">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                <p style="color:black;">Browse Videos or images</p>
                <div class="file btn btn-lg upload-btn" >
                    <!-- Upload -->
                  <input id="image" name="image[]" type="file" accept="image/*,video/*" multiple>
                </div>
            </div>
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
    @if(in_array("See Price of Previous Tickets", $permissonarray))
    <th>Price</th>
    @endif
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
    @if(in_array("See Price of Previous Tickets", $permissonarray))
    <td>${{$ticket->price}}</td>
    @endif
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

<!-- address model start-->
<div class="modal fade" id="add-address" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content customer-modal-box  overflow-hidden">
     <!-- <form class="form-material m-t-40  form-valide" method="post" action="{{route('company.customeraddresscreate')}}" enctype="multipart/form-data">
        @csrf -->
      <div class="modal-body">
       <div class="add-customer-modal">
     <h5>Add Address</h5>
     </div>
     <input type="hidden" name="customerid" id="customerid" value="">
     <div class="row customer-form">
     <div class="col-md-12 mb-3">
     
     <input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address" required="">
  
     </div>
     <div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
     <button id="saveaddress" class="btn btn-add btn-block">Add Address</button>
     </div>
     
     </div>
      </div>
     <!-- </form> -->
    </div>
  </div>
</div>
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
            <input type="text" class="hh N" min="0" max="100" placeholder="hh" maxlength="2" name="time" id="time" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">:
            <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" name="minute" id="minute" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false">
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




<form id="form" method="post" action="{{route('worker.sendinvoice')}}">
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
       toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'clear'] ],
            //[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            //[ 'table', [ 'table' ] ],
            [ 'insert', [ 'link'] ],
            //[ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
        ]
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
   $(document).on('click','#sclick1',function(e) {
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
    var qid = $('#qid').val();
    if(qid==undefined) {
      var qid = "";
    }
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('worker.calculateprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#price12').val(data.totalprice);
            $('#ticketprice').val(data.totalprice);
          }
      })
  });
   $(document).on('change','#productid',function(e) {
      var serviceid = $('#serviceid').val();
      var productid = $('#productid').val();
      var qid = $('#qid').val();
        if(qid==undefined) {
          var qid = "";
        }
      var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
      $.ajax({
            url:'{{route('worker.calculateprice')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#price12').val(data.totalprice); 
              $('#ticketprice').val(data.totalprice);

            }
        })
     });

    $(document).on('click',".delete" ,function() {
      $(this).closest(".removediv" ).parent().remove();
    });

   $(document).ready(function () {
    $("#buttonin" ).click(function() {
       $("#closeout").val('closeout');
      //if($("#checklistcount").val() == undefined) {
        $( "#forminout" ).submit();
      //}  
      // {
      //   if($('input[name="pointckbox[]"]:checked').length == $("#checklistcount").val()) {
      //     var allVals = [];
      //     $( "#forminout" ).submit();
      //   } else {
      //     swal({
      //        title: "Oops!", 
      //        text: "Please check all service checklist!", 
      //        type: "error"
      //      },
      //    function(){ 
      //        location.reload();
      //    }
      // );
      //   }
      // }
    });
  });
   $(document).ready(function () {

        $("#image").fileinput({
            'theme': 'explorer',
            'uploadUrl': '#',
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: [
            ],
            initialPreviewConfig: [
                
            ]
        });

    });

   /*!
 * bootstrap-fileinput v4.3.9
 * http://plugins.krajee.com/file-input
 *
 * Author: Kartik Visweswaran
 * Copyright: 2014 - 2017, Kartik Visweswaran, Krajee.com
 *
 * Licensed under the BSD 3-Clause
 * https://github.com/kartik-v/bootstrap-fileinput/blob/master/LICENSE.md
 */
(function (factory) {
    "use strict";
    //noinspection JSUnresolvedVariable
    if (typeof define === 'function' && define.amd) { // jshint ignore:line
        // AMD. Register as an anonymous module.
        define(['jquery'], factory); // jshint ignore:line
    } else { // noinspection JSUnresolvedVariable
        if (typeof module === 'object' && module.exports) { // jshint ignore:line
            // Node/CommonJS
            // noinspection JSUnresolvedVariable
            module.exports = factory(require('jquery')); // jshint ignore:line
        } else {
            // Browser globals
            factory(window.jQuery);
        }
    }
}(function ($) {
    "use strict";

    $.fn.fileinputLocales = {};
    $.fn.fileinputThemes = {};

    var $h, FileInput;

    // fileinput helper object for all global variables and internal helper methods
    //noinspection JSUnresolvedVariable
    $h = {
        FRAMES: '.kv-preview-thumb',
        SORT_CSS: 'file-sortable',
        STYLE_SETTING: 'style="width:{width};height:{height};"',
        OBJECT_PARAMS: '<param name="controller" value="true" />\n' +
        '<param name="allowFullScreen" value="true" />\n' +
        '<param name="allowScriptAccess" value="always" />\n' +
        '<param name="autoPlay" value="false" />\n' +
        '<param name="autoStart" value="false" />\n' +
        '<param name="quality" value="high" />\n',
        DEFAULT_PREVIEW: '<div class="file-preview-other">\n' +
        '<span class="{previewFileIconClass}">{previewFileIcon}</span>\n' +
        '</div>',
        MODAL_ID: 'kvFileinputModal',
        MODAL_EVENTS: ['show', 'shown', 'hide', 'hidden', 'loaded'],
        objUrl: window.URL || window.webkitURL,
        compare: function (input, str, exact) {
            return input !== undefined && (exact ? input === str : input.match(str));
        },
        isIE: function (ver) {
            // check for IE versions < 11
            if (navigator.appName !== 'Microsoft Internet Explorer') {
                return false;
            }
            if (ver === 10) {
                return new RegExp('msie\\s' + ver, 'i').test(navigator.userAgent);
            }
            var div = document.createElement("div"), status;
            div.innerHTML = "<!--[if IE " + ver + "]> <i></i> <![endif]-->";
            status = div.getElementsByTagName("i").length;
            document.body.appendChild(div);
            div.parentNode.removeChild(div);
            return status;
        },
        initModal: function ($modal) {
            var $body = $('body');
            if ($body.length) {
                $modal.appendTo($body);
            }
        },
        isEmpty: function (value, trim) {
            return value === undefined || value === null || value.length === 0 || (trim && $.trim(value) === '');
        },
        isArray: function (a) {
            return Array.isArray(a) || Object.prototype.toString.call(a) === '[object Array]';
        },
        ifSet: function (needle, haystack, def) {
            def = def || '';
            return (haystack && typeof haystack === 'object' && needle in haystack) ? haystack[needle] : def;
        },
        cleanArray: function (arr) {
            if (!(arr instanceof Array)) {
                arr = [];
            }
            return arr.filter(function (e) {
                return (e !== undefined && e !== null);
            });
        },
        spliceArray: function (arr, index) {
            var i, j = 0, out = [];
            if (!(arr instanceof Array)) {
                return [];
            }
            for (i = 0; i < arr.length; i++) {
                if (i !== index) {
                    out[j] = arr[i];
                    j++;
                }
            }
            return out;
        },
        getNum: function (num, def) {
            def = def || 0;
            if (typeof num === "number") {
                return num;
            }
            if (typeof num === "string") {
                num = parseFloat(num);
            }
            return isNaN(num) ? def : num;
        },
        hasFileAPISupport: function () {
            return !!(window.File && window.FileReader);
        },
        hasDragDropSupport: function () {
            var div = document.createElement('div');
            /** @namespace div.draggable */
            /** @namespace div.ondragstart */
            /** @namespace div.ondrop */
            return !$h.isIE(9) &&
                (div.draggable !== undefined || (div.ondragstart !== undefined && div.ondrop !== undefined));
        },
        hasFileUploadSupport: function () {
            return $h.hasFileAPISupport() && window.FormData;
        },
        addCss: function ($el, css) {
            $el.removeClass(css).addClass(css);
        },
        getElement: function (options, param, value) {
            return ($h.isEmpty(options) || $h.isEmpty(options[param])) ? value : $(options[param]);
        },
        uniqId: function () {
            return Math.round(new Date().getTime() + (Math.random() * 100));
        },
        htmlEncode: function (str) {
            return str.replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&apos;');
        },
        replaceTags: function (str, tags) {
            var out = str;
            if (!tags) {
                return out;
            }
            $.each(tags, function (key, value) {
                if (typeof value === "function") {
                    value = value();
                }
                out = out.split(key).join(value);
            });
            return out;
        },
        cleanMemory: function ($thumb) {
            var data = $thumb.is('img') ? $thumb.attr('src') : $thumb.find('source').attr('src');
            /** @namespace $h.objUrl.revokeObjectURL */
            $h.objUrl.revokeObjectURL(data);
        },
        findFileName: function (filePath) {
            var sepIndex = filePath.lastIndexOf('/');
            if (sepIndex === -1) {
                sepIndex = filePath.lastIndexOf('\\');
            }
            return filePath.split(filePath.substring(sepIndex, sepIndex + 1)).pop();
        },
        checkFullScreen: function () {
            //noinspection JSUnresolvedVariable
            return document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement ||
                document.msFullscreenElement;
        },
        toggleFullScreen: function (maximize) {
            var doc = document, de = doc.documentElement;
            if (de && maximize && !$h.checkFullScreen()) {
                /** @namespace document.requestFullscreen */
                /** @namespace document.msRequestFullscreen */
                /** @namespace document.mozRequestFullScreen */
                /** @namespace document.webkitRequestFullscreen */
                /** @namespace Element.ALLOW_KEYBOARD_INPUT */
                if (de.requestFullscreen) {
                    de.requestFullscreen();
                } else if (de.msRequestFullscreen) {
                    de.msRequestFullscreen();
                } else if (de.mozRequestFullScreen) {
                    de.mozRequestFullScreen();
                } else if (de.webkitRequestFullscreen) {
                    de.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                /** @namespace document.exitFullscreen */
                /** @namespace document.msExitFullscreen */
                /** @namespace document.mozCancelFullScreen */
                /** @namespace document.webkitExitFullscreen */
                if (doc.exitFullscreen) {
                    doc.exitFullscreen();
                } else if (doc.msExitFullscreen) {
                    doc.msExitFullscreen();
                } else if (doc.mozCancelFullScreen) {
                    doc.mozCancelFullScreen();
                } else if (doc.webkitExitFullscreen) {
                    doc.webkitExitFullscreen();
                }
            }
        },
        moveArray: function (arr, oldIndex, newIndex) {
            if (newIndex >= arr.length) {
                var k = newIndex - arr.length;
                while ((k--) + 1) {
                    arr.push(undefined);
                }
            }
            arr.splice(newIndex, 0, arr.splice(oldIndex, 1)[0]);
            return arr;
        },
        cleanZoomCache: function ($el) {
            var $cache = $el.closest('.kv-zoom-cache-theme');
            if (!$cache.length) {
                $cache = $el.closest('.kv-zoom-cache');
            }
            $cache.remove();
        }
    };
    FileInput = function (element, options) {
        var self = this;
        self.$element = $(element);
        if (!self._validate()) {
            return;
        }
        self.isPreviewable = $h.hasFileAPISupport();
        self.isIE9 = $h.isIE(9);
        self.isIE10 = $h.isIE(10);
        if (self.isPreviewable || self.isIE9) {
            self._init(options);
            self._listen();
        } else {
            self.$element.removeClass('file-loading');
        }
    };
    FileInput.prototype = {
        constructor: FileInput,
        _init: function (options) {
            var self = this, $el = self.$element, $cont, t;
            self.options = options;
            $.each(options, function (key, value) {
                switch (key) {
                    case 'minFileCount':
                    case 'maxFileCount':
                    case 'maxFileSize':
                        self[key] = $h.getNum(value);
                        break;
                    default:
                        self[key] = value;
                        break;
                }
            });
            self.$form = $el.closest('form');
            self._initTemplateDefaults();
            self.fileInputCleared = false;
            self.fileBatchCompleted = true;
            if (!self.isPreviewable) {
                self.showPreview = false;
            }
            self.uploadFileAttr = !$h.isEmpty($el.attr('name')) ? $el.attr('name') : 'file_data';
            self.reader = null;
            self.formdata = {};
            self.clearStack();
            self.uploadCount = 0;
            self.uploadStatus = {};
            self.uploadLog = [];
            self.uploadAsyncCount = 0;
            self.loadedImages = [];
            self.totalImagesCount = 0;
            self.ajaxRequests = [];
            self.isError = false;
            self.ajaxAborted = false;
            self.cancelling = false;
            t = self._getLayoutTemplate('progress');
            self.progressTemplate = t.replace('{class}', self.progressClass);
            self.progressCompleteTemplate = t.replace('{class}', self.progressCompleteClass);
            self.progressErrorTemplate = t.replace('{class}', self.progressErrorClass);
            self.dropZoneEnabled = $h.hasDragDropSupport() && self.dropZoneEnabled;
            self.isDisabled = $el.attr('disabled') || $el.attr('readonly');
            if (self.isDisabled) {
                $el.attr('disabled', true);
            }
            self.isUploadable = $h.hasFileUploadSupport() && !$h.isEmpty(self.uploadUrl);
            self.isClickable = self.browseOnZoneClick && self.showPreview &&
                (self.isUploadable && self.dropZoneEnabled || !$h.isEmpty(self.defaultPreviewContent));
            self.slug = typeof options.slugCallback === "function" ? options.slugCallback : self._slugDefault;
            self.mainTemplate = self.showCaption ? self._getLayoutTemplate('main1') : self._getLayoutTemplate('main2');
            self.captionTemplate = self._getLayoutTemplate('caption');
            self.previewGenericTemplate = self._getPreviewTemplate('generic');
            if (self.resizeImage && (self.maxImageWidth || self.maxImageHeight)) {
                self.imageCanvas = document.createElement('canvas');
                self.imageCanvasContext = self.imageCanvas.getContext('2d');
            }
            if ($h.isEmpty($el.attr('id'))) {
                $el.attr('id', $h.uniqId());
            }
            self.namespace = '.fileinput_' + $el.attr('id').replace(/-/g, '_');
            if (self.$container === undefined) {
                self.$container = self._createContainer();
            } else {
                self._refreshContainer();
            }
            $cont = self.$container;
            self.$dropZone = $cont.find('.file-drop-zone');
            self.$progress = $cont.find('.kv-upload-progress');
            self.$btnUpload = $cont.find('.fileinput-upload');
            self.$captionContainer = $h.getElement(options, 'elCaptionContainer', $cont.find('.file-caption'));
            self.$caption = $h.getElement(options, 'elCaptionText', $cont.find('.file-caption-name'));
            self.$previewContainer = $h.getElement(options, 'elPreviewContainer', $cont.find('.file-preview'));
            self.$preview = $h.getElement(options, 'elPreviewImage', $cont.find('.file-preview-thumbnails'));
            self.$previewStatus = $h.getElement(options, 'elPreviewStatus', $cont.find('.file-preview-status'));
            self.$errorContainer = $h.getElement(options, 'elErrorContainer',
                self.$previewContainer.find('.kv-fileinput-error'));
            if (!$h.isEmpty(self.msgErrorClass)) {
                $h.addCss(self.$errorContainer, self.msgErrorClass);
            }
            self.$errorContainer.hide();
            self.previewInitId = "preview-" + $h.uniqId();
            self._initPreviewCache();
            self._initPreview(true);
            self._initPreviewActions();
            self._setFileDropZoneTitle();
            $el.removeClass('file-loading');
            if ($el.attr('disabled')) {
                self.disable();
            }
            self._initZoom();
        },
        _initTemplateDefaults: function () {
            var self = this, tMain1, tMain2, tPreview, tFileIcon, tClose, tCaption, tBtnDefault, tBtnLink, tBtnBrowse,
                tModalMain, tModal, tProgress, tSize, tFooter, tActions, tActionDelete, tActionUpload, tActionZoom,
                tActionDrag, tTagBef, tTagBef1, tTagBef2, tTagAft, tGeneric, tHtml, tImage, tText, tVideo, tAudio,
                tFlash, tObject, tPdf, tOther, tZoomCache;
            tMain1 = '{preview}\n' +
                '<div class="kv-upload-progress hide"></div>\n' +
                '</div>';
            tMain2 = '{preview}\n<div class="kv-upload-progress hide"></div>\n{remove}\n{cancel}\n{upload}\n{browse}\n';
            tPreview = '<div class="">\n' +
                '    <div class="{dropClass}">\n' +
                '    <div class="file-preview-thumbnails">\n' +
                '    </div>\n' +
                '    <div class="clearfix"></div>' +
                '    <div class="file-preview-status text-center text-success"></div>\n' +
                '    <div class="kv-fileinput-error"></div>\n' +
                '    </div>\n' +
                '</div>';
            tClose = '<div class="">&times;</div>\n';
            tFileIcon = '<i class="glyphicon glyphicon-file kv-caption-icon"></i>';
            tCaption = '<div tabindex="500" class="form-control file-caption {class}">\n' +
                '   <div class="file-caption-name"></div>\n' +
                '</div>\n';
            //noinspection HtmlUnknownAttribute
            tBtnDefault = '<button type="{type}" tabindex="500" title="{title}" class="{css}" ' +
                '{status}>{icon} {label}</button>';
            //noinspection HtmlUnknownAttribute
            tBtnLink = '<a href="{href}" tabindex="500" title="{title}" class="{css}" {status}>{icon} {label}</a>';
            //noinspection HtmlUnknownAttribute
            tBtnBrowse = '<div tabindex="500" class="{css}" {status}>{icon} {label}</div>';
            tModalMain = '<div id="' + $h.MODAL_ID + '" class="file-zoom-dialog modal fade" ' +
                'tabindex="-1" aria-labelledby="' + $h.MODAL_ID + 'Label"></div>';
            tModal = '<div class="modal-dialog modal-lg" role="document">\n' +
                '  <div class="modal-content">\n' +
                '    <div class="modal-header">\n' +
                '      <div class="kv-zoom-actions pull-right">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
                '      <h3 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h3>\n' +
                '    </div>\n' +
                '    <div class="modal-body">\n' +
                '      <div class="floating-buttons"></div>\n' +
                '      <div class="kv-zoom-body file-zoom-content {zoomFrameClass}"></div>\n' + '{prev} {next}\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>\n';
            tProgress = '<div class="{class}" role="progressbar"' +
                ' aria-valuenow="{percent}" aria-valuemin="0" aria-valuemax="100" style="width:{percent}%;">\n' +
                '        {status}\n' +
                '     </div>\n' +
                '</div>';
            tSize = ' <samp>({sizeText})</samp>';
            tFooter = '<div class="file-thumbnail-footer" style="height:18px;">\n' +
                '    <div class="file-footer-caption" title="{caption}">{caption}<br>{size}</div>\n' +
                '    {progress} {actions}\n' +
                '</div>';
            tActions = '<div class="file-upload-indicator" title="{indicatorTitle}">{indicator}</div>\n' +
                '{drag}\n' +
                '<div class="file-actions">\n' +
                '    <div class="file-footer-buttons">\n' +
                '        {upload} {delete} {zoom} {other}' +
                '    </div>\n' +
                '    <div class="clearfix"></div>\n' +
                '</div>';
            //noinspection HtmlUnknownAttribute
            tActionDelete = '<button type="button" class="kv-file-remove {removeClass}" ' +
                'title="{removeTitle}" {dataUrl}{dataKey}>{removeIcon}</button>\n';
            tActionUpload = '<button type="button" class="kv-file-upload {uploadClass}" title="{uploadTitle}">' +
                '{uploadIcon}</button>';
            tActionZoom = '<button type="button" class="kv-file-zoom {zoomClass}" ' +
                'title="{zoomTitle}">{zoomIcon}</button>';
            tActionDrag = '<span class="file-drag-handle {dragClass}" title="{dragTitle}">{dragIcon}</span>';
            tTagBef = '<div class="file-preview-frame {frameClass}" id="{previewId}" data-fileindex="{fileindex}"' +
                ' data-template="{template}"';

            tTagBef1 = tTagBef + '><div class="container"><div class="kv-file-content">\n';
            tTagBef2 = tTagBef + ' title="{caption}"><div class="kv-file-content">\n';
            tTagAft = '</div>{footer}\n</div>\n';
            tGeneric = '{content}\n';
            tHtml = '<div class="kv-preview-data file-preview-html" title="{caption}" ' + $h.STYLE_SETTING +
                '>{data}</div>\n';
            tImage = '<img src="{data}" class="file-preview-image kv-preview-data" title="{caption}" alt="{caption}" ' +
                $h.STYLE_SETTING + '>\n';
            tText = '<textarea class="kv-preview-data file-preview-text" title="{caption}" readonly ' +
                $h.STYLE_SETTING + '>{data}</textarea>\n';
            tVideo = '<video class="kv-preview-data file-preview-video" width="{width}" ' +
                'height="{height}" controls>\n' + '<source src="{data}" type="{type}">\n' + $h.DEFAULT_PREVIEW +
                '\n</video>\n';
            tAudio = '<div class="file-preview-audio"><audio class="kv-preview-data" controls>\n<source src="{data}" ' +
                'type="{type}">\n' + $h.DEFAULT_PREVIEW + '\n</audio></div>\n';
            tFlash = '<object class="kv-preview-data file-object" type="application/x-shockwave-flash" ' +
                'width="{width}" height="{height}" data="{data}">\n' + $h.OBJECT_PARAMS + ' ' + $h.DEFAULT_PREVIEW +
                '\n</object>\n';
            tObject = '<object class="kv-preview-data file-object" data="{data}" type="{type}" ' +
                'width="{width}" height="{height}">\n' + '<param name="movie" value="{caption}" />\n' +
                $h.OBJECT_PARAMS + ' ' + $h.DEFAULT_PREVIEW + '\n</object>\n';
            tPdf = '<embed class="kv-preview-data" src="{data}" ' +
                'width="{width}" height="{height}" type="application/pdf">\n';
            tOther = '<div class="kv-preview-data file-preview-other-frame">\n' + $h.DEFAULT_PREVIEW + '\n</div>\n';
            tZoomCache = '<div class="kv-zoom-cache" style="display:none">{zoomContent}</div>';
            self.defaults = {
                layoutTemplates: {
                    main1: tMain1,
                    main2: tMain2,
                    preview: tPreview,
                    close: tClose,
                    fileIcon: tFileIcon,
                    caption: tCaption,
                    modalMain: tModalMain,
                    modal: tModal,
                    progress: tProgress,
                    size: tSize,
                    footer: tFooter,
                    actions: tActions,
                    actionDelete: tActionDelete,
                    actionUpload: tActionUpload,
                    actionZoom: tActionZoom,
                    actionDrag: tActionDrag,
                    btnDefault: tBtnDefault,
                    btnLink: tBtnLink,
                    btnBrowse: tBtnBrowse,
                    zoomCache: tZoomCache
                },
                previewMarkupTags: {
                    tagBefore1: tTagBef1,
                    tagBefore2: tTagBef2,
                    tagAfter: tTagAft
                },
                previewContentTemplates: {
                    generic: tGeneric,
                    html: tHtml,
                    image: tImage,
                    text: tText,
                    video: tVideo,
                    audio: tAudio,
                    flash: tFlash,
                    object: tObject,
                    pdf: tPdf,
                    other: tOther
                },
                allowedPreviewTypes: ['image', 'html', 'text', 'video', 'audio', 'flash', 'pdf', 'object'],
                previewTemplates: {},
                previewSettings: {
                    image: {width: "auto", height: "160px"},
                    html: {width: "213px", height: "160px"},
                    text: {width: "213px", height: "160px"},
                    video: {width: "213px", height: "160px"},
                    audio: {width: "213px", height: "80px"},
                    flash: {width: "213px", height: "160px"},
                    object: {width: "160px", height: "auto"},
                    pdf: {width: "160px", height: "160px"},
                    other: {width: "160px", height: "160px"}
                },
                previewZoomSettings: {
                    image: {width: "auto", height: "auto", 'max-width': "100%", 'max-height': "100%"},
                    html: {width: "100%", height: "100%", 'min-height': "480px"},
                    text: {width: "100%", height: "100%", 'min-height': "480px"},
                    video: {width: "auto", height: "100%", 'max-width': "100%"},
                    audio: {width: "100%", height: "30px"},
                    flash: {width: "auto", height: "480px"},
                    object: {width: "auto", height: "100%", 'min-height': "480px"},
                    pdf: {width: "100%", height: "100%", 'min-height': "480px"},
                    other: {width: "auto", height: "100%", 'min-height': "480px"}
                },
                fileTypeSettings: {
                    image: function (vType, vName) {
                        return $h.compare(vType, 'image.*') || $h.compare(vName, /\.(gif|png|jpe?g)$/i);
                    },
                    html: function (vType, vName) {
                        return $h.compare(vType, 'text/html') || $h.compare(vName, /\.(htm|html)$/i);
                    },
                    text: function (vType, vName) {
                        return $h.compare(vType, 'text.*') || $h.compare(vName, /\.(xml|javascript)$/i) ||
                            $h.compare(vName, /\.(txt|md|csv|nfo|ini|json|php|js|css)$/i);
                    },
                    video: function (vType, vName) {
                        return $h.compare(vType, 'video.*') && ($h.compare(vType, /(ogg|mp4|mp?g|mov|webm|3gp)$/i) ||
                            $h.compare(vName, /\.(og?|mp4|webm|mp?g|mov|3gp)$/i));
                    },
                    audio: function (vType, vName) {
                        return $h.compare(vType, 'audio.*') && ($h.compare(vName, /(ogg|mp3|mp?g|wav)$/i) ||
                            $h.compare(vName, /\.(og?|mp3|mp?g|wav)$/i));
                    },
                    flash: function (vType, vName) {
                        return $h.compare(vType, 'application/x-shockwave-flash', true) || $h.compare(vName, /\.(swf)$/i);
                    },
                    pdf: function (vType, vName) {
                        return $h.compare(vType, 'application/pdf', true) || $h.compare(vName, /\.(pdf)$/i);
                    },
                    object: function () {
                        return true;
                    },
                    other: function () {
                        return true;
                    }
                },
                fileActionSettings: {
                    showRemove: true,
                    showUpload: true,
                    showZoom: true,
                    showDrag: true,
                    removeIcon: '<i class="fa fa-trash text-danger" style="font-size:20px;"></i>',
                    removeClass: 'btn btn-xs btn-default',
                    removeTitle: 'Remove file',
                    dragIcon: '<i class="glyphicon glyphicon-menu-hamburger"></i>',
                    dragClass: 'text-info',
                    dragTitle: 'Move / Rearrange',
                    dragSettings: {},
                    indicatorNew: '<i class="glyphicon glyphicon-hand-down text-warning"></i>',
                    indicatorSuccess: '<i class="glyphicon glyphicon-ok-sign text-success"></i>',
                    indicatorError: '<i class="glyphicon glyphicon-exclamation-sign text-danger"></i>',
                    indicatorLoading: '<i class="glyphicon glyphicon-hand-up text-muted"></i>',
                    indicatorNewTitle: 'Not uploaded yet',
                    indicatorSuccessTitle: 'Uploaded',
                    indicatorErrorTitle: 'Upload Error',
                    indicatorLoadingTitle: 'Uploading ...'
                }
            };
            $.each(self.defaults, function (key, setting) {
                if (key === 'allowedPreviewTypes') {
                    if (self.allowedPreviewTypes === undefined) {
                        self.allowedPreviewTypes = setting;
                    }
                    return;
                }
                self[key] = $.extend(true, {}, setting, self[key]);
            });
            self._initPreviewTemplates();
        },
        _initPreviewTemplates: function () {
            var self = this, cfg = self.defaults, tags = self.previewMarkupTags, tagBef, tagAft = tags.tagAfter;
            $.each(cfg.previewContentTemplates, function (key, value) {
                if ($h.isEmpty(self.previewTemplates[key])) {
                    tagBef = tags.tagBefore2;
                    if (key === 'generic' || key === 'image' || key === 'html' || key === 'text') {
                        tagBef = tags.tagBefore1;
                    }
                    self.previewTemplates[key] = tagBef + value + tagAft;
                }
            });
        },
        _initPreviewCache: function () {
            var self = this;
            self.previewCache = {
                data: {},
                init: function () {
                    var content = self.initialPreview;
                    if (content.length > 0 && !$h.isArray(content)) {
                        content = content.split(self.initialPreviewDelimiter);
                    }
                    self.previewCache.data = {
                        content: content,
                        config: self.initialPreviewConfig,
                        tags: self.initialPreviewThumbTags
                    };
                },
                fetch: function () {
                    return self.previewCache.data.content.filter(function (n) {
                        return n !== null;
                    });
                },
                count: function (all) {
                    return !!self.previewCache.data && !!self.previewCache.data.content ?
                        (all ? self.previewCache.data.content.length : self.previewCache.fetch().length) : 0;
                },
                get: function (i, isDisabled) {
                    var ind = 'init_' + i, data = self.previewCache.data, config = data.config[i],
                        content = data.content[i], previewId = self.previewInitId + '-' + ind, out, $tmp, cat, ftr,
                        fname, ftype, frameClass, asData = $h.ifSet('previewAsData', config, self.initialPreviewAsData),
                        parseTemplate = function (cat, dat, fn, ft, id, ftr, ind, fc, t) {
                            fc = ' file-preview-initial ' + $h.SORT_CSS + (fc ? ' ' + fc : '');
                            return self._generatePreviewTemplate(cat, dat, fn, ft, id, false, null, fc, ftr, ind, t);
                        };
                    if (!content) {
                        return '';
                    }
                    isDisabled = isDisabled === undefined ? true : isDisabled;
                    cat = $h.ifSet('type', config, self.initialPreviewFileType || 'generic');
                    fname = $h.ifSet('filename', config, $h.ifSet('caption', config));
                    ftype = $h.ifSet('filetype', config, cat);
                    ftr = self.previewCache.footer(i, isDisabled, (config && config.size || null));
                    frameClass = $h.ifSet('frameClass', config);
                    if (asData) {
                        out = parseTemplate(cat, content, fname, ftype, previewId, ftr, ind, frameClass);
                    } else {
                        out = parseTemplate('generic', content, fname, ftype, previewId, ftr, ind, frameClass, cat)
                            .replace(/\{content}/g, data.content[i]);
                    }
                    if (data.tags.length && data.tags[i]) {
                        out = $h.replaceTags(out, data.tags[i]);
                    }
                    /** @namespace config.frameAttr */
                    if (!$h.isEmpty(config) && !$h.isEmpty(config.frameAttr)) {
                        $tmp = $(document.createElement('div')).html(out);
                        $tmp.find('.file-preview-initial').attr(config.frameAttr);
                        out = $tmp.html();
                        $tmp.remove();
                    }
                    return out;
                },
                add: function (content, config, tags, append) {
                    var data = self.previewCache.data, index;
                    if (!$h.isArray(content)) {
                        content = content.split(self.initialPreviewDelimiter);
                    }
                    if (append) {
                        index = data.content.push(content) - 1;
                        data.config[index] = config;
                        data.tags[index] = tags;
                    } else {
                        index = content.length - 1;
                        data.content = content;
                        data.config = config;
                        data.tags = tags;
                    }
                    self.previewCache.data = data;
                    return index;
                },
                set: function (content, config, tags, append) {
                    var data = self.previewCache.data, i, chk;
                    if (!content || !content.length) {
                        return;
                    }
                    if (!$h.isArray(content)) {
                        content = content.split(self.initialPreviewDelimiter);
                    }
                    chk = content.filter(function (n) {
                        return n !== null;
                    });
                    if (!chk.length) {
                        return;
                    }
                    if (data.content === undefined) {
                        data.content = [];
                    }
                    if (data.config === undefined) {
                        data.config = [];
                    }
                    if (data.tags === undefined) {
                        data.tags = [];
                    }
                    if (append) {
                        for (i = 0; i < content.length; i++) {
                            if (content[i]) {
                                data.content.push(content[i]);
                            }
                        }
                        for (i = 0; i < config.length; i++) {
                            if (config[i]) {
                                data.config.push(config[i]);
                            }
                        }
                        for (i = 0; i < tags.length; i++) {
                            if (tags[i]) {
                                data.tags.push(tags[i]);
                            }
                        }
                    } else {
                        data.content = content;
                        data.config = config;
                        data.tags = tags;
                    }
                    self.previewCache.data = data;
                },
                unset: function (index) {
                    var chk = self.previewCache.count();
                    if (!chk) {
                        return;
                    }
                    if (chk === 1) {
                        self.previewCache.data.content = [];
                        self.previewCache.data.config = [];
                        self.previewCache.data.tags = [];
                        self.initialPreview = [];
                        self.initialPreviewConfig = [];
                        self.initialPreviewThumbTags = [];
                        return;
                    }
                    self.previewCache.data.content[index] = null;
                    self.previewCache.data.config[index] = null;
                    self.previewCache.data.tags[index] = null;
                },
                out: function () {
                    var html = '', caption, len = self.previewCache.count(true), i;
                    if (len === 0) {
                        return {content: '', caption: ''};
                    }
                    for (i = 0; i < len; i++) {
                        html += self.previewCache.get(i);
                    }
                    caption = self._getMsgSelected(self.previewCache.count());
                    return {content: html, caption: caption};
                },
                footer: function (i, isDisabled, size) {
                    var data = self.previewCache.data;
                    if (!data || !data.config || data.config.length === 0 || $h.isEmpty(data.config[i])) {
                        return '';
                    }
                    isDisabled = isDisabled === undefined ? true : isDisabled;
                    var config = data.config[i], caption = $h.ifSet('caption', config), actions = '',
                        width = $h.ifSet('width', config, 'auto'), url = $h.ifSet('url', config, false),
                        key = $h.ifSet('key', config, null), fs = self.fileActionSettings,
                        showDel = $h.ifSet('showDelete', config, true), showZoom = $h.ifSet('showZoom', config, fs.showZoom),
                        showDrag = $h.ifSet('showDrag', config, fs.showDrag), disabled = (url === false) && isDisabled;
                    if (self.initialPreviewShowDelete) {
                        actions = self._renderFileActions(false, showDel, showZoom, showDrag, disabled, url, key, true);
                    }
                    return self._getLayoutTemplate('footer').replace(/\{progress}/g, self._renderThumbProgress())
                        .replace(/\{actions}/g, actions).replace(/\{caption}/g, caption)
                        .replace(/\{size}/g, self._getSize(size)).replace(/\{width}/g, width)
                        .replace(/\{indicator}/g, '').replace(/\{indicatorTitle}/g, '');
                }
            };
            self.previewCache.init();
        },
        _handler: function ($el, event, callback) {
            var self = this, ns = self.namespace, ev = event.split(' ').join(ns + ' ') + ns;
            if (!$el || !$el.length) {
                return;
            }
            $el.off(ev).on(ev, callback);
        },
        _log: function (msg) {
            var self = this, id = self.$element.attr('id');
            if (id) {
                msg = '"' + id + '": ' + msg;
            }
            if (typeof window.console.log !== "undefined") {
                window.console.log(msg);
            } else {
                window.alert(msg);
            }
        },
        _validate: function () {
            var self = this, status = self.$element.attr('type') === 'file';
            if (!status) {
                self._log('The input "type" must be set to "file" for initializing the "bootstrap-fileinput" plugin.');
            }
            return status;
        },
        _errorsExist: function () {
            var self = this, $err;
            if (self.$errorContainer.find('li').length) {
                return true;
            }
            $err = $(document.createElement('div')).html(self.$errorContainer.html());
            $err.find('span.kv-error-close').remove();
            $err.find('ul').remove();
            return $.trim($err.text()).length ? true : false;
        },
        _errorHandler: function (evt, caption) {
            var self = this, err = evt.target.error;
            /** @namespace err.NOT_FOUND_ERR */
            /** @namespace err.SECURITY_ERR */
            /** @namespace err.NOT_READABLE_ERR */
            if (err.code === err.NOT_FOUND_ERR) {
                self._showError(self.msgFileNotFound.replace('{name}', caption));
            } else if (err.code === err.SECURITY_ERR) {
                self._showError(self.msgFileSecured.replace('{name}', caption));
            } else if (err.code === err.NOT_READABLE_ERR) {
                self._showError(self.msgFileNotReadable.replace('{name}', caption));
            } else if (err.code === err.ABORT_ERR) {
                self._showError(self.msgFilePreviewAborted.replace('{name}', caption));
            } else {
                self._showError(self.msgFilePreviewError.replace('{name}', caption));
            }
        },
        _addError: function (msg) {
            var self = this, $error = self.$errorContainer;
            if (msg && $error.length) {
                $error.html(self.errorCloseButton + msg);
                self._handler($error.find('.kv-error-close'), 'click', function () {
                    $error.fadeOut('slow');
                });
            }
        },
        _resetErrors: function (fade) {
            var self = this, $error = self.$errorContainer;
            self.isError = false;
            self.$container.removeClass('has-error');
            $error.html('');
            if (fade) {
                $error.fadeOut('slow');
            } else {
                $error.hide();
            }
        },
        _showFolderError: function (folders) {
            var self = this, $error = self.$errorContainer, msg;
            if (!folders) {
                return;
            }
            msg = self.msgFoldersNotAllowed.replace(/\{n}/g, folders);
            self._addError(msg);
            $h.addCss(self.$container, 'has-error');
            $error.fadeIn(800);
            self._raise('filefoldererror', [folders, msg]);
        },
        _showUploadError: function (msg, params, event) {
            var self = this, $error = self.$errorContainer, ev = event || 'fileuploaderror', e = params && params.id ?
            '<li data-file-id="' + params.id + '">' + msg + '</li>' : '<li>' + msg + '</li>';
            if ($error.find('ul').length === 0) {
                self._addError('<ul>' + e + '</ul>');
            } else {
                $error.find('ul').append(e);
            }
            $error.fadeIn(800);
            self._raise(ev, [params, msg]);
            self.$container.removeClass('file-input-new');
            $h.addCss(self.$container, 'has-error');
            return true;
        },
        _showError: function (msg, params, event) {
            var self = this, $error = self.$errorContainer, ev = event || 'fileerror';
            params = params || {};
            params.reader = self.reader;
            self._addError(msg);
            $error.fadeIn(800);
            self._raise(ev, [params, msg]);
            if (!self.isUploadable) {
                self._clearFileInput();
            }
            self.$container.removeClass('file-input-new');
            $h.addCss(self.$container, 'has-error');
            self.$btnUpload.attr('disabled', true);
            return true;
        },
        _noFilesError: function (params) {
            var self = this, label = self.minFileCount > 1 ? self.filePlural : self.fileSingle,
                msg = self.msgFilesTooLess.replace('{n}', self.minFileCount).replace('{files}', label),
                $error = self.$errorContainer;
            self._addError(msg);
            self.isError = true;
            self._updateFileDetails(0);
            $error.fadeIn(800);
            self._raise('fileerror', [params, msg]);
            self._clearFileInput();
            $h.addCss(self.$container, 'has-error');
        },
        _parseError: function (operation, jqXHR, errorThrown, fileName) {
            /** @namespace jqXHR.responseJSON */
            var self = this, errMsg = $.trim(errorThrown + ''), dot = errMsg.slice(-1) === '.' ? '' : '.',
                text = jqXHR.responseJSON !== undefined && jqXHR.responseJSON.error !== undefined ?
                    jqXHR.responseJSON.error : jqXHR.responseText;
            if (self.cancelling && self.msgUploadAborted) {
                errMsg = self.msgUploadAborted;
            }
            if (self.showAjaxErrorDetails && text) {
                text = $.trim(text.replace(/\n\s*\n/g, '\n'));
                text = text.length > 0 ? '<pre>' + text + '</pre>' : '';
                errMsg += dot + text;
            } else {
                errMsg += dot;
            }
            if (errMsg === dot) {
                errMsg = self.msgAjaxError.replace('{operation}', operation);
            }
            self.cancelling = false;
            return fileName ? '<b>' + fileName + ': </b>' + errMsg : errMsg;
        },
        _parseFileType: function (file) {
            var self = this, isValid, vType, cat, i, types = self.allowedPreviewTypes || [];
            for (i = 0; i < types.length; i++) {
                cat = types[i];
                isValid = self.fileTypeSettings[cat];
                vType = isValid(file.type, file.name) ? cat : '';
                if (!$h.isEmpty(vType)) {
                    return vType;
                }
            }
            return 'other';
        },
        _getPreviewIcon: function (fname) {
            var self = this, ext, out = null;
            if (fname && fname.indexOf('.') > -1) {
                ext = fname.split('.').pop();
                if (self.previewFileIconSettings && self.previewFileIconSettings[ext]) {
                    out = self.previewFileIconSettings[ext];
                }
                if (self.previewFileExtSettings) {
                    $.each(self.previewFileExtSettings, function (key, func) {
                        if (self.previewFileIconSettings[key] && func(ext)) {
                            out = self.previewFileIconSettings[key];
                            //noinspection UnnecessaryReturnStatementJS
                            return;
                        }
                    });
                }
            }
            return out;
        },
        _parseFilePreviewIcon: function (content, fname) {
            var self = this, icn = self._getPreviewIcon(fname) || self.previewFileIcon;
            if (content.indexOf('{previewFileIcon}') > -1) {
                content = content.replace(/\{previewFileIconClass}/g, self.previewFileIconClass).replace(
                    /\{previewFileIcon}/g, icn);
            }
            return content;
        },
        _raise: function (event, params) {
            var self = this, e = $.Event(event);
            if (params !== undefined) {
                self.$element.trigger(e, params);
            } else {
                self.$element.trigger(e);
            }
            if (e.isDefaultPrevented() || e.result === false) {
                return false;
            }
            switch (event) {
                // ignore these events
                case 'filebatchuploadcomplete':
                case 'filebatchuploadsuccess':
                case 'fileuploaded':
                case 'fileclear':
                case 'filecleared':
                case 'filereset':
                case 'fileerror':
                case 'filefoldererror':
                case 'fileuploaderror':
                case 'filebatchuploaderror':
                case 'filedeleteerror':
                case 'filecustomerror':
                case 'filesuccessremove':
                    break;
                // receive data response via `filecustomerror` event`
                default:
                    self.ajaxAborted = e.result;
                    break;
            }
            return true;
        },
        _listenFullScreen: function (isFullScreen) {
            var self = this, $modal = self.$modal, $btnFull, $btnBord;
            if (!$modal || !$modal.length) {
                return;
            }
            $btnFull = $modal && $modal.find('.btn-fullscreen');
            $btnBord = $modal && $modal.find('.btn-borderless');
            if (!$btnFull.length || !$btnBord.length) {
                return;
            }
            $btnFull.removeClass('active').attr('aria-pressed', 'false');
            $btnBord.removeClass('active').attr('aria-pressed', 'false');
            if (isFullScreen) {
                $btnFull.addClass('active').attr('aria-pressed', 'true');
            } else {
                $btnBord.addClass('active').attr('aria-pressed', 'true');
            }
            if ($modal.hasClass('file-zoom-fullscreen')) {
                self._maximizeZoomDialog();
            } else {
                if (isFullScreen) {
                    self._maximizeZoomDialog();
                } else {
                    $btnBord.removeClass('active').attr('aria-pressed', 'false');
                }
            }
        },
        _listen: function () {
            var self = this, $el = self.$element, $form = self.$form, $cont = self.$container, fullScreenEvents;
            self._handler($el, 'change', $.proxy(self._change, self));
            if (self.showBrowse) {
                self._handler(self.$btnFile, 'click', $.proxy(self._browse, self));
            }
            self._handler($cont.find('.fileinput-remove:not([disabled])'), 'click', $.proxy(self.clear, self));
            self._handler($cont.find('.fileinput-cancel'), 'click', $.proxy(self.cancel, self));
            self._initDragDrop();
            self._handler($form, 'reset', $.proxy(self.reset, self));
            if (!self.isUploadable) {
                self._handler($form, 'submit', $.proxy(self._submitForm, self));
            }
            self._handler(self.$container.find('.fileinput-upload'), 'click', $.proxy(self._uploadClick, self));
            self._handler($(window), 'resize', function () {
                self._listenFullScreen(screen.width === window.innerWidth && screen.height === window.innerHeight);
            });
            fullScreenEvents = 'webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange';
            self._handler($(document), fullScreenEvents, function () {
                self._listenFullScreen($h.checkFullScreen());
            });
            self._initClickable();
        },
        _initClickable: function () {
            var self = this, $zone;
            if (!self.isClickable) {
                return;
            }
            $zone = self.isUploadable ? self.$dropZone : self.$preview.find('.file-default-preview');
            $h.addCss($zone, 'clickable');
            $zone.attr('tabindex', -1);
            self._handler($zone, 'click', function (e) {
                var $tar = $(e.target);
                if (!$tar.parents('.file-preview-thumbnails').length || $tar.parents('.file-default-preview').length) {
                    self.$element.trigger('click');
                    $zone.blur();
                }
            });
        },
        _initDragDrop: function () {
            var self = this, $zone = self.$dropZone;
            if (self.isUploadable && self.dropZoneEnabled && self.showPreview) {
                self._handler($zone, 'dragenter dragover', $.proxy(self._zoneDragEnter, self));
                self._handler($zone, 'dragleave', $.proxy(self._zoneDragLeave, self));
                self._handler($zone, 'drop', $.proxy(self._zoneDrop, self));
                self._handler($(document), 'dragenter dragover drop', self._zoneDragDropInit);
            }
        },
        _zoneDragDropInit: function (e) {
            e.stopPropagation();
            e.preventDefault();
        },
        _zoneDragEnter: function (e) {
            var self = this, hasFiles = $.inArray('Files', e.originalEvent.dataTransfer.types) > -1;
            self._zoneDragDropInit(e);
            if (self.isDisabled || !hasFiles) {
                e.originalEvent.dataTransfer.effectAllowed = 'none';
                e.originalEvent.dataTransfer.dropEffect = 'none';
                return;
            }
            $h.addCss(self.$dropZone, 'file-highlighted');
        },
        _zoneDragLeave: function (e) {
            var self = this;
            self._zoneDragDropInit(e);
            if (self.isDisabled) {
                return;
            }
            self.$dropZone.removeClass('file-highlighted');
        },
        _zoneDrop: function (e) {
            var self = this;
            e.preventDefault();
            /** @namespace e.originalEvent.dataTransfer */
            if (self.isDisabled || $h.isEmpty(e.originalEvent.dataTransfer.files)) {
                return;
            }
            self._change(e, 'dragdrop');
            self.$dropZone.removeClass('file-highlighted');
        },
        _uploadClick: function (e) {
            var self = this, $btn = self.$container.find('.fileinput-upload'), $form,
                isEnabled = !$btn.hasClass('disabled') && $h.isEmpty($btn.attr('disabled'));
            if (e && e.isDefaultPrevented()) {
                return;
            }
            if (!self.isUploadable) {
                if (isEnabled && $btn.attr('type') !== 'submit') {
                    $form = $btn.closest('form');
                    // downgrade to normal form submit if possible
                    if ($form.length) {
                        $form.trigger('submit');
                    }
                    e.preventDefault();
                }
                return;
            }
            e.preventDefault();
            if (isEnabled) {
                self.upload();
            }
        },
        _submitForm: function () {
            var self = this, $el = self.$element, files = $el.get(0).files;
            if (files && self.minFileCount > 0 && self._getFileCount(files.length) < self.minFileCount) {
                self._noFilesError({});
                return false;
            }
            return !self._abort({});
        },
        _clearPreview: function () {
            var self = this, $p = self.$preview,
                $thumbs = self.showUploadedThumbs ? $p.find($h.FRAMES + ':not(.file-preview-success)') : $p.find($h.FRAMES);
            $thumbs.each(function () {
                var $thumb = $(this);
                $thumb.remove();
                $h.cleanZoomCache($p.find('#zoom-' + $thumb.attr('id')));
            });
            if (!self.$preview.find($h.FRAMES).length || !self.showPreview) {
                self._resetUpload();
            }
            self._validateDefaultPreview();
        },
        _initSortable: function () {
            var self = this, $el = self.$preview, settings, selector = '.' + $h.SORT_CSS;
            if (!window.KvSortable || $el.find(selector).length === 0) {
                return;
            }
            //noinspection JSUnusedGlobalSymbols
            settings = {
                handle: '.drag-handle-init',
                dataIdAttr: 'data-preview-id',
                draggable: selector,
                onSort: function (e) {
                    var oldIndex = e.oldIndex, newIndex = e.newIndex, key, $frame;
                    self.initialPreview = $h.moveArray(self.initialPreview, oldIndex, newIndex);
                    self.initialPreviewConfig = $h.moveArray(self.initialPreviewConfig, oldIndex, newIndex);
                    self.previewCache.init();
                    for (var i = 0; i < self.initialPreviewConfig.length; i++) {
                        if (self.initialPreviewConfig[i] !== null) {
                            key = self.initialPreviewConfig[i].key;
                            $frame = $(".kv-file-remove[data-key='" + key + "']").closest($h.FRAMES);
                            $frame.attr('data-fileindex', 'init_' + i).data('fileindex', 'init_' + i);
                        }
                    }
                    self._raise('filesorted', {
                        previewId: $(e.item).attr('id'),
                        'oldIndex': oldIndex,
                        'newIndex': newIndex,
                        stack: self.initialPreviewConfig
                    });
                }
            };
            if ($el.data('kvsortable')) {
                $el.kvsortable('destroy');
            }
            $.extend(true, settings, self.fileActionSettings.dragSettings);
            $el.kvsortable(settings);
        },
        _initPreview: function (isInit) {
            var self = this, cap = self.initialCaption || '', out;
            if (!self.previewCache.count()) {
                self._clearPreview();
                if (isInit) {
                    self._setCaption(cap);
                } else {
                    self._initCaption();
                }
                return;
            }
            out = self.previewCache.out();
            cap = isInit && self.initialCaption ? self.initialCaption : out.caption;
            self.$preview.html(out.content);
            self._setInitThumbAttr();
            self._setCaption(cap);
            self._initSortable();
            if (!$h.isEmpty(out.content)) {
                self.$container.removeClass('file-input-new');
            }
        },
        _getZoomButton: function (type) {
            var self = this, label = self.previewZoomButtonIcons[type], css = self.previewZoomButtonClasses[type],
                title = ' title="' + (self.previewZoomButtonTitles[type] || '') + '" ',
                params = title + (type === 'close' ? ' data-dismiss="modal" aria-hidden="true"' : '');
            if (type === 'fullscreen' || type === 'borderless' || type === 'toggleheader') {
                params += ' data-toggle="button" aria-pressed="false" autocomplete="off"';
            }
            return '<button type="button" class="' + css + ' btn-' + type + '"' + params + '>' + label + '</button>';
        },
        _getModalContent: function () {
            var self = this;
            return self._getLayoutTemplate('modal')
                .replace(/\{zoomFrameClass}/g, self.frameClass)
                .replace(/\{heading}/g, self.msgZoomModalHeading)
                .replace(/\{prev}/g, self._getZoomButton('prev'))
                .replace(/\{next}/g, self._getZoomButton('next'))
                .replace(/\{toggleheader}/g, self._getZoomButton('toggleheader'))
                .replace(/\{fullscreen}/g, self._getZoomButton('fullscreen'))
                .replace(/\{borderless}/g, self._getZoomButton('borderless'))
                .replace(/\{close}/g, self._getZoomButton('close'));
        },
        _listenModalEvent: function (event) {
            var self = this, $modal = self.$modal, getParams = function (e) {
                return {
                    sourceEvent: e,
                    previewId: $modal.data('previewId'),
                    modal: $modal
                };
            };
            $modal.on(event + '.bs.modal', function (e) {
                var $btnFull = $modal.find('.btn-fullscreen'), $btnBord = $modal.find('.btn-borderless');
                self._raise('filezoom' + event, getParams(e));
                if (event === 'shown') {
                    $btnBord.removeClass('active').attr('aria-pressed', 'false');
                    $btnFull.removeClass('active').attr('aria-pressed', 'false');
                    if ($modal.hasClass('file-zoom-fullscreen')) {
                        self._maximizeZoomDialog();
                        if ($h.checkFullScreen()) {
                            $btnFull.addClass('active').attr('aria-pressed', 'true');
                        } else {
                            $btnBord.addClass('active').attr('aria-pressed', 'true');
                        }
                    }
                }
            });
        },
        _initZoom: function () {
            var self = this, $dialog, modalMain = self._getLayoutTemplate('modalMain'), modalId = '#' + $h.MODAL_ID;
            if (!self.showPreview) {
                return;
            }
            self.$modal = $(modalId);
            if (!self.$modal || !self.$modal.length) {
                $dialog = $(document.createElement('div')).html(modalMain).insertAfter(self.$container);
                self.$modal = $(modalId).insertBefore($dialog);
                $dialog.remove();
            }
            $h.initModal(self.$modal);
            self.$modal.html(self._getModalContent());
            $.each($h.MODAL_EVENTS, function (key, event) {
                self._listenModalEvent(event);
            });
        },
        _initZoomButtons: function () {
            var self = this, previewId = self.$modal.data('previewId') || '', $first, $last, $preview = self.$preview,
                thumbs = $preview.find($h.FRAMES).toArray(), len = thumbs.length,
                $prev = self.$modal.find('.btn-prev'), $next = self.$modal.find('.btn-next');
            if (thumbs.length < 2) {
                $prev.hide();
                $next.hide();
                return;
            } else {
                $prev.show();
                $next.show();
            }
            if (!len) {
                return;
            }
            $first = $(thumbs[0]);
            $last = $(thumbs[len - 1]);
            $prev.removeAttr('disabled');
            $next.removeAttr('disabled');
            if ($first.length && $first.attr('id') === previewId) {
                $prev.attr('disabled', true);
            }
            if ($last.length && $last.attr('id') === previewId) {
                $next.attr('disabled', true);
            }
        },
        _maximizeZoomDialog: function () {
            var self = this, $modal = self.$modal, $head = $modal.find('.modal-header:visible'),
                $foot = $modal.find('.modal-footer:visible'), $body = $modal.find('.modal-body'),
                h = $(window).height(), diff = 0;
            $modal.addClass('file-zoom-fullscreen');
            if ($head && $head.length) {
                h -= $head.outerHeight(true);
            }
            if ($foot && $foot.length) {
                h -= $foot.outerHeight(true);
            }
            if ($body && $body.length) {
                diff = $body.outerHeight(true) - $body.height();
                h -= diff;
            }
            $modal.find('.kv-zoom-body').height(h);
        },
        _resizeZoomDialog: function (fullScreen) {
            var self = this, $modal = self.$modal, $btnFull = $modal.find('.btn-fullscreen'),
                $btnBord = $modal.find('.btn-borderless');
            if ($modal.hasClass('file-zoom-fullscreen')) {
                $h.toggleFullScreen(false);
                if (!fullScreen) {
                    if (!$btnFull.hasClass('active')) {
                        $modal.removeClass('file-zoom-fullscreen');
                        self.$modal.find('.kv-zoom-body').css('height', self.zoomModalHeight);
                    } else {
                        $btnFull.removeClass('active').attr('aria-pressed', 'false');
                    }
                } else {
                    if (!$btnFull.hasClass('active')) {
                        $modal.removeClass('file-zoom-fullscreen');
                        self._resizeZoomDialog(true);
                        if ($btnBord.hasClass('active')) {
                            $btnBord.removeClass('active').attr('aria-pressed', 'false');
                        }
                    }
                }
            } else {
                if (!fullScreen) {
                    self._maximizeZoomDialog();
                    return;
                }
                $h.toggleFullScreen(true);
            }
            $modal.focus();
        },
        _setZoomContent: function ($frame, animate) {
            var self = this, $content, tmplt, body, title, $body, $dataEl, config, pid = $frame.attr('id'),
                $modal = self.$modal, $prev = $modal.find('.btn-prev'), $next = $modal.find('.btn-next'), $tmp,
                $btnFull = $modal.find('.btn-fullscreen'), $btnBord = $modal.find('.btn-borderless'), cap, size,
                $btnTogh = $modal.find('.btn-toggleheader'), $zoomPreview = self.$preview.find('#zoom-' + pid);
            tmplt = $zoomPreview.attr('data-template') || 'generic';
            $content = $zoomPreview.find('.kv-file-content');
            body = $content.length ? $content.html() : '';
            cap = $frame.data('caption') || '';
            size = $frame.data('size') || '';
            title = cap + ' ' + size;
            $modal.find('.kv-zoom-title').html(title);
            $body = $modal.find('.kv-zoom-body');
            $modal.removeClass('kv-single-content');
            if (animate) {
                $tmp = $body.clone().insertAfter($body);
                $body.html(body).hide();
                $tmp.fadeOut('fast', function () {
                    $body.fadeIn('fast');
                    $tmp.remove();
                });
            } else {
                $body.html(body);
            }
            config = self.previewZoomSettings[tmplt];
            if (config) {
                $dataEl = $body.find('.kv-preview-data');
                $h.addCss($dataEl, 'file-zoom-detail');
                $.each(config, function (key, value) {
                    $dataEl.css(key, value);
                    if (($dataEl.attr('width') && key === 'width') || ($dataEl.attr('height') && key === 'height')) {
                        $dataEl.removeAttr(key);
                    }
                });
            }
            $modal.data('previewId', pid);
            self._handler($prev, 'click', function () {
                self._zoomSlideShow('prev', pid);
            });
            self._handler($next, 'click', function () {
                self._zoomSlideShow('next', pid);
            });
            self._handler($btnFull, 'click', function () {
                self._resizeZoomDialog(true);
            });
            self._handler($btnBord, 'click', function () {
                self._resizeZoomDialog(false);
            });
            self._handler($btnTogh, 'click', function () {
                var $header = $modal.find('.modal-header'), $floatBar = $modal.find('.modal-body .floating-buttons'),
                    ht, $actions = $header.find('.kv-zoom-actions'), resize = function (height) {
                        var $body = self.$modal.find('.kv-zoom-body'), h = self.zoomModalHeight;
                        if ($modal.hasClass('file-zoom-fullscreen')) {
                            h = $body.outerHeight(true);
                            if (!height) {
                                h = h - $header.outerHeight(true);
                            }
                        }
                        $body.css('height', height ? h + height : h);
                    };
                if ($header.is(':visible')) {
                    ht = $header.outerHeight(true);
                    $header.slideUp('slow', function () {
                        $actions.find('.btn').appendTo($floatBar);
                        resize(ht);
                    });
                } else {
                    $floatBar.find('.btn').appendTo($actions);
                    $header.slideDown('slow', function () {
                        resize();
                    });
                }
                $modal.focus();
            });
            self._handler($modal, 'keydown', function (e) {
                var key = e.which || e.keyCode;
                if (key === 37 && !$prev.attr('disabled')) {
                    self._zoomSlideShow('prev', pid);
                }
                if (key === 39 && !$next.attr('disabled')) {
                    self._zoomSlideShow('next', pid);
                }
            });
        },
        _zoomPreview: function ($btn) {
            var self = this, $frame, $modal = self.$modal;
            if (!$btn.length) {
                throw 'Cannot zoom to detailed preview!';
            }
            $h.initModal($modal);
            $modal.html(self._getModalContent());
            $frame = $btn.closest($h.FRAMES);
            self._setZoomContent($frame);
            $modal.modal('show');
            self._initZoomButtons();
        },
        _zoomSlideShow: function (dir, previewId) {
            var self = this, $btn = self.$modal.find('.kv-zoom-actions .btn-' + dir), $targFrame, i,
                thumbs = self.$preview.find($h.FRAMES).toArray(), len = thumbs.length, out;
            if ($btn.attr('disabled')) {
                return;
            }
            for (i = 0; i < len; i++) {
                if ($(thumbs[i]).attr('id') === previewId) {
                    out = dir === 'prev' ? i - 1 : i + 1;
                    break;
                }
            }
            if (out < 0 || out >= len || !thumbs[out]) {
                return;
            }
            $targFrame = $(thumbs[out]);
            if ($targFrame.length) {
                self._setZoomContent($targFrame, true);
            }
            self._initZoomButtons();
            self._raise('filezoom' + dir, {'previewId': previewId, modal: self.$modal});
        },
        _initZoomButton: function () {
            var self = this;
            self.$preview.find('.kv-file-zoom').each(function () {
                var $el = $(this);
                self._handler($el, 'click', function () {
                    self._zoomPreview($el);
                });
            });
        },
        _clearObjects: function ($el) {
            $el.find('video audio').each(function () {
                this.pause();
                $(this).remove();
            });
            $el.find('img object div').each(function () {
                $(this).remove();
            });
        },
        _clearFileInput: function () {
            var self = this, $el = self.$element, $srcFrm, $tmpFrm, $tmpEl;
            self.fileInputCleared = true;
            if ($h.isEmpty($el.val())) {
                return;
            }
            // Fix for IE ver < 11, that does not clear file inputs. Requires a sequence of steps to prevent IE
            // crashing but still allow clearing of the file input.
            if (self.isIE9 || self.isIE10) {
                $srcFrm = $el.closest('form');
                $tmpFrm = $(document.createElement('form'));
                $tmpEl = $(document.createElement('div'));
                $el.before($tmpEl);
                if ($srcFrm.length) {
                    $srcFrm.after($tmpFrm);
                } else {
                    $tmpEl.after($tmpFrm);
                }
                $tmpFrm.append($el).trigger('reset');
                $tmpEl.before($el).remove();
                $tmpFrm.remove();
            } else { // normal input clear behavior for other sane browsers
                $el.val('');
            }
        },
        _resetUpload: function () {
            var self = this;
            self.uploadCache = {content: [], config: [], tags: [], append: true};
            self.uploadCount = 0;
            self.uploadStatus = {};
            self.uploadLog = [];
            self.uploadAsyncCount = 0;
            self.loadedImages = [];
            self.totalImagesCount = 0;
            self.$btnUpload.removeAttr('disabled');
            self._setProgress(0);
            $h.addCss(self.$progress, 'hide');
            self._resetErrors(false);
            self.ajaxAborted = false;
            self.ajaxRequests = [];
            self._resetCanvas();
            self.cacheInitialPreview = {};
            if (self.overwriteInitial) {
                self.initialPreview = [];
                self.initialPreviewConfig = [];
                self.initialPreviewThumbTags = [];
                self.previewCache.data = {
                    content: [],
                    config: [],
                    tags: []
                };
            }
        },
        _resetCanvas: function () {
            var self = this;
            if (self.canvas && self.imageCanvasContext) {
                self.imageCanvasContext.clearRect(0, 0, self.canvas.width, self.canvas.height);
            }
        },
        _hasInitialPreview: function () {
            var self = this;
            return !self.overwriteInitial && self.previewCache.count();
        },
        _resetPreview: function () {
            var self = this, out, cap;
            if (self.previewCache.count()) {
                out = self.previewCache.out();
                self.$preview.html(out.content);
                self._setInitThumbAttr();
                cap = self.initialCaption ? self.initialCaption : out.caption;
                self._setCaption(cap);
            } else {
                self._clearPreview();
                self._initCaption();
            }
            if (self.showPreview) {
                self._initZoom();
                self._initSortable();
            }
        },
        _clearDefaultPreview: function () {
            var self = this;
            self.$preview.find('.file-default-preview').remove();
        },
        _validateDefaultPreview: function () {
            var self = this;
            if (!self.showPreview || $h.isEmpty(self.defaultPreviewContent)) {
                return;
            }
            self.$preview.html('<div class="file-default-preview">' + self.defaultPreviewContent + '</div>');
            self.$container.removeClass('file-input-new');
            self._initClickable();
        },
        _resetPreviewThumbs: function (isAjax) {
            var self = this, out;
            if (isAjax) {
                self._clearPreview();
                self.clearStack();
                return;
            }
            if (self._hasInitialPreview()) {
                out = self.previewCache.out();
                self.$preview.html(out.content);
                self._setInitThumbAttr();
                self._setCaption(out.caption);
                self._initPreviewActions();
            } else {
                self._clearPreview();
            }
        },
        _getLayoutTemplate: function (t) {
            var self = this, template = self.layoutTemplates[t];
            if ($h.isEmpty(self.customLayoutTags)) {
                return template;
            }
            return $h.replaceTags(template, self.customLayoutTags);
        },
        _getPreviewTemplate: function (t) {
            var self = this, template = self.previewTemplates[t];
            if ($h.isEmpty(self.customPreviewTags)) {
                return template;
            }
            return $h.replaceTags(template, self.customPreviewTags);
        },
        _getOutData: function (jqXHR, responseData, filesData) {
            var self = this;
            jqXHR = jqXHR || {};
            responseData = responseData || {};
            filesData = filesData || self.filestack.slice(0) || {};
            return {
                form: self.formdata,
                files: filesData,
                filenames: self.filenames,
                filescount: self.getFilesCount(),
                extra: self._getExtraData(),
                response: responseData,
                reader: self.reader,
                jqXHR: jqXHR
            };
        },
        _getMsgSelected: function (n) {
            var self = this, strFiles = n === 1 ? self.fileSingle : self.filePlural;
            return n > 0 ? self.msgSelected.replace('{n}', n).replace('{files}', strFiles) : self.msgNoFilesSelected;
        },
        _getThumbs: function (css) {
            css = css || '';
            return this.$preview.find($h.FRAMES + ':not(.file-preview-initial)' + css);
        },
        _getExtraData: function (previewId, index) {
            var self = this, data = self.uploadExtraData;
            if (typeof self.uploadExtraData === "function") {
                data = self.uploadExtraData(previewId, index);
            }
            return data;
        },
        _initXhr: function (xhrobj, previewId, fileCount) {
            var self = this;
            if (xhrobj.upload) {
                xhrobj.upload.addEventListener('progress', function (event) {
                    var pct = 0, total = event.total, position = event.loaded || event.position;
                    /** @namespace event.lengthComputable */
                    if (event.lengthComputable) {
                        pct = Math.floor(position / total * 100);
                    }
                    if (previewId) {
                        self._setAsyncUploadStatus(previewId, pct, fileCount);
                    } else {
                        self._setProgress(pct);
                    }
                }, false);
            }
            return xhrobj;
        },
        _ajaxSubmit: function (fnBefore, fnSuccess, fnComplete, fnError, previewId, index) {
            var self = this, settings;
            if (!self._raise('filepreajax', [previewId, index])) {
                return;
            }
            self._uploadExtra(previewId, index);
            settings = $.extend(true, {}, {
                xhr: function () {
                    var xhrobj = $.ajaxSettings.xhr();
                    return self._initXhr(xhrobj, previewId, self.getFileStack().length);
                },
                url: self.uploadUrl,
                type: 'POST',
                dataType: 'json',
                data: self.formdata,
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: fnBefore,
                success: fnSuccess,
                complete: fnComplete,
                error: fnError
            }, self.ajaxSettings);
            self.ajaxRequests.push($.ajax(settings));
        },
        _mergeArray: function (prop, content) {
            var self = this, arr1 = $h.cleanArray(self[prop]), arr2 = $h.cleanArray(content);
            self[prop] = arr1.concat(arr2);
        },
        _initUploadSuccess: function (out, $thumb, allFiles) {
            var self = this, append, data, index, $div, $newCache, content, config, tags, i;
            if (!self.showPreview || typeof out !== 'object' || $.isEmptyObject(out)) {
                return;
            }
            if (out.initialPreview !== undefined && out.initialPreview.length > 0) {
                self.hasInitData = true;
                content = out.initialPreview || [];
                config = out.initialPreviewConfig || [];
                tags = out.initialPreviewThumbTags || [];
                append = out.append === undefined || out.append ? true : false;
                if (content.length > 0 && !$h.isArray(content)) {
                    content = content.split(self.initialPreviewDelimiter);
                }
                self._mergeArray('initialPreview', content);
                self._mergeArray('initialPreviewConfig', config);
                self._mergeArray('initialPreviewThumbTags', tags);
                if ($thumb !== undefined) {
                    if (!allFiles) {
                        index = self.previewCache.add(content, config[0], tags[0], append);
                        data = self.previewCache.get(index, false);
                        $div = $(document.createElement('div')).html(data).hide().insertAfter($thumb);
                        $newCache = $div.find('.kv-zoom-cache');
                        if ($newCache && $newCache.length) {
                            $newCache.insertAfter($thumb);
                        }
                        $thumb.fadeOut('slow', function () {
                            var $newThumb = $div.find('.file-preview-frame');
                            if ($newThumb && $newThumb.length) {
                                $newThumb.insertBefore($thumb).fadeIn('slow').css('display:inline-block');
                            }
                            self._initPreviewActions();
                            self._clearFileInput();
                            $h.cleanZoomCache(self.$preview.find('#zoom-' + $thumb.attr('id')));
                            $thumb.remove();
                            $div.remove();
                            self._initSortable();
                        });
                    } else {
                        i = $thumb.attr('data-fileindex');
                        self.uploadCache.content[i] = content[0];
                        self.uploadCache.config[i] = config[0] || [];
                        self.uploadCache.tags[i] = tags[0] || [];
                        self.uploadCache.append = append;
                    }
                } else {
                    self.previewCache.set(content, config, tags, append);
                    self._initPreview();
                    self._initPreviewActions();
                }
            }
        },
        _initSuccessThumbs: function () {
            var self = this;
            if (!self.showPreview) {
                return;
            }
            self._getThumbs($h.FRAMES + '.file-preview-success').each(function () {
                var $thumb = $(this), $preview = self.$preview, $remove = $thumb.find('.kv-file-remove');
                $remove.removeAttr('disabled');
                self._handler($remove, 'click', function () {
                    var id = $thumb.attr('id'), out = self._raise('filesuccessremove', [id, $thumb.data('fileindex')]);
                    $h.cleanMemory($thumb);
                    if (out === false) {
                        return;
                    }
                    $thumb.fadeOut('slow', function () {
                        $h.cleanZoomCache($preview.find('#zoom-' + id));
                        $thumb.remove();
                        if (!$preview.find($h.FRAMES).length) {
                            self.reset();
                        }
                    });
                });
            });
        },
        _checkAsyncComplete: function () {
            var self = this, previewId, i;
            for (i = 0; i < self.filestack.length; i++) {
                if (self.filestack[i]) {
                    previewId = self.previewInitId + "-" + i;
                    if ($.inArray(previewId, self.uploadLog) === -1) {
                        return false;
                    }
                }
            }
            return (self.uploadAsyncCount === self.uploadLog.length);
        },
        _uploadExtra: function (previewId, index) {
            var self = this, data = self._getExtraData(previewId, index);
            if (data.length === 0) {
                return;
            }
            $.each(data, function (key, value) {
                self.formdata.append(key, value);
            });
        },
        _uploadSingle: function (i, files, allFiles) {
            var self = this, total = self.getFileStack().length, formdata = new FormData(), outData,
                previewId = self.previewInitId + "-" + i, $thumb, chkComplete, $btnUpload, $btnDelete,
                hasPostData = self.filestack.length > 0 || !$.isEmptyObject(self.uploadExtraData),
                $prog = $('#' + previewId).find('.file-thumb-progress'),
                fnBefore, fnSuccess, fnComplete, fnError, updateUploadLog, params = {id: previewId, index: i};
            self.formdata = formdata;
            if (self.showPreview) {
                $thumb = $('#' + previewId + ':not(.file-preview-initial)');
                $btnUpload = $thumb.find('.kv-file-upload');
                $btnDelete = $thumb.find('.kv-file-remove');
                $prog.removeClass('hide');
            }
            if (total === 0 || !hasPostData || ($btnUpload && $btnUpload.hasClass('disabled')) || self._abort(params)) {
                return;
            }
            updateUploadLog = function (i, previewId) {
                self.updateStack(i, undefined);
                self.uploadLog.push(previewId);
                if (self._checkAsyncComplete()) {
                    self.fileBatchCompleted = true;
                }
            };
            chkComplete = function () {
                var u = self.uploadCache, $initThumbs, i, j, len = 0, data = self.cacheInitialPreview;
                if (!self.fileBatchCompleted) {
                    return;
                }
                if (data && data.content) {
                    len = data.content.length;
                }
                setTimeout(function () {
                    if (self.showPreview) {
                        self.previewCache.set(u.content, u.config, u.tags, u.append);
                        if (len) {
                            for (i = 0; i < u.content.length; i++) {
                                j = i + len;
                                data.content[j] = u.content[i];
                                if (data.config.length) {
                                    data.config[j] = u.config[i];
                                }
                                if (data.tags.length) {
                                    data.tags[j] = u.tags[i];
                                }
                            }
                            self.initialPreview = $h.cleanArray(data.content);
                            self.initialPreviewConfig = $h.cleanArray(data.config);
                            self.initialPreviewThumbTags = $h.cleanArray(data.tags);
                        } else {
                            self.initialPreview = u.content;
                            self.initialPreviewConfig = u.config;
                            self.initialPreviewThumbTags = u.tags;
                        }
                        self.cacheInitialPreview = {};
                        if (self.hasInitData) {
                            self._initPreview();
                            self._initPreviewActions();
                        }
                    }
                    self.unlock();
                    self._clearFileInput();
                    $initThumbs = self.$preview.find('.file-preview-initial');
                    if (self.uploadAsync && $initThumbs.length) {
                        $h.addCss($initThumbs, $h.SORT_CSS);
                        self._initSortable();
                    }
                    self._raise('filebatchuploadcomplete', [self.filestack, self._getExtraData()]);
                    self.uploadCount = 0;
                    self.uploadStatus = {};
                    self.uploadLog = [];
                    self._setProgress(101);
                }, 100);
            };
            fnBefore = function (jqXHR) {
                outData = self._getOutData(jqXHR);
                self.fileBatchCompleted = false;
                if (self.showPreview) {
                    if (!$thumb.hasClass('file-preview-success')) {
                        self._setThumbStatus($thumb, 'Loading');
                        $h.addCss($thumb, 'file-uploading');
                    }
                    $btnUpload.attr('disabled', true);
                    $btnDelete.attr('disabled', true);
                }
                if (!allFiles) {
                    self.lock();
                }
                self._raise('filepreupload', [outData, previewId, i]);
                $.extend(true, params, outData);
                if (self._abort(params)) {
                    jqXHR.abort();
                    self._setProgressCancelled();
                }
            };
            fnSuccess = function (data, textStatus, jqXHR) {
                var pid = self.showPreview && $thumb.attr('id') ? $thumb.attr('id') : previewId;
                outData = self._getOutData(jqXHR, data);
                $.extend(true, params, outData);
                setTimeout(function () {
                    if ($h.isEmpty(data) || $h.isEmpty(data.error)) {
                        if (self.showPreview) {
                            self._setThumbStatus($thumb, 'Success');
                            $btnUpload.hide();
                            self._initUploadSuccess(data, $thumb, allFiles);
                            self._setProgress(101, $prog);
                        }
                        self._raise('fileuploaded', [outData, pid, i]);
                        if (!allFiles) {
                            self.updateStack(i, undefined);
                        } else {
                            updateUploadLog(i, pid);
                        }
                    } else {
                        self._showUploadError(data.error, params);
                        self._setPreviewError($thumb, i);
                        if (allFiles) {
                            updateUploadLog(i, pid);
                        }
                    }
                }, 100);
            };
            fnComplete = function () {
                setTimeout(function () {
                    if (self.showPreview) {
                        $btnUpload.removeAttr('disabled');
                        $btnDelete.removeAttr('disabled');
                        $thumb.removeClass('file-uploading');
                    }
                    if (!allFiles) {
                        self.unlock(false);
                        self._clearFileInput();
                    } else {
                        chkComplete();
                    }
                    self._initSuccessThumbs();
                }, 100);
            };
            fnError = function (jqXHR, textStatus, errorThrown) {
                var op = self.ajaxOperations.uploadThumb,
                    errMsg = self._parseError(op, jqXHR, errorThrown, (allFiles ? files[i].name : null));
                setTimeout(function () {
                    if (allFiles) {
                        updateUploadLog(i, previewId);
                    }
                    self.uploadStatus[previewId] = 100;
                    self._setPreviewError($thumb, i);
                    $.extend(true, params, self._getOutData(jqXHR));
                    self._setProgress(101, $prog, self.msgAjaxProgressError.replace('{operation}', op));
                    self._showUploadError(errMsg, params);
                }, 100);
            };
            formdata.append(self.uploadFileAttr, files[i], self.filenames[i]);
            formdata.append('file_id', i);
            self._ajaxSubmit(fnBefore, fnSuccess, fnComplete, fnError, previewId, i);
        },
        _uploadBatch: function () {
            var self = this, files = self.filestack, total = files.length, params = {}, fnBefore, fnSuccess, fnError,
                fnComplete, hasPostData = self.filestack.length > 0 || !$.isEmptyObject(self.uploadExtraData),
                setAllUploaded;
            self.formdata = new FormData();
            if (total === 0 || !hasPostData || self._abort(params)) {
                return;
            }
            setAllUploaded = function () {
                $.each(files, function (key) {
                    self.updateStack(key, undefined);
                });
                self._clearFileInput();
            };
            fnBefore = function (jqXHR) {
                self.lock();
                var outData = self._getOutData(jqXHR);
                if (self.showPreview) {
                    self._getThumbs().each(function () {
                        var $thumb = $(this), $btnUpload = $thumb.find('.kv-file-upload'),
                            $btnDelete = $thumb.find('.kv-file-remove');
                        if (!$thumb.hasClass('file-preview-success')) {
                            self._setThumbStatus($thumb, 'Loading');
                            $h.addCss($thumb, 'file-uploading');
                        }
                        $btnUpload.attr('disabled', true);
                        $btnDelete.attr('disabled', true);
                    });
                }
                self._raise('filebatchpreupload', [outData]);
                if (self._abort(outData)) {
                    jqXHR.abort();
                    self._setProgressCancelled();
                }
            };
            fnSuccess = function (data, textStatus, jqXHR) {
                /** @namespace data.errorkeys */
                var outData = self._getOutData(jqXHR, data), $thumbs = self._getThumbs(':not(.file-preview-error)'), key = 0,
                    keys = $h.isEmpty(data) || $h.isEmpty(data.errorkeys) ? [] : data.errorkeys;
                if ($h.isEmpty(data) || $h.isEmpty(data.error)) {
                    self._raise('filebatchuploadsuccess', [outData]);
                    setAllUploaded();
                    if (self.showPreview) {
                        $thumbs.each(function () {
                            var $thumb = $(this), $btnUpload = $thumb.find('.kv-file-upload');
                            $thumb.find('.kv-file-upload').hide();
                            self._setThumbStatus($thumb, 'Success');
                            $thumb.removeClass('file-uploading');
                            $btnUpload.removeAttr('disabled');
                        });
                        self._initUploadSuccess(data);
                    } else {
                        self.reset();
                    }
                    self._setProgress(101);
                } else {
                    if (self.showPreview) {
                        $thumbs.each(function () {
                            var $thumb = $(this), $btnDelete = $thumb.find('.kv-file-remove'),
                                $btnUpload = $thumb.find('.kv-file-upload');
                            $thumb.removeClass('file-uploading');
                            $btnUpload.removeAttr('disabled');
                            $btnDelete.removeAttr('disabled');
                            if (keys.length === 0) {
                                self._setPreviewError($thumb);
                                return;
                            }
                            if ($.inArray(key, keys) !== -1) {
                                self._setPreviewError($thumb);
                            } else {
                                $thumb.find('.kv-file-upload').hide();
                                self._setThumbStatus($thumb, 'Success');
                                self.updateStack(key, undefined);
                            }
                            key++;
                        });
                        self._initUploadSuccess(data);
                    }
                    self._showUploadError(data.error, outData, 'filebatchuploaderror');
                }
            };
            fnComplete = function () {
                self.unlock();
                self._initSuccessThumbs();
                self._clearFileInput();
                self._raise('filebatchuploadcomplete', [self.filestack, self._getExtraData()]);
            };
            fnError = function (jqXHR, textStatus, errorThrown) {
                var outData = self._getOutData(jqXHR), op = self.ajaxOperations.uploadBatch,
                    errMsg = self._parseError(op, jqXHR, errorThrown);
                self._showUploadError(errMsg, outData, 'filebatchuploaderror');
                self.uploadFileCount = total - 1;
                if (!self.showPreview) {
                    return;
                }
                self._getThumbs().each(function () {
                    var $thumb = $(this), key = $thumb.attr('data-fileindex');
                    $thumb.removeClass('file-uploading');
                    if (self.filestack[key] !== undefined) {
                        self._setPreviewError($thumb);
                    }
                });
                self._getThumbs().removeClass('file-uploading');
                self._getThumbs(' .kv-file-upload').removeAttr('disabled');
                self._getThumbs(' .kv-file-delete').removeAttr('disabled');
                self._setProgress(101, self.$progress, self.msgAjaxProgressError.replace('{operation}', op));
            };
            $.each(files, function (key, data) {
                if (!$h.isEmpty(files[key])) {
                    self.formdata.append(self.uploadFileAttr, data, self.filenames[key]);
                }
            });
            self._ajaxSubmit(fnBefore, fnSuccess, fnComplete, fnError);
        },
        _uploadExtraOnly: function () {
            var self = this, params = {}, fnBefore, fnSuccess, fnComplete, fnError;
            self.formdata = new FormData();
            if (self._abort(params)) {
                return;
            }
            fnBefore = function (jqXHR) {
                self.lock();
                var outData = self._getOutData(jqXHR);
                self._raise('filebatchpreupload', [outData]);
                self._setProgress(50);
                params.data = outData;
                params.xhr = jqXHR;
                if (self._abort(params)) {
                    jqXHR.abort();
                    self._setProgressCancelled();
                }
            };
            fnSuccess = function (data, textStatus, jqXHR) {
                var outData = self._getOutData(jqXHR, data);
                if ($h.isEmpty(data) || $h.isEmpty(data.error)) {
                    self._raise('filebatchuploadsuccess', [outData]);
                    self._clearFileInput();
                    self._initUploadSuccess(data);
                    self._setProgress(101);
                } else {
                    self._showUploadError(data.error, outData, 'filebatchuploaderror');
                }
            };
            fnComplete = function () {
                self.unlock();
                self._clearFileInput();
                self._raise('filebatchuploadcomplete', [self.filestack, self._getExtraData()]);
            };
            fnError = function (jqXHR, textStatus, errorThrown) {
                var outData = self._getOutData(jqXHR), op = self.ajaxOperations.uploadExtra,
                    errMsg = self._parseError(op, jqXHR, errorThrown);
                params.data = outData;
                self._showUploadError(errMsg, outData, 'filebatchuploaderror');
                self._setProgress(101, self.$progress, self.msgAjaxProgressError.replace('{operation}', op));
            };
            self._ajaxSubmit(fnBefore, fnSuccess, fnComplete, fnError);
        },
        _deleteFileIndex: function ($frame) {
            var self = this, ind = $frame.attr('data-fileindex');
            if (ind.substring(0, 5) === 'init_') {
                ind = parseInt(ind.replace('init_', ''));
                self.initialPreview = $h.spliceArray(self.initialPreview, ind);
                self.initialPreviewConfig = $h.spliceArray(self.initialPreviewConfig, ind);
                self.initialPreviewThumbTags = $h.spliceArray(self.initialPreviewThumbTags, ind);
                self.$preview.find($h.FRAMES).each(function () {
                    var $nFrame = $(this), nInd = $nFrame.attr('data-fileindex');
                    if (nInd.substring(0, 5) === 'init_') {
                        nInd = parseInt(nInd.replace('init_', ''));
                        if (nInd > ind) {
                            nInd--;
                            $nFrame.attr('data-fileindex', 'init_' + nInd);
                        }
                    }
                });
                if (self.uploadAsync) {
                    self.cacheInitialPreview = self.getPreview();
                }
            }
        },
        _initFileActions: function () {
            var self = this, $preview = self.$preview;
            if (!self.showPreview) {
                return;
            }
            self._initZoomButton();
            $preview.find($h.FRAMES + ' .kv-file-remove').each(function () {
                var $el = $(this), $frame = $el.closest($h.FRAMES), hasError, id = $frame.attr('id'),
                    ind = $frame.attr('data-fileindex'), n, cap, status;
                self._handler($el, 'click', function () {
                    status = self._raise('filepreremove', [id, ind]);
                    if (status === false || !self._validateMinCount()) {
                        return false;
                    }
                    hasError = $frame.hasClass('file-preview-error');
                    $h.cleanMemory($frame);
                    $frame.fadeOut('slow', function () {
                        $h.cleanZoomCache($preview.find('#zoom-' + id));
                        self.updateStack(ind, undefined);
                        self._clearObjects($frame);
                        $frame.remove();
                        if (id && hasError) {
                            self.$errorContainer.find('li[data-file-id="' + id + '"]').fadeOut('fast', function () {
                                $(this).remove();
                                if (!self._errorsExist()) {
                                    self._resetErrors();
                                }
                            });
                        }
                        self._clearFileInput();
                        var filestack = self.getFileStack(true), chk = self.previewCache.count(),
                            len = filestack.length,
                            hasThumb = self.showPreview && $preview.find($h.FRAMES).length;
                        if (len === 0 && chk === 0 && !hasThumb) {
                            self.reset();
                        } else {
                            n = chk + len;
                            cap = n > 1 ? self._getMsgSelected(n) : (filestack[0] ? self._getFileNames()[0] : '');
                            self._setCaption(cap);
                        }
                        self._raise('fileremoved', [id, ind]);
                    });
                });
            });
            self.$preview.find($h.FRAMES + ' .kv-file-upload').each(function () {
                var $el = $(this);
                self._handler($el, 'click', function () {
                    var $frame = $el.closest($h.FRAMES), ind = $frame.attr('data-fileindex');
                    if (!$frame.hasClass('file-preview-error')) {
                        self._uploadSingle(ind, self.filestack, false);
                    }
                });
            });
        },
        _initPreviewActions: function () {
            var self = this, $preview = self.$preview, deleteExtraData = self.deleteExtraData || {},
                btnRemove = $h.FRAMES + ' .kv-file-remove',
                resetProgress = function () {
                    var hasFiles = self.isUploadable ? self.previewCache.count() : self.$element.get(0).files.length;
                    if ($preview.find(btnRemove).length === 0 && !hasFiles) {
                        self.reset();
                        self.initialCaption = '';
                    }
                };
            self._initZoomButton();
            $preview.find(btnRemove).each(function () {
                var $el = $(this), vUrl = $el.data('url') || self.deleteUrl, vKey = $el.data('key');
                if ($h.isEmpty(vUrl) || vKey === undefined) {
                    return;
                }
                var $frame = $el.closest($h.FRAMES), cache = self.previewCache.data,
                    settings, params, index = $frame.data('fileindex'), config, extraData;
                index = parseInt(index.replace('init_', ''));
                config = $h.isEmpty(cache.config) && $h.isEmpty(cache.config[index]) ? null : cache.config[index];
                extraData = $h.isEmpty(config) || $h.isEmpty(config.extra) ? deleteExtraData : config.extra;
                if (typeof extraData === "function") {
                    extraData = extraData();
                }
                params = {id: $el.attr('id'), key: vKey, extra: extraData};
                settings = $.extend(true, {}, {
                    url: vUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: $.extend(true, {}, {key: vKey}, extraData),
                    beforeSend: function (jqXHR) {
                        self.ajaxAborted = false;
                        self._raise('filepredelete', [vKey, jqXHR, extraData]);
                        if (self.ajaxAborted) {
                            jqXHR.abort();
                        } else {
                            $h.addCss($frame, 'file-uploading');
                            $h.addCss($el, 'disabled');
                        }
                    },
                    success: function (data, textStatus, jqXHR) {
                        var n, cap;
                        if ($h.isEmpty(data) || $h.isEmpty(data.error)) {
                            self.previewCache.init();
                            index = parseInt(($frame.data('fileindex')).replace('init_', ''));
                            self.previewCache.unset(index);
                            n = self.previewCache.count();
                            cap = n > 0 ? self._getMsgSelected(n) : '';
                            self._deleteFileIndex($frame);
                            self._setCaption(cap);
                            self._raise('filedeleted', [vKey, jqXHR, extraData]);
                        } else {
                            params.jqXHR = jqXHR;
                            params.response = data;
                            self._showError(data.error, params, 'filedeleteerror');
                            $frame.removeClass('file-uploading');
                            $el.removeClass('disabled');
                            resetProgress();
                            return;
                        }
                        $frame.removeClass('file-uploading').addClass('file-deleted');
                        $frame.fadeOut('slow', function () {
                            $h.cleanZoomCache($preview.find('#zoom-' + $frame.attr('id')));
                            self._clearObjects($frame);
                            $frame.remove();
                            resetProgress();
                            if (!n && self.getFileStack().length === 0) {
                                self._setCaption('');
                                self.reset();
                            }
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var op = self.ajaxOperations.deleteThumb, errMsg = self._parseError(op, jqXHR, errorThrown);
                        params.jqXHR = jqXHR;
                        params.response = {};
                        self._showError(errMsg, params, 'filedeleteerror');
                        $frame.removeClass('file-uploading');
                        resetProgress();
                    }
                }, self.ajaxDeleteSettings);
                self._handler($el, 'click', function () {
                    if (!self._validateMinCount()) {
                        return false;
                    }
                    $.ajax(settings);
                });
            });
        },
        _hideFileIcon: function () {
            if (this.overwriteInitial) {
                this.$captionContainer.find('.kv-caption-icon').hide();
            }
        },
        _showFileIcon: function () {
            this.$captionContainer.find('.kv-caption-icon').show();
        },
        _getSize: function (bytes) {
            var self = this, size = parseFloat(bytes), i, func = self.fileSizeGetter, sizes, out;
            if (!$.isNumeric(bytes) || !$.isNumeric(size)) {
                return '';
            }
            if (typeof func === 'function') {
                out = func(size);
            } else {
                if (size === 0) {
                    out = '0.00 B';
                } else {
                    i = Math.floor(Math.log(size) / Math.log(1024));
                    sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                    out = (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + sizes[i];
                }
            }
            return self._getLayoutTemplate('size').replace('{sizeText}', out);
        },
        _generatePreviewTemplate: function (cat, data, fname, ftype, previewId, isError, size, frameClass, foot, ind, templ) {
            var self = this, caption = self.slug(fname), prevContent, zoomContent = '',
                config = self.previewSettings[cat] || self.defaults.previewSettings[cat],
                w = config && config.width ? config.width : '', h = config && config.height ? config.height : '',
                footer = foot || self._renderFileFooter(caption, size, ($h.isEmpty(w) ? 'auto' : w), isError),
                hasIconSetting = self._getPreviewIcon(fname),
                forcePrevIcon = hasIconSetting && self.preferIconicPreview,
                forceZoomIcon = hasIconSetting && self.preferIconicZoomPreview,
                getContent = function (c, d, zoom, frameCss) {
                    var id = zoom ? 'zoom-' + previewId : previewId, tmplt = self._getPreviewTemplate(c),
                        css = (frameClass || '') + ' ' + frameCss;
                    if (self.frameClass) {
                        css = self.frameClass + ' ' + css;
                    }
                    if (zoom) {
                        css = css.replace(' ' + $h.SORT_CSS, '');
                    }
                    tmplt = self._parseFilePreviewIcon(tmplt, fname);
                    if (c === 'text') {
                        d = $h.htmlEncode(d);
                    }
                    return tmplt.replace(/\{previewId}/g, id).replace(/\{caption}/g, caption)
                        .replace(/\{frameClass}/g, css).replace(/\{type}/g, ftype).replace(/\{fileindex}/g, ind)
                        .replace(/\{width}/g, w).replace(/\{height}/g, h)
                        .replace(/\{footer}/g, footer).replace(/\{data}/g, d).replace(/\{template}/g, templ || cat);
                };
            ind = ind || previewId.slice(previewId.lastIndexOf('-') + 1);
            if (self.fileActionSettings.showZoom) {
                zoomContent = getContent((forceZoomIcon ? 'other' : cat), data, true, 'kv-zoom-thumb');
            }
            zoomContent = '\n' + self._getLayoutTemplate('zoomCache').replace('{zoomContent}', zoomContent);
            prevContent = getContent((forcePrevIcon ? 'other' : cat), data, false, 'kv-preview-thumb');
            return prevContent + zoomContent;
        },
        _previewDefault: function (file, previewId, isDisabled) {
            var self = this, $preview = self.$preview;
            if (!self.showPreview) {
                return;
            }
            var fname = file ? file.name : '', ftype = file ? file.type : '', content, size = file.size || 0,
                caption = self.slug(fname), isError = isDisabled === true && !self.isUploadable,
                data = $h.objUrl.createObjectURL(file);
            self._clearDefaultPreview();
            content = self._generatePreviewTemplate('other', data, fname, ftype, previewId, isError, size);
            $preview.append("\n" + content);
            self._setThumbAttr(previewId, caption, size);
            if (isDisabled === true && self.isUploadable) {
                self._setThumbStatus($('#' + previewId), 'Error');
            }
        },
        _previewFile: function (i, file, theFile, previewId, data) {
            if (!this.showPreview) {
                return;
            }
            var self = this, cat = self._parseFileType(file), fname = file ? file.name : '', caption = self.slug(fname),
                types = self.allowedPreviewTypes, mimes = self.allowedPreviewMimeTypes, $preview = self.$preview,
                chkTypes = types && types.indexOf(cat) >= 0, size = file.size || 0,
                iData = (cat === 'text' || cat === 'html' || cat === 'image') ? theFile.target.result : data, content,
                chkMimes = mimes && mimes.indexOf(file.type) !== -1;
            /** @namespace window.DOMPurify */
            if (cat === 'html' && self.purifyHtml && window.DOMPurify) {
                iData = window.DOMPurify.sanitize(iData);
            }
            if (chkTypes || chkMimes) {
                content = self._generatePreviewTemplate(cat, iData, fname, file.type, previewId, false, size);
                self._clearDefaultPreview();
                $preview.append("\n" + content);
                self._validateImage(previewId, caption, file.type);
            } else {
                self._previewDefault(file, previewId);
            }
            self._setThumbAttr(previewId, caption, size);
            self._initSortable();
        },
        _setThumbAttr: function (id, caption, size) {
            var self = this, $frame = $('#' + id);
            if ($frame.length) {
                size = size && size > 0 ? self._getSize(size) : '';
                $frame.data({'caption': caption, 'size': size});
            }
        },
        _setInitThumbAttr: function () {
            var self = this, data = self.previewCache.data, len = self.previewCache.count(true), config,
                caption, size, previewId;
            if (len === 0) {
                return;
            }
            for (var i = 0; i < len; i++) {
                config = data.config[i];
                previewId = self.previewInitId + '-' + 'init_' + i;
                caption = $h.ifSet('caption', config, $h.ifSet('filename', config));
                size = $h.ifSet('size', config);
                self._setThumbAttr(previewId, caption, size);
            }
        },
        _slugDefault: function (text) {
            return $h.isEmpty(text) ? '' : String(text).replace(/[\-\[\]\/\{}:;#%=\(\)\*\+\?\\\^\$\|<>&"']/g, '_');
        },
        _readFiles: function (files) {
            this.reader = new FileReader();
            var self = this, $el = self.$element, $preview = self.$preview, reader = self.reader,
                $container = self.$previewContainer, $status = self.$previewStatus, msgLoading = self.msgLoading,
                msgProgress = self.msgProgress, previewInitId = self.previewInitId, numFiles = files.length,
                settings = self.fileTypeSettings, ctr = self.filestack.length, readFile,
                fileTypes = self.allowedFileTypes, typLen = fileTypes ? fileTypes.length : 0,
                fileExt = self.allowedFileExtensions, strExt = $h.isEmpty(fileExt) ? '' : fileExt.join(', '),
                maxPreviewSize = self.maxFilePreviewSize && parseFloat(self.maxFilePreviewSize),
                canPreview = $preview.length && (!maxPreviewSize || isNaN(maxPreviewSize)),
                throwError = function (msg, file, previewId, index) {
                    var p1 = $.extend(true, {}, self._getOutData({}, {}, files), {id: previewId, index: index}),
                        p2 = {id: previewId, index: index, file: file, files: files};
                    self._previewDefault(file, previewId, true);
                    if (self.isUploadable) {
                        self.addToStack(undefined);
                        setTimeout(function () {
                            readFile(index + 1);
                        }, 100);
                    }
                    self._initFileActions();
                    if (self.removeFromPreviewOnError) {
                        $('#' + previewId).remove();
                    }
                    return self.isUploadable ? self._showUploadError(msg, p1) : self._showError(msg, p2);
                };

            self.loadedImages = [];
            self.totalImagesCount = 0;

            $.each(files, function (key, file) {
                var func = self.fileTypeSettings.image;
                if (func && func(file.type)) {
                    self.totalImagesCount++;
                }
            });
            readFile = function (i) {
                if ($h.isEmpty($el.attr('multiple'))) {
                    numFiles = 1;
                }
                if (i >= numFiles) {
                    if (self.isUploadable && self.filestack.length > 0) {
                        self._raise('filebatchselected', [self.getFileStack()]);
                    } else {
                        self._raise('filebatchselected', [files]);
                    }
                    $container.removeClass('file-thumb-loading');
                    $status.html('');
                    return;
                }
                var node = ctr + i, previewId = previewInitId + "-" + node, isText, isImage, file = files[i], fSizeKB,
                    caption = file.name ? self.slug(file.name) : '', fileSize = (file.size || 0) / 1000, j, msg,
                    fileExtExpr = '', previewData = $h.objUrl.createObjectURL(file), typ, chk, typ1, typ2,
                    fileCount = 0, strTypes = '', func;
                if (typLen > 0) {
                    for (j = 0; j < typLen; j++) {
                        typ1 = fileTypes[j];
                        typ2 = self.msgFileTypes[typ1] || typ1;
                        strTypes += j === 0 ? typ2 : ', ' + typ2;
                    }
                }
                if (caption === false) {
                    readFile(i + 1);
                    return;
                }
                if (caption.length === 0) {
                    msg = self.msgInvalidFileName.replace('{name}', $h.htmlEncode(file.name));
                    self.isError = throwError(msg, file, previewId, i);
                    return;
                }
                if (!$h.isEmpty(fileExt)) {
                    fileExtExpr = new RegExp('\\.(' + fileExt.join('|') + ')$', 'i');
                }
                fSizeKB = fileSize.toFixed(2);
                if (self.maxFileSize > 0 && fileSize > self.maxFileSize) {
                    msg = self.msgSizeTooLarge.replace('{name}', caption).replace('{size}', fSizeKB)
                        .replace('{maxSize}', self.maxFileSize);
                    self.isError = throwError(msg, file, previewId, i);
                    return;
                }
                if (self.minFileSize !== null && fileSize <= $h.getNum(self.minFileSize)) {
                    msg = self.msgSizeTooSmall.replace('{name}', caption).replace('{size}', fSizeKB)
                        .replace('{minSize}', self.minFileSize);
                    self.isError = throwError(msg, file, previewId, i);
                    return;
                }
                if (!$h.isEmpty(fileTypes) && $h.isArray(fileTypes)) {
                    for (j = 0; j < fileTypes.length; j += 1) {
                        typ = fileTypes[j];
                        func = settings[typ];
                        fileCount += !func || (typeof func !== 'function') ? 0 : (func(file.type, file.name) ? 1 : 0);
                    }
                    if (fileCount === 0) {
                        msg = self.msgInvalidFileType.replace('{name}', caption).replace('{types}', strTypes);
                        self.isError = throwError(msg, file, previewId, i);
                        return;
                    }
                }
                if (fileCount === 0 && !$h.isEmpty(fileExt) && $h.isArray(fileExt) && !$h.isEmpty(fileExtExpr)) {
                    chk = $h.compare(caption, fileExtExpr);
                    fileCount += $h.isEmpty(chk) ? 0 : chk.length;
                    if (fileCount === 0) {
                        msg = self.msgInvalidFileExtension.replace('{name}', caption).replace('{extensions}', strExt);
                        self.isError = throwError(msg, file, previewId, i);
                        return;
                    }
                }
                if (!self.showPreview) {
                    self.addToStack(file);
                    setTimeout(function () {
                        readFile(i + 1);
                    }, 100);
                    self._raise('fileloaded', [file, previewId, i, reader]);
                    return;
                }
                if (!canPreview && fileSize > maxPreviewSize) {
                    self.addToStack(file);
                    $container.addClass('file-thumb-loading');
                    self._previewDefault(file, previewId);
                    self._initFileActions();
                    self._updateFileDetails(numFiles);
                    readFile(i + 1);
                    return;
                }
                if ($preview.length && FileReader !== undefined) {
                    $status.html(msgLoading.replace('{index}', i + 1).replace('{files}', numFiles));
                    $container.addClass('file-thumb-loading');
                    reader.onerror = function (evt) {
                        self._errorHandler(evt, caption);
                    };
                    reader.onload = function (theFile) {
                        self._previewFile(i, file, theFile, previewId, previewData);
                        self._initFileActions();
                    };
                    reader.onloadend = function () {
                        msg = msgProgress.replace('{index}', i + 1).replace('{files}', numFiles)
                            .replace('{percent}', 50).replace('{name}', caption);
                        setTimeout(function () {
                            $status.html(msg);
                            self._updateFileDetails(numFiles);
                            readFile(i + 1);
                        }, 100);
                        self._raise('fileloaded', [file, previewId, i, reader]);
                    };
                    reader.onprogress = function (data) {
                        if (data.lengthComputable) {
                            var fact = (data.loaded / data.total) * 100, progress = Math.ceil(fact);
                            msg = msgProgress.replace('{index}', i + 1).replace('{files}', numFiles)
                                .replace('{percent}', progress).replace('{name}', caption);
                            setTimeout(function () {
                                $status.html(msg);
                            }, 100);
                        }
                    };
                    isText = settings.text;
                    isImage = settings.image;

                    if (isText(file.type, caption)) {
                        reader.readAsText(file, self.textEncoding);
                    } else {
                        if (isImage(file.type, caption)) {
                            reader.readAsDataURL(file);
                        } else {
                            reader.readAsArrayBuffer(file);
                        }
                    }
                } else {
                    self._previewDefault(file, previewId);
                    setTimeout(function () {
                        readFile(i + 1);
                        self._updateFileDetails(numFiles);
                    }, 100);
                    self._raise('fileloaded', [file, previewId, i, reader]);
                }
                self.addToStack(file);
            };

            readFile(0);
            self._updateFileDetails(numFiles, false);
        },
        _updateFileDetails: function (numFiles) {
            var self = this, $el = self.$element, fileStack = self.getFileStack(),
                name = ($h.isIE(9) && $h.findFileName($el.val())) ||
                    ($el[0].files[0] && $el[0].files[0].name) || (fileStack.length && fileStack[0].name) || '',
                label = self.slug(name), n = self.isUploadable ? fileStack.length : numFiles,
                nFiles = self.previewCache.count() + n, log = n > 1 ? self._getMsgSelected(nFiles) : label;
            if (self.isError) {
                self.$previewContainer.removeClass('file-thumb-loading');
                self.$previewStatus.html('');
                self.$captionContainer.find('.kv-caption-icon').hide();
            } else {
                self._showFileIcon();
            }
            self._setCaption(log, self.isError);
            self.$container.removeClass('file-input-new file-input-ajax-new');
            if (arguments.length === 1) {
                self._raise('fileselect', [numFiles, label]);
            }
            if (self.previewCache.count()) {
                self._initPreviewActions();
            }
        },
        _setThumbStatus: function ($thumb, status) {
            var self = this;
            if (!self.showPreview) {
                return;
            }
            var icon = 'indicator' + status, msg = icon + 'Title',
                css = 'file-preview-' + status.toLowerCase(),
                $indicator = $thumb.find('.file-upload-indicator'),
                config = self.fileActionSettings;
            $thumb.removeClass('file-preview-success file-preview-error file-preview-loading');
            if (status === 'Error') {
                $thumb.find('.kv-file-upload').attr('disabled', true);
            }
            if (status === 'Success') {
                $thumb.find('.file-drag-handle').remove();
                $indicator.css('margin-left', 0);
            }
            $indicator.html(config[icon]);
            $indicator.attr('title', config[msg]);
            $thumb.addClass(css);
        },
        _setProgressCancelled: function () {
            var self = this;
            self._setProgress(101, self.$progress, self.msgCancelled);
        },
        _setProgress: function (p, $el, error) {
            var self = this, pct = Math.min(p, 100), out, status, pctLimit = self.progressUploadThreshold,
                t = p <= 100 ? self.progressTemplate : self.progressCompleteTemplate,
                template = pct < 100 ? self.progressTemplate : (error ? self.progressErrorTemplate : t);
            $el = $el || self.$progress;
            if (!$h.isEmpty(template)) {
                if (pctLimit && pct > pctLimit && p <= 100) {
                    out = template.replace(/\{percent}/g, pctLimit).replace(/\{status}/g, self.msgUploadThreshold);
                } else {
                    status = p > 100 ? self.msgUploadEnd : pct + '%';
                    out = template.replace(/\{percent}/g, pct).replace(/\{status}/g, status);
                }
                $el.html(out);
                if (error) {
                    $el.find('[role="progressbar"]').html(error);
                }
            }
        },
        _setFileDropZoneTitle: function () {
            var self = this, $zone = self.$container.find('.file-drop-zone'), title = self.dropZoneTitle, strFiles;
            if (self.isClickable) {
                strFiles = $h.isEmpty(self.$element.attr('multiple')) ? self.fileSingle : self.filePlural;
                title += self.dropZoneClickTitle.replace('{files}', strFiles);
            }
            $zone.find('.' + self.dropZoneTitleClass).remove();
            if (!self.isUploadable || !self.showPreview || $zone.length === 0 || self.getFileStack().length > 0 || !self.dropZoneEnabled) {
                return;
            }
            if ($zone.find($h.FRAMES).length === 0 && $h.isEmpty(self.defaultPreviewContent)) {
                $zone.prepend('<div class="' + self.dropZoneTitleClass + '">' + title + '</div>');
            }
            self.$container.removeClass('file-input-new');
            $h.addCss(self.$container, 'file-input-ajax-new');
        },
        _setAsyncUploadStatus: function (previewId, pct, total) {
            var self = this, sum = 0;
            self._setProgress(pct, $('#' + previewId).find('.file-thumb-progress'));
            self.uploadStatus[previewId] = pct;
            $.each(self.uploadStatus, function (key, value) {
                sum += value;
            });
            self._setProgress(Math.floor(sum / total));

        },
        _validateMinCount: function () {
            var self = this, len = self.isUploadable ? self.getFileStack().length : self.$element.get(0).files.length;
            if (self.validateInitialCount && self.minFileCount > 0 && self._getFileCount(len - 1) < self.minFileCount) {
                self._noFilesError({});
                return false;
            }
            return true;
        },
        _getFileCount: function (fileCount) {
            var self = this, addCount = 0;
            if (self.validateInitialCount && !self.overwriteInitial) {
                addCount = self.previewCache.count();
                fileCount += addCount;
            }
            return fileCount;
        },
        _getFileId: function (file) {
            var self = this, custom = self.generateFileId, relativePath;
            if (typeof custom === 'function') {
                return custom(file, event);
            }
            if (!file) {
                return null;
            }
            /** @namespace file.webkitRelativePath */
            relativePath = file.webkitRelativePath || file.fileName || file.name || null;
            if (!relativePath) {
                return null;
            }
            return (file.size + '-' + relativePath.replace(/[^0-9a-zA-Z_-]/img, ''));
        },
        _getFileName: function (file) {
            return file && file.name ? this.slug(file.name) : undefined;
        },
        _getFileIds: function (skipNull) {
            var self = this;
            return self.fileids.filter(function (n) {
                return (skipNull ? n !== undefined : n !== undefined && n !== null);
            });
        },
        _getFileNames: function (skipNull) {
            var self = this;
            return self.filenames.filter(function (n) {
                return (skipNull ? n !== undefined : n !== undefined && n !== null);
            });
        },
        _setPreviewError: function ($thumb, i, val) {
            var self = this;
            if (i !== undefined) {
                self.updateStack(i, val);
            }
            if (self.removeFromPreviewOnError) {
                $thumb.remove();
            } else {
                self._setThumbStatus($thumb, 'Error');
            }
        },
        _checkDimensions: function (i, chk, $img, $thumb, fname, type, params) {
            var self = this, msg, dim, tag = chk === 'Small' ? 'min' : 'max', limit = self[tag + 'Image' + type],
                $imgEl, isValid;
            if ($h.isEmpty(limit) || !$img.length) {
                return;
            }
            $imgEl = $img[0];
            dim = (type === 'Width') ? $imgEl.naturalWidth || $imgEl.width : $imgEl.naturalHeight || $imgEl.height;
            isValid = chk === 'Small' ? dim >= limit : dim <= limit;
            if (isValid) {
                return;
            }
            msg = self['msgImage' + type + chk].replace('{name}', fname).replace('{size}', limit);
            self._showUploadError(msg, params);
            self._setPreviewError($thumb, i, null);
        },
        _validateImage: function (previewId, fname, ftype) {
            var self = this, $preview = self.$preview, params, w1, w2, $thumb = $preview.find("#" + previewId),
                i = $thumb.attr('data-fileindex'), $img = $thumb.find('img');
            fname = fname || 'Untitled';
            if (!$img.length) {
                return;
            }
            self._handler($img, 'load', function () {
                w1 = $thumb.width();
                w2 = $preview.width();
                if (w1 > w2) {
                    $img.css('width', '100%');
                    $thumb.css('width', '97%');
                }
                params = {ind: i, id: previewId};
                self._checkDimensions(i, 'Small', $img, $thumb, fname, 'Width', params);
                self._checkDimensions(i, 'Small', $img, $thumb, fname, 'Height', params);
                if (!self.resizeImage) {
                    self._checkDimensions(i, 'Large', $img, $thumb, fname, 'Width', params);
                    self._checkDimensions(i, 'Large', $img, $thumb, fname, 'Height', params);
                }
                self._raise('fileimageloaded', [previewId]);
                self.loadedImages.push({ind: i, img: $img, thumb: $thumb, pid: previewId, typ: ftype});
                self._validateAllImages();
            });
        },
        _validateAllImages: function () {
            var self = this, i, counter = {val: 0}, numImgs = self.loadedImages.length;
            if (numImgs !== self.totalImagesCount) {
                return;
            }
            self._raise('fileimagesloaded');
            if (!self.resizeImage) {
                return;
            }
            for (i = 0; i < self.loadedImages.length; i++) {
                self._getResizedImage(self.loadedImages[i], counter, numImgs);
            }
        },
        _getResizedImage: function (config, counter, numImgs) {
            var self = this, img = $(config.img)[0], width = img.naturalWidth, height = img.naturalHeight,
                ratio = 1, maxWidth = self.maxImageWidth || width, maxHeight = self.maxImageHeight || height,
                isValidImage = !!(width && height), chkWidth, chkHeight, canvas = self.imageCanvas,
                context = self.imageCanvasContext, type = config.typ, pid = config.pid, ind = config.ind,
                thumb = config.thumb, throwError, msg;
            throwError = function (msg, params, ev) {
                if (self.isUploadable) {
                    self._showUploadError(msg, params, ev);
                } else {
                    self._showError(msg, params, ev);
                }
                self._setPreviewError(thumb, ind);
            };
            if (!self.filestack[ind] || !isValidImage || (width <= maxWidth && height <= maxHeight)) {
                if (isValidImage && self.filestack[ind]) {
                    self._raise('fileimageresized', [pid, ind]);
                }
                counter.val++;
                if (counter.val === numImgs) {
                    self._raise('fileimagesresized');
                }
                if (!isValidImage) {
                    throwError(self.msgImageResizeError, {id: pid, 'index': ind}, 'fileimageresizeerror');
                    return;
                }
            }
            type = type || self.resizeDefaultImageType;
            chkWidth = width > maxWidth;
            chkHeight = height > maxHeight;
            if (self.resizePreference === 'width') {
                ratio = chkWidth ? maxWidth / width : (chkHeight ? maxHeight / height : 1);
            } else {
                ratio = chkHeight ? maxHeight / height : (chkWidth ? maxWidth / width : 1);
            }
            self._resetCanvas();
            width *= ratio;
            height *= ratio;
            canvas.width = width;
            canvas.height = height;
            try {
                context.drawImage(img, 0, 0, width, height);
                canvas.toBlob(function (blob) {
                    self.filestack[ind] = blob;
                    self._raise('fileimageresized', [pid, ind]);
                    counter.val++;
                    if (counter.val === numImgs) {
                        self._raise('fileimagesresized', [undefined, undefined]);
                    }
                    if (!(blob instanceof Blob)) {
                        throwError(self.msgImageResizeError, {id: pid, 'index': ind}, 'fileimageresizeerror');
                    }
                }, type, self.resizeQuality);
            }
            catch (err) {
                counter.val++;
                if (counter.val === numImgs) {
                    self._raise('fileimagesresized', [undefined, undefined]);
                }
                msg = self.msgImageResizeException.replace('{errors}', err.message);
                throwError(msg, {id: pid, 'index': ind}, 'fileimageresizeexception');
            }
        },
        _initBrowse: function ($container) {
            var self = this;
            if (self.showBrowse) {
                self.$btnFile = $container.find('.btn-file');
                self.$btnFile.append(self.$element);
            } else {
                self.$element.hide();
            }
        },
        _initCaption: function () {
            var self = this, cap = self.initialCaption || '';
            if (self.overwriteInitial || $h.isEmpty(cap)) {
                self.$caption.html('');
                return false;
            }
            self._setCaption(cap);
            return true;
        },
        _setCaption: function (content, isError) {
            var self = this, title, out, n, cap, stack = self.getFileStack();
            if (!self.$caption.length) {
                return;
            }
            if (isError) {
                title = $('<div>' + self.msgValidationError + '</div>').text();
                n = stack.length;
                if (n) {
                    cap = n === 1 && stack[0] ? self._getFileNames()[0] : self._getMsgSelected(n);
                } else {
                    cap = self._getMsgSelected(self.msgNo);
                }
                out = '<span class="' + self.msgValidationErrorClass + '">' + self.msgValidationErrorIcon +
                    ($h.isEmpty(content) ? cap : content) + '</span>';
            } else {
                if ($h.isEmpty(content)) {
                    return;
                }
                title = $('<div>' + content + '</div>').text();
                out = self._getLayoutTemplate('fileIcon') + title;
            }
            self.$caption.html(out);
            self.$caption.attr('title', title);
            self.$captionContainer.find('.file-caption-ellipsis').attr('title', title);
        },
        _createContainer: function () {
            var self = this, $container = $(document.createElement("div"))
                .attr({"class": 'file-input file-input-new'})
                .html(self._renderMain());
            self.$element.before($container);
            self._initBrowse($container);
            if (self.theme) {
                $container.addClass('theme-' + self.theme);
            }
            return $container;
        },
        _refreshContainer: function () {
            var self = this, $container = self.$container;
            $container.before(self.$element);
            $container.html(self._renderMain());
            self._initBrowse($container);
        },
        _renderMain: function () {
            var self = this, dropCss = (self.isUploadable && self.dropZoneEnabled) ? ' ' : 'file-drop-disabled',
                close = !self.showClose ? '' : self._getLayoutTemplate('close'),
                preview = !self.showPreview ? '' : self._getLayoutTemplate('preview')
                    .replace(/\{class}/g, self.previewClass)
                    .replace(/\{dropClass}/g, dropCss),
                css = self.isDisabled ? self.captionClass + ' file-caption-disabled' : self.captionClass,
                caption = self.captionTemplate.replace(/\{class}/g, css + ' kv-fileinput-caption');
            return self.mainTemplate.replace(/\{class}/g +
                (!self.showBrowse && self.showCaption ? ' no-browse' : ''))
                .replace(/\{preview}/g, preview)
                .replace(/\{close}/g, close)
                .replace(/\{caption}/g, caption)
                .replace(/\{upload}/g, self._renderButton('upload'))
                .replace(/\{remove}/g, self._renderButton('remove'))
                .replace(/\{cancel}/g, self._renderButton('cancel'))
                .replace(/\{browse}/g, self._renderButton('browse'));
        },
        _renderButton: function (type) {
            var self = this, tmplt = self._getLayoutTemplate('btnDefault'), css = self[type + 'Class'],
                title = self[type + 'Title'], icon = self[type + 'Icon'], label = self[type + 'Label'],
                status = self.isDisabled ? ' disabled' : '', btnType = 'button';
            switch (type) {
                case 'remove':
                    if (!self.showRemove) {
                        return '';
                    }
                    break;
                case 'cancel':
                    if (!self.showCancel) {
                        return '';
                    }
                    css += ' hide';
                    break;
                case 'upload':
                    if (!self.showUpload) {
                        return '';
                    }
                    if (self.isUploadable && !self.isDisabled) {
                        tmplt = self._getLayoutTemplate('btnLink').replace('{href}', self.uploadUrl);
                    } else {
                        btnType = 'submit';
                    }
                    break;
                case 'browse':
                    if (!self.showBrowse) {
                        return '';
                    }
                    tmplt = self._getLayoutTemplate('btnBrowse');
                    break;
                default:
                    return '';
            }

            css += type === 'browse' ? ' btn-file' : ' fileinput-' + type + ' fileinput-' + type + '-button';
            // if (!$h.isEmpty(label)) {
            //     label = ' <span class="' + self.buttonLabelClass + '">' + label + '</span>';
            // }
            return tmplt.replace('{type}', btnType).replace('{css}', css).replace('{title}', title)
                .replace('{status}', status).replace('{icon}', icon).replace('{label}', label);
        },
        _renderThumbProgress: function () {
            var self = this;
            return '<div class="file-thumb-progress hide">' + self.progressTemplate.replace(/\{percent}/g, '0')
                    .replace(/\{status}/g, self.msgUploadBegin) + '</div>';
        },
        _renderFileFooter: function (caption, size, width, isError) {
            var self = this, config = self.fileActionSettings, rem = config.showRemove, drg = config.showDrag,
                upl = config.showUpload, zoom = config.showZoom, out, template = self._getLayoutTemplate('footer'),
                indicator = isError ? config.indicatorError : config.indicatorNew,
                title = isError ? config.indicatorErrorTitle : config.indicatorNewTitle;
            size = self._getSize(size);
            if (self.isUploadable) {
                out = template.replace(/\{actions}/g, self._renderFileActions(upl, rem, zoom, drg, false, false, false))
                    .replace(/\{caption}/g, caption).replace(/\{size}/g, size).replace(/\{width}/g, width)
                    .replace(/\{progress}/g, self._renderThumbProgress()).replace(/\{indicator}/g, indicator)
                    .replace(/\{indicatorTitle}/g, title);
            } else {
                out = template.replace(/\{actions}/g,
                    self._renderFileActions(false, false, zoom, drg, false, false, false))
                    .replace(/\{caption}/g, caption).replace(/\{size}/g, size).replace(/\{width}/g, width)
                    .replace(/\{progress}/g, '').replace(/\{indicator}/g, indicator)
                    .replace(/\{indicatorTitle}/g, title);
            }
            out = $h.replaceTags(out, self.previewThumbTags);
            return out;
        },
        _renderFileActions: function (showUpload, showDelete, showZoom, showDrag, disabled, url, key, isInit) {
            if (!showUpload && !showDelete && !showZoom && !showDrag) {
                return '';
            }
            var self = this,
                vUrl = url === false ? '' : ' data-url="' + url + '"',
                vKey = key === false ? '' : ' data-key="' + key + '"',
                btnDelete = '', btnUpload = '', btnZoom = '', btnDrag = '', css,
                template = self._getLayoutTemplate('actions'), config = self.fileActionSettings,
                otherButtons = self.otherActionButtons.replace(/\{dataKey}/g, vKey),
                removeClass = disabled ? config.removeClass + ' disabled' : config.removeClass;
            if (showDelete) {
                btnDelete = self._getLayoutTemplate('actionDelete')
                    .replace(/\{removeClass}/g, removeClass)
                    .replace(/\{removeIcon}/g, config.removeIcon)
                    .replace(/\{removeTitle}/g, config.removeTitle)
                    .replace(/\{dataUrl}/g, vUrl)
                    .replace(/\{dataKey}/g, vKey);
            }
            if (showDrag && isInit) {
                css = 'drag-handle-init ' + config.dragClass;
                btnDrag = self._getLayoutTemplate('actionDrag').replace(/\{dragClass}/g, css)
                    .replace(/\{dragTitle}/g, config.dragTitle)
                    .replace(/\{dragIcon}/g, config.dragIcon);
            }
            return template.replace(/\{delete}/g, btnDelete)
                .replace(/\{upload}/g, btnUpload)
                .replace(/\{zoom}/g, btnZoom)
                .replace(/\{drag}/g, btnDrag)
                .replace(/\{other}/g, otherButtons);
        },
        _browse: function (e) {
            var self = this;
            self._raise('filebrowse');
            if (e && e.isDefaultPrevented()) {
                return;
            }
            if (self.isError && !self.isUploadable) {
                self.clear();
            }
            self.$captionContainer.focus();
        },
        _filterDuplicate: function (file, files, fileIds) {
            var self = this, fileId = self._getFileId(file);
            if (fileId && fileIds && fileIds.indexOf(fileId) > -1) {
                return;
            }
            if (!fileIds) {
                fileIds = [];
            }
            files.push(file);
            fileIds.push(fileId);
        },
        _change: function (e) {

            var self = this, $el = self.$element;
            if (!self.isUploadable && $h.isEmpty($el.val()) && self.fileInputCleared) { // IE 11 fix
                self.fileInputCleared = false;
                return;
            }
            self.fileInputCleared = false;
            var tfiles = [], msg, total, isDragDrop = arguments.length > 1, isAjaxUpload = self.isUploadable, n, len,
                files = isDragDrop ? e.originalEvent.dataTransfer.files : $el.get(0).files, ctr = self.filestack.length,
                isSingleUpload = $h.isEmpty($el.attr('multiple')), flagSingle = (isSingleUpload && ctr > 0),
                folders = 0, fileIds = self._getFileIds(), throwError = function (mesg, file, previewId, index) {
                    var p1 = $.extend(true, {}, self._getOutData({}, {}, files), {id: previewId, index: index}),
                        p2 = {id: previewId, index: index, file: file, files: files};
                    return self.isUploadable ? self._showUploadError(mesg, p1) : self._showError(mesg, p2);
                };
            self.reader = null;
            self._resetUpload();
            self._hideFileIcon();
            if (self.isUploadable) {
                self.$container.find('.file-drop-zone .' + self.dropZoneTitleClass).remove();
            }
            if (isDragDrop) {
                $.each(files, function (i, f) {
                    if (f && !f.type && f.size !== undefined && f.size % 4096 === 0) {
                        folders++;
                    } else {
                        self._filterDuplicate(f, tfiles, fileIds);
                    }
                });
            } else {
                var files = e.target.files;
                for(var i = 0, f; f = files[i]; i++) {
                    var fsize = files[i].size;
                                    
                    if(fsize>="2110000") {
                        swal('Not allowed maximum upload size of 2 MB');
                        return false;
                    }
                }
                if(e.target && e.target.files === undefined) {
                    files = e.target.value ? [{name: e.target.value.replace(/^.+\\/, '')}] : [];
                } else {
                    files = e.target.files || {};
                }
                $.each(files, function (i, f) {
                    self._filterDuplicate(f, tfiles, fileIds);
                });
            }
            if ($h.isEmpty(tfiles) || tfiles.length === 0) {
                if (!isAjaxUpload) {
                    self.clear();
                }
                self._showFolderError(folders);
                self._raise('fileselectnone');
                return;
            }
            self._resetErrors();
            len = tfiles.length;
            total = self._getFileCount(self.isUploadable ? (self.getFileStack().length + len) : len);
            if (self.maxFileCount > 0 && total > self.maxFileCount) {
                if (!self.autoReplace || len > self.maxFileCount) {
                    n = (self.autoReplace && len > self.maxFileCount) ? len : total;
                    msg = self.msgFilesTooMany.replace('{m}', self.maxFileCount).replace('{n}', n);
                    self.isError = throwError(msg, null, null, null);
                    self.$captionContainer.find('.kv-caption-icon').hide();
                    self._setCaption('', true);
                    self.$container.removeClass('file-input-new file-input-ajax-new');
                    return;
                }
                if (total > self.maxFileCount) {
                    self._resetPreviewThumbs(isAjaxUpload);
                }
            } else {
                if (!isAjaxUpload || flagSingle) {
                    self._resetPreviewThumbs(false);
                    if (flagSingle) {
                        self.clearStack();
                    }
                } else {
                    if (isAjaxUpload && ctr === 0 && (!self.previewCache.count() || self.overwriteInitial)) {
                        self._resetPreviewThumbs(true);
                    }
                }
            }
            if (self.isPreviewable) {
                self._readFiles(tfiles);
            } else {
                self._updateFileDetails(1);
            }
            self._showFolderError(folders);
        },
        _abort: function (params) {
            var self = this, data;
            if (self.ajaxAborted && typeof self.ajaxAborted === "object" && self.ajaxAborted.message !== undefined) {
                data = $.extend(true, {}, self._getOutData(), params);
                data.abortData = self.ajaxAborted.data || {};
                data.abortMessage = self.ajaxAborted.message;
                self.cancel();
                self._setProgress(101, self.$progress, self.msgCancelled);
                self._showUploadError(self.ajaxAborted.message, data, 'filecustomerror');
                return true;
            }
            return false;
        },
        _resetFileStack: function () {
            var self = this, i = 0, newstack = [], newnames = [], newids = [];
            self._getThumbs().each(function () {
                var $thumb = $(this), ind = $thumb.attr('data-fileindex'), file = self.filestack[ind];
                if (ind === -1) {
                    return;
                }
                if (file !== undefined) {
                    newstack[i] = file;
                    newnames[i] = self._getFileName(file);
                    newids[i] = self._getFileId(file);
                    $thumb.attr({'id': self.previewInitId + '-' + i, 'data-fileindex': i});
                    i++;
                } else {
                    $thumb.attr({'data-fileindex': '-1'});
                }
            });
            self.filestack = newstack;
            self.filenames = newnames;
            self.fileids = newids;
        },
        clearStack: function () {
            var self = this;
            self.filestack = [];
            self.filenames = [];
            self.fileids = [];
            return self.$element;
        },
        updateStack: function (i, file) {
            var self = this;
            self.filestack[i] = file;
            self.filenames[i] = self._getFileName(file);
            self.fileids[i] = file && self._getFileId(file) || null;
            return self.$element;
        },
        addToStack: function (file) {
            var self = this;
            self.filestack.push(file);
            self.filenames.push(self._getFileName(file));
            self.fileids.push(self._getFileId(file));
            return self.$element;
        },
        getFileStack: function (skipNull) {
            var self = this;
            return self.filestack.filter(function (n) {
                return (skipNull ? n !== undefined : n !== undefined && n !== null);
            });
        },
        getFilesCount: function () {
            var self = this, len = self.isUploadable ? self.getFileStack().length : self.$element.get(0).files.length;
            return self._getFileCount(len);
        },
        lock: function () {
            var self = this;
            self._resetErrors();
            self.disable();
            if (self.showRemove) {
                $h.addCss(self.$container.find('.fileinput-remove'), 'hide');
            }
            if (self.showCancel) {
                self.$container.find('.fileinput-cancel').removeClass('hide');
            }
            self._raise('filelock', [self.filestack, self._getExtraData()]);
            return self.$element;
        },
        unlock: function (reset) {
            var self = this;
            if (reset === undefined) {
                reset = true;
            }
            self.enable();
            if (self.showCancel) {
                $h.addCss(self.$container.find('.fileinput-cancel'), 'hide');
            }
            if (self.showRemove) {
                self.$container.find('.fileinput-remove').removeClass('hide');
            }
            if (reset) {
                self._resetFileStack();
            }
            self._raise('fileunlock', [self.filestack, self._getExtraData()]);
            return self.$element;
        },
        cancel: function () {
            var self = this, xhr = self.ajaxRequests, len = xhr.length, i;
            if (len > 0) {
                for (i = 0; i < len; i += 1) {
                    self.cancelling = true;
                    xhr[i].abort();
                }
            }
            self._setProgressCancelled();
            self._getThumbs().each(function () {
                var $thumb = $(this), ind = $thumb.attr('data-fileindex');
                $thumb.removeClass('file-uploading');
                if (self.filestack[ind] !== undefined) {
                    $thumb.find('.kv-file-upload').removeClass('disabled').removeAttr('disabled');
                    $thumb.find('.kv-file-remove').removeClass('disabled').removeAttr('disabled');
                }
                self.unlock();
            });
            return self.$element;
        },
        clear: function () {
            var self = this, cap;
            if (!self._raise('fileclear')) {
                return;
            }
            self.$btnUpload.removeAttr('disabled');
            self._getThumbs().find('video,audio,img').each(function () {
                $h.cleanMemory($(this));
            });
            self._resetUpload();
            self.clearStack();
            self._clearFileInput();
            self._resetErrors(true);
            if (self._hasInitialPreview()) {
                self._showFileIcon();
                self._resetPreview();
                self._initPreviewActions();
                self.$container.removeClass('file-input-new');
            } else {
                self._getThumbs().each(function () {
                    self._clearObjects($(this));
                });
                if (self.isUploadable) {
                    self.previewCache.data = {};
                }
                self.$preview.html('');
                cap = (!self.overwriteInitial && self.initialCaption.length > 0) ? self.initialCaption : '';
                self.$caption.html(cap);
                self.$caption.attr('title', '');
                $h.addCss(self.$container, 'file-input-new');
                self._validateDefaultPreview();
            }
            if (self.$container.find($h.FRAMES).length === 0) {
                if (!self._initCaption()) {
                    self.$captionContainer.find('.kv-caption-icon').hide();
                }
            }
            self._hideFileIcon();
            self._raise('filecleared');
            self.$captionContainer.focus();
            self._setFileDropZoneTitle();
            return self.$element;
        },
        reset: function () {
            var self = this;
            if (!self._raise('filereset')) {
                return;
            }
            self._resetPreview();
            self.$container.find('.fileinput-filename').text('');
            $h.addCss(self.$container, 'file-input-new');
            if (self.$preview.find($h.FRAMES).length || self.isUploadable && self.dropZoneEnabled) {
                self.$container.removeClass('file-input-new');
            }
            self._setFileDropZoneTitle();
            self.clearStack();
            self.formdata = {};
            return self.$element;
        },
        disable: function () {
            var self = this;
            self.isDisabled = true;
            self._raise('filedisabled');
            self.$element.attr('disabled', 'disabled');
            self.$container.find(".kv-fileinput-caption").addClass("file-caption-disabled");
            self.$container.find(".btn-file, .fileinput-remove, .fileinput-upload, .file-preview-frame button").attr(
                "disabled",
                true);
            self._initDragDrop();
            return self.$element;
        },
        enable: function () {
            var self = this;
            self.isDisabled = false;
            self._raise('fileenabled');
            self.$element.removeAttr('disabled');
            self.$container.find(".kv-fileinput-caption").removeClass("file-caption-disabled");
            self.$container.find(
                ".btn-file, .fileinput-remove, .fileinput-upload, .file-preview-frame button").removeAttr("disabled");
            self._initDragDrop();
            return self.$element;
        },
        upload: function () {
            var self = this, totLen = self.getFileStack().length, params = {}, i, outData, len,
                hasExtraData = !$.isEmptyObject(self._getExtraData());
            if (!self.isUploadable || self.isDisabled) {
                return;
            }
            if (self.minFileCount > 0 && self._getFileCount(totLen) < self.minFileCount) {
                self._noFilesError(params);
                return;
            }
            self._resetUpload();
            if (totLen === 0 && !hasExtraData) {
                self._showUploadError(self.msgUploadEmpty);
                return;
            }
            self.$progress.removeClass('hide');
            self.uploadCount = 0;
            self.uploadStatus = {};
            self.uploadLog = [];
            self.lock();
            self._setProgress(2);
            if (totLen === 0 && hasExtraData) {
                self._uploadExtraOnly();
                return;
            }
            len = self.filestack.length;
            self.hasInitData = false;
            if (self.uploadAsync) {
                outData = self._getOutData();
                self._raise('filebatchpreupload', [outData]);
                self.fileBatchCompleted = false;
                self.uploadCache = {content: [], config: [], tags: [], append: true};
                self.uploadAsyncCount = self.getFileStack().length;
                for (i = 0; i < len; i++) {
                    self.uploadCache.content[i] = null;
                    self.uploadCache.config[i] = null;
                    self.uploadCache.tags[i] = null;
                }
                self.$preview.find('.file-preview-initial').removeClass($h.SORT_CSS);
                self._initSortable();
                self.cacheInitialPreview = self.getPreview();

                for (i = 0; i < len; i++) {
                    if (self.filestack[i] !== undefined) {
                        self._uploadSingle(i, self.filestack, true);
                    }
                }
                return;
            }
            self._uploadBatch();
            return self.$element;
        },
        destroy: function () {
            var self = this, $form = self.$form, $cont = self.$container, $el = self.$element, ns = self.namespace;
            $(document).off(ns);
            $(window).off(ns);
            if ($form && $form.length) {
                $form.off(ns);
            }
            $el.insertBefore($cont).off(ns).removeData();
            $cont.off().remove();
            return $el;
        },
        refresh: function (options) {
            var self = this, $el = self.$element;
            options = options ? $.extend(true, {}, self.options, options) : self.options;
            self.destroy();
            $el.fileinput(options);
            if ($el.val()) {
                $el.trigger('change.fileinput');
            }
            return $el;
        },
        zoom: function (frameId) {
            var self = this, $frame = $('#' + frameId), $modal = self.$modal;
            if (!$frame.length) {
                self._log('Cannot zoom to detailed preview! Invalid frame with id: "' + frameId + '".');
                return;
            }
            $h.initModal($modal);
            $modal.html(self._getModalContent());
            self._setZoomContent($frame);
            $modal.modal('show');
            self._initZoomButtons();
        },
        getPreview: function () {
            var self = this;
            return {
                content: self.initialPreview,
                config: self.initialPreviewConfig,
                tags: self.initialPreviewThumbTags
            };
        }
    };

    $.fn.fileinput = function (option) {
        if (!$h.hasFileAPISupport() && !$h.isIE(9)) {
            return;
        }
        var args = Array.apply(null, arguments), retvals = [];
        args.shift();
        this.each(function () {
            var self = $(this), data = self.data('fileinput'), options = typeof option === 'object' && option,
                theme = options.theme || self.data('theme'), l = {}, t = {},
                lang = options.language || self.data('language') || $.fn.fileinput.defaults.language || 'en', opt;
            if (!data) {
                if (theme) {
                    t = $.fn.fileinputThemes[theme] || {};
                }
                if (lang !== 'en' && !$h.isEmpty($.fn.fileinputLocales[lang])) {
                    l = $.fn.fileinputLocales[lang] || {};
                }
                opt = $.extend(true, {}, $.fn.fileinput.defaults, t, $.fn.fileinputLocales.en, l, options, self.data());
                data = new FileInput(this, opt);
                self.data('fileinput', data);
            }

            if (typeof option === 'string') {
                retvals.push(data[option].apply(data, args));
            }
        });
        switch (retvals.length) {
            case 0:
                return this;
            case 1:
                return retvals[0];
            default:
                return retvals;
        }
    };

    $.fn.fileinput.defaults = {
        language: 'en',
        showCaption: true,
        showBrowse: true,
        showPreview: true,
        showRemove: true,
        showUpload: true,
        showCancel: true,
        showClose: true,
        showUploadedThumbs: true,
        browseOnZoneClick: false,
        autoReplace: false,
        generateFileId: null,
        previewClass: '',
        captionClass: '',
        frameClass: 'krajee-default',
       // mainClass: 'file-caption-main',
        mainTemplate: null,
        purifyHtml: true,
        fileSizeGetter: null,
        initialCaption: '',
        initialPreview: [],
        initialPreviewDelimiter: '*$$*',
        initialPreviewAsData: false,
        initialPreviewFileType: 'image',
        initialPreviewConfig: [],
        initialPreviewThumbTags: [],
        previewThumbTags: {},
        initialPreviewShowDelete: true,
        removeFromPreviewOnError: false,
        deleteUrl: '',
        deleteExtraData: {},
        overwriteInitial: true,
        previewZoomButtonIcons: {
            prev: '<i class="glyphicon glyphicon-triangle-left"></i>',
            next: '<i class="glyphicon glyphicon-triangle-right"></i>',
            toggleheader: '<i class="glyphicon glyphicon-resize-vertical"></i>',
            fullscreen: '<i class="glyphicon glyphicon-fullscreen"></i>',
            borderless: '<i class="glyphicon glyphicon-resize-full"></i>',
            close: '<i class="glyphicon glyphicon-remove"></i>'
        },
        previewZoomButtonClasses: {
            prev: 'btn btn-navigate',
            next: 'btn btn-navigate',
            toggleheader: 'btn btn-default btn-header-toggle',
            fullscreen: 'btn btn-default',
            borderless: 'btn btn-default',
            close: 'btn btn-default'
        },
        preferIconicPreview: false,
        preferIconicZoomPreview: false,
        allowedPreviewTypes: undefined,
        allowedPreviewMimeTypes: null,
        allowedFileTypes: null,
        allowedFileExtensions: null,
        defaultPreviewContent: null,
        customLayoutTags: {},
        customPreviewTags: {},
        previewFileIcon: '<i class="glyphicon glyphicon-file"></i>',
        previewFileIconClass: 'file-other-icon',
        previewFileIconSettings: {},
        previewFileExtSettings: {},
        //buttonLabelClass: 'hidden-xs',
        browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>&nbsp;',
        browseClass: 'btn btn-primary',
        removeIcon: '<i class="glyphicon glyphicon-trash"></i>',
        removeClass: 'btn btn-default',
        cancelIcon: '<i class="glyphicon glyphicon-ban-circle"></i>',
        cancelClass: 'btn btn-default',
        uploadIcon: '<i class="glyphicon glyphicon-upload"></i>',
        uploadClass: 'btn btn-default',
        uploadUrl: null,
        uploadAsync: true,
        uploadExtraData: {},
        zoomModalHeight: 480,
        minImageWidth: null,
        minImageHeight: null,
        maxImageWidth: null,
        maxImageHeight: null,
        resizeImage: false,
        resizePreference: 'width',
        resizeQuality: 0.92,
        resizeDefaultImageType: 'image/jpeg',
        minFileSize: 0,
        maxFileSize: 0,
        maxFilePreviewSize: 25600, // 25 MB
        minFileCount: 0,
        maxFileCount: 0,
        validateInitialCount: false,
        msgValidationErrorClass: 'text-danger',
        msgValidationErrorIcon: '<i class="glyphicon glyphicon-exclamation-sign"></i> ',
        msgErrorClass: 'file-error-message',
        progressThumbClass: "progress-bar progress-bar-success progress-bar-striped active",
        progressClass: "progress-bar progress-bar-success progress-bar-striped active",
        progressCompleteClass: "progress-bar progress-bar-success",
        progressErrorClass: "progress-bar progress-bar-danger",
        progressUploadThreshold: 99,
        previewFileType: 'image',
        elCaptionContainer: null,
        elCaptionText: null,
        elPreviewContainer: null,
        elPreviewImage: null,
        elPreviewStatus: null,
        elErrorContainer: null,
        errorCloseButton: '<span class="close kv-error-close">&times;</span>',
        slugCallback: null,
        dropZoneEnabled: true,
        dropZoneTitleClass: 'file-drop-zone-title',
        fileActionSettings: {},
        otherActionButtons: '',
        textEncoding: 'UTF-8',
        ajaxSettings: {},
        ajaxDeleteSettings: {},
        showAjaxErrorDetails: true
    };

    $.fn.fileinputLocales.en = {
        fileSingle: 'file',
        filePlural: 'files',
        browseLabel: 'Browse &hellip;',
        removeLabel: 'Remove',
        removeTitle: 'Clear selected files',
        cancelLabel: 'Cancel',
        cancelTitle: 'Abort ongoing upload',
        uploadLabel: 'Upload',
        uploadTitle: 'Upload selected files',
        msgNo: 'No',
        msgNoFilesSelected: 'No files selected',
        msgCancelled: 'Cancelled',
        msgZoomModalHeading: 'Detailed Preview',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'File "{name}" (<b>{size} KB</b>) exceeds maximum allowed upload size of <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'You must select at least <b>{n}</b> {files} to upload.',
        msgFilesTooMany: 'Number of files selected for upload <b>({n})</b> exceeds maximum allowed limit of <b>{m}</b>.',
        msgFileNotFound: 'File "{name}" not found!',
        msgFileSecured: 'Security restrictions prevent reading the file "{name}".',
        msgFileNotReadable: 'File "{name}" is not readable.',
        msgFilePreviewAborted: 'File preview aborted for "{name}".',
        msgFilePreviewError: 'An error occurred while reading the file "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Invalid type for file "{name}". Only "{types}" files are supported.',
        msgInvalidFileExtension: 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
        msgFileTypes: {
            'image': 'image',
            'html': 'HTML',
            'text': 'text',
            'video': 'video',
            'audio': 'audio',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'object'
        },
        msgUploadAborted: 'The file upload was aborted',
        msgUploadThreshold: 'Processing...',
        msgUploadBegin: 'Initializing...',
        msgUploadEnd: 'Done',
        msgUploadEmpty: 'No valid data available for upload.',
        msgValidationError: 'Validation Error',
        msgLoading: 'Loading file {index} of {files} &hellip;',
        msgProgress: 'Loading file {index} of {files} - {name} - {percent}% completed.',
        msgSelected: '{n} {files} selected',
        msgFoldersNotAllowed: 'Drag & drop files only! {n} folder(s) dropped were skipped.',
        msgImageWidthSmall: 'Width of image file "{name}" must be at least {size} px.',
        msgImageHeightSmall: 'Height of image file "{name}" must be at least {size} px.',
        msgImageWidthLarge: 'Width of image file "{name}" cannot exceed {size} px.',
        msgImageHeightLarge: 'Height of image file "{name}" cannot exceed {size} px.',
        msgImageResizeError: 'Could not get the image dimensions to resize.',
        msgImageResizeException: 'Error while resizing the image.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Drag & drop files here &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        previewZoomButtonTitles: {
            prev: 'View previous file',
            next: 'View next file',
            toggleheader: 'Toggle header',
            fullscreen: 'Toggle full screen',
            borderless: 'Toggle borderless mode',
            close: 'Close detailed preview'
        }
    };

    $.fn.fileinput.Constructor = FileInput;

    /**
     * Convert automatically file inputs with class 'file' into a bootstrap fileinput control.
     */
    $(document).ready(function () {
        var $input = $('input.file[type=file]');
        if ($input.length) {
            $input.fileinput();
        }
        $("#time").val('1');
        $("#minute").val('0');
    });
}));
$(".upload-btn").click(function() {
  
  $('input.file[type=file]').trigger('change');
});
$("#unclose").click(function() {
    swal({
          title: "Are you sure?",
          text: "Are you sure you want to unclose this ticket!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, unclose it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
    function (isConfirm) {
        if (isConfirm) {
            $( "#forminout" ).submit();
        } 
    else {
        location.reload(); //swal("Cancelled", "Your customer is safe :)", "error");
      }
    }
  );
});

$(document).on('click','.sendtocustomer',function(e) {
    // $("#add-tickets").hide();
    // $("#send-emailinvoice").show();

    // var id = $(this).data('id');
    var email = $(this).data('email');
    var enteremail = $("#email").val();

    if(email!="") {
        if(IsEmail(email) == false) {
            swal("Email Validate ?", "Please enter validate Email address!", "error");
            return false;
        }
       $("#form").submit();
        return true;
    }
    if(enteremail!="") {
        if(IsEmail(enteremail) == false) {
            swal("Email Validate ?", "Please enter validate Email address!", "error");
            return false;
        }
        $("#form").submit();
        return true;
    }
    if(email == "" || enteremail=="") { 
        swal("Customer Email?", "Please add customer Email!", "error");
         return false;
    }
   

    //  $.ajax({
    //   url:"{{url('company/customer/leftbarviewinvoiceemail')}}",
    //   data: {
    //     id: id,
    //     email :email
    //   },
    //   method: 'post',
    //   dataType: 'json',
    //   refresh: true,
    //   success:function(data) {
    //     $('#viewinvoicemodaldata').html(data.html);
        
    //   }
    // });
});

function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    }
    else {
        return true;
    }
}
$(document).on('click','.cancelpopup',function(e) {
   location.reload();

});
</script>
@endsection