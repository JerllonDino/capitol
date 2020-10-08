@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
    <div class="form-inline">
        <div class="form-group">
            <label>MONTH</label>
            <select class="form-control" id="month">
                <option value="all">All</option>
                <?php
                    $months = [ '1' => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                ?>
                @for($i = 1; $i <= 12; $i++)
                    @if(\Carbon\Carbon::now()->format('m') == $i)
                        <option value="{{ $i . " " . \Carbon\Carbon::createFromDate($base['yr'], $i, 1)->endOfMonth()->format('d') }}" selected>{{ $months[$i] }} </option>
                    @else
                        <option value="{{ $i . " " . \Carbon\Carbon::createFromDate($base['yr'], $i, 1)->endOfMonth()->format('d') }}">{{ $months[$i] }}</option>
                    @endif
                @endfor
            </select>
        </div>
        <div class="form-group">
            <div class="form-group">
                <label for="show_year">YEAR</label>
                <input type="number" min="2017" max="{{ date('Y') }}" class="form-control" id="year" name="year" value="{{ $base['yr'] }}">
            </div>
            
        </div>
        <button class="btn btn-default" id="show" onclick="$.fn.delinquent_payors()">SHOW</button>
        <a href="{{ route('rpt.delqnt.print_notice', ['mnth', 'yr', 'date' ]) }}" id="gen_notice" class="btn btn-primary">Generate Notice</a>
    </div> 
    <br>
    <table class="table table-responsive table-condensed" id="delqnt_payors">
        <thead>
            <tr>
                <th>Name</th>
                <th>Last Paid</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

    <div class="modal fade" id="limit">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Print Limit<button class="close pull-right" data-dismiss="modal">&times;</button></span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Limit Results</label>
                        <select class="form-control" id="pages">
                            
                        </select>
                    </div>
                    <div class="form-group" id="hide_pages" style="display: none;">
                        <div class="col-sm-6">
                            <label id="pages_label"></label>
                            <select id="dates" class="form-control">
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <a id="submit_print" class="btn btn-success pull-right">Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $.fn.delinquent_payors = function() {
            if($.fn.DataTable.isDataTable('#delqnt_payors')) {
                $('#delqnt_payors').DataTable().destroy();
            }
            $('#delqnt_payors').dataTable({
                dom: '<"dt-custom">frtip',
                processing: true,
                serverSide: true,
                ajax: 
                { 
                    'url' : '{{ route("rpt.delinquent_tbl") }}', 
                    'data' : { 
                        'year' : $('#year').val(),
                        'month' : $('#month').val(),
                        '_token' : '{{ csrf_token() }}',
                    },
                    // 'dataSrc': '',
                },
                columns: 
                [
                    { data: 'name', name: 'name', searchable: true, bSortable: false, },
                    { data: 
                        function(data) {
                            var date = new Date(data.last_pd);
                            var months = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            return months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();
                        },
                        searchable: false, 
                        bSortable: false, 
                    },
                    { data: null, name: null, 
                        render: function(data) {
                            var route = '{{ route("rpt.delqnt_view", "id") }}';
                            var route2 = route.replace('id', data.col_customer_id);
                            var route3 = '{{ route("rpt.delqnt_edit", "id") }}';
                            var route4 = route3.replace('id', data.col_customer_id);
                            return '<a href="'+route2+'" class="btn btn-info btn-sm" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>';
                            // return '<a href="'+route2+'" class="btn btn-info btn-sm" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>&nbsp;<a href="'+route4+'" class="btn btn-warning btn-sm" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>';
                        },
                        searchable: false,
                        bSortable: false,
                    },
                ], 
            });
        }

        $.fn.delinquent_payors();

        $(document).on('click', '#gen_notice', function(e) {
            e.preventDefault();
            if($('#month').val() == 'all') {
                $('#pages').empty();
                $('#pages').append('<option value="all" selected>All</option>');
            } else {    
                $('#pages').empty();    
                $('#pages').append('<option selected value="all">All</option>');
                $('#pages').append('<option value="pages">Specify Date</option>');
            }
            $('#limit').modal('show');

            $(document).on('change', '#pages', function() {
                if($(this).val() == 'pages') {
                    $('#hide_pages').css('display', 'block');
                } else {
                    $('#hide_pages').css('display', 'none');
                }

                var route = $('#gen_notice').attr('href');
                var route2 = route.replace('mnth', $('#month').val());
                var route3 = route2.replace('yr', $('#year').val());
                var split = $('#month').val();
                var explode = split.split(' ');
                var months = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                if($('#pages').val() == 'pages') {
                    $('#pages_label').html('');
                    $('#dates').empty();
                    $('#pages_label').html(months[explode[0]-1] + " " + $('#year').val());
                    for(var i = 1; i <= explode[1]; i++) {
                        $('#dates').append('<option value='+i+'>'+i+'</option>');
                    }
                }

                $(document).on('click', '#submit_print', function() {
                    if($('#pages').val() == 'pages') {
                        var route_1 = route3.replace('date', $('#dates').val());
                        route3 = route_1;
                    }
                    $('#submit_print').attr('href', route3);
                });
            });
        });
    </script>
@endsection
