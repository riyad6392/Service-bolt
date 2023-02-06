<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>

<body>
<div style="max-width: 680px;margin:auto;background: url('') no-repeat center top;background-size: cover;padding: 25px;">
<div class="banner" style="background: #fff;width: 112%;border-radius: 4px;height: auto;border: 0px solid #a4a0a0;">
<div>
    @php
        $usrcolor = App\Models\User::select('color','companyname','company_address','phone','footercontent','txtcolor')->where('id',$quoteuserid)->first();
        if($usrcolor->color!=""){
            $color = $usrcolor->color;
        } else {
            $color = "#000";
        }

        if($usrcolor->footercontent!=null) {
            $footercontent = $usrcolor->footercontent;
        } else {
            $footercontent = "";
        }

        if($usrcolor->txtcolor!=null) {
           $txtcolor = $usrcolor->txtcolor;
        } else {
           $txtcolor = "#fff";
        }
    @endphp
<div class="text-center" style="background-color: {{$color}};border-radius: 0px;border:2px solid #a4a0a0">
    <table style="width:100%">
        <tbody>
            <tr>
                <td style=" width: %;">
                    INVOICE
                </td>

                   <td style="position: relative; left: 0% !important;font-size: 21px;text-align:center !important; display: block; top:6px;color:{{$txtcolor}};">
                    <h1 style="color: {{$txtcolor}};margin: 0;"><img src="{{$cimage}}" style="width: 100px;"></h1>
                </td> 

                <td style="vertical-align: middle; padding: 1px 0px; width:30%">
                   
                    <span style="color: #fff; margin:0;">
                    <p style="margin: 0; padding:0; color:#605252;"><span>{{$usrcolor->companyname}}</span>
                    </p>

                    <p style="margin: 0px 0px;color:#605252;">@if($usrcolor->company_address!=""){{ $usrcolor->company_address }}@endif</p>
                    <p style="margin:3px 0; padding:0; color:#605252;">
                        <span>{{$usrcolor->phone}}</span>
                    </p>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="border-bottom: 2px solid #ccc;text-align: center;">
    </div>
</div>
<table style="width: 100%;border: 1px solid #ccc;border-radius: 6px;
    background: #f8f7f7;padding: 0px 0px;">
 <tbody>
 <tr>
    <td style="vertical-align: top; width: 70%; padding: 12px">
        <p style="margin: 0px 0 5px 0;color: #000; font-size: 16px; ">Bill to:</p>
            <span style="color: black; font-weight: bold;"></span>{{$customername}} 
            <!-- <span style="color: black; font-weight: bold;">Company Name -</span>{{$companyname}}
        
        <br><span style="color: black; font-weight: bold;">Phone Number -</span>{{$phone}} -->
        <!-- <br><span style="color: black; font-weight: bold;">Email -</span>{{$email}} -->
        <br><span style="color: black; font-weight: bold;"></span>
        @if(isset($billingaddress) && $billingaddress!="")
            {{$billingaddress}}
        @else
            {{$address}}
        @endif
    </td>
    <td style="vertical-align: top; padding: 17px">
        <p style="margin: 0px 0 0px 0;color: #000; font-size: 16px; ">Invoice:<br><span style="color: black;">#{{ $invoiceId }} </span></p>
        <p style="margin: 0px 0 0px 0;color: #000; font-size: 16px; ">Date:<br><span style="color: black;">{{date('m-d-Y', strtotime($date))}} </span></p>
        <p style="margin: 0px 0 0px 0;color: #000; font-size: 16px; ">Invoice due date:<br><span style="color: black;">
            @if($duedate!="")
                {{date('m-d-Y', strtotime($duedate))}}
            @else
                Due on reciept
            @endif
         </span></p>
    </h4>
    </td>
 </tr>
  </tbody>
