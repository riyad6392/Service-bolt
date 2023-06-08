<link href="http://localhost/servicebolt/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    .back-btn{
            background: green;
    color: #fff;
    text-transform: capitalize;
    padding: 8px 20px;
    border-radius: 100px;
    border: none;
    }

.errowMessage {
    margin-top: 100px;
    text-align: center;
    margin-bottom: 20px;
}

.mainbox{
    background: #fffdd7;
    height: 100vh;
    padding-top: 20px;
    padding-bottom: 20px;
}

.card{
        height: 80vh;
}

</style>

<section class="mainbox">
<div class="container">
    <div class="card">
          <div class="card-body">
           <div class="row">
  
           <div class="col-md-5" >
            <div>
                <button type="button btn btn-primary" class="back-btn">
                 <a href="{{ url()->previous() }}" class="back-btn" style="color:#fff;">Try Again</a>
                </button>

            <div class="errowMessage">
              {{$errors}}
            </div>
            </div>
            </div>

          <div class="col-md-7" style="text-align:center;">
              <img src="{{ asset('uploads/payment-error.jpg')}}" class="img-fluid">
          </div>
  
            </div>
            </div>
            </div>
            </div>
            </div>
</section>
