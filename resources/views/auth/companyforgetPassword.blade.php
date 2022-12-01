<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Favicon -->
<link rel="shortcut icon" href="{{url('/')}}/uploads/serviceboltfavicon.png" />
<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title></title>
</head>
<body style="background: black;">

<div>
    <div class="row g-0">
        <div class="col-md-5 none-sign" style="height: 100vh">
            <div class="bg-sign"></div>
        </div>
        <div class="col-md-7">
            
        <div class="box-size-p p-150">
           
<div class="d-flex align-items-center justify-content-center h-75-w">  
    <form method="POST" action="{{ route('login.forget.post') }}" class="w-100">
      @csrf
          
      <div class="form-box-s">
         @if(Session::has('message'))
              <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            @if(session()->has('error'))
             <div class="alert alert-danger">
                  {{ session()->get('error') }}
              </div>
          @endif
      <div class="mb-4">
          <h4 class="text-white">Forgot Password</h4>
          @if(session()->has('success'))
          <p class="text-white">{{ session()->get('success') }}</p>
           @endif
      </div>
          

      <div class="mb-4 position-relative">
      <i class="fa fa-envelope" aria-hidden="true"></i>
        <input type="email" class="form-control padding @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div class="mb-4">
  <div class="">
      <label for="css"><a href="{{ route('login') }}" style="color: #FAED61;">Click here to Login</a></div>
  </div>
    
      <button type="submit" class="btn btn-primary subit mt-3">Submit</button>
  </form>
  </div>
</div>
</div>
  </div>
</div>
</body>
</html>