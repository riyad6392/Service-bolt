@extends('layouts.header')
@section('content')
<style type="text/css">
  /*payment css*/
  .payment_left h6 {
    font-size: 17px;
  }

  .save_btn {
    border: 2px solid #c7c3fb;
    border-radius: 3px;
    color: #6558F5;
    font-weight: 600;
    padding: 8px 15px;
  }

  .save_btn:focus {
    outline: none;
    box-shadow: none;
  }

  .save_btn:hover {
    color: #6558F5;
  }

  .save_btn1 {
    border: 2px solid #c7c3fb;
    border-radius: 3px;
    color: #6558F5;
    font-weight: 600;
    padding: 8px 15px;
  }

  .save_btn1:focus {
    outline: none;
    box-shadow: none;
  }

  .save_btn1:hover {
    color: #6558F5;
  }

  .form-select:focus {
    border-color: none;
    outline: 0;
    box-shadow: none;
  }

  .dataTables_length {
    display: none;
  }

  .dataTables_info {
    display: none;
  }

  .dataTables_filter {
    display: none;
  }

  select option {
    background-color: #fff;
    /* Background color for options */
    color: #333;
    /* Text color for options */
    margin-top: 10px;
  }

  /* Hover styling for select options */
  option:focus {
    background-color: yellow !important;
  }

  option:active {
    background-color: red !important;
  }

  option:visited {
    background-color: yellowgreen !important;
  }

  .input_item {
    border: 2px solid #d4d4d4;
    border-radius: 3px;
    padding: 7px;
  }

  .input_item:focus {
    box-shadow: none;
    outline: none;
  }

  label {
    font-weight: 600;
  }

  table tbody tr {
    border-radius: 8px;
    margin-bottom: 20px;
    position: relative;
  }

  table tr td,
  table tr th {
    border: none !important;
    color: rgb(41, 56, 69) !important;
    padding: 15px 30px;
    background: lightgray;
  }

  table tr th {
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 300;
    color: #b0b7c3;
    color: b0b7c3;
  }

  table tr td {
    padding: 0 !important;
    /*font-weight: 600;*/
  }

  .middle_box .d-flex {
    gap: 10px;
  }

  .top_content {}

  .text_center {
    text-align: center;
  }

  .payment_left h4 {
    font-size: 21px;
  }

  .box {
    border-radius: 3px;
    min-height: 200px;
  }

  .modal-header,
  .modal-footer {
    border: none;
  }

  .modal-footer {
    justify-content: start;
  }

  .modal-footer button {
    display: inline;
  }

  .modal_table input {
    margin-bottom: 5px;
  }

  .box table tr td {
    padding: 0.5rem 0.5rem !important;
  }

  .form-check-input:checked {
    background-color: #fee200 !important;
    border-color: #fee200 !important;
    width: 20px !important;
    height: 20px !important;
  }

  /* customCheckBox */
  input[type="checkbox"] {
    width: 20px !important;
    height: 20px !important;
    border-color: #fee200 !important;
  }

  tr.tableHover {
    border: 1px solid #dee2e6;
    height: 65px;
    vertical-align: middle;
  }

  .tableHover:hover .tableColorHover {
    color: #29dbba !important;
  }
