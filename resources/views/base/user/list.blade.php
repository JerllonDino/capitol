@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <table id="userlist" class="dtable table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Creation Date</th>
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
$('#userlist').DataTable({
    dom: '<"dt-custom">frtip',
    processing: true,
    serverSide: true,
    ajax: '{{ route("datatables", "user") }}',
    columns: [
        { data: 'realname', name: 'realname' },
        { data: 'username', name: 'username' },
        { data: 'email', name: 'email' },
        { data:
                function(data) {
                    var date = new Date(data.created_at);
                    var month = date.toLocaleString('en-us', {month: 'long'});
                    return month +' '+ date.getDate() +', '+ date.getFullYear();
                },
                bSortable: false,
                searchable: false,
            },
        { data:
            function(data) {
                var view = '';
                var write = '';
                @if ( Session::get('permission')['user'] & $base['can_read'] )
                var view = '<a href="{{ route('user.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                @endif
                @if ( Session::get('permission')['user'] & $base['can_write'] )
                var write = '<a href="{{ route('user.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                @endif
                return view + write;
            },
            bSortable: false,
            searchable: false,
        }
    ]
});

@if ( Session::get('permission')['user'] & $base['can_write'] )
$("div.dt-custom").html(
    '<a href="{{ route("user.create") }}" class="btn btn-med btn-success">Add</a>'
);
@endif
</script>
@endsection
