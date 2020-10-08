@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <table id="grouplist" class="dtable table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
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
<script>
$('#grouplist').DataTable({
    dom: '<"dt-custom">frtip',
    processing: true,
    serverSide: true,
    ajax: '{{ route("datatables", "group") }}',
    columns: [
        { data: 'name', name: 'name' },
        { data: 'description', name: 'description' },
        { data:
            function(data) {
                var view = '';
                var write = '';
                @if ( Session::get('permission')['group'] & $base['can_read'] )
                var view = '<a href="{{ route('group.index') }}/'+data.id+'/permission" class="btn btn-sm btn-info datatable-btn" title="Permission"><i class="fa fa-check-square-o"></i></a>' +
                    '<a href="{{ route('group.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                @endif
                @if ( Session::get('permission')['group'] & $base['can_write'] )
                var write = '<a href="{{ route('group.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                @endif
                return view + write;
            },
            bSortable: false,
            searchable: false,
        }
    ],
});

@if ( Session::get('permission')['group'] & $base['can_write'] )
$("div.dt-custom").html(
    '<a href="{{ route("group.create") }}" class="btn btn-med btn-success">Add</a>'
);
@endif

/*
$('.input-sm').on('keyup', function(e) {
    alert();
});
*/
</script>
@endsection