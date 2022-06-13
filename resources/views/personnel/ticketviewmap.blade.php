@extends('layouts.workerheader')
@section('content')
<style type="text/css">
  .input-container input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: .75rem;
    position: relative;
    width: 100%;
}
.col-lg-3.col-md-12.text-center.mb-2 {
    position: absolute;
    right: 1%;
}
input[type="date"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}
.note-popover.popover {
  display: none;
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
<div class="content">
  <div class="row">
    <div class="col-md-12">
        <div class="side-h3">
          <h3 class="align-items-center d-flex justify-content-between">Ticket Map View 
        </div>
        <p><a href="{{url()->previous()}}" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>
     Back</a></p>
        <div id="map"></div>
    </div>
    <input type="hidden" name="mapid" id="mapid" value="{{$id}}">
  </div>
</div>
  
@endsection

@section('script')
<script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  });
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

  $(document).ready(function() {
      var APP_URL = {!! json_encode(url('/')) !!}
      var mapid = $("#mapid").val();
      $.ajax({
            url:"{{url('personnel/myticket/mapdata')}}",
            data: {
              id: mapid
            },
            method: 'post',
            dataType: 'json',
            refresh: true,
            success:function(data) {
              console.log(data.html);
             console.log("latlong",data);
                          var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 10,
                            center: new google.maps.LatLng(data.html[0][1], data.html[0][2]),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                           
                          });

                          var infowindow = new google.maps.InfoWindow();

                          var marker, i;

                          for (i = 0; i < data.html.length; i++) {  
                            marker = new google.maps.Marker({
                              position: new google.maps.LatLng(data.html[i][1], data.html[i][2]),
                              map: map,
                              icon: {
                                 url: APP_URL+'/uploads/customer/'+data.html[i][4],
                                 size: new google.maps.Size(36, 36),
                                 scaledSize: new google.maps.Size(36, 36),
                                 anchor: new google.maps.Point(0, 50),
                                
                              }
                            });
                            
                            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                              return function() {
                                 if(data.html[i][4]!=null){
                                  var imgeurl = APP_URL+'/uploads/customer/'+data.html[i][4];
                                } else {
                                  var imgeurl = APP_URL+'/uploads/servicebolt-noimage.png';
                                }
                              var contentString =
                              '<div class="">' +
                              '<div>' +
                              '<img class="mb-2" src="'+imgeurl+'" alt="" style="width:60px;height:60px;border-radius:100%">' +
                              '&nbsp;&nbsp;<span>'+data.html[i][0]+'</span>' +' <p>Address: '+data.html[i][3]+'</p>' +'<p>Ticket #'+data.html[i][5]+'&nbsp;<span style="font-size:12px;color:black;">Time:  '+data.html[i][6]+' </span>' +
                              "</div>" +
                              "</div>";
                                infowindow.setContent(contentString);
                                //infowindow.setContent(data.html[i][0]);
                                infowindow.open(map, marker);
                              }
                            })(marker, i));
                          }
        }
    })
});
function CustomMarker(latlng, map, imageSrc) {
     this.latlng_ = latlng;
    this.imageSrc = imageSrc;
    // Once the LatLng and text are set, add the overlay to the map.  This will
    // trigger a call to panes_changed which should in turn call draw.
    this.setMap(map);
}
</script>
@endsection