<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>


<body>
<div style="max-width: 680px;margin:auto;background: url('') no-repeat center top;background-size: cover;padding: 25px;">
<div class="banner" style="background: #fff;width: 100%;border-radius: 4px;height: auto;border: 2px solid #ccc;">
<div>
    @php
        $usrcolor = App\Models\User::select('color','company_address','footercontent','txtcolor')->where('id',auth()->user()->id)->first();
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
<div class="text-center" style="background-color: {{$color}};border-radius: 4px;">
    <table style="width:100%">
        <tbody>
            <tr>
                  <td style="position: relative;left: 47% !important;font-size: 21px;"> Invoice</td>  
            </tr>
            <tr>
            
                <td style=" width: 70%; padding: 12px">
                    <h1 style="color: {{$txtcolor}};margin: 0;"><img src="{{$cimage}}" style="width: 30%"></h1>
                </td>
                <td style="vertical-align: top; padding: 1px 0px;">
                   
                    <h4 style="color: #fff; margin:0;">
                    <p style="margin: 5px 0px;color:{{$txtcolor}};">@if($usrcolor->company_address!=""){{ $usrcolor->company_address }}@endif</p>
                    </h4>
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
        <p style="margin: 0px 0 5px 0;color: #ccc; font-size: 16px; ">Bill to:</p>
            <span style="color: black; font-weight: bold;">Name - {{$customername}}</span> <br>
            <span style="color: black; font-weight: bold;">Company Name -{{$companyname}}</span>
        
        <br><span style="color: black; font-weight: bold;">Phone Number -{{$phone}}</span>
        <br><span style="color: black; font-weight: bold;">Email -{{$email}}</span>
        <br><span style="color: black; font-weight: bold;">Service Address -{{$address}}</span>
    </td>
    <td style="vertical-align: top; padding: 17px">
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; ">Invoice:<br>
            <span style="color: black; font-weight: bold;">#{{ $invoiceId }} </span>
        </p>
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; ">Date:<br>
            <span style="color: black; font-weight: bold;">{{date('m-d-Y', strtotime($date))}} </span>
        </p>
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; ">Invoice due date:<br>
            <span style="color: black; font-weight: bold;">
            @if($duedate!="")
                {{date('m-d-Y', strtotime($duedate))}}
            @else
                ---
            @endif    
        </span>
        </p>
    </h4>
    </td>
 </tr>
  </tbody>
</table>
<div class="table-responsive">
    <table class="table table-striped no-wrap table-new table-list align-items-center">
    <thead style="width: 100%; color: #000;padding: 12px;">
        <tr>
            <th style="padding: 15px; width: 50%;font-size: 13px;border-bottom: 1px solid #ccc;">Item</th>
            <th style="padding: 15px; width: 50%;font-size: 13px;border-bottom: 1px solid #ccc;">DESCRIPTION</th>
            <th style="font-size: 13px;border-bottom: 1px solid #ccc;">QTY</th>
            <th style="padding: 15px; width: 15%;font-size: 13px;border-bottom: 1px solid #ccc;">PRICE</th>
            <th style="padding: 15px; width: 15%;font-size: 13px;border-bottom: 1px solid #ccc;">TAX</th>
            <th style="padding: 15px; width: 15%;font-size: 13px;border-bottom: 1px solid #ccc;">AMOUNT</th>
        </tr>
    </thead>
    <tbody style="padding: 12px; text-align: center;">
        @php
            
      $servicedetails = App\Models\Service::select('servicename','price','description')
    ->whereIn('id', $serviceid)->get();

    $userdetails = App\Models\User::select('taxtype','taxvalue','servicevalue','productvalue')
    ->where('id', auth()->user()->id)->first();

      $sum = 0;
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $txvalue = 0;
        if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") {
                $txvalue = $value['price']*$userdetails->servicevalue/100; 
            } else {
                $txvalue = 0;
            }
        }

        $sum+= $value['price'] + $txvalue;
      }

     
      $pdetails = 
      App\Models\Inventory::select('productname','id','price','description')
    ->whereIn('id', $productid)->get();

      $sum1 = 0;
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
        $txvalue1 = 0;
         if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
           if($userdetails->productvalue != null || $userdetails->taxtype == "both") { 
                $txvalue1 = $value['price']*$userdetails->productvalue/100; 
            } else {
                $txvalue1 = 0;
            }
        }
        $sum1+= $value['price'] + $txvalue1;
      } 

      $totalprice = $sum+$sum1;
      $totalprice = number_format($totalprice,2);
      $totalprice = preg_replace('/[^\d.]/', '', $totalprice);
      $i=0;
        @endphp
    @foreach($servicedetails as $key => $value)
        @php
        
                $txvalue = 0;
                $txtpercentage = 0;
          if($userdetails->taxtype == "service_products" || $userdetails->taxtype == "both") {
            if($userdetails->servicevalue != null || $userdetails->taxtype == "both") { 
                $txvalue = $value['price']*$userdetails->servicevalue/100; 
                $txtpercentage = $userdetails->servicevalue;
            } else {
                $txvalue = 0;
                $txtpercentage = 0;
            }
        }  
        @endphp
        @if($i % 2 == 0)
            <tr style="background-color:#ccc;">
        @else
            <tr style="background-color:#5a5959;">
        @endif        
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{ $value['servicename'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{ $value['description'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">1</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ $value['price'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{$txtpercentage}}%</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ number_format((float)$value['price'] + (float)$txvalue, 2, '.', '') }}</td>
    </tr>
    @php
        $i++;
    @endphp
    @endforeach
    @php
        $i=0+count($servicedetails);
     @endphp
    @foreach($pdetails as $key => $value)
    @php
        $txvalue1 = 0;
        $txtpercentage1 = 0;
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
            <tr style="background-color:#ccc;">
        @else
            <tr style="background-color:#5a5959;">
        @endif 
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{ $value['productname'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{ $value['description'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">1</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ $value['price'] }}</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">{{$txtpercentage1}}%</td>
        <td style="padding: 15px;border-bottom: 1px solid #ccc;">${{ number_format((float)$value['price'] + (float)$txvalue1, 2, '.', '') }}</td>
    </tr>
    @php
        $i++;
    @endphp
    @endforeach

    </tbody>
    </table>
  <div class="text-center" style="border-radius: 4px;" >
    <table style="width: 100%;background:#f8f6f6;border-radius: 10px;
    margin: 0px;">
    <tbody>
     <tr>
        <td style="vertical-align: top;padding: 0px 12px; border-radius: 10px;background-color: {{$color}};">
         <p style="margin: 0px 0 5px 0;color: {{$txtcolor}}; font-size: 20px;">Invoice Note:
            <br><span style="color: {{$txtcolor}};  font-size: 16px;">@if(@$invoicenote){{@$invoicenote}}@else --- @endif</span></p>
        </td>
        <td style="width: 50%;padding: 0px 12px;background-color: {{$color}}; border-radius: 10px;">
            <h5 style="margin: 0px 0 5px 0;color: {{$txtcolor}};font-size: 22px; ">Total:<br><h1 style="color: {{$txtcolor}}; font-weight: bold; font-size: 22px;">${{ $totalprice }} </h1></h5>

        </td>
        </tr>
    </tbody>
</table>
    <table style="width: 96%;
    margin: auto;">
        <tbody>
         <tr>
          <td style="text-align: center; ">
            <img src="{{$cdimage}}" style="max-width: 100px;" >
         <p style="color: #ccc; font-size: 16px;
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
