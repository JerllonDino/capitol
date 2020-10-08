@extends('nav')

@section('content')

{{ Form::open(['method' => 'POST', 'route' => ['receipt.f56_detail_submit', 'id' => $base['id']]]) }}
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Date</dt>
            <dd>{{ date('F d, Y', strtotime($base['receipt']->date_of_entry)) }}</dd>
            <dt>Payor</dt>
            <dd>{{ $base['receipt']->customer->name }}</dd>
            <dt>OR No.</dt>
            <dd>{{ $base['receipt']->serial_no }}</dd>
            <dt>Brgy.</dt>
            <dd>
            @if (!empty($base['receipt']->barangay))
                {{ $base['receipt']->barangay->name}}
            @endif
            </dd>
        </dl>
    </div>
</div>

@if (!isset($base['detail']))
<div class="row">
    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>TD/ARP No.</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control" name="tdarpno[]" required>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="period_covered">Period Covered</label>
        <input type="text" class="form-control" name="period_covered" value="" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="f56_type">Classification</label>
        <select class="form-control" id="f56_type" name="f56_type" required>
            <option selected disabled></option>
            @foreach ($base['f56_types'] as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<br>
<div class="row">
    <div class="form-group col-sm-4">
        <label for="basic_current">Current Year Gross Amt.</label>
        <input type="number" class="form-control" name="basic_current" value="0" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-4">
        <label for="basic_discount">Discount</label>
        <input type="number" class="form-control" name="basic_discount" value="0" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-4">
        <label for="basic_previous">Previous Year/s</label>
        <input type="number" class="form-control" name="basic_previous" value="0" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="basic_penalty_current">Penalty for Current Year</label>
        <input type="number" class="form-control" name="basic_penalty_current" value="0" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="basic_penalty_previous">Penalty for Previous Year/s</label>
        <input type="number" class="form-control" name="basic_penalty_previous" value="0" min="0" step="0.01" required>
    </div>
</div>
<br>
<div class="row">
    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-success" value="Submit">
    </div>
</div>
@else
<div class="row">
    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>TD/ARP No.</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base['detail']->TDARP as $tan)
                <tr>
                    <td>
                        <input type="text" class="form-control" name="tdarpno[]" value="{{ $tan->tdarpno }}" required>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm rem_row" type="button"><i class="fa fa-minus"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="period_covered">Period Covered</label>
        <input type="text" class="form-control" name="period_covered" value="{{ $base['detail']->period_covered }}" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="f56_type">Classification</label>
        <select class="form-control" id="f56_type" name="f56_type" required>
            @foreach ($base['f56_types'] as $type)
                @if ($base['detail']->col_f56_type_id == $type->id)
                <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                @else
                <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
<br>
<div class="row">
    <div class="form-group col-sm-4">
        <label for="basic_current">Current Year Gross Amt.</label>
        <input type="number" class="form-control" name="basic_current" value="{{ $base['detail']->basic_current }}" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-4">
        <label for="basic_discount">Discount</label>
        <input type="number" class="form-control" name="basic_discount" value="{{ $base['detail']->basic_discount }}" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-4">
        <label for="basic_previous">Previous Year/s</label>
        <input type="number" class="form-control" name="basic_previous" value="{{ $base['detail']->basic_previous }}" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="basic_penalty_current">Penalty for Current Year</label>
        <input type="number" class="form-control" name="basic_penalty_current" value="{{ $base['detail']->basic_penalty_current }}" min="0" step="0.01" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="basic_penalty_previous">Penalty for Previous Year/s</label>
        <input type="number" class="form-control" name="basic_penalty_previous" value="{{ $base['detail']->basic_penalty_previous }}" min="0" step="0.01" required>
    </div>
</div>
<br>
<div class="row">
    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-success" value="Submit">
    </div>
</div>
@endif
{{ Form::close() }}

@endsection

@section('js')
<script>
$('#add_row').click( function() {
    $('#table').find('tbody')
        .append($('<tr>')
            .append($('<td>')
                .append($('<input>')
                    .attr('type', 'text')
                    .attr('class', 'form-control')
                    .attr('required', 'true')
                    .attr('name', 'tdarpno[]')
                )
            )
            .append($('<td>')
                .append($('<button>')
                    .attr('type', 'button')
                    .attr('class', 'btn btn-warning btn-sm rem_row')
                    .append($('<i>')
                        .attr('class', 'fa fa-minus')
                    )
                )
            )
        )
});

$(document).on('click', '.rem_row', function() {
    $(this).parent().parent().remove();
    compute_total();
    check_shared();
});
</script>
@endsection