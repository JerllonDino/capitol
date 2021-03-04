@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['hospitals.store']]) }}
    <div class="form-group col-sm-4">
        <label for="start">Hospital Name</label>
        <input type="text" class="form-control" name="name" id="hospital_name" required autofocus>
    </div>


    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Add Hospital</button>
    </div>
    {{ Form::close() }}
</div>
<hr>
<table id="hospitals" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Hospital Name</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>

<!-- Modal -->
<div class="modal fade" id="editHospital" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
            {{ Form::open(['method' => 'POST', 'route' => ['hospitals.edit'], 'id' => 'edit_hospital']) }}
                
                <input type="hidden" name="id">

                <label for="start">Hospital Name</label>
                <input type="text" class="form-control" name="name" id="edit_name" required autofocus>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-body">
          Delete hospital?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="delete-confirm">Yes</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script type="text/javascript">
    $('#hospitals').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: false,
        ajax: '{{ route("collection.datatables", "hospitals") }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: null, name : '' ,
               render : function(data) {
                    return `
                        <button class="btn btn-primary" data-id="`+data.id+`" data-name="`+data.name+`" id="edit"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger" id="delete" data-id="`+data.id+`"><i class="fa fa-trash"></i></button>
                    `;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });

    $('#hospitals').on('click', '#edit', function(){
        var hospital_name = $(this).data('name');
        var id = $(this).data('id');
        $('#edit_hospital').find('input[name="name"]').val(hospital_name);
        $('#edit_hospital').find('input[name="id"]').val(id);

        $('#editHospital').modal('show');
    });

    $('#editHospital').on('hidden.bs.modal', function(){
        $('#edit_hospital').trigger('reset');
    });

    $('#hospitals').on('click', '#delete', function(){
        var id = $(this).data('id');
        console.log(id);
        $('#delete-confirm').data('id', id);

        $('#deleteConfirm').modal('show');
    });

    $('#delete-confirm').click(function(){
        var id = $(this).data('id');

        $.ajax({
            url: '{{ route("hospitals.delete") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id : id
            }
        }).done(function(res){
            location.reload();
        }).fail(function(){

        }).always(function(){

        });
    });

</script>
@endsection
