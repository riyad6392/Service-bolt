@extends('layouts.header')
@section('content')
  <link rel="stylesheet" type="text/css" href="https://fullcalendar.io/releases/fullcalendar/3.10.0/fullcalendar.min.css">
   <link rel="stylesheet" type="text/css" href="https://fullcalendar.io/releases/fullcalendar-scheduler/1.9.4/scheduler.min.css">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">

<style type="">
    .fc-time-grid-event .fc-content {
        overflow:visible;
    }
@media screen and (min-width: 1600px) {
  .fixed {
    width: 80%!important;
  }
}

@media screen and (min-width: 1200px) {
  .fixed {
    width: 80%!important;
  }
}

     #header {
  background: #fff;
  padding: 3px;
  padding: 10px 20px;
  color: #fff;
}
.fc-content .text-start {
    position: absolute;
}
.datess {
    display: flex;
    align-items: center;
}
.datess span {
    width: 196px;
    color: #000;
    font-size: 20px;
}
.fc-time-grid .fc-slats td {
  text-transform: uppercase;
}
/*.fc-scroller.fc-time-grid-container {
    height: 500px!important;
}*/

.fc-highlight {
    background: #87CEEB!important;
    opacity: 1!important;
    filter: alpha(opacity=100)!important;
}

.fixed {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 9;
}   

.new-filters {
    position: absolute;
    right: 0;
}
.new-filters i.fa.fa-times {
    font-size: 20px!important;
    position: relative;
    bottom: 0px;
    right: 12px;
}
a.fc-time-grid-event.fc-v-event.fc-event.fc-start.fc-end.fc-draggable.fc-resizable.intro {
    z-index: 9999999!important;
}

/* width */
#calendar::-webkit-scrollbar {
  width: 1px;
}
.padeings {
    padding: 0 25px!important;
}
/* Track */
#calendar::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
#calendar::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
#calendar::-webkit-scrollbar-thumb:hover {
  background: #555; 
} 


/* width */
body::-webkit-scrollbar {
  width: 1px;
}

/* Track */
body::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
body::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
body::-webkit-scrollbar-thumb:hover {
  background: #555; 
} 

.slider ul {
    position: relative;
    width: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    list-style: none;
}

#external-events {
    z-index: 2;
    top: 0;
    left: 20px;
    width: auto!important;
    padding: 0 10px;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    justify-content: space-around;
    background: transparent;
}
  .column .sticky {
  position: -webkit-sticky;
  position: sticky;
  top: 15px;
}
.modal-content {
    border-radius: 15px;
    padding: 13px!important;
}
.modal-dialog {
    max-width: 560px!important;
  }


.card {
  height: auto;
  border-radius: 0!important;
}
/*.fixed-top {
    position: fixed;
    top: 65px;
    z-index: 9;
    width: 78%;
    left: auto;
    right: auto;

}*/

.inner p {
    color: #fff!important;
}
.heierts {
  height: 300px!important;
}
a.next.control {
    transform: rotate(180deg);
}
a.prev.control {
    transform: rotate(180deg);
}
/*.modal-body {
    height: 600px;
    overflow-x: hidden;
    overflow-y: scroll;
}*/
.add-customer-modal h5 {
    font-size: 32px;
    font-weight: 500;
}
.fc.fc-unthemed.fc-ltr.new {
    margin: 6px 0 0!important;
    background: #fff;
}
.fc-unthemed .fc-content, .fc-unthemed .fc-divider, .fc-unthemed .fc-list-heading td, .fc-unthemed .fc-list-view, .fc-unthemed .fc-popover, .fc-unthemed .fc-row, .fc-unthemed tbody, .fc-unthemed td, .fc-unthemed th, .fc-unthemed thead {
    font-size: 15px!important;
    }
.use.new {
    top: 43%!important;
}

.card.fixed-top.new {
    width: 100%!important;
    left: 0;
    top: 0;
}
.customer-form .form-select {
    border: 1px solid #F3F3F3;
    box-sizing: border-box;
    border-radius: 15px;
    height: 50px;
    outline: none;
}
.contents {
    overflow: hidden!important;
    position: fixed;
    width: 100%;
}
textarea.form-control.height-180 {
    height: 180px;
    resize: none;
    color: #212529;
}
.modal-header {
    padding-bottom: 0;
    border-bottom: none!important;
}
.customer-form .form-control {
    border: 1px solid #F3F3F3;
    box-sizing: border-box;
    border-radius: 15px;
    height: 50px;
    outline: none;
}
.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: 100%!important;
}

