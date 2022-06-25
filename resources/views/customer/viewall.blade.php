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
.modal-ul-box {
    padding: 0;
    margin: 10px;
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
<div class="">
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>All Tickets ({{$address}})</h3>
       <a href="{{url('company/customer/view')}}/{{$customeridv}}" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
     Back</a>
     </div>

     </div>
<div class="col-lg-12">

<!-- Completed ticket section start -->
<div class="card mb-3 h-auto">
<div class="card-body">
  <div class="row align-items-center mb-3">
    <!-- <div>
      <h5>All Tickets</h5>
    </div> -->
  </div>
  <div class="table-responsive">
    <table id="example2" class="table no-wrap table-new table-list align-items-center">
    <thead>
    <tr>
    <th>Ticket number</th>
    <th>Customer Name</th>
    <th>Frequency</th>
    <th>Price</th>
    <th>Service Name</th>
    <th>Action</th>
    
    </tr>
    </thead>
    <tbody>
      @php
      $i = 1;
    @endphp
      @foreach($recentTicket as $ticket)
      @php
        $explode_id = explode(',', $ticket->serviceid);
        $servicedata = App\Models\Service::select('servicename')
          ->whereIn('services.id',$explode_id)->get();
      @endphp
    <tr>
    <td>#{{$ticket->id}}</td>
    <td>{{$ticket->customername}}</td>
    <td>{{$ticket->frequency}}</td>
    <td>{{$ticket->price}}</td>
    <td>@php
      $i=0;
    @endphp
    @foreach($servicedata as $servicename)
        @php
          if(count($servicedata) == 0){
            $servicename = '-';
          } else {
            $servicename = $servicename->servicename;
          }
        @endphp

        {{$servicename}}
      @if(count($servicedata)>1) 
        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" class="sup-dot service_list_dot" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#service-list-dot" id="service_list_dot" data-id="{{ $ticket->serviceid }}">
          <circle cx="5" cy="5" r="5" fill="#FA8F61"></circle>
        </svg>
        @endif
        @php
        $i=1; break;
      @endphp
    @endforeach</td>
    <td><a class="btn btn-edit p-3 w-auto" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$ticket->id}}">View</a>
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
<!-- Completed ticket end -->

</div>

    
     


     </div>
   </div>



          </div>
     
@endsection
<div class="modal fade" id="view-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <div id="viewcompletedmodal"></div>
      </div>
    </div>
  </div>
</div>
<!-- Dots modal start -->
<!-- dots Modal End -->
<!-- Dots modal start -->
<!-- dots Modal End -->


