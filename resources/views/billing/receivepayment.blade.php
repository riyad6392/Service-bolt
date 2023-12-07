@extends('layouts.header')
@section('content')
<style type="text/css">


  
  .input_item{
    border: 2px solid #d4d4d4;
    border-radius: 3px;
    padding: 7px;
  }
  .input_item:focus{
    box-shadow: none;
    outline: none;
  }
  label{
    font-weight: 600;
  }


  .exportCSV{
  	    position: absolute;
    right: 27px;
    top: 6%;
  }
  
</style>
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3 class="align-items-center d-flex justify-content-between">Received Payments</h3>
        @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
     </div>
     </div>


     <div class="col-md-12">
       <div class="card">
     <div class="card-body">
     <div class="row" style="display: none;">
      <div class="col-lg-2 mb-2">
        Balance Sheet</div>
     <div class="col-lg-3 mb-2">
     <div class="show-fillter">
     <select id="inputState" class="form-select">
        <option>Show: A to Z</option>
        <!-- <option>By Service</option>
        <option>By Frequency</option>
        <option>By Company</option> -->
      </select>
     </div>
     </div>
     
     <div class="col-lg-4 mb-2 offset-lg-3" style="display: none;">
     <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search  Transaction">
     <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button>
     </div>
     
     </div>
     
     </div>
     <form action="{{ route('company.billingexport') }}" method="post">
          @csrf
          @php
            $exportids = 0;
            $exportids = implode(",",$ticketids);
          @endphp
          <input type="hidden" name="exportids" id="exportids" value="{{$exportids}}">
          <input type="hidden" name="customerid" id="customerid" value="{{$customerid}}">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <h6 class="mb-0">Select Customer</h6>

            <div class="exportCSV">
            	<button class="btn add-btn-yellow" type="submit" name="search" value="excel">Export to CSV</button>
            </div>

          </div>
        </form>
         <select class="form-select input_item puser selectpicker" data-placeholder="Select customer or Type in" data-live-search="true" id="cid">
        @foreach($customerData as $key => $value)
          <option value="{{$value->id}}" @if(@$customerid ==  $value->id) selected @endif>{{$value->customername}}</option>
        @endforeach
      </select> 
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
	            <th>Ticket #</th>
	            <th>Date of Payment</th>
	            <th>Invoice Paid</th>
	            <th>Method</th>
            </tr>
          </thead>
          <tbody>
            @foreach($balancesheet as $key => $value)
              @php
                    $newdate = date("M, d Y", strtotime($value->created_at));
                  @endphp
              <tr>
                <td>#{{$value->ticketid}}</td>
                  <td>{{$newdate}}</td>
                  <td>{{$value->amount}}</td>
                  @php
                     $checkinfo = App\Models\Quote::select('checknumber')->where('id',$value->ticketid)->first();
                     if($checkinfo->checknumber!=0 && $checkinfo->checknumber!="") {
                        $checknumber = "(". $checkinfo->checknumber. ")";
                     } else {
                        $checknumber = "";
                     }
                  @endphp
                   <td>{{$value->paymentmethod}} {{$checknumber}}</td>
              </tr>
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

@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

    $(document).ready(function () {
        var search = $(location).attr('search');
        var customerid = $("#customerid").val();
        if(search == "") {
            var ids = $("#ticketid").val();
            window.location.href = "?cid=" + customerid;
            
        }
	  	$(".puser").on('change', function() {
	    	cid = $("#cid").val();
	    	$("#customerid").val(cid);
	        window.location.href = "?cid=" + cid;
	  	});
 	});
 </script>
@endsection