.timepicker {
    border: 1px solid rgb(163, 175, 251);
    text-align: center;
    display: inline;
    border-radius: 4px;
          padding:2px;
}

.timepicker .hh, .timepicker .mm {
    width: 44%;
    outline: none;
    border: none;
    text-align: center;
}

.timepicker.valid {
    border: solid 1px springgreen;
}

.timepicker.invalid {
    border: solid 1px red;
}

.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
    color: #999;
    padding: 12px;
    font-size: 16px;
}
div#bs-select-1 {
    width: 100%;
    text-align: left!important;

}
.blues {
    padding: 0!important;
}
.fc-time-grid-event .fc-time {
  text-align: center;
}
.fc-time-grid-event .fc-time, .fc-time-grid-event .fc-title {
  text-align: center;
}
.counter {
    font-size: 16px;
    background: red;
    padding: 6px 7px;
    color: #fff;
    border-radius: 30px;
    position: relative;
    bottom: 3px;
}
.side-h3 {
    padding: 0px 24px 8px!important;
}
/*.fc-scroller {
   overflow-y: hidden !important;
}*/
.tickets_div p {
    font-size: 15px;
    margin: 0;
}
.tickets_div h6 {
    font-size: 15px;
    margin: 0;
}
span.closeon i {
    color: red;
    background: #fff;
    padding: 4px 5px;
    border-radius: 24px;
    font-size: 15px;
    box-shadow: 0px 0px #ccc;
        margin-top: 25px;
            position: absolute;
    left: 0;

}
.fc-content .fa.fa-edit {
    background: #fff;
    color: blue;
    padding: 4px;
    border-radius: 17px;
    font-size: 14px;
}
.tickets_div {
    text-align: center;
    padding: 4px 0;
}

.use {
    position: absolute;
    top: 68%;
    display: flex;
    justify-content: space-between;
    width: 100%;
    z-index: 0;
    padding: 0 10px;
}

.card-body {
    padding: 14px 25px 14px 25px;
}
.fc-event {
    border: none!important;
    padding: 2px;
    height: auto;
    width: auto;
    border-radius: 12px;
    z-index: 99;
}
.fc-time-grid .fc-bgevent, .fc-time-grid .fc-event {
    border-radius: 12px!important;
 
    padding: 4px!important;
}
th.fc-resource-cell {
   
    padding: 0px;
    text-transform: capitalize;
    text-align: left;
}


.fc-icon-left-single-arrow:after {
top: -52%!important;
}
.fc-icon-right-single-arrow:after {

top: -52%!important;
}

button.fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right {
    display: none!important;
}
button.fc-month-button.fc-button.fc-state-default.fc-corner-left {
    display: none!important;
}
button.fc-agendaWeek-button.fc-button.fc-state-default {
    display: none!important;
}
button.fc-agendaDay-button.fc-button.fc-state-default.fc-corner-right.fc-state-active {
    display: none!important;
}
.slider ul li {
width: 188px;
}
.fc .fc-toolbar.fc-header-toolbar {
    margin-bottom: 1.5em;
    justify-content: end!important;
}

.adses {
    height: 470px!important;
}
#external-events .fc-event {
  margin: 1em 0;
  cursor: move;
}

#calendar-container {
  position: relative;
  z-index: 1;
  margin-left: 200px;
}

#calendar {
    margin: 12px 0 0 auto;
    padding: 6px 2px;
    border-radius: 4px;
    background-color: #fff;
}
.fc {
    display: flex;
    flex-direction: inherit!important;
    }
/*.fc-time-grid .fc-slats td {
  background: #fff;

}*/


