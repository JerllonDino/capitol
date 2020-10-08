@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <h4>ALL ACCOUNTS REPORT</h4>
    {{ Form::open(['method' => 'POST', 'route' => ['pdf.accounts_report']]) }}
        <div class="form-group col-sm-12">
            <label for="report_no">Report Number</label>
            <input type="text" class="form-control" name="report_no" value="{{ date('Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-primary" name="button_pdf" id="confirm">EXPORT TO PDF</button>
            <button type="submit" class="btn btn-success" name="button_pdf_province" id="confirm">EXPORT TO PDF-Provincial Income</button>
            <!-- <button type="submit" class="btn btn-primary" name="button_excel" id="confirm">Generate Report EXCEL</button> -->


        </div>

        <!-- <h4>Accounts Shares PDF</h4> -->
        <div class="form-group col-sm-12">
            <!-- <button type="submit" class="btn btn-success" name="button_pdf_shared_bac" id="confirm">Report on SHARED and BAC DAILY/WEEKLY</button> -->
            <!-- <button type="submit" class="btn btn-success" name="button_pdf_province" id="confirm">Generate Report Provincial Income</button> -->

          <!--   <button type="submit" class="btn btn-success" name="button_pdf_municapal" id="confirm">Generate Report Municipal Income</button>

            <button type="submit" class="btn btn-success" name="button_pdf_brgy" id="confirm">Generate Report Barangay Income</button> -->

        </div>
    {{ Form::close() }}
</div>

<hr />
<hr />

<!-- <div class="row">
    <h4>PER-ACCOUNT REPORT</h4>
    {{ Form::open(['method' => 'POST', 'route' => ['pdf.per_accounts_report']]) }}
    <div class="form-group col-sm-6">
            <label for="report_no">ACCOUNT</label>
            <select name="account" class="form-control">
                @foreach($base['titles'] as $keyt => $title)
                    <option value="{{ $title->id }}">{{ $title->name }} ( {{ $title->group->category->name }} ) </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="report_no">Report Number</label>
            <input type="text" class="form-control" name="report_no" value="{{ date('Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-info" name="button_pdf_professional_tax" id="confirm">View</button>

        </div>
    {{ Form::close() }}
</div> -->


<hr />

<div class="row">
    <h4>PER-ACCOUNT REPORT </h4>
    {{ Form::open(['method' => 'POST', 'route' => ['pdf.per_accounts_report2'], 'target' => '_blank'] ) }}
    <div class="form-group col-sm-6">
            <label for="report_no">ACCOUNT</label>
            <select name="account" class="form-control" id="account">
                <option></option>
                @foreach($base['titles'] as $keyt => $title)
                    <option value="{{ $title->id }}">{{ $title->name }} ( {{ $title->group->category->name }} ) </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="report_no">Report Number</label>
            <input type="text" class="form-control" name="report_no" value="{{ date('Y') }}" required>
        </div>
        <div class="form-group col-sm-6" id="subtitleDiv">
            <label for="subtitle">Subtitles</label>
            <select class="form-control" name="subtitle" id="subtitle"></select>
        </div>

        <div class="form-group col-sm-6 hidden" id="showSharing">
            <label for="subtitle">Show Sharing for</label>
            <select class="form-control" name="sharing" id="sharing"></select>
        </div>

        <div class="col-sm-6 col-sm-offset-6">
            &nbsp;
        </div>
        <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="{{ date('m/d/Y') }}" required>
        </div>
        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-info" name="button_pdf_professional_tax" id="confirm">EXPORT TO PDF</button>
            <button type="submit" class="btn btn-info" name="button_html_professional_tax" id="confirm">View html</button>

        </div>
    {{ Form::close() }}
</div>


<hr />


<hr />

<!-- <div class="row">
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
      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">View</button>
    </div>
</form>
 </div> -->
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

    $('#subtitleDiv').hide();

    $(document).on('change','#account',function(){ // here
        $.fn.getSubs();
        $.fn.checkSharing();
    });

    $.fn.checkSharing = function(){
        var account = $('#account').val();
        var subtitle = $('#subtitle').val();
        if(account){
            $.ajax({
              type: 'POST',
              url: '{{route("report.checkSharing")}}',
              data: {
                account : account,
                subtitle : subtitle,
                _token : '{{csrf_token()}}',
              },
              dataType: "json",
              success: function(data) {
                // console.log($('#showSharing').hasClass('hidden'));
                if(data.is_shared == 1){
                    $('#sharing').empty();
                    $('#showSharing').removeClass('hidden');
                     $('#sharing').append('<option value="value"">Total</option>');
                    if(data.sharepct_barangay > 0){
                        $('#sharing').append('<option value="share_barangay"">Barangay Share ('+data.sharepct_barangay+'%)</option>');
                    }
                    if(data.sharepct_municipal > 0){
                        $('#sharing').append('<option value="share_municipal"">Municipal Share ('+data.sharepct_municipal+'%)</option>');
                    }
                    if(data.sharepct_provincial > 0){
                        $('#sharing').append('<option value="share_provincial"">Provincial Share ('+data.sharepct_provincial+'%)</option>');
                    }
                }else{
                    $('#showSharing').addClass('hidden');
                }
              },
              error: function(){
                  // console.log('error sharing');
                  // $('#subtitleDiv').hide();
                  $('#showSharing').addClass('hidden');
              },
          });
        }
    };

    $.fn.getSubs = function(){

        var account = $('#account').val();
        if(account){
              $('#subtitle').html('');
            $.ajax({
              type: 'POST',
              url: '{{route("report.accounts_report_getsubs")}}',
              data: {
                account : account,
                _token : '{{csrf_token()}}',
              },
              dataType: "json",
              error: function(){
                  console.log('error getSubs');
                  // $('#subtitleDiv').hide();
              },
              success: function(data) {
                if(data.length !== 0){
                    $('#subtitle').append('<option></option>');
                    $('#subtitle').append('<option value="all">All</option>');
                    $.each(data,function(index, value){
                        //console.log(value);
                        $('#subtitle').append('<option value='+value.id+'>'+value.name+'</option>');
                    });
                    $('#subtitleDiv').show();
                }else{
                    $('#subtitleDiv').hide();
                }

              }
          });
        }

    };
    $.fn.getSubs();
     $.fn.checkSharing();
</script>
@endsection