@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')

<hr />


<div class="row">
    <h4>ACCOUNTABLE FORMS MONTHLY</h4>
<form method="POST" action="{{route('report.montly_accountable_forms')}}">
<div class="col-sm-12">
{{ csrf_field() }}
 <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">EXPORT TO PDF</button>
    </div>

 </div>
 </form>
@endsection

@section('js')
<script>
    // $('#account').select2();
    // $('#subtitle').select2();

    $('.date').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });
</script>
@endsection