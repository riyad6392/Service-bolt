<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
<link rel="shortcut icon" href="{{url('/')}}/uploads/serviceboltfavicon.png" />
<link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
  .input-container input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: .75rem;
    position: relative;
    width: 100%;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}
</style>
    <title></title>
</head>
<body style="background: black;">



    <div class="row g-0">
        <div class="col-lg-5 none-sign" style="height: 125vh">
            <div class="bg-sign"></div>
        </div>
        <div class="col-lg-7 d-flex align-items-center justify-content-center">
      <div class="box-size-p p-150">
            
                
<img src="{{url('/')}}/images/logo.png" class="logo-signin"> 
<div class="d-flex align-items-center justify-content-center h-95-w mt-4">          
<div class="form-box-s">
<div>
    <h4 class="text-white">Sign Up</h4>
    <p class="text-white"></p>
</div>
<form method="POST" action="{{ route('signupcomplete1') }}">
    @csrf 
    <div class="row">
         
          <input type="hidden" name="price" id="price" value="{{ Session::get('price') }}
">
        <div class="col-md-6">
          <div class="mb-3 position-relative">
            <i class="fa fa-user"></i>
            <input type="text" class="form-control padding @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus  placeholder="First name">
          
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3 position-relative">
           <i class="fa fa-user"></i>
            <input type="text" class="form-control padding @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus  placeholder="Last name">
          </div>
        </div>
        <div class="col-md-12">
          <div class="mb-3 position-relative">
        <i class="fa fa-building-o"></i>
            <input type="text" class="form-control padding @error('companyname') is-invalid @enderror" name="companyname" value="{{ old('companyname') }}" required autocomplete="companyname" autofocus  placeholder="Company Name">
          </div>
        </div>
        <div class="col-md-12">
          <div class="mb-3 position-relative">
          <i class="fa fa-envelope" aria-hidden="true"></i>
           <input id="email" type="email" class="form-control padding @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
          </div>
          @error('email')
              <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
           @enderror
        </div>
        <div class="col-md-6">
          <div class="mb-3 position-relative">
            <i class="fa fa-lock"></i>
            <input type="password" class="form-control padding @error('password') is-invalid @enderror" name="password" id="password" value="{{ old('password') }}" placeholder="Password" required="">
          </div>
        </div>
        @error('password')
          <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
        @enderror
        <div class="col-md-6">
          <div class="mb-3 position-relative">
           <i class="fa fa-lock" aria-hidden="true"></i>
            <input type="password" class="form-control padding @error('confirmpassword') is-invalid @enderror" name="confirmpassword" id="confirmpassword" value="{{ old('confirmpassword') }}" placeholder="Confirm Password" required="">
          </div>
        </div>
        @error('confirmpassword')
          <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
        @enderror
        <div class="col-md-6">
          <div class="mb-3 position-relative">
            <i class="fa fa-phone"></i>
            <input type="text" class="form-control padding @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus  placeholder="Phone" onkeypress="return checkPhone(event)" maxlength="12">
           
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3 position-relative">
           <i class="fa fa-credit-card" aria-hidden="true"></i>
            <input type="text" class="form-control padding @error('cardnumber') is-invalid @enderror" name="cardnumber" id="cardnumber" value="{{ old('cardnumber') }}" required autocomplete="cardnumber" autofocus  placeholder="Credit Card Number" onkeypress="return checkDigit(event)">
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="mb-3 position-relative">
            <i class="fa fa-calendar" ></i>
            <input type="text" class="form-control padding @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" placeholder="Exp. Date" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}" onkeydown="return false;">
            <!-- <input type="text" placeholder="Exp. Date"
          onfocus="(this.type='date')"> -->
           
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3 position-relative">
            <i class="fa fa-lock" aria-hidden="true"></i>
            <input type="text" class="form-control padding @error('securitycode') is-invalid @enderror" name="securitycode" value="{{ old('securitycode') }}" placeholder="Security Code" maxlength="3" onkeypress="return checkDigit1(event)">
          </div>
        </div>
          <div class="mb-3 form-check" style="margin: 0 14px;">
            <input type="checkbox" class="form-check-input" id="accept_terms_conditions" name="accept_terms_conditions" required>
            <label class="form-check-label text-white" for="accept_terms_conditions" >Agree to <a href="#" class="" data-toggle="modal" data-target="#exampleModalLong">terms and conditions.</a></label>
          @error('accept_terms_conditions')
              <div class="invalid-feedback">{{ $message }}</div>
           @enderror
          </div>
          <div class="col-md-12">
         <button type="submit" class="btn btn-primary subit" >Sign Up</button>
        </div>
        
    </div>
