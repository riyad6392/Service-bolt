@extends('layouts.header')
@section('content')
<style type="text/css">
.table-new tbody tr.selectedrow:after {
    /*background: #FDFBEC;*/
    background: #FAED61 !important;

}
span.fa.fa-fw.fa-eye.field_icon.toggle-password {
    position: absolute;
    right: 8%;
}
span.fa.fa-fw.field_icon.toggle-password.fa-eye-slash{
  position: absolute;
    right: 8%;
}
input[type="date"]::-webkit-calendar-picker-indicator {
}
.inner.show {
    height: 102px;
}
</style>
<div class="">
<div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       	  <h3>Personnel</h3>
       	</div>
     </div>
@if(count($PersonnelData)>0)
 <div class="col-md-4 mb-4">
    <div class="card" style="position: sticky;top: 46px;">
    	<div id="viewleftservicemodal"></div>
	  </div>
</div>
@endif
@php
	if(count($PersonnelData)>0) {
		$class = "col-md-8 mb-4";
	} else {
		$class = "col-md-12 mb-4";
	}
@endphp
	<div class="{{$class}}">
	 <div class="card">
	   <div class="card-body">
	   <h5 class="align-items-center d-flex justify-content-between mb-4">Personnel List <a href="#"  data-bs-toggle="modal" data-bs-target="#add-personnel " class="add-btn-yellow">
	   Add Personnel +
	   </a></h5>
	   <div class="row">
     	@if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif

          @if(Session::has('error'))
             <div class="alert alert-danger" id="selector">

                  {{Session::get('error')}}

              </div>     
          @endif 

         

	   
	   <!-- <div class="col-lg-8 mb-2" style="visibility: hidden;">
	   <div class="show-fillter">
	   <input type="text" class="form-control" placeholder="Search Personnel"/>
	   <button class="search-icon">
	   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
	   </button>
	   </div>
	   
	   </div>
	   
	   <div class="col-lg-3 offset-lg-1 text-center mb-2">
	   
	   </div> -->
	   
	   </div>
	   
	   <div class="col-lg-12 mt-2">
	   <div class="table-responsive">
        <table id="example" class="table no-wrap table-new table-list" style="position: relative;">
          <thead>
            <tr>
              <th style="display: none;">ID</th>
              <th>NAME</th>
              <th>PHONE NUMBER</th>
              <th>EMAIL</th>
            </tr>
          </thead>
          <tbody>
          	@php
              $i = 1;
            @endphp
           @if(count($PersonnelData)>0)
          	@foreach($PersonnelData as $personnel)
            <tr target="{{$i}}" data-id="{{$personnel->id}}" class="user-hover showSingle">
              <td style="display: none;">{{$personnel->id}}</td>
              <td>
		
		  	   <div class="user-descption align-items-center d-flex">
				   <div class="user-img me-3">
				   @if($personnel->image!=null)
				   	<img src="{{url('uploads/personnel/thumbnail')}}/{{$personnel->image}}" alt="">
				   	@else
				   	<img src="{{url('uploads/servicebolt-noimage.png')}}" alt="">
				   @endif
				   </div>
				   <div class="user-content">
				   	<h5 class="m-0"><span class="" >{{$personnel->personnelname}}</span></h5>
				   </div>
			   </div>
			  
			  </td>
              <td>{{$personnel->phone}}</td>
              <td>{{$personnel->email}}</td>
             </tr>
             @php
              $i++;
             @endphp
             @endforeach
            @endif
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
 <!-- Modal -->

      
<!-- scheduler start -->	  
   <div class="modal fade" id="see-schedule" tabindex="-1" aria-labelledby="see-scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      	<div class="custom-calender">
             <div class="ev-calender-title">
             <h3><span id="spanid" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{date('l - F d, Y')}}">{{date('l - F d, Y')}}</span></h3>
         </div>
        <form method="post" action="{{ route('company.personnelupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata1"></div>
	        </form>
	      </div>
	  </div>
	     </div>
	  </div>
	</div>   

<!-- Timesheet Start -->
<div class="modal fade" id="see-timesheet" tabindex="-1" aria-labelledby="see-scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
     <div class="modal-body p-4">
       <h5 class="mb-4">Time Sheets</h5>
		<!-- <form method="post" action="{{ route('company.timesheetupdate') }}" enctype="multipart/form-data">
        @csrf -->
        	<div id="viewmodaldatatimesheet"></div>
	    <!-- </form>  --> 
    </div>
    </div>
  </div>
