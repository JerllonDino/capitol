@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <table id="group_permissionlist" class="dtable table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Category</th>
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
$('#group_permissionlist').DataTable({
    dom: '<"dt-custom">frtip',
    processing: true,
    serverSide: true,
    ajax: '{{ route("datatables", ["group_permission", "id" => $base["group_id"]]) }}',
    columns: [
        { data: 'name', name: 'name' },
        { data: 'description', name: 'description' },
        { data:
            function(data) {
                var view = '';
                var write = '';
                @if ( Session::get('permission')['group'] & $base['can_read'] )
                var view = '<a href="{{ route('group.index') }}/{{ $base["group_id"] }}/permission/'+ data.id +'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                @endif
                @if ( Session::get('permission')['group'] & $base['can_write'] )
                var write = '<a href="{{ route('group.index') }}/{{ $base["group_id"] }}/permission/'+ data.id +'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                @endif
                return view + write;
            },
            bSortable: false,
            searchable: false,
        }
    ]
});

@if ( Session::get('permission')['group'] & $base['can_write'] )
$("div.dt-custom").html(
    '<a href="{{ route("group.edit", ["id" => $base["group_id"]]) }}" class="btn btn-med btn-success">Add</a>'
);
@endif
</script>
@endsection