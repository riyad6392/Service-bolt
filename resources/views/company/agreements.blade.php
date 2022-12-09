@extends('layouts.header')
@section('content')
<style type="text/css" href="https://cdn.cardknox.com/ifields/2.14.2211.1101/c2p-def.css"></style>
<style type="text/css">
  img[src$="#custom_marker"]{
    /*border: 2px solid #000 !important;*/
    border-radius:50%;
}

 iframe {
                border: 0 solid black;
                width: 100%;
                height: 28px;
                padding: 0px;
                margin-bottom: 5px;
            }
            div.main {
                width: 350px;
            }
            iframe.gp {
                display: inline-block;
                border: 0;
                border-radius: 6px;
                width: 100%;
                height: 100%;
                max-height: 45px;
                vertical-align: middle;
                cursor: pointer;
                white-space: nowrap;
                padding: 0px;
                margin: 0px;
                overflow: hidden;
            }
            div.gp {
                width: 250px;
                position: relative;
                vertical-align: middle;
                overflow: hidden;
                margin-bottom: 20px;
            }
            iframe.agreement {
                display: block;
                height: 250px;
                padding: 0px;
            }
            div.agreement {
                border: 1px solid black;
                display: block;
                padding: 8px 8px 0px 0px;
                margin-top: -12px;
                margin-bottom: 12px;
            }
            input.agreement {
                display: inline-block;
                width: 95%;
                margin-left: 10px;
                margin-bottom: 8px;
            }
            .ap {
                border: 0;
                width: 250px;
                height: auto;
                min-height: 55px;
                padding: 0px;
                margin-bottom: 12px;
            }
            .c2p {
                display: inline-block;
                border: 0;
                width: 260px;
                height: auto;
                margin-bottom: 0;
            }
            .c2p .txt-signin{
                width: 90% !important;
            }

            input {
                border: 1px solid black;
                font-size: 14px;
                padding: 3px;
                width: 250px;
                margin-bottom: 12px;
            }

            #card-data-error {
                color: red;
            }

            .hidden {
                display: none;
            }

            .note {
                font-size: small;
                border-left:  4px solid rgb(52, 109, 219);
                border-radius: 4px;
                background-color: rgb(245, 247, 249);
                display: inline-block;
                overflow-wrap: break-word;
                padding: 2px 4px;
                margin-top: 5px;
            }

            textarea {
                border: 1px solid black;
                width: 100%;
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
<div class="main">
        <form id="payment-form" method="POST">
            <input id="name" name="xName" placeholder="Name" autocomplete="cc-name"></input>
            <br/>
            
             <iframe data-ifields-id="ach" data-ifields-placeholder="Checking Account Number" src="https://cdn.cardknox.com/ifields/2.14.2211.1101/ifield.htm"></iframe>
            <input data-ifields-id="ach-token" name="xACH" type="hidden"></input>
            <br/>
           
           <iframe data-ifields-id="card-number" data-ifields-placeholder="Card Number" src="https://cdn.cardknox.com/ifields/2.14.2211.1101/ifield.htm"></iframe>
            <input data-ifields-id="card-number-token" name="xCardNum" type="hidden"></input>
            <br/>
            <!-- Use the following src for the iframe on your form and replace ****version**** with the desired version: src="https://cdn.cardknox.com/ifields/****version****/ifield.htm" -->
            <iframe data-ifields-id="cvv" data-ifields-placeholder="CVV" src="https://cdn.cardknox.com/ifields/2.14.2211.1101/ifield.htm"></iframe>
            <input data-ifields-id="cvv-token" name="xCVV" type="hidden"></input>
            <br/>
            <input id="amount" name="xAmount" placeholder="Amount" type="number" inputmode="decimal"></input>
            <br/>
            <input id="month" name="xMonth" placeholder="Month" autocomplete="cc-exp-month" type="number" pattern="[0-9]*"></input>
            <br/>
            <input id="year" name="xYear" placeholder="Year" autocomplete="cc-exp-year" type="number" pattern="[0-9]*"></input>
            <br/>
            <input id="gatewayMerchantId" name="xGatewayMerchantId" class="hidden" placeholder="GatewayMerchantId" autocomplete="gateway-merch-id"></input>
            <div id="agr-container" class="hidden" >
                <br/>
                <div class="agreement">
                    <iframe id="agreement" class="agreement" data-ifields-id="agreement" src="https://cdn.cardknox.com/ifields/2.14.2211.1101/agreement.htm"></iframe>
                    <div id="agr-btn-container" class="hidden">
                        <br />
                        <input id="agreement-btn" type="button" value="Get Token" class="agreement" ></input>
                    </div>
                </div>
            </div>
            <br />
            <input id="submit-btn" type="submit" value="Submit"></input>
            <br/>
            <input id="clear-btn" type="button" value="Clear"></input>
            <br/>
            <div id="divGpay" class="gp hidden">
                <iframe id="igp" class="gp" data-ifields-id="igp" data-ifields-oninit="gpRequest.initGP" src="https://cdn.cardknox.com/ifields/2.14.2211.1101/igp.htm"
                        allowpaymentrequest
                        sandbox="allow-popups allow-modals allow-scripts allow-same-origin
                                 allow-forms allow-popups-to-escape-sandbox allow-top-navigation"
                        title="GPay checkout page">
                </iframe>
                <br/>
                <div class="note">
                    We support Google Pay <span style="font-style: italic; font-weight: bolder;">Address Validation</span> and for displaying purposes any address in Hawaii (HI) will be rejected.
                </div>
            </div>
            <div id="ap-container" class="ap hidden" >
                <div id="ap-btn-container" class="hidden" ></div>
                <div class="note">
                    We support Apple Pay <span style="font-style: italic; font-weight: bolder;">Address Validation</span> and for displaying purposes any address in Hawaii (HI) will be rejected.
                </div>
            </div>
            <br/>
            <label id="transaction-status"></label>
            <br/>
            <label data-ifields-id="card-data-error" style="color: red;"></label>
            <br/>
            <label>ACH Token: </label><label id="ach-token"></label>
            <br/>
            <label>Card Token: </label><label id="card-token"></label>
            <br/>
            <label>CVV Token: </label><label id="cvv-token"></label>
            <br/>
            <br/>
            <div id="divAgr" class="hidden">
                <label>Agreement Token: </label><label id="agr-token"></label>
                <br/>
                <br/>
            </div>
            <div id="divGPPayload" class="hidden">
                <label id="lbGPPayload">Google Pay Payload: </label>
                <br />
                <textarea id="gp-payload" rows="10" readonly="true"></textarea>
                <br/>
                <br/>
            </div>
            <div id="divAPPayload" class="hidden">
                <label id="lbAPPayload">Apple Pay Payload: </label>
                <br />
                <textarea id="ap-payload" rows="10" readonly="true"></textarea>
                <br/>
                <br/>
            </div>
            <br/>
            
        </form>
        </div>
</div>
@endsection
@section('script')
    <script src="https://cdn.cardknox.com/ifields/2.14.2211.1101/ifields.min.js"></script>
<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function(event) { 
                let defaultStyle = {
                    border: '1px solid black',
                    'font-size': '14px',
                    padding: '3px',
                    width: '250px'
                };
                                
                let validStyle = {
                    border: '1px solid green',
                    'font-size': '14px',
                    padding: '3px',
                    width: '250px'
                };   

                let invalidStyle = {
                    border: '1px solid red',
                    'font-size': '14px',
                    padding: '3px',
                    width: '250px'
                };
               
                if (/[?&](is)?debug/i.test(window.location.search)){
                    setDebugEnv(true);
                }
                const queryString = parseQueryString(window.location.href);
                /*
                 * Customer Agreement
                 */
                if ('agreement' in queryString) {
                    if (queryString['agreement'] === 'auto') {
                        ckCustomerAgreement.enableAgreement({
                            iframeField: 'agreement',
                            xKey: 'ifields_serviceboltdeva682f99929fd4ecc9e3ef29',
                            autoAgree: true,
                            callbackName: 'agrRequest.showToken',
                            isDebug: isDebugEnv
                        });
                    } else {
                        ckCustomerAgreement.enableAgreement({
                            iframeField: 'agreement',
                            xKey: 'ifields_serviceboltdeva682f99929fd4ecc9e3ef29',
                            autoAgree: false,
                            isDebug: isDebugEnv
                        });
                        document.getElementById('agreement-btn').addEventListener('click', function(e) {
                            e.preventDefault();
                            agrRequest.showToken({status: iStatus.success, token: 'Loading token, please wait...'});
                            ckCustomerAgreement.getToken()
                                .then(resp => {
                                    agrRequest.showToken(resp);
                                })
                                .catch(err => {
                                    logError("Agreement Token Error", err);
                                    agrRequest.showToken(err);
                                });
                        });
                        showHide("agr-btn-container", true);
                    }
                    showHide("agr-container", true);
                }                
                /*
                 * [Contitional]
                 * Required if you want to enable Google Pay payment method on your website
                 * For more information please refer to documentation 
                 */
                 ckGooglePay.enableGooglePay({amountField: 'amount'});

                 /*
                 * [Contitional]
                 * Required if you want to enable Apple Pay payment method on your website
                 * For more information please refer to documentation 
                 */
                 ckApplePay.enableApplePay({
                    initFunction: 'apRequest.initAP',
                    amountField: 'amount'
                });

                 /*
                 * [Optional]
                 * You can enable allowing the user to submit the form by pressing the 'enter' key on their keyboard when the ifield is focused by calling
                 * enableAutoSubmit(formElementId)
                 * 
                 * The formElementId is the id of your form that gets submit when the user presses 'enter' on their keyboard.
                 * 
                 * Note: If this feature is enabled, the getTokens must be handled in a submit event listener for the form as that event is what gets triggered.
                 */
                enableAutoSubmit('payment-form');
                
                /*
                * [Optional]
                * You can enable 3DS Protection using enable3DS(amountElementId, monthElementId, yearElementId, waitForResponse, waitForResponseTimeout)
                *
                * The amountElementId, monthElementId, and yearElementId parameters are the ids for the html elements that contain the resepective values for those fields.
                * 
                * The waitForResponse parameter is an optional flag that indicates whether the getToken function should wait for 3DS data if it did not yet 
                * recieve it before generating the tokens (the default value is true). If the value is set to false, the tokens will be created without 3DS protection.
                * 
                * The waitForResponseTimeout parameter is how long it should wait for a 3DS response before creating the token without it (the default value is 20,000ms).
                *
                * Note: The timeout for waitForResponse and getToken are not cumulative. If they are the same value and the waitForResponse timeout is hit, the getToken timeout
                * will trigger as well.
                */
                enable3DS('amount', 'month', 'year', true, 20000);

                /*
                 * [Optional]
                 * You can customize the iFields by passing in the appropriate css as JSON using setIfieldStyle(ifieldName, style)
                 */
                setIfieldStyle('ach', defaultStyle);
                setIfieldStyle('card-number', defaultStyle);
                setIfieldStyle('cvv', defaultStyle);

                /*
                 * [Required]
                 * Set your account data using setAccount(ifieldKey, yourSoftwareName, yourSoftwareVersion).
                 */
                setAccount('ifields_serviceboltdeva682f99929fd4ecc9e3ef29', 'iFields_Sample_Form', '1.0');
                
                /*
                 * [Optional]
                 * Use enableAutoFormatting(separator) to automatically format the card number field making it easier to read
                 * The function contains an optional parameter to set the separator used between the card number chunks (Default is a single space)
                 */
                enableAutoFormatting();

                /*
                 * [Optional]
                 * Use addIfieldCallback(event, callback) to set callbacks for when the event is triggered inside the ifield
                 * The callback function receives a single parameter with data about the state of the ifields
                 * The data returned can be seen by using alert(JSON.stringify(data));
                 * The available events are ['input', 'click', 'focus', 'dblclick', 'change', 'blur', 'keypress', 'issuerupdated']
                 * ('issuerupdated' is fired when the CVV ifield is updated with card issuer)
                 * 
                 * The below example shows a use case for this, where you want to visually alert the user regarding the validity of the card number, cvv and ach ifields
                 */
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

                /*
                 * [Optional]
                 * You can set focus on an ifield by calling focusIfield(ifieldName), in this case a delay is added to ensure the iframe has time to load
                 */ 
                let checkCardLoaded = setInterval(function() {
                    clearInterval(checkCardLoaded);
                    focusIfield('card-number');
                }, 1000);

                /*
                 * [Optional]
                 * You can clear the ifield by calling clearIfield(ifieldName)
                 */
                document.getElementById('clear-btn').addEventListener('click', function(e){
                    e.preventDefault();
                    clearIfield('card-number');
                    clearIfield('cvv');
                    clearIfield('ach');
                    setGPPayload("");
                    setAPPayload("");
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

                if (/[?&]merchant/.test(window.location.search)) {
                    showHide("gatewayMerchantId", true);
                }
            });

            //Google Pay
            const gpRequest = {
                merchantInfo: {
                    merchantName: "Example Merchant"
                },
                buttonOptions: {
                    buttonSizeMode: GPButtonSizeMode.fill
                },
                billingParams: {
                    //phoneNumberRequired: true,
                    emailRequired: true,
                    billingAddressRequired: true,
                    billingAddressFormat: GPBillingAddressFormat.full                        
                },
                shippingParams: {
                    phoneNumberRequired: true,
                    emailRequired: true,
                    onGetShippingCosts: function (shippingData) {
                        logDebug({
                            label: "onGetShippingCosts",
                            data: shippingData
                        });
                        return {
                            "shipping-001": "0.00",
                            "shipping-002": "1.99",
                            "shipping-003": "10.00"
                        }
                    },
                    onGetShippingOptions: function (shippingData) {
                        logDebug({
                            label: "onGetShippingOptions",
                            data: shippingData
                        });
                        const hasShipping = shippingData && shippingData.shippingAddress;
                        if (hasShipping && shippingData.shippingAddress.administrativeArea == "HI") {
                            return {
                                error: {
                                    reason: "SHIPPING_ADDRESS_UNSERVICEABLE",
                                    message: "This shipping option is invalid for the given address",
                                    intent: "SHIPPING_ADDRESS"
                                }
                            }
                        }
                        let selectedOptionid = "shipping-001";
                        if (hasShipping && shippingData.shippingOptionData.id !== "shipping_option_unselected") {
                            selectedOptionid = shippingData.shippingOptionData.id;
                        }
                        return {
                            defaultSelectedOptionId: selectedOptionid,
                            shippingOptions: [
                                {
                                    "id": "shipping-001",
                                    "label": "Free: Standard shipping",
                                    "description": "Free Shipping delivered in 5 business days."
                                },
                                {
                                    "id": "shipping-002",
                                    "label": "$1.99: Standard shipping",
                                    "description": "Standard shipping delivered in 3 business days."
                                },
                                {
                                    "id": "shipping-003",
                                    "label": "$10: Express shipping",
                                    "description": "Express shipping delivered in 1 business day."
                                },
                            ]
                        };
                    }
                },
                onGetTransactionInfo: function (shippingData) {
                    logDebug({
                            label: "onGetTransactionInfo",
                            data: shippingData
                        });
                    const taxMap = new Map([["DEF", 0.1], ["NY", 0.0875], ["NJ", 0.07]]);
                    const amt = getAmount();
                    const hasShipping = shippingData && shippingData.shippingAddress;
                    const tax = hasShipping && taxMap.get(shippingData.shippingAddress.administrativeArea) || taxMap.get("DEF");
                    return {
                        displayItems: [
                            {
                                label: "Subtotal",
                                type: "SUBTOTAL",
                                price: roundTo(amt, 2),
                            },
                            {
                                label: "Tax",
                                type: "TAX",
                                price: roundTo(tax * amt, 2),
                            }
                        ],
                        countryCode: 'US',
                        currencyCode: "USD",
                        totalPriceStatus: "FINAL",
                        totalPrice: roundTo((1+tax) * amt, 2),
                        totalPriceLabel: "Total"
                    }
                },    
                onBeforeProcessPayment: function () {
                    return new Promise(function (resolve, reject) {
                        try {
                            //Do some validation here
                            resolve(iStatus.success);
                        } catch (err) {
                            reject(err);
                        }
                    });
                },
                onProcessPayment: function (paymentResponse) {
                    return new Promise(function (resolve, reject) {
                            try {
                                // show returned data in developer console for debugging
                                logDebug({
                                    label: "paymentResponse",
                                    data: JSON.stringify(paymentResponse)
                                });                               
                                paymentToken = paymentResponse.paymentData.paymentMethodData.tokenizationData.token;
                                logDebug({
                                    label: "paymentToken",
                                    data: paymentToken
                                });
                                const amt = (paymentResponse && paymentResponse.transactionInfo && paymentResponse.transactionInfo.totalPrice) || 0;
                                try {
                                if (amt <= 0) {
                                    throw "Payment is not authorized. Invalid amount. Amount must be greater than 0";
                                }
                                const gmid = document.getElementById("gatewayMerchantId");
                                if (gmid && gmid.value) {
                                    paymentResponse.xGatewayMerchantId = gmid.value;
                                }
                                ckGooglePay.authorize(paymentResponse)
                                    .then((resp) => {
                                        gpRequest.handleResponse(resp);
                                        setGPPayload(JSON.stringify(paymentResponse, null, 2));
                                        resolve(resp);
                                    })
                                    .catch((rej) => {
                                        logError("Payment is not authorized", rej);
                                        setGPPayload("");
                                        setTimeout(function () { alert("Payment is not authorized. Please check the logs") }, 500);
                                        reject(rej);
                                    });
                                } catch (err) {
                                    logError("onProcessPayment", err);
                                    setGPPayload("");
                                    setTimeout(function () { alert(exMsg(err)) }, 500);
                                    reject({error: err});
                                }
                        } catch (err) {
                            setGPPayload("");
                            reject(err);
                        }
                    });
                },
                onPaymentCanceled: function(respCanceled) {
                    setTimeout(function () { alert("Payment was canceled") }, 500);
                },
                handleResponse: function (resp) {
                    const respObj = JSON.parse(resp);
                    if (respObj) {
                        if (respObj.xError) {
                            setTimeout(function () { alert(`There was a problem with your order (${respObj.xRefNum})!`) }, 500);
                        } else
                            setTimeout(function () { alert(`Thank you for your order (${respObj.xRefNum})!`) }, 500);
                    }
                },
                getGPEnvironment: function () {
                    if (/[?&]prod/.test(window.location.search)) {
                        return GPEnvironment.production;
                    }
                    return GPEnvironment.test;
                },
                initGP: function() {
                    return {
                        merchantInfo: this.merchantInfo,
                        buttonOptions: this.buttonOptions,
                        environment: this.getGPEnvironment(),
                        billingParameters: this.billingParams,
                        shippingParameters: {
                            emailRequired: this.shippingParams.emailRequired,
                            onGetShippingCosts: "gpRequest.shippingParams.onGetShippingCosts",
                            onGetShippingOptions: "gpRequest.shippingParams.onGetShippingOptions"
                        },
                        onGetTransactionInfo: "gpRequest.onGetTransactionInfo",
                        onBeforeProcessPayment: "gpRequest.onBeforeProcessPayment",
                        onProcessPayment: "gpRequest.onProcessPayment",
                        onPaymentCanceled: "gpRequest.onPaymentCanceled",
                        onGPButtonLoaded: "gpRequest.gpButtonLoaded",
                        isDebug: isDebugEnv
                    };
                },
                gpButtonLoaded: function(resp) {
                    if (!resp) return;
                    if (resp.status === iStatus.success) {
                        showHide("divGpay", true);
                    } else if (resp.reason) {
                        logDebug({
                            label: "gpButtonLoaded",
                            data: resp.reason
                        });
                    }
                }
            };
            function setGPPayload(value) {
                document.getElementById('gp-payload').value = value;
                showHide("divGPPayload", value);
            }

            const apRequest = {
                buttonOptions: {
                    buttonContainer: "ap-btn-container",
                    buttonColor: APButtonColor.black,
                    buttonType: APButtonType.pay
                },
                taxAmt: null,
                shippingMethod: null,
                creditType: null,
                getTransactionInfo: function (taxAmt, shippingMethod, creditType) {
                    try {
                        this.shippingMethod = shippingMethod || this.shippingMethod || {
                                    "label": "Free Shipping",
                                    "amount": "0.00",
                                    "type": "final"
                                };
                        this.taxAmt = roundToNumber(taxAmt, 4) || this.taxAmt || 0.07;
                        this.creditType = creditType || this.creditType;
                        const amt = getAmount();
                        const lineItems = [
                            {
                                "label": "Subtotal",
                                "type": "final",
                                "amount": amt
                            },
                            this.shippingMethod
                        ];
                        if (this.creditType === "credit") {
                            lineItems.push({
                                "label": "Credit Card Fee",
                                "amount": roundTo(0.0275*amt, 2),
                                "type": "final"
                            });
                        }
                        lineItems.push({
                            "label": "Estimated Tax",
                            "amount": roundTo(this.taxAmt*amt, 2),
                            "type": "final"
                        });
                        let totalAmt = 0;
                        lineItems.forEach((item) => {
                            totalAmt += parseFloat(item.amount)||0;
                        });
                        totalAmt = roundTo(totalAmt, 2);

                        return {
                            'lineItems': lineItems,  
                            total: {
                                    type:  'final',
                                    label: 'Total',
                                    amount: totalAmt,
                                }
                        };                        
                    } catch (err) {
                        logError("getTransactionInfo error ", err);
                    }
                },  
                onGetTransactionInfo: function () {
                    try {
                        return this.getTransactionInfo();
                    } catch (err) {
                        logError("onGetTransactionInfo error ", err);
                    }
                },  
                onGetShippingMethods: function()  {
                    return [
                        {
                            label: 'Free Shipping',
                            amount: '0.00',
                            identifier: 'free',
                            detail: 'Delivers in five business days',
                        },
                        {
                            label: 'Express Shipping',
                            amount: '5.00',
                            identifier: 'express',
                            detail: 'Delivers in two business days',
                        },
                    ];
                },
                onShippingContactSelected: function(shippingContact) {
                    const self = this;
                    return new Promise(function (resolve, reject) {
                        try {
                            logDebug({
                                label: "shippingContact",
                                data: JSON.stringify(shippingContact)
                            });
                            const hasShipping = shippingContact && shippingContact.administrativeArea;
                            let taxAmt = 0.1;
                            const newShippingMethods = [
                                {
                                    label: 'Free Shipping',
                                    amount: '0.00',
                                    identifier: 'free',
                                    detail: 'Delivers in five business days',
                                }                                
                            ];
                            if (hasShipping) {
                                if (shippingContact.administrativeArea === "NY") {
                                    taxAmt = 0.0875;
                                    newShippingMethods.push(
                                            {
                                                label: 'Overnight Shipping',
                                                amount: '10.00',
                                                identifier: 'overnight',
                                                detail: 'Delivers in one business days',
                                            }
                                        );
                                } else if (shippingContact.administrativeArea === "NJ") {
                                    taxAmt = 0.07;
                                    newShippingMethods.push(
                                        {
                                            label: 'Express Shipping',
                                            amount: '5.00',
                                            identifier: 'express',
                                            detail: 'Delivers in two business days',
                                        }
                                    );
                                }
                            }
                            resp = self.getTransactionInfo(taxAmt, newShippingMethods[0]);
                            resp.shippingMethods = newShippingMethods;
                            if (hasShipping && shippingContact.administrativeArea == "HI") {
                                resp.error = {
                                    code: APErrorCode.addressUnserviceable,
                                    contactField: APErrorContactField.administrativeArea,
                                    message: "Shipping is not available for the given address"
                                }
                            }
                            resolve(resp);                            
                        } catch (err) {
                            logError("onShippingContactSelected error.", err);
                            reject({errors: [err]});
                        }
                    })                
                },
                onShippingMethodSelected: function(shippingMethod) {
                    const self = this;
                    return new Promise(function (resolve, reject) {
                        try {
                            logDebug({
                                label: "shippingMethod",
                                data: JSON.stringify(shippingMethod)
                            });
                            const resp = self.getTransactionInfo(null, shippingMethod);
                            resolve(resp);                            
                        } catch (err) {
                            logError("onShippingMethodSelected error.", err);
                            reject({errors: [err]});
                        }
                    })                
                },
                onPaymentMethodSelected: function(paymentMethod) {
                    const self = this;
                    return new Promise(function (resolve, reject) {
                        try {
                            logDebug({
                                label: "paymentMethod",
                                data: JSON.stringify(paymentMethod)
                            });
                            const resp = self.getTransactionInfo(null, null, paymentMethod.type);
                            resolve(resp);                            
                        } catch (err) {
                            logError("onPaymentMethodSelected error.", err);
                            reject({errors: [err]});
                        }
                    })                
                },
                onValidateMerchant: function(validationUrl) {
                    return new Promise(function (resolve, reject) {
                        try {
                            ckApplePay.getSession(validationUrl)
                            .then(function (response) {
                                try {
                                    logDebug({
                                        label: "onValidateMerchant",
                                        data: response
                                    });
                                    resolve(response);
                                } catch (err) {
                                    logError("getApplePaySession exception.", err);
                                    reject(err);
                                }
                            })
                            .catch(function(err) {
                                logError("getApplePaySession error.", err);
                                reject(err);
                            });    
                        } catch (err) {
                            logError("onValidateMerchant error.", err);
                            reject(err);
                        }
                    })
                },
                onPaymentAuthorize: function(paymentResponse) {
                    return new Promise(function (resolve, reject) {
                        try {
                            ckApplePay.authorize(paymentResponse.token)
                            .then(function (response) {
                                try {
                                    logDebug({
                                        label: "onPaymentAuthorize",
                                        data: response
                                    });
                                    const respPayload = "Apple Response (PaymentResponse):\n---------------------\n"+
                                        JSON.stringify(paymentResponse, null, 2) +
                                        "\n\nGateway Response (CompleteResponse):\n---------------------\n"+
                                        response;
                                    setAPPayload(respPayload);
                                    const resp = JSON.parse(response);
                                    if (!resp)
                                        throw "Invalid response: "+ response;
                                    if (resp.xError) {
                                        throw resp;
                                    }
                                    resolve(response);
                                } catch (err) {
                                    throw err;
                                }
                            })
                            .catch(function(err) {
                                logError("authorizeAPay error.", err);
                                apRequest.handleAPError(err);
                                reject(err);
                            });    
                        } catch (err) {
                            logError("onPaymentAuthorize error.", err);
                            apRequest.handleAPError(err);
                            reject(err);
                        }
                    })
                },
                onPaymentComplete: function(paymentComplete) {
                    if (paymentComplete.response) { //Success
                        const resp = JSON.parse(paymentComplete.response);
                        if (resp.xRefNum) {
                            setTimeout(function(){ alert("Thank you for your order:("+resp.xRefNum+")")}, 100);
                        } else {
                            setTimeout(function(){ alert("Thank you for your order.")}, 100);
                        }
                    } else if (paymentComplete.error) {
                        logError("onPaymentComplete", paymentComplete.error);
                        this.handleAPError(paymentComplete.error);
                    }
                                    
                },
                handleAPError: function(err) {
                    if (err && err.xRefNum) {
                        setTimeout(function(){ alert("There was a problem with your order:("+err.xRefNum+")")}, 100);
                    } else {
                        setTimeout(function(){ alert("There was a problem with your order:"+exMsg(err))}, 100);
                    }
                },
                initAP: function() {
                    return {
                        buttonOptions: this.buttonOptions,
                        merchantIdentifier: "merchant.cardknoxdev.com",
                        requiredFeatures: [APRequiredFeatures.address_validation, APRequiredFeatures.support_subscription],
                        requiredBillingContactFields: ['postalAddress', 'name', 'phone', 'email'],
                        requiredShippingContactFields: ['postalAddress', 'name', 'phone', 'email'],
                        onGetTransactionInfo: "apRequest.onGetTransactionInfo",
                        onGetShippingMethods: "apRequest.onGetShippingMethods",
                        onShippingContactSelected: "apRequest.onShippingContactSelected",
                        onShippingMethodSelected: "apRequest.onShippingMethodSelected",
                        onPaymentMethodSelected: "apRequest.onPaymentMethodSelected",
                        onValidateMerchant: "apRequest.onValidateMerchant",
                        onPaymentAuthorize: "apRequest.onPaymentAuthorize",
                        onPaymentComplete: "apRequest.onPaymentComplete",
                        onAPButtonLoaded: "apRequest.apButtonLoaded",
                        isDebug: isDebugEnv
                    };
                },
                apButtonLoaded: function(resp) {
                    if (!resp) return;
                    if (resp.status === iStatus.success) {
                        showHide(this.buttonOptions.buttonContainer, true);
                        showHide("ap-container", true);
                    } else if (resp.reason) {
                        logDebug({
                            label: "apButtonLoaded",
                            data: resp.reason
                        });
                    }
                }
            };
            function setAPPayload(value) {
                document.getElementById('ap-payload').value = value;
                showHide("divAPPayload", value);
            }

            function showHide(elem, toShow) {
                if (typeof(elem) === "string") {
                    elem = document.getElementById(elem);
                }
                if (elem) {
                    toShow ? elem.classList.remove("hidden") : elem.classList.add("hidden");
                }
            }
            function getAmount () {
                return roundToNumber(document.getElementById("amount").value || "0", 2);
            }

            const click2payRequest = {
                paymentPrefill: function(){
                    const result = {
                        merchantRequestId: "Merchant defined request ID",
                        currencyCode: "USD",
                        description: "...corp Product",
                        orderId: "Merchant defined order ID",
                        promoCode: "Merchant defined promo code",
                        subtotal: roundTo(getAmount(), 2),
                        shippingHandling: "2.00",
                        tax: "2.00",
                        discount: "1.00",
                        giftWrap: "2.00",
                        misc: "1.00",
                        setTotal:  function() {
                            this.total = roundTo(
                                roundToNumber(this.subtotal, 2)
                                + roundToNumber(this.shippingHandling, 2)
                                + roundToNumber(this.tax, 2)
                                + roundToNumber(this.giftWrap, 2)
                                + roundToNumber(this.misc, 2)
                                - roundToNumber(this.discount, 2)
                            , 2);
                            delete this.setTotal;
                            return this;
                        },
                    }.setTotal();
                    logDebug({
                        label: "paymentPrefill",
                        data: result
                    });
                    return result;
                },
                paymentCallback: function (payload) {
                    click2payRequest.setPayload(payload);
                },
                setPayload: function (value) {
                    document.getElementById('c2p-payload').value = JSON.stringify(value, null, 2);
                    showHide("divC2PPayload", value);
                }
            }

            const agrRequest = {                
                showToken: function(resp) {
                    let msg = null;
                    if (!resp) {
                        msg = "Failed to load token. No Response";
                    } else if (resp.status !== iStatus.success) {
                        msg = "Failed to load token. "+resp.statusText || "No Error description available";
                    } else if (!resp.token) {
                        msg = "Failed to load token. No Token available";
                    } else {
                        msg = "<b>"+resp.token+"</b>";
                    }
                    if (!this.lbToken) {
                        this.lbToken = document.getElementById("agr-token");
                    } 
                    this.lbToken.innerHTML = msg;
                    showHide("divAgr", true);
                }
            }
        </script>
@endsection