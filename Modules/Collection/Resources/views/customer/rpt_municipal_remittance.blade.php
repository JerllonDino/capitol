@extends('nav')

@section('css')
<style>

    .panel{
        background-color:#f5f5f5;
        padding: 10px;
    }


</style>
@endsection

@section('content')

<div class="row">
    <div class="col col-sm-12">
        <div class="panel">
            <div class="container-fluid">
                <div class="row">
                    <div class="col col-sm-12">
                        <div class="form-group row">
                            <div class="col col-sm-4">
                                <label for="municipality">Municipality</label>
                                <select name="municipality" id="municipality" class="form-control">
                                    @foreach($base['municipality'] as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col col-sm-4">
                                <label for="remit_month">Month</label>
                                <select name="remit_month" id="remit_month" class="form-control">
                                    @foreach($base['months'] as $i => $month)
                                        <option value="{{ $i+1 }}" {{ $i+1 == date('n') ? "selected" : "" }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col col-sm-4">
                                <label for="remit_year">Year</label>
                                <input type="text" name="remit_year" id="remit_year" class="form-control" value="{{ date('Y') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col col-sm-6">
                                <label for="current_year">Current Year</label>
                                <input type="text" name="current_year" id="current_year" class="form-control" value="{{ date('Y') }}">
                            </div>
                            <div class="col col-sm-6">
                                <label for="immediate_year">Immediate Preceeding Year</label>
                                <input type="text" name="immediate_year" id="immediate_year" class="form-control" value="{{ date('Y')-1 }}">
                            </div>
                        </div>
                        
                    </div>
                </div>
                    <button class="btn btn-primary" style="float: center">Save</button>
            </div>
        </div>
        
        <h3>Municipal Remittances</h3>
        <div class="row">
            <div class="col col-sm-4">
                <label for="search_month">Month</label>
                <select name="search_month" id="search_month" class="form-control">
                    @foreach($base['months'] as $i => $month)
                        <option value="{{ $i+1 }}" {{ $i+1 == date('n') ? "selected" : "" }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col col-sm-4">
                <label for="search_year">Year</label>
                <input type="number" name="search_year" id="search_year" class="form-control" value="{{ date('Y') }}">
            </div>
            <div class="col col-sm-4">
                <button class="btn btn-primary" style="margin-top: 7%" onclick="getRemittances()">Show</button>
            </div>
            
        </div>
        <br>
        <table id="remittances" class="table table-responsive table-striped table-hover">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Municipality</th>
                    <th>Date Imported</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script>
getRemittances();
function getRemittances(){
    if($.fn.DataTable.isDataTable('#remittances')) {
        $('#remittances').DataTable().destroy();
    }
    $('#remittances').DataTable({
        processing: true, 
        serverSide: false,
        deferRender: true,
        order: [[ 0, 'desc' ]],
        ajax: {
            url: "{{ route('rpt.get_municipal_remittances') }}",
            data: {
                'report_year' : $('#search_year').val(),
                'report_month' : $('#search_month').val()
            }
        },
        columns: [
            { data: 'report_year', name: 'report_year' },
            { data: null, render: function(data){
                return getMonthName(data.report_month);
            } },
            { data: 'municipality_name', name: 'municipality_name' },
            { data: 'created_at', name: 'created_at' },
            { data: null, render: function(data) {
                return `<button class="btn btn-info view-generated-report" data-values='`+JSON.stringify(data)+`'><i class="fa fa-spinner fa-spin" style="display:none"></i> <i class="fa fa-eye"></i></button>`;
            } }
        ],
    });
}

function getMonthName(monthNumber) {
      var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      return months[monthNumber - 1];
}
</script>
@endsection

@endsection