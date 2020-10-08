@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open(['method' => 'GET', 'route' => ['pdf.provincial_income']]) }}
        <div class="form-group col-sm-6">
            <label for="month">Month</label>
            <select class="form-control" name="month" id="month" required autofocus>
               
                @foreach ( $base['months'] as $mkey => $month)
                    @if ($month == date('m'))
                    <option value="{{ $mkey }}" selected>{{ $month }}</option>
                    @else
                    <option value="{{ $mkey }}">{{ $month }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-6">
            <label for="year">Year</label>
            <input type="number" class="form-control" name="year" value="{{ date('Y') }}" id="year" step="1" max="{{ date('Y') }}" required>
        </div>

        <div class="form-group col-sm-12">
          <button type="submit" class="btn btn-primary" name="button" id="confirm">EXPORT TO PDF</button>
          <button type="submit" class="btn btn-primary" name="button_excel" id="confirm">EXPORT TO EXCEL</button>
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