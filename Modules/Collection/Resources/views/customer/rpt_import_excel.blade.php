@extends('nav')

@section('css')
<style>
	#excel-container .table td{
		padding:2px !important;
		font-size: 10px;
		margin:0;
		width: 50px;
	}
	#excel-container .table thead tr th{
		padding:2px !important;
		font-size: 10px;
		background: #d9fffe;
		position: sticky;
        top: 0px;
	}

	#excel-container .table thead{
		background-color: white;
	}
	
	#imports{
		/* padding:10px; */
		/* */
		cursor: pointer;
		outline:none;
	}

	#importExcel {
		display: flex;
		flex-flow: row wrap;
		align-items: center;
	}
    
    .btn{
        outline: none !important; 
        /* padding: 10px; */
        /* */
    }

    #import-excel{
        float:right;
        cursor: pointer;
    }

    #imgViewer::-webkit-scrollbar {
    -webkit-appearance: none;
    height: 10px;
}
#imgViewer::-webkit-scrollbar-thumb {
    /* border-radius: 5px; */
    background-color: rgba(0,0,0,.5);
    box-shadow: 0 0 1px rgba(255,255,255,.5);
}

.panel{
    background-color:#f5f5f5;
    padding: 10px;
}

</style>
@endsection

@section('content')
@if(session('successMessage'))
<div class="alert alert-success alert-dismissible">{{ session('successMessage') }}</div>
@endif
{{-- <form enctype="multipart/form-data" id="importExcel" method="post" action="{{ route('rpt.import_excel_report') }}" class="form-inline"> --}}
<h1 id="import-excel" data-toggle="modal" data-target="#modalHelp"><i class="fa fa-question-circle"></i></h1>
<div class="panel">
    <div class="container-fluid">
<h3 style="margin-top: 0px">Import Municipal Remittance</h3>
<div class="row" style="height: 40px">
    <div class="col col-sm-2"><h4>Municipality:</h4></div>
    <div class="col col-sm-2"><h4>Month:</h4></div>
    <div class="col col-sm-2"><h4>Year:</h4></div>
    <div class="col col-sm-3"></div>
    <div class="col col-sm-2"></div>
