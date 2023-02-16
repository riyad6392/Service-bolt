


</body>
 <script src='js/jquery.min.js'></script>
<script src="js/bootstrap.bundle.min.js"></script>
 
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
   <!-- <script src="js/chart.js"></script> -->
   <script src="js/drop-zone.js"></script>
   <script src='js/jquery-ui.js'></script>
  
<script src='js/slick.min.js'></script>
<script src='js/main.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
   <script src="js/add-field.js"></script> 

   
   
<script>
  $(document).ready(function() {
        $('#multiple-checkboxes').multiselect({
          includeSelectAllOption: true,
        });
    });
$(".confirm").click(function() {
  swal("Successfully address", "Well done, you pressed a button", "success")
});
$(".add-ticket-alert").click(function() {
  swal("Success full", "The ticket has been created", "success")
});

$(document).ready(function () {
        var url = window.location;
		
        $('.sidebar ul li a[href="' + url + '"]').parent().addClass('active');

    // Will also work for relative and absolute hrefs
        $('.sidebar ul li a').filter(function () {
            return this.href == url;
        }).parent().addClass('active').parent().parent().addClass('active');
		
		
		$(".menubar").click(function(){
    $(".sidebar").toggle();
	$("body").toggleClass('leftside-none');
	
  });
		
    });
	
</script>

    <script type="">
    	
function initMap() {
	
	var uk = { 
		lat: 53.990221, 
		lng: -3.911132 
	};
	
	var map = new google.maps.Map( document.getElementById('map'), {
	  zoom: 6,
	  center: uk, 
	  disableDefaultUI: true,
	  styles: [{"elementType":"labels","stylers":[{"visibility":"off"},{"color":"#f49f53"}]},{"featureType":"landscape","stylers":[{"color":"#f9ddc5"},{"lightness":-7}]},{"featureType":"road","stylers":[{"color":"#813033"},{"lightness":43}]},{"featureType":"poi.business","stylers":[{"color":"#645c20"},{"lightness":38}]},{"featureType":"water","stylers":[{"color":"#1994bf"},{"saturation":-69},{"gamma":0.99},{"lightness":43}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#f19f53"},{"weight":1.3},{"visibility":"on"},{"lightness":16}]},{"featureType":"poi.business"},{"featureType":"poi.park","stylers":[{"color":"#645c20"},{"lightness":39}]},{"featureType":"poi.school","stylers":[{"color":"#a95521"},{"lightness":35}]},{},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"color":"#813033"},{"lightness":38},{"visibility":"off"}]},{},{},{},{},{},{},{},{},{},{},{},{"elementType":"labels"},{"featureType":"poi.sports_complex","stylers":[{"color":"#9e5916"},{"lightness":32}]},{},{"featureType":"poi.government","stylers":[{"color":"#9e5916"},{"lightness":46}]},{"featureType":"transit.station","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","stylers":[{"color":"#813033"},{"lightness":22}]},{"featureType":"transit","stylers":[{"lightness":38}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#f19f53"},{"lightness":-10}]},{},{},{}]
	});
		  
	var InfoWindows = new google.maps.InfoWindow({});
	
	airports.forEach(function(airport) {	
		var marker = new google.maps.Marker({
		  position: { lat: airport.position.lat, lng: airport.position.lng },
		  map: map,
		  icon: icons[airport.icon].icon,
		  title: airport.title
		});
		marker.addListener('mouseover', function() {
		  InfoWindows.open(map, this);
		  InfoWindows.setContent(airport.content);
		});
	});
}


    </script>


   

    <script type="">
    	$(".circle_percent").each(function() {
    var $this = $(this),
		$dataV = $this.data("percent"),
		$dataDeg = $dataV * 3.6,
		$round = $this.find(".round_per");
	$round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");	
	$this.append('<div class="circle_inbox"><span class="percent_text"></span></div>');
	$this.prop('Counter', 0).animate({Counter: $dataV},
	{
		duration: 2000, 
		easing: 'swing', 
		step: function (now) {
            $this.find(".percent_text").text(Math.ceil(now)+"%");
        }
    });
	if($dataV >= 51){
		$round.css("transform", "rotate(" + 360 + "deg)");
		setTimeout(function(){
			$this.addClass("percent_more");
		},1000);
		setTimeout(function(){
			$round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
		},1000);
	} 
});
    </script>
</html>