</form>
  <div class="mt-3">
    <p class="text-white">Already have an account?<a href="{{url('login')}}" style="color: #FAED61;text-decoration: none;"> Sign In </a></p>
  </div>


  

            </div>
            </div>
        </div>
    </div>
  <!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
<h2 style="text-align: center;"><b>TERMS AND CONDITIONS</b></h2>
<p>Last updated: 2022-03-15</p>
<p>1. <b>Introduction</b></p>
<p>Welcome to <b>ServiceBolt</b> (“Company”, “we”, “our”, “us”)!</p>
<p>These Terms of Service (“Terms”, “Terms of Service”) govern your use of our website located at <b>http://servicebolt.digimonkindia.com/</b> (together or individually “Service”) operated by <b>ServiceBolt</b>.</p>
<p>Our Privacy Policy also governs your use of our Service and explains how we collect, safeguard and disclose information that results from your use of our web pages.</p>
<p>Your agreement with us includes these Terms and our Privacy Policy (“Agreements”). You acknowledge that you have read and understood Agreements, and agree to be bound of them.</p>
<p>If you do not agree with (or cannot comply with) Agreements, then you may not use the Service, but please let us know by emailing at <b>support@servicebolt.com</b> so we can try to find a solution. These Terms apply to all visitors, users and others who wish to access or use Service.</p>
<p>2. <b>Communications</b></p>
<p>By using our Service, you agree to subscribe to newsletters, marketing or promotional materials and other information we may send. However, you may opt out of receiving any, or all, of these communications from us by following the unsubscribe link or by emailing at support@servicebolt.com.</p>
<p>3. <b>Purchases</b></p><p>If you wish to purchase any product or service made available through Service (“Purchase”), you may be asked to supply certain information relevant to your Purchase including but not limited to, your credit or debit card number, the expiration date of your card, your billing address, and your shipping information.</p><p>You represent and warrant that: (i) you have the legal right to use any card(s) or other payment method(s) in connection with any Purchase; and that (ii) the information you supply to us is true, correct and complete.</p><p>We may employ the use of third party services for the purpose of facilitating payment and the completion of Purchases. By submitting your information, you grant us the right to provide the information to these third parties subject to our Privacy Policy.</p><p>We reserve the right to refuse or cancel your order at any time for reasons including but not limited to: product or service availability, errors in the description or price of the product or service, error in your order or other reasons.</p><p>We reserve the right to refuse or cancel your order if fraud or an unauthorized or illegal transaction is suspected.</p>
<p>4. <b>Contests, Sweepstakes and Promotions</b></p>
<p>Any contests, sweepstakes or other promotions (collectively, “Promotions”) made available through Service may be governed by rules that are separate from these Terms of Service. If you participate in any Promotions, please review the applicable rules as well as our Privacy Policy. If the rules for a Promotion conflict with these Terms of Service, Promotion rules will apply.</p>

