@extends('layouts.header')
@section('content')
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>jq.Schedule Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
     <link rel="stylesheet" type="text/css" href="{{ asset('css/style.min.css')}}">

    
</head>
<body>
    <div id="schedule"></div>
    <div style="padding: 12px 0 0;">
            <div id="logs" class="table-responsive"></div>
        </div>
@endsection
@section('script')
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" type="text/javascript" language="javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="{{ asset('js/jq.schedule.min.js')}}"></script>
<script type="text/javascript">
    function addLog(type, message){
        var $log = $('<tr />');
        $log.append($('<th />').text(type));
        $log.append($('<td />').text(message ? JSON.stringify(message) : ''));
        $("#logs table").prepend($log);
    }
    $(function(){
        $("#logs").append('<table class="table">');
        var isDraggable = true;
        var isResizable = true;
        var $sc = $("#schedule").timeSchedule({
            startTime: "07:00", // schedule start time(HH:ii)
            endTime: "12:00",   // schedule end time(HH:ii)
            widthTime: 60 * 10,  // cell timestamp example 10 minutes
            timeLineY: 60,       // height(px)
            verticalScrollbar: 20,   // scrollbar (px)
            timeLineBorder: 2,   // border(top and bottom)
            bundleMoveWidth: 6,  // width to move all schedules to the right of the clicked time line cell
            draggable: isDraggable,
            resizable: isResizable,
            resizableLeft: true,
            rows : {
                '0' : {
                    title : 'Title Area1',
                    schedule:[
                        {
                            start: '09:00',
                            end: '10:00',
                            text: 'task1',
                            data: {
                            }
                        },
                        {
                            start: '10:00',
                            end: '11:00',
                            text: 'task2',
                            data: {
                            }
                        }
                    ]
                },
                '1' : {
                    title : 'Title Area2',
                    schedule:[
                        {
                            start: '9:00',
                            end: '10:00',
                            text: 'task3',
                            data: {
                            }
                        }
                    ]
                }
            },
             onChange: function(node, data){
                addLog('onChange', data);
            },
            onInitRow: function(node, data){
                addLog('onInitRow', data);
            },
            onClick: function(node, data){
                addLog('onClick', data);
            },
            onAppendRow: function(node, data){
                addLog('onAppendRow', data);
            },
            
            
        });
        
        $('#clear-logs').on('click', function(){
            $('#logs .table').empty();
        });
    });
</script>
@endsection
</body>
</html>
