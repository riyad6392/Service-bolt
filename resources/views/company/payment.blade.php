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
<form id="payment-form" method="POST">
   <input id="name" name="xName" placeholder="Name" autocomplete="cc-name"></input>
   <br />
   <!-- Use the following src for the iframe on your form and replace ****version**** with the desired version: src="https://cdn.cardknox.com/ifields/****version****/ifield.htm" -->
   <iframe data-ifields-id="ach" data-ifields-placeholder="Checking Account Number" src="https://cdn.cardknox.com/ifields/2.6.2006.0102/ifield.htm"></iframe>
   <input data-ifields-id="ach-token" name="xACH" type="hidden"></input>
   <br />
   <!-- Use the following src for the iframe on your form and replace ****version**** with the desired version: src="https://cdn.cardknox.com/ifields/****version****/ifield.htm" -->
   <iframe data-ifields-id="card-number" data-ifields-placeholder="Card Number" src="https://cdn.cardknox.com/ifields/2.6.2006.0102/ifield.htm"></iframe>
   <input data-ifields-id="card-number-token" name="xCardNum" type="hidden"></input>
   <br />
   <!-- Use the following src for the iframe on your form and replace ****version**** with the desired version: src="https://cdn.cardknox.com/ifields/****version****/ifield.htm" -->
   <iframe data-ifields-id="cvv" data-ifields-placeholder="CVV" src="https://cdn.cardknox.com/ifields/2.6.2006.0102/ifield.htm"></iframe>
   <input data-ifields-id="cvv-token" name="xCVV" type="hidden"></input>
   <br />
   <input id="amount" name="xAmount" placeholder="Amount"></input>
   <br />
   <input id="month" name="xMonth" placeholder="Month" autocomplete="cc-exp-month"></input>
   <br />
   <input id="year" name="xYear" placeholder="Year" autocomplete="cc-exp-year"></input>
   <br />
   <input id="submit-btn" type="submit" value="Submit"></input>
   <br />
   <input id="clear-btn" type="button" value="Clear"></input>
   <br />
   <label id="transaction-status"></label>
   <br />
   <label data-ifields-id="card-data-error"></label>
   <br />
   <div class="results">
     <label style="display: none;">ACH Token: </label><label id="ach-token" style="display: none;"></label>
     <br />
     <label>Card Token: </label><label id="card-token"></label>
     <br />
     <label style="display: none;">CVV Token: </label><label id="cvv-token" style="display: none;"></label>
   </div>
   <br />
   <br />
 </form>
</div>
@endsection
@section('script')
    <script src="https://cdn.cardknox.com/ifields/2.6.2006.0102/ifields.min.js"></script>
<script type="text/javascript">
// setAccount("ifields_serviceboltdeva682f99929fd4ecc9e3ef29", "ServiceBolt", "0.1.2");
// var el = document.getElementById('payment-form');
document.addEventListener("DOMContentLoaded", function(event) { 
let defaultStyle = {
    border: '1px solid black',
    'font-size': '14px',
    padding: '3px',
    width: '350px',
    height: '30px',
    borderRadius: '5px'
};
                
let validStyle = {
    border: '1px solid green',
    'font-size': '14px',
    padding: '3px',
    width: '350px',
    height: '30px'
};   

let invalidStyle = {
    border: '1px solid red',
    'font-size': '14px',
    padding: '3px',
    width: '350px',
    height: '30px'
};

enableAutoSubmit('payment-form');
enable3DS('amount', 'month', 'year', true, 20000);

setIfieldStyle('ach', defaultStyle);
setIfieldStyle('card-number', defaultStyle);
setIfieldStyle('cvv', defaultStyle);

/*
 * [Required]
 * Set your account data using setAccount(ifieldKey, yourSoftwareName, yourSoftwareVersion).
 */
setAccount('ifields_serviceboltdeva682f99929fd4ecc9e3ef29', 'iFields_Sample_Form', '1.0');

enableAutoFormatting();

 addIfieldCallback('input', function(data) {
    if (data.ifieldValueChanged) {
        setIfieldStyle('card-number', data.cardNumberFormattedLength <= 0 ? defaultStyle : data.cardNumberIsValid ? validStyle : invalidStyle);
        if (data.lastIfieldChanged === 'cvv'){
            setIfieldStyle('cvv', data.issuer === 'unknown' || data.cvvLength <= 0 ? defaultStyle : data.cvvIsValid ? validStyle : invalidStyle);
        } else if (data.lastIfieldChanged === 'card-number') {
            if (data.issuer === 'unknown' || data.cvvLength <= 0) {
                setIfieldStyle('cvv', defaultStyle);
            } else if (data.issuer === 'amex'){
                setIfieldStyle('cvv', data.cvvLength === 4 ? validStyle : invalidStyle);
            } else {
                setIfieldStyle('cvv', data.cvvLength === 3 ? validStyle : invalidStyle);
            }
        } else if (data.lastIfieldChanged === 'ach') {
            setIfieldStyle('ach',  data.achLength === 0 ? defaultStyle : data.achIsValid ? validStyle : invalidStyle);
        }
    }
});

let checkCardLoaded = setInterval(function() {
    clearInterval(checkCardLoaded);
    focusIfield('card-number');
}, 1000);

document.getElementById('clear-btn').addEventListener('click', function(e){
    e.preventDefault();
    clearIfield('card-number');
    clearIfield('cvv');
    clearIfield('ach');
});

/*
 * [Required]
 * Call getTokens(onSuccess, onError, [timeout]) to create the SUTs. * Pass in callback functions for onSuccess and onError. Timeout is an optional 
 * parameter that will exit the function if the call to retrieve the SUTs take too long. The default timeout is 60 seconds. It takes an number value
 * marking the number of milliseconds.
 * 
 * If autoSubmit is enabled, this must be done in a submit event listener for the form element.
 */
document.getElementById('payment-form').addEventListener('submit', function(e){
    e.preventDefault();
    document.getElementById('transaction-status').innerHTML = 'Processing Transaction...';
    let submitBtn = this;
    submitBtn.disabled = true;
    getTokens(function() { 
            document.getElementById('transaction-status').innerHTML  = '';
            document.getElementById('ach-token').innerHTML = document.querySelector("[data-ifields-id='ach-token']").value;
            document.getElementById('card-token').innerHTML = document.querySelector("[data-ifields-id='card-number-token']").value;
            document.getElementById('cvv-token').innerHTML = document.querySelector("[data-ifields-id='cvv-token']").value;
            submitBtn.disabled = false;

            //The following line of code has been commented out for the benefit of the sample form. Uncomment this line on your actual webpage.
            //document.getElementById('payment-form').submit();
        },
        function() {
            document.getElementById('transaction-status').innerHTML = '';
            document.getElementById('ach-token').innerHTML = '';
            document.getElementById('card-token').innerHTML = '';
            document.getElementById('cvv-token').innerHTML = '';
            submitBtn.disabled = false;
        },
        30000
    );
});
});
</script>
@endsection