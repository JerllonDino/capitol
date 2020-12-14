@extends('nav')

@section('css')
    {{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
    <style type="text/css">
        table, td {
            padding: 1px;
        }
        .center {
            width: 100%;
            text-align: center;
        }
        .border_all_table {
            margin: 1px;
            padding-top: 5px;
        }
        .border_all {
            border: 1px solid #000000;
            font-size: 10px;
        }
        .border_all_table tr th, .border_all_table tr td  {
            border: 1px solid #000000;
            /*font-size: 8px;*/ /* 10px */
            text-align: center;
        }
        .val {
            text-align: right;
        }
        .hidden {
            display: none;
        }
        .min_width {
            width: 1px;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .ctr {
            text-align: center;
        }
        .remdep {
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }
        .newpage {
            page-break-before: always;
        }
        .table-center {
            text-align: center;
            padding: 0;
            margin: 0;
        }
        .table-div {
            overflow-x: scroll;
            font-size: 10px;
        }
        #report_content_modal > div {
            width: 95%;
        }
        input[type=number] {
            text-align: right;
            width: 60px;
            font-size: 10px;
        }
        input{
            position: relative; 
            z-index: 10;
        }
        .ui-datepicker{
             z-index: 9999 !important;
             }
    </style>
@endsection

@section('content')
@if (session('isSaved'))
        <div class="alert alert-success">{{ session('isSaved') }}</div>
@endif
<h3>View/Edit Report</h3>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group col-sm-6">
            <label for="municipality">Municipality</label>
            <select class="form-control" name="search_municipality" id="search_municipality" required>
                @foreach ($base['municipalities'] as $mun)
                <option value="{{ $mun->id }}">{{ $mun->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="report_type">Report Type</label>
            <select name="report_type" id="report_type" class="form-control">
                <option value="button">Municipal Report</option>
                <option value="rpt_mun_report_collections">Municipal Report (Collection)</option>
                <option value="rpt_mun_report_summary_disposition">Municipal Report (Summary and Disposition)</option>
                <option value="rpt_mun_report_protest">Municipal Report (Paid under protest/Held in Trust)</option>
                <option value="rpt_mun_report_protest_col">Municipal Report Collections (Paid under protest/Held in Trust)</option>
                <option value="rpt_mun_report_protest_sd">Municipal Report Summary and Disposition (Paid under protest/Held in Trust)</option>
            </select>
    </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <label for="report_number">Report Number</label>
        <div class="input-group">
            <span class="input-group-addon">RPT-</span>
            <input type="text" class="form-control" id="report_number" placeholder="Please Input Report Number">
            <span class="input-group-addon btn btn-warning" style="width:5%" id="search-report"><i class="fa fa-search" aria-hidden="true"></i></span>
        </div>
        <div class="loading hidden">
            <i class="fa fa-spinner fa-spin"></i>
            <span>Searching...</span>
        </div>
    </div>
</div>
<hr>
<h3>Generate New Report</h3>
@if (session('isExist'))
    <div class="alert alert-danger">{{ session('isExist') }}</div>
@endif
<div class="row">
    {{ Form::open(['method' => 'GET', 'route' => ['pdf.real_property'], 'id' => 'pdf_rpt']) }}
        <div class="form-group col-sm-6">
            <label for="municipality">Municipality</label>
            <select class="form-control" name="municipality" id="municipality" required>
                @foreach ($base['municipalities'] as $mun)
                <option value="{{ $mun->id }}">{{ $mun->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group col-sm-2">
            <label for="report_no">Report No.</label>
            <div class="input-group">
                <span class="input-group-addon">RPT-</span>
                <input type="text" class="form-control" name="report_no" id="report_no" value="{{ $base['report_number'] }}" required>
            </div>
        </div>

         <div class="form-group col-sm-4">
            <label for="end_date">Report Date</label>
            <input type="text" class="form-control date" name="report_date" value="{{ date('m/d/Y') }}" required>
        </div>
        
        <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>

        <input type="hidden" name="isEdit" class="isEdit" value="0">

        <div class="form-group col-sm-12">
          <button type="submit" class="btn btn-primary rpt_report" name="button" id="confirm">Municipal Report</button>
          <button type="submit" class="btn btn-primary rpt_report" name="rpt_mun_report_collections" id="confirm">Municipal Report (Collections)</button>
          <button type="submit" class="btn btn-primary rpt_report" name="rpt_mun_report_summary_disposition" id="confirm">Municipal Report (Summary and Disposition)</button>
        </div>

        <!-- <div class="form-group col-sm-12">
          <button type="submit" class="btn btn-primary" name="rpt_excel" id="confirm">Municipal Report (Excel)</button>
          <button type="submit" class="btn btn-primary" name="rpt_collections_excel" id="confirm">Municipal Report (Collections) (Excel)</button>
          <button type="submit" class="btn btn-primary" name="rpt_summ_dispo_excel" id="confirm">Municipal Report (Summary and Disposition) (Excel)</button>
        </div> -->

        <!-- <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-primary" name="rpt_mun_report_advanced" id="confirm">Municipal Report (w/ Advanced Payment)</button>
            <button type="submit" class="btn btn-primary" name="rpt_mun_report_advanced_col" id="confirm">Municipal Report Collections (w/ Advanced Payment)</button>
            <button type="submit" class="btn btn-primary" name="rpt_mun_report_advanced_sd" id="confirm">Municipal Report Summary and Disposition (w/ Advanced Payment)</button>
        </div> -->

        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-primary rpt_report" name="rpt_mun_report_protest" id="confirm">Municipal Report (Paid under protest/Held in Trust)</button>
            <button type="submit" class="btn btn-primary rpt_report" name="rpt_mun_report_protest_col" id="confirm">Municipal Report Collections (Paid under protest/Held in Trust)</button>
            <button type="submit" class="btn btn-primary rpt_report" name="rpt_mun_report_protest_sd" id="confirm">Municipal Report Summary and Disposition (Paid under protest/Held in Trust)</button>
        </div>
    {{ Form::close() }}
</div>
<br>
<div class="row">
    {{ Form::open(['method' => 'GET', 'route' => ['pdf.real_property_consolidated']]) }}
         <div class="form-group col-sm-4 ">
                <label for="end_date">Report Date</label>
                <input type="text" class="form-control date" name="report_date" value="{{ date('m/d/Y') }}" required>
            </div>
           

        <div class="form-group col-sm-4">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-4">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>

        <div class="form-group col-sm-12">
          <button type="submit" class="btn btn-primary" name="button" id="confirm">Consolidated Municipal Report</button>
          <button type="submit" class="btn btn-primary" name="rpt_mun_report_collections" id="confirm">Consolidated Municipal Report (Collections)</button>
          <button type="submit" class="btn btn-primary" name="rpt_mun_report_summary_disposition" id="confirm">Consolidated Municipal Report (Summary and Disposition)</button>
        </div>
    {{ Form::close() }}
</div>
<br>
<div class="row">
    {{ Form::open(['method' => 'GET', 'route' => ['pdf.real_property_p2']]) }}
     <div class="form-group col-sm-6 ">
                <label for="end_date">Report Date</label>
                <input type="text" class="form-control date" name="report_date" value="{{ date('m/d/Y') }}" required>
            </div>
            <div class="form-group col-sm-6 ">
                <label for="end_date">Report No.</label>
                <input type="text" class="form-control" name="report_no" required>
            </div>
        <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>

        <div class="form-group col-sm-12">
          <button type="submit" class="btn btn-primary" name="button" id="confirm">Consolidated Report</button>
        </div>
    {{ Form::close() }}
</div>

<div class="modal fade" id="report_content_modal">
    <div class="modal-dialog modal-lg" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i>&times;</i></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('rpt.submit') }}" id="form">
                    {{ csrf_field() }}
                    <div id="addl_inputs">
                        <div id="addl_entries"></div>
                        {{-- <input type="hidden" name="municipality" id="munic">
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="hidden" name="report_no" id="report_num">
                        <input type="hidden" name="report_date" id="report_date"> --}}
                        <input type="hidden" name="btn_pdf" id="btn_pdf">
                        <input type="hidden" name="isEdit" class="isEdit" value="0">
                    </div>

                    <div id="report_content">
                    </div>

                    <br>
                    <div class="modal-footer" id="submit" style="display: none;">
                        <button class="btn btn-success pull-right" type="submit" name="view_report" class="submit_btn" style="margin: 0 0 0 1%"><i class="fa fa-eye"></i> View Report in PDF</button>
                        <button class="btn btn-success pull-right" type="submit" name="save_report" class="submit_btn"><i class="fa fa-spinner fa-spin" style="display:none">&nbsp</i> <i class="fa fa-save"></i> Save Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>
@endsection

@section('js')
<script>
    

    $('button[name="view_report"]').click(function(){
        $('#form').attr('target', '_blank');
    });

    $('button[name="save_report"]').click(function(){
        $(this).find('.fa-spinner').show();
        $('#form').attr('target', '');
    });

    $('#search-report').click(function(){
        $('#loading-error').remove();
        $('.isEdit').val(1);
        reportNumber = 'RPT-'+$('#report_number').val();
        municipal = $('#search_municipality').val();
        reportType = $('#report_type').val();
        $.ajax({
            url: '{{ route("pdf.real_property_search", ["report_num" => "reportNumber", "municipality" => "mun_id"]) }}'.replace('reportNumber', reportNumber).replace('mun_id', municipal),
            method: 'GET',
            beforeSend: function(){
                $('.loading').removeClass('hidden');
            }
        }).done(function(data){
            console.log(data);
            preparePDF(data[0], reportType, municipal);
        }).fail(function(err){
            $('.loading').after(`
            <div class="alert alert-danger" id="loading-error">
                <strong>`+err.responseJSON+`</strong>
            </div>
            `);
            setTimeout(function(){
                $('#loading-error').remove();
            }, 3000);
        }).always(function(){
            $('.loading').addClass('hidden');
        });
    });

    $(document).on('click', '.rpt_report', function(e) {
        e.preventDefault();
        $('.isEdit').val(0);
        arrayData = {
            'municipality' : $('#pdf_rpt').find('[name="municipality"]').val(),
            'report_no' : 'RPT-'+$('#pdf_rpt').find('input[name="report_no"]').val(),
            'report_date' : $('#pdf_rpt').find('input[name="report_date"]').val(),
            'start_date' : $('#pdf_rpt').find('input[name="start_date"]').val(),
            'end_date' : $('#pdf_rpt').find('input[name="end_date"]').val(),
        }

        var reportType = $(this).attr('name');
        console.log(reportType);

        preparePDF(arrayData, reportType, arrayData.municipality);
        
    });
    function preparePDF(arrayData, button_pdf, municipality){
        
        var isEdit = $('.isEdit').val();
        var report_no = arrayData.report_no;
        var report_date = arrayData.report_date;
        var start_date = arrayData.start_date;
        var end_date = arrayData.end_date;
        console.log(isEdit);

        if(isEdit == 0){
            $('#addl_entries').html(`
                        <input type="hidden" name="municipality" id="munic">
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="hidden" name="report_no" id="report_num">
                        <input type="hidden" name="report_date" id="report_date">
            `);
        }else{
            $('#addl_entries').html('');
        }

        $.ajax({
            'url' : '{{ route("rpt.prepare") }}', 
            'data' : {
                'municipality' : municipality,
                'report_no' : report_no,
                'report_date' : report_date,
                'start_date' : start_date,
                'end_date' : end_date,
                'isEdit' : isEdit,
            },
            error : function(error) {
                alert(error.responseJSON);
            },
            success : function(data) {
                console.log(data);
                $('#report_content').removeClass('alert alert-danger');
                $('#report_content').empty();
                if(typeof(data) == 'object') {
                    $('#submit').css('display', 'block');
                    
                    $('#btn_pdf').val(button_pdf);
                    $('#report_content_modal').modal('show');
                    var content = '';

                    content += '<table class="center">\
                            <tr>\
                                <td>RECORD OF REAL PROPERTY TAX COLLECTIONS</td>\
                            </tr>\
                            <tr>\
                                <td>BASIC & SEF</td>\
                            </tr>';

                    if(data.municipality != undefined && data.municipality != null) {

                        municipalities = JSON.parse('{!! $base['municipalities'] !!}');
                        municipality_options = ``;
                        municipality_select = '';

                        if (isEdit == 1) {
                            municipalities.forEach(element => {
                            municipality_options += `
                                <option value="`+element.id+`">`+element.name+`</option>
                            `;
                        });

                        municipality_select = `<select class="form-control-xs" name="municipality" id="munic" required>
                                                    `+municipality_options+`
                                                </select>`;
                        }
                        
                        content += `<tr>
                                <td>MUNICIPALITY OF `+ (isEdit == 1 ? municipality_select : data.municipality.name) +`
                                </td>
                            </tr>`;
                    }
                            
                    content += '<tr>\
                                <td>'+( isEdit == 1 ? '<input type="text" class="form-control-xs date" name="start_date" id="start_date" required> to <input type="text" class="form-control-xs date" name="end_date" id="end_date" required>' : data.date_range )+'</td>\
                            </tr>\
                        </table>\
                        <table>\
                            <tr>\
                                <td width="50%">Name of Accountable Officer: ISABEL D. KIW-AN - Local Recenue Collection Officer IV</td>\
                                <td width="35%"></td>';

                    if(report_no != undefined || report_no != null || report_no != "") {
                        content += '<td>Report No.</td>\
                            <td>'+ (isEdit == 1 ? '<input class="form-control-xs" type="text" name="report_no" id="report_num">' : report_no  ) +'</td>';
                    }

                    content += '</tr>\
                            <tr>\
                                <td>A. COLLECTIONS</td>\
                                <td></td>\
                                <td>Date</td>\
                                <td>'+ (isEdit == 1 ? '<input type="text" class="form-control-xs date" name="report_date" id="report_date" required>' : report_date) +'</td>\
                            </tr>\
                        </table>';

                    // collections table
                    content += '<div class="table-div">\
                        <table class="table table-bordered table-responsive">\
                            <thead>\
                                <tr>\
                                    <th rowspan="4">Date</th>\
                                    <th rowspan="4">Name of Tax Payor</th>\
                                    <th rowspan="4">Period Covered</th>\
                                    <th rowspan="4">Official Receipt Number</th>\
                                    <th rowspan="4">TD/ARP No.</th>\
                                    <th rowspan="4">Name of Brgy.</th>\
                                    <th rowspan="4">Classifi <br> cation</th>\
                                    <th colspan="11">BASIC TAX</th>\
                                    <th rowspan="4">Sub-total Gross Collection</th>\
                                    <th rowspan="4">Sub-total Net Collection</th>\
                                    <th colspan="11">SPECIAL EDUCATION FUND</th>\
                                    <th rowspan="4">Sub-total Gross Collection</th>\
                                    <th rowspan="4">Sub-total Net Collection</th>\
                                    <th rowspan="4">Grand Total Gross Collection</th>\
                                    <th rowspan="4">Grand Total Net Collection</th>\
                                </tr>\
                                <tr>\
                                    <th colspan="2" rowspan="2">Advance</th>\
                                    <th colspan="2" rowspan="2">Current Year</th>\
                                    <th rowspan="3">'+data.preceeding+'</th>\
                                    <th colspan="2" rowspan="2">PRIOR YEARS</th>\
                                    <th colspan="4">PENALTIES</th>\
                                    <th colspan="2" rowspan="2">Advance</th>\
                                    <th colspan="2" rowspan="2">Current Year</th>\
                                    <th rowspan="3">'+data.preceeding+'</th>\
                                    <th colspan="2" rowspan="2">PRIOR YEARS</th>\
                                    <th colspan="4">PENALTIES</th>\
                                </tr>\
                                <tr>\
                                    <th rowspan="2">Current Year</th>\
                                    <th rowspan="2">'+data.preceeding+'</th>\
                                    <th colspan="2">PRIOR YEARS</th>\
                                    <th rowspan="2">Current Year</th>\
                                    <th rowspan="2">'+data.preceeding+'</th>\
                                    <th colspan="2">PRIOR YEARS</th>\
                                </tr>\
                                <tr>\
                                    <th>Gross Amount</th>\
                                    <th>\
                                        D<br>\
                                        I<br>\
                                        S<br>\
                                        C<br>\
                                        O<br>\
                                        U<br>\
                                        N<br>\
                                        T<br>\
                                    </th>\
                                    <th>Gross Amount</th>\
                                    <th>\
                                        D<br>\
                                        I<br>\
                                        S<br>\
                                        C<br>\
                                        O<br>\
                                        U<br>\
                                        N<br>\
                                        T<br>\
                                    </th>\
                                    <th>'+data.prior_start+'-1992</th>\
                                    <th>1991 & Below</th>\
                                    <th>'+data.prior_start+'-1992</th>\
                                    <th>1991 & Below</th>\
                                    <th>Gross Amount</th>\
                                    <th>\
                                        D<br>\
                                        I<br>\
                                        S<br>\
                                        C<br>\
                                        O<br>\
                                        U<br>\
                                        N<br>\
                                        T<br>\
                                    </th>\
                                    <th>Gross Amount</th>\
                                    <th>\
                                        D<br>\
                                        I<br>\
                                        S<br>\
                                        C<br>\
                                        O<br>\
                                        U<br>\
                                        N<br>\
                                        T<br>\
                                    </th>\
                                    <th>'+data.prior_start+'-1992</th>\
                                    <th>1991 & Below</th>\
                                    <th>'+data.prior_start+'-1992</th>\
                                    <th>1991 & Below</th>\
                                </tr>\
                            </thead>\
                            <tbody>';

                    var total_basic_current = 0;
                    var total_basic_discount = 0;
                    var total_basic_previous = 0;
                    var total_basic_penalty_current = 0;
                    var total_basic_penalty_previous = 0;
                    var total_basic_gross = 0;
                    var total_basic_net = 0;
                    var gt_gross = 0;
                    var gt_net = 0;
                    var counter = 0;

                    // immediate preceeding year
                    var total_preceed = 0;

                    // prior years
                    var total_prior_1992 = 0; // for 1992 and above
                    var total_prior_1991 = 0; // for 1991 and below
                    var total_penalty_prior_1992 = 0;
                    var total_penalty_prior_1991 = 0;

                    // advance
                    var total_adv = 0;
                    var total_adv_discount = 0;

                    $.each(data.receipts, function(key, receipt) {
                        var rcpt_done = 0;
                        var date_of_entry = moment(new Date(receipt.date_of_entry));
                        var entry_date = moment(new Date(receipt.date_of_entry)).format('YYYY-MM-DD');

                        if (receipt.is_cancelled == 1) {
                            content += '<tr>\
                                    <td>'+date_of_entry.format('MM-DD')+'</td>\
                                    <td colspan="2" style="color:red;">Cancelled</td>\
                                    <td colspan="1" style="color:red;">'+receipt.serial_no+'</td>\
                                    <td colspan="31" style="color:red;"></td>\
                                </tr>';
                        } else if(receipt.f56_detailmny.length > 0) {
                            $.each(receipt.f56_detailmny, function(key, f56_detail) {
                                counter++;
                                // current
                                if(f56_detail.period_covered == data.current) {
                                    total_basic_current += parseFloat(f56_detail.basic_current);
                                    total_basic_discount += parseFloat(f56_detail.basic_discount);
                                    total_basic_penalty_current += parseFloat(f56_detail.basic_penalty_current);
                                }

                                // immediate preceeding year
                                if(f56_detail.period_covered == data.preceeding) {
                                    total_basic_previous += parseFloat(f56_detail.basic_previous);
                                    total_basic_penalty_previous += parseFloat(f56_detail.basic_penalty_previous);
                                    total_preceed += parseFloat(f56_detail.basic_previous) + parseFloat(f56_detail.basic_penalty_previous);
                                }

                                if(f56_detail.period_covered >= data.advance_yr) {
                                    basic_gross = parseFloat(f56_detail.basic_penalty_previous) + parseFloat(f56_detail.basic_previous) + parseFloat(f56_detail.basic_current);
                                    total_adv += parseFloat(f56_detail.basic_current);
                                    total_adv_discount += parseFloat(f56_detail.basic_discount);
                                } else if(f56_detail.period_covered == data.current) {
                                    basic_gross = parseFloat(f56_detail.basic_current) + parseFloat(f56_detail.basic_penalty_current) + parseFloat(f56_detail.basic_penalty_previous) + parseFloat(f56_detail.basic_previous);
                                } else {
                                    basic_gross = parseFloat(f56_detail.basic_penalty_previous) + parseFloat(f56_detail.basic_previous);
                                }
                                if(f56_detail.period_covered >= data.advance_yr) {
                                    basic_net = parseFloat(basic_gross) - parseFloat(f56_detail.basic_discount);
                                } else if(f56_detail.period_covered == data.current) {
                                    basic_net = parseFloat(basic_gross) - parseFloat(f56_detail.basic_discount);
                                } else {
                                    basic_net = parseFloat(basic_gross);
                                }
                                total_basic_gross += parseFloat(basic_gross);
                                total_basic_net += parseFloat(basic_net);
                                gt_gross += (parseFloat(basic_gross*2));
                                gt_net += (parseFloat(basic_net*2));

                                // prior years
                                if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered >= 1992) {
                                    total_prior_1992 += parseFloat(f56_detail.basic_previous);
                                    total_penalty_prior_1992 += parseFloat(f56_detail.basic_penalty_previous);
                                }
                                if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered <= 1991) {
                                    total_prior_1991 += parseFloat(f56_detail.basic_previous);
                                    total_penalty_prior_1991 += parseFloat(f56_detail.basic_penalty_previous);
                                }

                                content += '<tr>';
                                if(rcpt_done == 0) {
                                    rcpt_done = 1;
                                    content += '<td>';
                                        if(date_of_entry.format('MMM') == 'Sep') {
                                            content += 'Sept ' + date_of_entry.format('DD');
                                        } else {
                                            content += date_of_entry.format('MMM DD');
                                        }
                                    content += '</td>\
                                        <td>'+f56_detail.owner_name+'</td>\
                                        <td>'+f56_detail.period_covered+'</td>\
                                        <td>'+receipt.serial_no+'</td>';
                                } else {
                                    content += '<td></td>\
                                        <td></td>\
                                        <td>'+f56_detail.period_covered+'</td>\
                                        <td></td>';
                                }

                                if(f56_detail.t_d_a_r_p[0] == undefined && f56_detail.t_d_a_r_p[0] == null) {
                                    content += '<td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>\
                                        <td></td>';
                                } else {
                                    content += '<td>'+f56_detail.t_d_a_r_p[0].tdarpno+'</td>';
                                        if(f56_detail.t_d_a_r_p_x.barangay_name != undefined && f56_detail.t_d_a_r_p_x.barangay_name != null) {
                                            content += '<td>'+f56_detail.t_d_a_r_p_x.barangay_name.name+'</td>';
                                        } else {
                                            content += '<td></td>'
                                        }
                                        content += '<td>'+f56_detail.f56_type.abbrev+'</td>;';
                                        // BASIC
                                        // adv gross
                                        if(f56_detail.period_covered >= data.advance_yr) {
                                            content += '<td>'+parseFloat(f56_detail.basic_current).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // adv discount
                                        if(f56_detail.period_covered >= data.advance_yr) {
                                            content += '<td>'+parseFloat(f56_detail.basic_discount).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // current gross
                                        if(f56_detail.period_covered == data.current) {
                                            content += '<td>'+parseFloat(f56_detail.basic_current).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // current discount
                                        if(f56_detail.period_covered == data.current) {
                                            content += '<td>'+parseFloat(f56_detail.basic_discount).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // preceeding year gross
                                        if(f56_detail.period_covered == data.preceeding) {
                                            content += '<td>'+parseFloat(f56_detail.basic_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1992 & above gross
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered >= 1992) {
                                            content += '<td>'+parseFloat(f56_detail.basic_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1991 & below gross
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered <= 1991) {
                                            content += '<td>'+parseFloat(f56_detail.basic_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // penalty current
                                        content += '<td>'+parseFloat(f56_detail.basic_penalty_current).toFixed(2)+'</td>';

                                        // preceeding penalty 
                                        if(f56_detail.period_covered == data.preceeding) {
                                            content += '<td>'+parseFloat(f56_detail.basic_penalty_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1992 & above penalty
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered >= 1992) {
                                            content += '<td>'+parseFloat(f56_detail.basic_penalty_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1991 & below penalty
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered <= 1991) {
                                            content += '<td>'+parseFloat(f56_detail.basic_penalty_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // subtotal gross and net
                                        content += '<td>'+parseFloat(basic_gross).toFixed(2)+'</td>\
                                            <td>'+parseFloat(basic_net).toFixed(2)+'</td>';

                                        // SEF
                                        // adv gross
                                        if(f56_detail.period_covered >= data.advance_yr) {
                                            content += '<td>'+parseFloat(f56_detail.basic_current).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // adv discount
                                        if(f56_detail.period_covered >= data.advance_yr) {
                                            content += '<td>'+parseFloat(f56_detail.basic_discount).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // current gross
                                        if(f56_detail.period_covered == data.current) {
                                            content += '<td>'+parseFloat(f56_detail.basic_current).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // current discount
                                        if(f56_detail.period_covered == data.current) {
                                            content += '<td>'+parseFloat(f56_detail.basic_discount).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // preceeding gross
                                        if(f56_detail.period_covered == data.preceeding) {
                                            content += '<td>'+parseFloat(f56_detail.basic_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1992 & above gross
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered >= 1992) {
                                            content += '<td>'+parseFloat(f56_detail.basic_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1991 & below gross
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered <= 1991) {
                                            content += '<td>'+parseFloat(f56_detail.basic_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // current penalty
                                        content += '<td>'+parseFloat(f56_detail.basic_penalty_current).toFixed(2)+'</td>';

                                        // preceeding penalty
                                        if(f56_detail.period_covered == data.preceeding) {
                                            content += '<td>'+parseFloat(f56_detail.basic_penalty_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1992 & above penalty
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered >= 1992) {
                                            content += '<td>'+parseFloat(f56_detail.basic_penalty_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // 1991 & below penalty
                                        if(f56_detail.period_covered <= data.prior_start && f56_detail.period_covered <= 1991) {
                                            content += '<td>'+parseFloat(f56_detail.basic_penalty_previous).toFixed(2)+'</td>';
                                        } else {
                                            content += '<td>0.00</td>';
                                        }

                                        // subtotal gross & net, grandtotal gross & net
                                        content += '<td>'+parseFloat(basic_gross).toFixed(2)+'</td>\
                                            <td>'+parseFloat(basic_net).toFixed(2)+'</td>\
                                            <td>'+parseFloat(basic_gross*2).toFixed(2)+'</td>\
                                            <td>'+parseFloat(basic_net*2).toFixed(2)+'</td>';
                                }
                                content += '</tr>';
                            });
                        }
                    });
                    content += '<tr>\
                            <th colspan="7">TOTAL COLLECTION</th>\
                            <th>'+parseFloat(total_adv).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_adv_discount).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_current).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_discount).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_previous).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_prior_1992).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_prior_1991).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_penalty_current).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_penalty_previous).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_penalty_prior_1992).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_penalty_prior_1991).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_gross).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_net).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_adv).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_adv_discount).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_current).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_discount).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_previous).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_prior_1992).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_prior_1991).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_penalty_current).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_penalty_previous).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_penalty_prior_1992).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_penalty_prior_1991).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_gross).toFixed(2)+'</th>\
                            <th>'+parseFloat(total_basic_net).toFixed(2)+'</th>\
                            <th>'+parseFloat(gt_gross).toFixed(2)+'</th>\
                            <th>'+parseFloat(gt_net).toFixed(2)+'</th>\
                        </tr>';
                    content += '</tbody>\
                        </table>\
                        </div>\
                        <div class="table-div">\
                        <table class="table table-bordered table-responsive">\
                            <tr>\
                                <td colspan="30"><b>Summary</b></td>\
                            </tr>';

                        $.each(data.f56_type,function(key, type) {
                            var class_basic_gross = (data.class_amt[type.id]['basic_current'] 
                                + data.class_amt[type.id]['basic_previous'] 
                                + data.class_amt[type.id]['basic_adv'] 
                                + data.class_amt[type.id]['basic_prior_1992'] 
                                + data.class_amt[type.id]['basic_prior_1991'] 
                                + data.class_amt[type.id]['basic_penalty_current'] 
                                + data.class_amt[type.id]['basic_penalty_previous']
                                + data.class_amt[type.id]['basic_prior_penalty_1992']
                                + data.class_amt[type.id]['basic_prior_penalty_1991']
                            );
                            var class_basic_net = class_basic_gross - (data.class_amt[type.id]['basic_discount'] + data.class_amt[type.id]['basic_adv_discount']);
                            var class_total_gross = class_basic_gross + class_basic_gross;
                            var class_total_net = class_basic_net + class_basic_net;
                            content += '<tr>\
                                    <td colspan="2">'+type.name+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_adv']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_adv_discount']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_current']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_discount']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_previous']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_1992']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_1991']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_penalty_current']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_penalty_previous']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_penalty_1992']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_penalty_1991']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(class_basic_gross).toFixed(2)+'</td>\
                                    <td>'+parseFloat(class_basic_net).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_adv']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_adv_discount']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_current']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_discount']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_previous']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_1992']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_1991']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_penalty_current']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_penalty_previous']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_penalty_1992']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(data.class_amt[type.id]['basic_prior_penalty_1991']).toFixed(2)+'</td>\
                                    <td>'+parseFloat(class_basic_gross).toFixed(2)+'</td>\
                                    <td>'+parseFloat(class_basic_net).toFixed(2)+'</td>\
                                    <td>'+parseFloat(class_total_gross).toFixed(2)+'</td>\
                                    <td>'+parseFloat(class_total_net).toFixed(2)+'</td>\
                                </tr>';
                        });

                        content += '<tr>\
                            <th class="border_all" colspan="2">TOTAL</th>\
                            <th class="border_all val">'+parseFloat(total_adv).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_adv_discount).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_current).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_discount).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_previous).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_prior_1992).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_prior_1991).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_penalty_current).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_penalty_previous).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_penalty_prior_1992).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_penalty_prior_1991).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_gross).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_net).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_adv).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_adv_discount).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_current).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_discount).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_previous).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_prior_1992).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_prior_1991).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_penalty_current).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_penalty_previous).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_penalty_prior_1992).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_penalty_prior_1991).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_gross).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(total_basic_net).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(gt_gross).toFixed(2)+'</th>\
                            <th class="border_all val">'+parseFloat(gt_net).toFixed(2)+'</th>\
                        </tr>';

                    // disposition
                    content += '</table>\
                        </div>\
                        <div class="table-div" id="dispo_div">\
                        <table class="table table-bordered table-responsive">';
                    // basic
                    content += '<tr>\
                            <th colspan="3"><b>Disposition</b></th>\
                            <th colspan="5" class="border_all ctr">ADVANCE</th>\
                            <th colspan="5" class="border_all ctr">CURRENT</th>\
                            <th colspan="3" class="border_all ctr">'+data.preceeding+'</th>\
                            <th colspan="3" class="border_all ctr">'+data.prior_start+'-1992</th>\
                            <th colspan="3" class="border_all ctr">1991 & below</th>\
                            <th colspan="10" class="border_all ctr">PENALTIES</th>\
                            <th colspan="2" rowspan="2" class="border_all ctr">TOTAL</th>\
                        </tr>\
                        <tr>\
                            <td colspan="3">BASIC TAX 1%</td>\
                            <th class="border_all">%</th>\
                            <th colspan="2" class="border_all ctr">AMOUNT</th>\
                            <th colspan="2" class="border_all ctr">DISCOUNT</th>\
                            <th class="border_all">%</th>\
                            <th colspan="2" class="border_all ctr">AMOUNT</th>\
                            <th colspan="2" class="border_all ctr">DISCOUNT</th>\
                            <th class="border_all">%</th>\
                            <th colspan="2" class="border_all ctr">AMOUNT</th>\
                            <th class="border_all">%</th>\
                            <th colspan="2" class="border_all ctr">AMOUNT</th>\
                            <th class="border_all">%</th>\
                            <th colspan="2" class="border_all ctr">AMOUNT</th>\
                            <th class="border_all">%</th>\
                            <th colspan="3" class="border_all ctr">CURRENT</th>\
                            <th colspan="2" class="border_all ctr">'+data.preceeding+'</th>\
                            <th colspan="2" class="border_all ctr">'+data.prior_start+'-1992</th>\
                            <th colspan="2" class="border_all ctr">1991 & below</th>\
                        </tr>';
                    if(data.sef_exist != null && data.sef_exist != undefined && data.sef_exist.length > 0) {
                        console.log('exist');
                        // compute totals here
                        if(data.sef_exist[0].report_basic_items[0] != null && data.sef_exist[0].report_basic_items[0] != undefined) {
                            var provincial_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_adv_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_curr_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_1991).toFixed(2));
                            var municipal_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_adv_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_curr_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_1991).toFixed(2));
                            var brgy_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_adv_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_curr_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_1991).toFixed(2));
                            var grandtotal = parseFloat(parseFloat(provincial_total).toFixed(2)) + parseFloat(parseFloat(municipal_total).toFixed(2)) + parseFloat(parseFloat(brgy_total).toFixed(2));

                            var adv_amt_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_adv_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_adv_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_adv_amt).toFixed(2));
                            var adv_discount_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_adv_discount).toFixed(2));
                            var curr_amt_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_curr_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_curr_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_curr_amt).toFixed(2));
                            var curr_discount_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_curr_discount).toFixed(2));
                            var prev_amt_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_prev_amt).toFixed(2));
                            var amt_1992_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_1992_amt).toFixed(2));
                            var amt_1991_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_1991_amt).toFixed(2));
                            var penalty_curr_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_curr).toFixed(2));
                            var penalty_prev_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_prev).toFixed(2));
                            var penalty_1992_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_1992).toFixed(2));
                            var penalty_1991_total = parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_1991).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_1991).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_1991).toFixed(2));
                            var basic_total_net = parseFloat(parseFloat(adv_amt_total).toFixed(2)) - parseFloat(parseFloat(adv_discount_total).toFixed(2)) + parseFloat(parseFloat(curr_amt_total).toFixed(2)) - parseFloat(parseFloat(curr_discount_total).toFixed(2)) + parseFloat(parseFloat(prev_amt_total).toFixed(2)) + parseFloat(parseFloat(amt_1992_total).toFixed(2)) + parseFloat(parseFloat(amt_1991_total).toFixed(2)) + parseFloat(parseFloat(penalty_curr_total).toFixed(2)) + parseFloat(parseFloat(penalty_prev_total).toFixed(2)) + parseFloat(parseFloat(penalty_1992_total).toFixed(2)) + parseFloat(parseFloat(penalty_1991_total).toFixed(2));

                            content += '<td colspan="3">Provincial Share</td>\
                                    <td class="border_all ctr">35%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_adv_ammount" name="prv_adv_ammount"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_adv_discount" name="prv_adv_discount"></td>\
                                    <td class="border_all ctr">35%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_curr_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_crnt_ammount" name="prv_crnt_ammount"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_curr_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_crnt_discount" name="prv_crnt_discount"></td>\
                                    <td class="border_all ctr">35%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_prev_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prvious_ammount" name="prv_prvious_ammount"></td>\
                                    <td class="border_all ctr">35%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1992_amt" name="prv_prior_1992_amt"></td>\
                                    <td class="border_all ctr">35%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1991_amt" name="prv_prior_1991_amt"></td>\
                                    <td class="border_all ctr">35%</td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_curr).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_pnalties_crnt" name="prv_pnalties_crnt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_pnalties_prvious" name="prv_pnalties_prvious"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_1992).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1992_penalties" name="prv_prior_1992_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].prv_penalty_1991).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1991_penalties" name="prv_prior_1991_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(provincial_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_total_basic" name="prv_total_basic" readonly></td>\
                                </tr>\
                                <tr>\
                                    <td colspan="3">Municipal Share</td>\
                                    <td class="border_all ctr">40%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_adv_ammount" name="mnc_adv_ammount"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_adv_discount" name="mnc_adv_discount"></td>\
                                    <td class="border_all ctr">40%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_curr_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_current" name="munshare_basic_current"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_curr_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_discount" name="munshare_basic_discount"></td>\
                                    <td class="border_all ctr">40%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_prev_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_previous" name="munshare_basic_previous"></td>\
                                    <td class="border_all ctr">40%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1992_amt" name="mnc_prior_1992_amt"></td>\
                                    <td class="border_all ctr">40%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1991_amt" name="mnc_prior_1991_amt"></td>\
                                    <td class="border_all ctr">40%</td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_curr).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_penalty_current" name="munshare_basic_penalty_current"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_penalty_previous" name="munshare_basic_penalty_previous"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_1992).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1992_penalties" name="mnc_prior_1992_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].mnc_penalty_1991).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1991_penalties" name="mnc_prior_1991_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(municipal_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mncpal_total_basic" name="mncpal_total_basic" readonly></td>\
                                </tr>\
                                <tr>\
                                    <td colspan="3">Barangay Share</td>\
                                    <td class="border_all ctr">25%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_adv_ammount" name="brgy_adv_ammount"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_adv_discount" name="brgy_adv_discount"></td>\
                                    <td class="border_all ctr">25%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_curr_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_current" name="brgyshare_basic_current"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_curr_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_discount" name="brgyshare_basic_discount"></td>\
                                    <td class="border_all ctr">25%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_prev_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_previous" name="brgyshare_basic_previous"></td>\
                                    <td class="border_all ctr">25%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1992_amt" name="brgy_prior_1992_amt"></td>\
                                    <td class="border_all ctr">25%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1991_amt" name="brgy_prior_1991_amt"></td>\
                                    <td class="border_all ctr">25%</td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_curr).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_penalty_current" name="brgyshare_basic_penalty_current"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_penalty_previous" name="brgyshare_basic_penalty_previous"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_1992).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1992_penalties" name="brgy_prior_1992_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_basic_items[0].brgy_penalty_1991).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1991_penalties" name="brgy_prior_1991_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_total_basic" name="brgy_total_basic" readonly></td>\
                                </tr>\
                                <tr>\
                                    <th colspan="3">TOTAL(S)</th>\
                                    <td class="border_all"></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(adv_amt_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_adv_amt" name="total_adv_amt" readonly></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(adv_discount_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_adv_discount" name="total_adv_discount" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(curr_amt_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_current" name="total_basic_current" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(curr_discount_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_discount" name="total_basic_discount" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(prev_amt_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_previous" name="total_basic_previous" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(amt_1992_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1992_amt" name="total_prior_1992_amt" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(amt_1991_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1991_amt" name="total_prior_1991_amt" class="total_prior_1991_amt" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(penalty_curr_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_penalty_current" name="total_basic_penalty_current" class="total_basic_penalty_current" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(penalty_prev_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_penalty_previous" name="total_basic_penalty_previous" class="total_basic_penalty_previous" readonly></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(penalty_1992_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1992_penalties" name="total_prior_1992_penalties" class="total_prior_1992_penalties" readonly></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(penalty_1991_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1991_penalties" name="total_prior_1991_penalties" class="total_prior_1991_penalties" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(basic_total_net).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_net" name="total_basic_net" class="total_basic_net" readonly></td>\
                                </tr>';
                            } else {
                                console.log('basic not exist');
                                var munshare_basic_current             = parseFloat(total_basic_current) * .4;
                                var munshare_basic_discount            = parseFloat(total_basic_discount) * .4;
                                var munshare_basic_previous            = parseFloat(total_basic_previous) * .4;
                                var munshare_basic_penalty_current     = parseFloat(total_basic_penalty_current) * .4;
                                var munshare_basic_penalty_previous    = parseFloat(total_basic_penalty_previous) * .4;
                                var munshare_basic_net                 = parseFloat(total_basic_net) * .4;

                                var prv_crnt_ammount = parseFloat(total_basic_current) * .35;
                                var prv_crnt_discount = parseFloat(total_basic_discount) * .35;
                                var prv_prvious_ammount = parseFloat(total_basic_previous) * .35;
                                var prv_pnalties_crnt = parseFloat(total_basic_penalty_current) * .35;
                                var prv_pnalties_prvious = parseFloat(total_basic_penalty_previous) * .35;

                                // advance
                                var prv_adv_ammount = parseFloat(total_adv) * .35;
                                var prv_adv_discount = parseFloat(total_adv_discount) * .35;
                                var mnc_adv_ammount = parseFloat(total_adv) * .40;
                                var mnc_adv_discount = parseFloat(total_adv_discount) * .40;
                                var brgy_adv_ammount = parseFloat(parseFloat(total_adv).toFixed(2)) - parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) - parseFloat(parseFloat(mnc_adv_ammount).toFixed(2));
                                var brgy_adv_discount = parseFloat(parseFloat(total_adv_discount).toFixed(2)) - parseFloat(parseFloat(prv_adv_discount).toFixed(2)) - parseFloat(parseFloat(mnc_adv_discount).toFixed(2));
                                var total_adv_amt = parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) + parseFloat(parseFloat(mnc_adv_ammount).toFixed(2)) + parseFloat(parseFloat(brgy_adv_ammount).toFixed(2));
                                var total_adv_discount = parseFloat(parseFloat(prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(brgy_adv_discount).toFixed(2));
                                
                                // 1992-above
                                var prv_prior_1992_amt = parseFloat(total_prior_1992) * .35;
                                var prv_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .35;
                                var mnc_prior_1992_amt = parseFloat(total_prior_1992) * .40;
                                var mnc_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .40;
                                var brgy_prior_1992_amt = parseFloat(parseFloat(total_prior_1992).toFixed(2)) - parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2));
                                var brgy_prior_1992_penalties = parseFloat(parseFloat(total_penalty_prior_1992).toFixed(2)) - parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2));
                                var total_prior_1992_amt = parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_amt).toFixed(2));
                                var total_prior_1992_penalties = parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_penalties).toFixed(2));

                                // 1991-below
                                var prv_prior_1991_amt = parseFloat(total_prior_1991) * .35;
                                var prv_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .35;
                                var mnc_prior_1991_amt = parseFloat(total_prior_1991) * .40;
                                var mnc_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .40;
                                var brgy_prior_1991_amt = parseFloat(parseFloat(total_prior_1991).toFixed(2)) - parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2));
                                var brgy_prior_1991_penalties = parseFloat(parseFloat(total_penalty_prior_1991).toFixed(2)) - parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2));
                                var total_prior_1991_amt = parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_amt).toFixed(2));
                                var total_prior_1991_penalties = parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_penalties).toFixed(2));         

                                var brgyshare_basic_current            = parseFloat(parseFloat(total_basic_current).toFixed(2)) - parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) - parseFloat(parseFloat(munshare_basic_current).toFixed(2));
                                var brgyshare_basic_discount           = parseFloat(parseFloat(total_basic_discount).toFixed(2)) - parseFloat(parseFloat(prv_crnt_discount).toFixed(2)) - parseFloat(parseFloat(munshare_basic_discount).toFixed(2));
                                var brgyshare_basic_previous           = parseFloat(parseFloat(total_basic_previous).toFixed(2)) - parseFloat(parseFloat(prv_prvious_ammount).toFixed(2)) - parseFloat(parseFloat(munshare_basic_previous).toFixed(2));
                                var brgyshare_basic_penalty_current    = parseFloat(parseFloat(total_basic_penalty_current).toFixed(2)) - parseFloat(parseFloat(prv_pnalties_crnt).toFixed(2)) - parseFloat(parseFloat(munshare_basic_penalty_current).toFixed(2));
                                var brgyshare_basic_penalty_previous   = parseFloat(parseFloat(total_basic_penalty_previous).toFixed(2)) - parseFloat(parseFloat(prv_pnalties_prvious).toFixed(2)) - parseFloat(parseFloat(munshare_basic_penalty_previous).toFixed(2));

                                var prv_total_basic = parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) - parseFloat(parseFloat(prv_crnt_discount).toFixed(2)) + parseFloat(parseFloat(prv_prvious_ammount).toFixed(2)) + parseFloat(parseFloat(prv_pnalties_crnt).toFixed(2)) + parseFloat(parseFloat(prv_pnalties_prvious).toFixed(2)) + parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) + parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) - parseFloat(parseFloat(prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2));
                                var mncpal_total_basic = (parseFloat(parseFloat(munshare_basic_current).toFixed(2)) - parseFloat(parseFloat(munshare_basic_discount).toFixed(2))  + parseFloat(parseFloat(munshare_basic_previous).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_current).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_previous).toFixed(2))) + parseFloat(parseFloat(mnc_adv_ammount).toFixed(2)) - parseFloat(parseFloat(mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2));
                                var brgy_total_basic = parseFloat(parseFloat(brgyshare_basic_current).toFixed(2)) - parseFloat(parseFloat(brgyshare_basic_discount).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_previous).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_current).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_previous).toFixed(2)) + parseFloat(parseFloat(brgy_adv_ammount).toFixed(2)) - parseFloat(parseFloat(brgy_adv_discount).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_penalties).toFixed(2));

                                var total_basic_net = parseFloat(parseFloat(prv_total_basic).toFixed(2)) + parseFloat(parseFloat(mncpal_total_basic).toFixed(2)) + parseFloat(parseFloat(brgy_total_basic).toFixed(2));
                                var total_basic_current = parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) + parseFloat(parseFloat(munshare_basic_current).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_current).toFixed(2));

                                content += '<td colspan="3">Provincial Share</td>\
                                        <td class="border_all ctr">35%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_adv_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_adv_ammount" name="prv_adv_ammount"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_adv_discount" name="prv_adv_discount"></td>\
                                        <td class="border_all ctr">35%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_crnt_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_crnt_ammount" name="prv_crnt_ammount"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_crnt_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_crnt_discount" name="prv_crnt_discount"></td>\
                                        <td class="border_all ctr">35%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_prvious_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prvious_ammount" name="prv_prvious_ammount"></td>\
                                        <td class="border_all ctr">35%</td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(prv_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1992_amt" name="prv_prior_1992_amt"></td>\
                                        <td class="border_all ctr">35%</td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(prv_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1991_amt" name="prv_prior_1991_amt"></td>\
                                        <td class="border_all ctr">35%</td>\
                                        <td colspan="3" class="border_all val"><input value="'+parseFloat(prv_pnalties_crnt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_pnalties_crnt" name="prv_pnalties_crnt"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_pnalties_prvious).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_pnalties_prvious" name="prv_pnalties_prvious"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1992_penalties" name="prv_prior_1992_penalties"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1991_penalties" name="prv_prior_1991_penalties"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_total_basic).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_total_basic" name="prv_total_basic" readonly></td>\
                                    </tr>\
                                    <tr>\
                                        <td colspan="3">Municipal Share</td>\
                                        <td class="border_all ctr">40%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_adv_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_adv_ammount" name="mnc_adv_ammount"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_adv_discount" name="mnc_adv_discount"></td>\
                                        <td class="border_all ctr">40%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_current" name="munshare_basic_current"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_discount" name="munshare_basic_discount"></td>\
                                        <td class="border_all ctr">40%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_previous" name="munshare_basic_previous"></td>\
                                        <td class="border_all ctr">40%</td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(mnc_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1992_amt" name="mnc_prior_1992_amt"></td>\
                                        <td class="border_all ctr">40%</td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(mnc_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1991_amt" name="mnc_prior_1991_amt"></td>\
                                        <td class="border_all ctr">40%</td>\
                                        <td colspan="3" class="border_all val"><input value="'+parseFloat(munshare_basic_penalty_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_penalty_current" name="munshare_basic_penalty_current"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_penalty_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_penalty_previous" name="munshare_basic_penalty_previous"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1992_penalties" name="mnc_prior_1992_penalties"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1991_penalties" name="mnc_prior_1991_penalties"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(mncpal_total_basic).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mncpal_total_basic" name="mncpal_total_basic" readonly></td>\
                                    </tr>\
                                    <tr>\
                                        <td colspan="3">Barangay Share</td>\
                                        <td class="border_all ctr">25%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_adv_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_adv_ammount" name="brgy_adv_ammount"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_adv_discount" name="brgy_adv_discount"></td>\
                                        <td class="border_all ctr">25%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_current" name="brgyshare_basic_current"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_discount" name="brgyshare_basic_discount"></td>\
                                        <td class="border_all ctr">25%</td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_previous" name="brgyshare_basic_previous"></td>\
                                        <td class="border_all ctr">25%</td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(brgy_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1992_amt" name="brgy_prior_1992_amt"></td>\
                                        <td class="border_all ctr">25%</td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(brgy_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1991_amt" name="brgy_prior_1991_amt"></td>\
                                        <td class="border_all ctr">25%</td>\
                                        <td colspan="3" class="border_all val"><input value="'+parseFloat(brgyshare_basic_penalty_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_penalty_current" name="brgyshare_basic_penalty_current"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_penalty_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_penalty_previous" name="brgyshare_basic_penalty_previous"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1992_penalties" name="brgy_prior_1992_penalties"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1991_penalties" name="brgy_prior_1991_penalties"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_total_basic).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_total_basic" name="brgy_total_basic" readonly></td>\
                                    </tr>\
                                    <tr>\
                                        <th colspan="3">TOTAL(S)</th>\
                                        <td class="border_all"></td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(total_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_adv_amt" name="total_adv_amt" readonly></td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(total_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_adv_discount" name="total_adv_discount" readonly></td>\
                                        <td class="border_all"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_current" name="total_basic_current" readonly></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_discount" name="total_basic_discount" readonly></td>\
                                        <td class="border_all"></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_previous" name="total_basic_previous" readonly></td>\
                                        <td class="border_all"></td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1992_amt" name="total_prior_1992_amt" readonly></td>\
                                        <td class="border_all"></td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1991_amt" name="total_prior_1991_amt" class="total_prior_1991_amt" readonly></td>\
                                        <td class="border_all"></td>\
                                        <td colspan="3" class="border_all val"><input value="'+parseFloat(total_basic_penalty_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_penalty_current" name="total_basic_penalty_current" class="total_basic_penalty_current" readonly></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_penalty_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_penalty_previous" name="total_basic_penalty_previous" class="total_basic_penalty_previous" readonly></td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1992_penalties" name="total_prior_1992_penalties"class="total_prior_1992_penalties" readonly></td>\
                                        <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1991_penalties" name="total_prior_1991_penalties" class="total_prior_1991_penalties" readonly></td>\
                                        <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_net).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_net" name="total_basic_net" class="total_basic_net" readonly></td>\
                                    </tr>';
                            }
                    } else {
                        console.log('adjusted doesnt exist');
                        var munshare_basic_current             = parseFloat(total_basic_current) * .4;
                        var munshare_basic_discount            = parseFloat(total_basic_discount) * .4;
                        var munshare_basic_previous            = parseFloat(total_basic_previous) * .4;
                        var munshare_basic_penalty_current     = parseFloat(total_basic_penalty_current) * .4;
                        var munshare_basic_penalty_previous    = parseFloat(total_basic_penalty_previous) * .4;
                        var munshare_basic_net                 = parseFloat(total_basic_net) * .4;

                        var prv_crnt_ammount = parseFloat(total_basic_current) * .35;
                        var prv_crnt_discount = parseFloat(total_basic_discount) * .35;
                        var prv_prvious_ammount = parseFloat(total_basic_previous) * .35;
                        var prv_pnalties_crnt = parseFloat(total_basic_penalty_current) * .35;
                        var prv_pnalties_prvious = parseFloat(total_basic_penalty_previous) * .35;

                        // advance
                        var prv_adv_ammount = parseFloat(total_adv) * .35;
                        var prv_adv_discount = parseFloat(total_adv_discount) * .35;
                        var mnc_adv_ammount = parseFloat(total_adv) * .40;
                        var mnc_adv_discount = parseFloat(total_adv_discount) * .40;
                        var brgy_adv_ammount = parseFloat(parseFloat(total_adv).toFixed(2)) - parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) - parseFloat(parseFloat(mnc_adv_ammount).toFixed(2));
                        var brgy_adv_discount = parseFloat(parseFloat(total_adv_discount).toFixed(2)) - parseFloat(parseFloat(prv_adv_discount).toFixed(2)) - parseFloat(parseFloat(mnc_adv_discount).toFixed(2));
                        var total_adv_amt = parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) + parseFloat(parseFloat(mnc_adv_ammount).toFixed(2)) + parseFloat(parseFloat(brgy_adv_ammount).toFixed(2));
                        var total_adv_discount = parseFloat(parseFloat(prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(brgy_adv_discount).toFixed(2));

                        // 1992-above
                        var prv_prior_1992_amt = parseFloat(total_prior_1992) * .35;
                        var prv_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .35;
                        var mnc_prior_1992_amt = parseFloat(total_prior_1992) * .40;
                        var mnc_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .40;
                        var brgy_prior_1992_amt = parseFloat(parseFloat(total_prior_1992).toFixed(2)) - parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2));
                        var brgy_prior_1992_penalties = parseFloat(parseFloat(total_penalty_prior_1992).toFixed(2)) - parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2));
                        var total_prior_1992_amt = parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_amt).toFixed(2));
                        var total_prior_1992_penalties = parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_penalties).toFixed(2));

                        // 1991-below
                        var prv_prior_1991_amt = parseFloat(total_prior_1991) * .35;
                        var prv_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .35;
                        var mnc_prior_1991_amt = parseFloat(total_prior_1991) * .40;
                        var mnc_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .40;
                        var brgy_prior_1991_amt = parseFloat(parseFloat(total_prior_1991).toFixed(2)) - parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2));
                        var brgy_prior_1991_penalties = parseFloat(parseFloat(total_penalty_prior_1991).toFixed(2)) - parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2)) - parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2));
                        var total_prior_1991_amt = parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_amt).toFixed(2));
                        var total_prior_1991_penalties = parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_penalties).toFixed(2));         

                        var brgyshare_basic_current            = parseFloat(parseFloat(total_basic_current).toFixed(2)) - parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) - parseFloat(parseFloat(munshare_basic_current).toFixed(2));
                        var brgyshare_basic_discount           = parseFloat(parseFloat(total_basic_discount).toFixed(2)) - parseFloat(parseFloat(prv_crnt_discount).toFixed(2)) - parseFloat(parseFloat(munshare_basic_discount).toFixed(2));
                        var brgyshare_basic_previous           = parseFloat(parseFloat(total_basic_previous).toFixed(2)) - parseFloat(parseFloat(prv_prvious_ammount).toFixed(2)) - parseFloat(parseFloat(munshare_basic_previous).toFixed(2));
                        var brgyshare_basic_penalty_current    = parseFloat(parseFloat(total_basic_penalty_current).toFixed(2)) - parseFloat(parseFloat(prv_pnalties_crnt).toFixed(2)) - parseFloat(parseFloat(munshare_basic_penalty_current).toFixed(2));
                        var brgyshare_basic_penalty_previous   = parseFloat(parseFloat(total_basic_penalty_previous).toFixed(2)) - parseFloat(parseFloat(prv_pnalties_prvious).toFixed(2)) - parseFloat(parseFloat(munshare_basic_penalty_previous).toFixed(2));

                        var prv_total_basic = parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) - parseFloat(parseFloat(prv_crnt_discount).toFixed(2)) + parseFloat(parseFloat(prv_prvious_ammount).toFixed(2)) + parseFloat(parseFloat(prv_pnalties_crnt).toFixed(2)) + parseFloat(parseFloat(prv_pnalties_prvious).toFixed(2)) + parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) + parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) - parseFloat(parseFloat(prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2));
                        var mncpal_total_basic = (parseFloat(parseFloat(munshare_basic_current).toFixed(2)) - parseFloat(parseFloat(munshare_basic_discount).toFixed(2))  + parseFloat(parseFloat(munshare_basic_previous).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_current).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_previous).toFixed(2))) + parseFloat(parseFloat(mnc_adv_ammount).toFixed(2)) - parseFloat(parseFloat(mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2));
                        var brgy_total_basic = parseFloat(parseFloat(brgyshare_basic_current).toFixed(2)) - parseFloat(parseFloat(brgyshare_basic_discount).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_previous).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_current).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_previous).toFixed(2)) + parseFloat(parseFloat(brgy_adv_ammount).toFixed(2)) - parseFloat(parseFloat(brgy_adv_discount).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_penalties).toFixed(2));

                        var total_basic_net = parseFloat(parseFloat(prv_total_basic).toFixed(2)) + parseFloat(parseFloat(mncpal_total_basic).toFixed(2)) + parseFloat(parseFloat(brgy_total_basic).toFixed(2));
                        var total_basic_current = parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) + parseFloat(parseFloat(munshare_basic_current).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_current).toFixed(2));

                        content += '<td colspan="3">Provincial Share</td>\
                                <td class="border_all ctr">35%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_adv_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_adv_ammount" name="prv_adv_ammount"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_adv_discount" name="prv_adv_discount"></td>\
                                <td class="border_all ctr">35%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_crnt_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_crnt_ammount" name="prv_crnt_ammount"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_crnt_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_crnt_discount" name="prv_crnt_discount"></td>\
                                <td class="border_all ctr">35%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_prvious_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prvious_ammount" name="prv_prvious_ammount"></td>\
                                <td class="border_all ctr">35%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(prv_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1992_amt" name="prv_prior_1992_amt"></td>\
                                <td class="border_all ctr">35%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(prv_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1991_amt" name="prv_prior_1991_amt"></td>\
                                <td class="border_all ctr">35%</td>\
                                <td colspan="3" class="border_all val"><input value="'+parseFloat(prv_pnalties_crnt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_pnalties_crnt" name="prv_pnalties_crnt"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_pnalties_prvious).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_pnalties_prvious" name="prv_pnalties_prvious"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1992_penalties" name="prv_prior_1992_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_prior_1991_penalties" name="prv_prior_1991_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(prv_total_basic).toFixed(2)+'" class="form-control" type="number" step="0.01" id="prv_total_basic" name="prv_total_basic" readonly></td>\
                            </tr>\
                            <tr>\
                                <td colspan="3">Municipal Share</td>\
                                <td class="border_all ctr">40%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_adv_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_adv_ammount" name="mnc_adv_ammount"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_adv_discount" name="mnc_adv_discount"></td>\
                                <td class="border_all ctr">40%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_current" name="munshare_basic_current"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_discount" name="munshare_basic_discount"></td>\
                                <td class="border_all ctr">40%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_previous" name="munshare_basic_previous"></td>\
                                <td class="border_all ctr">40%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(mnc_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1992_amt" name="mnc_prior_1992_amt"></td>\
                                <td class="border_all ctr">40%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(mnc_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1991_amt" name="mnc_prior_1991_amt"></td>\
                                <td class="border_all ctr">40%</td>\
                                <td colspan="3" class="border_all val"><input value="'+parseFloat(munshare_basic_penalty_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_penalty_current" name="munshare_basic_penalty_current"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(munshare_basic_penalty_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="munshare_basic_penalty_previous" name="munshare_basic_penalty_previous"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1992_penalties" name="mnc_prior_1992_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(mnc_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mnc_prior_1991_penalties" name="mnc_prior_1991_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(mncpal_total_basic).toFixed(2)+'" class="form-control" type="number" step="0.01" id="mncpal_total_basic" name="mncpal_total_basic" readonly></td>\
                            </tr>\
                            <tr>\
                                <td colspan="3">Barangay Share</td>\
                                <td class="border_all ctr">25%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_adv_ammount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_adv_ammount" name="brgy_adv_ammount"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_adv_discount" name="brgy_adv_discount"></td>\
                                <td class="border_all ctr">25%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_current" name="brgyshare_basic_current"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_discount" name="brgyshare_basic_discount"></td>\
                                <td class="border_all ctr">25%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_previous" name="brgyshare_basic_previous"></td>\
                                <td class="border_all ctr">25%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(brgy_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1992_amt" name="brgy_prior_1992_amt"></td>\
                                <td class="border_all ctr">25%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(brgy_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1991_amt" name="brgy_prior_1991_amt"></td>\
                                <td class="border_all ctr">25%</td>\
                                <td colspan="3" class="border_all val"><input value="'+parseFloat(brgyshare_basic_penalty_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_penalty_current" name="brgyshare_basic_penalty_current"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgyshare_basic_penalty_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgyshare_basic_penalty_previous" name="brgyshare_basic_penalty_previous"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1992_penalties" name="brgy_prior_1992_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_prior_1991_penalties" name="brgy_prior_1991_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(brgy_total_basic).toFixed(2)+'" class="form-control" type="number" step="0.01" id="brgy_total_basic" name="brgy_total_basic" readonly></td>\
                            </tr>\
                            <tr>\
                                <th colspan="3">TOTAL(S)</th>\
                                <td class="border_all"></td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(total_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_adv_amt" name="total_adv_amt" readonly></td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(total_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_adv_discount" name="total_adv_discount" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_current" name="total_basic_current" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_discount" name="total_basic_discount" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_previous" name="total_basic_previous" readonly></td>\
                                <td class="border_all"></td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1992_amt" name="total_prior_1992_amt" readonly></td>\
                                <td class="border_all"></td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1991_amt" name="total_prior_1991_amt" class="total_prior_1991_amt" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="3" class="border_all val"><input value="'+parseFloat(total_basic_penalty_current).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_penalty_current" name="total_basic_penalty_current" class="total_basic_penalty_current" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_penalty_previous).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_penalty_previous" name="total_basic_penalty_previous" class="total_basic_penalty_previous" readonly></td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1992_penalties" name="total_prior_1992_penalties"class="total_prior_1992_penalties" readonly></td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(total_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_prior_1991_penalties" name="total_prior_1991_penalties" class="total_prior_1991_penalties" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(total_basic_net).toFixed(2)+'" class="form-control" type="number" step="0.01" id="total_basic_net" name="total_basic_net" class="total_basic_net" readonly></td>\
                            </tr>';
                    }
                    

                    var xtotal_basic_current = parseFloat(total_basic_current) * .5;
                    var xtotal_basic_discount = parseFloat(total_basic_discount) * .5;
                    var xtotal_basic_previous = parseFloat(total_basic_previous) * .5;
                    var xtotal_basic_penalty_current = parseFloat(total_basic_penalty_current) * .5;
                    var xtotal_basic_penalty_previous = parseFloat(total_basic_penalty_previous) * .5;

                    var xtotal_basic_net = (parseFloat(xtotal_basic_current) - parseFloat(xtotal_basic_discount)) + parseFloat(xtotal_basic_previous)  + parseFloat(xtotal_basic_penalty_current) + parseFloat(xtotal_basic_penalty_previous);

                    // sef
                    content += '<tr>\
                            <td colspan="3"><b>SEF TAX 1%</b></td>\
                            <td class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td class="border_all"></td>\
                            <td colspan="3" class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                            <td colspan="2" class="border_all"></td>\
                        </tr>';

                    if(data.sef_exist != null && data.sef_exist != undefined && data.sef_exist.length > 0) {
                        if(data.sef_exist[0].report_sef_items[0] != null && data.sef_exist[0].report_sef_items[0] != undefined) {      
                            var provincial_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_adv_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_curr_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_1991).toFixed(2));
                            var municipal_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_adv_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_curr_amt).toFixed(2)) - parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_1991).toFixed(2));
                            var grandtotal = parseFloat(parseFloat(provincial_total).toFixed(2)) + parseFloat(parseFloat(municipal_total).toFixed(2));
                            var adv_amt_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_adv_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_adv_amt).toFixed(2));
                            var adv_discount_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_adv_discount).toFixed(2));
                            var curr_amt_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_curr_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_curr_amt).toFixed(2));
                            var curr_discount_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_curr_discount).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_curr_discount).toFixed(2));
                            var prev_amt_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_prev_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_prev_amt).toFixed(2));
                            var amt_1992_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_1992_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_1992_amt).toFixed(2));
                            var amt_1991_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_1991_amt).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_1991_amt).toFixed(2));
                            var penalty_curr_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_curr).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_curr).toFixed(2));
                            var penalty_prev_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_prev).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_prev).toFixed(2));
                            var penalty_1992_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_1992).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_1992).toFixed(2));
                            var penalty_1991_total = parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_1991).toFixed(2)) + parseFloat(parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_1991).toFixed(2));
                            content += '<tr>\
                                    <td colspan="3">Provincial Share</td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_adv_amt" id="sef_prv_adv_amt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_adv_discount" id="sef_prv_adv_discount"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_curr_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_amt" id="sef_prv_amt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_curr_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_discount" id="sef_prv_discount"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_prev_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prev_prv_amt" id="sef_prev_prv_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1992_amt" id="sef_prv_prior_1992_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1991_amt" id="sef_prv_prior_1991_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_curr).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_penalty" id="sef_prv_penalty"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prev_prv_penalty" id="sef_prev_prv_penalty"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_1992).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1992_penalties" id="sef_prv_prior_1992_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].prv_penalty_1991).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1991_penalties" id="sef_prv_prior_1991_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(provincial_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prv_net" name="sef_prv_net" readonly></td>\
                                </tr>\
                                <tr>\
                                    <td colspan="3">Municipal Share</td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_adv_amt" id="sef_mnc_adv_amt"></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_adv_discount" id="sef_mnc_adv_discount"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_curr_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_crnt" id="sef_mncpl_crnt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_curr_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_dscnt" id="sef_mncpl_dscnt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_prev_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_prev" id="sef_mncpl_prev"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1992_amt" id="sef_mnc_prior_1992_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1991_amt" id="sef_mnc_prior_1991_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_curr).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_pen_crnt" id="sef_mncpl_pen_crnt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_pen_crnt_prev" id="sef_mncpl_pen_crnt_prev"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_1992).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1992_penalties" id="sef_mnc_prior_1992_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(data.sef_exist[0].report_sef_items[0].mnc_penalty_1991).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1991_penalties" id="sef_mnc_prior_1991_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(municipal_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_basic_net" name="sef_total_basic_net" readonly></td>\
                                </tr>\
                                <tr>\
                                    <th colspan="3">TOTAL(S)</th>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(adv_amt_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_adv_amt" name="sef_total_adv_amt" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(adv_discount_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_adv_discount" name="sef_total_adv_discount" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(curr_amt_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_total" name="sef_curr_total" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(curr_discount_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_discount_total" name="sef_curr_discount_total" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(prev_amt_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prev_total" name="sef_prev_total" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(amt_1992_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1992_amt" name="sef_total_prior_1992_amt" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(amt_1991_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1991_amt" name="sef_total_prior_1991_amt" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(penalty_curr_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_pen_total" name="sef_curr_pen_total" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(penalty_prev_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prev_penalty_total" name="sef_prev_penalty_total" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(penalty_1992_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1992_penalties" name="sef_total_prior_1992_penalties" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(penalty_1991_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1991_penalties" name="sef_total_prior_1991_penalties" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(grandtotal).toFixed(2)+'" class="form-control" type="number" step="0.01" id="gtotal_sef" name="gtotal_sef" readonly></td>\
                                </tr>';

                            content+= '</table>\
                            </div>';
                        } else {
                            // PROVINCIAL SHARE LARGER SHARE (* 50%)
                            // discount lower for prov'l share
                            var sef_prv_amt = parseFloat(total_basic_current) * .5;
                            var sef_prev_prv_amt = parseFloat(total_basic_previous) * .5;
                            // $sef_prv_penalty = $total_basic_penalty_current * .5;
                            // $sef_prev_prv_penalty = $total_basic_penalty_previous * .5;          

                            if(total_basic_discount > 0 && total_adv_discount > 0) {
                                var sef_prv_discount = parseFloat(total_basic_discount) * .5; 
                                var sef_mncpl_dscnt = parseFloat(parseFloat(total_basic_discount).toFixed(2)) - parseFloat(parseFloat(sef_prv_discount).toFixed(2));
                            } else {
                                var sef_mncpl_dscnt = parseFloat(total_basic_discount) * .5; 
                                var sef_prv_discount = parseFloat(parseFloat(total_basic_discount).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2));
                            }

                            var sef_mncpl_crnt = parseFloat(parseFloat(total_basic_current).toFixed(2)) - parseFloat(parseFloat(sef_prv_amt).toFixed(2)); 
                            var sef_mncpl_prev = parseFloat(parseFloat(total_basic_previous).toFixed(2)) - parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)); 
                            // $sef_mncpl_pen_crnt = parseFloat($total_basic_penalty_current).toFixed(2) - parseFloat($sef_prv_penalty).toFixed(2); // less
                            // $sef_mncpl_pen_crnt_prev = parseFloat($total_basic_penalty_previous).toFixed(2) - parseFloat($sef_prev_prv_penalty).toFixed(2); // less  

                            if(total_basic_penalty_current > 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 <= 0 && total_penalty_prior_1991 <= 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                                var sef_mncpl_pen_crnt = parseFloat(total_basic_penalty_current) * .5;
                                var sef_prv_penalty = parseFloat(parseFloat(total_basic_penalty_current).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2)); // less
                            } else {
                                var sef_prv_penalty = parseFloat(total_basic_penalty_current) * .5;
                                var sef_mncpl_pen_crnt = parseFloat(parseFloat(total_basic_penalty_current).toFixed(2)) - parseFloat(parseFloat(sef_prv_penalty).toFixed(2)); // less
                            }

                            if(total_basic_penalty_current > 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 <= 0 && total_penalty_prior_1991 <= 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                                var sef_mncpl_pen_crnt_prev = parseFloat(total_basic_penalty_previous) * .5; 
                                var sef_prev_prv_penalty = parseFloat(parseFloat(total_basic_penalty_previous).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2)); // less 
                            } else {
                                var sef_prev_prv_penalty = parseFloat(total_basic_penalty_previous) * .5; 
                                var sef_mncpl_pen_crnt_prev = parseFloat(parseFloat(total_basic_penalty_previous).toFixed(2)) - parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)); // less 
                            }

                            var sef_curr_total = parseFloat(parseFloat(sef_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_crnt).toFixed(2)); 
                            var sef_curr_discount_total = parseFloat(parseFloat(sef_prv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2));
                            var sef_curr_pen_total = parseFloat(parseFloat(sef_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2));


                            var sef_prev_total = parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_prev).toFixed(2));
                            var sef_prev_penalty_total = parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2));

                            // advance
                            var sef_prv_adv_amt = parseFloat(total_adv) * .50;
                            var sef_mnc_adv_amt = parseFloat(parseFloat(total_adv).toFixed(2)) - parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2));
                            var sef_total_adv_amt = parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_amt).toFixed(2));
                            if(total_basic_discount > 0 && total_adv_discount > 0) {
                                var sef_prv_adv_discount = parseFloat(total_adv_discount) * .50;
                                var sef_mnc_adv_discount = parseFloat(parseFloat(total_adv_discount).toFixed(2)) - parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2)); // lesser
                            } else {
                                var sef_mnc_adv_discount = parseFloat(total_adv_discount) * .50;
                                var sef_prv_adv_discount = parseFloat(parseFloat(total_adv_discount).toFixed(2)) - parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2)); // lesser
                            }
                            
                            var sef_total_adv_discount = parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2));

                            // 1992-above
                            var sef_prv_prior_1992_amt = parseFloat(total_prior_1992) * .50;
                            var sef_mnc_prior_1992_amt = parseFloat(parseFloat(total_prior_1992).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2));
                            // $sef_prv_prior_1992_penalties = $total_penalty_prior_1992 * .50;
                            // $sef_mnc_prior_1992_penalties = parseFloat($total_penalty_prior_1992).toFixed(2) - parseFloat($sef_prv_prior_1992_penalties).toFixed(2);
                            if(total_basic_penalty_current <= 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 > 0 && total_penalty_prior_1991 <= 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                                var sef_mnc_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .50;
                                var sef_prv_prior_1992_penalties = parseFloat(parseFloat(total_penalty_prior_1992).toFixed(2)) - parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2));
                            } else {
                                var sef_prv_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .50;
                                var sef_mnc_prior_1992_penalties = parseFloat(parseFloat(total_penalty_prior_1992).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2));
                            }
                            var sef_total_prior_1992_amt = parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_amt).toFixed(2));
                            var sef_total_prior_1992_penalties = parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2));

                            // 1991-below
                            var sef_prv_prior_1991_amt = parseFloat(total_prior_1991) * .50;
                            var sef_mnc_prior_1991_amt = parseFloat(parseFloat(total_prior_1991).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2));
                            // $sef_prv_prior_1991_penalties = $total_penalty_prior_1991 * .50;
                            // $sef_mnc_prior_1991_penalties = parseFloat($total_penalty_prior_1991).toFixed(2) - parseFloat($sef_prv_prior_1991_penalties).toFixed(2);
                            if(total_basic_penalty_current <= 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 <= 0 && total_penalty_prior_1991 > 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                                var sef_mnc_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .50;
                                var sef_prv_prior_1991_penalties = parseFloat(parseFloat(total_penalty_prior_1991).toFixed(2)) - parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));
                            } else {
                                var sef_prv_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .50;
                                var sef_mnc_prior_1991_penalties = parseFloat(parseFloat(total_penalty_prior_1991).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2));
                            }
                            var sef_total_prior_1991_amt = parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_amt).toFixed(2));
                            var sef_total_prior_1991_penalties = parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));

                            var sef_prv_net = 0;
                            var sef_total_basic_net = 0;
                            var gtotal_sef = 0;
                            sef_prv_net += parseFloat(parseFloat(sef_prv_amt).toFixed(2)) - parseFloat(parseFloat(sef_prv_discount).toFixed(2)) + parseFloat(parseFloat(sef_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2)) - parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2));
                            sef_total_basic_net += parseFloat(parseFloat(sef_mncpl_crnt).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_prev).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_amt).toFixed(2)) - parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));
                            gtotal_sef += parseFloat(parseFloat(sef_prv_net).toFixed(2)) + parseFloat(parseFloat(sef_total_basic_net).toFixed(2));

                            content += '<tr>\
                                    <td colspan="3">Provincial Share</td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_adv_amt" id="sef_prv_adv_amt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_adv_discount" id="sef_prv_adv_discount"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_amt" id="sef_prv_amt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_discount" id="sef_prv_discount"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_prv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prev_prv_amt" id="sef_prev_prv_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1992_amt" id="sef_prv_prior_1992_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1991_amt" id="sef_prv_prior_1991_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(sef_prv_penalty).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_penalty" id="sef_prv_penalty"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_prv_penalty).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prev_prv_penalty" id="sef_prev_prv_penalty"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1992_penalties" id="sef_prv_prior_1992_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1991_penalties" id="sef_prv_prior_1991_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_net).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prv_net" name="sef_prv_net" readonly></td>\
                                </tr>\
                                <tr>\
                                    <td colspan="3">Municipal Share</td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_adv_amt" id="sef_mnc_adv_amt"></td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_adv_discount" id="sef_mnc_adv_discount"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_crnt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_crnt" id="sef_mncpl_crnt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_dscnt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_dscnt" id="sef_mncpl_dscnt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_prev" id="sef_mncpl_prev"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1992_amt" id="sef_mnc_prior_1992_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1991_amt" id="sef_mnc_prior_1991_amt"></td>\
                                    <td class="border_all ctr">50%</td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(sef_mncpl_pen_crnt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_pen_crnt" id="sef_mncpl_pen_crnt"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_pen_crnt_prev" id="sef_mncpl_pen_crnt_prev"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mnc_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1992_penalties" id="sef_mnc_prior_1992_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mnc_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1991_penalties" id="sef_mnc_prior_1991_penalties"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_basic_net).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_basic_net" name="sef_total_basic_net" readonly></td>\
                                </tr>\
                                <tr>\
                                    <th colspan="3">TOTAL(S)</th>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_adv_amt" name="sef_total_adv_amt" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_adv_discount" name="sef_total_adv_discount" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_curr_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_total" name="sef_curr_total" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_curr_discount_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_discount_total" name="sef_curr_discount_total" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prev_total" name="sef_prev_total" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1992_amt" name="sef_total_prior_1992_amt" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1991_amt" name="sef_total_prior_1991_amt" readonly></td>\
                                    <td class="border_all"></td>\
                                    <td colspan="3" class="border_all val"><input value="'+parseFloat(sef_curr_pen_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_pen_total" name="sef_curr_pen_total" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_penalty_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prev_penalty_total" name="sef_prev_penalty_total" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1992_penalties" name="sef_total_prior_1992_penalties" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1991_penalties" name="sef_total_prior_1991_penalties" readonly></td>\
                                    <td colspan="2" class="border_all val"><input value="'+parseFloat(gtotal_sef).toFixed(2)+'" class="form-control" type="number" step="0.01" id="gtotal_sef" name="gtotal_sef" readonly></td>\
                                </tr>';

                            content+= '</table>\
                            </div>';
                        }
                    } else {
                        // PROVINCIAL SHARE LARGER SHARE (* 50%)
                        // discount lower for prov'l share
                        var sef_prv_amt = parseFloat(total_basic_current) * .5;
                        var sef_prev_prv_amt = parseFloat(total_basic_previous) * .5;
                        // $sef_prv_penalty = $total_basic_penalty_current * .5;
                        // $sef_prev_prv_penalty = $total_basic_penalty_previous * .5;          

                        if(total_basic_discount > 0 && total_adv_discount > 0) {
                            var sef_prv_discount = parseFloat(total_basic_discount) * .5; 
                            var sef_mncpl_dscnt = parseFloat(parseFloat(total_basic_discount).toFixed(2)) - parseFloat(parseFloat(sef_prv_discount).toFixed(2));
                        } else {
                            var sef_mncpl_dscnt = parseFloat(total_basic_discount) * .5; 
                            var sef_prv_discount = parseFloat(parseFloat(total_basic_discount).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2));
                        }

                        var sef_mncpl_crnt = parseFloat(parseFloat(total_basic_current).toFixed(2)) - parseFloat(parseFloat(sef_prv_amt).toFixed(2)); 
                        var sef_mncpl_prev = parseFloat(parseFloat(total_basic_previous).toFixed(2)) - parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)); 
                        // $sef_mncpl_pen_crnt = parseFloat($total_basic_penalty_current).toFixed(2) - parseFloat($sef_prv_penalty).toFixed(2); // less
                        // $sef_mncpl_pen_crnt_prev = parseFloat($total_basic_penalty_previous).toFixed(2) - parseFloat($sef_prev_prv_penalty).toFixed(2); // less  

                        if(total_basic_penalty_current > 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 <= 0 && total_penalty_prior_1991 <= 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                            var sef_mncpl_pen_crnt = parseFloat(total_basic_penalty_current) * .5;
                            var sef_prv_penalty = parseFloat(parseFloat(total_basic_penalty_current).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2)); // less
                        } else {
                            var sef_prv_penalty = parseFloat(total_basic_penalty_current) * .5;
                            var sef_mncpl_pen_crnt = parseFloat(parseFloat(total_basic_penalty_current).toFixed(2)) - parseFloat(parseFloat(sef_prv_penalty).toFixed(2)); // less
                        }

                        if(total_basic_penalty_current > 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 <= 0 && total_penalty_prior_1991 <= 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                            var sef_mncpl_pen_crnt_prev = parseFloat(total_basic_penalty_previous) * .5; 
                            var sef_prev_prv_penalty = parseFloat(parseFloat(total_basic_penalty_previous).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2)); // less 
                        } else {
                            var sef_prev_prv_penalty = parseFloat(total_basic_penalty_previous) * .5; 
                            var sef_mncpl_pen_crnt_prev = parseFloat(parseFloat(total_basic_penalty_previous).toFixed(2)) - parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)); // less 
                        }

                        var sef_curr_total = parseFloat(parseFloat(sef_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_crnt).toFixed(2)); 
                        var sef_curr_discount_total = parseFloat(parseFloat(sef_prv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2));
                        var sef_curr_pen_total = parseFloat(parseFloat(sef_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2));


                        var sef_prev_total = parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_prev).toFixed(2));
                        var sef_prev_penalty_total = parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2));

                        // advance
                        var sef_prv_adv_amt = parseFloat(total_adv) * .50;
                        var sef_mnc_adv_amt = parseFloat(parseFloat(total_adv).toFixed(2)) - parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2));
                        var sef_total_adv_amt = parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_amt).toFixed(2));
                        if(total_basic_discount > 0 && total_adv_discount > 0) {
                            var sef_prv_adv_discount = parseFloat(total_adv_discount) * .50;
                            var sef_mnc_adv_discount = parseFloat(parseFloat(total_adv_discount).toFixed(2)) - parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2)); // lesser
                        } else {
                            var sef_mnc_adv_discount = parseFloat(total_adv_discount) * .50;
                            var sef_prv_adv_discount = parseFloat(parseFloat(total_adv_discount).toFixed(2)) - parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2)); // lesser
                        }
                        
                        var sef_total_adv_discount = parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2));

                        // 1992-above
                        var sef_prv_prior_1992_amt = parseFloat(total_prior_1992) * .50;
                        var sef_mnc_prior_1992_amt = parseFloat(parseFloat(total_prior_1992).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2));
                        // $sef_prv_prior_1992_penalties = $total_penalty_prior_1992 * .50;
                        // $sef_mnc_prior_1992_penalties = parseFloat($total_penalty_prior_1992).toFixed(2) - parseFloat($sef_prv_prior_1992_penalties).toFixed(2);
                        if(total_basic_penalty_current <= 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 > 0 && total_penalty_prior_1991 <= 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                            var sef_mnc_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .50;
                            var sef_prv_prior_1992_penalties = parseFloat(parseFloat(total_penalty_prior_1992).toFixed(2)) - parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2));
                        } else {
                            var sef_prv_prior_1992_penalties = parseFloat(total_penalty_prior_1992) * .50;
                            var sef_mnc_prior_1992_penalties = parseFloat(parseFloat(total_penalty_prior_1992).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2));
                        }
                        var sef_total_prior_1992_amt = parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_amt).toFixed(2));
                        var sef_total_prior_1992_penalties = parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2));

                        // 1991-below
                        var sef_prv_prior_1991_amt = parseFloat(total_prior_1991) * .50;
                        var sef_mnc_prior_1991_amt = parseFloat(parseFloat(total_prior_1991).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2));
                        // $sef_prv_prior_1991_penalties = $total_penalty_prior_1991 * .50;
                        // $sef_mnc_prior_1991_penalties = parseFloat($total_penalty_prior_1991).toFixed(2) - parseFloat($sef_prv_prior_1991_penalties).toFixed(2);
                        if(total_basic_penalty_current <= 0 && total_basic_penalty_previous <= 0 && total_penalty_prior_1992 <= 0 && total_penalty_prior_1991 > 0 && (total_basic_discount > 0 || total_adv_discount > 0)) {
                            var sef_mnc_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .50;
                            var sef_prv_prior_1991_penalties = parseFloat(parseFloat(total_penalty_prior_1991).toFixed(2)) - parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));
                        } else {
                            var sef_prv_prior_1991_penalties = parseFloat(total_penalty_prior_1991) * .50;
                            var sef_mnc_prior_1991_penalties = parseFloat(parseFloat(total_penalty_prior_1991).toFixed(2)) - parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2));
                        }
                        var sef_total_prior_1991_amt = parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_amt).toFixed(2));
                        var sef_total_prior_1991_penalties = parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));

                        var sef_prv_net = 0;
                        var sef_total_basic_net = 0;
                        var gtotal_sef = 0;
                        sef_prv_net += parseFloat(parseFloat(sef_prv_amt).toFixed(2)) - parseFloat(parseFloat(sef_prv_discount).toFixed(2)) + parseFloat(parseFloat(sef_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2)) - parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2));
                        sef_total_basic_net += parseFloat(parseFloat(sef_mncpl_crnt).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_prev).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_amt).toFixed(2)) - parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));
                        gtotal_sef += parseFloat(parseFloat(sef_prv_net).toFixed(2)) + parseFloat(parseFloat(sef_total_basic_net).toFixed(2));

                        content += '<tr>\
                                <td colspan="3">Provincial Share</td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_adv_amt" id="sef_prv_adv_amt"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_adv_discount" id="sef_prv_adv_discount"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_amt" id="sef_prv_amt"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_discount" id="sef_prv_discount"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_prv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prev_prv_amt" id="sef_prev_prv_amt"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1992_amt" id="sef_prv_prior_1992_amt"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1991_amt" id="sef_prv_prior_1991_amt"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="3" class="border_all val"><input value="'+parseFloat(sef_prv_penalty).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_penalty" id="sef_prv_penalty"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_prv_penalty).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prev_prv_penalty" id="sef_prev_prv_penalty"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1992_penalties" id="sef_prv_prior_1992_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_prv_prior_1991_penalties" id="sef_prv_prior_1991_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prv_net).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prv_net" name="sef_prv_net" readonly></td>\
                            </tr>\
                            <tr>\
                                <td colspan="3">Municipal Share</td>\
                                <td class="border_all ctr">50%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_adv_amt" id="sef_mnc_adv_amt"></td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_adv_discount" id="sef_mnc_adv_discount"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_crnt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_crnt" id="sef_mncpl_crnt"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_dscnt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_dscnt" id="sef_mncpl_dscnt"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_prev" id="sef_mncpl_prev"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1992_amt" id="sef_mnc_prior_1992_amt"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td class="border_all val" colspan="2"><input value="'+parseFloat(sef_mnc_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1991_amt" id="sef_mnc_prior_1991_amt"></td>\
                                <td class="border_all ctr">50%</td>\
                                <td colspan="3" class="border_all val"><input value="'+parseFloat(sef_mncpl_pen_crnt).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_pen_crnt" id="sef_mncpl_pen_crnt"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mncpl_pen_crnt_prev" id="sef_mncpl_pen_crnt_prev"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mnc_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1992_penalties" id="sef_mnc_prior_1992_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_mnc_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" name="sef_mnc_prior_1991_penalties" id="sef_mnc_prior_1991_penalties"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_basic_net).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_basic_net" name="sef_total_basic_net" readonly></td>\
                            </tr>\
                            <tr>\
                                <th colspan="3">TOTAL(S)</th>\
                                <td class="border_all"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_adv_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_adv_amt" name="sef_total_adv_amt" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_adv_discount).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_adv_discount" name="sef_total_adv_discount" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_curr_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_total" name="sef_curr_total" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_curr_discount_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_discount_total" name="sef_curr_discount_total" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prev_total" name="sef_prev_total" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1992_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1992_amt" name="sef_total_prior_1992_amt" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1991_amt).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1991_amt" name="sef_total_prior_1991_amt" readonly></td>\
                                <td class="border_all"></td>\
                                <td colspan="3" class="border_all val"><input value="'+parseFloat(sef_curr_pen_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_curr_pen_total" name="sef_curr_pen_total" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_prev_penalty_total).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_prev_penalty_total" name="sef_prev_penalty_total" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1992_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1992_penalties" name="sef_total_prior_1992_penalties" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(sef_total_prior_1991_penalties).toFixed(2)+'" class="form-control" type="number" step="0.01" id="sef_total_prior_1991_penalties" name="sef_total_prior_1991_penalties" readonly></td>\
                                <td colspan="2" class="border_all val"><input value="'+parseFloat(gtotal_sef).toFixed(2)+'" class="form-control" type="number" step="0.01" id="gtotal_sef" name="gtotal_sef" readonly></td>\
                            </tr>';

                        content+= '</table>\
                        </div>';
                    }
                    
                            
                    $('#report_content').append(content);
                    $('.date').datepicker({
                        changeMonth:true,
                        changeYear:true,
                        showAnim:'slide',
                        dateFormat: 'mm/dd/yy'
                    });
                    $('#munic').val(municipality);
                    $('#start_date').val(moment(new Date(start_date)).format('MM/DD/YYYY'));
                    $('#end_date').val(moment(new Date(end_date)).format('MM/DD/YYYY'));
                    $('#report_num').val(report_no);
                    $('#report_date').val(moment(new Date(report_date)).format('MM/DD/YYYY'));
                } else {
                    $('#submit').css('display', 'none');
                    $('#report_content_modal').modal('show');
                    $('#report_content').addClass('alert alert-danger')
                    $('#report_content').append('<p>'+data+'</p>')
                }
            }
        });

        $(document).on('focus', 'input[type=number]', function() {
            $(this).animate({width: "110px"}, 400);
        });

        $(document).on('focusout', 'input[type=number]', function() {
            $(this).animate({width: "60px"}, 400);
        });

        $(document).on('keyup', 'input[type=number]', function() {
            if($(this).val() == '') {
                $(this).css('box-shadow', '1px 1px 7px #f52c2c');
            } else {
                $(this).css('box-shadow', 'none');
            }

            // basic
            var prv_adv_ammount = $('#prv_adv_ammount').val() != '' ? $('#prv_adv_ammount').val() : 0; 
            var prv_adv_discount = $('#prv_adv_discount').val() != '' ? $('#prv_adv_discount').val() : 0; 
            var prv_crnt_ammount = $('#prv_crnt_ammount').val() != '' ? $('#prv_crnt_ammount').val() : 0; 
            var prv_crnt_discount = $('#prv_crnt_discount').val() != '' ? $('#prv_crnt_discount').val() : 0; 
            var prv_prvious_ammount = $('#prv_prvious_ammount').val() != '' ? $('#prv_prvious_ammount').val() : 0; 
            var prv_prior_1992_amt = $('#prv_prior_1992_amt').val() != '' ? $('#prv_prior_1992_amt').val() : 0; 
            var prv_prior_1991_amt = $('#prv_prior_1991_amt').val() != '' ? $('#prv_prior_1991_amt').val() : 0; 
            var prv_pnalties_crnt = $('#prv_pnalties_crnt').val() != '' ? $('#prv_pnalties_crnt').val() : 0; 
            var prv_pnalties_prvious = $('#prv_pnalties_prvious').val() != '' ? $('#prv_pnalties_prvious').val() : 0; 
            var prv_prior_1992_penalties = $('#prv_prior_1992_penalties').val() != '' ? $('#prv_prior_1992_penalties').val() : 0; 
            var prv_prior_1991_penalties = $('#prv_prior_1991_penalties').val() != '' ? $('#prv_prior_1991_penalties').val() : 0; 

            var mnc_adv_ammount = $('#mnc_adv_ammount').val() != '' ? $('#mnc_adv_ammount').val() : 0;
            var mnc_adv_discount = $('#mnc_adv_discount').val() != '' ? $('#mnc_adv_discount').val() : 0;
            var munshare_basic_current = $('#munshare_basic_current').val() != '' ? $('#munshare_basic_current').val() : 0;
            var munshare_basic_discount = $('#munshare_basic_discount').val() != '' ? $('#munshare_basic_discount').val() : 0;
            var munshare_basic_previous = $('#munshare_basic_previous').val() != '' ? $('#munshare_basic_previous').val() : 0;
            var mnc_prior_1992_amt = $('#mnc_prior_1992_amt').val() != '' ? $('#mnc_prior_1992_amt').val() : 0;
            var mnc_prior_1991_amt = $('#mnc_prior_1991_amt').val() != '' ? $('#mnc_prior_1991_amt').val() : 0;
            var munshare_basic_penalty_current = $('#munshare_basic_penalty_current').val() != '' ? $('#munshare_basic_penalty_current').val() : 0;
            var munshare_basic_penalty_previous = $('#munshare_basic_penalty_previous').val() != '' ? $('#munshare_basic_penalty_previous').val() : 0;
            var mnc_prior_1992_penalties = $('#mnc_prior_1992_penalties').val() != '' ? $('#mnc_prior_1992_penalties').val() : 0;
            var mnc_prior_1991_penalties = $('#mnc_prior_1991_penalties').val() != '' ? $('#mnc_prior_1991_penalties').val() : 0;

            var brgy_adv_ammount = $('#brgy_adv_ammount').val() != '' ? $('#brgy_adv_ammount').val() : 0;
            var brgy_adv_discount = $('#brgy_adv_discount').val() != '' ? $('#brgy_adv_discount').val() : 0;
            var brgyshare_basic_current = $('#brgyshare_basic_current').val() != '' ? $('#brgyshare_basic_current').val() : 0;
            var brgyshare_basic_discount = $('#brgyshare_basic_discount').val() != '' ? $('#brgyshare_basic_discount').val() : 0;
            var brgyshare_basic_previous = $('#brgyshare_basic_previous').val() != '' ? $('#brgyshare_basic_previous').val() : 0;
            var brgy_prior_1992_amt = $('#brgy_prior_1992_amt').val() != '' ? $('#brgy_prior_1992_amt').val() : 0;
            var brgy_prior_1991_amt = $('#brgy_prior_1991_amt').val() != '' ? $('#brgy_prior_1991_amt').val() : 0;
            var brgyshare_basic_penalty_current = $('#brgyshare_basic_penalty_current').val() != '' ? $('#brgyshare_basic_penalty_current').val() : 0;
            var brgyshare_basic_penalty_previous = $('#brgyshare_basic_penalty_previous').val() != '' ? $('#brgyshare_basic_penalty_previous').val() : 0;
            var brgy_prior_1992_penalties = $('#brgy_prior_1992_penalties').val() != '' ? $('#brgy_prior_1992_penalties').val() : 0;
            var brgy_prior_1991_penalties = $('#brgy_prior_1991_penalties').val() != '' ? $('#brgy_prior_1991_penalties').val() : 0;

            // calculate gross amt
            var basic_prv_total = (parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) - parseFloat(parseFloat(prv_adv_discount).toFixed(2))) + (parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) - parseFloat(parseFloat(prv_crnt_discount).toFixed(2))) + parseFloat(parseFloat(prv_prvious_ammount).toFixed(2)) + parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(prv_pnalties_crnt).toFixed(2)) + parseFloat(parseFloat(prv_pnalties_prvious).toFixed(2)) + parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2));
            var basic_mnc_total = (parseFloat(parseFloat(mnc_adv_ammount).toFixed(2)) - parseFloat(parseFloat(mnc_adv_discount).toFixed(2))) + (parseFloat(parseFloat(munshare_basic_current).toFixed(2)) - parseFloat(parseFloat(munshare_basic_discount).toFixed(2))) + parseFloat(parseFloat(munshare_basic_previous).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_current).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_previous).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2));
            var basic_brgy_total = (parseFloat(parseFloat(brgy_adv_ammount).toFixed(2)) - parseFloat(parseFloat(brgy_adv_discount).toFixed(2))) + (parseFloat(parseFloat(brgyshare_basic_current).toFixed(2)) - parseFloat(parseFloat(brgyshare_basic_discount).toFixed(2))) + parseFloat(parseFloat(brgyshare_basic_previous).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_current).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_previous).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_penalties).toFixed(2));

            var total_adv_amt = parseFloat(parseFloat(prv_adv_ammount).toFixed(2)) + parseFloat(parseFloat(mnc_adv_ammount).toFixed(2)) + parseFloat(parseFloat(brgy_adv_ammount).toFixed(2));
            var total_adv_discount = parseFloat(parseFloat(prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(mnc_adv_discount).toFixed(2)) + parseFloat(parseFloat(brgy_adv_discount).toFixed(2));
            var total_basic_current = parseFloat(parseFloat(prv_crnt_ammount).toFixed(2)) + parseFloat(parseFloat(munshare_basic_current).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_current).toFixed(2));
            var total_basic_discount = parseFloat(parseFloat(prv_crnt_discount).toFixed(2)) + parseFloat(parseFloat(munshare_basic_discount).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_discount).toFixed(2));
            var total_basic_previous = parseFloat(parseFloat(prv_prvious_ammount).toFixed(2)) + parseFloat(parseFloat(munshare_basic_previous).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_previous).toFixed(2));
            var total_prior_1992_amt = parseFloat(parseFloat(prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_amt).toFixed(2));
            var total_prior_1991_amt = parseFloat(parseFloat(prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_amt).toFixed(2));
            var total_basic_penalty_current = parseFloat(parseFloat(prv_pnalties_crnt).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_current).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_current).toFixed(2));
            var total_basic_penalty_previous = parseFloat(parseFloat(prv_pnalties_prvious).toFixed(2)) + parseFloat(parseFloat(munshare_basic_penalty_previous).toFixed(2)) + parseFloat(parseFloat(brgyshare_basic_penalty_previous).toFixed(2));
            var total_prior_1992_penalties = parseFloat(parseFloat(prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1992_penalties).toFixed(2));
            var total_prior_1991_penalties = parseFloat(parseFloat(prv_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(mnc_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(brgy_prior_1991_penalties).toFixed(2));
            var total_basic_net = parseFloat(parseFloat(basic_prv_total).toFixed(2)) + parseFloat(parseFloat(basic_mnc_total).toFixed(2)) + parseFloat(parseFloat(basic_brgy_total).toFixed(2));


            $('#total_adv_amt').val(parseFloat(total_adv_amt).toFixed(2));
            $('#total_adv_discount').val(parseFloat(total_adv_discount).toFixed(2));
            $('#total_basic_current').val(parseFloat(total_basic_current).toFixed(2));
            $('#total_basic_discount').val(parseFloat(total_basic_discount).toFixed(2));
            $('#total_basic_previous').val(parseFloat(total_basic_previous).toFixed(2));
            $('#total_prior_1992_amt').val(parseFloat(total_prior_1992_amt).toFixed(2));
            $('#total_prior_1991_amt').val(parseFloat(total_prior_1991_amt).toFixed(2));
            $('#total_basic_penalty_current').val(parseFloat(total_basic_penalty_current).toFixed(2));
            $('#total_basic_penalty_previous').val(parseFloat(total_basic_penalty_previous).toFixed(2));
            $('#total_prior_1992_penalties').val(parseFloat(total_prior_1992_penalties).toFixed(2));
            $('#total_prior_1991_penalties').val(parseFloat(total_prior_1991_penalties).toFixed(2));
            $('#total_basic_net').val(parseFloat(total_basic_net).toFixed(2));
            $('#prv_total_basic').val(parseFloat(basic_prv_total).toFixed(2));
            $('#mncpal_total_basic').val(parseFloat(basic_mnc_total).toFixed(2));
            $('#brgy_total_basic').val(parseFloat(basic_brgy_total).toFixed(2));

            // sef
            // provincial
            var sef_prv_adv_amt = $('#sef_prv_adv_amt').val() != '' ? $('#sef_prv_adv_amt').val() : 0;
            var sef_prv_adv_discount = $('#sef_prv_adv_discount').val() != '' ? $('#sef_prv_adv_discount').val() : 0;
            var sef_prv_amt = $('#sef_prv_amt').val() != '' ? $('#sef_prv_amt').val() : 0;
            var sef_prv_discount = $('#sef_prv_discount').val() != '' ? $('#sef_prv_discount').val() : 0;
            var sef_prev_prv_amt = $('#sef_prev_prv_amt').val() != '' ? $('#sef_prev_prv_amt').val() : 0;
            var sef_prv_prior_1992_amt = $('#sef_prv_prior_1992_amt').val() != '' ? $('#sef_prv_prior_1992_amt').val() : 0;
            var sef_prv_prior_1991_amt = $('#sef_prv_prior_1991_amt').val() != '' ? $('#sef_prv_prior_1991_amt').val() : 0;
            var sef_prv_penalty = $('#sef_prv_penalty').val() != '' ? $('#sef_prv_penalty').val() : 0;
            var sef_prev_prv_penalty = $('#sef_prev_prv_penalty').val() != '' ? $('#sef_prev_prv_penalty').val() : 0;
            var sef_prv_prior_1992_penalties = $('#sef_prv_prior_1992_penalties').val() != '' ? $('#sef_prv_prior_1992_penalties').val() : 0;
            var sef_prv_prior_1991_penalties = $('#sef_prv_prior_1991_penalties').val() != '' ? $('#sef_prv_prior_1991_penalties').val() : 0;

            // municipal
            var sef_mnc_adv_amt = $('#sef_mnc_adv_amt').val() != '' ? $('#sef_mnc_adv_amt').val() : 0;
            var sef_mnc_adv_discount = $('#sef_mnc_adv_discount').val() != '' ? $('#sef_mnc_adv_discount').val() : 0;
            var sef_mncpl_crnt = $('#sef_mncpl_crnt').val() != '' ? $('#sef_mncpl_crnt').val() : 0;
            var sef_mncpl_dscnt = $('#sef_mncpl_dscnt').val() != '' ? $('#sef_mncpl_dscnt').val() : 0;
            var sef_mncpl_prev = $('#sef_mncpl_prev').val() != '' ? $('#sef_mncpl_prev').val() : 0;
            var sef_mnc_prior_1992_amt = $('#sef_mnc_prior_1992_amt').val() != '' ? $('#sef_mnc_prior_1992_amt').val() : 0;
            var sef_mnc_prior_1991_amt = $('#sef_mnc_prior_1991_amt').val() != '' ? $('#sef_mnc_prior_1991_amt').val() : 0;
            var sef_mncpl_pen_crnt = $('#sef_mncpl_pen_crnt').val() != '' ? $('#sef_mncpl_pen_crnt').val() : 0;
            var sef_mncpl_pen_crnt_prev = $('#sef_mncpl_pen_crnt_prev').val() != '' ? $('#sef_mncpl_pen_crnt_prev').val() : 0;
            var sef_mnc_prior_1992_penalties = $('#sef_mnc_prior_1992_penalties').val() != '' ? $('#sef_mnc_prior_1992_penalties').val() : 0;
            var sef_mnc_prior_1991_penalties = $('#sef_mnc_prior_1991_penalties').val() != '' ? $('#sef_mnc_prior_1991_penalties').val() : 0;

            // calculate gross amount
            var prv_gross = (parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2)) - parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2))) + (parseFloat(parseFloat(sef_prv_amt).toFixed(2)) - parseFloat(parseFloat(sef_prv_discount).toFixed(2))) + parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2));
            var mnc_gross = (parseFloat(parseFloat(sef_mnc_adv_amt).toFixed(2)) - parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2))) + (parseFloat(parseFloat(sef_mncpl_crnt).toFixed(2)) - parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2))) + parseFloat(parseFloat(sef_mncpl_prev).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));
            var gtotal = parseFloat(parseFloat(prv_gross).toFixed(2)) + parseFloat(parseFloat(mnc_gross).toFixed(2));

            var sef_total_adv_amt = parseFloat(parseFloat(sef_prv_adv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_amt).toFixed(2));
            var sef_total_adv_discount = parseFloat(parseFloat(sef_prv_adv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mnc_adv_discount).toFixed(2));
            var sef_curr_total = parseFloat(parseFloat(sef_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_crnt).toFixed(2));
            var sef_curr_discount_total  = parseFloat(parseFloat(sef_prv_discount).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_dscnt).toFixed(2));
            var sef_prev_total = parseFloat(parseFloat(sef_prev_prv_amt).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_prev).toFixed(2));
            var sef_total_prior_1992_amt = parseFloat(parseFloat(sef_prv_prior_1992_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_amt).toFixed(2));
            var sef_total_prior_1991_amt = parseFloat(parseFloat(sef_prv_prior_1991_amt).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_amt).toFixed(2));
            var sef_curr_pen_total = parseFloat(parseFloat(sef_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt).toFixed(2));
            var sef_prev_penalty_total = parseFloat(parseFloat(sef_prev_prv_penalty).toFixed(2)) + parseFloat(parseFloat(sef_mncpl_pen_crnt_prev).toFixed(2));
            var sef_total_prior_1992_penalties = parseFloat(parseFloat(sef_prv_prior_1992_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1992_penalties).toFixed(2));
            var sef_total_prior_1991_penalties = parseFloat(parseFloat(sef_prv_prior_1991_penalties).toFixed(2)) + parseFloat(parseFloat(sef_mnc_prior_1991_penalties).toFixed(2));

            $('#sef_total_adv_amt').val(parseFloat(sef_total_adv_amt).toFixed(2));
            $('#sef_total_adv_discount').val(parseFloat(sef_total_adv_discount).toFixed(2));
            $('#sef_curr_total').val(parseFloat(sef_curr_total).toFixed(2));
            $('#sef_curr_discount_total').val(parseFloat(sef_curr_discount_total).toFixed(2));
            $('#sef_prev_total').val(parseFloat(sef_prev_total).toFixed(2));
            $('#sef_total_prior_1992_amt').val(parseFloat(sef_total_prior_1992_amt).toFixed(2));
            $('#sef_total_prior_1991_amt').val(parseFloat(sef_total_prior_1991_amt).toFixed(2));
            $('#sef_curr_pen_total').val(parseFloat(sef_curr_pen_total).toFixed(2));
            $('#sef_prev_penalty_total').val(parseFloat(sef_prev_penalty_total).toFixed(2));
            $('#sef_total_prior_1992_penalties').val(parseFloat(sef_total_prior_1992_penalties).toFixed(2));
            $('#sef_total_prior_1991_penalties').val(parseFloat(sef_total_prior_1991_penalties).toFixed(2));
            $('#sef_prv_net').val(parseFloat(prv_gross).toFixed(2));
            $('#sef_total_basic_net').val(parseFloat(mnc_gross).toFixed(2));
            $('#gtotal_sef').val(parseFloat(gtotal).toFixed(2));
        });
    }

    $(document).on('click', '.submit_btn', function(e) {
        e.preventDefault();
        // basic
        var prv_adv_ammount = $('#prv_adv_ammount').val();
        var prv_adv_discount = $('#prv_adv_discount').val();
        var prv_crnt_ammount = $('#prv_crnt_ammount').val();
        var prv_crnt_discount = $('#prv_crnt_discount').val();
        var prv_prvious_ammount = $('#prv_prvious_ammount').val();
        var prv_prior_1992_amt = $('#prv_prior_1992_amt').val();
        var prv_prior_1991_amt = $('#prv_prior_1991_amt').val();
        var prv_pnalties_crnt = $('#prv_pnalties_crnt').val();
        var prv_pnalties_prvious = $('#prv_pnalties_prvious').val();
        var prv_prior_1992_penalties = $('#prv_prior_1992_penalties').val();
        var prv_prior_1991_penalties = $('#prv_prior_1991_penalties').val();

        var mnc_adv_ammount = $('#mnc_adv_ammount').val();
        var mnc_adv_discount = $('#mnc_adv_discount').val();
        var munshare_basic_current = $('#munshare_basic_current').val();
        var munshare_basic_discount = $('#munshare_basic_discount').val();
        var munshare_basic_previous = $('#munshare_basic_previous').val();
        var mnc_prior_1992_amt = $('#mnc_prior_1992_amt').val();
        var mnc_prior_1991_amt = $('#mnc_prior_1991_amt').val();
        var munshare_basic_penalty_current = $('#munshare_basic_penalty_current').val();
        var munshare_basic_penalty_previous = $('#munshare_basic_penalty_previous').val();
        var mnc_prior_1992_penalties = $('#mnc_prior_1992_penalties').val();
        var mnc_prior_1991_penalties = $('#mnc_prior_1991_penalties').val();

        var brgy_adv_ammount = $('#brgy_adv_ammount').val();
        var brgy_adv_discount = $('#brgy_adv_discount').val();
        var brgyshare_basic_current = $('#brgyshare_basic_current').val();
        var brgyshare_basic_discount = $('#brgyshare_basic_discount').val();
        var brgyshare_basic_previous = $('#brgyshare_basic_previous').val();
        var brgy_prior_1992_amt = $('#brgy_prior_1992_amt').val();
        var brgy_prior_1991_amt = $('#brgy_prior_1991_amt').val();
        var brgyshare_basic_penalty_current = $('#brgyshare_basic_penalty_current').val();
        var brgyshare_basic_penalty_previous = $('#brgyshare_basic_penalty_previous').val();
        var brgy_prior_1992_penalties = $('#brgy_prior_1992_penalties').val();
        var brgy_prior_1991_penalties = $('#brgy_prior_1991_penalties').val();

        // sef
        var sef_prv_adv_amt = $('#sef_prv_adv_amt').val();
        var sef_prv_adv_discount = $('#sef_prv_adv_discount').val();
        var sef_prv_amt = $('#sef_prv_amt').val();
        var sef_prv_discount = $('#sef_prv_discount').val();
        var sef_prev_prv_amt = $('#sef_prev_prv_amt').val();
        var sef_prv_prior_1992_amt = $('#sef_prv_prior_1992_amt').val();
        var sef_prv_prior_1991_amt = $('#sef_prv_prior_1991_amt').val();
        var sef_prv_penalty = $('#sef_prv_penalty').val();
        var sef_prev_prv_penalty = $('#sef_prev_prv_penalty').val();
        var sef_prv_prior_1992_penalties = $('#sef_prv_prior_1992_penalties').val();
        var sef_prv_prior_1991_penalties = $('#sef_prv_prior_1991_penalties').val();

        var sef_mnc_adv_amt = $('#sef_mnc_adv_amt').val();
        var sef_mnc_adv_discount = $('#sef_mnc_adv_discount').val();
        var sef_mncpl_crnt = $('#sef_mncpl_crnt').val();
        var sef_mncpl_dscnt = $('#sef_mncpl_dscnt').val();
        var sef_mncpl_prev = $('#sef_mncpl_prev').val();
        var sef_mnc_prior_1992_amt = $('#sef_mnc_prior_1992_amt').val();
        var sef_mnc_prior_1991_amt = $('#sef_mnc_prior_1991_amt').val();
        var sef_mncpl_pen_crnt = $('#sef_mncpl_pen_crnt').val();
        var sef_mncpl_pen_crnt_prev = $('#sef_mncpl_pen_crnt_prev').val();
        var sef_mnc_prior_1992_penalties = $('#sef_mnc_prior_1992_penalties').val();
        var sef_mnc_prior_1991_penalties = $('#sef_mnc_prior_1991_penalties').val();

        if(sef_prv_adv_amt == '' || sef_prv_adv_discount == '' || sef_prv_amt == '' || sef_prv_discount == '' || sef_prev_prv_amt == '' || sef_prv_prior_1992_amt == '' || sef_prv_prior_1991_amt == '' ||  sef_prv_penalty == '' || sef_prev_prv_penalty == '' || sef_prv_prior_1992_penalties == '' || sef_prv_prior_1991_penalties == '' || sef_mnc_adv_amt == '' || sef_mnc_adv_discount == '' || sef_mncpl_crnt == '' || sef_mncpl_dscnt == '' || sef_mncpl_prev == '' || sef_mnc_prior_1992_amt == '' || sef_mnc_prior_1991_amt == '' || sef_mncpl_pen_crnt == '' || sef_mncpl_pen_crnt_prev == '' || sef_mnc_prior_1992_penalties == '' || sef_mnc_prior_1991_penalties == '' || prv_adv_ammount == '' || prv_adv_discount == '' || prv_crnt_ammount == '' || prv_crnt_discount == '' || prv_prvious_ammount == '' || prv_prior_1992_amt == '' || prv_prior_1991_amt == '' || prv_pnalties_crnt == '' || prv_pnalties_prvious == '' || prv_prior_1992_penalties == '' || prv_prior_1991_penalties == '' || mnc_adv_ammount == '' || mnc_adv_discount == '' || munshare_basic_current == '' || munshare_basic_discount == '' || munshare_basic_previous == '' || mnc_prior_1992_amt == '' || mnc_prior_1991_amt == '' || munshare_basic_penalty_current == '' || munshare_basic_penalty_previous == '' || mnc_prior_1992_penalties == '' || mnc_prior_1991_penalties == '' || brgy_adv_ammount == '' || brgy_adv_discount == '' || brgyshare_basic_current == '' || brgyshare_basic_discount == '' || brgyshare_basic_previous == '' || brgy_prior_1992_amt == '' || brgy_prior_1991_amt == '' || brgyshare_basic_penalty_current == '' || brgyshare_basic_penalty_previous == '' || brgy_prior_1992_penalties == '' || brgy_prior_1991_penalties == '') {
            $('#report_content').append('<div class="alert alert-danger text-center">Please provide values to all input fields</div>');
        } else {
            $('#form').submit();
        }
    });
    function roundOff(number){
        return (Math.round(parseFloat(number).toFixed(4) * 100) / 100);
    }
    function unroundOff(num){
        var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (2 || -1) + '})?');
        return num.toString().match(re)[0];
    }
    $('.date').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide',
            dateFormat: 'mm/dd/yy'
        });

    
</script>
@endsection