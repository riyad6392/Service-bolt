@extends('layouts.app')
@section('content')
<div class="banner">
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="div-flex">
                <h1 class="text-white text mb-5">Lorem <b>ipsum dolor sit amet</b>, consectetur adipiscing <b>elit</b></h1>
                <p class="text-white mb-5">Pulvinar neque laoreet suspendisse interdum consectetur libero id. Cursus vitae congue mauris rhoncus aenean vel elit scelerisque mauris.</p>
                <div>
                    <a href="{{ route('login') }}"><button  type="button" class="btn btn-outline-warning">Get Started</button></a>
                    <button  type="button" class="btn btn-outline-warning">How IT Works?</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<section class="pt-100 pb-5" style="background-image: url(images/bg.png);
    background-repeat: no-repeat; background-size: contain; background-position: inherit;">
    <div class="container">
        <div class="row">
            <div class="col-md-5 offset-lg-1">
                <div class="col-lg-10">
                    <h6 style="color: #B0B7C3;">PRODUCT FEATURE</h6>
                    <h1 class="text-h1 mb-5">Lorem ipsum dolor sit amet</h1>
                </div>
                <div class="flex mb-3">
                    <img src="images/g1.png" class="flex-img">
                    <p style="color: #6B6B66;">Pulvinar neque laoreet suspendisse interdum consectetur libero id. Cursus vitae congue mauris rhoncus aenean vel elit scelerisque mauris. </p>
                </div>
                <div class="flex mb-3">
                    <img src="images/g2.png" class="flex-img">
                    <p style="color: #6B6B66;">Pulvinar neque laoreet suspendisse interdum consectetur libero id. Cursus vitae congue mauris rhoncus aenean vel elit scelerisque mauris. </p>
                </div>
                <div class="flex">
                    <img src="images/g3.png" class="flex-img">
                    <p style="color: #6B6B66;">Pulvinar neque laoreet suspendisse interdum consectetur libero id. Cursus vitae congue mauris rhoncus aenean vel elit scelerisque mauris. </p>
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <img src="images/Group233.png" class="Img-fix">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-5 pb-5" style="background-image: url(images/back.png);" >
    <div class="line-img">
    <div class="container">
        
        <div class="wrap">  
        
<ul class="nav tabs-home" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Tab 1</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Tab 2</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Tab 3</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Tab 4</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Tab 5</a>
    </li>
</ul><!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" id="tabs-1" role="tabpanel">
         <div class="slider">
    
    <div class="item">
      <img src="images/r5.png" alt="">
    </div>
    <div class="item">
      <img src="images/r4.png" alt="">
    </div>
    <div class="item">
      <img src="images/r5.png" alt="">
    </div>
    <div class="item">
      <img src="images/r4.png" alt="">
    </div>
    <div class="item">
      <img src="images/video-1.png" alt="">
      <div class="play-icon-modal">
      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
      </div>
    </div>
    
  </div>
    </div>
    <div class="tab-pane" id="tabs-2" role="tabpanel">
        <div class="slider">
    
    <div class="item">
      <img src="images/r5.png" alt="">
    </div>
    <div class="item">
      <img src="images/r4.png" alt="">
    </div>
    <div class="item">
      <img src="images/r5.png" alt="">
    </div>
    <div class="item">
      <img src="images/r4.png" alt="">
    </div>
    <div class="item">
      <img src="images/video-1.png" alt="">
      <div class="play-icon-modal">
      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
      </div>
    </div>
    
  </div>
    </div>
    <div class="tab-pane" id="tabs-3" role="tabpanel">
        <div class="slider">
    
    <div class="item">
      <img src="images/r5.png" alt="">
    </div>
    <div class="item">
      <img src="images/r4.png" alt="">
    </div>
    <div class="item">
      <img src="images/r5.png" alt="">
    </div>
    <div class="item">
      <img src="images/r4.png" alt="">
    </div>
    <div class="item">
      <img src="images/video-1.png" alt="">
      <div class="play-icon-modal">
      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
      </div>
    </div>
    
  </div>
    </div>
</div>
        

         
<div class="pb-5 text-center pulvinar">         
         
    <div class="col-lg-6 mx-auto">
    <p>Pulvinar neque laoreet suspendisse interdum consectetur libero id. Cursus vitae congue mauris rhoncus aenean vel elit scelerisque mauris. Diam vel quam elementum pulvinar etiam non quam lacus suspendisse. </p>
     <a href="{{ route('login') }}"><button type="button" class="btn btn-outline-warning login mt-4 btn-started" style="width: 180px;">Get Started</button></a>
</div>  
</div>      
        

  
</div>

    </div>
</div>
</section>
<section>
    <div style="background-image: url(images/Rectangle.png);height: 350px;background-size: contain;width: 100%;">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="get-start">
          <h1 class="get">Get Started Today</h1>
        </div>
            </div>
            <div class="col-md-5">
                <div class="text-p">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nisl purus in mollis nunc.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="btnss">
                     <a href="{{ route('register') }}"><button type="button" class="btn btn-outline-warning login btn-started">Sign Up</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>


<section>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-p">
                        <img src="images/logo.png" style="width: 230px;height: auto;">
                        <p style="color: #81878F;font-size: 12px;  margin: 12px 0px;">2021 Service Bolt - All right reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection