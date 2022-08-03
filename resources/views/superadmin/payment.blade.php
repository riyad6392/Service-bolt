@extends('layouts.superadminheader')
@section('content')
<div class="content-page">
<div class="content p-3">
     <div class="row">
      <h5>Manage Payment</h5>
      <div class="col-md-12 mt-2">
        <div class="card">
	   <div class="card-body">
      <form style="">
        <div class="row">
      @if($since=='') 
        <div class="col-lg-3 mb-3">
          <label>From Date</label>
          <input type="date" id="since" value="{{$currentdate}}" name="since" class="form-control">
        </div>
        <div class="col-lg-3 mb-3">
          <label>To Date</label>
          <input type="date" id="until" value="{{$currentdate}}" name="until" class="form-control">
        </div>
        <div class="col-lg-3 mb-3">
          <label></label>
         <button class="btn btn-block button" type="submit" id="search1" style="margin-top: 25px;">Search</button>
        </div>
      @else
        <div class="col-lg-3 mb-3">
          <label>From Date</label>
          <input type="date" id="since" value="{{$since}}" name="since" class="form-control">
        </div>
        <div class="col-lg-3 mb-3">
          <label>To Date</label>
          <input type="date" id="until" value="{{$until}}" name="until" class="form-control">
        </div>
        <div class="col-lg-3 mb-3">
          
         <button class="btn btn-block button" type="submit" id="search1" style="margin-top: 25px;">Search</button>
        </div>
        <div class="col-lg-3 mb-3" style="margin-top: 35px;">
         <a href="{{url('superadmin/managePayment')}}">Clear</a>
        </div>
      @endif
</div>
      </form>
	   <div class="table-responsive">
	   <table id="example12" class="table no-wrap table-new table-list align-items-center">
	  <thead>
	  <tr>
    <th style="display: none;"></th> 
	  <th>Paid On</th>
	  <th>Payment ID</th>
    <th>Amount</th>
	  <th>Company Name</th>
    <th>Status</th>
	  <th>Action</th>
	  </tr>
	  </thead>
	  <tbody>
	  @foreach($paymentData as $payment)
	  <tr>
    <td style="display: none;">{{$payment->id}}</td>
	  <td>{{date('j M Y', strtotime($payment->created_at))}}</td>
	  <td>{{$payment->id}}</td>
    <td>
      @if($payment->amount!=null)
        {{$payment->amount}}
      @else
        --
      @endif
    </td>
	  <td>{{$payment->companyname}}</td>
	  <td>
	  @if($payment->amount!=null)
		Successfull
	  @else
	  	Failed
	  @endif
	</td>
	  <td>
	  	@if($payment->amount != null)
         <a href="#" id="makeunpaid" class="add-btn-yellow" data-id="{{$payment->id}}">
         	Make Unpaid
         </a>
        @else
         <a href="#" id="makepaid" class="add-btn-yellow" data-id="{{$payment->id}}">
          Make Paid
         </a>
        @endif
	  </td>
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
@endsection
<script src="{{ asset('js/jquery.min.js')}}"></script>  
<script type="text/javascript">
  $(document).ready(function() {
    $('#example12').DataTable({
      "order": [[ 0, "desc" ]]
    });
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

    $(document).on('click','#makepaid',function(e) {
     	var userid = $(this).data('id');
     	var status = "Paid";
     	swal({
          title: "Are you sure?",
          text: "Are you sure you want to subscription Paid this user!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, Paid it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
           $.ajax({
                url:"{{route('superadmin.subscriptionstatus')}}",
                data: {
                'userid':userid,
                'status':'Paid',
                 '_token': '{{csrf_token()}}',
              },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Subscription Paid succesfully!","success");
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
    $(document).on('click','#makeunpaid',function(e) {
     	var userid = $(this).data('id');
     	var status = "unpaid";
     	swal({
          title: "Are you sure?",
          text: "Are you sure you want to subscription Unpaid this user!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, Unpaid it!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
           $.ajax({
                url:"{{route('superadmin.subscriptionstatus')}}",
                data: {
                'userid':userid,
                'status':'unpaid',
                 '_token': '{{csrf_token()}}',
              },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Subscription Unpaid succesfully!","success");
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
</script>