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

  .table-new tbody tr.selectedrow:after {
    /*background: #FDFBEC;*/
    background: #FAED61 !important;
  }
  .card-body.customer-scroll-div {
    height: 450px;
    overflow-y: auto;
}
</style>
<div class="">
<div class="content">
  <div class="row">
    <div class="col-md-12">
	  <a href="{{route('company.customer')}}" class="back-btn">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
	   Back</a>
        <div class="side-h3">
       <h3 class="info">Customer Info</h3>
      </div>
     </div>

     <div class="col-md-4 mb-4">
      <div class="card">
        <div class="card-body">
          @foreach($customerData as $key => $value)
          <div class="placeholder">
            @if($value->image!=null)
            <img src="{{url('uploads/customer/')}}/{{$value->image}}" alt="" style="object-fit: cover;">
            @else 
              <img src="{{url('uploads/servicebolt-noimage.png')}}" alt="" style="object-fit: cover;">
            @endif
            <h4 class="thomas-img">{{$value->customername}}</h4>
          </div>
          <div>
           <p class="number-1">Phone Number</p>
           <h6 class="heading-h6">{{$value->phonenumber}}</h6>
          </div>
          <div>
            <p class="number-1">Email</p>
            <h6 class="heading-h6">{{$value->email}}</h6>
          </div>
          <div>
            <p class="number-1">Company Name</p>
            <h6 class="heading-h6">{{$value->companyname}}</h6>
          </div>
          @endforeach
        </div>
      </div>
    </div>

<div class="col-md-8 mb-4">
<div class="card">
<div class="card-body p-3">
  <div class="card-box">
  <h5 class="ms-2">
  Connected Addresses
  </h5>
  @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
  <div class="row">

<form class="form-material m-t-40 row form-valide" method="post" action="{{route('company.customeraddresscreate')}}">
  @csrf
  <input type="hidden" name="customerid" id="customerid" value="{{@$customerData[0]->id}}">
  <div class="col-lg-8 mb-2">
   <div class="show-fillter">
	   <input type="text" class="form-control" placeholder="Search Addresses" name="address" id="address" required="">
    </div>
  </div>
  <div class="col-lg-3 offset-lg-1 mb-3">
    <!-- <button class="btn btn-add btn-block" type="submit">Add Address</button> -->
    <a class="btn btn-add btn-block" data-bs-toggle="modal" data-bs-target="#add-note" id="addnote" style="padding: 10px;">Add Address</a>
  </div>
</form>


@if(count($customerAddress)>0)
          @else
         <div>Not found any addresses.</div>
         @endif
 <!--  <div class="col-lg-3 offset-lg-1 mb-3">
  <a href="#" class="add-btn-yellow btn-block text-center" data-bs-toggle="modal" data-bs-target="#add-customer">
	   Add New +
	   </a>
	   </div> -->
<div class="modal fade" id="create-ctickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
        <form method="post" action="{{ route('company.customercreatequote') }}" enctype="multipart/form-data">
        @csrf
        <div id="viewcustomerquotemodaldata"></div>
      </form>
      </div>
  </div>
</div>
</div>
<input type="hidden" name="customerid" id="customerid" value="last(request()->segments())"> 	
<!--edit address modal open-->
<div class="modal fade" id="edit-address" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.updateaddress') }}" enctype="multipart/form-data">
          @csrf
          <div id="vieweditaddressmodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>
<!-- edit notes modal open -->
<div class="modal fade" id="edit-note" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.updatenotes') }}" enctype="multipart/form-data">
          @csrf
          <div id="vieweditnotemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

	   
	   <div class="col-lg-12 mb-2" style="display: none;">
	   <input type="text" placeholder=" Address" class="form-control input-gray" readonly style="box-shadow: none;"/>
	   </div>
     @foreach($customerAddress as $key=>$value)
  	   <div class="col-lg-12 mb-2">
    	   <div class="d-flex align-items-center justify-content-between ps-4 pt-2 pe-2 pb-2 address-line2 address-line">
    	     <div class="d-flex align-items-center addressdata"><a href="javascript:void(0);" class="info_link1" dataval="{{$value->id}}"><i class="fa fa-trash"></i></a><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="me-3"><path d="M12 18a6 6 0 100-12 6 6 0 000 12z" fill="currentColor"></path></svg> <a class="" data-bs-toggle="modal" data-bs-target="#edit-address" id="editaddress" data-id="{{$value->id}}" data-address="{{$value->address}}">{{$value->address}}</a></div>
           <a class="" data-bs-toggle="modal" data-bs-target="#edit-note" id="editnote" data-id="{{$value->id}}" data-note="{{$value->notes}}"><img src="{{url('/')}}/images/writing.png" style="width:30px;"></a>
    	  <!--  <button class="btn btn-save confirm">Service Ticket</button> -->
        <a class="btn btn-save confirm" data-bs-toggle="modal" data-bs-target="#create-ctickets" id="createctickets" data-id="{{$value->customerid}}" data-address="{{$value->address}}" style="width:152px;">Create Ticket</a>
        <a href="{{url('company/customer/ticketviewall/')}}/{{$value->customerid}}/{{$value->address}}" class="btn btn-save confirm" style="width:152px;">View Tickets</a>
    	   </div>
  	   </div>
	   @endforeach
	
	   
	   
	   
  
  </div>
  </div>
