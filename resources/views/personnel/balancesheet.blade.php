@extends('layouts.workerheader')
@section('content')
<div class="content">
     <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3 class="align-items-center d-flex justify-content-between">Balance Sheet</h3>
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
        Balance Sheet</div>
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
     
     <div class="col-lg-4 mb-2 offset-lg-3" style="display: none;">
     <div class="show-fillter">
     <input type="text" class="form-control" placeholder="Search  Transaction">
     <button class="search-icon">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z" fill="currentColor"></path></svg>
     </button>
     </div>
     
     </div>
     
     </div>
     <form action="{{ route('worker.balancesheetfilter') }}" method="post">
      @csrf
      <div class="row">
      <div class="col-md-4">
      </div><div class="col-md-3">
      </div><div class="col-md-3">
      </div>
      <div class="col-md-2">
       <button class="btn add-btn-yellow py-2 px-5" type="submit" name="search" value="excel">{{ __('Export') }}</button>
      </div>
    </div>
     </form>
     <div class="col-lg-12 mt-4">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="
    position: relative;">
          <thead>
            <tr>
              <th>Transaction #</th>
              <th>TicketId</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Payment Method </th>
              <th>Customer</th>
            </tr>
          </thead>
          <tbody>
            @foreach($balancesheet as $key => $value)
              @php
                $newdate = date("M, d Y", strtotime($value->created_at));
              @endphp
              <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->ticketid}}</td>
                <td>{{$newdate}}</td>
                <td>{{$value->amount}}</td>
                <td>{{$value->paymentmethod}}</td>
                <td>{{$value->customername}}</td>
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

@endsection

@section('script')
@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
 </script>
@endsection


