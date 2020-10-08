@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
@if ( Session::get('permission')['col_serial'] & $base['can_write'] )
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['serialsg.store']]) }}
    <div class="form-group col-sm-4">
        <label for="start">Start</label>
        <input type="number" class="form-control" name="start" id="start" step="1" min="0" required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="end">End</label>
        <input type="number" class="form-control" name="end" id="end" step="1" min="0" required>
    </div>

    <div class="form-group col-sm-3">
        <label for="acct_cat_id">Type</label>
        <select class="form-control f51_inputs" name="acct_cat_id" id="acct_cat_id" >
            <option selected disabled></option>
            @foreach($sg_type as $type)
             
                <option value="{{ $type->id }}">{{ $type->sg_type }}</option>
            @endforeach;
        </select>
    </div>


    <div class="form-group col-sm-3">
        <label for="date">Date</label>
        <input type="text" class="form-control" name="date" id="date" value="{{ date('m/d/Y') }}" required>
    </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    {{ Form::close() }}
</div>
<hr>
@endif
@if ( Session::get('permission')['col_serial'] & $base['can_read'] )
<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Form</th>
            <th>Start</th>
            <th>End</th>
            <th>QTY</th>
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
    $('#seriallist').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "serialsg") }}',
        columns: [
            { data: 'sg_type', name: 'col_serial_sg_type.sg_type' },
            { data: 'serial_start', name: 'col_serial_sg.serial_start' },
            { data: 'serial_end', name: 'col_serial_sg.serial_end' },
            { data: 'serial_qty', name: 'col_serial_sg.serial_qty' },
            { data: 'serial_date' , name : 'col_serial_sg.serial_date' ,
                render : function(data) {
                    var date = new Date(data);
                    var month = date.toLocaleString('en-us', {month: 'long'});
                    return month +' '+ date.getDate() +', '+ date.getFullYear();
                }
            },
            { data: null, name : '' ,
               render : function(data) {
                    var complete = '';
                    var view = '';
                    var write = '';
                    @if ( Session::get('permission')['col_serial'] & $base['can_read'] )
                     if(data.serial_current != 0)
                        view = '<a href="{{ route('serialsg.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif

                    @if ( Session::get('permission')['col_serial'] & $base['can_write'] )
                        if(data.serial_current != 0)
                            write = '<a href="{{ route('serialsg.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif

                     @if ( Session::get('permission')['col_serial'] & $base['can_write'] )
                        if(data.serial_current == 0)
                            complete = '<strong style="color:red;">completed</strong>';
                    @endif

                    return view + write + complete;
                },
                bSortable: false,
                searchable: false,
            }
        ],
        order : [[ 2, "desc" ]]
    });

    $(document).ready(function(){
        $('#date').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide'
        });
    });

    
</script>
@endsection
