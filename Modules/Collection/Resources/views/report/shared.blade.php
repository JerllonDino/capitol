@extends('nav')

@section('css')
<style>
.val {
    text-align: right;
}

.hidden {
    display: none;
}

    fieldset
    {
        border: 1px solid #7a8ada  !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }

 legend
{
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 0px;
    /* width: 35%; */
    border: 1px solid #a0b1e2;
    border-radius: 4px;
    padding: 5px 5px 5px 10px;
    background-color: #ffffff;
 }
.munc_name{
    font-size: 18px;
    text-transform: uppercase;
    text-decoration: underline;
}
 .brgy_name{
    padding-left: 20px;
 }

 .record_name1{
    padding-left: 25px;
 }

 .record_name2{
    padding-left: 25px;
 }
</style>
@endsection

@section('content')
<div class="row">



<br /><br /><br />
<form method="POST" action="{{ route('report.shared_pdf') }}" >
{{ csrf_field() }}
<div class="col-sm-12">
    <!-- <div class="form-group col-sm-4">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality" required autofocus>
            @foreach ($base['municipalities'] as $mun)
                <option value="{{ $mun->id }}">{{ $mun->name }}</option>
            @endforeach
        </select>
    </div> -->

    <div class="form-group col-sm-4">
        <label for="month">Month</label>
        <select class="form-control" name="month" id="month" required>
            @foreach ($base['months'] as $i => $month)
                @if ($i == date('m'))
                <option value="{{ $i }}" selected>{{ $month }}</option>
                @else
                <option value="{{ $i }}">{{ $month }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="year">Year</label>
        <input type="number" class="form-control" name="year" value="{{ date('Y') }}" id="year" step="1" max="{{ date('Y') }}" required>
    </div>
</div>

    <div class="form-group col-sm-12">
      <button type="button" class="btn btn-info" id="display" name="button" id="confirm">Display</button>
      <button type="submit" class="btn btn-primary"  name="button_pdf_x">EXPORT TO PDF-SHARED and BAC</button>

       <button type="submit" class="btn btn-primary" value="button_pdf_sef" name="button_pdf_sef">EXPORT TO PDF-SEF</button>
       <button type="submit" class="btn btn-primary" value="button_pdf_basic" name="button_pdf_basic">EXPORT TO PDF-BASIC</button>
    </div>
</form>
    <div class="form-group col-sm-12 hidden" id="result_bac">
        <table class="table col-sm-12 table-condensed table-hover">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th class="val" id="total_bac"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<hr />

<div class="row">
    <h4>  DAILY/WEEKLY </h4>
    {{ Form::open(['method' => 'POST', 'route' => ['pdf.accounts_report_share']]) }}
        <div class="form-group col-sm-12">
            <label for="report_no">Report Number</label>
            <input type="text" class="form-control" name="report_no" value="{{ date('Y') }}" required>
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
            <!-- <button type="submit" class="btn btn-success" name="button_pdf_shared_bac" id="confirm">Report on SHARED and BAC</button> -->
            <button type="submit" class="btn btn-success" name="button_pdf_shared_bac" id="confirm">EXPORT TO PDF-Report on SHARED and BAC</button>
            
        </div>
    {{ Form::close() }}
</div>

    <div class="form-group col-sm-12 hidden" id="result">
        <table class="table col-sm-12 table-condensed table-hover">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th class="val" id="total"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('.date').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });

    
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$('#display').on('click', function() {

var accounts_id = [];
$('.accounts_id').each(function() {
   if ($(this).is(":checked")) {
        accounts_id.push($(this).val());
   }
});

var accounts_sub_id = [];
$('.accounts_sub_id').each(function() {
   if ($(this).is(":checked")) {
        accounts_sub_id.push($(this).val());
   }
});
    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'action': 'get_shared_bac_report',
            'month': $('#month').val(),
            'year': $('#year').val(),
            'municipality': $('#municipality').val(),
            'accounts_id' : accounts_id,
            'accounts_sub_id' : accounts_sub_id,

        },
        success: function(response) {
            if (response == '') {
                $('#result').addClass('hidden');
                $('#result_bac').addClass('hidden');
                show_message('error', ['No record for query.']);
                return;
            }

            hide_messages();
            $('#result').removeClass('hidden');
            $('#result').find('tbody').html('');

            $('#result_bac').removeClass('hidden');
            $('#result_bac').find('tbody').html('');
            var total = 0;

            $.each(response, function(municipality_name, record) {
                $('#result').find('tbody')
                                    .append($('<tr>')
                                        .append($('<td>')
                                            .html('<strong class="munc_name">'+municipality_name+'</strong>')
                                        )
                                        .append($('<td>')
                                            .attr('class', 'val')
                                            .html(' ')
                                        )
                                    );
                  $.each(record, function(key, record) {
                            if (!record.name) {
                                // if( key != municipality_name){
                                            $('#result').find('tbody')
                                                .append($('<tr>')
                                                    .append($('<td>')
                                                        .html('<strong class="brgy_name">'+key+'</strong>')
                                                    )
                                                    .append($('<td>')
                                                        .attr('class', 'val')
                                                        .html(' ')
                                                    )
                                                );
                                            $.each(record, function(rec_key, rec_record) {
                                                var acct_value = numberWithCommas((parseFloat(rec_record.value)).toFixed(2));
                                                total += parseFloat(rec_record.value);
                                                $('#result').find('tbody')
                                                    .append($('<tr>')
                                                        .append($('<td>')
                                                            .html('<span class="record_name1">'+rec_record.name+'<span>')
                                                        )
                                                        .append($('<td>')
                                                            .attr('class', 'val')
                                                            .html(acct_value)
                                                        )
                                                    );
                                            });
                                 // }
                            }
                 });
            });
            $('#total').html(numberWithCommas(total.toFixed(2)));
            var total_bac = 0;
             $.each(response['Atok'], function(key, record) {
                        if (record.name) {
                                    var acct_value = numberWithCommas((parseFloat(record.value)).toFixed(2));
                                    total_bac += parseFloat(record.value);
                                            $('#result_bac').find('tbody')
                                                .append($('<tr>')
                                                    .append($('<td>')
                                                        .html('<span class="record_name2">'+record.name+'<span>')
                                                    )
                                                    .append($('<td>')
                                                        .attr('class', 'val')
                                                        .html(acct_value)
                                                    )
                                                );
                        }
             });

            $('#total_bac').html(numberWithCommas(total_bac.toFixed(2)));
        },
        error: function(response) {
            console.log(response.responseText);
        },
    });
});
</script>
@endsection