@extends('layouts.header')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
<style type="text/css">
  #loader1 {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
.loadershow1 {
    height: 100%;
    position: absolute;
    left: 0;
    width: 100%;
    z-index: 10;
    background: rgb(35 35 34 / 43%);
    padding-top: 15%;
    display: flex;
    justify-content: center;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
.accept-btn {
    background-color: limegreen;
    color: #fff;
    padding: 5px 8px!important;
}
.reject-btn {
    background-color: red;
    color: #fff;
    padding: 5px 8px!important;
}

tr.rejected-row:after {
    background-color: #FFCCCB !important;
     width: 72%!important;
}


tr.accepted-row:after {
    background-color: #90EE90 !important;
     width: 72%!important;
}
</style>
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3 d-flex justify-content-between">
       <h3>Import</h3>
     
       
     </div>
     </div>
     @if(Session::has('success'))

<div class="alert alert-success" id="selector">

    {{Session::get('success')}}

</div>

@endif

     <div class="col-md-12">
      <div class="card">
     <div class="card-body">
     <div class="row">
      
     <form action="{{ route('company.importservices') }}" method="post" enctype="multipart/form-data">
      @csrf
      <input type="file" name="file" accept=".xlsx, .xls">
      <button type="submit">Import Services</button>
    </form>
@endsection

@section('script')
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
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
</script>
@endsection


