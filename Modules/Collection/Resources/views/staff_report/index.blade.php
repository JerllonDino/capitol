@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
    {{ Form::open([ 'method' => 'POST', 'route' => 'staff.encoders_report_view' ]) }}
    {{ csrf_field() }}
        <div class="form-group col-sm-3">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-3">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-primary" name="view_staff_report" id="confirm">VIEW</button>

           
        </div>
     <div class="form-group col-sm-12">
    </div>

    {{ Form::close() }}
</div>



@endsection

@section('js')
<script>
    $('.date').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });
</script>
@endsection
