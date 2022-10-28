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
</style>
<div class="content">
  <div class="col-md-12">
        <div class="side-h3">
       <h3>Billing & Payments</h3>
       <a href="{{route('company.billing')}}" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
     Back</a>
     </div>
     <form method="post" action="{{route('company.billingview') }}" class="row pe-0">
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
   </div>
   <form method="post" action="{{ url('company/billing/paynow') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="date" id="date" value="{{$date}}">
     <div class="row">

      


      @if(Session::has('success'))

          <div class="alert alert-success" id="selector">

              {{Session::get('success')}}

          </div>

      @endif
      <!-- modal start -->
      @if(count($billingData)>0)
        <div id="viewleftbarbillingdata" class="col-md-4 mb-4"></div>
      @endif
      <!-- modal end -->
    
@php
  if(count($billingData)>0) {
    $class = "col-md-8 mb-4";
  } else {
    $class = "col-md-12 mb-4";
  }
@endphp

<div class="{{$class}}">

<div class="card">

     <div class="card-body">
      
     <h5 class="mb-4">{{$datef}}</h5>
      @php
        $pagedata = App\Models\Managefield::select('*')
        ->where('page','companybilling')->where('userid',$auth_id)->get();
        $cpagedta = count($pagedata);
      @endphp
     <div class="col-lg-12 mt-2">
     <div class="table-responsive">
    
     <table id="example" class="table no-wrap table-new table-list align-items-center">
    <thead>
    <tr>
        <th>Customer</th>
        <th>Ticket Total</th>
        <th>Personnel</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @php
      $i = 1;
    @endphp
    @foreach($billingData as $key=>$value)
      <tr class="user-hover showSingle" target="{{$i}}" data-id="{{$value->id}}">
        <input type="hidden" name="personnelid" id="personnelid" value="{{$value->personnelid}}">
        <td>{{$value->customername}}</td>
        <td>{{number_format((float)$value->price, 2, '.', '')}}</td>
        <td>
          @if($value->personnelname!="")
            {{@$value->personnelname}}
           @else
            --
          @endif
        </td>
        <td>
          View
        </td>
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

<!-- Dots modal start -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
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
                ->where('page','companybilling')->where('columname',$value)->where('userid',$auth_id)->first();
                
               @endphp
                @if($value=="customername" || $value=="price" || $value=="servicename" || $value=="payment_status")
                <li><label> <input type="checkbox" class="checkcolumn me-2" name="checkcolumn[]" value="{{$value}}" {{ (@$pagedata->columname) == $value ? 'checked' : '' }}>{{strtoupper($value)}} </label></li>
                  
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

<!-- Due invoice modal -->
<div class="modal fade" id="view-invoice" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.downloadinvoiceview') }}" enctype="multipart/form-data">
          @csrf
          <div id="viewdueinvoicemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

<div class="modal fade" id="service-list-dot" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
       <div id="viewservicelistdata"></div>
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
    $("#example tbody > tr:first-child").addClass('selectedrow');
  });

  $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
  jQuery(function() {
    var pid = $("#pid").val();
    var to = $("#to").val();
     var date = $("#date").val();
    $(document).on('click','.showSingle',function(e) {
        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        //var date = $(this).attr('data-etc');
        $.ajax({
            url:"{{url('company/billing/leftbarbillingdata')}}",
            data: {
              targetid: targetid,
              serviceid: serviceid,
               date: date,
               pid: pid,
               to: to,
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
              serviceid: 0,
              date: date,
              pid: pid,
              to: to,
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

  $('table tr').each(function(a,b) {
    $(b).click(function() {
         $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
    });
  });
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

  $(document).on('click','.viewinvoice',function(e) {
   var id = $(this).data('id');
   var duedate = $(this).data('duedate');

   $.ajax({
      url:"{{url('company/billing/leftbarviewinvoice')}}",
      data: {
        id: id,
        duedate: duedate,
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {
        console.log(data.html);
        $('#viewdueinvoicemodaldata').html(data.html);
        
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


