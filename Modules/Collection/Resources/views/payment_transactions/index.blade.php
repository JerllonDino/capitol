@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
{{ Html::style('/base/sweetalert/sweetalert2.min.css') }}
<style>
    .td_amt {
        width: 150px;
    }
    .td_nature {
        width: 450px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    #sand_blk {
        display: none;
    }

    .btn-pink{
        background-image: linear-gradient(to bottom,#f66adc 0,#b11faa 100%);
    }

    .btn-gray{
        color : #fff;
        background-image: linear-gradient(to bottom,#959294 0,#625e61 100%);
    }

    .btn-green{
        color : #fff;
        background-image: linear-gradient(to bottom,#73e641 0,#4a9c18 100%);
    }

    .btn-red{
        color : #fff;
        background-image: linear-gradient(to bottom,#ff0009 0,#9e1523 100%);
    }

    .btn-another{
        color:#fff;
        background-image: linear-gradient(to bottom,#229568 0,#0b470e 100%);
    }

    .btn-another-none{
        color:#fff;
        background-image: linear-gradient(to bottom,#5a755d 0,#435744 100%);
    }

    #sg_booklets{
        background: burlywood;
    }

    .bg-cert-ttc{
        background-image: linear-gradient(to bottom,#0b147d 0,#0c2768 100%);
    }

    .bg-cert-sg{
        background-image:linear-gradient(to bottom,#ae3bcb 0,#cd3bd1 100%);
    }

    .bg-cert-pp{
        background-image: linear-gradient(to bottom,#0a933c 0,#0f661d 100%);
    }
    .bg-cert-pp > a{
        color: #000;
    }


.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }

</style>
@endsection

@section('content')
@if ( Session::get('permission')['col_payment_transactions'] & $base['can_write'] )
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['payment_transactions.store'], 'id' => 'store_form']) }}
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['user']->realname }}</dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
        <input type="hidden" class="form-control" name="transaction_source" id="transaction_source" value="receipt">
    </div>

    <div class="row">
        <div class="form-group col-sm-4">
            <div class="col-md-4">
            <label for="date">AUTO TIMER</label>
        </div>
            <div class="col-md-4">
                <input type="checkbox" class="form-control " value="true" checked="" name="auto_timer" id="auto_timer" />
            </div>
        </div>
    </div>

    <div class="form-group col-sm-2">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" id="date_timex" value="" required autofocus>
    </div>

    <div class="form-group col-sm-2">
        <label for="user">AF Type</label>
        <select class="form-control" id="form" name="form">
            <option value="1">Form 51</option>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="serial_id">Series</label>
        <select class="form-control" name="serial_id" id="serial_id" disabled required>
        </select>
    </div>

   <div class="form-group col-sm-2">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            <option selected></option>
            @foreach($base['municipalities'] as $municipality)
                <option value="{{ $municipality['id'] }}">{{ $municipality['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="barangay">Barangay</label>
        <select class="form-control" name="brgy" id="brgy" disabled>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" required>
        <input type="hidden" class="form-control" name="customer_id" id="customer_id">
    </div>

    <!-- <div class="form-group col-sm-4">
        <label for="customer_type">View Previous Client Type/s</label>
        <select class="form-control" id="customer_type"> 
            <option></option> -->
                {{-- @foreach($base['sandgravel_types'] as $sandgravel_types) --}}
                    <!-- <option value="{{-- $sandgravel_types['id'] --}}">{{-- $sandgravel_types['description'] --}}</option> -->
                {{-- @endforeach --}}
        <!-- </select>
    </div> -->

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
        <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
        <small id="client_type_msg" style="color: red;"></small>
        <!-- <select class="form-control" name="customer_type" id="new_customer_type"> -->
        <select class="form-control" name="customer_type" id="customer_type">
            <option></option>
                @foreach($base['sandgravel_types'] as $sandgravel_types)
                    <option value="{{ $sandgravel_types['id'] }}">{{ $sandgravel_types['description'] }}</option>
                @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" required="">
            <option selected></option>
            <option value="female">Female</option>
            <option value="male">Male</option>

        </select>
    </div>



    <div class="form-group col-sm-4">
        <label for="user">Transaction Type</label>
        <select class="form-control" id="transaction_type" name="transaction_type" required>
            @foreach ($base['transaction_type'] as $transaction_type)
                <option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_name">Bank Name</label>
        <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" disabled>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_number">Number</label>
        <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" disabled>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_date">Date</label>
        <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" disabled>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_remark">Bank Remarks</label>
        <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
        <small id="info_bank_rem" style="color: red;"></small>
        {{-- <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value="" > --}}
        <textarea class="form-control bank_input" name="bank_remark" id="bank_remark"></textarea>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_to_remarks">&nbsp;</label>
        <input type="button" id="bank_to_remarks" name="bank_to_remarks" class="form-control btn btn-info" value="Add Bank to Receipt Remarks" disabled>
    </div>

    <div class="form-group col-sm-4">
        <label for="user">With Certificate</label>
        <select class="form-control" id="with_cert" name="with_cert">
            <option value="null">NONE</option>
            <option value="Transfer Tax">TRANSFER TAX</option>
             <option value="Sand & Gravel">SAND AND GRAVEL</option>
              <option value="Provincial Permit">PROVINCIAL PERMIT</option>
              <option value="Sand and Gravel Certification">SAND AND GRAVEL CERTIFICATION</option>
        </select>
    </div>

    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th colspan="2">Account</th>
                    <th class="td_nature">Nature</th>
                    <th>Amount</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td><input type="text" id="total" class="form-control" readonly></td>
                    <td></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control account " required>
                        <input type="hidden" class="form-control" name="account_id[]">
                        <input type="hidden" class="form-control" name="account_type[]">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                        <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                    </td>
                    <td>
                        <input type="text" class="form-control nature" name="nature[]" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" required>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group" id="sand_blk">
        <input type="hidden" class="form-control sand_inputs addtl_inputs" name="sand_transaction" id="sand_transaction" value="0">
        <div class="form-group col-sm-3">
            <label for="sand_sandgravelprocessed">Less: <br>Sand and Gravel (processed)</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravelprocessed" id="sand_sandgravelprocessed" value="0" step="0.01" min="0">
        </div>

        <div class="form-group col-sm-3">
            <label for="sand_abc">Less: <br>Aggregate Base Course</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_abc" id="sand_abc" value="0" step="0.01" min="0">
        </div>

        <div class="form-group col-sm-3">
            <label for="sand_sandgravel">Less: <br>Sand and Gravel</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravel" id="sand_sandgravel" value="0" step="0.01" min="0">
        </div>

        <div class="form-group col-sm-3">
            <label for="sand_boulders">Less: <br>Boulders</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_boulders" id="sand_boulders" value="0" step="0.01" min="0">
        </div>
    </div>

    <div class="col-md-12 hidden" id="sg_booklets">
        <table class="table table-bordered center" id="booklets_sg">
            <thead>
                <tr>
                    <th class="text-center">BOOKLET START</th>
                    <th class="text-center">BOOKLET END</th>
                    <th class="text-center"><button type="button" id="add_booklet_row" class="btn btn-sm btn-info"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="number" class="form-control booklet_start" name="booklet_start[]"></td>
                    <td><input type="number" class="form-control booklet_end" name="booklet_end[]"></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group col-sm-12">
        <label for="remarks">Receipt Remarks</label>
        <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
        <small id="info_rem" style="color: red;"></small>
        <textarea id="remarks" class="form-control" name="remarks"></textarea>
    </div>

    <div class="form-group col-sm-12">

        @if ($base['check_unprinted'] == 0 )
            @if (isset($base['serial']->serial_begin))
                <button type="submit" class="btn btn-success btnf51" name="button" id="confirm">SAVE</button>
            @else
                 <button type="submit" class="btn btn-success btnf51" name="button" id="confirm" disabled>SAVE</button>
            @endif
        @else
                <button type="button" class="btn btn-danger" name="button" id="x" disabled>PLEASE PRINT ALL UNPRINTED RECEIPT/s ON THIS STATION</button>
        @endif
    </div>
    {{ Form::close() }}
</div>
<hr>

<div id="account_panel">
</div>

@endif
@if ( Session::get('permission')['col_receipt'] & $base['can_read'] )

<form class="form-inline">
  <div class="form-group">
    <label for="show_year">YEAR</label>
    <input type="number" min="2017" max="{{ date('Y') }}" class="form-control" id="show_year" placeholder="{{ date('Y') }}" value="{{ date('Y') }}">
  </div>
  <div class="form-group">
    <label for="show_mnth">Month</label>
    <select class="form-control" name="show_mnth" id="show_mnth">
        <option value="ALL">ALL</option>
            @foreach ($base['months'] as $mkey => $month)
                @if($mnth == $mkey)
                    <option value="{{ $mkey }}" selected>{{ $month }} </option>
                @else
                    <option value="{{ $mkey }}">{{ $month }}</option>
                @endif
            @endforeach
    </select>
  </div>
  <div class="form-group">
      <label>Day</label>
      <select class="form-control" name="show_day" id="show_day">
      </select>
  </div>
<button type="button" class="btn btn-default" onclick="$(this).loadTable();" >SHOW</button>
</form>

 <fieldset>
  <legend>Legend:</legend>
        <h5>Certificates Icon</h5>
        <button class="btn btn-sm btn-gray datatable-btn" title="Certificate"><i class="fa fa-certificate"></i> </button> No Certificate
        <button  class="btn btn-sm bg-cert-ttc datatable-btn" title="Transfer Tax Certificate"><i class="fa fa-certificate"></i></button>Transfer Tax Certificate
        <button class="btn btn-sm bg-cert-sg datatable-btn" title="Sand &amp; Gravel Certificate"><i class="fa fa-certificate"></i></button>Sand &amp; Gravel Certificate
        <button class="btn btn-sm bg-cert-pp datatable-btn" title="Provincial Permit Certificate"><i class="fa fa-certificate"></i></button>Provincial Permit Certificate
        <hr />
        {{-- <h5>Additional Receipt Icon</h5>
        <button class="btn btn-sm btn-another-none datatable-btn" title="ANOTHER RECEIPT"><i class="fa fa-plus"></i> </button>Create ANOTHER RECEIPT --}}

 </fieldset>

<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>User</th>
            <th>STATION IP</th>
            <th>Form</th>
            <th>Serial</th>
            <th>Date Entry</th>
            <th>Date Report</th>
            <th>Customer/Payor</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
@endif
@endsection

@section('js')
<script>
  $('.datepicker').datepicker({

  });
</script>
@endsection
