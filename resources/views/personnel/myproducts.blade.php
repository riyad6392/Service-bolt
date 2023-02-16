@extends('layouts.workerheader')
@section('content')
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Products</h3>
        @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
     </div>
     </div>


     <div class="col-md-12">
       <div class="card">
     <div class="card-body">
     <div class="row" style="display: none;">
      <div class="col-lg-2 mb-2">
        Quick Look</div>
     <div class="col-lg-3 mb-2">
     <div class="show-fillter">
     <select id="inputState" class="form-select">
        <option>Show: A to Z</option>
        <!-- <option>By Service</option>
        <option>By Frequency</option>
        <option>By Company</option> -->
      </select>
     </div>
     </div>
     
     <div class="col-lg-5 offset-lg-1 mb-2" style="visibility: hidden;">
     <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search customers by name"/>
     <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button>
     </div>
     
     </div>
     
     </div>
     
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Price</th>
             <!--  <th>Category</th>
              <th>Quantity</th>
              <th>SKU</th> -->
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productdata as $key => $value)
              <tr>
                <td>{{$value->productname}}</td>
                <td>{{$value->price}}</td>
                <!-- <td>{{$value->category}}</td>
                <td>{{$value->quantity}}</td>
                <td>{{$value->sku}}</td> -->
                <td>
                    <a href="#" class="btn add-btn-yellow ps-5 pe-5" data-bs-toggle="modal" data-bs-target="#ticket-modal" data-id="{{$value->id}}" id="myticketid">Add To ticket +</a>
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



          </div>
     
<!-- Modal -->
<div class="modal fade" id="ticket-modal" tabindex="-1" aria-labelledby="ticket-modalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content customer-modal-box">
     
      <div class="modal-body" style="height: auto;overflow-y: auto;">
       
    <form method="post" action="{{route('worker.ticketcreate1')}}" enctype="multipart/form-data">
        @csrf
        <div id="viewmodaldata"></div>
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
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $(document).on('click','#myticketid',function(e) {
  $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('worker.viewproductticketmodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              $('#viewmodaldata').html(data.html);
              $('.selectpicker').selectpicker({
                size: 3
              });
              var h = 1;
              var realmin = 0;
              $("#time").val(h);
              $("#minute").val(realmin);
            }
        })
    });
$(document).on('change','#customerid1',function(e) {
//$('#customerid1').on('change', function() {
    var customerid = this.value;
    $("#address2").html('');
    $("#addressicon").html('');
      $.ajax({
        url:"{{url('personnel/myticket/getaddressbyid')}}",
        type: "POST",
        data: {
        customerid: customerid,
        _token: '{{csrf_token()}}' 
        },
        dataType : 'json',
        success: function(result) {
          $("#address2").empty();
          $('#address2').html('<option value="">Select Customer Address</option>'); 
          $.each(result.address,function(key,value) {
            $("#address2").append('<option value="'+value.address+'">'+value.address+'</option>');
          });

          $('#customerid').val(customerid);
      }
    });
  });
$('#selector').delay(2000).fadeOut('slow');
</script>
@endsection


