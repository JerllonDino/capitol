@extends('nav')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <table id="report_officer_position" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Position</th>
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
{{ Html::script('/base/sweetalert/sweetalert2.all.min.js') }}
{{ Html::script('/base/sweetalert/sweetalert2.min.js') }}


<script>

    $('#report_officer_position').dataTable({
        pageLength: 50,
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "report_officer_position") }}',
        columns: [
            { data: 'position', name: 'position' },
            { data:
                function(data) {
                    var write = '';
                    var can_delete = ''; 

                    @if ( Session::get('permission')['col_settings'] & $base['can_write'] )
                    write = '<a href="{{ route('settings_report_officers.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif

                    @if ( Session::get('permission')['col_settings'] & $base['can_delete'] )
                    if(data.deleted_at == null){
                        can_delete = write +'<button onclick="$(this).delete(\''+data.id+'\');"  class="btn btn-sm btn-danger datatable-btn" title="Delete"><i class="fa fa-trash"></i></button>';
                    }
                    else{
                        can_delete = '<button onclick="$(this).restore(\''+data.id+'\');"  class="btn btn-sm btn-warning datatable-btn" title="Restore"><i class="fa fa-undo"></i></button>';
                    }                    
                    @endif

                    return can_delete;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });

    $.fn.delete = function(id){
        swal({
          title: 'Are you sure?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                    $.ajax({
                        url: '{{route('settings_report_officers.delete_new')}}',
                        type: 'POST',
                        data:{
                          idd: id,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Deleted!',
                      text: 'Report Officer deleted',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        //location.reload();
                        $('#report_officer_position').DataTable().ajax.reload();
                      }
                    })
            }
        });
    };

    $.fn.restore = function(id){
        swal({
          title: 'Are you sure?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
          if (result.value) {
                $.ajax({
                        url: '{{route('settings_report_officers.restore_new')}}',
                        type: 'POST',
                        data:{
                          idd: id,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
            swal({
                      title: 'Restored!',
                      text: 'Report Officer restored',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        //location.reload();
                        $('#report_officer_position').DataTable().ajax.reload();
                      }
                    })
          }
        });
    };

    @if ( Session::get('permission')['col_settings'] & $base['can_write'] )
        $("div.dt-custom").html(
            '<a href="{{ route("settings_report_officers.create") }}" class="btn btn-med btn-success">Add</a>'
        );
    @endif

</script>


@endsection
