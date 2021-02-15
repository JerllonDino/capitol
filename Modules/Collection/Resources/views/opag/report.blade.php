@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}

<style>
  .btn-primary{
            background-color: #1d3750;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            background-image: none !important;
            font-family: OpenSansBold;
            
        }

        .btn-primary:hover{
            background-color: #2e5981 !important;
            background-image: none !important;
        }
</style>

@endsection

@section('content')
<div class="row">
    {{ Form::open(['method' => 'GET', 'route' => ['pdf.opag_report'], 'id' => 'form_print']) }}
        <div class="form-group col-sm-4">
            <label for="type">Type</label>
            <select name="account_type" id="account_type" class="form-control" required>
              <option></option>
              <option value="GF">General Fund</option>
              <option value="ColdChain">Cold Chain Project</option>
          </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="report_no">Report Number</label>
            <?php
              $report_no = date('Y');

              if(Session::has('report_no')){
                $report_no = Session::get('report_no');
              }
            ?>
              <input type="text" class="form-control" name="report_no" id="report_no" value="{{ $report_no }}" required>
        </div>
        <div class="form-group col-sm-4">
            <label for="report_no">Report Date</label>
            <input type="text" class="form-control date" name="report_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>

        <!-- report officer change -->
        <div class="form-group col-sm-6">
            <label>Report Officer Name</label>
            <select class="form-control" name="report_officer" id="report_officer_name">
              <option></option>
              <?php
                  foreach ($base['report_officers'] as $officer) { ?>
                    <option value="{{ $officer['officer_id']."_".$officer['position_id']."_".$officer['position'] }}">{{ $officer['officer_name'] }}</option>
              <?php   }
              ?>
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label>Report Officer Position</label>
            <input class="form-control" name="report_officer_pos" id="report_officer_pos" value="">
        </div>
        <!-- end report officer change -->

        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-primary pdf generate" name="button_pdf_type" value="type_A" id="confirm">EXPORT TO PDF AB</button>
            <button type="submit" class="btn btn-primary pdf generate" name="button_pdf_type"  value="type_C"  id="confirm">EXPORT TO PDF CD</button>
          <br /> <br />
           
        </div>

        <input type="hidden" name="paper_size" id="paper_size" value="letter"> <!-- default -->
        <input type="hidden" name="custom_height" id="custom_height">
        <input type="hidden" name="custom_width" id="custom_width">

        <input type="hidden" name="" id="change_button">
    {{ Form::close() }}
</div>
<div class="row">
<br />
<br />
</div>

@endsection

@section('js')
<script>
    $('.date').datepicker({
    changeMonth:true,
    changeYear:true,
    showAnim:'slide'
  });

    $.fn.setMonths = function(){
        var d = new Date();
        var n = d.getMonth();
        // console.log(n);
        var months = $('input[name="monthly_allowed_prvncial[]"]');
        months.each(function(index){
            if(index > n){
                $(this).attr('disabled',true);
            }else{
                 $(this).attr('checked',true);
            }
        });
    };

    $(document).on('change', '#report_officer_name', function() {
      var value = $(this).val();
      var split = value.split("_");
      $('#report_officer_pos_id').val(split[1]);
      $('#report_officer_pos').val(split[2]);
    });

$.fn.setMonths();

</script>
@endsection