</div>
<form enctype="multipart/form-data" id="importExcel" method="post" action="" class="row">
    {{ csrf_field() }}
        <div class="col col-sm-2">
            <select name="excel_municipality" id="excel-municipality" class="form-control" style="margin-left: 3%; outline: none;">
                @foreach($base['municipality'] as $m)
                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
            </select>
        </div>
    <div class="col col-sm-2">
        <select name="excel_month" id="excel-month" class="form-control" style="margin-left: 3%; margin-right: 3%; outline: none;">
            @foreach($base['months'] as $i => $month)
                @if ($i+1 == date('n'))
                <option value="{{ $i+1 }}" selected>{{ $month }}</option>
                @else
                <option value="{{ $i+1 }}">{{ $month }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="col col-sm-2">
        <input type="int" value="{{ date('Y') }}" name="excel_year" class="form-control" style="margin-left: 3%; margin-right: 3%; outline: none;">
    </div>
    <div class="col col-sm-3">
        <input type="file" name="imports" id="imports" class="btn btn-warning form-control">
    </div>
    <div class="col col-sm-2">
        <button type="submit" class="btn btn-primary form-control" style="margin-left: 3%;" id="submitExcel"> <i class="fa fa-spinner fa-spin" style="display: none"></i> &nbsp;<i class="fa fa-upload"></i> Upload Excel file</button>
    </div>
    
</form>
</div>
</div>
<br>
<h3>Imported Municipal Remittances</h3>
<div class="form-inline" style="margin-top: 30px">
    <div class="form-group">
        <label for="report_month">Month</label>
        <select name="report_month" class="form-control" id="report_month">
            @foreach($base['months'] as $i => $month)
                @if ($i+1 == date('n'))
                <option value="{{ $i+1 }}" selected>{{ $month }}</option>
                @else
                <option value="{{ $i+1 }}">{{ $month }}</option>
                @endif
            @endforeach
        </select>
    </div>
    
    <div class="form-group" style="margin-left: 20px">
        <label for="report_year">Year</label>
        <input type="text" name="report_year" class="form-control" id="report_year" value="{{ date('Y') }}">
    </div>
    <button id="show_imported" class="btn btn-primary" style="margin-left: 20px" onclick="importedExcelDatatable()">Show</button>
</div>
<br>
<table class="table table-hovered" id="imported-excel" style="margin-top: 20px">
    <thead>
        <th>Year</th>
        <th>Month</th>
        <th>Municipality</th>
        <th>Date Imported</th>
        <th>Action</th>
    </thead>
</table>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalHelp">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">A guide to importing municipal report excel file to the system</h4>
          </div>
        <div class="modal-body">
            <h3>Step 1: Checking and validating the excel file</h3>
            <h4>Please check the contents of the excel file if it meets the requirements before uploading it to the system. The requirements are as follows:</h4>

            <div class="panel">
                <div id="imgViewer" style="overflow-x: scroll;">
                    <img src="{{ asset('asset/images/import-guide/step-1.1.png') }}" alt="" class="guide-images">
                </div>
                <h5>Make sure that the table headers of the excel file you are uploading have the exact same header and exact letter column position as showed from the figure above. (Click the image to enlarge.)</h5>
            </div>

            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/step-1.2.png') }}" alt=""><img src="{{ asset('asset/images/import-guide/step-1.8.png') }}" alt="">
                <h5>It is mandatory to put dates on every record for the system to recognize every data in the excel file. The format to be used to input dates in the excel file should be DD/MM/YYYY, not the word.</h5>
            </div>

            <div class="panel">
                <h4>Column skipping if under the same Official receipt number</h4>
                <img src="{{ asset('asset/images/import-guide/step-1.5.png') }}" alt="">
                <h5>The columns allowed to be skipped are the Name of the Taxpayor and the OR number. (Refer to the above figure)</h5>
            </div>

            <div class="panel">
                <h4>No using of abbreviations in barangay names</h4>
                <img src="{{ asset('asset/images/import-guide/step-1.6.png') }}" alt="">
                <h5>Type the whole word when adding barangay names to a record in the excel file</h5>
            </div>

            <h3>Step 2: Uploading  the excel file</h3>
            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/choose.png') }}" style="width: 100%" alt="">
                <h4>Click the choose file button. A new window will appear. In this window, you will now locate and open the excel file you will upload.</h4>
            </div>
            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/muni.png') }}" style="width: 100%" alt="">
                <h4>Once the file has been selected or if you can see the name of the file appeared in the choose file button, select what municipality the report belongs to.</h4>
            </div>
            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/upload.png') }}" style="width: 100%" alt="">
                <h4>Once the proper municipality has been selected. Click the Excel File Button.</h4>
            </div>

            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/success1.png') }}" style="width: 100%" alt="">
                <h4>If there were no errors, a table should appear below the form inputs containing the data from the excel file.</h4>
                <h4>Click Save Import if everything is correct.</h4>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" data-dismiss="modal">Ok, I understand</button>
        </div>
         
      </div>
    </div>
  </div>

  <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
            Municipal Report already exist! Do you want to overwrite?
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger confirm-save">Yes</button>
            <button class="btn btn-secondary" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>  

  <div class="modal fade" id="excel-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-body">
                <div id="excel-container" style="padding-top: 30px; width: 100%; height: 100%; overflow:scroll">

                    <table class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th rowspan="4">Date</th>
                                <th rowspan="4">Name of Tax Payor</th>
                                <th rowspan="4">Period Covered</th>
                                <th rowspan="4">Official Receipt Number</th>
                                <th rowspan="4">TD/ARP No.</th>
                                <th rowspan="4">Name of Brgy.</th>
                                <th rowspan="4">Classifi <br> cation</th>
                                <th colspan="11">BASIC TAX</th>
                                <th rowspan="4">Sub-total Gross Collection</th>
                                <th rowspan="4">Sub-total Net Collection</th>
                                <th colspan="11">SPECIAL EDUCATION FUND</th>
                                <th rowspan="4">Sub-total Gross Collection</th>
                                <th rowspan="4">Sub-total Net Collection</th>
                                <th rowspan="4">Grand Total Gross Collection</th>
                                <th rowspan="4">Grand Total Net Collection</th>
                            </tr>
                            <tr>
                                <!-- basic --> 
                                <th style="top: 20px" colspan="2" rowspan="2">Advance</th>
                                <th style="top: 20px" colspan="2" rowspan="2">Current Year</th>
                                <th style="top: 20px" rowspan="3" class="table-immediate">'.(date("Y")-1).'</th>
                                <th style="top: 20px" colspan="2" rowspan="2">PRIOR YEARS</th>
                                <th style="top: 20px" colspan="4">PENALTIES</th>
                                <!-- sef --> 
                                <th style="top: 20px" colspan="2" rowspan="2">Advance</th>
                                <th style="top: 20px" colspan="2" rowspan="2">Current Year</th>
                                <th style="top: 20px" rowspan="3" class="table-immediate">'.(date("Y")-1).'</th>
                                <th style="top: 20px" colspan="2" rowspan="2">PRIOR YEARS</th>
                                <th style="top: 20px" colspan="4">PENALTIES</th>
                            </tr> 
                            <tr>
                                <!-- basic -->
                                <th style="top: 40px" rowspan="2" class="table-current">'.date("Y").'</th>
                                <th style="top: 40px" rowspan="2" class="table-immediate">'.(date("Y")-1).'</th>
                                <th style="top: 40px" colspan="2">PRIOR YEARS</th>
                                <!-- sef -->
                                <th style="top: 40px" rowspan="2" class="table-current">'.date("Y").'</th>
                                <th style="top: 40px" rowspan="2" class="table-immediate">'.(date("Y")-1).'</th>
                                <th style="top: 40px" colspan="2">PRIOR YEARS</th>
                            </tr>
                            <tr>
                                <!-- basic -->
                                <th style="top: 60px">Gross Amount</th>
                                <th style="top: 60px">
                                   Disc
                                </th>
                                <th style="top: 60px">Gross Amount</th>
                                <th style="top: 60px">
                                   Disc
                                </th>
                                <th style="top: 60px"><span class="table-prior"></span>-1992</th>
                                <th style="top: 60px">1991 & Below</th>
                                <th style="top: 60px"><span class="table-prior"></span>-1992</th>
                                <th style="top: 60px">1991 & Below</th>
                    
                                <!-- sef -->
                                <th style="top: 60px">Gross Amount</th>
                                <th style="top: 60px">
                                    Disc
                                </th>
                                <th style="top: 60px">Gross Amount</th>
                                <th style="top: 60px">
                                   Disc
                                </th>
                                <th style="top: 60px"><span class="table-prior"></span>-1992</th>
                                <th style="top: 60px">1991 & Below</th>
                                <th style="top: 60px"><span class="table-prior"></span>-1992</th>
                                <th style="top: 60px">1991 & Below</th>
                            </tr>
                        </thead>
                        <tbody id='excel-tbody'>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('rpt.save_excel_report') }}" id="excel-form" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="excel_data">
                    <input type="hidden" name="excel_provincial">
                    <input type="hidden" name="excel_final_municipality">
                    <input type="hidden" name="excel_final_month">
                    <input type="hidden" name="excel_final_year">
                    <button data-dismiss="modal" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-success" id="save-import"><i class="fa fa-save"></i> Save Import</button>
                </form>
            </div>
        </div>
    </div>
  </div>
  

