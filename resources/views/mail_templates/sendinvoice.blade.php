<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>

<body>
<div style="max-width: 680px;margin:auto;background: url('') no-repeat center top;background-size: cover;padding: 25px;">
<div class="banner" style="background: #fff;width: 112%;border-radius: 4px;height: auto;border: 2px solid #ccc;">
<div>
    @php
        $usrcolor = App\Models\User::select('color','company_address','footercontent')->where('id',$quoteuserid)->first();
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
    @endphp
<div class="text-center" style="background-color: {{$color}};border-radius: 4px;" >
    <table style="width:100%">
        <tbody>
            <tr>
                <td style=" width: 70%; padding: 12px;">
                    <h1 style="color: #fff;">INVOICE #{{ $invoiceId }}</h1>
                </td>
                <td style="vertical-align: top; padding: 1px 0px;">
                    <p style="margin: 0px 0 5px 0;color: #ccc; font-size: 16px; "><img src="{{$cimage}}" style="width: 50%"></p>
                    <h4 style="color: #fff">
                    <p style="margin: 5px 0px;">@if($usrcolor->company_address!=""){{ $usrcolor->company_address }}@endif</p>
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
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; ">Bill to:<br><span style="color: black; font-weight: bold;">Name - </span> <span style="color: black; font-weight: bold;">{{$customername}}</span></p>
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; "><br><span style="color: black; font-weight: bold;">Company Name - </span> <span style="color: black; font-weight: bold;">{{$companyname}}</span></p>
        
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; "><br><span style="color: black; font-weight: bold;">Phone Number -</span> <span style="color: black; font-weight: bold;">{{$phone}}</span></p>
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; "><br><span style="color: black; font-weight: bold;">Email -</span> <span style="color: black; font-weight: bold;">{{$email}}</span></p>
        <p style="margin: 0px 0 0px 0;color: #ccc; font-size: 16px; "><br><span style="color: black; font-weight: bold;">Service Address -</span> <span style="color: black; font-weight: bold;">{{$address}}</span></p>
    </td>
    <td style="vertical-align: top; padding: 17px">
        <p style="margin: 0px 0 5px 0;color: #ccc; font-size: 16px; ">Invoice:<br><span style="color: black; font-weight: bold;">#{{ $invoiceId }} </span></p>
        <p style="margin: 0px 0 5px 0;color: #ccc; font-size: 16px; ">Date:<br><span style="color: black; font-weight: bold;">{{ $date }} </span></p>
        <p style="margin: 0px 0 5px 0;color: #ccc; font-size: 16px; ">Invoice due date:<br><span style="color: black; font-weight: bold;">{{ $duedate }} </span></p>
    </h4>
    </td>
 </tr>
  </tbody>
</table>
<div class="table-responsive">
    <table class="table no-wrap table-new table-list align-items-center">
    <thead style="color: #ccc;padding: 12px;">
        <tr>
            <th style="padding: 15px; width: 50%; font-size:14px;">SERVICE PROVIDED</th>
            <th style="padding: 15px; width: 50%; font-size:14px;">DESCRIPTION</th>
            <th>QTY</th>
            <th style="padding: 15px; width: 15%; font-size:14px;">PRICE</th>
            <th style="padding: 15px; width: 15%; font-size:14px;">TAX</th>
            <th style="padding: 15px; width: 15%; font-size:14px;">AMOUNT</th>
        </tr>
    </thead>
    <tbody style="padding: 12px; text-align: center;">
        @php
            $serviceidarray = explode(',', $serviceid);
      $servicedetails = App\Models\Service::select('servicename','price','description')
    ->whereIn('id', $serviceidarray)->get();
      $sum = 0;
      foreach ($servicedetails as $key => $value) {
        $sname[] = $value['servicename'];
        $sum+= $value['price'];
      }

      $pidarray = explode(',', $productid);
      $pdetails = 
      App\Models\Inventory::select('productname','id','price','description')
    ->whereIn('id', $pidarray)->get();
      $sum1 = 0;
      foreach ($pdetails as $key => $value) {
        $pname[] = $value['productname'];
        $sum1+= $value['price'];
      } 

      $totalprice = $sum+$sum1;
        @endphp
    @foreach($servicedetails as $key => $value)
    <tr  style="color:#5a5959;box-shadow: 0px 0px 7px #ccc;">
        <td style="padding: 15px;">{{ $value['servicename'] }}</td>
        <td style="padding: 15px;">-</td>
        <td style="padding: 15px;">1</td>
        <td style="padding: 15px;">${{ $value['price'] }}</td>
        <td style="padding: 15px;">0%</td>
        <td style="padding: 15px;">${{ $value['price'] }}</td>
    </tr>
    @endforeach
    @foreach($pdetails as $key => $value)
    <tr  style="color:#ddd;">
        <td style="padding: 15px;">{{ $value['productname'] }}</td>
        <td style="padding: 15px;">{{ $value['description'] }}</td>
        <td style="padding: 15px;">1</td>
        <td style="padding: 15px;">${{ $value['price'] }}</td>
        <td style="padding: 15px;">0%</td>
        <td style="padding: 15px;">${{ $value['price'] }}</td>
    </tr>
    @endforeach

    </tbody>
    </table>
  <div class="text-center" style="border-radius: 4px;" >
    <table style="width: 94%;background:#f8f6f6;border-radius: 10px;
    margin: 22px;">
    <tbody>
     <tr>
        <td style="vertical-align: top; padding: 17px;background-color: {{$color}};">
         <p style="margin: 0px 0 5px 0;color: #ccc; font-size: 20px;">Note:
            <br><span style="color: #ccc;  font-size: 16px;">{{ @$description }}</span></p>
        </td>
        <td style="width: 50%; padding: 12px;background-color: {{$color}}; border-radius: 10px;">
            <h5 style="margin: 0px 0 5px 0;color: #ccc;font-size: 22px; ">Total:<br><h1 style="color: #fff; font-weight: bold; font-size: 36px;margin-left: 55%">${{ $totalprice }} </h1></h5>

        </td>
        </tr>
    </tbody>
</table>
    <table style="width:96%;margin: auto;">
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
