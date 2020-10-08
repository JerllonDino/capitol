@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
@if ( Session::get('permission')['col_customer'] & $base['can_write'] )
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['customer.store']]) }}
    <div class="form-group col-sm-12">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" required autofocus>
    </div>
    
    
    <div class="form-group col-sm-12">
        <label for="address">Address</label>
        <textarea class="form-control" name="address"></textarea>
    </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    {{ Form::close() }}
</div>
@endif
@if ( Session::get('permission')['col_customer'] & $base['can_read'] )
<table id="seriallist" class="table table-striped table-hover" cellspacing = 0 width = "100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Client Type</th>
            <th>Address</th>
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
    $('#seriallist').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "customer") }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 
                function(data) {
                    
                    return !data.customer_type ? '' : data.customer_type['description'];
                }, 
                bSortable: false,
                searchable: false,

            },
            { data: 'address', name: 'address' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    @if ( Session::get('permission')['col_customer'] & $base['can_read'] )
                    var view = '<a href="{{ route('customer.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif
                    @if ( Session::get('permission')['col_customer'] & $base['can_write'] )
                    var write = '<a href="{{ route('customer.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif
                    return view + write;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });
</script>
@endsection
