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
                    <th>Year</th>
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
        pageLength: 50,
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "budget") }}',
        columns: [
            { data: 'year', name: 'year' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    @if ( Session::get('permission')['col_settings'] & $base['can_read'] )
                    view = '<a href="{{ route('budget_estimate.index') }}/'+data.year+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif
                    
                    @if ( Session::get('permission')['col_settings'] & $base['can_write'] )
                    write = '<a href="{{ route('budget_estimate.index') }}/'+data.year+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
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
            '<a href="{{ route("budget_estimate.create") }}" class="btn btn-med btn-success">Add</a>'
        );
    @endif
</script>
@endsection
