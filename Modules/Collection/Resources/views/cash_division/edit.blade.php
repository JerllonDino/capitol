@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
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
@if ( Session::get('permission')['col_cash_division'] & $base['can_write'] )
<div class="row">
    {{ Form::open(['method' => 'PATCH', 'route' => ['cash_division.update', $base['addtl']->id]]) }}
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['user']->realname }}</dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
    </div>

    <div class="form-group col-sm-6">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y', strtotime($base['addtl']->date_of_entry)) }}" required autofocus>
    </div>

    <div class="form-group col-sm-6">
        <label for="refno">Reference No.</label>
        <input type="text" class="form-control" name="refno" value="{{ $base['addtl']->refno }}" required>
    </div>



    <div class="form-group col-sm-6 d-none">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            @if ($base['addtl']->col_municipality_id == '')
            <option selected disabled></option>
            @else
            <option disabled></option>
            @endif

            @foreach($base['municipalities'] as $municipality)
                @if ($base['addtl']->col_municipality_id == $municipality['id'])
                <option value="{{ $municipality['id'] }}" selected>{{ $municipality['name'] }}</option>
                @else
                <option value="{{ $municipality['id'] }}">{{ $municipality['name'] }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-6 d-none">
        <label for="barangay">Barangay</label>
        @if (!empty($base['barangays']))
        <select class="form-control" name="brgy" id="brgy">
        @else
        <select class="form-control" name="brgy" id="brgy" disabled>
        @endif
            <option value="0"></option>
            @if (!empty($base['barangays']))
                @foreach ($base['barangays'] as $brgy)
                    @if ($base['addtl']->col_barangay_id == $brgy['id'])
                    <option value="{{ $brgy['id'] }}" selected>{{ $brgy['name'] }}</option>
                    @else
                    <option value="{{ $brgy['id'] }}">{{ $brgy['name'] }}</option>
                    @endif
                @endforeach
            @endif
        </select>
    </div>



    <div class="form-group col-sm-6">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" value="{{$base['addtl']->customer->name}}" >
        <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="{{$base['addtl']->customer->id}}">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
             <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            @foreach($base['sandgravel_types'] as $sandgravel_types)
                @if($sandgravel_types['id'] == $base['addtl']->client_type )
                    <option value="{{ $sandgravel_types['id'] }}" selected>{{ $sandgravel_types['description'] }}</option>
                @else
                    <option value="{{ $sandgravel_types['id'] }}">{{ $sandgravel_types['description'] }}</option>
                @endif
            @endforeach
            </select>
    </div>


    <div class="form-group col-sm-2">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" required="">

            @if( $base['addtl']->sex == 'male' )
                <option value="female">Female</option>
                <option value="male" selected="">Male</option>
            @elseif($base['addtl']->sex == 'female')
                <option value="female" selected="">Female</option>
                <option value="male">Male</option>
            @else
                 <option selected="" ></option>
                <option value="female" >Female</option>
                <option value="male">Male</option>
            @endif
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

                @foreach($base['addtl']->items as $i => $item)
                <tr>
                    <td>
                        @if ($item->acct_title != null)
                        <input type="text" class="form-control account" value="{{ $item->acct_title->name .' ('. $item->acct_title->group->category->name .')' }}" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="{{ $item->acct_title->id }}">
                        <input type="hidden" class="form-control" name="account_type[]" value="title">
                            @if (isset($item->acct_title->rate->is_shared))
                            <input type="hidden" class="form-control account_is_shared" value="{{ $item->acct_title->rate->is_shared }}" name="account_is_shared[]">
                            @else
                            <input type="hidden" class="form-control account_is_shared" value="" name="account_is_shared[]">
                            @endif
                        @else
                        <input type="text" class="form-control account" value="{{ $item->acct_subtitle->name .' ('. $item->acct_subtitle->title->group->category->name .')' }}" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="{{ $item->acct_subtitle->id }}">
                        <input type="hidden" class="form-control" name="account_type[]" value="subtitle">
                            @if (isset($item->acct_title->rate->is_shared))
                            <input type="hidden" class="form-control account_is_shared" value="{{ $item->acct_title->rate->is_shared }}" name="account_is_shared[]">
                            @else
                            <input type="hidden" class="form-control account_is_shared" value="" name="account_is_shared[]">
                            @endif
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                    </td>
                    <td>
                        {{-- @if ($item->acct_title != null)
                        <input type="text" class="form-control" name="nature[]" value="{{ $item->acct_title->name }}" maxlength="300" required>
                        @else
                        <input type="text" class="form-control" name="nature[]" value="{{ $item->acct_subtitle->name }}" maxlength="300" required>
                        @endif --}}
                        <input type="text" class="form-control" name="nature[]" value="{{ $item->nature }}" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" value="{{ $item->value }}" required>
                    </td>
                    <td>
                    @if ($i > 0)
                        <button class="btn btn-warning btn-sm rem_row" type="button">
                        <i class="fa fa-minus"></i>
                        </button>
                    @endif
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    {{ Form::close() }}
</div>

<div id="account_panel">
</div>

@endif

@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
{{ Html::script('/vendor/autocomplete/jquery.autocomplete.js') }}
<script type="text/javascript">
    var collection_type = 'show_in_cashdivision';
</script>

@include('collection::shared.transactions_js')


@endsection
