@extends('nav')

@section('css')
<style>

</style>
@endsection

@section('content')
<div class="row">



<br /><br /><br />
<div class="col-sm-12">
<form method="POST" action="{{route('report.cashdiv_report_others')}}">
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
 <div class="form-group col-sm-4">
     <label for="year">CASH DIV TYPE</label>
     <select class="form-control" name="cash_div_type">
            <option value="OPAg">OPAg</option>
            <option value="PVET">PVET</option>
            <option value="COLD CHAIN">COLD CHAIN</option>
            <option value="CERTIFICATIONS OPP - DOJ">CERTIFICATIONS OPP - DOJ</option>
            <option value="PROVINCIAL HEALTH OFFICE">PROVINCIAL HEALTH OFFICE</option>
            <option value="RPT">RPT</option>

        </select>
 </div>

    <div class="form-group col-sm-12">


      <button type="submit" class="btn btn-primary" id="display" name="button_excel" id="confirm">EXPORT TO EXCEL</button>

      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">EXPORT TO PDF</button>
    </div>
    </form>
</div>



@endsection

@section('js')

@endsection