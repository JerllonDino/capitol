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
    @if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] )
        <div class="row">
            <a href="{{ route('mncpal.rcpt.delete', $base['receipt']->id) }}" class="btn btn-danger pull-right">Delete Receipt</a>
        </div>
        <div class="row">
            {{ Form::open(['method'=>'POST', 'route'=>['mncpal.rcpt.update'], 'id'=>'updt_form']) }}
                {{ csrf_field() }}
                <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
                <div class="form-group col-md-4">
                    <label for="rcpt_no">Receipt No.</label>
                    <input type="hidden" name="rcpt_id" id="rcpt_id" value="{{ $base['receipt']->id }}">
                    <input type="number" name="rcpt_no" id="rcpt_no" class="form-control" value="{{ $base['receipt']->rcpt_no }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="rcpt_date">Receipt Date</label>
                    <input type="text" name="rcpt_date" id="rcpt_date" class="form-control datepicker" value="{{ \Carbon\Carbon::parse($base['receipt']->rcpt_date)->format('m/d/Y H:i:s') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="customer_id">Payor/Customer</label>
                    <input type="text" class="form-control" name="customer" id="customer" value="{{ $base['receipt']->getCustomer->name }}" required>
                    <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="{{ $base['receipt']->col_customer_id }}">
                </div>
                <div class="form-group col-sm-4">
                    <label for="mncpal_mnc">Municipality</label>
                    <select class="form-control" name="mncpal_mnc" id="mncpal_mnc" required>
                        <option value="0"></option>
                        @foreach($base['municipalities'] as $munic)
                            @if($base['receipt']->col_municipality_id == $munic['id'])
                                <option value="{{ $munic['id'] }}" selected>{{ $munic['name'] }}</option>
                            @else
                                <option value="{{ $munic['id'] }}">{{ $munic['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="mncpal_brgy">Barangay</label>
                    <select class="form-control" name="mncpal_brgy" id="mncpal_brgy">
                        <option></option>
                        @foreach($base['brgys'] as $brgy)
                            @if($base['receipt']->col_barangay_id == $brgy['id'])
                                <option value="{{ $brgy['id'] }}" selected>{{ $brgy['name'] }}</option>
                            @elseif($base['receipt']->col_municipality_id == $brgy['municipality_id'])
                                <option value="{{ $brgy['id'] }}">{{ $brgy['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="customer_type">Client Type</label>
                    <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
            The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
                    <small id="client_type_msg" style="color: red;"></small>
                    <select class="form-control" name="customer_type" id="customer_type">
                        <option></option>
                        @foreach($base['sandgravel_types'] as $sg_types)
                            @if($base['receipt']->client_type == $sg_types['id'])
                                <option value="{{ $sg_types['id'] }}" selected>{{ $sg_types['description'] }}</option>
                            @else
                                <option value="{{ $sg_types['id'] }}">{{ $sg_types['description'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <label for="transaction_type">Transaction Type</label>
                    <select class="form-control" id="transaction_type" name="transaction_type" required>
                        @foreach ($base['transaction_type'] as $transaction_type)
                            @if($base['receipt']->transaction_type == $transaction_type['id'])
                                <option value="{{ $transaction_type->id }}" selected>{{ $transaction_type->name }}</option>
                            @else
                                <option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="bank_name">Bank Name</label>
                    @if($base['receipt']->transaction_type > 1)
                        <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="{{ $base['receipt']->drawee_bank }}">
                    @else
                        <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" disabled>
                    @endif
                </div>
                <div class="form-group col-sm-4">
                    <label for="bank_number">Bank Number</label>
                    @if($base['receipt']->transaction_type > 1)
                        <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="{{ $base['receipt']->bank_no }}">
                    @else
                        <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" disabled>
                    @endif
                </div>
                <div class="form-group col-sm-4">
                    <label for="bank_date">Bank Date</label>
                    @if($base['receipt']->transaction_type > 1)
                        <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="{{ \Carbon\Carbon::parse($base['receipt']->bank_date)->format('m/d/Y H:i:s') }}">
                    @else
                        <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" disabled>
                    @endif 
                </div>
                <div class="form-group col-sm-12">
                    <label for="">Remarks</label>
                    <textarea class="form-control" name="mncpal_remarks" id="mncpal_remarks" value="{{ $base['receipt']->remarks }}"></textarea>
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
                            @foreach($base['receipt']->getItems as $i => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="item_id[]" id="item_id" value="{{ $item->id }}">
                                        <input type="text" class="form-control account" value="{{ !is_null($item->getAccount) ? $item->getAccount->name : (!is_null($item->getSubAccount) ? $item->getSubAccount->name : '' ) }}" required>
                                        <input type="hidden" class="form-control" name="account_id[]" value="{{ $item->col_acct_title_id > 0 ? $item->col_acct_title_id : $item->col_acct_subtitle_id }}">
                                        <input type="hidden" class="form-control" name="account_type[]" value="{{ $item->col_acct_title_id > 0 ? 'title' : 'subtitle' }}">
                                        <input type="hidden" class="form-control account_is_shared" name="account_is_shared[]" value="{{ !is_null($item->getCollectRate) ? $item->getCollectRate->is_shared : 0 }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                                        <input type="hidden" class="form-control">
                                        <input type="hidden" class="form-control account_rate" name="account_rate[]" value="{{ $item->col_collection_rate_id }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control nature" name="nature[]" maxlength="300" value="{{ $item->nature }}" required>
                                    </td>
                                    <td class="td_amt">
                                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" value="{{ $item->value }}" required>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm rem_row" type="button">
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="form-group col-sm-12">
                    <button type="submit pull-left" class="btn btn-success" id="submit">SAVE</button>
                </div>
            {{ Form::close() }}
        </div>
        <div id="account_panel">
        </div>
    @endif
@endsection

@section('js')
    <script type="text/javascript">
        var collection_type = 'show_in_fieldlandtax';
        $('#transaction_type').change(function() {
            if ($(this).val() > 1) {
                $('#bank_name').prop('disabled', false);
                $('#bank_number').prop('disabled', false);
                $('#bank_date').prop('disabled', false);
                $('#bank_name').prop('required', true);
                $('#bank_number').prop('required', true);
                $('#bank_date').prop('required', true);
            } else {
                $('#bank_name').prop('disabled', true);
                $('#bank_number').prop('disabled', true);
                $('#bank_date').prop('disabled', true);
                $('#bank_name').prop('required', false);
                $('#bank_number').prop('required', false);
                $('#bank_date').prop('required', false);
            }
        });

        $('#mncpal_mnc').change(function() {
            if($(this).val() > 0) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route("collection.ajax") }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'action': 'get_barangays',
                        'input': $('#mncpal_mnc').val(),
                    },
                    success: function(response) {
                        $('#mncpal_brgy').find('option')
                            .remove()
                            .end()
                            .prop('disabled', false).append('<option value=""></option>');

                        $.each( response, function(key, brgy) {
                            $('#mncpal_brgy').append($('<option>', {
                                'data-code' :brgy.code,
                                value: brgy.id,
                                text: brgy.name
                            }));
                        });
                    },
                    error: function(response) {

                    },
                });
            }
        });
    </script>
    @include('collection::shared/transactions_js')
@endsection
