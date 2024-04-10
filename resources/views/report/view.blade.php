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
    <a href="{{route('company.report')}}" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
     Back</a>
      <div class="side-h3">
         <h3>Invoices</h3>
         
      </div>

     </div>
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
        <th>Service Address</th>
        <th>Personnel Name</th>
        <th>Amount</th>
        <th>Payment status</th>
      </tr>
      </thead>
      <tbody>
      @php
        $i = 1;
      @endphp
      @foreach($totalInvoiceData as $key=>$value)
      @php
        if($value->parentid!=0) {
          $ids= $value->parentid;
        } else {
          $ids = $value->id;
        }
        
        $pstatus = "Pending";
        
      @endphp

        <tr class="" target="{{$i}}" data-id="{{$value->id}}">
          <td>#{{$ids}}</td>
          <td><a class="btn add-btn-yellow w-100 viewinvoice" data-id="{{$value->id}}" data-duedate="{{$value->duedate}}" data-invoicenote="{{$value->invoicenote}}" data-bs-toggle="modal" data-bs-target="#view-invoice">#{{$value->invoiceid}}</a></td>
          <td>{{date('m-d-Y', strtotime($value->date))}}</td>
          <td>{{$value->customername}}</td>
          <td>{{$value->address}}</td>
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

<div class="modal fade" id="send-emailinvoice" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.viewinvoice') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="invoicetype" id="invoicetype" value="sendinvoice">
          <div id="viewinvoicemodaldatainvoiced"></div>
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
        <form method="post" action="{{ route('company.viewinvoice') }}" enctype="multipart/form-data" target="_blank">
          @csrf
          <div id="viewdueinvoicemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

<div class="modal fade" id="edit-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
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
              $("#btnSubmit").prop("disabled", false);
              location.reload();
          },
          error: function (e) {
              $("#output").text(e.responseText);
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
        $('#viewservicelistdata').html(data.html);
      }
  })
 });

 $(document).on('click','.viewinvoice',function(e) {
   var id = $(this).data('id');
   var duedate = $(this).data('duedate');
   var invoicenote = $(this).data('invoicenote');
    $.ajax({
      url:"{{url('company/customer/leftbarviewinvoice')}}",
      data: {
        id: id,
        duedate: duedate,
        invoicenote: invoicenote,
      },
      method: 'post',
      dataType: 'json',
      refresh: true,
      success:function(data) {
        $('#viewdueinvoicemodaldata').html(data.html);
        
      }
    });
  })

  $(document).on('click','.sendtocustomer',function(e) {
        $("#view-invoice").hide();
        $("#send-emailinvoice").show();

        var id = $(this).data('id');
        var email = $(this).data('email');

         $.ajax({
          url:"{{url('company/customer/leftbarviewinvoiceemail')}}",
          data: {
            id: id,
            email :email
          },
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#viewinvoicemodaldatainvoiced').html(data.html);
            
          }
        });
       return false;
    })
  $(document).on('click','.cancelpopup',function(e) {
   location.reload();
  }); 

  $(function () {
  $("#from").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  });

   $("#to").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  });
});

  $(document).on('click','#editTickets',function(e) {
   $('.selectpicker2').selectpicker();
   var id = $(this).data('id');

   var pvalue = $(this).data('pid');
   
   var type = $(this).data('type');
   if(type==undefined) {
    var type = "quote";
   }
   var dataString =  'id='+ id+ '&type='+ type;
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

  $(document).on('change','#serviceid',function(e) {
  gethours();
  var qid = $('#quoteid').val();
  if(qid==undefined) {
      var qid = "";
  }
  var serviceid = $('#serviceid').val();
  var productid = $('#productid').val(); 
    
    $(document).find('#testprice').empty('');
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;

    $.ajax({
          url:'{{route('company.calculatebillingprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            console.log(data);
            $('#priceticketedit').val(data.totalprice);
            $('#tickettotaledit').val(data.totalprice);
            $('#edithiddenprice').val(data.totalprice);
            $('#testprice').append(data.hourpricehtml);
        }
      })
})
$(document).on('change','#productid',function(e) {
    var serviceid = $('#serviceid').val();
    var productid = $('#productid').val(); 
    var qid = $('#quoteid').val();
    if(qid==undefined) {
      var qid = "";
    }
    $(document).find('#testprice1').empty('');
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('company.calculatebillingprice')}}',
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


