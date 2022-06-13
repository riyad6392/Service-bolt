@extends('layouts.workerheader')
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
</style>
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3>Category</h3>
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
     <div class="col-lg-3 mb-2" style="visibility: hidden;">
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
     
     <div class="col-lg-3 text-end mb-2">
     <a href="#"  data-bs-toggle="modal" data-bs-target="#add-category" class="add-btn-yellow">
     Add Category +
     </a>
     </div>
     
     </div>
     
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th style="display: none;">Id</th>
              <th>Category Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	@php
            $i=1;
           @endphp
            @foreach($category as $cdata)
           <tr>
              <td style="display: none;">{{$cdata->id}}</td>
              
            <td>
              {{$cdata->category_name}}
            </td>
              <td><a class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#edit-category" id="editCategory" data-id="{{$cdata->id}}">Edit</a>
              <a href="javascript:void(0);" class="info_link1 btn btn-edit" dataval="{{$cdata->id}}">Delete</a>
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
     </div>












     </div>
   </div>



          </div>
<!-- Modal -->
<div class="modal fade" id="add-category" tabindex="-1" aria-labelledby="add-customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
     <form class="form-material m-t-40  form-valide" method="post" action="{{route('worker.categoriescreate')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">
       <div class="add-customer-modal">
     <h5>Add a new category</h5>
     </div>
     
     <div class="row customer-form">
      
     <div class="col-md-12 mb-3">
     
     <input type="text" class="form-control" placeholder="Category Name" name="category_name" required="">
  
     </div>
     
     <div class="col-lg-6 mb-3">
     <button class="btn btn-cancel btn-block"  data-bs-dismiss="modal">Cancel</button>
     </div>
     <div class="col-lg-6 mb-3">
     <button type="submit" class="btn btn-add btn-block">Save</button>
     </div>
     
     </div>
      </div>
     </form>
    </div>
  </div>
</div>

<!----------------------edit form------------>
<div class="modal fade" id="edit-category" tabindex="-1" aria-labelledby="add-personnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content customer-modal-box">
      <div class="modal-body">
      <form method="post" action="{{ route('worker.categoriesupdate') }}" enctype="multipart/form-data">
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
$('#selector').delay(2000).fadeOut('slow');

$(document).on('click','#editCategory',function(e) {
  $('.selectpicker').selectpicker();
   var id = $(this).data('id');
   var dataString =  'id='+ id;
   $.ajax({
            url:'{{route('worker.viewcategorymodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {

              $('#viewmodaldata').html(data.html);
             
            }
        })
  });

  $('html').on('click','.info_link1',function() {
        var customerid = $(this).attr('dataval');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this category!",
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
                url:"{{url('personnel/categories/delete')}}",
                data: {
                  id: customerid 
                },
                method: 'post',
                dataType: 'json',
                refresh: true,
                success:function(data) {
                 swal("Done!","Category deleted succesfully!","success");
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
</script>
@endsection


