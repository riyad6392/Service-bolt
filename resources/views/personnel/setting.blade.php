@extends('layouts.workerheader') @section('content')
<style type="text/css">
.form-control.form-control-2 input {
  border: none;
  box-sizing: border-box;
  outline: 0;
  padding: .75rem;
  position: relative;
  width: 100%;
  display: block;
}

.form-control.form-control-2[type="date"]::-webkit-calendar-picker-indicator {
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
  display: block;
}
.third-section ul {
    padding: 0;
}
.payment-page .input-group{
  width: auto;
}
.selection-div li{
    padding: 10px 0;
}.payment-page input[type='text'] {
  width: 100px;
}
.selection-div li .radio-div {
    font-size: 18px!important;
    font-weight: 500;
    width: 35%;
}
.selection-div li .container-checkbox{
   font-size: 18px!important;
    font-weight: 500;
    width: 57%;
    color: #232322;
}
.selection-div li {
    padding: 10px 0;
    width: 470px;
}
.radio-div {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 20px;
    font-weight: 600;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #232322!important;
}
.icon-show {
    background-color: #f5e87c;
    border-radius: 100%;
    padding: 2px;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
<div class="">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="side-h3">
          <h3>Settings</h3> </div>
      </div> @if(Session::has('success'))
      <div class="alert alert-success" id="selector"> {{Session::get('success')}} </div> @endif
      <form method="post" action="{{ route('worker.updatesetting') }}" enctype="multipart/form-data"> @csrf
        <div class="col-lg-12 mb-4">
          <div class="card admin-setting">
            <div class="card-body">
              <h5 class="mb-4">Basic Info</h5>
              <div class="row">
                <div class="col-lg-9">
                  <div class="row">
                    <div class="col-lg-6 mb-3">
                      <label class="form-label">Name</label>
                      <input type="text" class="form-control form-control-2" placeholder="Name" value="{{$workerData->personnelname}}" name="personnelname" required=""> </div>
                    <div class="col-lg-6 mb-3">
                      <label class="form-label">Address</label>
                      <input type="text" class="form-control form-control-2" placeholder="Address" value="{{$workerData->address}}" name="address" id="address" required=""> </div>
                    <div class="col-lg-12 mb-3">
                      <label class="form-label">Phone</label>
                      <input type="text" class="form-control form-control-2" placeholder="Phone" value="{{$workerData->phone}}" name="phone" required="" onkeypress="return checkPhone(event)" maxlength="12"> </div>
                    <div class="col-lg-6 mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control form-control-2" placeholder="Email Id" value="{{$workerData->email}}" name="email" readonly=""> </div>
                    <div class="col-lg-6 mb-3">
                      <label class="form-label">Hired Date</label>
                     <p class="mt-3">06/28/2022</p></div>
                    

                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="avatar-upload">
                    <div class="avatar-edit">
                      <input type='file' id="imageUpload" name="imageUpload" accept=".png, .jpg, .jpeg">
                      <label for="imageUpload"></label>
                    </div>
                    <div class="avatar-preview"> @if($workerData->image!=null)
                      <div id="imagePreview" style="background-image: url('{{url('uploads/personnel/thumbnail/')}}/{{$workerData->image}}');"> </div> @else @php $dimage = url('/').'/uploads/servicebolt-noimage.png'; @endphp
                      <div id="imagePreview" style="background-image: url('{{$dimage}}');"> </div> @endif </div>
                    <div style="color: #999999;margin-bottom: 6px;position: relative;left: 10px;width: 100px;">Approximate Image Size : 122 * 122</div>
                  </div>
                </div>
              </div>
              <hr/>
              <div class="row">
                <div class="col-lg-12 mt-4 text-center">
                  <input class="btn btn-add w-25 w-none" type="submit" value="Save Changes"> </div>
              </div>
              <!-- -==-=--=-=-=-start new form-=-=-=-=- -->
              <div class="row content payment-page">

               
                <div class="col-md-12 mt-3">
                  <div class="">
                     <h5 class="mb-4">Payment Info</h5>
                    <!-- <p style="color: #B0B7C3;">Lorem ipsum dolor sit amet</p> -->
                  </div></div><hr>
                  <div class="col-md-6 mt-3">
                   <div class="settings-payement">
                    <h5 class="mb-4 ">My Payment Settings</h5> </div>
                </div>
                <div class="col-lg-6 mt-3">
                  <div class="card p-3">
                    <div class="d-flex align-items-center justify-content-between"><div class="icon-show"><i class="fa fa-money fa-2x"></i></div><select name="earning" id="earning" name="earning" style="border: 1px solid #F3F3F3;">
                        <option value="" selected>Select Duration</option>
                        <option value="1week" >1 Week</option>
                        <option value="1month">1 Month</option>
                        <option value="6month">6 Month</option>
                        <option value="1year">1 Year</option>
                      </select> </div>
                      <div class="earning-content"><h5 class="mt-4">Total Earning</h5>
                        <h3><b>$200</b></h3></div>
                  </div>
                </div>
                <div class="personal-setting">
                  @php
                    if(count($paymentdata)>0) {
                        foreach($paymentdata as $key=>$value) {
                             if($value->type == "hourly") {
                               $hchecked = "checked";
                               $hvalue =json_decode($value->content);
                               $hvalue = $hvalue[0]->hourly; 
                             }
                             if($value->paymentbase == "fixedsalary") {
                               $fixedchecked = "checked"; 
                               if($value->type=="monthlysalaryamount") {
                                $monthlysalaryamount = json_decode($value->content);
                                $monthlysalaryamount = $monthlysalaryamount[0]->monthlysalary;
                                $mchecked = "checked"; 
                               }

                               if($value->type=="bimonthlysalaryamount") {
                                $bimonthlysalaryamount = json_decode($value->content);
                                $bimonthlysalaryamount = $bimonthlysalaryamount[0]->bimonthlysalary; 
                                $bchecked = "checked"; 
                               }

                               if($value->type=="weeklysalaryamount") {
                                $weeklysalaryamount = json_decode($value->content);
                                $weeklysalaryamount = $weeklysalaryamount[0]->weeklysalary; 
                                $wchecked = "checked";
                               }

                               if($value->type=="biweeklysalaryamount") {
                                $biweeklysalaryamount = json_decode($value->content);
                                $biweeklysalaryamount = $biweeklysalaryamount[0]->biweeklysalary;
                                $bwchecked = "checked"; 
                               }

                               $fixevalue =json_decode($value->content);
                               
                             } else {
                                $fixedchecked = "";
                             }
                        }    
                    }
                  @endphp
                  <hr>
                  <div class="first-section">
                    <label class="radio-div active">Hourly Payment
                      </label>
                    <ul class="selection-div">
                      <li class="d-flex">
                        <label class="radio-div me-2">Amount Per Hour :
                         </label>
                        <p>{{@$hvalue}}</p>
                      </li>
                    </ul>
                  </div>
                  <hr>
                  <div class="second-section">
                    <label class="radio-div">Fixed Salary
                      </label>
                    <ul class="selection-div">
                      <li class="d-flex">
                        <label class="radio-div me-2">Monthly Salary Amount :
                         </span> </label>
                        <p>{{@$monthlysalaryamount}}</p>
                      </li>
                      <li class="d-flex">
                        <label class="radio-div me-2">Bi Monthly Salary Amount :
                          </label>
                       <p>{{@$bimonthlysalaryamount}}</p>
                      </li>
                      <li class="d-flex">
                        <label class="radio-div me-2">Weekly Salary Amount :
                          </label>
                        <p>{{@$weeklysalaryamount}}</p>
                      </li>
                      <li class="d-flex">
                        <label class="radio-div me-2">Bi Weekly Salary Amount :
                          </label>
                        <p>{{@$biweeklysalaryamount}}</p>
                      </li>
                    </ul>
                  </div>
                  <hr>
                  @php
                    if(count($paymentdata) > 0) {
                       
                        $commissiondata = App\Models\PaymentSetting::where('uid',$uid)->where('pid',$wid)->where('type','amount')->get();

                        if(count($commissiondata) == 0) { 
                            $commissiondata = "";
                            $type = ""; 
                        } else {
                            @$commissiondata = json_decode(@$commissiondata[0]->content,true);
                            $type = "amount";
                        }

                        $commissionpdata = App\Models\PaymentSetting::where('uid',$uid)->where('pid',$wid)->where('type','percent')->get();

                        if(count($commissionpdata) == 0) { 
                           $commissionpdata = "";
                           $type1 = ""; 
                        } else {
                            $commissionpdata = json_decode(@$commissionpdata[0]->content,true);
                            $type1 = "percent";   
                        }

                        $commissiondata1 = App\Models\PaymentSetting::select('allspvalue')->where('uid',$uid)->where('pid',$wid)->where('type','amount')->get();

                        $commissionpdata1 = App\Models\PaymentSetting::where('uid',$uid)->where('pid',$wid)->where('type','percent')->get();

                        if(@$commissiondata1[0]->allspvalue!=null) {
                            $allspvalueamount = $commissiondata1[0]->allspvalue;
                            $allspvalueamountchecked = "checked";
                        }

                        if(@$commissionpdata1[0]->allspvalue!=null) {
                            $allspvaluepercent = $commissionpdata1[0]->allspvalue;
                            $allspvaluepercentchecked = "checked";
                        }

                    @endphp
                    <!-- commission start here -->
                  <div class="third-section">
                    <label class="radio-div">Commission Basis
                     </label>
                    <div class="row">
                      <div class="col-md-6">
                        <div style="padding-left:35px">
                          <label class="radio-div ">Amount Wise
                            </label>
                             @php
                                $totlcount = count($services);
                            @endphp
                          <ul class="selection-div">
                           
                            <li class="d-flex">
                              <label class="container-checkbox active me-4">All Services/Products
                               </label>
                              <p>{{@$allspvalueamount}}</p>
                            </li>
                            
                             @foreach($services as $key => $value)
                            <li class="d-flex">
                              <label class="container-checkbox me-4">{{$value->servicename}} :
                               </label>
                               <p>{{@$commissiondata[$key][$value->servicename]}}</p>
                            </li>
                            @endforeach
                            @foreach($products as $key1 => $product)
                            <li class="d-flex">
                              <label class="container-checkbox me-4">{{$product->productname}} :
                              </label>
                              <p>{{@$commissiondata[$key1+$totlcount][$product->productname]}}</p>
                            </li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="radio-div">Percent Wise
                          </label>
                        <ul class="selection-div">
                          <li class="d-flex">
                            <label class="container-checkbox me-4">All Services/Products
                             </label>
                             <p>{{@$allspvaluepercent}}</p>
                          </li>
                        @foreach($services as $key => $value)
                          <li class="d-flex">
                            <label class="container-checkbox me-4">{{$value->servicename}} :
                               </label>
                              @if($commissionpdata!="")
                                <p>{{@$commissionpdata[$key][$value->servicename]}}</p>
                              @else  
                             <p>0</p>
                              @endif
                          </li>
                          @endforeach
                          @foreach($products as $key1 => $product)
                          <li class="d-flex">
                            <label class="container-checkbox me-4">{{$product->productname}} :
                              </label>
                            <p>{{@$commissionpdata[$key1+$totlcount][$product->productname]}}</p>
                          </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  </div>
                @php
                  } else {
                    if(count($commissiondata1)>0) {
                      $allspvalueamount = $commissiondata1[0]->allspvalue;
                      $allspvalueamountchecked = "checked";
                    }
                    if(count($commissionpdata1)>0) {
                        $allspvaluepercent = $commissionpdata1[0]->allspvalue;
                        $allspvaluepercentchecked = "checked";
                    }
                @endphp
                <!-- commision end here -->
                  <div class="third-section">
                    <label class="radio-div">Commission Basis
                     </label>
                    <div class="row">
                      <div class="col-md-6">
                        <div style="padding-left:35px">
                          <label class="radio-div ">Amount Wise
                            </label>
                             @php
                                $totlcount = count($services);
                            @endphp
                          <ul class="selection-div">
                           
                            <li class="d-flex">
                              <label class="container-checkbox active me-4">All Services/Products
                               </label>
                              <p>{{@$allspvalueamount}}</p>
                            </li>
                            
                             @foreach($services as $key => $value)
                            <li class="d-flex">
                              <label class="container-checkbox me-4">{{$value->servicename}} :
                               </label>
                               <p>{{@$commissiondata[$key][$value->servicename]}}</p>
                            </li>
                            @endforeach
                            @foreach($products as $key1 => $product)
                            <li class="d-flex">
                              <label class="container-checkbox me-4">{{$product->productname}} :
                              </label>
                              <p>{{@$commissiondata[$key1+$totlcount][$product->productname]}}</p>
                            </li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="radio-div">Percent Wise
                          </label>
                        <ul class="selection-div">
                          <li class="d-flex">
                            <label class="container-checkbox me-4">All Services/Products
                             </label>
                             <p>{{@$allspvaluepercent}}</p>
                          </li>
                        @foreach($services as $key => $value)
                          <li class="d-flex">
                            <label class="container-checkbox me-4">{{$value->servicename}} :
                               </label>
                             <p>{{@$commissionpdata[$key][$value->servicename]}}</p>
                          </li>
                          @endforeach
                          @foreach($products as $key1 => $product)
                          <li class="d-flex">
                            <label class="container-checkbox me-4">{{$product->productname}} :
                              </label>
                            <p>{{@$commissionpdata[$key1+$totlcount][$product->productname]}}</p>
                          </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  </div>
                @php
                  }
                @endphp
                </div>

              </div>
              <!-- -=-=-=-=-=end new form-=-=-=-=-= -->
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div> 
@endsection 
@section('script')
<script type="text/javascript">
function checkPhone(event) {
  var code = (event.which) ? event.which : event.keyCode;
  if((code < 48 || code > 57) && (code > 31)) {
    return false;
  }
  return true;
}
$('#selector').delay(2000).fadeOut('slow');
$(document).ready(function() {
  $('#imageUpload').bind('change', function() {
    var a = (this.files[0].size);
    //alert(a);
    if(a > 2000000) {
      swal({
        title: "Image Large?",
        text: "Ïmage should not be larger than 2 mb!",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ok",
        closeOnConfirm: false,
        closeOnCancel: false
      }, function(isConfirm) {
        if(isConfirm) {
          location.reload();
        }
      });
    }
  });
});
</script> @endsection