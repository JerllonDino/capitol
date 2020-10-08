@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
{{ Html::style('/base/sweetalert/sweetalert2.min.css') }}

@endsection

@section('content')
    <div class="form-inline">
        <div class="form-group">
            <label>Year</label>
            <select class="form-control" id="year">
                @for($year = \Carbon\Carbon::now()->format('Y') ; $year > 2015; $year--)
                    @if($year == \Carbon\Carbon::now()->format('Y'))
                        <option value="{{ $year }}" selected>{{ $year }}</option>
                    @else
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endif
                @endfor
            </select>
        </div>
        <button id="show" class="btn btn-default" onclick="$.fn.adj_table()">SHOW</button>
    </div>
    <br>
    <table class="table table-condensed" id="adj">
        <thead>
            <tr>
                <th>Month</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <!-- MODALS -->
    <div class="modal fade" id="edit_adj" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ route('cashdiv.adjustment_update') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button class="close pull-right" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Adjustment</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Change Type</label>
                            <select class="form-control" name="adj_type" id="adj_type">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input class="form-control" type="number" name="adj_amt" id="adj_amt" step="0.01">
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer">
                        <button class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
{{ Html::script('/base/sweetalert/sweetalert2.min.js') }}

<script type="text/javascript">
    $.fn.adj_table = function() {
        if ($.fn.DataTable.isDataTable('#adj')) {
            $('#adj').DataTable().destroy();
        }
        $('#adj').DataTable({
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("cashdiv.adjustment_dt") }}',
                data: {
                    '_token' : '{{ csrf_token() }}',
                    'year' : $('#year').val()
                }
            },
            columns: [
                { data: function(data) {
                        var months = [ 'January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
                        return months[data.month-1];
                    }, 
                    searchable: false, 
                    bSortable: false 
                },
                { data: 'type', name: 'type', bSortable: false },
                { data: 'amount', name: 'amount', searchable: false, bSortable: false },
                { data: function(data) {
                        return "<input type='hidden' value='"+data.id+"'><button class='btn btn-primary' id='edit_btn'><span class='glyphicon glyphicon-edit'></span>&nbsp;Edit</button>&nbsp;<button class='btn btn-danger' id='del_btn'><span class='glyphicon glyphicon-remove'></span>Delete</button>";
                    },
                    searchable: false, bSortable: false
                },
            ],
        });
    }
    $.fn.adj_table();
    $(document).on('click', '#edit_btn', function() {
        var closest = $(this).closest('tr');
        var id = $(this).siblings()[0];
        var type = closest.children()[1];
        var amt = closest.children()[2];
        var type_val = $(type).html();
        var amt_val = $(amt).html();
        var id_val = $(id).val();
        var types = [ 'OPAg', 'PVET', 'COLD CHAIN', 'CERTIFICATIONS OPP - DOJ', 'PROVINCIAL HEALTH OFFICE', 'RPT' ]

        $('#adj_type').empty();
        for(var i = 0; i < types.length; i++) {
            if (type_val == types[i]) {
                $('#adj_type').append('<option value="'+types[i]+'" selected>'+types[i]+'</option>');
            } else {
                $('#adj_type').append('<option value="'+types[i]+'">'+types[i]+'</option>');
            }
        }
        $('#id').val(id_val);
        $('#adj_amt').val(amt_val);
        $('#edit_adj').modal('show');
    });

    $(document).on('click', '#del_btn', function() {
        var id = $(this).siblings()[0];
        var id_val = $(id).val();
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
                    url: '{{ route("cashdiv.adjustment_delete") }}',
                    type: 'POST',
                    data:{
                      id: id_val,
                      _token: '{{ csrf_token() }}'
                    },
                    dataType: 'JSON',
                    success: (data) => {
                    }
                });
            swal({
                  title: 'Deleted!',
                  text: 'Cash Div Adjustment deleted',
                  timer: 1500,
                  onOpen: () => {
                    swal.showLoading()
                  }
                }).then((result) => {
                  if (result.dismiss === 'timer') {
                    $.fn.adj_table();
                  }
                })
          }
        });
    });
</script>
@endsection
