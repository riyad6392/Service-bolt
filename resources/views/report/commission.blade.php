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

    .table-new tbody td,
    .table-new thead th {
        z-index: 1;
        padding: 15px 6px !important;
    }

    .glyphicon.glyphicon-plus.plusIcon {
        background: yellow;
        width: 25px;
        height: 25px;
        /* align-items: center; */
        justify-content: center;
        display: flex;
        margin: 0px 4px;
        border-radius: 50px;
        font-weight: 700;
        cursor: pointer;
    }

    .glyphicon.glyphicon-minus.plusIcon {
        background: yellow;
        width: 25px;
        height: 25px;
        /* align-items: center; */
        justify-content: center;
        display: flex;
        margin: 0px 4px;
        border-radius: 50px;
        font-weight: 700;
        cursor: pointer;
    }

    input#flexCheckDefault::before {
        background-color: red;
    }

    input#flexCheckDefault {
        border-radius: 5px;
        outline: none;
        border: 1px solid gray;
    }

    .table-new tbody tr::after {
        content: '';
        width: 100%;
        position: absolute;
        left: 0;
        right: 0;
        background-color: #fff;
        height: 100%;
        z-index: 0;
        /* border: 1px solid yellow; */
        border-radius: 15px;
    }

    /* .checkbox:checked:before {
        background-color: yellow;
        outline: none;
        border: none;
        
    }

    input#flexCheckDefault {
        background-color: yellow;
        outline: none;
        border: none;
        
    } */

    tr.explode.hide {
    border: 2px solid yellow;
}

.table-new .tbody-1:after {
        content: '';
        width: 100%;
        position: absolute;
        left: 0;
        right: 0;
        background-color: #fff;
        height: 100%;
        z-index: 0;
        border: 1px solid yellow;
        border-radius: 15px;
        top:0px;
    }
