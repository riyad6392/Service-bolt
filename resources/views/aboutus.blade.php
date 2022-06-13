@extends('layouts.cmspage')
@section('content')
@php
$aboutus = App\Models\CMSpage::where('pagename','About Us')->where('status','Active')->first();
@endphp
<section>
    <div style="background-image: url(images/Rectangle.png);height: 350px;background-size: contain;width: 100%;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-p">
                    <p>{{$aboutus->description}}</p>
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