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
    {{ Form::open(['method' => 'POST', 'route' => ['bac.store']]) }}
    
    <div class="form-group col-sm-6">
        <label for="date">User</label>
		<input type="text" class="form-control" name="user" value="{{ $base['user']->realname }}" required readonly>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
    </div>
    
    <div class="form-group col-sm-6">
        <label for="date">Date</label>
		<input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y') }}" required autofocus>
    </div>
</div>
<div class="row">    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC Goods & Services</label>
        <div class="col-sm-8">
            <input type="number" name="bac_val[]" min="0" step="0.01" value="0" class="form-control">
            <input type="hidden" name="bac_type[]" value="1">
        </div>
    </div>
    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC INFRA</label>
        <div class="col-sm-8">
            <input type="number" name="bac_val[]" min="0" step="0.01" value="0" class="form-control">
            <input type="hidden" name="bac_type[]" value="2">
        </div>
    </div>
    
    <div class="form-group bac_inputs">
        <label class="control-label col-sm-4" for="logo">BAC Drugs & Meds</label>
        <div class="col-sm-8">
            <input type="number" name="bac_val[]" min="0" step="0.01" value="0" class="form-control">
            <input type="hidden" name="bac_type[]" value="3">
        </div>
    </div>
	
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    {{ Form::close() }}
</div>
<hr>

<div id="account_panel">
</div>

@endif
@if ( Session::get('permission')['col_bac_income'] & $base['can_read'] )
<table id="baclist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>User</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
@endif
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script type="text/javascript">
    $('#baclist').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "bac_inputs") }}',
        columns: [
            { data: 'realname', name: 'realname' },
            { data:
                function(data) {
                    var date = new Date(data.date_of_entry);
                    var month = date.toLocaleString('en-us', {month: 'long'});
                    return month +' '+ date.getDate() +', '+ date.getFullYear();
                }
            },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    @if ( Session::get('permission')['col_bac_income'] & $base['can_read'] )
                    view = '<a href="{{ route('bac.index') }}/'+data.date_of_entry+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif
                    @if ( Session::get('permission')['col_bac_income'] & $base['can_write'] )
                    write = '<a href="{{ route('bac.index') }}/'+data.date_of_entry+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif
                    return view + write;
                },
                bSortable: false,
                searchable: false,
            },
        ]
    });
    
    $('.datepicker').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });
</script>
@endsection
