


</body>
 <script src='js/jquery.min.js'></script>
<script src="js/bootstrap.bundle.min.js"></script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
   <script src="js/chart.js"></script>
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



<script >
	var canvas = document.getElementById("canvas");
var tooltipCanvas = document.getElementById("tooltip-canvas");

var gradientBlue = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
gradientBlue.addColorStop(0, '#5555FF');
gradientBlue.addColorStop(1, '#9787FF');

var gradientRed = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
gradientRed.addColorStop(0, '#FF55B8');
gradientRed.addColorStop(1, '#FF8787');

var gradientGrey = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
gradientGrey.addColorStop(0, '#888888');
gradientGrey.addColorStop(1, '#AAAAAA');



window.arcSpacing = 0.15;
window.segmentHovered = false;

function textInCenter(value, label) {
  var ctx = tooltipCanvas.getContext('2d');
  ctx.clearRect(0, 0, tooltipCanvas.width, tooltipCanvas.height)
  
	ctx.restore();
    
  // Draw value
  ctx.fillStyle = '#333333';
  ctx.font = '24px sans-serif';
  ctx.textBaseline = 'middle';

  // Define text position
  var textPosition = {
    x: Math.round((tooltipCanvas.width - ctx.measureText(value).width) / 2),
    y: tooltipCanvas.height / 2,
  };

  ctx.fillText(value, textPosition.x, textPosition.y);

  // Draw label
  ctx.fillStyle = '#AAAAAA';
  ctx.font = '8px sans-serif';

  // Define text position
  var labelTextPosition = {
    x: Math.round((tooltipCanvas.width - ctx.measureText(label).width) / 2),
    y: tooltipCanvas.height / 2,
  };

  ctx.fillText(label, labelTextPosition.x, labelTextPosition.y - 20);
  ctx.save();
}

Chart.elements.Arc.prototype.draw = function() {
  var ctx = this._chart.ctx;
  var vm = this._view;
  var sA = vm.startAngle;
  var eA = vm.endAngle;

  ctx.beginPath();
  ctx.arc(vm.x, vm.y, vm.outerRadius, sA + window.arcSpacing, eA - window.arcSpacing);
  ctx.strokeStyle = vm.backgroundColor;
  ctx.lineWidth = vm.borderWidth;
  ctx.lineCap = 'round';
  ctx.stroke();
  ctx.closePath();
};

var config = {
    type: 'doughnut',
    data: {
        labels: ['Pink', 'Grey', 'Blue' , 'green'],
        datasets: [
          {
              data: [400, 540, 290, 200],
              backgroundColor: [
              	gradientRed,
                gradientGrey,
                gradientBlue,
                gradientBlue,
              ],
          }
        ]
    },
    options: {
    		cutoutPercentage: 80,
    		elements: {
        	arc: {
          	borderWidth: 12,
          },
        },
        legend: {
        	display: false,
        },
        animation: {
        	onComplete: function(animation) {
          	if (!window.segmentHovered) {
              var value = this.config.data.datasets[0].data.reduce(function(a, b) { 
                return a + b;
              }, 0);
              var label = 'T O T A L';

              textInCenter(value, label);
            }
          },
        },
        tooltips: {
        	enabled: false,
        	custom: function(tooltip) {
          	if (tooltip.body) {
              var line = tooltip.body[0].lines[0],
              	parts = line.split(': ');
              textInCenter(parts[1], parts[0].split('').join(' ').toUpperCase());
              window.segmentHovered = true;
            } else {
            	window.segmentHovered = false;
            }
          },
        },
    },
};

window.chart = new Chart(canvas, config);

function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(data);
    });
    chart.update();
}

</script>


    <script type="">
    	
var icons = { parking: { icon: 'https://tarantelleromane.files.wordpress.com/2016/10/map-marker.png?w=50' } };


// REPLACE WITH DATA FROM API
//TITLE | POSITION - LAT , LNG | ICON | TITLE | CONTENT
var airports = [
	{ 
		title: 'Manchester Airport', 
		position: { 
			lat: 53.3588026, 
			lng: -2.274919 }, 
		icon: 'parking',	
		content: '<div id="content"><div><img src="images/Ellipse-1.png"/>&nbsp;<span>Jason Smith</span><p style="margin:0px;font-size:12px;color:#B0B7C3;">Ticket # 2 -<span style="font-size:12px;color:black;"> 2:00 - 4:00</span></p></div>'
	},
	{ 
		title: 'Leeds Airport', 
		position: { 
			lat: 53.8679434, 
			lng: -1.6637193 }, 
		icon: 'parking',	
		content: '<div id="content"><div><img src="images/Ellipse-1.png"/>&nbsp;<span>Jason Smith</span><p style="margin:0px;font-size:12px;color:#B0B7C3;">Ticket # 2 -<span style="font-size:12px;color:black;"> 2:00 - 4:00</span></p></div>'
	},
	{ 
		title: 'Belfast Airport', 
		position: { 
			lat: 54.661781, 
			lng: -6.2184331 }, 
		icon: 'parking',	
		content: '<div id="content"><div><img src="images/Ellipse-1.png"/>&nbsp;<span>Jason Smith</span><p style="margin:0px;font-size:12px;color:#B0B7C3;">Ticket # 2 -<span style="font-size:12px;color:black;"> 2:00 - 4:00</span></p></div>'
	},
	{ 
		title: 'Edinburgh Airport', 
		position: { 
			lat: 55.950785, 
			lng: -3.3636419 }, 
		icon: 'parking',	
		content: '<div id="content"><div><img src="images/Ellipse-1.png"/>&nbsp;<span>Jason Smith</span><p style="margin:0px;font-size:12px;color:#B0B7C3;">Ticket # 2 -<span style="font-size:12px;color:black;"> 2:00 - 4:00</span></p></div>'
	},
	{ 
		title: 'Cardiff Airport', 
		position: { 
			lat: 51.3985498, 
			lng: -3.3416461 }, 
		icon: 'parking',	
		content: '<div id="content"><div><img src="images/Ellipse-1.png"/>&nbsp;<span>Jason Smith</span><p style="margin:0px;font-size:12px;color:#B0B7C3;">Ticket # 2 -<span style="font-size:12px;color:black;"> 2:00 - 4:00</span></p></div>'
	},
	{ 
		title: 'Heathrow Airport', 
		position: { 
			lat: 51.4700223, 
			lng: -0.4542955 }, 
		icon: 'parking',	
	content: '<div id="content"><div><img src="images/Ellipse-1.png"/>&nbsp;<span>Jason Smith</span><p style="margin:0px;font-size:12px;color:#B0B7C3;">Ticket # 2 -<span style="font-size:12px;color:black;"> 2:00 - 4:00</span></p></div>'}
];

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