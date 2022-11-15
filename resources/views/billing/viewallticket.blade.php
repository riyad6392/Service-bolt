@extends('layouts.header')
@section('content')
<style type="text/css">
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
  .side-h3 {
    padding: 30px 0;
    display: flex;
    justify-content: space-between;
    width: 100%;
}
</style>
<div class="content">
  <div class="col-md-12">
      <div class="side-h3">
         <h3>Invoiced Tickets</h3>
         <form action="{{ route('company.viewallticketfilter') }}" method="post">
          @csrf
          <div class="row">
            
            <div class="col-md-2">
             <button class="btn add-btn-yellow py-2 px-5" type="submit" name="search" value="excel">{{ __('Export') }}</button>
            </div>
          </div>
         </form>
      </div>

     </div>
  <form method="post" action="{{route('company.viewallticket') }}" class="row pe-0">
      @csrf
      <input type="hidden" name="phiddenid" id="phiddenid" value="">
      <div class="col-lg-3 mb-3">
        <select class="form-select puser" name="pid" id="pid">
          <option value="">Select Personnel</option> 
          @foreach($personnelUser as $key => $value)
            <option value="{{$value->id}}" @if(@$pid ==  $value->id) selected @endif>{{$value->personnelname}}</option>
          @endforeach
        </select>
      </div>
      @if(isset($from))
        <div class="col-lg-3 mb-3">
          <input type="date" id="from" value="{{$from}}" name="from" class="form-control">
        </div>
        <div class="col-lg-3 mb-3">
          <input type="date" id="to" value="{{$to}}" name="to" class="form-control">
        </div>
      @else
        <div class="col-lg-3 mb-3">
          <input type="date" id="from" value="{{@$_REQUEST['from']}}" name="from" class="form-control">
        </div>
        @if(!isset($_REQUEST['to']))
        <div class="col-lg-3 mb-3">
          <input type="date" id="to" value="{{@$_REQUEST['from']}}" name="to" class="form-control">
        </div>
        @else
          <div class="col-lg-3 mb-3">
          <input type="date" id="to" value="{{@$_REQUEST['to']}}" name="to" class="form-control">
        </div>
        @endif
      @endif
      <div class="col-lg-3 mb-3 pe-0" >
       <button class="btn btn-block button" type="submit" id="search1">Search</button>
      </div>

     </div>
     </form>
   <form method="post" action="{{ url('company/billing/update') }}" enctype="multipart/form-data">
      @csrf
     <div class="row">
    @if(Session::has('success'))

          <div class="alert alert-success" id="selector">

              {{Session::get('success')}}

          </div>

      @endif
<div class="col-md-12 mb-4">
<div class="card">
     <div class="card-body">
     <div class="col-lg-12 mt-2">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list align-items-center">
      <thead>
      <tr>
        <th>#</th>
        <th>Invoice Id</th>
        <th>Date</th>
        <th>Customer Name</th>
        <th>Personnel Name</th>
        <th>Amount</th>
        <th>Payment status</th>
      </tr>
      </thead>
      <tbody>
      @php
        $i = 1;
      @endphp
      @foreach($totalbillingData as $key=>$value)
      @php
        if($value->payment_status!="" || $value->payment_mode!="") {
          $pstatus = "Paid";
        } 
        elseif($value->invoiced=="1") {
          $pstatus = "invoiced";
        }
        elseif($value->invoiced=="0" && $value->payment_mode=="") {
          $pstatus = "Pending";
        }
      @endphp

        <tr class="" target="{{$i}}" data-id="{{$value->id}}">
          <td>#{{$value->id}}</td>
          <td>#{{$value->invoiceid}}</td>
          <td>{{date('m-d-Y', strtotime($value->date))}}</td>
          <td>{{$value->customername}}</td>
          <td>{{$value->personnelname}}</td>
          <td>{{$value->price}}</td>
          <td>{{$pstatus}}</td>
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
   </form>
   </div>
</div>

<div class="modal fade" id="edit-address" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.sendbillinginvoice') }}" enctype="multipart/form-data">
          @csrf
          <div id="viewinvoicemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>
@endsection

@section('script')
<script type="text/javascript">

  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });
   // $("#example tbody > tr:first-child").addClass('selectedrow');
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
        $.ajax({
            url:"{{url('company/billing/leftbarbillingdata')}}",
            data: {
              targetid: targetid,
              serviceid: serviceid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftbarbillingdata').html(data.html);
            }
        })
    });

    $.ajax({
            url:"{{url('company/billing/leftbarbillingdata')}}",
            data: {
              targetid: 0,
              serviceid: 0 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftbarbillingdata').html(data.html);
            }
        })
  });

  // $('table tr').each(function(a,b) {
  //   $(b).click(function() {
  //        $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
  //   });
  // });
  $('#selector').delay(2000).fadeOut('slow');

    $(document).ready(function () {
      $("#btnSubmit").click(function (event) {
        //stop submit the form, we will post it manually.
        event.preventDefault();
        // Get form
        var form = $('#my-form')[0];
        // FormData object 
        var data = new FormData(form);
        // If you want to add an extra field for the FormData
        data.append("page", "companybilling");
        // disabled the submit button
        $("#btnSubmit").prop("disabled", true);
        $.ajax({
          url:'{{route('company.savefieldbilling')}}',
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

  $(document).on('click','.emailinvoice',function(e) {
   var id = $(this).data('id');
   var email = $(this).data('email');
   $.ajax({
      url:"{{url('company/billing/leftbarinvoice')}}",
      data: {
        id: id,
        email :email
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {
        console.log(data.html);
        $('#viewinvoicemodaldata').html(data.html);
        
      }
    });
  })

  $(document).on('click','.service_list_dot',function(e) {
   var id = $(this).data('id');
   var name = "Service Name";
   $.ajax({
      url:'{{route('company.viewservicepopup')}}',
      data: {
        'id':id,
        'name':name
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {

        console.log(data.html);
        $('#viewservicelistdata').html(data.html);
      }
  })
 });
</script>
@endsection


