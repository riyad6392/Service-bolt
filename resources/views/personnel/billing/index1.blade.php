@extends('layouts.workerheader')
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
   <form method="post" action="{{ url('personnel/billing/update') }}" enctype="multipart/form-data">
      @csrf
     <div class="row">

      <div class="col-md-12">
        <div class="side-h3">
       <h3>Billing & Payments</h3>
       <!-- <p style="color: #B0B7C3;">Lorem ipsum dolor sit amet</p> -->
     </div>
     </div>
      @if(Session::has('success'))

          <div class="alert alert-success" id="selector">

              {{Session::get('success')}}

          </div>

      @endif
<div class="col-md-12 mb-4">
<div class="card">
     <div class="card-body">
     <h5 class="mb-4">List of Payments</h5>
     <div class="row">

     
     <div class="col-lg-8 mb-2" style="visibility: hidden;">
     <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search"/>
     <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button>
     </div>
     
     </div>
     
     <div class="col-lg-3 offset-lg-1 text-center mb-2" style="display:none;">
     <a href="#" class="add-btn-yellow">
    Add Payment +
     </a>
     </div>
     
     </div>
      
     <div class="col-lg-12 mt-2">
     <div class="table-responsive">
    
     <table id="example" class="table no-wrap table-new table-list align-items-center">
    <thead>
    <tr>
        <th>Date</th>
        <th>Ticket Total</th>
        <th>Billing Total</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @php
      $i = 1;
    @endphp
    @foreach($totalbillingData as $key=>$value)
      <tr class="" target="{{$i}}" data-id="{{$value->id}}">
        <td>{{$value->date}}</td>
        <td>{{number_format((float)$value->totalprice, 2, '.', '')}}</td>
        <td>{{number_format((float)$value->totalprice, 2, '.', '')}}</td>
        <td><a href="{{url('personnel/billing/billingview/')}}/{{$value->date}}" class="user-hover" style="color:#29DBBA;">View</a></td>
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
        <form method="post" action="{{ route('worker.sendbillinginvoice') }}" enctype="multipart/form-data">
          @csrf
          <div id="viewinvoicemodaldata"></div>
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
            url:"{{url('personnel/billing/leftbarbillingdata')}}",
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
            url:"{{url('personnel/billing/leftbarbillingdata')}}",
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
          url:'{{route('worker.savefieldbilling')}}',
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
      url:"{{url('personnel/billing/leftbarinvoice')}}",
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
      url:'{{route('worker.viewservicepopup')}}',
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


