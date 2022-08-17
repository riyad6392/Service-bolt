@extends('layouts.header')
@section('content')
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
    height: auto;
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
     <div class="row">
      <div class="col-md-6">
        <div class="side-h3">
       <h3>Commission Report</h3>
        @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
     </div>
     </div>
     <div class="col-md-4">
        <div class="side-h3">
          <select class="form-select puser" name="pid" id="pid" required="">
            <option value="all">All Personnel</option> 
            @foreach($pdata as $key => $value)
            <option value="{{$value->id}}" @if(@$personnelid ==  $value->id) selected @endif> {{$value->personnelname}}</option>
            @endforeach
          </select>
        </div>
     </div>
     <div class="col-md-2">
       <div class="side-h3">
         <button type="submit" class="btn btn-block button" style="width:50%;height: 40px;">Run</button>
       </div>
     </div>

     <div class="col-md-12">
      <div class="card">
     <div class="card-body">
  
     @php
      $pagedata = App\Models\Managefield::select('*')
      ->where('page','companycustomer')->where('userid',$auth_id)->get();
     $cpagedta = count($pagedata);
     @endphp
     <div class="col-lg-12">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="position: relative;">
          <thead>
            <tr>
              <th style="display: none;">Id</th>
              <th>Personnel NAME</th>
              <th>Tickets Worked</th>
              <th>Flat Amount</th>
              <th>Variable Amount</th>
              <th>Total Payout Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="display: none;"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>
                
              </td>
            </tr>
           
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
  $('.dropify').dropify();
  $(document).ready(function() {
    $('#example').DataTable({
      
      "order": [[ 0, "desc" ]]
    });
    //   $('#example').DataTable( {
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'csv'
    //     ]
    // } );

  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
$(document).on('click','#service_list_dot',function(e) {
   var id = $(this).data('id');
   var name = $(this).data('name');
   //var dataString =  'id='+ id,'name='+ name;
   //alert(dataString);
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
$('#selector').delay(2000).fadeOut('slow');

$(document).on('click','#editCustomer',function(e) {
  $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewcustomermodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewmodaldata').html(data.html);
              $('.dropify').dropify();
              $('.selectpicker').selectpicker({
                size: 3
              });
            }
        })
  });

  function readURL(input) {
     if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function(e) {
             $('#bannerPreview12').css('background-image', 'url('+e.target.result +')');
             $('#bannerPreview12').hide();
             $('#bannerPreview12').fadeIn(650);
         }
         reader.readAsDataURL(input.files[0]);
     }
   }
   $('html').on('change','.bannerUpload',function(){
   //$(document).on("change","#bannerUpload",function() {
      $('.defaultimage').hide();
       $('#bannerPreview12').show();
     readURL(this);
   });

  $('html').on('click','.info_link1',function() {
        var customerid = $(this).attr('dataval');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this customer!",
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
                url:"{{url('company/customer/deleteCustomer')}}",
                data: {
                  id: customerid 
                },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Customer deleted succesfully!","success");
                location.reload();
                }
            })
          } 
          else {
            location.reload(); //swal("Cancelled", "Your customer is safe :)", "error");
          }
        }
      );
  });
  $(window).on('load', function () {
      $('.loadershow1').hide();
    })

  // $('#save_value').click(function() {
     
  //   $('.checkcolumn:checked').each(function() {
  //     alert($(this).val());
  //   });
  // });
  $(document).ready(function () {
    $("#btnSubmit").click(function (event) {
      //stop submit the form, we will post it manually.
      event.preventDefault();
      // Get form
      var form = $('#my-form')[0];
      // FormData object 
      var data = new FormData(form);
      // If you want to add an extra field for the FormData
      data.append("page", "companycustomer");
      // disabled the submit button
      $("#btnSubmit").prop("disabled", true);
      $.ajax({
        url:'{{route('company.savefieldpage')}}',
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
</script>
@endsection


