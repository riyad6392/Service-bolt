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

 i.fa.fa-cog {
        color: gray;
        font-size: 25px;
    }

    input#flexCheckDefault {
        outline: navajowhite;
        width: 20px;
        height: 20px;
        margin: 2px 5px;
        box-shadow: none;
        border: 1px solid gray;
        border-radius: 0;
    }

    .table>thead>tr>th {
        border: navajowhite;
    }

    th,td{
        border: none !important;
    }

    button.exploder {
        background: white;
        outline: none;
        color: black;
        border: none;
        box-shadow: none;
    }

    .btn-danger:hover {
        background: white;
        color: black;
        outline: none;
        border: none;
        box-shadow: none;
    }

    .btn-danger.focus, .btn-danger:focus {
        background: white;
        color: black;
        outline: none;
        border: none;
    }
</style>
<div class="content">
     <div class="row">
      <div class="col-md-4">
        <div class="side-h3">
       <h3>Commission Report</h3>
        @if(Session::has('success'))

              <div class="alert alert-success" id="selector">

                  {{Session::get('success')}}

              </div>

          @endif
     </div>
     </div>
        <div class="col-md-2" style="padding:7px;">
          <label style="visibility:hidden;">Select Date Range</label>
          <input type="date" id="since" value="" name="since" class="form-control date1">
        </div>
        <div class="col-md-2" style="padding:7px;">
          <label style="visibility:hidden;">To Date</label>
          <input type="date" id="until" value="" name="until" class="form-control date2">
        </div>
     <div class="col-md-2">
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
                    <th style="font-weight:400;">Personal Name</th>
                    <th style="font-weight:400;">Tickets Worked</th>
                    <th style="font-weight:400;">Flat Amount</th>
                    <th style="font-weight:400;">Variable Amount</th>
                    <th style="font-weight:400;">Totol Payout Amount</th>
                    <th colspan="" style="font-weight:400;">Action</th>
                </tr>
          </thead>
          <tbody>
            <tr class="sub-container">
                <td>
                 <span class="glyphicon glyphicon-plus plusIcon">+</span>
                 <span class="glyphicon glyphicon-minus plusIcon" style="display:none">-</span>
                    Adom Norsworthy
                </td>
                    <td>2</td>
                    <td>$65.00</td>
                    <td>$7.55</td>
                    <td>$72.55</td>
                    <td>
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <input class="form-check-input flexCheckDefault" type="checkbox" value="" id="flexCheckDefault">
                    </td>
                </tr>

                <tr class="explode hide" style="display:none;">
                    <td colspan="8" id="toggle_text">
                        <table class="table table-condensed">
                            <thead>
                                <tr style="font-family: system-ui;">
                                    <th>Date</th>
                                    <th>Ticket#</th>
                                    <th>Services</th>
                                    <th>Products</th>
                                    <th>Flat Amount</th>
                                    <th>Variable Amount</th>
                                    <th>Total Payout</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="font-size: 17px; border:none; background:white;">
                                    <td>8-16-22</td>
                                    <td>169</td>
                                    <td>Moving , Weed Eet</td>
                                    <td>iron Pipe</td>
                                    <td>$25</td>
                                    <td>$7.55</td>
                                    <td>$32.55</td>
                                </tr>
                                <tr style="font-size: 17px;">
                                    <td>8-16-22</td>
                                    <td>169</td>
                                    <td>Moving , Weed Eet</td>
                                    <td>iron Pipe</td>
                                    <td>$25</td>
                                    <td>$7.55</td>
                                    <td>$32.55</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
          </tbody>

          <tbody>
            <tr class="sub-container">
                <td>
                 <span class="glyphicon glyphicon-plus plusIcon">+</span>
                 <span class="glyphicon glyphicon-minus plusIcon" style="display:none">-</span>
                    Mike Norsworthy
                </td>
                    <td>2</td>
                    <td>$65.00</td>
                    <td>$7.55</td>
                    <td>$72.55</td>
                    <td>
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <input class="form-check-input flexCheckDefault" type="checkbox" value="" id="flexCheckDefault">
                    </td>
                </tr>

                <tr class="explode hide" style="display:none;">
                    <td colspan="8" id="toggle_text">
                        <table class="table table-condensed">
                            <thead>
                                <tr style="font-family: system-ui;">
                                    <th>Date</th>
                                    <th>Ticket#</th>
                                    <th>Services</th>
                                    <th>Products</th>
                                    <th>Flat Amount</th>
                                    <th>Variable Amount</th>
                                    <th>Total Payout</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="font-size: 17px; border:none; background:white;">
                                    <td>8-16-22</td>
                                    <td>169</td>
                                    <td>Moving , Weed Eet</td>
                                    <td>iron Pipe</td>
                                    <td>$25</td>
                                    <td>$7.55</td>
                                    <td>$32.55</td>
                                </tr>
                                <tr style="font-size: 17px;">
                                    <td>8-16-22</td>
                                    <td>169</td>
                                    <td>Moving , Weed Eet</td>
                                    <td>iron Pipe</td>
                                    <td>$25</td>
                                    <td>$7.55</td>
                                    <td>$32.55</td>
                                </tr>
                            </tbody>
                        </table>
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
    });

    $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(".plusIcon").on("click",function() {
      var obj = $(this);
      if( obj.hasClass("glyphicon-plus") ){
        obj.hide();
        obj.next().show();            
        obj.parent().parent().next().show();
      }else{
         obj.hide();
         obj.prev().show();
         obj.parent().parent().next().hide();
      }
    });

    $(".exploder1").click(function () {
        //$('#toggle_icon').text('-');

        $(this).children("span").toggleClass("glyphicon-search glyphicon-zoom-out");

        $(this).closest("tr").next("tr").toggleClass("hide");

        if ($(this).closest("tr").next("tr").hasClass("hide")) {
            $(this).closest("tr").next("tr").children("td").slideUp();
        }
        else {
            $(this).closest("tr").next("tr").children("td").slideDown(350);
        }
    });
</script>
@endsection


