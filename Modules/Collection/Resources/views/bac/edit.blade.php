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
    {{ Form::open(['method' => 'PATCH', 'route' => ['bac.update', 'date' => $base['date']]]) }}
    <div class="form-group col-sm-6">
        <label for="date">User</label>
		<input type="text" class="form-control" name="user" value="{{ $base['user']->realname }}" required readonly>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
    </div>
    
    <div class="form-group col-sm-6">
        <label for="date">Date</label>
		<input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y', strtotime($base['bac'][0]->date_of_entry)) }}" required autofocus>
    </div>
</div>
<div class="row">    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC Goods & Services</label>
        <div class="col-sm-8">
            <input type="number" name="bac_val[]" min="0" step="0.01" value="{{ $base['bac'][0]->value }}" class="form-control">
            <input type="hidden" name="bac_type[]" value="1">
        </div>
    </div>
    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC INFRA</label>
        <div class="col-sm-8">
            <input type="number" name="bac_val[]" min="0" step="0.01" value="{{ $base['bac'][1]->value }}" class="form-control">
            <input type="hidden" name="bac_type[]" value="2">
        </div>
    </div>
    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC Drugs & Meds</label>
        <div class="col-sm-8">
            <input type="number" name="bac_val[]" min="0" step="0.01" value="{{ $base['bac'][2]->value }}" class="form-control">
            <input type="hidden" name="bac_type[]" value="3">
        </div>
    </div>
    
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    {{ Form::close() }}
</div>
<hr>

@endif
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script type="text/javascript">
    $('.datepicker').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });
</script>
@endsection