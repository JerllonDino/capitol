@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <table id="account_title" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
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
    $('#account_title').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "title") }}',
        columns: [
            { data: 'code', name: 'col_acct_title.code' },
            { data: 'name', name: 'col_acct_title.name' },
            { data: 'cat_name', name: 'col_acct_category.name' },
            { data: null , name : '',
                render : function(data) {
                    var view = '';
                    var write = '';
                    var can_delete = '';
                    @if ( Session::get('permission')['col_settings'] & $base['can_read'] )
                    view = '<a href="{{ route('account_title.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif

                    @if ( Session::get('permission')['col_settings'] & $base['can_write'] )
                    write = '<a href="{{ route('account_title.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif

                    @if ( Session::get('permission')['col_settings'] & $base['can_delete'] )
                    can_delete = '<a href="{{ route('account_title.index') }}/'+data.id+'/destroy" class="btn btn-sm btn-danger datatable-btn" title="Delete"><i class="fa fa-trash"></i></a>';
                    @endif
                    return view + write + can_delete;
                },
                bSortable: false,
                searchable: false,
            }
        ],
        order : [[ 2, "asc" ]]
    });

    @if ( Session::get('permission')['col_settings'] & $base['can_write'] )
        $("div.dt-custom").html(
            '<a href="{{ route("account_title.create") }}" class="btn btn-med btn-success">Add</a>'
        );
    @endif
</script>
@endsection
