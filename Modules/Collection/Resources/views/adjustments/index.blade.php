@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
{{ Html::style('/base/sweetalert/sweetalert2.min.css') }}
<style>
    .td_amt {
        width: 150px;
    }
    .td_nature {
        width: 450px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
    .select2-container{ width:100% !important; }
</style>
@endsection

@section('content')
{{-- @if ( Session::get('permission')['col_cash_division'] & $base['can_write'] ) --}}
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['adjustments.store']]) }}
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['user']->realname }}</dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
        <input type="hidden" class="form-control" name="transaction" id="transaction" value="cash_division">
    </div>



    <div class="form-group col-sm-6">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y') }}" required autofocus>
    </div>

    <div class="form-group col-sm-6">
        <label for="refno">Reference No.</label>
        <!--<input type="text" class="form-control" name="refno" value="" required>-->
         <textarea class="form-control" name="refno" value="" rows="2" required></textarea> 
    </div>

    <div class="form-group col-sm-6">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            <option selected></option>
            @foreach($base['municipalities'] as $municipality)
                <option value="{{ $municipality['id'] }}">{{ $municipality['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="barangay">Barangay</label>
        <select class="form-control" name="brgy" id="brgy" disabled>
        </select>
    </div>

     <div class="form-group col-sm-6">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" >
        <input type="hidden" class="form-control" name="customer_id" id="customer_id">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
             <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            @foreach($base['sandgravel_types'] as $sandgravel_types)
                <option value="{{ $sandgravel_types['id'] }}">{{ $sandgravel_types['description'] }}</option>
            @endforeach
            </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" >
            <option selected></option>
            <option value="female">Female</option>
            <option value="male">Male</option>

        </select>
    </div>

    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th colspan="2">Account</th>
                    <th class="td_nature">Nature</th>
                    <th>Amount</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td><input type="text" id="total" class="form-control" readonly></td>
                    <td></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control account" required>
                        <input type="hidden" class="form-control" name="account_id[]">
                        <input type="hidden" class="form-control" name="account_type[]">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" required>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    {{ Form::close() }}
</div>
<hr>

<div id="account_panel">
</div>
{{-- @endif --}}
{{-- @if ( Session::get('permission')['col_cash_division'] & $base['can_read'] ) --}}
<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>User</th>
            <th>Date</th>
            <th>REFNO</th>
            <th>CUSTOMER</th>
            <th>MUNICIPALITY</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
{{-- @endif --}}
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
{{ Html::script('/base/sweetalert/sweetalert2.min.js') }}
<script type="text/javascript">

 $.fn.loadTable = function(){
  if ( $.fn.DataTable.isDataTable('#seriallist') ) {
  $('#seriallist').DataTable().destroy();
}
    $('#seriallist').dataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "adjustments") }}',
        columns: [
            { data: 'realname', name: 'realname' },
            { data:
                function(data) {
                    var date = new Date(data.date_of_entry);
                    var month = date.toLocaleString('en-us', {month: 'long'});
                    return month +' '+ date.getDate() +', '+ date.getFullYear();
                }
            },
            { data: 'refno', name: 'refno' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'name', name: 'name' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    var deletez = '';
                    view = '<a href="{{ route('adjustments.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    write = '<a href="{{ route('adjustments.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    if(data.deleted_at == null){
                        deletez = view + write+'<button onclick="$(this).delete(\''+data.id+'\');"  class="btn btn-sm btn-danger datatable-btn" title="Edit"><i class="fa fa-trash"></i></button>';
                    }else{
                        deletez = '<button onclick="$(this).restore(\''+data.id+'\');"  class="btn btn-sm btn-warning datatable-btn" title="Edit"><i class="fa fa-undo"></i></button>';
                    }

                    return deletez;
                },
                bSortable: false,
                searchable: false,
            },
        ]
    });
}

    $.fn.delete = function(deleteid){
        swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#a22314',
              cancelButtonColor: '#c9bebe',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                    $.ajax({
                        url: '{{ route("adjustments.delete") }}',
                        type: 'POST',
                        data:{
                          adjustments: deleteid,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Deleted!',
                      text: 'Adjustment deleted',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        $.fn.loadTable();
                      }
                    })


              }
            });

    };
    $.fn.restore = function(deleteid){
        swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#a22314',
              cancelButtonColor: '#c9bebe',
              confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
              if (result.value) {
                    $.ajax({
                        url: '{{ route("adjustments.restore") }}',
                        type: 'POST',
                        data:{
                          adjusments: deleteid,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Restored!',
                      text: 'Adjustment restored',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        $.fn.loadTable();
                      }
                    })


              }
            });

    };
     $.fn.loadTable();
</script>
{{ Html::script('/vendor/autocomplete/jquery.autocomplete.js') }}
<script type="text/javascript">
    var collection_type = 'show_in_cashdivision';
</script>
@include('collection::shared.transactions_js')
@endsection
