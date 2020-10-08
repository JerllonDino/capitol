@extends('nav')

@section('css')
<style>

</style>
@endsection

@section('content')
<div class="row">



<br /><br /><br />
<form method="POST" action="{{route('report.amusements_genreport')}}">
<div class="col-sm-12">
{{ csrf_field() }}
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
      <button type="submit" class="btn btn-primary" id="display" name="button_excel" id="confirm">EXPORT TO EXCEL</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">EXPORT TO PDF</button>
    </div>
</form>


@endsection

@section('js')

@endsection