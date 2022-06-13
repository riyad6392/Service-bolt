@extends('layouts.header')
@section('content')
      
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Reports</h3>
     </div>
     </div>

     

<div class="col-lg-12">
<div class="card mb-3 h-auto">
<div class="card-body">
<div class="row align-items-center mb-3">
<div class="col-lg-2 mb-2">
Service Report</div>
	   <div class="col-lg-3 mb-2" style="display: none;">
	   <div class="show-fillter">
	    <select id="inputState" class="form-select">
				<option>Show: A to Z</option>
				<option>Show: Z to A</option>
			</select>
	   </div>
	   </div>
	   
	   <div class="col-lg-5 mb-2 offset-lg-2" style="margin-left:415px;">
	   <div class="show-fillter">
	   <input type="text" class="form-control" placeholder="Search Reports">
	   <button class="search-icon">
	   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
	   </button>
	   </div>
	   
	   </div>
	   
	   
	   
	   </div>
	   
	   
	   <ul class="nav report-tabs mb-3" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Service Report</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Product Sold</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Personnel</button>
  </li>
   <li class="nav-item" role="presentation">
    <button class="nav-link" id="sale-tab" data-bs-toggle="tab" data-bs-target="#sale" type="button" role="tab" aria-controls="sele" aria-selected="false">Sales Report</button>
  </li>
  
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission" type="button" role="tab" aria-controls="commission" aria-selected="false">Commission Report</button>
  </li>
  
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  <div class="table-responsive">
	  
	   <table class="table no-wrap table-new table-list align-items-center">
	  <thead>
	  <tr>
	  <th>Ticket number</th>
	  <th>Customer Name</th>
	  <th>Service location</th>
	  <th>Personel</th>
	  <th>Service Provided</th>
	  <th>Cost</th>
	  <th>Status</th>
	  
	  </tr>
	  </thead>
	  <tbody>
	  	No record found
	  <!-- <tr>
	  <td>1</td>
	  <td>John Tomas</td>
	  <td>123 Street Russell...</td>
	  <td>Billy Thomas</td>
	  <td>Stump Grinding</td>
	  <td>$355,00</td>
	  <td><a href="#" class="btn-incompleted btn-common">Pending</a></td>
	  
	  </tr> -->
	  
	  </tbody>
	  </table>
	  
	 </div>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Coming Soon</div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Coming Soon</div>
  <div class="tab-pane fade" id="sale" role="tabpanel" aria-labelledby="sale-tab">Coming Soon</div>
  <div class="tab-pane fade" id="commission" role="tabpanel" aria-labelledby="commission-tab">Coming Soon</div>
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
    $('#example').DataTable();
    

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
</script>
@endsection


