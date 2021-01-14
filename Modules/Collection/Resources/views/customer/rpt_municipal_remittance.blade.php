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
@if (session('successMessage'))
        <div class="alert alert-success">{{ session('successMessage') }}</div>
    @endif
<div class="alert-container" style="display:none">
    
</div>

<div class="row">
    <div class="col col-sm-6">
        <div class="panel">
                <h3 style="margin-top: 10px">Imported Municipal Remittances</h3>
                <div class="row">
                    <div class="col col-sm-12">
                        <div class="form-group row">
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
                            <div class="col-sm-4">
                                <button class="btn btn-primary search-municipal-remittance form-control" onclick="getImported()" style="margin-top: 17%">Show</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-hovered" id="imported_excel">
                    <thead>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Municipality</th>
                        <th>Date Imported</th>
                        <th>Action</th>
                    </thead>
                    <tbody></tbody>
                </table>
        </div>
    </div>
    <h3>Verified Remittances</h3>
    <div class="col-sm-6">
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
            <button class="btn btn-primary form-control" style="margin-top: 16%" onclick="getRemittances()">Show</button>
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
                        <th colspan="2" class="th-immediate">Immediate Year</th>
                        <th colspan="2" class="th-less-immediate">-1992</th>
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
                        <th class="th-immediate">IMMEDIATE</th>
                        <th class="th-less-immediate">-1992</th>
                        <th>1991 & below</th>
                        <th></th>
                    </tr>
              </thead>
              <tbody id="provincial-tbody">
                  <form action="{{ route('rpt.verify_provincial_share') }}" method="post">
                    {{ csrf_field() }}
                  <input type="hidden" name="id">
                  <input type="hidden" name="is_verified">
                  <tr>
                      <td>Provincial Share</td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_advance_amount"></td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_advance_discount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_current_amount"></td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_current_discount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_immediate_amount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_1992_amount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_1991_amount"></td>
                      <td>35%</td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_penalty_current"></td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_penalty_immediate"></td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_penalty_1992"></td>
                      <td><input type="text" class="form-control basic-provincial-values" name="basic_penalty_1991"></td>
                      <td><input type="text" class="form-control" id="basic_provincial_total" disabled></td>
                  </tr>
                  <tr>
                      <th colspan="19">SEF TAX</th>
                  </tr>
                  <tr>
                    <tr>
                        <td>Provincial Share</td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_advance_amount"></td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_advance_discount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_current_amount"></td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_current_discount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_immediate_amount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_1992_amount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_1991_amount"></td>
                        <td>35%</td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_penalty_current"></td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_penalty_immediate"></td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_penalty_1992"></td>
                        <td><input type="text" class="form-control sef-provincial-values" name="sef_penalty_1991"></td>
                        <td><input type="text" class="form-control" id="sef_provincial_total" disabled></td>
                    </tr>
                  </tr>
              </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Verify</button>
        </div>
        </form>
      </div>
    </div>
  </div>

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script>
getRemittances();
getImported();

function computeProvincial()
{
    var basicTotal = 0;
    var sefTotal = 0;
    $('.basic-provincial-values').each(function() {
        basicTotal += Number(parseFloat($(this).val()).toFixed(2));
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

function showProvincialShareModal(provincialShare)
{
    var ctr = 0;
    if (provincialShare != 0) {
            $('.th-immediate').text($('#remit_year').val()-1);
            $('.th-less-immediate').text(($('#remit_year').val()-2) + '-1992');
            for (const key in provincialShare) {
                $('#provincial-tbody').find("input[name='"+key+"']").val(( key == 'is_verified' || key == 'id' ? provincialShare[key] : parseFloat(provincialShare[key]).toFixed(2)));
                ctr++;
            }
            computeProvincial();
            $('#provincialShareModal').modal('show');
        }else{
            $('.alert-container').show().html(`
                <div class="alert alert-danger">
                    Sorry, No data was found.
                </div>
            `);
        }
}

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
                'isVerified' : 1,
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
                return `<button class="btn btn-info view-report verified" data-values='`+data.provincial_id+`'><i class="fa fa-spinner fa-spin" style="display:none"></i> <i class="fa fa-eye"></i></button>`;
            } }
        ],
    });
}

function getImported(){
    if($.fn.DataTable.isDataTable('#imported_excel')) {
        $('#imported_excel').DataTable().destroy();
    }
    $('#imported_excel').DataTable({
        processing: true, 
        serverSide: false,
        deferRender: true,
        order: [[ 0, 'desc' ]],
        ajax: {
            url: "{{ route('rpt.get_municipal_remittances') }}",
            data: {
                'isVerified' : 0,
                'report_year' : $('#remit_year').val(),
                'report_month' : $('#remit_month').val()
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
                return `<button class="btn btn-info view-report imported" data-values='`+data.provincial_id+`'><i class="fa fa-spinner fa-spin" style="display:none"></i> <i class="fa fa-eye"></i></button>`;
            } }
        ],
    });
}

$('#provincial-tbody input').on('keyup change', function(){
    computeProvincial();
});

$('.modal').on('hidden.bs.modal', function () {
  $('#provincial-tbody input').val('');
});

$('#remittances, #imported_excel').on('click', '.view-report', function(){
    var data_id = $(this).data('values');
    $.ajax({
        url: '{{ route("rpt.get_provincial_share") }}',
        type: 'GET',
        data: {
            'data_id': data_id,
        },
        beforeSend: function(){

        }
    }).done(function(response){
        var provincialShare = JSON.parse(response);
        showProvincialShareModal(provincialShare);
    }).fail(function(){

    });
});

</script>
@endsection

@endsection