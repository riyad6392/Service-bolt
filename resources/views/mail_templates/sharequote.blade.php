<div>
	<p>Thanks for your interest in {{auth()->user()->companyname}}. Your {{$name}} details are below. </p>
    Address: {{ $address }}
    <br>
    Service Name: {{ $servicename }}
    <br>
    Type: {{ $type }}
    <br>
    Frequency: {{ $frequency }}
    <br>
    Time: {{ $time }}
    <br>
    Price: {{ $price }}
    <br>
    ETC: {{ $etc }}
    <br>
    Description: {{ $description }}
    <br>
    <p>Thanks</p>
    <p>ServiceBolt Team</p>
</div>