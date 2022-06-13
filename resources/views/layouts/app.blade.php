<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ServiceBolt</title>

    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    
</head>
<body>
   <nav class="navbar navbar-expand-lg fixed-top header-menu pt-5 pb-5">

  <div class="container">
    <a class="navbar-brand" href="#">
    	<img src="{{ asset('images/logo.png')}}" style="width: 210px;height: auto;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
     <a href="{{ route('login') }}"> <button type="button" class="btn btn-outline-warning login">Login</button></a>
        </li>
        <li class="nav-item">
      <a href="{{ route('register') }}">    <button type="button" class="btn btn-outline-warning login">Sign Up</button></a>
        </li>
      </ul>
     
    </div>
  </div>
</nav>

        <div>
            @yield('content')
        </div>
    
<script src="{{ asset('js/jquery.min.js')}}"></script>
<script src="{{ asset('js/popper.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/slick.min.js')}}"></script>

<script src="{{ asset('js/comman.js')}}"></script>
<script type="text/javascript">
 $('.slider').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  arrows: true,
  dots: false,
  centerMode: true,
  variableWidth: true,
  infinite: true,
  focusOnSelect: true,
  cssEase: 'linear',
  touchMove: true,
  prevArrow:'<button class="slick-prev"> < </button>',
  nextArrow:'<button class="slick-next"> > </button>',
  
          responsive: [                        
              {
                breakpoint: 576,
               settings: {
               centerMode: false,
                 variableWidth: false,
               }
              },
        ]
});

$('.nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
     e.target
     e.relatedTarget
     $('.slider').slick('setPosition');
 });

var imgs = $('.slider img');
imgs.each(function(){
  var item = $(this).closest('.item');
  item.css({
    'background-image': 'url(' + $(this).attr('src') + ')', 
    'background-position': 'center',            
    '-webkit-background-size': 'cover',
    'background-size': 'cover', 
  });
  $(this).hide();
});
</script>

    @yield('script')
</body>
</html>