</div>
</div>

     </div>

     <div class="col-md-8 mb-4">
             <div class="card">
       <div class="card-body customer-scroll-div">
	    <h5 class="mb-4">Recent Tickets</h5>
		 
		<div class="">
    <table id="example" class="table no-wrap table-new table-list align-items-center">
    <thead>
    <tr>
    <th>Ticket number</th>
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
    <tr target="{{$i}}">
    <td>#{{$ticket->id}}</td>
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
    <td><a class=" showSingle" target="{{$i}}" data-id="{{$ticket->customerid}}" datat-id="{{$ticket->id}}">View</a>
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
     <div class="col-md-4 mb-4" style="display: block;">
       <div class="card">
        <div class="card-body">
		  <div class="row">
         <div id="viewleftservicemodal">
           
         </div>
     </div>
		</div>
   </div>
     </div>


     </div>
   </div>



          </div>
     
	 
	 
	 <!-- Modal -->
<div class="modal fade" id="add-customer" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
       <div class="add-customer-modal">
	   <h5>Add a new Address</h5>
	   </div>
	   
	   <div class="row customer-form">
	   <div class="col-md-12 mb-3">
	     
       <input
      id="pac-input1"
      class="form-control"
      type="text"
      placeholder="Search Box"
    />
	   </div>


	   <div class="col-lg-6 mb-3">
	   <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
	   </div>
	   <div class="col-lg-6 mb-3">
	   <button class="btn btn-add btn-block">Add Address</button>
	   </div>
	   
	   </div>
      </div>
     
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="viewimage" tabindex="-1" aria-labelledby="viewimageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-body">
       <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/gallery-1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/gallery-1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/gallery-1.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
      </div>
     
    </div>
  </div>
</div>

<!-- Due invoice modal -->
<div class="modal fade" id="view-invoice" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <form method="post" action="{{ route('company.viewinvoice') }}" enctype="multipart/form-data">
          @csrf
          <div id="viewdueinvoicemodaldata"></div>
        </form>
      </div>
  </div>
</div>
</div>

<div class="modal fade" id="add-note" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body">
        <form method="post" action="{{route('company.customeraddresscreate')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="saddress" id="saddress" value="">
        <input type="hidden" name="customerid" id="customerid" value="{{@$customerData[0]->id}}">
        <div class="add-customer-modal">
                  <div style="font-size:25px;">Add Notes</div>
                 </div>
               <div class="col-md-12 mb-2">
                <div class="input_fields_wrap">
                  <select class="form-control selectpicker " multiple="" data-placeholder="Select Checklist" data-live-search="true" style="width: 100%;" tabindex="-1" aria-hidden="true" name="adminck[]" id="adminck">
                    @foreach($adminchecklist as $key =>$value1)
                      <option value="{{$value1->serviceid}}">{{$value1->checklistname}}</option>';
                    @endforeach

                 </select>
                </div>
              </div> 
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                <textarea class="form-control" name="note" id="note" placeholder="Notes" cols="45" rows="5"></textarea>
                  </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">Save</button>
        </div>
      </form>
      </div>
  </div>
</div>
</div>

@endsection

@section('script')
  <script>
$(document).ready(function() {
    // $('#example').DataTable({
    //   "order": [[ 0, "desc" ]]
    // });
    $("#example tbody > tr:first-child").addClass('selectedrow');
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   
    
    // $(document).on('change', 'select.selectpicker',function() {
    //   gethours();
    // });

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


$(document).on('click','#createctickets',function(e) {
  $('.selectpicker').selectpicker();
   var cid = $(this).data('id');
   var address = $(this).data('address');
   
   // var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('company.viewcustomerquotemodal')}}',
            data: {
              'cid':cid,
              'address':address,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewcustomerquotemodaldata').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
              $(".selectpickerc1").selectpicker();
            }
        })
  });

 $(document).on('click','#editaddress',function(e) {
   var cid = $(this).data('id');
   var address = $(this).data('address');
   $.ajax({
            url:'{{route('company.vieweditaddressmodal')}}',
            data: {
              'cid':cid,
              'address':address,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#vieweditaddressmodaldata').html(data.html);
              initAutocomplete();
            }
        })
  });

