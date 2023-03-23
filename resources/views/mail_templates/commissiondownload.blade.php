<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>


<body>
    <h4>Commission Report for Personnel : {{$pname}}</h4>
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
        @php
            if(@$from!=null && @$to!=null) {
                $since = date('Y-m-d', strtotime($from));
                $until = date('Y-m-d', strtotime($to));
                @$tickedatadetailsdata = \App\Models\Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->where('quote.ticket_status',3)->whereColumn('quote.personnelid','quote.primaryname')->whereBetween('quote.ticketdate', [$since, $until])->get();
            } else {
                @$tickedatadetailsdata = \App\Models\Quote::select('quote.*','personnel.personnelname')->join('personnel', 'personnel.id', '=', 'quote.personnelid')->where('quote.personnelid',$persid)->whereColumn('quote.personnelid','quote.primaryname')->where('quote.ticket_status',3)->get();
            }

        @endphp
        @foreach($tickedatadetailsdata as $key=>$value)
            @php
                $ids=$value->id;
                if(!empty($value->parentid))
                {
                    $ids=$value->parentid;

                }
                $explode_id = explode(',', $value->serviceid);
                $servicedata = App\Models\Service::select('servicename','price')
                                ->whereIn('services.id',$explode_id)->get();
                $pexplode_id = 0;
                $productname = "--";
                if($value->product_id!=null || $value->product_id!="") {   
                  $pexplode_id = explode(',', $value->product_id);
                   $pdata = App\Models\Inventory::select('id','price','productname')
                    ->whereIn('products.id',$pexplode_id)->get();
                }
             
                $ttlflat = 0;
                $ptamounttotal = 0;
                if(count($amountall)>0) {

                    if($amountall[0]->allspvalue==null) {
                        foreach($servicedata as $key1=>$value1) {
                          $sname[] = $value1->servicename;
                           $servname = implode(',',$sname);
                        }
                        if($pexplode_id!=0) {
                        foreach($pdata as $key2=>$value2) {
                           @$pname[] = @$value2->productname;
                           $productname = implode(',',$pname);
                         }
                        }

                        $ttlflat1 = 0;

                        foreach($explode_id as $servicekey =>$servicevalue) {
                            foreach($comisiondataamount->service as $key=>$sitem)
                            {
                                if($sitem->id==$servicevalue && $sitem->price!=0)
                                {
                                //echo $servicevalue."==={$sitem->id} price==".  $sitem->price."<br>";
                                  $ttlflat1 += $sitem->price;
                                }
                            }
                        }

                        $ttlflat2 = 0;
                    if($pexplode_id!=0) {
                        foreach($pexplode_id as $servicekey =>$servicevalue) {
                            foreach($comisiondataamount->product as $key=>$sitem1)
                            {
                                if($sitem1->id==$servicevalue && $sitem1->price!=0)
                                {
                                  $ttlflat2 += $sitem1->price;
                                }
                            }
                        }
                    }
                        $ttlflat = $ttlflat1 +$ttlflat2;
                        
                    } else {

                         foreach($servicedata as $key1=>$value1) {
                          $sname[] = $value1->servicename;
                           $servname = implode(',',$sname);
                        }

                        if($pexplode_id!=0) {
                           foreach($pdata as $key2=>$value2) {
                           @$pname = @$value2->productname;
                           $productname = $pname;
                         }
                        }

                $flatvalue = $amountall[0]->allspvalue;

                $flatv = $flatvalue*count($explode_id); 
                if($pexplode_id!=0) {
                    $pvalue  =  $flatvalue*count($pexplode_id);
                } else {
                    $pvalue = 0;  
                }
                
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
                    if($pexplode_id!=0) {
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
                    }
                 } else {
                 foreach($servicedata as $key1=>$value1) {
                   $ptamount += $value1->price*$percentall[0]->allspvalue/100;
                   $ptamount222 =  $ptamount *count($servicedata);
                   $sname[] = $value1->servicename;
                   $servname = implode(',',$sname);
                 }
                if($pexplode_id!=0) { 
                 foreach($pdata as $key2=>$value2) {

                   @$ptamount1 += @$value2->price*@$percentall[0]->allspvalue/100;
                   @$pname[] = @$value2->productname;
                   $productname = implode(',',$pname);
                 }
               }
             }
                 $ptamounttotal =$ptamount+$ptamount1;
                 
                }
            @endphp
            <tr style="font-size: 17px; border:none; background:white;">
                <td>{{$value->ticketdate}}</td>
                <td>{{$ids}}</td>
                <td >{{Str::limit(@$servname, 20)}}</td>
                <td>{{Str::limit(@$productname, 20)}}</td>
                <td>${{@$ttlflat}}</td>
                <td>${{@$ptamounttotal}}</td>
                <td>${{@$ttlflat+@$ptamounttotal}}</td>
            </tr>
        @endforeach
    </tbody>
  </table>
</body>
</html>