@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection



@section('content')
<div class='row'>
    @if ( Session::has('success') )
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>
            <strong>{{ Session::get('success') }}</strong>
        </div>
        @endif
     
        @if ( Session::has('error') )
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>
            <strong>{{ Session::get('error') }}</strong>
        </div>
        @endif
     
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
          <div>
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif
     
    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        Choose your xls/csv File : <input type="file" name="file" class="form-control">
     
        <input type="submit" class="btn btn-primary btn-lg" style="margin-top: 3%">
    </form>
    </div>
    
<div class="row">
    <div class="col-lg-12">
        <table id="mpi" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Month</th>
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
    $('#mpi').DataTable({
        pageLength: 50,
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "monthly_sand_gravel") }}',
        columns: [
            { data:
                function(data) {
                    var monthNames = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December',
                    ];
                    return monthNames[data.month - 1];
                },
                name: 'month'
            },
            { data: 'year', name: 'year' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    @if ( Session::get('permission')['col_monthly_provincial_income'] & $base['can_read'] )
                    view = '<a href="{{ route('sandgravel.monthly_view',[null,null]) }}/'+data.year+'/'+data.month+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif

                    @if ( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write'] )
                    write = '<a href="{{ route('sandgravel.monthly_edit',[null,null]) }}/'+data.year+'/'+data.month+'" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif
                    return view + write;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });

    @if ( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write'] )
        $("div.dt-custom").html(
            '<a href="{{ route("sandgravel.monthly_create") }}" class="btn btn-med btn-success">Add</a>'
        );


    @endif
</script>

@endsection