</style>
<div class="container mt-3">
  <div class="row">
    @if(Session::has('success'))
    <div class="alert alert-success" id="selector">
      {{Session::get('success')}}
    </div>
    @endif
    @if(Session::has('error'))
    <div class="alert alert-danger" id="selector">
      {{Session::get('error')}}
    </div>
    @endif
    <h3 class="mb-3">Collect Payments</h3>
    <div class="col-lg-4 col-md-4">
      <div class="card">
        <div class="card-body p-3 py-5">
          <div class="payment_left">
            <h6>Select Customer</h6>
            <select class="form-select input_item puser" aria-label="Default select example" id="cid">
              @foreach($customerData as $key => $value)
              <option value="{{$value->customerid}}" @if(@$customerid==$value->customerid) selected @endif>{{$value->customername}}</option>
              @endforeach
            </select>

            <div class="form-group mt-4">
              <h6>Select Payment Method</h6>
              <select class="form-select input_item" aria-label="Default select example" id="mySelect">
                <option value="Cash" selected>Cash</option>
                <option value="Check">Check</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Gift Card">Gift Card</option>
              </select>
            </div>

            <form method="put" action="{{ url('company/billing/updatenew') }}" enctype="multipart/form-data" id="saveform">
              @csrf

              <div class="middle_box">
                <div class="row my-3">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <h6>Payment Amount</h6>
                      <input type="text" class="form-control input_item" name="amount" id="amount" value="{{$price}}" readonly>
                      <input type="hidden" name="ticketid" id="ticketid" value="{{$id}}">
                      <input type="hidden" name="personnelid" id="personnelid" value="{{$personnelid}}">
                      <input type="hidden" name="customername" id="customername" value="{{$customername}}">
                      <input type="hidden" name="customerid" id="customerid" value="{{$customerid}}">
                      <input type="hidden" class="form-control form-control-2" name="method" id="method" value="" placeholder="">
                    </div>
                  </div>
                </div>

                <div class="form-check customCheckBox">
                  <input class="form-check-input input_item" type="checkbox" value="" id="myCheckbox" />
                  <label class="form-check-label" for="myCheckbox">
                    <h6>Make partial payment on invoices</h6>
                  </label>
                </div>
              </div>

              <div id="mycheck" style="display:none;">
                <div class="mb-3">
                  <label class="form-label">Check Number</label>
                  <input type="number" class="form-control" name="check_no" id="check_no" placeholder="" required>
                </div>
              </div>

              <div class="mt-2">
                <button type="button" class="btn add-btn-yellow save_pay">Save Payment</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md-8 ps-0">
      <div class="payment_right payment_left">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-12 col-md-12" style="overflow:hidden; overflow-x: auto;">
                <h6>Open Invoices</h6>
                <div class="box">
                  <table id="example" class="table">
                    <thead>
                      <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Amount Owned</th>

                      </tr>
                    </thead>
                    <tbody>
                      @foreach($allinvoices as $key=>$value)
                      @php
                      $ids = explode(',',$id);
                      if(in_array($value->id,$ids)) {
                      $checked="checked";
                      } else {
                      $checked="";
                      }
                      @endphp
                      @if($value->price - $value->amount_paid !=0)
                      <tr>
                        <td>
                          <div class="form-check">
                            <input class="form-check-input input_item invoicecheck" type="checkbox" id="radio-{{$value->id}}" data-id="{{$value->id}}" name="switch1" value="yes" {{$checked}} />
                            <label class="form-check-label" for="radio-{{$value->id}}">
                              <h6>#{{$value->id}}</h6>
                            </label>
                          </div>
                        </td>
                        <td>{{$value->givenstartdate}}</td>
                        <td class="text_center">{{$value->price - $value->amount_paid}}</td>

                      </tr>
                      @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @if(isset($overpaidData))
                @if(count($overpaidData)>0)
                <br>
                <h6 class="text_center">Over Paid Invoices</h6>
                <div class="box">
                  <table class="table">
                    <tr>
                      <th>Invoice #</th>
                      <th>Date</th>
                      <th>Over Paid</th>
                    </tr>
                    @foreach($overpaidData as $key=>$value)
                    <tr>
                      <td>#{{$value->id}}</td>
                      <td>{{$value->givenstartdate}}</td>
                      <td class="text_center">{{$value->over_paid}}</td>
                    </tr>
                    @endforeach
                  </table>
                </div>
                @endif
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="bottom_item mt-3">
    <form action="{{ route('company.billingexport') }}" method="post">
      @csrf
      @php
      $exportids = 0;
      $exportids = implode(",",$ticketids);
      @endphp
      <input type="hidden" name="exportids" id="exportids" value="{{$exportids}}">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <h6 class="mb-0">Customer Payments</h6>
        <button class="btn add-btn-yellow" type="submit" name="search" value="excel">Export to CSV</button>
      </div>
    </form>

    <div class="card my-3">
      <div class="card-body p-4">
        <div class="box">
          <table class="table table-new1">
            <tr class="tableHover">
              <th>Ticket #</th>
              <th>Date of Payment</th>
              <th>Invoice Paid</th>
              <th>Method</th>
            </tr>
            @foreach($balancesheet as $key => $value)
            <tr class="tableHover">
              @php
              $newdate = date("M, d Y", strtotime($value->created_at));
              @endphp
              <td class="tableColorHover">#{{$value->ticketid}}</td>
              <td class="tableColorHover">{{$newdate}}</td>
              <td class="tableColorHover">{{$value->amount}}</td>
              @php
              $checkinfo = App\Models\Quote::select('checknumber')->where('id',$value->ticketid)->first();
              if($checkinfo->checknumber!=0 && $checkinfo->checknumber!="") {
              $checknumber = "(". $checkinfo->checknumber. ")";
              } else {
              $checknumber = "";
              }
              @endphp
              <td class="tableColorHover">{{$value->paymentmethod}} {{$checknumber}}</td>
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" tabindex="-1" id="exampleModal" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Partial Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="put" action="{{ url('company/billing/updatenew') }}" enctype="multipart/form-data" id="modalpost">
        @csrf
        <input type="hidden" class="form-control form-control-2" name="method1" id="method1" value="" placeholder="">
        <input type="hidden" class="form-control" name="check_no1" id="check_no1" value="">
        <div class="modal-body">
          <div id="modalContent"></div>
        </div>
        <div class="modal-footer float-left">
          <button type="button" class="btn  d-inline add-btn-yellow" id="modalhide">Apply</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--modal end-->
