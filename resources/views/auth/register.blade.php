<!DOCTYPE html>
<html class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Favicon -->
<link rel="shortcut icon" href="{{url('/')}}/uploads/serviceboltfavicon.png" />
<link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
  .input-container input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: .75rem;
    position: relative;
    width: 100%;
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
.slick-dots li button:before {
   
   
    color: yellow !important;
   
}
.slick-dots li.slick-active button:before {
    opacity: 1;
    color: yellow !important;
}
.bg {
   
    height: 100%;
}
</style>
    <title></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick-theme.min.css" />

</head>
<body class="h-100">
    <div class="container-fluid p-0 h-100">
  <div class="row g-0 h-100">
    <div class="col-md-6 h-100">
  @php
     $featuredata = App\Models\Feature::select('*')
    ->where('status','Active')->get();
    $cfeaturedata = count($featuredata);

    $price = App\Models\User::select('featureprice')
    ->where('role','superadmin')->first();
  @endphp     
  <div class="bg">
    <img class="sets" src="images/logo.png">  
   <div class="align-content-center d-flex flex-column justify-content-center" style="height: 80%;">
    <h1 class="add">${{$price->featureprice}}</h1>
    <p class="box">one price,all inclusive for your entire team</p>
    <h5 class="few">Features</h5>
  <div class="slider1" >
    @foreach($featuredata as $key =>$value)
    <div>
     <p class="box1">{{$value->description}}</p>
    </div>
    @endforeach
    <!-- <div>
  <p class="box1">Lorem ipsum dolor sit amit, consectetur adipiscing adipiscing edit,<br>sed do eiusmod tempur incididunt ut lobare et dolore<br>magna aliqua.Ut enim ad minim veniam,quis nosturd<br>exercitation ullamco laboris nisi ut aliquip ex ea commodo <br>consequet</p>
    </div>
    <div>
  <p class="box1">Lorem ipsum dolor sit amit, consectetur adipiscing adipiscing edit,<br>sed do eiusmod tempur incididunt ut lobare et dolore<br>magna aliqua.Ut enim ad minim veniam,quis nosturd<br>exercitation ullamco laboris nisi ut aliquip ex ea commodo <br>consequet</p>
    </div> -->
 
</div>
        
<form method="POST" action="{{ route('signupcomplete') }}" class="mt-5">
    @csrf
      <div  class="">
        
        <input type="hidden" name="amount" id="amount" value="99">
        <button type="submit" class="box2">Get Started Now</button>
      </div>
</form>
    </div>
    </div>
    </div>
       <div class="col-md-6 d-flex align-items-center justify-content-center">
      <img  src="{{url('/')}}/images/amountpageimg.png">
    </div>
    </div>
    
  </div>

   
<script src="{{ asset('js/jquery.min.js')}}"></script>

<script src="{{ asset('js/slick.min.js')}}"></script>




<script type="">







  $('.slider1').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  dots: true,
  

  infinite: true,

 

  
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
  </body>
</html>