$(document).on('click','#addnote',function(e) {
  var address = $("#saddress").val();
  if(address=="") {
    swal({
        title: "Search address?",
        text: "Can you please search address!",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "ok",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function (isConfirm) {
        if (isConfirm) {
          location.reload();
        }
      }
    )
    }    
});
 $(document).on('click','#editnote',function(e) {
   $('.selectpicker').selectpicker();
   var cid = $(this).data('id');
   var note = $(this).data('note');
   $.ajax({
            url:'{{route('company.vieweditnotemodal')}}',
            data: {
              'cid':cid,
              'note':note,
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#vieweditnotemodaldata').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
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
 $('#selector').delay(2000).fadeOut('slow');

 jQuery(function() {
   jQuery('.showSingle').click(function() {
        var targetid = $(this).attr('target');
        var customerid = $(this).attr('data-id');
        var ticketid = $(this).attr('datat-id');
        $.ajax({
            url:"{{url('company/customer/leftbarticketdata')}}",
            data: {
              targetid: targetid,
              customerid: customerid,
              ticketid: ticketid 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
            }
        })
    });
    var customerid1 = $("#customerid").val();
    $.ajax({
            url:"{{url('company/customer/leftbarticketdata')}}",
            data: {
              targetid: 0,
              customerid1: customerid1 
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#viewleftservicemodal').html(data.html);
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
        console.log(data.html);
        $('#viewdueinvoicemodaldata').html(data.html);
        
      }
    });
  })

 $('html').on('click','.info_link1',function() {
      var addressid = $(this).attr('dataval');
      swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this address!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if(isConfirm) {
             $.ajax({
              url:"{{url('company/customer/deleteAddress')}}",
              data: {
                id: addressid 
              },
              method: 'post',
              dataType: 'json',
              refresh: true,
              success:function(data) {
                if(data == 0) {
                    swal({
                      title: "Can not delete!",
                      text: "This Address already exist in ticket!",
                      type: "warning",
                    });
                } else {
                  swal("Done!","It was succesfully deleted!","success");
                  location.reload();
                }
              }
            })
          } 
          else {
            location.reload();
          }
        }
      );
  
 });



$('table tr').each(function(a,b) {
    $(b).click(function() {
         $(this).addClass('selectedrow').siblings().removeClass('selectedrow');
    });
  });


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
function gethours() {
        var h=0;
        var m=0;
        $('select.selectpicker').find('option:selected').each(function() {
          h += parseInt($(this).data('hour'));
          m += parseInt($(this).data('min'));

          
        });
        //if(h == NaN) {
        var realmin = m % 60;
          var hours = Math.floor(m / 60);
          h = h+hours;
          $("#time").val(h);
        $("#minute").val(realmin);
      //}
      }

  function getprice() {
      var price = 0;
      $('select.selectpicker').find('option:selected').each(function() {
          price += parseFloat($(this).data('price'));
      });
      
      $("#price").val(price.toFixed(2));  
  }
  function getpricep1() {
      var price = parseFloat($("#price").val());
      $('select.selectpickerc1').find('option:selected').each(function() {
        price += parseFloat($(this).data('price'));
    });
    
    $("#price").val(price.toFixed(2));  
    }

    function getfrequency() {
      var frequency = "";
      $("#frequency option").removeAttr('selected');
      $('select.selectpicker').find('option:selected').each(function() {
          frequency = $(this).data('frequency');
      });
      $("#frequency option[value='"+frequency+"']").attr('selected', 'selected');
      
    }

$(document).on('change','#productname',function(e) {
  //getpricep1();
  var serviceid = $('#servicename').val();
    var productid = $('#productname').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
          url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            console.log(data.totalprice);
            $('#price').val(data.totalprice);
            $('#tickettotal').val(data.totalprice);
          }
      })
});

 $(document).on('change','#servicename',function(e) {
    gethours();
    var serviceid = $('#servicename').val();
    var productid = $('#productname').val(); 
    var qid = "";
    var dataString =  'serviceid='+ serviceid+ '&productid='+ productid+ '&qid='+ qid;
    $.ajax({
           url:'{{route('company.calculateproductprice')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
          success:function(data) {
            $('#price').val(data.totalprice);
            $('#tickettotal').val(data.totalprice);
            
          }
      })
   });
</script>
@endsection