@endsection
@section('script')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [
        [0, "desc"]
      ]
    });
  });
  $(document).ready(function() {
    var search = $(location).attr('search');
    var customerid = $("#customerid").val();
    if (search == "") {
      var ids = $("#ticketid").val();
      window.location.href = "?id=" + ids + "&cid=" + customerid;

    }
    $(".puser").on('change', function() {
      cid = $("#cid").val();
      $("#customerid").val(cid);
      var ids = $("#ticketid").val();
      $.ajax({
        url: "{{url('company/billing/getreceivepayment')}}",
        data: {
          cid: cid,
        },
        method: 'post',
        dataType: 'json',
        refresh: true,
        success: function(data) {
          window.location.href = "?id=" + data.tid + "&cid=" + data.cid;
        }
      })
    });

    $('#myCheckbox').change(function() {
      var ids = $("#ticketid").val();
      if ($(this).is(':checked')) {
        $.ajax({
          url: "{{url('company/billing/getticketData')}}",
          data: {
            ticketid: ids,
          },
          method: 'post',
          dataType: 'json',
          refresh: true,
          success: function(data) {
            $('#modalContent').html(data.html);
            $('#exampleModal').modal('show');
          }
        })
      } else {
        $('#exampleModal').modal('hide');
      }
    });
  });

  $(".invoicecheck").change(function() {
    const checkboxes = $('.invoicecheck');

    const checkedCheckboxes = checkboxes.filter(':checked');

    if (checkedCheckboxes.length === 0) {
      // If none are checked, prevent unchecking the current checkbox
      $(this).prop('checked', true);
    }

    var allObj = {};
    allObj.checkbox = [];
    $("input:checkbox").each(function() {
      if ($(this).is(":checked")) {
        allObj.checkbox.push($(this).attr("data-id"));
      }
    });
    var ckids = allObj.checkbox;

    window.location.href = "?id=" + ckids;
  });


  $("#modalhide").click(function() {
    var totalSum = 0;
    $('.formamount').each(function() {
      // Parse the input value as a float and add it to the total
      totalSum += parseFloat($(this).val()) || 0;
    });
    $("#amount").val(totalSum);
    $('#exampleModal').modal('hide');
    return false;
  });

  $(".save_pay").click(function() {
    var selectedValue = $("#mySelect").val();
    if (selectedValue == "Check") {
      var cnumber = $("#check_no").val();
      $("#check_no1").val(cnumber);
      if (cnumber == "") {
        swal("Check Number", "Please provide Check Number :)", "error");
        return false;
      }
    }
    if ($('#myCheckbox').is(':checked')) {
      var formData = $('#modalpost').serialize();
      $('#modalpost').submit();
    } else {
      $('#saveform').submit();
    }

  });

  $(document).ready(function() {
    $("#method").val('Cash');
    $("#method1").val('Cash');

    $("#mySelect").change(function() {
      // Get the selected value
      var selectedValue = $(this).val();
      if (selectedValue == "Check") {
        $('#mycheck').show();
      } else {
        $('#mycheck').hide();
      }
      $("#method").val(selectedValue);
      $("#method1").val(selectedValue);
    });

    $('.btn-close').click(function() {
      // Uncheck the checkbox
      $('#myCheckbox').prop('checked', false);
    });

  });

  function checkDigit(event) {
    var code = (event.which) ? event.which : event.keyCode;

    if ((code < 48 || code > 57) && (code > 31)) {
      return false;
    }

    return true;
  }

  function cc_format(value) {
    var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
    var matches = v.match(/\d{4,16}/g);
    var match = matches && matches[0] || ''
    var parts = []
    for (i = 0, len = match.length; i < len; i += 4) {
      parts.push(match.substring(i, i + 4))
    }
    if (parts.length) {
      return parts.join(' ')
    } else {
      return value
    }
  }
</script>
@endsection