@extends('layouts.app')
@section('content')
<div class="banner">
<div class="container">
    <div class="row">
        <div class="col-md-5">
            @php
                $topData = App\Models\HomePageContent::where('title','Top Section Content')->first();
            @endphp
            <div class="div-flex">
                <h1 class="text-white text mb-5">
                    @if($topData->title1!="")
                    <b>{{$topData->title1}}</b>
                    @endif
                </h1>
                <p class="text-white mb-5">{{$topData->content}}</p>
                <div>
                    <a href="{{ route('login') }}"><button  type="button" class="btn btn-outline-warning">Get Started</button></a>
                    <button  type="button" class="btn btn-outline-warning">How IT Works?</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@php
    $featureData = App\Models\ProductFeature::where('status','Active')->get();
    $pimage = App\Models\User::where('role','superadmin')->first();
    if($pimage->feature_img!=null) {
        $imagepath = url('/').'/uploads/featureimg/thumbnail/'.$pimage->feature_img;
     } else {
      $imagepath = url('/').'/uploads/servicebolt-noimage.png';
    }
@endphp
<section class="pt-100 pb-5" style="background-image: url(images/bg.png);
    background-repeat: no-repeat; background-size: contain; background-position: inherit;">
    <div class="container">
        <div class="row">
            <div class="col-md-5 offset-lg-1">
                <div class="col-lg-10">
                    @php
                        $feaurecontent = App\Models\HomePageContent::where('title','Product Feature Manage')->first();
                    @endphp
                    @if($feaurecontent->title1!="")
                        <h6 style="color: #B0B7C3;">{{$feaurecontent->title1}}</h6>
                    @endif
                    <h1 class="text-h1 mb-5">{{$feaurecontent->content}}</h1>
                </div>
                @foreach($featureData as $key =>$value)
                    <div class="flex mb-3" style="justify-content:start;">
                        <img src="{{url('/')}}/uploads/productchecklist/thumbnail/{{$value->image}}" class="flex-img">
                        <p style="color: #6B6B66;">{{$value->productfeature}} </p>
                    </div>
                @endforeach
            </div>
            <div class="col-md-6">
                <div>
                    <img src="{{$imagepath}}" class="Img-fix">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-5 pb-5" style="background-image: url(images/back.png);" >
    <div class="line-img">
    <div class="container">
        
        <div class="wrap">  
    @php
        $tabImage = App\Models\User::select('tab1','tab2','tab3','tab4','tab5','tab1title','tab2title','tab3title','tab4title','tab5title')->where('role','superadmin')->first();
    @endphp    