</style>
<div class="content">
    <form method="post" action="{{route('company.commisionreport') }}" class="row pe-0">
      @csrf
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
    
      <input type="hidden" name="phiddenid" id="phiddenid" value="">
     <div class="col-md-2">
        <div class="side-h3">
          <select class="form-select puser" name="pid" id="pid" required="">
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
  
     <div class="col-lg-12">
     <div class="table-responsive">
      <table id="example" class="table no-wrap table-new table-list" style="position: relative;">
          <thead>
                <tr>
                    <th style="font-weight:400;font-size:15px;">Personnel Name</th>
                    <th style="font-weight:400;font-size:15px;">Tickets Worked</th>
                    <th style="font-weight:400;font-size:15px;">Flat Amount</th>
                    <th style="font-weight:400;font-size:15px;">Variable Amount</th>
                    <th style="font-weight:400;font-size:15px;">Totol Payout</th>
                    <th colspan="" style="font-weight:400;font-size:15px;">Action</th>
                </tr>
          </thead>
          

          <tbody class="tbody-1">
            @foreach($tickedata as $key => $value)

                @php
                  $ttlflat = 0;
                  $ptamounttotal = 0;
                  $explode_id = explode(',', $value->serviceid);
                  
                  $servicedata = App\Models\Service::select('servicename','price')
                    ->whereIn('services.id',$explode_id)->get();

                  $pexplode_id = explode(',', $value->product_id);
                  $pdata = App\Models\Inventory::select('id','price')
                    ->whereIn('products.id',$pexplode_id)->get();
                     $ttlflat=0;
                     
                     $ttlflat2 = 0;

                    if(count($amountall)>0) {
                        if($amountall[0]->allspvalue==null) {
                            foreach($explode_id as $servicekey =>$servicevalue) {

                                  foreach($comisiondataamount->service as $key=>$sitem)
                                  {
                                    if($sitem->id==$servicevalue && $sitem->price!=0)
                                    {
                                                $servicevalue."==={$sitem->id}price==".  $sitem->price."<br>";
                                        $ttlflat2 += $sitem->price;
                                    }
                                  }
                                  $ttlflat1 = 0;

                                foreach($pexplode_id as $servicekey =>$servicevalue) {
                                  $ttlflat1 = 0;

                                  foreach($comisiondataamount->product as $key=>$sitem1)
                                      {
                                        if($sitem1->id==$servicevalue && $sitem1->price!=0)
                                        {
                                                   // $servicevalue."==={$sitem->id}price==".  $sitem1->price."<br>";
                                            $ttlflat1 += $sitem1->price;
                                        }
                                      }
                                }     
                                 
                                  $ttlflat =$ttlflat1+$ttlflat2;
                            } 
                        } else {
                            $flatvalue = $amountall[0]->allspvalue;
                            $flatv = $flatvalue*count($explode_id);
                            $pvalue = $flatvalue *count($pexplode_id);
                            $ttlflat = $flatv+$pvalue;
                        }
                    }

                    if(count($percentall)>0) {
                        $ptamount = 0;
                        $ptamount1 = 0;
                        if($percentall[0]->allspvalue==null) {
                            foreach($explode_id as $servicekey =>$servicevalue) {
                                foreach($comisiondatapercent->service as $key=>$sitem)
                                {
                                  if($sitem->id==$servicevalue && $sitem->price!=0)
                                  {
                                    //echo $servicevalue."==={$sitem->id}price==".  $sitem->price."<br>";

                                    $servicedata = App\Models\Service::select('servicename','price')
                                    ->where('services.id',$sitem->id)->first();

                                    $ptamount += $servicedata->price*$sitem->price/100;               
                                  }
                                }
                                
                          } 
                          
                          foreach($pexplode_id as $key=>$pid) {
                                foreach($comisiondatapercent->product as $key=>$sitem)
                                {
                                  if($sitem->id==$pid && $sitem->price!=0)
                                  {
                                    $pdata = App\Models\Inventory::select('id','price')->where('products.id',$sitem->id)->first();
                                    
                                    @$ptamount1 += @$pdata->price*@$sitem->price/100;               
                                  }
                                }
                          }
                        } else {
                            foreach($explode_id as $key=>$serviceid) {
                                $servicedata = App\Models\Service::select('servicename','price')
                                ->where('services.id',$serviceid)->first();
                                $ptamount += $servicedata->price*$percentall[0]->allspvalue/100;               
                             }   
                            $ptamount1 = 0;
                            foreach($pexplode_id as $key=>$pid) {
                                $pdata = App\Models\Inventory::select('id','price')->where('products.id',$pid)->first();
                                @$ptamount1 += @$pdata->price*@$percentall[0]->allspvalue/100;               
                            }
                        }  
                        $ptamounttotal =$ptamount+$ptamount1;
                    }
                @endphp
                <tr class="sub-container">
                    <td style="display: flex;">
                                                    <div class="glyphicon glyphicon-plus plusIcon">+</div>
                                                    <div class="glyphicon glyphicon-minus plusIcon" style="display:none">-</div>
                        {{$value->personnelname}}
                    </td>
                    <td>{{$value->counttotal}}</td>
                    <td>${{@$ttlflat}}</td>
                    <td>${{@$ptamounttotal}}</td>
                    <td>${{@$ttlflat+@$ptamounttotal}}</td>
                    <td>
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <input class="form-check-input flexCheckDefault" type="checkbox" value="" id="flexCheckDefault">
                    </td>
                </tr>
            @endforeach
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
                                @foreach($tickedatadetails as $key=>$value)
                                    @php

                                      $explode_id = explode(',', $value->serviceid);
                                      $servicedata = App\Models\Service::select('servicename','price')
                                        ->whereIn('services.id',$explode_id)->get();

                                      $pexplode_id = explode(',', $value->product_id);
                                      $pdata = App\Models\Inventory::select('id','price','productname')
                                        ->whereIn('products.id',$pexplode_id)->get();
                                        $ttlflat = 0;
                                        $ptamounttotal = 0;
                                        if(count($amountall)>0) {

                                            if($amountall[0]->allspvalue==null) {
                                                
                                                foreach($explode_id as $servicekey =>$servicevalue) {
                                                    foreach($comisiondataamount->service as $key=>$sitem)
                                                    {
                                                        if($sitem->id==$servicevalue && $sitem->price!=0)
                                                        {
                                                        //echo $servicevalue."==={$sitem->id}price==".  $sitem->price."<br>";
                                                          $ttlflat += $sitem->price;
                                                        }
                                                    }
                                                }
                                                foreach($pexplode_id as $servicekey =>$servicevalue) {
                                                    foreach($comisiondataamount->product as $key=>$sitem1)
                                                    {
                                                        if($sitem->id==$servicevalue && $sitem1->price!=0)
                                                        {
                                                        //echo $servicevalue."==={$sitem->id}price==".  $sitem->price."<br>";
                                                          $ttlflat += $sitem1->price;
                                                        }
                                                    }
                                                }
                                                
                                            } else {
                                                $flatvalue = $amountall[0]->allspvalue;

                                                $flatv = $flatvalue*count($explode_id); 
                                                $pvalue  =  $flatvalue*count($pexplode_id);
                                                
                                                $ttlflat = $flatv+$pvalue;
                                            }
                                        }

                                        if(count($percentall)>0) {
                                         $ptamount = 0;
                                         $sname = array();
                                         $ptamount1 = 0;
                                         $pname = array();

                                         if($percentall[0]->allspvalue == null) {
                                            foreach($explode_id as $servicekey =>$servicevalue) {
                                                foreach($comisiondatapercent->service as $key=>$sitem)
                                                {
                                                  if($sitem->id==$servicevalue && $sitem->price!=0)
                                                  {
                                                    $servicedata = App\Models\Service::select('servicename','price')
                                                    ->where('services.id',$sitem->id)->first();
                                                    $ptamount += $servicedata->price*$sitem->price/100;               
                                                  }
                                                }
                                            }
                                            foreach($pexplode_id as $key=>$pid) {
                                                foreach($comisiondatapercent->product as $key=>$sitem)
                                                {
                                                  if($sitem->id==$pid && $sitem->price!=0)
                                                  {
                                                    $pdata = App\Models\Inventory::select('id','price')->where('products.id',$sitem->id)->first();
                                                    
                                                    @$ptamount1 += @$pdata->price*@$sitem->price/100;               
                                                  }
                                                }
                                            }
                                         } else {
                                         foreach($servicedata as $key1=>$value1) {
                                           $ptamount += $value1->price*$percentall[0]->allspvalue/100;
                                           $ptamount222 =  $ptamount *count($servicedata);
                                           $sname[] = $value1->servicename;
                                           $servname = implode(',',$sname);
                                         }
                                         
                                         foreach($pdata as $key2=>$value2) {

                                           @$ptamount1 += @$value2->price*@$percentall[0]->allspvalue/100;
                                           @$pname[] = @$value2->productname;
                                           $productname = implode(',',$pname);
                                         }
                                     }
                                         $ptamounttotal =$ptamount+$ptamount1;
                                         
                                        }
                                    @endphp
                                    <tr style="font-size: 17px; border:none; background:white;">
                                        <td>{{$value->ticketdate}}</td>
                                        <td>{{$value->id}}</td>
                                        <td>{{@$servname}}</td>
                                        <td>{{@$productname}}</td>
                                        <td>${{@$ttlflat}}</td>
                                        <td>${{@$ptamounttotal}}</td>
                                        <td>${{@$ttlflat+@$ptamounttotal}}</td>
                                    </tr>
                                @endforeach
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
</form>
   </div>
  </div>
     
@endsection

@section('script')
<script type="text/javascript">
    $('.dropify').dropify();
    $(document).ready(function() {
     // $('#example').DataTable({
     //  "order": [[ 0, "desc" ]]
     //  });
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

    $('.puser').on('change', function() {
      var pid = this.value;
      $("#phiddenid").val(pid);
      this.form.submit();
    });
</script>
@endsection


