@extends('nav')

@section('css')
<style>

    .panel{
        background-color:#f5f5f5;
        padding: 10px;
    }

    #provincialShareModal > div {
        width: 95%;
    }

    .table-div{
        overflow-x: scroll;
        font-size: 10px;
    }

    .table-div input{
        font-size: 10px;
        padding: 5%;
    }
    
    .table-div th {
        text-align: center;
    }


</style>
@endsection

@section('content')
<div class="alert-container" style="display:none">
    
</div>

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
                        
                    </div>
                </div>
                    {{-- <button class="btn btn-primary" style="float: center" data-toggle="modal" data-target="#provincialShareModal">Search</button> --}}
                    <button class="btn btn-primary search-municipal-remittance" style="float: center">Search</button>
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

<div class="modal fade" id="provincialShareModal" tabindex="-1" role="dialog" aria-labelledby="provincialModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="provincialModalTitle">Provincial Share</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body table-div">
          <table class="table table-responsive table-bordered">
              <thead>
                    <tr>
                        <th colspan="19">Basic Tax</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th colspan="3">ADVANCE</th>
                        <th colspan="3">CURRENT</th>
                        <th colspan="2">Immediate Year</th>
                        <th colspan="2">-1992</th>
                        <th colspan="2">1991 & below</th>
                        <th colspan="5">PENALTIES</th>
                        <th>TOTAL</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>%</th>
                        <th>AMOUNT</th>
                        <th>DISCOUNT</th>
                        <th>%</th>
                        <th>AMOUNT</th>
                        <th>DISCOUNT</th>
                        <th>%</th>
                        <th>AMOUNT</th>
                        <th>%</th>
                        <th>AMOUNT</th>
                        <th>%</th>
                        <th>AMOUNT</th>
                        <th>%</th>
                        <th>CURRENT</th>
                        <th>IMMEDIATE</th>
                        <th>-1992</th>
                        <th>1991 & below</th>
                        <th></th>
                    </tr>
              </thead>
              <tbody id="provincial-tbody">
                  <input type="hidden" name="id">
                  <tr>
                      <td>Provincial Share</td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_advance_amount"></td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_advance_discount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_current_amount"></td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_current_discount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_immediate_amount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_1992_amount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_1991_amount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_penalty_current"></td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_penalty_immediate"></td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_penalty_1992"></td>
                      <td><input type="text" class="form-control basic-provincial-values" id="basic_penalty_1991"></td>
                      <td><input type="text" class="form-control" id="basic_provincial_total"></td>
                  </tr>
                  <tr>
                      <th colspan="19">SEF TAX</th>
                  </tr>
                  <tr>
                    <tr>
                        <td>Provincial Share</td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_advance_amount"></td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_advance_discount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_current_amount"></td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_current_discount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_immediate_amount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_1992_amount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_1991_amount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_penalty_current"></td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_penalty_immediate"></td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_penalty_1992"></td>
                        <td><input type="text" class="form-control sef-provincial-values" id="sef_penalty_1991"></td>
                        <td><input type="text" class="form-control" id="sef_provincial_total"></td>
                    </tr>
                  </tr>
              </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
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

$('#provincial-tbody input').on('keyup change', function(){
    computeProvincial();
})

$('.search-municipal-remittance').click(function(e){
    $('.alert-container').hide();
    var municipality = $('#municipality').val();
    var month = $('#remit_month').val();
    var year = $('#remit_year').val();

    $.ajax({
        url: '{{ route("rpt.search_provincial_share") }}',
        type: 'GET',
        data: {
            'municipality': municipality,
            'month' : month,
            'year' : year
        },
        beforeSend: function(){

        }
    }).done(function(response){
        var provincialShare = JSON.parse(response);
        var ctr = 0;
        var basicTotal = 0;
        var sefTotal = 0;
        if (response != 0) {
            for (const key in provincialShare) {
                $('#provincial-tbody').find("#"+key).val(parseFloat(provincialShare[key]).toFixed(2));
                ctr++;
            }
            computeProvincial();
            $('#provincialShareModal').modal('show');
        }else{
            $('.alert-container').show().html(`
                <div class="alert alert-danger">
                    No data found.
                </div>
            `);
            
        }
    }).fail(function(error){
        $('.alert-container').html(`
            <div class="alert alert-danger">
                Uh oh, Something went wrong, please refresh and try again.
            </div>
        `);
        $('.alert-container').show();
    });
});

$('.modal').on('hidden.bs.modal', function () {
  $('#provincial-tbody input').val('');
})

function computeProvincial()
{
    var basicTotal = 0;
    var sefTotal = 0;
    $('.basic-provincial-values').each(function() {
        basicTotal += Number(parseFloat($(this).val()).toFixed(2));
        console.log(basicTotal);
    });
    $('.sef-provincial-values').each(function() {
        sefTotal += Number(parseFloat($(this).val()).toFixed(2));
    });
    $('#provincial-tbody').find('#basic_provincial_total').val(parseFloat(basicTotal).toFixed(2));
    $('#provincial-tbody').find('#sef_provincial_total').val(parseFloat(sefTotal).toFixed(2));
}

function getMonthName(monthNumber) {
      var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      return months[monthNumber - 1];
}
</script>
@endsection

@endsection