<ul class="nav tabs-home" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">{{$tabImage->tab1title}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">{{$tabImage->tab2title}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">{{$tabImage->tab3title}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">{{$tabImage->tab4title}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-5" role="tab">{{$tabImage->tab5title}}</a>
    </li>
</ul><!-- Tab panes -->

<div class="tab-content">
    <div class="tab-pane active" id="tabs-1" role="tabpanel">
        <div class="slider">
            @if($tabImage->tab1!=null)
                @php
                    $tab1path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab1;
                @endphp
                <div class="item">
                    <img src="{{$tab1path}}" alt="">
                </div>
            @endif
            

             @if($tabImage->tab2!=null)
                @php
                    $tab2path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab2;
                @endphp
                <div class="item">
                    <img src="{{$tab2path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab4!=null)
                @php
                    $tab4path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab4;
                @endphp
                <div class="item">
                    <img src="{{$tab4path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab5!=null)
                @php
                    $tab5path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab5;
                @endphp
                <div class="item">
                    <img src="{{$tab5path}}" alt="">
                </div>
            @endif
             @if($tabImage->tab3!=null)
                @php
                    $tab3path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab3;
                @endphp
                <div class="item">
                  <img src="{{$tab3path}}" alt="">
                  <div class="play-icon-modal">
                      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
                  </div>
                </div>
            @endif
        </div>
    </div>

    <div class="tab-pane" id="tabs-2" role="tabpanel">
        <div class="slider">
            @if($tabImage->tab1!=null)
                @php
                    $tab1path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab1;
                @endphp
                <div class="item">
                    <img src="{{$tab1path}}" alt="">
                </div>
            @endif
            

             @if($tabImage->tab2!=null)
                @php
                    $tab2path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab2;
                @endphp
                <div class="item">
                    <img src="{{$tab2path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab4!=null)
                @php
                    $tab4path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab4;
                @endphp
                <div class="item">
                    <img src="{{$tab4path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab5!=null)
                @php
                    $tab5path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab5;
                @endphp
                <div class="item">
                    <img src="{{$tab5path}}" alt="">
                </div>
            @endif
             @if($tabImage->tab3!=null)
                @php
                    $tab3path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab3;
                @endphp
                <div class="item">
                  <img src="{{$tab3path}}" alt="">
                  <div class="play-icon-modal">
                      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
                  </div>
                </div>
            @endif
        </div>
    </div>
    <div class="tab-pane" id="tabs-3" role="tabpanel">
        <div class="slider">
            @if($tabImage->tab1!=null)
                @php
                    $tab1path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab1;
                @endphp
                <div class="item">
                    <img src="{{$tab1path}}" alt="">
                </div>
            @endif
            

             @if($tabImage->tab2!=null)
                @php
                    $tab2path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab2;
                @endphp
                <div class="item">
                    <img src="{{$tab2path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab4!=null)
                @php
                    $tab4path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab4;
                @endphp
                <div class="item">
                    <img src="{{$tab4path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab5!=null)
                @php
                    $tab5path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab5;
                @endphp
                <div class="item">
                    <img src="{{$tab5path}}" alt="">
                </div>
            @endif
             @if($tabImage->tab3!=null)
                @php
                    $tab3path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab3;
                @endphp
                <div class="item">
                  <img src="{{$tab3path}}" alt="">
                  <div class="play-icon-modal">
                      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
                  </div>
                </div>
            @endif
        </div>
    </div>

    <div class="tab-pane" id="tabs-4" role="tabpanel">
        <div class="slider">
            @if($tabImage->tab1!=null)
                @php
                    $tab1path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab1;
                @endphp
                <div class="item">
                    <img src="{{$tab1path}}" alt="">
                </div>
            @endif
            

             @if($tabImage->tab2!=null)
                @php
                    $tab2path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab2;
                @endphp
                <div class="item">
                    <img src="{{$tab2path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab4!=null)
                @php
                    $tab4path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab4;
                @endphp
                <div class="item">
                    <img src="{{$tab4path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab5!=null)
                @php
                    $tab5path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab5;
                @endphp
                <div class="item">
                    <img src="{{$tab5path}}" alt="">
                </div>
            @endif
             @if($tabImage->tab3!=null)
                @php
                    $tab3path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab3;
                @endphp
                <div class="item">
                  <img src="{{$tab3path}}" alt="">
                  <div class="play-icon-modal">
                      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
                  </div>
                </div>
            @endif
        </div>
    </div>

    <div class="tab-pane" id="tabs-5" role="tabpanel">
        <div class="slider">
            @if($tabImage->tab1!=null)
                @php
                    $tab1path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab1;
                @endphp
                <div class="item">
                    <img src="{{$tab1path}}" alt="">
                </div>
            @endif
            

             @if($tabImage->tab2!=null)
                @php
                    $tab2path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab2;
                @endphp
                <div class="item">
                    <img src="{{$tab2path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab4!=null)
                @php
                    $tab4path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab4;
                @endphp
                <div class="item">
                    <img src="{{$tab4path}}" alt="">
                </div>
            @endif

            @if($tabImage->tab5!=null)
                @php
                    $tab5path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab5;
                @endphp
                <div class="item">
                    <img src="{{$tab5path}}" alt="">
                </div>
            @endif
             @if($tabImage->tab3!=null)
                @php
                    $tab3path = url('/').'/uploads/featureimg/thumbnail/'.$tabImage->tab3;
                @endphp
                <div class="item">
                  <img src="{{$tab3path}}" alt="">
                  <div class="play-icon-modal">
                      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play-circle" class="svg-inline--fa fa-play-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>
                  </div>
                </div>
            @endif
        </div>
    </div>


</div>
        

         
<div class="pb-5 text-center pulvinar">         
         
    <div class="col-lg-6 mx-auto">
     @php
        $tabData = App\Models\HomePageContent::where('title','Tab Section Content')->first();
    @endphp
    <p>{{$tabData->content}} </p>
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
                    @php
                        $bottomData = App\Models\HomePageContent::where('title','Get Started Content')->first();
                    @endphp
                    <p>{{$bottomData->content}}</p>
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