@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <table id="example" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
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
    $('#example').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "colgrp") }}',
        columns: [
            { data: 'grp_name', name: 'col_acct_group.name' },
            { data: 'name', name: 'col_acct_category.name' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    @if ( Session::get('permission')['col_settings'] & $base['can_read'] )
                    view = '<a href="{{ route('account_group.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif
                    
                    @if ( Session::get('permission')['col_settings'] & $base['can_write'] )
                    write = '<a href="{{ route('account_group.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif
                    return view + write;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });

    @if ( Session::get('permission')['col_settings'] & $base['can_write'] )
        $("div.dt-custom").html(
            '<a href="{{ route("account_group.create") }}" class="btn btn-med btn-success">Add</a>'
        );
    @endif
</script>
@endsection
