@extends('layouts.header')
@section('content')
<style type="text/css">
  .reject-btn {
    background-color: red;
    color: #fff;
    padding: 5px 8px!important;
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3 d-flex justify-content-between">
       <h3>Notifications</h3>
     
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
     <div class="row">
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th style="display: none;">Id</th>
              <th>Message</th>
              <th>DateTime</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($notificationlist as $key => $value)
            <tr>
              <td style="display: none;">{{$value->id}}</td>
              <td style="">
                 @if($value->ticketid!="")
                  <a class="btn w-auto" data-bs-toggle="modal" data-bs-target="#view-tickets" id="viewTickets" data-id="{{$value->ticketid}}">{{$value->message}}</a>
                 @else
                  <a class="btn w-auto" href="{{url('company/timeoff')}}">{{$value->message}}</a>
                 @endif
              </td>
              <td>{{$value->created_at}}</td>
              <td><a class="btn btn-edit reject-btn p-3 w-auto" id="delete" data-id="{{$value->id}}">Delete</a></td>
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

  <!-- view tickets on popup -->
   <div class="modal fade" id="view-tickets" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
        <div id="viewcompletedmodal"></div>
      </div>
    </div>
  </div>
</div>
<!-- end -->
  @endsection

@section('script')
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 0, "desc" ]]
    });

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


  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
$('html').on('click','#delete',function() {
   var id = $(this).data('id');
   var dataString =  'id='+ id;
  swal({
    title: "Are you sure!",
    text: "Are you sure? you want to delete it!",
    type: "error",
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Yes!",
    showCancelButton: true,
  },
  function() {
    $.ajax({
          url:'{{route('company.deletenotification')}}',
          data: dataString,
          method: 'post',
          dataType: 'json',
          refresh: true,
         success:function()
         {
          swal({
             title: "Done!", 
             text: "Deleted Successfully!", 
             type: "success"
          },
          function() { 
                 location.reload();
              }
          );
         }
    })
  });
});
</script>
@endsection


