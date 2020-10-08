@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
{{ Html::style('/base/sweetalert/sweetalert2.min.css') }}
<style>
    .td_amt {
        width: 150px;
    }
    .td_nature {
        width: 450px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
    }

     #sg_booklets{
        background: burlywood;
    }

    .btn-green{
        color : #fff;
        background-image: linear-gradient(to bottom,#73e641 0,#4a9c18 100%);
    }

    .btn-gray{
        color : #fff;
        background-image: linear-gradient(to bottom,#959294 0,#625e61 100%);
    }


    .btn-pink{
        background-image: linear-gradient(to bottom,#f66adc 0,#b11faa 100%);
    }

    .btn-gray{
        color : #fff;
        background-image: linear-gradient(to bottom,#959294 0,#625e61 100%);
    }

    .btn-green{
        color : #fff;
        background-image: linear-gradient(to bottom,#73e641 0,#4a9c18 100%);
    }

    .btn-red{
        color : #fff;
        background-image: linear-gradient(to bottom,#ff0009 0,#9e1523 100%);
    }

    .btn-another{
        color:#fff;
        background-image: linear-gradient(to bottom,#229568 0,#0b470e 100%);
    }

    .btn-another-none{
        color:#fff;
        background-image: linear-gradient(to bottom,#5a755d 0,#435744 100%);
    }

    #sg_booklets{
        background: burlywood;
    }

    th.border_all{ vertical-align: middle !important; }


.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }

</style>
@endsection

@section('content')
@if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] )
<div class="row">
        <div class="form-group col-sm-12">
            <a class="btn btn-info hidden" href="{{route('pdf.land_tax_collection',['sign',$base['receipt']->id])}}">Print w/ signatories</a>
            <a class="btn btn-info hidden" href="{{route('pdf.land_tax_collection',['nsign',$base['receipt']->id])}}">Print w/o Signatories</a>

            @if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] )
                    <a href="{{ route('form56.edit','') }}/{{ $base['receipt']->id }}" class="btn  btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>
            @endif
        @if ($base['receipt']->is_printed == 1 && $base['receipt']->is_cancelled == 0)
            <button type="button" class="btn btn-warning pull-right" id="cancel_btn">Cancel Receipt</button>
        @endif
        </div>

         @include('collection::form56/settings/form_print')

         
            <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['receipt']->user->realname }}</dd>
            <dt>AF Type</dt>
            <dd>{{ $base['receipt']->form->name }}</dd>
            <dt>Serial Number</dt>
            <dd>{{ $base['receipt']->serial_no }}</dd>
            <dt>Payor/Customer</dt>
            <dd>{{ $base['receipt']->customer->name }}</dd>
            <dt>Municipality</dt>
            <dd>
            @if (!empty($base['receipt']->municipality->name))
            {{ $base['receipt']->municipality->name }}
            @endif
            </dd>
            <dt>Barangay</dt>
            <dd>
            @if (!empty($base['receipt']->barangay->name))
            {{ $base['receipt']->barangay->name }}
            @endif
            </dd>
            <dt>Date</dt>
            <dd>{{ date('m/d/Y', strtotime($base['receipt']->date_of_entry)) }}</dd>
            <dt>Transaction Type</dt>
            <dd>{{ $base['receipt']->transactiontype->name }}</dd>
            <dt>Bank Name</dt>
            <dd>{{ $base['receipt']->bank_name }}</dd>
            <dt>Number</dt>
            <dd>{{ $base['receipt']->bank_number }}</dd>
            <dt>Date</dt>
            <dd>{{ $base['receipt']->bank_date }}</dd>
            <dt>Tax Type</dt>
            <dd>
                <?php
                    $tax_types = ['Number', 'RPT Billing', 'New Owner', 'New Owner w/ Back Taxes', 'Newly Decared w/ back taxes', 'Collected by MTO', 'Collected by PTO'];
                ?>
                {{ isset($base['receipt_tdarp']->previous_tax_type_id) ? $tax_types[$base['receipt_tdarp']->previous_tax_type_id] : '' }}
            </dd>
            <dt>Remark</dt>
            <dd>{{ isset($base['receipt']) ? $base['receipt']->bank_remark : '' }}</dd>
            <dt>Status</dt>
            <dd>
            @if ($base['receipt']->is_cancelled == 1)
                Cancelled
                <p>{{ $base['receipt']->cancelled_remark }}</p>
            @else
                Issued
            @endif
            </dd>
        </dl>



    <div class="form-group col-sm-12">
            <div id="mncpal_brgy_code_error"  ></div>


    <hr />
        <table class="table" id="tablex">
            <thead>
                <tr>
                    <th>Declared Owner </th>
                    <th>TD/ARP No. </th>
                    <th>BARANGAY</th>
                    <th>Classification</th>
                    <th>Assessment Value</th>
                    <th>Period Covered</th>
                    <th>Full/Partial</th>
                    <th>Current Year Gross Amt.</th>
                    <th>Discount</th>
                    <th>Previous Year/s</th>
                    <th>Penalty for Current Year</th>
                    <th>Penalty for Previous Year/s</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $base['receipt']->F56Detailmny as $key => $F56Detail )

                <?php


                    switch ($F56Detail->full_partial) {
                        case 0:
                             $full_partial = 'FULL PAYMENT';
                            break;
                         case 1:
                             $full_partial = 'Partial - 1st Quarter';
                            break;
                         case 2:
                             $full_partial = 'Partial - 2nd Quarter';
                            break;
                         case 3:
                             $full_partial = 'Partial - 3rd Quarter';
                            break;
                         case 4:
                             $full_partial = 'Partial - 4th Quarter';
                            break;
                         case 5:
                             $full_partial = 'Partial Advance';
                            break;
                         case 6:
                             $full_partial = 'Balance Settlement';
                            break;
                         case 7:
                             $full_partial = 'Backtax';
                            break;
                         case 8:
                             $full_partial = 'Additional Payment';
                            break;
                        default:
                             $full_partial = '';
                            break;
                    }
                ?>

                        <tr>
                            <td>{{ $F56Detail->owner_name }}</td>
                            <td>{{ $F56Detail->TDARPX->tdarpno }}</td>
                            <td>{{ isset($F56Detail->TDARPX->barangay_name) ? $F56Detail->TDARPX->barangay_name->name : '' }}</td>
                            <td>{{ ($F56Detail->F56Type->name) }}</td>
                            <td>{{ ($F56Detail->tdrp_assedvalue) }}</td>
                            <td>{{ ($F56Detail->period_covered) }}</td>
                            <td>
                                {{ $full_partial }}
                                @if(!is_null($F56Detail->ref_num))
                                    <br>(Referred OR: {{ $F56Detail->ref_num }})
                                @endif
                            </td>
                            <td>{{ ($F56Detail->basic_current) }}</td>
                            <td>{{ ($F56Detail->basic_discount) }}</td>
                            <td>{{ ($F56Detail->basic_previous) }}</td>
                            <td>{{ ($F56Detail->basic_penalty_current) }}</td>
                            <td>{{ ($F56Detail->basic_penalty_previous) }}</td>
                        </tr>
                @endforeach
            </tbody>
        </table>

    <hr />

   <table class="table">
    <thead>
        <tr>
            <th>Nature</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base['receipt']->items as $item)
        <tr>
            <td>{{ $item->nature }}</td>
            <td align="right">{{ number_format($item->value, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr />

<h3>BASIC AND SEF COMPUTATION</h3>
<div class="table-responsive">
   <table class="table table-bordered table-condensed">
    <thead>
         <tr>
            <th class="border_all" rowspan="3" style="width: 200px;">TD/ARP</th>
            <th class="border_all" colspan="7">Basic Tax</th>
            <th class="border_all" colspan="7">SEF</th>
            <th class="border_all" rowspan="3">Grand Total (gross)</th>
            <th class="border_all" rowspan="3">Grand Total (net)</th>
        </tr>
        <tr>
            <th class="border_all" rowspan="2">Current Year Gross Amt.</th>
            <th class="border_all" rowspan="2">Discount</th>
            <th class="border_all" rowspan="2">Previous Years</th>
            <th class="border_all" colspan="2">Penalties</th>
            <th class="border_all" rowspan="2">Sub Total Gross Collections</th>
            <th class="border_all" rowspan="2">Sub Total Net Collections</th>
            <th class="border_all" rowspan="2">Current Year Gross Amt.</th>
            <th class="border_all" rowspan="2">Discount</th>
            <th class="border_all" rowspan="2">Previous Years</th>
            <th class="border_all" colspan="2">Penalties</th>
            <th class="border_all" rowspan="2">Sub Total Gross Collections</th>
            <th class="border_all" rowspan="2">Sub Total Net Collections</th>
        </tr>
        <tr>
            <th class="border_all">Current Year</th>
            <th class="border_all">Previous Years</th>
            <th class="border_all">Current Year</th>
            <th class="border_all">Previous Years</th>
        </tr>
    </thead>
    <tbody>
         <?php
            $total_basic_current = 0;
            $total_basic_discount = 0;
            $total_basic_previous = 0;
            $total_basic_penalty_current = 0;
            $total_basic_penalty_previous = 0;
            $total_basic_gross = 0;
            $total_basic_net = 0;
            $gt_gross = 0;
            $gt_net = 0;
      ?>
         @foreach( $base['receipt']->F56Detailmny as $key => $f56_detail )

         <?php

                            $basic_gross = $f56_detail->basic_current + $f56_detail->basic_previous + $f56_detail->basic_penalty_current + $f56_detail->basic_penalty_previous;
                            $basic_net = $basic_gross - $f56_detail->basic_discount;
                            $total_basic_current += $f56_detail->basic_current;
                            $total_basic_discount += $f56_detail->basic_discount;
                            $total_basic_previous += $f56_detail->basic_previous;
                            $total_basic_penalty_current += $f56_detail->basic_penalty_current;
                            $total_basic_penalty_previous += $f56_detail->basic_penalty_previous;
                            $total_basic_gross += $basic_gross;
                            $total_basic_net += $basic_net;
                            $gt_gross += ($basic_gross + $basic_gross);
                            $gt_net += ($basic_net + $basic_net);
                ?>

            <tr>
                <td>{{$f56_detail->TDARPX->tdarpno}}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                <td class="border_all val">{{ number_format(($basic_gross + $basic_gross), 2) }}</td>
                <td class="border_all val">{{ number_format(($basic_net + $basic_net), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
    </div>


    <br/>

    <div class="form-group col-sm-12">

    </div>
</div>
<hr />


<div id="account_panel">
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
         <div id="tdrp_tax_dec" ></div>
     </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="cancel_panel">
    {{ Form::open(['method' => 'POST', 'route' => ['field_land_tax.cancel', $base['receipt']->id]]) }}
    <div class="form-group col-sm-12">
        <label for="bank_remark">Remark</label>
        <textarea class="form-control" name="cancel_remark" required></textarea>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" id="go">Go</button>
    </div>
    {{ Form::close() }}
</div>
@endif

@endsection

@section('js')
<script type="text/javascript">
$('#cancel_panel').dialog({
    autoOpen: false,
    draggable:false,
    modal: true,
    resizable: false,
    title: 'Cancel',
    width: 600,
});

$(document).on('click', '#cancel_btn', function() {
    $('#cancel_panel').dialog('open');
});


function zeroPad(num, places) {
    if(!isNaN(num)){
         var zero = places - num.toString().length + 1;
         return Array(+(zero > 0 && zero)).join("0") + num;
    }
}

$.fn.tdarpno = function(){
    var mncpal_brgy_code_error = $('#mncpal_brgy_code_error');

    $('.tdarpno').on('keyup',function(){
         mncpal_brgy_code_error.html('');
        mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
        var el = $(this);
        var el_index = $('.tdarpno').index(this);
        var el_val = el.val().split('-');
        var mncplty = el_val[1];
        var brgy = el_val[2];
            brgy = zeroPad(brgy,3);
        var barangay_code = $('option:selected','.tdrp_barangay').eq(el_index).attr('data-code');
            barangay_code = zeroPad(barangay_code,3);
        var municipal_code = $('option:selected','#municipality').attr('data-code');
        var error = '';
        // console.log(brgy+'----'+ barangay_code+'----'+municipal_code+'===='+el_index);
        if(el_val[0] === '2010'){
             if( el_val.length >1 && mncplty != ''&& mncplty !== municipal_code){
             error =' <strong> Error ! Wrong Municipality Code</strong>';
            }
              if(el_val.length >2 && brgy != '' && brgy !== barangay_code){
                error =  error +  ' <strong> Error ! Wrong Barangay Code</strong>';
            }
            if(error!=''){
                mncpal_brgy_code_error.html(error);
                mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
            }
        }
    });

    $('.tdrp_barangay').change(function(){
         mncpal_brgy_code_error.html('');
        mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');

        var el_index = $('.tdrp_barangay').index(this);
        var el = $('.tdarpno').eq(el_index);
        var el_val = el.val().split('-');
        var mncplty = el_val[1];
        var brgy = el_val[2];
            brgy = zeroPad(brgy,3);
        var barangay_code = $('option:selected','.tdrp_barangay').eq(el_index).attr('data-code');
            barangay_code = zeroPad(barangay_code,3);
        var municipal_code = $('option:selected','#municipality').attr('data-code');
        var error = '';
        // console.log(brgy+'----'+ barangay_code+'----'+municipal_code+'===='+el_index);
        if(el_val[0] === '2010'){
             if( el_val.length >1 && mncplty != ''&& mncplty !== municipal_code){
             error =' <strong> Error ! Wrong Municipality Code</strong>';
            }
              if(el_val.length >2 && brgy != '' && brgy !== barangay_code){
                error =  error +  ' <strong> Error ! Wrong Barangay Code</strong>';
            }
            if(error!=''){
                mncpal_brgy_code_error.html(error);
                mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
            }
        }
    });


};
$.fn.tdarpno();


$.fn.changeAmountTotal = function(){
    $('.basic_current').change(function(){
            $.fn.computeAmountTotal();
    });
};

$.fn.computeAmountTotal = function(){
    var total = 0;
            $('.basic_current').each(function() {
               total += parseFloat($(this).val());
            });
            $('.amounts').eq(0).val(total.toFixed(2));
};

$.fn.changeAmountTotal();
$('#add_row_form56').click( function() {
    var xxx = $('.tdrp_barangay').html();

    var add_tdarp = '<tr>'+
                        '<td><input type="text" class="form-control tdarpno" name="tdarpno[]" required></td>'+
                        '<td>'+
                             '<select class="form-control tdrp_barangay" name="tdrp_barangay[]" id="tdrp_barangay[]"  >'+
                                 xxx +
                             '</select>'+
                        '</td>'+
                        '<td>'+
                            '<select class="form-control f56_type" id="f56_type" name="f56_type[]" required>'+
                                '<option selected ></option>'+
                                @foreach ($base['f56_types'] as $type)
                                    '<option value="{{ $type->id }}">{{ $type->name }}</option>'+
                                @endforeach
                            '</select>'+
                        '</td>'+
                        '<td><input type="text" class="form-control period_covered" name="period_covered[]" value="{{date('Y')}}" required></td>'+
                        '<td>'+
                            '<select class="form-control full_partial" id="full_partial[]" name="full_partial[]" required>'+
                                '<option value="0" selected >Full</option>'+
                                '<option value="1" >Partial - 1st Quarter</option>'+
                                '<option value="2" >Partial - 2nd Quarter</option>'+
                                '<option value="3" >Partial - 3rd Quarter</option>'+
                                '<option value="4" >Partial - 4th Quarter</option>'+
                            '</select>'+
                        '</td>'+
                        '<td><input type="number" class="form-control basic_current" name="basic_current[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control basic_discount" name="basic_discount[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control basic_previous" name="basic_previous[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control basic_penalty_current" name="basic_penalty_current[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control basic_penalty_previous" name="basic_penalty_previous[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><button type="button" class="btn btn-warning btn-sm rem_row" ><i class="fa fa-minus"></i></button></td>'+
                    '</tr>';


    $('#tablex').find('tbody').append(add_tdarp);
    $.fn.tdarpno();
    $.fn.bms_getTDRP();
    $.fn.changeAmountTotal();


});



$.fn.bms_showTDRPclear = function(){
    $('#tdrp_tax_dec').html('');
};

$.fn.bms_showTDRP = function(){
    $.ajax({
            url: 'http://localhost/capitol_rpt/public/api/bms_get_tax_dec_info',
            type: 'POST',
            data:{
              'tax_dec' : $('#tax_dec_no_bms').val()
            },
            dataType: 'html',
            success: (data) => {
                $('#tdrp_tax_dec').html(data);

                $('#myModal').modal('show');

            }
        });
};

$.fn_index_of_max_3 = function(el_index_x,el_index_y,el_index_z){
    if (el_index_x > el_index_y) {
        if (el_index_x >  el_index_z) return  el_index_x;
        else        return el_index_z ;
    } else {
        if (el_index_y >  el_index_z) return el_index_y ;
        else        return el_index_z ;
    }
}

$.fn.bms_getTDRP = function(){
$('.tdarpno,.period_covered,.full_partial').change(function(){
    var el = $(this);
    var el_index_x = $('.tdarpno').index(this);
    var el_index_y = $('.period_covered').index(this);
    var el_index_z = $('.full_partial').index(this);

    var el_index = $.fn_index_of_max_3(el_index_x,el_index_y,el_index_z);
    console.log(el_index_x+'===='+el_index_y+'===='+el_index_z+'===='+el_index);
    $.ajax({
        url: 'http://localhost/capitol_rpt/public/api/bms_get_tax_dec_details',
        type: 'POST',
        data:{
          'tax_dec' : $('.tdarpno').eq(el_index).val(),
          'period' : $('.period_covered').eq(el_index).val(),
          'full_partial' : $('.full_partial').eq(el_index).val(),
        },
        dataType: 'json',
        success: (data) => {
            if(data[0] == 'TAX DEC FOUND'){
                 $('.f56_type').eq(el_index).find('option').each(function(i){
                    if($(this).text() == data[1].f56_type){
                        $(this).prop('selected', true);
                    }
                });

                 $('.basic_current').eq(el_index).val(data[1].assessed_value_gross);
                 $('.basic_discount').eq(el_index).val(data[1].discounted);
                 $('.basic_penalty_current').eq(el_index).val(data[1].penalty);


                 $.fn.computeAmountTotal();

            }



        }
    });

});
};
$.fn.bms_getTDRP();

$.fn.loadTable = function(){
  if ( $.fn.DataTable.isDataTable('#seriallist') ) {
     $('#seriallist').DataTable().destroy();
    }
    $('#seriallist').dataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: { 'url' : '{{ route("collection.datatables", "form56") }}',
                'data' : {'show_year' : $('#show_year').val() }
            },
        columns: [
            { data: 'realname', name: 'realname' },
            { data: 'mun_name', name: 'mun_name' },
            { data: 'brgy_name', name: 'brgy_name',
                    render: function(data) {
                    if(data)
                        return data;
                    else
                        return 'multiple barangay';
                },
            },
            { data: 'serial_no', name: 'serial_no' },
           { data: 'date_of_entry',name : 'date_of_entry',
                  render: function(data) {
                      var d = moment(data);
                    return d.format('MMMM DD, YYYY HH:mm:ss');
                },
                bSortable: true,
                searchable : true,
            },
            { data: 'name', name: 'name' },
            { data:
                function(data) {
                    var status = '';
                    if (data.is_cancelled == 1) {
                        status = 'Cancelled';
                    } else if (data.is_printed == 1) {
                        status = 'Issued';
                    }
                    return status;
                }
            },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    var cert = '';
                    var another = '';
                     var restore = '';

                    @if ( Session::get('permission')['col_field_land_tax'] & $base['can_read'] )
                    view = '<a href="{{ route('field_land_tax.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif

                    @if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] )
                    write = (data.is_cancelled == 0) ? '<a href="{{ route('field_land_tax.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>' : '';
                    @endif

                     if(data.process_status == '0'){

                        }else if(data.process_status == '1'){
                             cert = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-green datatable-btn" title="'+data.cert_type+' Certificate"><i class="fa fa-certificate"></i></a>' : '';
                        }else if(data.col_rcpt_certificate_type_id != null){
                             cert = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-green datatable-btn" title="'+data.cert_typex+' Certificate"><i class="fa fa-certificate"></i></a>' : '';
                        }else{
                            cert = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-gray datatable-btn" title="Certificate"><i class="fa fa-certificate"></i></a>' : '';
                        }

                     @if ( Session::get('permission')['col_receipt'] & $base['can_write'] )
                       if(data.is_printed == 1){
                            if(data.col_receipt_serial_parent == null)
                                another = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/another?types=field" class="btn btn-sm btn-another-none datatable-btn" title="ANOTHER RECEIPT"><i class="fa fa-plus"></i></a>' : '';
                            else
                                if(data.col_receipt_serial_parent == data.serial_no ){
                                another = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/another?types=field" class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'"><i class="fa fa-plus"></i></a>' : '';
                            }else{
                                another = '<button class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'">  '+data.col_receipt_serial_parent+'</button>';
                            }
                       }


                    @endif

                    @if(session::get('user')->position == 'Administrator')
                        //  if (data.is_cancelled == 1) {
                        //     restore = '<a href="{{ route('receipt.index') }}/'+data.id+'/restore" class="btn btn-sm btn-warning datatable-btn" title="Restore : '+data.serial_no+'"><i class="fa fa-undo"></i></a>';
                        // }
                         if (data.is_cancelled == 1) {
                            restore = '<button class="btn btn-sm btn-warning datatable-btn" title="Restore : '+data.serial_no+'" onclick="$(this).restore(\''+data.id+'\');" ><i class="fa fa-undo"></i></a>';
                        }
                    @endif

                    return cert + view + write + another+restore;

                },
                bSortable: false,
                searchable: false,
            },
        ],
        order : [[ 3, "desc" ]]
    });
 };
$.fn.loadTable();

    var collection_type = 'show_in_fieldlandtax';

     $.fn.restore = function(id){

        swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#a22314',
              cancelButtonColor: '#c9bebe',
              confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
              if (result.value) {
                    $.ajax({
                        url: '{{ route("receipt.restore") }}',
                        type: 'POST',
                        data:{
                          receipt: id,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Restored!',
                      text: 'RECEIPT RESTORED',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        $.fn.loadTable();
                      }
                    })
              }
            });

    };
</script>
@include('collection::shared.transactions_js')
@endsection
