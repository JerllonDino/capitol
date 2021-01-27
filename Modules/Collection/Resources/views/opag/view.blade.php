@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
<style type="text/css">
    fieldset {
        border: 1px solid #da7a7a  !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }

    legend {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px;
        /*width: 35%;*/
        border: 1px solid #da7a7a ;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        background-color: #f7d2d2;
    }
    legend > strong {
        color:red;
    }
    .modal .modal-dialog .modal-content {
        height: 200px !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    @if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] )
    <div class="form-group col-sm-12">
        @if ($base['receipt']->af_type == 2)
            @if ($base['receipt']->is_cancelled == 1)
            <a href="#" class="btn btn-info" disabled>Form 56 Detail</a>
            @else
            <a href="{{ route('field_land_tax.f56_detail_form', ['id' =>$base['receipt']->id]) }}" class="btn btn-info">Form 56 Detail</a>
            @endif
        @endif

        {{-- @if ($base['receipt']->is_printed == 1)
            <button type="button" class="btn btn-warning pull-right" id="cancel_btn">Cancel Receipt</button>
        @else
            <button type="button" class="btn btn-warning pull-right" id="cancel_btn" disabled>Cancel Receipt</button>
        @endif

        @if (isset($base['cert']))

            <button type="button" class="btn btn-info" id="" data-toggle="modal" data-target="#paper_size_opt">Print</button>
        @else
            <a class="btn btn-info disabled" href="#">Print Certificate</a>
        @endif --}}
    </div>
    @endif

<div class="modal" id="paper_size_opt">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <span><button type="button" class="close" data-dismiss="modal">&times;</button></span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{ route('pdf.cert', ['receipt' => $base['receipt'], 'prov_gov' => 'gov', 'ppr_size' => 'ppr_size']) }}" id="print_form">
                    <div class="form-group">
                        <label>Choose Paper Size</label>
                        <select class="form-control" name="ppr_size" id="ppr_size">
                            <option value="letter" selected>Letter (8.5x11")</option>
                            <option value="a4">A4</option>
                            <option value="legal">Legal (8.5x13")</option>
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm pull-right" type="submit" id="print_btn">Print</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="form-group col-sm-12">
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
            <dt>Remarks</dt>
            <!--{{-- <dd>{{ $base['receipt']->bank_remark }}</dd> --}}-->
            <dd>
                @if(strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['receipt']->remarks))) != 0)
                    <?php echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks).'<br>' : ''; ?>
                    <?php echo $base['receipt']->bank_remark != '' ? strip_tags($base['receipt']->bank_remark).'<br>' : ''; ?>
                    <?php 
                        if(isset($base['cert'])) {
                            if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->details))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->details))) != 0) {
                                echo $base['cert']->details != '' ? strip_tags($base['cert']->details).'<br>' : '';
                            }
                        }
                    ?> 
                @else
                    <?php echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks).'<br>' : ''; ?>
                    <?php 
                        if(isset($base['cert'])) {
                            if(strcasecmp(trim(strip_tags($base['cert']->details)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(trim(strip_tags($base['cert']->details)), trim(strip_tags($base['receipt']->bank_remark))) != 0) {
                                echo $base['cert']->details != '' ? $base['cert']->details.'<br>' : ''; 
                            }
                        }
                    ?>
                @endif
            </dd>
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
<hr>
<div class="row">
<div class="form-group col-sm-12">
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
    <tfoot>
        <tr>
            <td><b>TOTAL VALUE</b></td>
            <td align="right"><b>{{ number_format($base['total'], 2) }}</b></td>
        </tr>
    </tfoot>
</table>
</div>
</div>

<!--{{-- <div class="form-group col-sm-12">
    <label for="remarks">Receipt Remarks</label>
    @if(!is_null($base['receipt']->remarks))
        <textarea id="remarks" class="form-control" name="remarks">{{ $base['receipt']->remarks }}</textarea>
    @else
        <textarea id="remarks" class="form-control" name="remarks"></textarea>
    @endif
</div> --}}-->

<div class="modal fade" id="check">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i>&times;</i></button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <i class="fa fa-info-circle fa-4x"></i>
                <p>Either of the fields for PTR Number or Reference Number should be filled in</p>
            </div>
        </div>
    </div>
</div>
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

$('#cert_type').change( function() {
    show_addtl_inputs(this.value);
});

$(document).ready(function() {
    var val = $('#cert_type').val();
    show_addtl_inputs(val);
});
    
function show_addtl_inputs(type) {
    var group = '';
    var div = '';
    if (type == 1) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').removeClass('hidden');
        div = '#provincial_permit';
        group = '.provincial_inputs';
    } else if (type == 2) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').addClass('hidden');
        div = '#transfer_tax';
        group = '.transfer_inputs';
    } else if (type == 3) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').addClass('hidden');
        div = '#sand_gravel';
        group = '.sand_inputs';
    } else if(type == 4) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').removeClass('hidden');
        div = '#provincial_permit';
    } else {
        $('.addtl_div').addClass('hidden');
        $('fieldset').addClass('hidden');
    }

    $('.addtl_div').addClass('hidden');
    $('.addtl_inputs').prop('required', false);
    $(group).prop('required', true);
    $('#provincial_note').prop('required', false);
    $(div).removeClass('hidden');
}

tinymce.init({
    selector: '#detail',
    toolbar: [
                'undo redo | styleselect | bold underline italic  | link image alignleft aligncenter alignright fontsizeselect',
              ],
    fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px",
});

tinymce.init({
    selector: '#transfer_notary_public',
    toolbar: [
                'undo redo | styleselect | bold underline italic  | link image alignleft aligncenter alignright fontsizeselect',
              ],
    fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px",
});

tinymce.init({
    selector: '#remarks',
})

$.fn.datepickerx = function(){
        $('.datepicker').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide'
        });
    };
$.fn.datepickerx();

$(document).on('change', '#prov_gov', function() {
    var str = $('#print_btn').attr('href');
    var str2 = str.replace('gov', $(this).val());
    $('#print_btn').attr('href', str2);
});

$(document).on('click', '#confirm', function(e) {
    if($('#cert_type').val() == 2) {
        e.preventDefault();
        var ptr = $('#transfer_ptr_number').val();
        var ref = $('#transfer_ref_num').val();

        if (ptr == "" && ref == "") {
            $('#check').modal('toggle');
        } else {
            $('#cert_submit').submit();
        }
    }
});

$(document).on('click', '#print_btn', function(e) {
    e.preventDefault();
    var route = $('#print_form').attr('action');
    var route2 = route.replace('ppr_size', $('#ppr_size').val());
    $('#print_form').attr('action', route2);
    $('#print_form').submit();
});
</script>
@endsection