@section('script')
<script type="text/javascript">
  $(document).on('click','.emailinvoice',function(e) {
   var id = $(this).data('id');
   var email = $(this).data('email');
   $.ajax({
      url:"{{url('company/quote/leftbarinvoice')}}",
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
  $(document).ready(function() {
     $('#example').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
  });
  $(document).ready(function() {
     $('#example1').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
    
  });
  $(document).ready(function() {
     $('#example2').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
    
  });

   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $(".add-ticket-alert").click(function() {
      var id = $(this).data('id');
      $.ajax({
            url:"{{url('company/quote/updateticket')}}",
            data: {
              quoteid: id 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              
              swal({
                title: "Success!",
                text: "The ticket has been created!",
                type: "success"
            }, function() {
                location.reload();
            });
              
              
            }
        })
    
   });
  
  $(document).on('click','#createtickets',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewquotemodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewquotemodaldata').html(data.html);
            }
        })
  });
  $('html').on('click','.etc',function() {
  var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
    $('#etc').attr('min', maxDate);
  
 });
  function gethoursajax() {
        var h=0;
        var m=0;
        $('select.selectpicker2').find('option:selected').each(function() {
          h += parseInt($(this).data('hour'));
          m += parseInt($(this).data('min'));
          
        });
        var realmin = m % 60;
        var hours = Math.floor(m / 60);
        h = h+hours;
        
        $("#time").val(h);
        $("#minute").val(realmin);
      }
    
    $(document).on('change', 'select.selectpicker2',function() {
      gethoursajax();
    });

     
    $(document).ready(function() {
     function gethours() {
        var h=0;
        var m=0;
        $('select.selectpicker').find('option:selected').each(function() {
          h += parseInt($(this).data('hour'));
          m += parseInt($(this).data('min'));
          
        });
        var realmin = m % 60;
        var hours = Math.floor(m / 60);
        h = h+hours;
        
        $("#time").val(h);
        $("#minute").val(realmin);
      }
    
    $('select.selectpicker').on('change', function() {
      gethours();
    });
    $('.selectpicker1').selectpicker();
    function gethours1() {
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
    
    $('select.selectpicker1').on('change', function() {
      gethours1();
    });

    $('#customerid').on('change', function() {
      var customerid = this.value;
      $("#address1").html('');
        $.ajax({
          url:"{{url('company/quote/getaddressbyid')}}",
          type: "POST",
          data: {
          customerid: customerid,
          _token: '{{csrf_token()}}' 
          },
          dataType : 'json',
          success: function(result) {
          $('#address1').html('<option value="">Select Customer Address</option>'); 
            $.each(result.address,function(key,value) {
              $("#address1").append('<option value="'+value.address+'">'+value.address+'</option>');
            });
          }
      });
    });

    $('#customerid1').on('change', function() {
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
          $('#address2').html('<option value="">Select Customer Address</option>'); 
            $.each(result.address,function(key,value) {
              $("#address2").append('<option value="'+value.address+'">'+value.address+'</option>');
            });
          }
      });
    });    
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
          $('#address3').html('<option value="">Select Customer Address</option>'); 
            $.each(result.address,function(key,value) {
              $("#address3").append('<option value="'+value.address+'">'+value.address+'</option>');
            });
          }
      });
  }); 

 $('.selectpicker2').selectpicker();
    function gethours2() {
        var h=0;
        var m=0;
        $('select.selectpicker2').find('option:selected').each(function() {
          h += parseInt($(this).data('hour'));
          m += parseInt($(this).data('min'));
          
        });
        var realmin = m % 60;
        var hours = Math.floor(m / 60);
        h = h+hours;
        console.log(h);
        $("#time").val(h);
        $("#minute").val(realmin);
      }
    
    $('select.selectpicker2').on('change', function() {
      gethours2();
    });
 $(document).on('click','#editTickets',function(e) {
   $('.selectpicker2').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.vieweditticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldata1').html(data.html);
              $('.selectpicker2').selectpicker({
                size: 3
              });
            }
        })
  });

  $('html').on('click','.info_link1',function() {
      var tid = $(this).attr('dataval');
      swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this ticket!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
            $.ajax({
              url:"{{url('company/quote/deleteQuote')}}",
              data: {
                id: tid 
              },
              method: 'post',
              dataType: 'json',
              refresh: true,
              success:function(data) {
               swal("Done!","It was succesfully deleted!","success");
              location.reload();
              }
            })
          } 
          else {
            location.reload();
          }
        }
      );
    });
    function onlyNumberKey(evt) {
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }

   $(document).on('click','#viewTickets',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewcompleteticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewcompletedmodal').html(data.html);
            }
        })
  });

    $(document).ready(function () {
      $("#btnSubmit").click(function (event) {
        //stop submit the form, we will post it manually.
        event.preventDefault();
        // Get form
        var form = $('#my-form')[0];
        // FormData object 
        var data = new FormData(form);
        // If you want to add an extra field for the FormData
        data.append("page", "companyquote");
        // disabled the submit button
        $("#btnSubmit").prop("disabled", true);
        $.ajax({
          url:'{{route('company.savefieldquote')}}',
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

      $("#btnSubmit1").click(function (event) {
        //stop submit the form, we will post it manually.
        event.preventDefault();
        // Get form
        var form = $('#my-form1')[0];
        // FormData object 
        var data = new FormData(form);
        // If you want to add an extra field for the FormData
        data.append("page", "companyticket");
        // disabled the submit button
        $("#btnSubmit1").prop("disabled", true);
        $.ajax({
          url:'{{route('company.savefieldticket')}}',
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
              $("#btnSubmit1").prop("disabled", false);
              location.reload();
          },
          error: function (e) {
              $("#output").text(e.responseText);
              console.log("ERROR : ", e);
              $("#btnSubmit1").prop("disabled", false);
          }
        });
      });
    });
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