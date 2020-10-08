@extends('nav')

@section('css')
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
@if ( Session::get('permission')['col_receipt'] & $base['can_write'] )

<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['receipt.another_save', $base['receipt']->id]]) }}
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['user']->realname }}</dd>
			<dt>Form</dt>
            <dd>{{ $base['receipt']->serial->formtype->name }}</dd>
			<dt>PARENT Serial Number</dt>
            <dd>{{ $base['receipt']->serial_no }}</dd>
        </dl>
        <input type="hidden" class="form-control" name="receipt_id" id="receipt_id" value="{{ $base['receipt_id'] }}">
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
        <input type="hidden" class="form-control" name="transaction_source" id="transaction_source" value="receipt">
        <input type="hidden" class="form-control" name="serial_idx" id="serial_idx" value="{{ $base['receipt']->serial->id }}">
    </div>

    <div class="form-group col-sm-4">
        <label for="date">Date</label>
		<input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y  H:i:s') }}" required autofocus>
    </div>

     <div class="form-group col-sm-4">
        <label for="user">AF Type</label>
        <select class="form-control" id="form" name="form">
            <option value="1" selected>Form 51</option>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="serial_id">Series</label>
        <select class="form-control" name="serial_id" id="serial_id"  required>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer"  value="{{ $base['receipt']->customer->name }}"  disabled />
        <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="{{ $base['receipt']->customer->id }}">
    </div>

    <div class="form-group col-sm-4">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
			@if ($base['receipt']->col_municipality_id == '')
            <option selected disabled></option>
			@else
			<option disabled></option>
			@endif

            @foreach($base['municipalities'] as $municipality)
                @if ($base['receipt']->col_municipality_id == $municipality['id'])
                <option value="{{ $municipality['id'] }}" selected>{{ $municipality['name'] }}</option>
                @else
                <option value="{{ $municipality['id'] }}">{{ $municipality['name'] }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-4">
        <label for="barangay">Barangay</label>
        @if (!empty($base['barangays']))
        <select class="form-control" name="brgy" id="brgy">
        @else
        <select class="form-control" name="brgy" id="brgy" disabled>
        @endif

            @if (!empty($base['barangays']))
                @foreach ($base['barangays'] as $brgy)
                    @if ($base['receipt']->col_barangay_id == $brgy['id'])
                    <option value="{{ $brgy['id'] }}" selected>{{ $brgy['name'] }}</option>
                    @else
                    <option value="{{ $brgy['id'] }}">{{ $brgy['name'] }}</option>
                    @endif
                @endforeach
            @endif

        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="user">Transaction Type</label>
        <select class="form-control" id="transaction_type" name="transaction_type">
            @foreach ($base['transaction_type'] as $transaction_type)
                @if ($base['receipt']->transaction_type == $transaction_type->id)
                <option value="{{ $transaction_type->id }}" selected>{{ $transaction_type->name }}</option>
                @else
                <option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
                @endif
            @endforeach
        </select>
    </div>

	<div class="form-group col-sm-4">
        <label for="bank_name">Bank Name</label>
        @if ($base['receipt']->bank_name == '')
            <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" disabled>
        @else
            <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="{{ $base['receipt']->bank_name }}">
        @endif
    </div>

	<div class="form-group col-sm-4">
        <label for="bank_number">Number</label>
        @if ($base['receipt']->bank_number == '')
            <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" disabled>
        @else
            <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="{{ $base['receipt']->bank_number }}">
        @endif
    </div>

	<div class="form-group col-sm-4">
        <label for="bank_date">Date</label>
        @if ($base['receipt']->bank_date == '')
            <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" disabled>
        @else
            <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="{{ date('m/d/Y', strtotime($base['receipt']->bank_date)) }}">
        @endif

    </div>

	<div class="form-group col-sm-4">
        <label for="bank_remark">Remark</label>
        @if ($base['receipt']->bank_remark == '')
            <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value="" disabled>
        @else
            <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value="{{ $base['receipt']->bank_remark }}">
        @endif
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_to_remarks">&nbsp;</label>
        @if ($base['receipt']->bank_name == '')
        <input type="button" id="bank_to_remarks" name="bank_to_remarks" class="form-control btn btn-info" value="Add Bank to Receipt Remarks" disabled>
        @else
        <input type="button" id="bank_to_remarks" name="bank_to_remarks" class="form-control btn btn-info" value="Add Bank to Receipt Remarks">
        @endif
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
                       <input type="text" class="form-control account ui-autocomplete-input" required="required" autocomplete="off">
                       <input type="hidden" class="form-control" name="account_id[]">
                       <input type="hidden" class="form-control" name="account_type[]">
                       <input type="hidden" class="form-control account_is_shared" name="account_is_shared[]" value="0">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                        <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                    </td>
                    <td>
                       <input type="text" class="form-control nature" name="nature[]" required="required" maxlength="300" autocomplete="off">
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" value="" required>
                    </td>
                    <td>
                    </td>
                </tr>

            </tbody>
        </table>

        <label>Remarks</label>
        <textarea name="remarks" rows="8" cols="80">{{ $base['receipt']->remarks }}</textarea>
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

	@if ( $base['receipt']->is_cancelled == 0  )
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Save</button>
    </div>
	@endif
    {{ Form::close() }}
</div>

<div id="account_panel">
</div>

@endif

@endsection

@section('js')

<script type="text/javascript">
     @if(isset($_GET['types']) &&  $_GET['types'] == 'field')
        var collection_type = 'show_in_fieldlandtax';
    @else
        var collection_type = 'show_in_landtax';
    @endif
</script>
 @include('collection::shared.transactions_js')

<script type="text/javascript">
    tinymce.init({forced_root_block: "", selector:'textarea'});
</script>
@endsection
