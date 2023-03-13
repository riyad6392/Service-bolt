<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body>
    @if(isset($type) && ($type == "sendinvoice"))
        @if($body!=null)
            <span>{!!$body!!}</span>
        @endif
    @endif
</body>
</html>
