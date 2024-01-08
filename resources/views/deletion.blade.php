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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">

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
    <form id="deleteaccount" method="POST" action="#" class="w-100">
    @csrf
        @if(session()->has('error'))
           <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
    <div class="form-box-s">
    <div class="mb-4">
        <h4 class="text-white">Delete Account</h4>
    </div>
        

  <div class="mb-4 position-relative">
  <i class="fa fa-envelope" aria-hidden="true"></i>
    <input type="email" class="form-control padding @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
  </div>


  <div class="mb-4 position-relative">
   <i class="fa fa-user"></i>
    <input type="password" class="form-control padding @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"  placeholder="Password">
  </div>

  <div class="mb-4 position-relative">
    <input type="text" class="form-control padding @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"  placeholder="Reason for Deletion" style="height:85px;">
  </div>
  
  <button type="submit" class="btn btn-primary subit mt-3">Delete Account</button>


</div>
</form>
</div>
</div>
            
        </div>
    </div>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
     $(document).ready(function() {
      $('#deleteaccount').submit(function(e) {
        // Prevent the form from submitting to the server
        e.preventDefault();

        // Show SweetAlert
        Swal.fire({
          title: 'Account Deletion Request',
          text: 'Account deletion request submitted successfully.',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          // Refresh the page
          location.reload();
        });
      });
    });
</script>