@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
@if ( Session::get('permission')['backup'] & $base['can_write'] )
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['backup.store']]) }}
    
    <div class="form-group col-sm-6">
        <label for="date">Date</label>
        <input type="text" class="form-control" name="date" id="date" value="{{ date('m/d/Y') }}" readonly required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="remark">Remark</label>
        <input type="text" class="form-control" name="remark" id="remark" required autofocus>
    </div>
    
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    
    {{ Form::close() }}
</div>
<hr>
@endif

<div class="row">
    <div class="col-lg-12">
        <table id="userlist" class="dtable table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Remark</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script type="text/javascript">
$('#userlist').DataTable({
    dom: '<"dt-custom">frtip',
    processing: true,
    serverSide: true,
    ajax: '{{ route("datatables", "backup") }}',
    columns: [
        { data: 'date_of_entry', name: 'date_of_entry' },
        { data: 'remark', name: 'remark' },
        { data:
            function(data) {
                var view = '';
                @if ( Session::get('permission')['backup'] & $base['can_write'] )
                view = '<a href="{{ route('backup.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                @endif
                return view;
            },
            bSortable: false,
            searchable: false,
        }
    ]
});
</script>
@endsection