</table>
<div class="table-responsive">
    <table class="table no-wrap table-new table-list align-items-center" style="width: 100%;background: #a4a0a0;">
    <thead style="width: 100%; background-color: #ccc;padding: 12px;">
        <tr>
            <th style="padding: 15px; width: 50%; font-size:13px;border-bottom: 1px solid #ccc;">Item</th>
            <th style="padding: 15px; width: 50%; font-size:13px;border-bottom: 1px solid #ccc;">DESCRIPTION</th>
            <th style="padding: 15px; width: 50%; font-size:13px;border-bottom: 1px solid #ccc;">QTY</th>
            <th style="padding: 15px; width: 15%; font-size:13px;border-bottom: 1px solid #ccc;">PRICE</th>
            <th style="padding: 15px; width: 15%; font-size:13px;border-bottom: 1px solid #ccc;">TAX</th>
            <th style="padding: 15px; width: 15%; font-size:13px;border-bottom: 1px solid #ccc;">AMOUNT</th>
        </tr>
    </thead>
    <tbody style="padding: 12px; text-align: center;">
        @php
          $serviceidarray = explode(',', $serviceid);
          $servicedetails = App\Models\Service::select('servicename','price','description')
        ->whereIn('id', $serviceidarray)->get();

        $worker = App\Models\User::select('userid','workerid')->where('id',auth()->user()->id)->first();

        $userdetails = App\Models\User::select('taxtype','taxvalue','servicevalue','productvalue')
        ->where('id', $worker->userid)->first();

      $sum = 0;
      $price1=0;
      $tax1=0;
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $txvalue = 0;
        $txtpercentage = 0;
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
                $txvalue = $value['price']*$userdetails->servicevalue/100;

                $txtpercentage = $userdetails->servicevalue; 
            }
        }
        $sum+= $value['price'] + $txvalue;
        $price1+= $value['price'];
        $tax1+= $txvalue;
       }
       
      $pidarray = explode(',', $productid);
      $pdetails = 
      App\Models\Inventory::select('productname','id','price','description')
    ->whereIn('id', $pidarray)->get();
      $sum1 = 0;
      $price2=0;
      $tax2=0;
      $txtpercentage1 = 0;
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
        $txvalue1 = 0;
         if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
           if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
                $txvalue1 = $value['price']*$userdetails->productvalue/100; 
                $txtpercentage1 = $userdetails->productvalue; 
            } else {
                $txvalue1 = 0;
                $txtpercentage1 = 0;
            }
        }

        $sum1+= $value['price'] + $txvalue1;
        $price2+= $value['price'];
        $tax2+= $txvalue1;
      } 

      $totalprice = $sum+$sum1;
      $totalprice = number_format($totalprice,2);
      $totalprice = preg_replace('/[^\d.]/', '', $totalprice);

      $subtotalprice = $price1+$price2;
      $subtotalprice = number_format($subtotalprice,2);
      $subtotalprice = preg_replace('/[^\d.]/', '', $subtotalprice);

      $taxprice = $tax1+$tax2;
      $taxprice = number_format($taxprice,2);
      $taxprice = preg_replace('/[^\d.]/', '', $taxprice);

      $i=0;
      $txtpercentage = 0;
        @endphp
    @foreach($servicedetails as $key => $value)
    @php
         $txvalue = 0;
        $txtpercentage = 0;
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
                $txvalue = $value['price']*$userdetails->servicevalue/100;

                $txtpercentage = $userdetails->servicevalue; 
            }
        }
    @endphp
        @if($i % 2 == 0)
            <tr style="background-color:#fff;">
        @else
            <tr style="background-color:#ccc;">
        @endif  
            <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{ $value['servicename'] }}</td>
            <td style="padding: 15px;border-bottom: 1px solid #ccc;">-</td>
            <td style="padding: 15px;border-bottom: 1px solid #ccc;">1</td>
            <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ $value['price'] }}</td>
            <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{@$txtpercentage}}%</td>
            <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ number_format((float)$value['price'] + (float)$txvalue, 2, '.', '') }}</td>
        </tr>
    @php
        $i++;
    @endphp
    @endforeach
    @php
        $i=0+count($servicedetails);
        $txtpercentage1 = 0;
     @endphp
    @foreach($pdetails as $key => $value)
        @php
            $txvalue1 = 0;
         if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
           if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
                $txvalue1 = $value['price']*$userdetails->productvalue/100; 
                $txtpercentage1 = $userdetails->productvalue; 
            } else {
                $txvalue1 = 0;
                $txtpercentage1 = 0;
            }
        }
        @endphp
        @if($i % 2 == 0)
            <tr style="background-color:#fff;">
        @else
            <tr style="background-color:#ccc;">
        @endif
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{ $value['productname'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{ $value['description'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">1</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ $value['price'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{@$txtpercentage1}}%</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ number_format((float)$value['price'] + (float)$txvalue1, 2, '.', '') }}</td>
    </tr>
    @php
        $i++;
    @endphp
    @endforeach

    </tbody>
    </table>
  <div class="text-center" style="border-radius: 1px;
    border: 2px solid #a4a0a0;  border-top: 0;" >
    <table style="width: 100%;background:#f8f6f6;border-radius: 10px;
    margin: 0px;">
    <tbody>
    <tr>
        <td style="vertical-align: top; padding: 12px;background-color: {{$color}};">
         <p style="margin: 0px 0 5px 0;color: {{$txtcolor}}; font-size: 20px;border-radius: 10px;">Note:
            <br><span style="color: {{$txtcolor}};  font-size: 16px;">{!! @$description !!}</span></p>
        </td>
        <td style="width: 34%;padding: 0px 12px;background-color: {{$color}}; border-radius: 10px;">
    <p style="border-bottom: 1px solid; margin: 0px 0 5px 0;color: {{$txtcolor}};font-size: 16px; ">Subtotal: {{$subtotalprice}}</p>
    <p style=" border-bottom: 1px solid; margin: 0px 0 5px 0;color: {{$txtcolor}};font-size: 16px; ">Sales Tax: {{$taxprice}}</p>
    <p style=" border-bottom: 1px solid; margin: 0px 0 5px 0;color: {{$txtcolor}};font-size: 18px; ">Total: ${{ $totalprice }}</p>
        </td> 
    </tr>
    </tbody>
</table>
    <table style="width:96%;margin: auto;">
        <tbody>
         <tr>
          <td style="text-align: center; ">
            <!-- <img src="{{$cdimage}}" style="max-width: 100px;" > -->
         <p style="color: #000; font-size: 14px;
        ">{{$footercontent}}</p>
        </td>
        </tr>
        </tbody>
    </table>
  </div>
 </div>

 </div>
    </div>
    </div>
</body>
</html>
