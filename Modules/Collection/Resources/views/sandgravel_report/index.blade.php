@extends('nav')

@section('css')
<style>

</style>
@endsection

@section('content')
<div class="row">



<br /><br /><br />
<div class="col-sm-12">
    @if (session('isEmpty'))
        <div class="alert alert-danger">Sorry, there are no entries yet. :)</div>
    @endif
    
<form method="POST" action="{{route('report.sandgravel_report_municpality_generate')}}">
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
      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">EXPORT TO PDF-Source of Aggregates</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_excel" id="confirm">EXPORT TO EXCEL-Source of Aggregates</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxsharing" id="confirm">EXPORT TO PDF-Tax & Penalties Sharing</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxsharing_excel" id="confirm">EXPORT TO EXCEL-Tax & Penalties Sharing</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxcollected_clienttype" id="confirm">EXPORT TO PDF-Tax &Penalties Collection by client type</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxcollected_clienttype_excel" id="confirm">EXPORT TO EXCEL-Tax &Penalties Collection by client type</button>

      
      {{-- <button type="submit" class="btn btn-primary" id="display" name="button_taxcollected" id="confirm">Tax &Penalties Collection by client type</button> --}}
    </div>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" id="display" name="button_delivery_reciept" id="confirm">Delivery Reciept</button>
    </div>
</form>


@endsection

@section('js')

@endsection