<p>5. <b>Refunds</b></p><p>We issue refunds for Contracts within <b>30 days</b> of the original purchase of the Contract.</p>
<p>6. <b>Content</b></p><p>Content found on or through this Service are the property of ServiceBolt or used with permission. You may not distribute, modify, transmit, reuse, download, repost, copy, or use said Content, whether in whole or in part, for commercial purposes or for personal gain, without express advance written permission from us.</p>
<p>7. <b>Prohibited Uses</b></p>
<p>You may use Service only for lawful purposes and in accordance with Terms. You agree not to use Service:</p>
<p>0.1. In any way that violates any applicable national or international law or regulation.</p>
<p>0.2. For the purpose of exploiting, harming, or attempting to exploit or harm minors in any way by exposing them to inappropriate content or otherwise.</p>
<p>0.3. To transmit, or procure the sending of, any advertising or promotional material, including any “junk mail”, “chain letter,” “spam,” or any other similar solicitation.</p>
<p>0.4. To impersonate or attempt to impersonate Company, a Company employee, another user, or any other person or entity.</p>
<p>0.5. In any way that infringes upon the rights of others, or in any way is illegal, threatening, fraudulent, or harmful, or in connection with any unlawful, illegal, fraudulent, or harmful purpose or activity.</p>
<p>0.6. To engage in any other conduct that restricts or inhibits anyone’s use or enjoyment of Service, or which, as determined by us, may harm or offend Company or users of Service or expose them to liability.</p>
<p>Additionally, you agree not to:</p>
<p>0.1. Use Service in any manner that could disable, overburden, damage, or impair Service or interfere with any other party’s use of Service, including their ability to engage in real time activities through Service.</p>
<p>0.2. Use any robot, spider, or other automatic device, process, or means to access Service for any purpose, including monitoring or copying any of the material on Service.</p>
<p>0.3. Use any manual process to monitor or copy any of the material on Service or for any other unauthorized purpose without our prior written consent.</p>
<p>0.4. Use any device, software, or routine that interferes with the proper working of Service.</p>
<p>0.5. Introduce any viruses, trojan horses, worms, logic bombs, or other material which is malicious or technologically harmful.</p>
<p>0.6. Attempt to gain unauthorized access to, interfere with, damage, or disrupt any parts of Service, the server on which Service is stored, or any server, computer, or database connected to Service.</p>
<p>0.7. Attack Service via a denial-of-service attack or a distributed denial-of-service attack.</p>
<p>0.8. Take any action that may damage or falsify Company rating.</p>
<p>0.9. Otherwise attempt to interfere with the proper working of Service.</p>
<p>8. <b>Analytics</b></p>
<p>We may use third-party Service Providers to monitor and analyze the use of our Service.</p>
<p>9. <b>No Use By Minors</b></p>
<p>Service is intended only for access and use by individuals at least eighteen (18) years old. By accessing or using Service, you warrant and represent that you are at least eighteen (18) years of age and with the full authority, right, and capacity to enter into this agreement and abide by all of the terms and conditions of Terms. If you are not at least eighteen (18) years old, you are prohibited from both the access and usage of Service.</p>

