@extends('nav')

@section('content')
<div class="row">
    {{ Form::open(['method' => 'PUT', 'route' => ['serial.update', $base['serial']->id]]) }}
    <div class="form-group col-sm-4">
        <label for="start">Start</label>
        <input type="number" class="form-control" name="start" id="start" step="1" value="{{ $base['serial']->serial_begin }}" required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="start">Current</label>
        <input type="number" class="form-control" name="current" id="current" min="0" max="{{ $base['serial']->serial_end }}" step="1" value="{{ $base['serial']->serial_current }}" required autofocus>
    </div>
    
    <div class="form-group col-sm-4">
        <label for="end">End</label>
        <input type="number" class="form-control" name="end" id="end" step="1" value="{{ $base['serial']->serial_end }}" required>
    </div>
    
    <div class="form-group col-sm-4">
        <label for="form">Form</label>
        <select class="form-control" name="form" id="form" required>
            @foreach($res as $result)
                @if ($base['serial']->acctble_form_id == $result->id)
                    <option value="{{ $result->id }}" selected>{{ $result->name }}</option>
                @else
                    <option value="{{ $result->id }}">{{ $result->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    
    @if ($base['serial']->municipality_id !== null)
    <div class="form-group col-sm-3">
        <label for="unit">Unit</label>
        <input type="text" class="form-control f51_inputs" name="unit" id="unit" value="" disabled>
    </div>
    
    <div class="form-group col-sm-3">
        <label for="acct_cat_id">Fund</label>
        <select class="form-control f51_inputs" name="acct_cat_id" id="acct_cat_id" disabled>
            <option selected disabled></option>
            @foreach ($base['acct_cat'] as $acct_cat)
                <option value="{{ $acct_cat->id }}">{{ $acct_cat->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group col-sm-3">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            @foreach($base['municipality'] as $mun)
                @if ($base['serial']->municipality_id == $mun->id)
                    <option value="{{$mun->id}}" selected>{{$mun->name}}</option>
                @else
                    <option value="{{$mun->id}}">{{$mun->name}}</option>
                @endif
            @endforeach
        </select>
    </div>
    @else
    <div class="form-group col-sm-3">
        <label for="unit">Unit</label>
        <input type="text" class="form-control f51_inputs" name="unit" id="unit" value="{{ $base['serial']->unit }}" required>
    </div>
    
    <div class="form-group col-sm-3">
        <label for="acct_cat_id">Fund</label>
        <select class="form-control f51_inputs" name="acct_cat_id" id="acct_cat_id">
            @foreach ($base['acct_cat'] as $acct_cat)
                @if ($base['serial']->acct_cat_id == $acct_cat->id)
                <option value="{{ $acct_cat->id }}" selected>{{ $acct_cat->name }}</option>
                @else
                <option value="{{ $acct_cat->id }}">{{ $acct_cat->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    
    <div class="form-group col-sm-3">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality" disabled>
            <option value="" selected disabled></option>
            @foreach($base['municipality'] as $mun)
            <option value="{{$mun->id}}">{{$mun->name}}</option>
            @endforeach
        </select>
    </div>
    @endif
    
    <div class="form-group col-sm-3">
        <label for="date">Date</label>
        <input type="text" class="form-control" name="date" id="date" value="{{ date('m/d/Y', strtotime($base['serial']->date_added)) }}" required>
    </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    {{ Form::close() }}
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $('#date').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide'
        });
    });
    
    $('#form').change( function() {
        if ($(this).val() == 2) {
            // form 56
            $('.f51_inputs').attr('disabled', true);
            $('.f51_inputs').attr('required', false);
            $('.f51_inputs').val('');
            
            $('#municipality').attr('disabled', false);
            $('#municipality').attr('required', true);
            $('#municipality').val('');
        } else {
            $('.f51_inputs').attr('disabled', false);
            $('.f51_inputs').attr('required', true);
            $('.f51_inputs').val('');
            
            $('#municipality').attr('disabled', true);
            $('#municipality').attr('required', false);
            $('#municipality').val('');
        }
    });
</script>
@endsection