@extends('nav')

@section('content')
<div class="row">
    {{ Form::open(['method' => 'PUT', 'route' => ['serialsg.update', $base['serial']->id]]) }}
    <div class="form-group col-sm-4">
        <label for="start">Start</label>
        <input type="number" class="form-control" name="start" id="start" step="1" value="{{ $base['serial']->serial_start }}" required autofocus>
    </div>

    
    <div class="form-group col-sm-4">
        <label for="end">End</label>
        <input type="number" class="form-control" name="end" id="end" step="1" value="{{ $base['serial']->serial_end }}" required>
    </div>

    <div class="form-group col-sm-3">
        <label for="acct_cat_id">Type</label>
        <select class="form-control f51_inputs" name="acct_cat_id" id="acct_cat_id" >
            @foreach($sg_type as $type)
                @if( $type->id == $serial->serial_type )
                    <option value="{{ $type->id }}" selected>{{ $type->sg_type }} </option>
                @else
                    <option value="{{ $type->id }}">{{ $type->sg_type }}  </option>
                @endif
            @endforeach;
        </select>
    </div>
    
   
    
    <div class="form-group col-sm-3">
        <label for="date">Date</label>
        <input type="text" class="form-control" name="date" id="date" value="{{ date('m/d/Y', strtotime($base['serial']->serial_date)) }}" required>
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
    
   
</script>
@endsection