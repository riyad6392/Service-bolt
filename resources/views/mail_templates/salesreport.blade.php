<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>


<body>
  
<div class="tab-pane fade" id="sale" role="tabpanel" aria-labelledby="sale-tab">
   <div class="col-md-12">
<div class="card">
<div class="card-body">
<div class="col-lg-12">
<div class="table-responsive">
 <table id="example111" class="table no-wrap table-new table-list no-footer" style="position: relative;">
     <thead>
           <tr>
              <th>Date</th>
              <th># of Tickets</th>
              <th>Service Sold <br>Total ($)</th>
              <th>Product Sold <br>Total ($)</th>
              <th>Ticket Total</th>
              <th>Billing Total</th>
            </tr>
     </thead>
        <tbody class="tbody-1">
            @foreach($salesreport as $key => $value)
         @php
            if($value->tickettotalprice==null || $value->tickettotalprice==0 || $value->tickettotalprice=="") {
              $newprice = $value->totalprice;
            } else {
              $newprice = $value->tickettotalprice;
            }

           $arrayv = explode(",",$value->serviceid);
           $countsf = array_count_values($arrayv);
           arsort($countsf);

           $totalssold = 0;
           foreach($countsf as $key1=>$value1) {
                $servicedata = App\Models\Service::select('price')
                    ->where('id',$key1)->get();
                $totalssold =  $totalssold+@$servicedata[0]->price * $value1;
           }

           $parrayv = explode(",",$value->product_id);
           $pcountsf = array_count_values($parrayv);
           arsort($pcountsf);
           
           $totalpsold = 0;
           foreach($pcountsf as $key11=>$value11) {
                $pdata = App\Models\Inventory::select('price')
                    ->where('id',$key11)->get();
                $totalpsold =  $totalpsold+@$pdata[0]->price * $value11;
           }

          @endphp
               <tr class="sub-container">
                    <td style="display: flex;">{{date('m-d-Y', strtotime($value->date))}}</td>
                      <td>{{$value->totalticket}}</td>
                      <td>{{$totalssold}}</td>
                      <td>{{$totalpsold}}</td>
                      <td>{{number_format((float)$newprice, 2, '.', '')}}</td>
                      <td>{{number_format((float)$value->totalprice, 2, '.', '')}}</td>
               </tr>
               
                <tr class="explode hide" style="">
                    <td colspan="8" id="toggle_text">
                        <table class="table table-condensed">
                            <thead>
                                <tr style="font-family: system-ui;">
                                    <th>Personnel</th>
                                    <th>Customer</th>
                                    <th>Ticket Total</th>
                                    <th>Billing Total</th>
                                 </tr>
                            </thead>
                            <tbody>
                                 @php
                                    $billingData = \App\Models\Quote::select('quote.id','quote.parentid','quote.serviceid','quote.product_id','quote.price','quote.tickettotal','quote.givendate','quote.etc','quote.payment_status','quote.personnelid','quote.primaryname','quote.tax', 'customer.customername', 'customer.email','personnel.personnelname','services.servicename')->join('customer', 'customer.id', '=', 'quote.customerid')->join('services', 'services.id', '=', 'quote.serviceid')->leftJoin('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.userid',auth()->user()->id)->whereColumn('quote.personnelid','quote.primaryname')->whereIn('quote.ticket_status',['3','5','4'])->whereBetween('quote.givenstartdate', [$value->date, $value->date])->where('quote.payment_status','!=',null)->where('quote.payment_mode','!=',null)->orderBy('quote.id','desc')->get();
                                @endphp
                                @foreach($billingData as $key1=>$value1)
                                @php
                                    if($value1->tickettotal==null || $value1->tickettotal==0 || $value1->tickettotal=="") {
                                      $newprice = $value1->price;
                                    } else {
                                      $newprice = $value1->tickettotal;
                                    }
                                    $ids=$value1->id;
                                    if(!empty($value1->parentid))
                                    {
                                        $ids=$value1->parentid;

                                    }
                                  @endphp
                                <tr style="font-size: 17px; border:none; background:white;">
                                    <td>@if($value1->personnelname!="")
                                            {{@$value1->personnelname}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{$value->customername}}</td>
                                    <td>{{number_format((float)$newprice, 2, '.', '')}}</td>
                                    <td>{{number_format((float)$value1->price, 2, '.', '')}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
               @endforeach 
                </tbody>
                
           </tbody>
    </table>
 </div>
</div>
</div>
</div>
</div> 
    
  </div>

</body>
</html>