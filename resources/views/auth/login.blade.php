<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{url('/')}}/uploads/serviceboltfavicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/css/bootstrap.min.css"
          integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
                <img src="{{url('/')}}/images/logo.png" class="logo-signin">
                <div class="d-flex align-items-center justify-content-center h-75-w">
                    <form method="POST" action="{{ route('login') }}" class="w-100">
                        @csrf
                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        <div class="form-box-s">
                            <div class="mb-4">
                                <h4 class="text-white">Sign In</h4>
                                @if(session()->has('success'))
                                    <p class="text-white">{{ session()->get('success') }}</p>
                                @endif
                            </div>


                            <div class="mb-4 position-relative">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <input type="email" class="form-control padding @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" placeholder="Email" required
                                       autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                                @enderror
                            </div>


                            <div class="mb-4 position-relative">
                                <i class="fa fa-user"></i>
                                <input type="password"
                                       class="form-control padding @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password" placeholder="Password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <div class="">
                                    <label for="css"><a href="{{ route('login.forget.get') }}" style="color: #FAED61;">Forgot
                                            Password ?</a>
                                </div>
                            </div>
                            <div class="form-group row" style="display: none;">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary subit mt-3">Sign In</button>


                            <div class="mt-3">
                                <p class="text-white">Don’t have an account? <a href="{{url('register')}}"
                                                                                style="color: #FAED61;text-decoration: none;">
                                        Sign Up </a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>