<p>10. <b>Intellectual Property</b></p>
<p>Service and its original content (excluding Content provided by users), features and functionality are and will remain the exclusive property of ServiceBolt and its licensors. Service is protected by copyright, trademark, and other laws of  and foreign countries. Our trademarks may not be used in connection with any product or service without the prior written consent of ServiceBolt.</p>
<p>11. <b>Copyright Policy</b></p>
<p>We respect the intellectual property rights of others. It is our policy to respond to any claim that Content posted on Service infringes on the copyright or other intellectual property rights (“Infringement”) of any person or entity.</p>
<p>If you are a copyright owner, or authorized on behalf of one, and you believe that the copyrighted work has been copied in a way that constitutes copyright infringement, please submit your claim via email to support@servicebolt.com, with the subject line: “Copyright Infringement” and include in your claim a detailed description of the alleged Infringement as detailed below, under “DMCA Notice and Procedure for Copyright Infringement Claims”</p>
<p>You may be held accountable for damages (including costs and attorneys’ fees) for misrepresentation or bad-faith claims on the infringement of any Content found on and/or through Service on your copyright.</p>
<p>12. <b>DMCA Notice and Procedure for Copyright Infringement Claims</b></p>
<p>You may submit a notification pursuant to the Digital Millennium Copyright Act (DMCA) by providing our Copyright Agent with the following information in writing (see 17 U.S.C 512(c)(3) for further detail):</p>
<p>0.1. an electronic or physical signature of the person authorized to act on behalf of the owner of the copyright’s interest;</p>
<p>0.2. a description of the copyrighted work that you claim has been infringed, including the URL (i.e., web page address) of the location where the copyrighted work exists or a copy of the copyrighted work;</p>
<p>0.3. identification of the URL or other specific location on Service where the material that you claim is infringing is located;</p>
<p>0.4. your address, telephone number, and email address;</p>
<p>0.5. a statement by you that you have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law;</p>
<p>0.6. a statement by you, made under penalty of perjury, that the above information in your notice is accurate and that you are the copyright owner or authorized to act on the copyright owner’s behalf.</p>
<p>You can contact our Copyright Agent via email at support@servicebolt.com.</p>
<p>13. <b>Error Reporting and Feedback</b></p>
<p>You may provide us either directly at support@servicebolt.com or via third party sites and tools with information and feedback concerning errors, suggestions for improvements, ideas, problems, complaints, and other matters related to our Service (“Feedback”). You acknowledge and agree that: (i) you shall not retain, acquire or assert any intellectual property right or other right, title or interest in or to the Feedback; (ii) Company may have development ideas similar to the Feedback; (iii) Feedback does not contain confidential information or proprietary information from you or any third party; and (iv) Company is not under any obligation of confidentiality with respect to the Feedback. In the event the transfer of the ownership to the Feedback is not possible due to applicable mandatory laws, you grant Company and its affiliates an exclusive, transferable, irrevocable, free-of-charge, sub-licensable, unlimited and perpetual right to use (including copy, modify, create derivative works, publish, distribute and commercialize) Feedback in any manner and for any purpose.</p>
<p>14. <b>Links To Other Web Sites</b></p>
<p>Our Service may contain links to third party web sites or services that are not owned or controlled by ServiceBolt.</p>
<p>ServiceBolt has no control over, and assumes no responsibility for the content, privacy policies, or practices of any third party web sites or services. We do not warrant the offerings of any of these entities/individuals or their websites.</p>
<p>For example, the outlined <a href="https://policymaker.io/terms-and-conditions/">Terms of Use</a> have been created using <a href="https://policymaker.io/">PolicyMaker.io</a>, a free web application for generating high-quality legal documents. PolicyMaker’s <a href="https://policymaker.io/terms-and-conditions/">Terms and Conditions generator</a> is an easy-to-use free tool for creating an excellent standard Terms of Service template for a website, blog, e-commerce store or app.</p>
<p>YOU ACKNOWLEDGE AND AGREE THAT COMPANY SHALL NOT BE RESPONSIBLE OR LIABLE, DIRECTLY OR INDIRECTLY, FOR ANY DAMAGE OR LOSS CAUSED OR ALLEGED TO BE CAUSED BY OR IN CONNECTION WITH USE OF OR RELIANCE ON ANY SUCH CONTENT, GOODS OR SERVICES AVAILABLE ON OR THROUGH ANY SUCH THIRD PARTY WEB SITES OR SERVICES.</p>
<p>WE STRONGLY ADVISE YOU TO READ THE TERMS OF SERVICE AND PRIVACY POLICIES OF ANY THIRD PARTY WEB SITES OR SERVICES THAT YOU VISIT.</p>
<p>15. <b>Disclaimer Of Warranty</b></p>
<p>THESE SERVICES ARE PROVIDED BY COMPANY ON AN “AS IS” AND “AS AVAILABLE” BASIS. COMPANY MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, AS TO THE OPERATION OF THEIR SERVICES, OR THE INFORMATION, CONTENT OR MATERIALS INCLUDED THEREIN. YOU EXPRESSLY AGREE THAT YOUR USE OF THESE SERVICES, THEIR CONTENT, AND ANY SERVICES OR ITEMS OBTAINED FROM US IS AT YOUR SOLE RISK.</p>
<p>NEITHER COMPANY NOR ANY PERSON ASSOCIATED WITH COMPANY MAKES ANY WARRANTY OR REPRESENTATION WITH RESPECT TO THE COMPLETENESS, SECURITY, RELIABILITY, QUALITY, ACCURACY, OR AVAILABILITY OF THE SERVICES. WITHOUT LIMITING THE FOREGOING, NEITHER COMPANY NOR ANYONE ASSOCIATED WITH COMPANY REPRESENTS OR WARRANTS THAT THE SERVICES, THEIR CONTENT, OR ANY SERVICES OR ITEMS OBTAINED THROUGH THE SERVICES WILL BE ACCURATE, RELIABLE, ERROR-FREE, OR UNINTERRUPTED, THAT DEFECTS WILL BE CORRECTED, THAT THE SERVICES OR THE SERVER THAT MAKES IT AVAILABLE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS OR THAT THE SERVICES OR ANY SERVICES OR ITEMS OBTAINED THROUGH THE SERVICES WILL OTHERWISE MEET YOUR NEEDS OR EXPECTATIONS.</p>
<p>COMPANY HEREBY DISCLAIMS ALL WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, STATUTORY, OR OTHERWISE, INCLUDING BUT NOT LIMITED TO ANY WARRANTIES OF MERCHANTABILITY, NON-INFRINGEMENT, AND FITNESS FOR PARTICULAR PURPOSE.</p>
<p>THE FOREGOING DOES NOT AFFECT ANY WARRANTIES WHICH CANNOT BE EXCLUDED OR LIMITED UNDER APPLICABLE LAW.</p>
<p>16. <b>Limitation Of Liability</b></p>
<p>EXCEPT AS PROHIBITED BY LAW, YOU WILL HOLD US AND OUR OFFICERS, DIRECTORS, EMPLOYEES, AND AGENTS HARMLESS FOR ANY INDIRECT, PUNITIVE, SPECIAL, INCIDENTAL, OR CONSEQUENTIAL DAMAGE, HOWEVER IT ARISES (INCLUDING ATTORNEYS’ FEES AND ALL RELATED COSTS AND EXPENSES OF LITIGATION AND ARBITRATION, OR AT TRIAL OR ON APPEAL, IF ANY, WHETHER OR NOT LITIGATION OR ARBITRATION IS INSTITUTED), WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE, OR OTHER TORTIOUS ACTION, OR ARISING OUT OF OR IN CONNECTION WITH THIS AGREEMENT, INCLUDING WITHOUT LIMITATION ANY CLAIM FOR PERSONAL INJURY OR PROPERTY DAMAGE, ARISING FROM THIS AGREEMENT AND ANY VIOLATION BY YOU OF ANY FEDERAL, STATE, OR LOCAL LAWS, STATUTES, RULES, OR REGULATIONS, EVEN IF COMPANY HAS BEEN PREVIOUSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. EXCEPT AS PROHIBITED BY LAW, IF THERE IS LIABILITY FOUND ON THE PART OF COMPANY, IT WILL BE LIMITED TO THE AMOUNT PAID FOR THE PRODUCTS AND/OR SERVICES, AND UNDER NO CIRCUMSTANCES WILL THERE BE CONSEQUENTIAL OR PUNITIVE DAMAGES. SOME STATES DO NOT ALLOW THE EXCLUSION OR LIMITATION OF PUNITIVE, INCIDENTAL OR CONSEQUENTIAL DAMAGES, SO THE PRIOR LIMITATION OR EXCLUSION MAY NOT APPLY TO YOU.</p>
<p>17. <b>Termination</b></p>
<p>We may terminate or suspend your account and bar access to Service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not limited to a breach of Terms.</p>
<p>If you wish to terminate your account, you may simply discontinue using Service.</p>
<p>All provisions of Terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability.</p>
<p>18. <b>Governing Law</b></p>
<p>These Terms shall be governed and construed in accordance with the laws of United States, which governing law applies to agreement without regard to its conflict of law provisions.</p>
<p>Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights. If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect. These Terms constitute the entire agreement between us regarding our Service and supersede and replace any prior agreements we might have had between us regarding Service.</p>
<p>19. <b>Changes To Service</b></p>
<p>We reserve the right to withdraw or amend our Service, and any service or material we provide via Service, in our sole discretion without notice. We will not be liable if for any reason all or any part of Service is unavailable at any time or for any period. From time to time, we may restrict access to some parts of Service, or the entire Service, to users, including registered users.</p>
<p>20. <b>Amendments To Terms</b></p>
<p>We may amend Terms at any time by posting the amended terms on this site. It is your responsibility to review these Terms periodically.</p>
<p>Your continued use of the Platform following the posting of revised Terms means that you accept and agree to the changes. You are expected to check this page frequently so you are aware of any changes, as they are binding on you.</p>
<p>By continuing to access or use our Service after any revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, you are no longer authorized to use Service.</p>
<p>21. <b>Waiver And Severability</b></p>
<p>No waiver by Company of any term or condition set forth in Terms shall be deemed a further or continuing waiver of such term or condition or a waiver of any other term or condition, and any failure of Company to assert a right or provision under Terms shall not constitute a waiver of such right or provision.</p>
<p>If any provision of Terms is held by a court or other tribunal of competent jurisdiction to be invalid, illegal or unenforceable for any reason, such provision shall be eliminated or limited to the minimum extent such that the remaining provisions of Terms will continue in full force and effect.</p>
<p>22. <b>Acknowledgement</b></p>
<p>BY USING SERVICE OR OTHER SERVICES PROVIDED BY US, YOU ACKNOWLEDGE THAT YOU HAVE READ THESE TERMS OF SERVICE AND AGREE TO BE BOUND BY THEM.</p>
<p>23. <b>Contact Us</b></p>
<p>Please send your feedback, comments, requests for technical support by email: <b>support@servicebolt.com</b>.</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">
  function cc_format(value) {
  var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
  var matches = v.match(/\d{4,16}/g);
  var match = matches && matches[0] || ''
  var parts = []
  for (i=0, len=match.length; i<len; i+=4) {
    parts.push(match.substring(i, i+4))
  }
  if (parts.length) {
    return parts.join(' ')
  } else {
    return value
  }
}

onload = function() {
  document.getElementById('cardnumber').oninput = function() {
    this.value = cc_format(this.value)
  }
}
function checkDigit(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
}

function checkDigit1(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
}

function checkPhone(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
        return false;
    }

    return true;
}
</script>