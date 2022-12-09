@extends('layouts.header')
@section('content')
<style type="text/css">
  img[src$="#custom_marker"]{
    /*border: 2px solid #000 !important;*/
    border-radius:50%;
}

iframe {
  border: 0 solid black;
  width: 600px;
  height: 45px;
  padding: 0px;
  margin-bottom: 5px;
}

input {
  border: 1px solid black;
  border-radius: 5px;
  font-size: 14px;
  padding: 3px;
  width: 350px;
  height: 30px;
  margin-bottom: 15px;
}

#card-data-error {
  color: red;
}

form {
  width: 500px;
}

#submit-btn, #clear-btn {
  background-color: #3f143e;
  color: #FFF;
  width: 357px;
}

.results {
  background-color: #e8e8e8;
  padding: 20px;
  width: 360px;
}

#ach-token,
#card-token,
#cvv-token {
  word-break: break-all;
  font-weight: 600;
}
</style>
<div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
       <h3 style="font-weight: bold;">Hello, {{Auth::user()->firstname}}  {{Auth::user()->lastname}}!</h3>
       <span style="color: #B0B7C3;">{{ date('l (F d, Y)') }}</span>
     </div>
     </div>
    </div>
    <iframe id="agreement" class="agreement" data-ifields-id="agreement" src="https://cdn.cardknox.com/ifields/2.6.2006.0102/agreement.htm"></iframe>
     <!-- <iframe id="agreement" class="agreement" data-ifields-id="agreement" src="https://cdn.cardknox.com/ifields/2.14.2211.1101/ifield-sample.htm?agreement"></iframe> -->
    
</div>
@endsection
@section('script')
    <script src="https://cdn.cardknox.com/ifields/2.6.2006.0102/ifields.min.js"></script>

<script type="text/javascript">

function handleAgreementResponse(response) {
    let msg = null;
    if (!response) {
        msg = "Failed to load token. No Response";
    } else if (response.status !== iStatus.success) {
        msg = "Failed to load token. "+response.statusText || "No Error description available";
    } else if (!response.token) {
        msg = "Failed to load token. No Token available";
    } else {
        msg = response.token;
    }
    setTimeout(() => {alert(msg)}, 10);
}

document.addEventListener("DOMContentLoaded", function(event) {
    if(typeof ckCustomerAgreement === 'undefined') {
        throw new ReferenceError("ckCustomerAgreement is not defined");
    }
    ckCustomerAgreement.enableAgreement({
        iframeField: 'agreement',
        xKey: 'ifields_serviceboltdeva682f99929fd4ecc9e3ef29',
        autoAgree: true,
        callbackName: 'handleAgreementResponse'
    }); 
    ckCustomerAgreement.getToken()
    .then(resp => {
        handleAgreementResponse(resp);
    })
    .catch(err => {
        console.error("Agreement Token Error", exMsg(err));
        handleAgreementResponse(err);
    });
});


</script>
@endsection