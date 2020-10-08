@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
<style>
    .bac_inputs {
        padding-top: 30px;
    }
</style>
@endsection

@section('content')
@if ( Session::get('permission')['col_bac_income'] & $base['can_write'] )
<div class="row">
    
    <div class="form-group col-sm-6">
        <label for="date">User</label>
		<input type="text" class="form-control" name="user" value="{{ $base['bac'][0]->user->realname }}" required readonly>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="date">Date</label>
		<input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y', strtotime($base['bac'][0]->date_of_entry)) }}" required readonly>
    </div>
</div>
<div class="row">    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC Goods & Services</label>
        <div class="col-sm-8">
            <input type="text" name="bac_val[]" value="{{ number_format($base['bac'][0]->value, 2) }}" class="form-control" readonly>
            <input type="hidden" name="bac_type[]" value="1">
        </div>
    </div>
    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC INFRA</label>
        <div class="col-sm-8">
            <input type="text" name="bac_val[]" value="{{ number_format($base['bac'][1]->value, 2) }}" class="form-control" readonly>
            <input type="hidden" name="bac_type[]" value="2">
        </div>
    </div>
    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC Drugs & Meds</label>
        <div class="col-sm-8">
            <input type="text" name="bac_val[]" value="{{ number_format($base['bac'][2]->value, 2) }}" class="form-control" readonly>
            <input type="hidden" name="bac_type[]" value="3">
        </div>
    </div>
</div>
<hr>

@endif
@endsection