</div>
<!-- Timesheet end --> 
<!-- Timeoff Start -->
<div class="modal fade" id="see-timeoff" tabindex="-1" aria-labelledby="see-scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
     <div class="modal-body p-4">
       <h5 class="mb-4">PTO</h5>
    <!-- <form method="post" action="{{ route('company.timesheetupdate') }}" enctype="multipart/form-data">
        @csrf -->
          <div id="viewmodaldatatimeoff"></div>
      <!-- </form>  --> 
    </div>
    </div>
  </div>
</div>
<!-- Timeoff end -->


<div class="modal fade" id="add-personnel" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content customer-modal-box overflow-hidden">
     <form class="form-material m-t-40  form-valide" method="post" action="{{route('company.personnelcreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
       <div class="add-customer-modal">
	   	<h5>Add A New Personnel</h5>
	   </div>
	   
	   <div class="row customer-form">
	   <div class="col-md-12 mb-3">
	   
	   <input type="text" class="form-control" placeholder="Personnel Name" name="personnelname" id="personnelname" value="{{ old('personnelname') }}" required="">
	
	   </div>

	   <div class="col-md-12 mb-3">
		   <div class="input_fields_wrap">
	    		<div class="mb-3">
			    	<input type="text" class="form-control" placeholder="Address" name="address" id="address" value="{{ old('address') }}" required="">
			    </div>
			</div>
	 	</div>
	   
	   <div class="col-md-12 mb-3">
	   
	   <input type="text" class="form-control" placeholder="Phone Number" name="phone" id="phone" value="{{ old('phone') }}" required="">
	   
	   </div>
	   
	   <div class="col-md-12 mb-3">
	  	<input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ old('email') }}" required="">
      @error('email')
        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
      @enderror
	   </div>

	   <div class="col-md-12 mb-3 position-relative">
	  	<input type="password" class="form-control" placeholder="Password" name="wpassword" id="wpassword" value="{{ old('wpassword') }}" required=""><span id="toggle_pwd" class="fa fa-fw fa-eye field_icon" style="position: absolute;
    right: 4%;top:17px;"></span>
	   </div>
	   
	@php
	  	$permission = array (
        '7'=>"Administrator",
	  		'5'=>"Create Ticket",
	  		'6'=>"Add Customer",
        '4'=>"Edit Customer",
        '8'=>"Add Service",
        '9'=>"Add Product",
        '1'=>"Create Invoice for payment",
        '2'=>"Generate PDF for invoice"
	  	);
	@endphp
	   <div class="col-md-12 mb-3">
	  	<div class="multbox-modal">
			<select class="form-control selectpicker " multiple="" data-placeholder="Select Permissions" data-live-search="true" style="width: 100%;" tabindex="-1" aria-hidden="true" name="ticketid[]" id="ticketid" required="">
				@foreach($permission as $key =>$value)
                 <option value="{{$value}}">{{$value}}</option>
                @endforeach
            </select>
  		</div>
	   </div>
	   
	   <div class="col-md-12">
	   	<div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
	    <!-- <div class="drop-zone">
		    <span class="drop-zone__prompt text-center">
			<small><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></small>
			Drop file here or click to upload</span>
		    <input type="file" name="image" id="image" class="drop-zone__input" accept="image/png, image/gif, image/jpeg" />

  		</div> -->
      <input type="file" class="dropify" name="image" id="image"data-max-file-size="2M" data-allowed-file-extensions='["jpg", "jpeg","png","gif","svg","bmp"]' accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg">


	   </div>
	   
	   
	   <div class="row mt-3"><div class="col-lg-6 mb-3">
	   	<button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
     </div>
	   <div class="col-lg-6 mb-3">
	   	<button type="submit" class="btn btn-add btn-block">Add a Personnel</button>
	   </div></div>
	   
	   </div>
      </div>
     </form>
    </div>
  </div>
</div>

