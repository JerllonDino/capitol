@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
<style>
    #year {
        background:white !important;
    }
    .ui-datepicker-calendar,.ui-datepicker-month {
        display: none;
    }​
</style>
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => ['holiday_settings.update', $base['year'], $base['month'] ]]) }}
    
    <div class="form-group col-sm-6">
        <label for="Code">Year</label>
        <input type="number" class="form-control year_month" step="1" name="year" id="year" value="{{ date('Y') }}" required readonly>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="Code">Month</label>
        <select class="form-control year_month" name="month" id="month" disabled="disabled">
        @foreach($base['months'] as $i => $month)
            @if ($i+1 == date('n'))
            <option value="{{ $i+1 }}" selected>{{ $month }}</option>
            @else
            <option value="{{ $i+1 }}">{{ $month }}</option>
            @endif
        @endforeach
        </select>
    </div>
    
    <div class="col-sm-12">
        <table class="table" id="days">
            <thead>
                <tr>
                    <th>Weekday</th>
                    <th>Holiday?</th>
                </tr>
            </thead>
            <tbody>
            @for($x=1; $x<=$base['current_month_days']; $x++)
                @if ((date('N', strtotime(date('Y') .'-'. date('m') .'-'. $x)) >= 6) != 1)
                <tr>
                    <td>
                        {{ date('F d, Y', strtotime(date('Y') .'-'. date('m') .'-'. $x)) }}
                    </td>
                    <td>
                        <div class="checkbox">
                            @if (in_array($x, $base['days']))
                            <label><input type="checkbox" value="{{ $x }}" name="holiday_date[]" checked>Holiday</label>
                            @else
                            <label><input type="checkbox" value="{{ $x }}" name="holiday_date[]">Holiday</label>
                            @endif
                        </div>
                    </td>
                </tr>
                @endif
            @endfor
            </tbody>
        </table>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="Update">
    </div>
    {{ Form::close() }}
</div>
@endsection

@section('js')
<script>
    $('.year_month').change( function() {
        updatedays();
    });
    
    $('#year').keyup( function() {
        updatedays();
    });
    
    function updatedays() {
        var days = daysInMonth($('#month').val(), $('#year').val());
        $('#days tbody').html('');
        for (var i = 1; i < days; i++) {
            var date = new Date($('#year').val() +'-'+ $('#month').val() +'-'+ i);
            var day = date.getDay();
            if (day == 6 || day == 0) {
                continue;
            }
            
            var month = date.toLocaleString('en-us', {month: 'long'});
            
            $('#days tbody').append(
            '<tr>' +
                '<td>' +
                    month +' '+ date.getDate() +', '+ date.getFullYear() +
                '</td>' +
                '<td>' +
                    '<div class="checkbox">' +
                        '<label><input type="checkbox" value="'+ i +'" name="holiday_date[]">Holiday</label>' +
                    '</div>' +
                '</td>' +
            '</tr>'
            );
        }
    }
    
    function daysInMonth(month,year) {
        return new Date(year, month, 0).getDate();
    }
</script>
@endsection