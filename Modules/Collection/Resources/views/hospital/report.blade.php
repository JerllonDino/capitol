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
    {{ Form::open(['method' => 'GET', 'route' => ['pdf.hospital_report'], 'id' => 'form_print']) }}
        <div class="form-group col-sm-4 d-none">
            <label for="type">Type</label>
            <select class="form-control" name="type" required autofocus>
                <option selected disabled></option>
                @foreach ($base['categories'] as $category)
                  <!-- exclude BTS, effective 2020 --> 
                  {{-- @if($category->id != 2) --}}
                    <option value="{{ $category->id }}" {{ $category->id == 1 ? 'selected' : '' }} >{{ $category->name }}</option>
                  {{-- @endif --}}
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="report_no">Report Number</label>
            <?php
              $report_no = date('Y');

              if(Session::has('report_no')){
                $report_no = Session::get('report_no');
              }
            ?>
            <input type="text" class="form-control" name="report_no" value="{{ $report_no }}" required>
        </div>
        <div class="form-group col-sm-6">
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
            <button type="submit" class="btn btn-primary pdf generate" name="button_pdf_type" value="type_A" id="confirm">EXPORT TO PDF A</button>
            <button type="submit" class="btn btn-primary pdf generate" name="button_pdf_type"  value="type_B"  id="confirm">EXPORT TO PDF B</button>
            <button type="submit" class="btn btn-primary pdf generate" name="button_pdf_type"  value="type_C"  id="confirm">EXPORT TO PDF CD</button>
          <br /> <br />
           
        </div>

        <input type="hidden" name="paper_size" id="paper_size" value=""> <!-- default -->
        <input type="hidden" name="custom_height" id="custom_height">
        <input type="hidden" name="custom_width" id="custom_width">

        <input type="hidden" name="" id="change_button">
    {{ Form::close() }}
</div>
<div class="row">
<br />
<br />

@if( \Session::get('user')->position == 'Administratorx' )
<h4>ALLOWED MONTHS FOR PROVINCIAL INCOME</h4>
    {{ Form::open(['method' => 'POST' , 'route' => ['allow_mnths.collections_deposits']]) }}

    <div class="form-group">
            <label>YEAR</label>
            <input type="number" class="form-control" name="monthly_allowed_prvncial_year" value="{{date('Y')}}" readonly>
    </div>
<div class="form-group col-sm-6 col-sm-offset-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="1">
                      Januarry
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="2">
                      February
                    </label>
                  </div>

                  <div class="checkbox" >
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="3">
                      March
                    </label>
                  </div>
                  <div class="checkbox" >
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="4">
                      April
                    </label>
                  </div>

                  <div class="checkbox" >
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="5">
                      May
                    </label>
                  </div>

                  <div class="checkbox" >
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="6">
                      June
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="7">
                      July
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="8">
                      August
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox"  name="monthly_allowed_prvncial[]" value="9">
                      September
                    </label>
                  </div>

                  <div class="checkbox" >
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="10">
                      October
                    </label>
                  </div>

                  <div class="checkbox" >
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="11">
                      November
                    </label>
                  </div>

                  <div class="checkbox" >
                    <label>
                      <input type="checkbox" name="monthly_allowed_prvncial[]" value="12">
                      December
                    </label>
                  </div>
                </div>
<div class="form-group col-sm-12">
            <button type="submit" class="btn btn-info" name="button" id="confirm">Allow Months</button>
        </div>
    {{ Form::close() }}
@endif
</div>

<div class="modal" id="set_ppr_size">
  <div class="modal-dialog" role="dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group"> 
          <label>Choose paper size</label>
          <select name="choose_ppr" id="choose_ppr" class="form-control">
            <option value="legal">Legal (8.5" x 14")</option>
            <option value="A3">A3</option>
            <option value="custom">Custom...</option>
          </select>
          <br>
          <div style="display: none;" id="custom_ppr">
            <div class="col-sm-6">
              <label>Height (in inches)</label>
              <input type="number" name="ppr_height" id="ppr_height" step="0.01" class="form-control">
            </div>
            <div class="col-sm-6">
              <label>Width (in inches)</label>
              <input type="number" name="ppr_width" id="ppr_width" step="0.01" class="form-control">
            </div>
          </div>
        </div>
        <br>
        <div class="modal-footer">
          <button type="button" id="print_report" class="btn btn-success">Print</button>
        </div>
      </div>
    </div>
  </div>
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

    $(document).on('click', '.pdf', function(e) {
      e.preventDefault();
      $('#set_ppr_size').modal('show');
    });

    $('#print_report').click(function() {
      if($('#choose_ppr').val() != "custom") {
        $('#paper_size').val($('#choose_ppr').val());
        $('#form_print').submit();
      } else {
        if($('#ppr_height').val() > 0 && $('#ppr_height').val() != "") {
          $('#custom_height').val($('#ppr_height').val());
        }

        if($('#ppr_width').val() > 0 && $('#ppr_width').val() != "") {
          $('#custom_width').val($('#ppr_width').val());
        }

        $('#ppr_width').css('box-shadow', 'none');
        $('#ppr_height').css('box-shadow', 'none');
        $('#ppr_width').siblings().remove();
        $('#ppr_height').siblings().remove();
        if($('#ppr_width').val() > 0 && $('#ppr_width').val() != "" && $('#ppr_height').val() > 0 && $('#ppr_height').val() != "") {
          $('#form_print').submit();
        } else {
          if($('#ppr_width').val() <= 0 || $('#ppr_width').val() == "") {
            $('#ppr_width').css('box-shadow', '1px 1px 10px red');
            $('#ppr_width').parent().append('<small style="color: red;">Please specify width in inches</small>');
          }

          if($('#ppr_height').val() <= 0 || $('#ppr_height').val() == "") {
            $('#ppr_height').css('box-shadow', '1px 1px 10px red');
            $('#ppr_height').parent().append('<small style="color: red;">Please specify height in inches</small>');
          }
        }
      }
    });

    $(document).on('change', '#choose_ppr', function() {
      if($(this).val() == "custom") {
        $('#custom_ppr').css('display', 'block');
        $('#ppr_height').prop('required', true);
        $('#ppr_width').prop('required', true);
      } else {
        $('#custom_ppr').css('display', 'none');
        $('#ppr_height').prop('required', false);
        $('#ppr_width').prop('required', false);
      }
    });

    $(document).on('click', '.generate', function() {
      // console.log($(this).prop('name'));
      $('#change_button').prop('name', $(this).prop('name'));
      if($(this).prop('name') == 'button_pdf_type') {
        $('#change_button').val($(this).val());
      }
    });
$.fn.setMonths();

</script>
@endsection