@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}

<script type="text/javascript">
    importedExcelDatatable();
    $excelImport = $('#importExcel');
	$excelContainer = $('#excel-tbody');
    $importButton = $('#submitExcel');
    $tableCurrent = $('.table-current');
    $tableImmediate = $('.table-immediate');
    $tablePrior = $('.table-prior');

    function getMonthName(monthNumber) {
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return months[monthNumber - 1];
    }
    
    function importedExcelDatatable()
    {
        if($.fn.DataTable.isDataTable('#imported-excel')) {
            $('#imported-excel').DataTable().destroy();
        }
        $('#imported-excel').DataTable({
            processing: true, 
            serverSide: false,
            deferRender: true,
            order: [[ 0, 'desc' ]],
            ajax: {
                url: "{{ route('rpt.get_imported_excels') }}",
                data: {
                    'report_year' : $('#report_year').val(),
                    'report_month' : $('#report_month').val()
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
                    return `<button class="btn btn-info view-report" data-values='`+data.id+`'><i class="fa fa-spinner fa-spin" style="display:none"></i> <i class="fa fa-eye"></i></button>`;
                } }
            ],
        });
    }
    
	$excelImport.submit(function(e){
        e.preventDefault();
        $excelContainer.html('');
        $('#excel-modal').find('#save-import').hide();
		data = new FormData(this);
        if ($('#imports').val()) {
            $.ajax({
                url: '{{ route("rpt.view_excel_report") }}',
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $importButton.find('.fa-spinner').removeAttr('style');
                },
            }).done(function(data){
                if (data.html) {
                    $excelContainer.html(data.html);
                    var excelYear = $excelImport.find('input[name="excel_year"]').val();
                    $tableCurrent.text(excelYear);
                    $tableImmediate.text(excelYear-1);
                    $tablePrior.text(excelYear-2);
                    $('#excel-form').find('input[name="excel_data"]').val(JSON.stringify(data.data));
                    $('#excel-form').find('input[name="excel_provincial"]').val(JSON.stringify(data.provincial));
                    $('#excel-modal').modal('show');
                }else{
                    showMessage(data.message, 1);
                }
            }).fail(function(error){
                showMessage('Sorry, something went wrong. Please refresh the page and try again.', 1);
            }).always(function(){
                $importButton.find('.fa-spinner').css('display', 'none');
            });
        }else{
            showMessage('Sorry, you haven\'t chosen a file yet. Please choose a file first before pressing the button.', 1);
        }
    });

    $('#save-import').click(function(e){
        e.preventDefault();
        $('#excel-form').find('input[name="excel_final_municipality"]').val($excelImport.find('[name="excel_municipality"]').val());
        $('#excel-form').find('input[name="excel_final_month"]').val($excelImport.find('[name="excel_month"]').val());
        $('#excel-form').find('input[name="excel_final_year"]').val($excelImport.find('input[name="excel_year"]').val());
        $.ajax({
            url : "{{ route('rpt.get_municipal_excel') }}",
            type: 'get',
            data: {
                'municipality' : $excelImport.find('[name="excel_municipality"]').val(),
                'month': $excelImport.find('[name="excel_month"]').val(),
                'year': $excelImport.find('input[name="excel_year"]').val()
            },
            beforeSend: function(){
                
            }
        }).done(function(data){
            if(data){
                $('.modal').modal('hide');
                $('#confirm-modal').modal('show');
            }else{
                $('#excel-form').submit();
            }
        }).fail(function(){

        });
    });
    $('.confirm-save').click(function(){
        $('#excel-form').submit();
    });

    $('#imported-excel').on('click', '.view-report', function(){
        $excelContainer.html('');
        $.ajax({
            url: "{{ route('rpt.get_imported_excel') }}",
            path: 'GET',
            data: {
                'excel_id' : $(this).data('values')
            }
        }).done(function(response){
            console.log(response.disposition);
            var html = '';
            var excelYear = $('#report_year').val();
            $tableCurrent.text(excelYear);
            $tableImmediate.text(excelYear-1);
            $tablePrior.text(excelYear-2);
            response.items.forEach(item => {
                html += '<tr>';
                    item.forEach(itemData => {
                       html += '<td>'+(itemData == "0.00" ? '' : itemData)+'</td>'; 
                    });
                html += '</tr>' 
            });
            html += response.disposition;
            $excelContainer.html(html);
            $('#excel-modal').find('#save-import').hide();
            $('#excel-modal').modal('show');
        }).fail(function(){

        });

    });


</script>
@endsection