th.fc-resource-cell img {
    width: 30px;
    padding: 8px;
    border-radius: 100%;
}
.fc-unthemed td.fc-today {
    background: #f7f9fa;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="col-lg-12 mb-4">
            <div class="col-md-12">
                <div class="card position-sticky">
                    <div class="card-body">
                        <div class="row mb-0">
                            <div class="col-md-6">
                                <div class="side-h3">
                                    <h3 style="font-weight: 500;color: #000;">Scheduler & Dispatch 
                                        <!-- <span class="counter">10</span> -->
                                    </h3>
                                    <p style="margin: 0; font-weight: 500;color: #000;">Tickets to Assign</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="datess">
                                    <span></span>
                                    <input type="text" placeholder="26/05/2022"
                                            onfocus="(this.type='date')" class="form-control">
                                           <!--  <input type="hidden" name="dateval" id="dateval" value="{{ date('l - F d, Y') }}">{{ date('l - F d, Y') }}</span> -->
                                </div>
                            </div>
                            <div class="col-lg-4 mb-0" id="srcoll-here">
                                <div class="show-fillter">
                                    <button class="menubar" id="hide-top" style="    background: #FEE200;color: #000;border: none;padding: 10px 20px;border-radius: 15px;">Full Screen</button>
                                    <span id="close"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 text-end mb-2 offset-lg-4">
                                <a href="#" class="add-btn-yellow">New Ticket Assign + </a>
                            </div>
                        </div>
                        <div class="card-personal" id="external-events">
                            <div class="gallery portfolio_slider  slider">
                                @if(count($ticketData)>0)  
                                <ul id="sortable1" class="connectedSortable" style="width:50000px">
                                    @foreach($ticketData as $key => $value)
                                       @php
                                          $servicecolor = App\Models\Service::select('color')
                                            ->where('services.servicename',$value->servicename)->get()->first();
                                        @endphp
                                        <li class="inner red-slide">
                                            <div class='fc-event' style="background-color: {{$servicecolor->color}};" data-color='{{$servicecolor->color}}' id='{{$value->id}}' data-bs-toggle='modal' data-bs-target='#exampleModal' > 
                                                <div class="tickets_div">
                                                    <h6>#{{$value->id}}</h6>
                                                    <p> {{$value->customername}}</p>
                                                    <p>{{$value->servicename}}</p>
                                                </div>
                                            </div>
                                        </li>
                                   @endforeach 
                                </ul>
                                @else
                                    <div style="text-align: center;">
                                        Not found any schedule ticket
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(count($ticketData)>0)
                        <div class="use">
                            <a href="#0" class="prev control">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                                </svg>
                            </a>
                            <a href="#0" class="next control">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <div id='calendar' style="position: relative;">
                    <input type="hidden" id="suggest_trip_start" value="6">
                <i class="fa fa-chevron-left" style="  cursor: pointer;  font-size: 24px;position: absolute;top: 18px;left: 6px; z-index: 9;"></i>
                <i class="fa fa-chevron-right" id="suggest_trip_next" style="cursor: pointer;  font-size: 24px;position: absolute;top: 18px; left: 30px; z-index: 9;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
      <form method="post" action="{{ route('company.sticketupdate') }}" enctype="multipart/form-data">
        @csrf
        <div id="sviewmodaldata"></div>
      </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/lib/jquery.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/lib/moment.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/fullcalendar.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar-scheduler/1.9.4/scheduler.min.js"></script>
<script  src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
<script async defer src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

<script type="">
      $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
    $(".menubar").click(function() {
        setTimeout(function() {
            $("div").removeClass("heierts");
        },)
        $(".fc-scroller.fc-time-grid-container").addClass("adses");
        $(".datess").addClass("d-none");
        $(".col-lg-4").toggleClass("col-lg-12 text-end");
        $(".card-body").toggleClass("padeings");
        $(".show-fillter").toggleClass("new-filters");
        $(".content").toggleClass("contents");
        $(".fixed-top").toggleClass("new");
        $(".card.fixed").toggleClass("new-1");
        $(".add-btn-yellow").toggleClass("d-none");
        $(".side-h3").toggleClass("d-none");
        $(".use").toggleClass("new");
        $(".fc.fc-unthemed.fc-ltr").toggleClass("new");
    });
</script>

 <script>
    $( function() {
        $( "#sortable1, #sortable2" ).sortable({
          connectWith: ".connectedSortable"
        }).disableSelection();
    });
  
    $(function() {
        var slideCount =  $(".slider ul li").length;
        var slideWidth =  $(".slider ul li").width();
        var slideHeight =  $(".slider ul li").height();
        var slideUlWidth =  slideCount * slideWidth;
        $(".slider ul li:last-child").prependTo($(".slider ul"));
        
        function moveLeft() {
            $(".slider ul").stop().animate({
              left: + slideWidth
            },700, function() {
              $(".slider ul li:last-child").prependTo($(".slider ul"));
              $(".slider ul").css("left","");
            });
        }
        function moveRight() {
            $(".slider ul").stop().animate({
              left: - slideWidth
            },700, function() {
              $(".slider ul li:first-child").appendTo($(".slider ul"));
              $(".slider ul").css("left","");
            });
        }
        $(".next").on("click",function() {
            moveRight();
        });
        $(".prev").on("click",function() {
            moveLeft();
        });
    });
  </script>

<script type="">
    $(document).ready(function () {
        $('html, body').animate({
            scrollTop: $('#srcoll-here').offset().top
        }, 'slow');
       
        setTimeout(function() {
            $(".fc-scroller").addClass("heierts");
        },1000);
        
    });
    
    $(document).on("click",'.fc-time-grid-event',function() {
        $(".fc-time-grid-event").removeClass('intro');
        $(this).addClass('intro');
    });
    $("#hide-top").click(function() {
        $("#hide-top").hide();
        $("#close").html('<a href="" style="position: relative;left: 0;top:0px;color: black;"> <i class="fa fa-times" aria-hidden="true" style="font-size: 32px;"></i></a>');
        $(".top-bar").toggle();
        $(".content-page").toggleClass("blues");
    });
</script>
<script type="">
    $(function() {
        $('#external-events .fc-event').each(function() {
            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true, // maintain when user navigates (see docs on the renderEvent method)
                color: $(this).data('color')
            });
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 9999999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
        
        $('#calendar').fullCalendar({
            header: {
                left: '',
                center: '', 
                right: ''
            },
            // defaultTimedEventDuration: '01:00:00',
            minTime: "00:00:00",
            maxTime: "24:00:00",  
            allDaySlot: false,
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            defaultView: 'agendaDay',
            groupByResource: true,
            eventOverlap: true,
            resources: function (callback) {
                $.ajax({
                url:"{{url('company/scheduler/getworker')}}",
                method: 'GET',
                dataType: 'json',
                refresh: true,
                    }).done(function(response) {
                    //console.log(response.resources);
        
                  callback(response.resources); //return resource data to the calendar via the provided callback function
                });
            },

            events: '{{route("company.getschedulerdata")}}',

            eventRender: function(event, element, view) {
                if (view.name == 'listDay') {
                    element.find(".fc-list-item-time").append("<div class='text-end'><span class='closeon'><i class='fa fa-trash' > </i></span></div>");
                } else {
                    element.find(".fc-content").prepend("<div class='text-end'><span class='closeon'><i class='fa fa-trash' > </i></span></div>");
                } 
                element.find(".closeon").on('click', function() {
                  var id = event.id;
                  $.ajax({
                   url:"{{url('company/scheduler/deleteTicket')}}",
                   type:"POST",
                   data:{id:id},
                   success:function()
                   {
                    $('#calendar').fullCalendar('removeEvents',event._id);
                    swal({
                       title: "Done!", 
                       text: "Ticket Removed Successfully!", 
                       type: "success"
                    },
                    function(){ 
                           location.reload();
                        }
                    );
                   }
                  })
                    //$('#calendar').fullCalendar('removeEvents',event._id);
                });
                
                if (view.name == 'listDay') {
                    element.find(".fc-list-item-time").append("<div class='text-start'><span class='fa fa-edit' data-bs-toggle='modal' data-bs-target='#exampleModal'></span></div>");
                } else {
                    element.find(".fc-content").prepend("<div class='text-start'><span class='fa fa-edit' data-bs-toggle='modal' data-bs-target='#exampleModal' id='editsticket' data-id='"+event.id+"'></span></div>");
                } 

                element.find(".fa-edit").on('click', function() {
                    //console.log(event.id);
                    $('#calendar').fullCalendar('editEvents',event._id);
                    console.log('edit');
                });
            },
            resourceRender: function (dataTds, eventTd) {
                var datatitle = dataTds.title;
                var title = datatitle.split("#");
                
                if(title[0]!=null) {
                    var url = "{{url('uploads/personnel/')}}/"+title[1];
                } else {
                    var url = "{{url('uploads/servicebolt-noimage.png')}}";
                }
                var textElement = eventTd.empty();
                textElement.append('<b><img src="'+ url +'" alt=""/>' + title[0] + '</b>');
            },
            unselectAuto: false,
            selectable: true,
            selectHelper: false,
            editable: true,
            droppable: true,
            dayClick: function (date) {
                //alert('clicked ' + date.format());
            },
            select: function (startDate, endDate) {
                //alert('selected ' + startDate.format() + ' to ' + endDate.format());
            },

            eventClick: function (info) {
                console.log(info);
            },
            // drop: function (date, allDay, jsEvent, ui) {
            drop: function (date, jsEvent, ui, resourceId) {

                var hours = date._i[3];
                var minutes = date._i[4];
                const ampm = hours >= 12 ? 'pm' : 'am';

                hours %= 12;
                hours = hours || 12;    
                hours = hours < 10 ? `0${hours}` : hours;
                minutes = minutes < 10 ? `0${minutes}` : minutes;

                var ticketid = $(this).attr('id');
                var workerid = resourceId;
                var giventime = `${hours}:${minutes} ${ampm}`;
                
                var dd = date._d;
                var date = moment(new Date(dd.toString().substr(0, 16)));
                var fulldate = date.format("dddd - MMMM DD, YYYY");

                form_data = new FormData();
                form_data.append('quoteid',ticketid);
                form_data.append('time',giventime);
                form_data.append('date',fulldate);
                form_data.append('workerid',workerid);
                $.ajax({
                    url:"{{url('company/scheduler/sortdata')}}",
                    method:"POST",
                    data:form_data,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success:function() {
                        swal({
                           title: "Done!", 
                           text: "Ticket Assigned Successfully!", 
                           type: "success"
                        },
                        function(){ 
                               location.reload();
                            }
                        );
                    }
                });
            },
            eventDrop: function(event,delta, revertFunc, jsEvent, ui, view ) {
                //console.log(event.start._d);
                var ticketid = event.id;
                var resourceId = event.resourceId;
                
                if(event.start._i[0].length==1) {
                    var datetime = event.start._i;
                    
                    var datetime = datetime.split(" ");
                    var time = datetime[1];
                    var timedata = time.split(":");
                    var hours = timedata[0];
                    var minutes = timedata[1];
                    const ampm = hours >= 12 ? 'pm' : 'am';

                    hours %= 12;
                    hours = hours || 12;    
                    hours = hours < 10 ? `0${hours}` : hours;
                    minutes = minutes < 10 ? `0${minutes}` : minutes;

                    var ticketid = ticketid;
                    var workerid = resourceId;
                    var giventime = `${hours}:${minutes} ${ampm}`

                    var dd = event.start._d;
                    var date = moment(new Date(dd.toString().substr(0, 16)));
                    var fulldate = date.format("dddd - MMMM DD, YYYY");
                } else {
                    var hours = event.start._i[3];
                    var minutes = event.start._i[4];
                    const ampm = hours >= 12 ? 'pm' : 'am';

                    hours %= 12;
                    hours = hours || 12;    
                    hours = hours < 10 ? `0${hours}` : hours;
                    minutes = minutes < 10 ? `0${minutes}` : minutes;

                    var ticketid = ticketid;
                    var workerid = resourceId;
                    var giventime = `${hours}:${minutes} ${ampm}`;

                    var dd = event.start._d;
                    var date = moment(new Date(dd.toString().substr(0, 16)));
                    var fulldate = date.format("dddd - MMMM DD, YYYY");
                }

                form_data = new FormData();
                form_data.append('quoteid',ticketid);
                form_data.append('time',giventime);
                form_data.append('date',fulldate);
                form_data.append('workerid',workerid);
                $.ajax({
                    url:"{{url('company/scheduler/sortdata')}}",
                    method:"POST",
                    data:form_data,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success:function() {
                        swal({
                           title: "Done!", 
                           text: "Ticket Assigned Successfully!", 
                           type: "success"
                        },
                        function(){ 
                               location.reload();
                            }
                        );
                    }
                });
            },
        });
    });

    function getworker() {
        var res="";
        $.ajax({
            url:"{{url('company/scheduler/getworker')}}",
            method: 'GET',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              
               res =data.resources;

              //return res;
              //$('#viewleftservicemodal').html(data.html);
            }
        });

        console.log(res);

        /*var res=[{id: '0', title: '123'},
        {id: '1', title: 'Servic...'},
        {id: '2', title: 'Servic...'},
        {id: '3', title: 'Servic...'},
        {id: '4', title: 'Servic...'},
        {id: '5', title: 'Servic...'},
        {id: '6', title: 'Servic...'}]*/
        return res;
    }

    $(document).on('click','#editsticket',function(e) {
         $('.selectpicker').selectpicker();
        var id = $(this).data('id');
        var dataString =  'id='+ id;
        $.ajax({
            url:'{{route('company.vieweditschedulermodal')}}',
            data: dataString,
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
              $('#sviewmodaldata').html(data.html);
              $('.selectpicker').selectpicker({
                size: 5
              });
            }
        })
    });
</script>

<script type="">
    $(function() {
        createSticky($("#header"));
    });

    function createSticky(sticky) {
        if (typeof sticky !== "undefined") {
            var pos = sticky.offset().top + 20,
            win = $(window);
            win.on("scroll", function() {
                win.scrollTop() >= pos ? sticky.addClass("fixed") : sticky.removeClass("fixed");
            });     
        }
    }
</script>
@endsection