<!----------------------edit form------------>
<div class="modal fade" id="edit-personnel" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('company.personnelupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata"></div>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-timesheet" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
<form method="post" action="{{ route('company.timeupdate') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="timeid" id="timeid" value="">
        <h4><span id="date" style="pointer-events: none;"><input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span></h4>
        <span id="spanid"><input type="hidden" name="date" id="date" value=""></span>
        <div class="mb-2">
                <input type="text" id="timepicker" name="timepicker" value="" class="form-control form-control-2" placeholder="Clock in time" required="">
              </div>
              <div class="mb-2">
                <input type="text" id="timepicker1" name="timepicker1" value="" class="form-control form-control-2" placeholder="Clock out time" required="">
              </div>
              <div class="row">
                <div class="col-lg-6 mb-2">
                  <a href="#" class="btn btn-personnal w-100" data-bs-dismiss="modal">Cancel</a>
                </div>
                <div class="col-lg-6 mb-2">
                  <button type="submit" class="btn btn-add btn-block">Submit</button>
                </div>
              </div>
      </form>
      </div>
    </div>
  </div>
</div>

@endsection
@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_iTi38PPPgtBY1msPceI8YfMxNSqDnUc&callback=initAutocomplete&libraries=places"
      async
    ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script>
             

    $('.dropify').dropify();
    function initAutocomplete() {

  var input = document.getElementById('address');
           var autocomplete = new google.maps.places.Autocomplete(input);
            // autocomplete.setComponentRestrictions(
            // {'country': ['us']});

           autocomplete.addListener('place_changed', function() {
               var place = autocomplete.getPlace();
                autocomplete.setComponentRestrictions(
            {'country': ['us']});

           
           });

  
}
</script>
<script type="text/javascript">
  
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });
    $("#example tbody > tr:first-child").addClass('selectedrow');
    @if(isset($errors)&& $errors->has('email'))
     $("#add-personnel").modal('show');
    @endif
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   //$(".ev-right").click(function() {
   	$(document).on('click','.ev-right',function(e) {
   	  var id = $(this).data('id');
	  var date1 = $("#dateval").val();
   	  var date = new Date(date1);
      var newdate = date.setDate(date.getDate() + 1);
	  var d = new Date(newdate);
	  var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
	  const dayName = days[d.getDay()];
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
      $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;color:#B0B7C3;width:400px;">');
      getschedulerdata(fulldate,id);
   	});

   	$(document).on('click','.ev-left',function(e) {
   	  var id = $(this).data('id');
   	  var date1 = $("#dateval").val();
   	  var date = new Date(date1);
      var newdate = date.setDate(date.getDate() - 1);
	  var d = new Date(newdate);
	  var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
	  const dayName = days[d.getDay()];
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
      $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;color:#B0B7C3;width:400px;">');
      getschedulerdata(fulldate,id);
   	});

   function getschedulerdata(fulldate,id) {
    $.ajax({
            url:"{{url('company/personnel/leftbarpersonnelschedulerdata')}}",
            data: {
              fulldate: fulldate,
              id:id
            },
            method: 'post',
            dataType: 'html',
            refresh: true,
            success:function(data) {
              console.log(data.html);
               // $("#spanid1").html('<input type="text" value="'+data.countsdata+'" name="countval" id="countval" style="pointer-events: none;position: absolute;top: 2px;background: #fb6794;color: #fff;border-radius:17px;padding: 0 8px;width: 25px;border: 0px;height: 25px;">');
               $("#spanid").html('<input type="text" value="'+fulldate+'" name="dateval" id="dateval" style="border:none;color:#B0B7C3;width:400px;">');
               $('.ev-arrow').show();
              $('#viewmodaldata1').html(data.html);


            }
        })
  }

   jQuery(function() {
   $(document).on('click','.showSingle',function(e) {
   		$('.selectpicker').selectpicker();
        var targetid = $(this).attr('target');
        var serviceid = $(this).attr('data-id');
        $.ajax({
            url:"{{url('company/personnel/leftbarservicedata')}}",
            data: {
              targetid: targetid,
              serviceid: serviceid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
               $('.selectpicker').selectpicker({
                size: 3
              });
            }
        })
    });

    $.ajax({
            url:"{{url('company/personnel/leftbarservicedata')}}",
            data: {
              targetid: 0,
              serviceid: 0 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
             // console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
                
               $('.selectpicker').selectpicker({
                size: 3
              });
            }
        })

  });
$('#selector').delay(2000).fadeOut('slow');


function viewmodalpopup(id) {
  alert('aaa');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewpersonnelmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {

              console.log(data.html);
              $('#viewmodaldata').html(data.html);
              $('.dropify').dropify();
              $('.selectpicker').selectpicker({
                size: 5
              });
            }
        })
}

function viewmodaldata() {
  var aa = "53";
  
  var dataString =  'id='+ aa;
   $.ajax({
            url:'{{route('company.viewpersonnelmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {

              console.log(data.html);
              $('#viewmodaldata').html(data.html);
              $('.dropify').dropify();
              $('.selectpicker').selectpicker({
                size: 5
              });
            }
        })
}
$(document).on('click','#editPersonnel',function(e) {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewpersonnelmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {

              console.log(data.html);
              $('#viewmodaldata').html(data.html);
              $('.dropify').dropify();
              $('.selectpicker').selectpicker({
                size: 5
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

  $(document).on('click','#seeSchedule',function(e) {
   var id = $(this).data('id');
   	var d = new Date();
      var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
	  const dayName = days[d.getDay()];
      const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
      const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
      const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
      var fulldate = `${dayName} - ${mo} ${da}, ${ye}`
   //var dataString =  'id='+ id;
   $.ajax({
            url:"{{url('company/personnel/leftbarpersonnelschedulerdata')}}",
           data: {
              id: id,
              fulldate :fulldate

            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldata1').html(data.html);
              
            }
        })
  });

  $(document).on('click','#search',function(e) {
   var id = $(this).data('id');
   var since = $("#since").val();
   var until = $("#until").val();
   $.ajax({
            url:"{{url('company/personnel/leftbarpersonneltimesheetdata')}}",
            data: {
              id: id,
              from :since,
              to :until,
			},
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldatatimesheet').html(data.html);
              
            }
        })
  });

  $(document).on('click','#seetimesheet',function(e) {
   var id = $(this).data('id');
   	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	var today = yyyy + '-' + mm + '-' + dd;
	
   $.ajax({
            url:"{{url('company/personnel/leftbarpersonneltimesheetdata')}}",
            data: {
              id: id,
              fulldate :today
			},
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldatatimesheet').html(data.html);
              
            }
        })
  });

  $(document).on('click','#edittimesheet',function(e) {
   var id = $(this).data('id');
   $.ajax({
            url:"{{url('company/personnel/leftbaredittimesheetdata')}}",
            data: {
              id: id
      },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $("#timepicker").val(data.starttime);
              $("#timepicker1").val(data.endtime);
              $("#timeid").val(data.timeid);
              $("#date").html('<input type="text" value="'+data.date+'" name="dateval" id="dateval" style="border:none;width:400px;">');
            }
        })
  });
  $('table tr').each(function(a,b) {
    $(b).click(function() {
         $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
    });
  });

  $("#timepicker").datetimepicker({
    format: "LT",
    icons: {
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down"
    }
  });
  $("#timepicker1").datetimepicker({
    format: "LT",
    icons: {
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down"
    }
  });

  $(document).on('click','#seetimeoff',function(e) {
   var id = $(this).data('id');
    var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();

  var today = yyyy + '-' + mm + '-' + dd;
  
   $.ajax({
            url:"{{url('company/personnel/leftbarpersonneltimeoffdata')}}",
            data: {
              id: id,
              fulldate :today
      },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldatatimeoff').html(data.html);
              
            }
        })
  });
  $(document).on('click','#search1',function(e) {
   var id = $(this).data('id');
   var since = $("#since").val();
   var until = $("#until").val();
   $.ajax({
            url:"{{url('company/personnel/leftbarpersonneltimeoffdata')}}",
            data: {
              id: id,
              from :since,
              to :until,
      },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewmodaldatatimeoff').html(data.html);
              
            }
        })
  });

$("body").on('click', '.toggle-password', function() {
  $(this).toggleClass("fa fa-fw field_icon fa-eye-slash");
  var input = $("#password");
  if (input.attr("type") === "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }

});

$("#toggle_pwd").click(function () {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
  $("#wpassword").attr("type", type);
});

$('html').on('click','#accept',function() {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.accepttime')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
               swal("Done!","It was succesfully Accepted!","success");
            }
        })
  });

$('html').on('click','#reject',function() {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.rejecttime')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
               swal("Done!","It was succesfully Rejected!","success");
            }
        })
  });
</script>
@endsection