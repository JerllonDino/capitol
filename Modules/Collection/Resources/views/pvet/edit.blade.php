@extends('nav')

@section('css')
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

.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }

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

</style>
@endsection

@section('content')
@if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] )
<div class="row">
    {{ Form::open(['method' => 'PATCH', 'route' => ['opag.update', $base['receipt']->id], 'id'=>'store_form']) }}
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['user']->realname }}</dd>
            <dt>Form</dt>
            <dd>{{ $base['receipt']->form->name }}
                <?php
                    if( $base['receipt']->form->name == 'Form 56'){
                        echo '<input type="hidden" name="form_type" id="form" value="2" /> ';
                    }else{
                         echo '<input type="hidden" name="form_type" id="form" value="1" /> ';
                    }
                ?>
            </dd>
            <dt>Serial Number</dt>
            <dd>{{ $base['receipt']->serial_no }}</dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
        <input type="hidden" class="form-control" name="serial_id" id="serial_id" value="{{ $base['receipt']->serial->id }}">
    </div>

    <div class="form-group col-sm-4">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y  H:i:s', strtotime($base['receipt']->date_of_entry)) }}" required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="date">Report Date</label>
        <input type="text" class="form-control datepicker" name="report_date" value="{{ date('m/d/Y', strtotime($base['receipt']->report_date)) }}" required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            @if ($base['receipt']->col_municipality_id == '')
            <option selected disabled></option>
            @else
            <option disabled></option>
            @endif

            @foreach($base['municipalities'] as $municipality)
                @if ($base['receipt']->col_municipality_id == $municipality['id'])
                <option value="{{ $municipality['id'] }}" selected>{{ $municipality['name'] }}</option>
                @else
                <option value="{{ $municipality['id'] }}">{{ $municipality['name'] }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-4">
        <label for="barangay">Barangay</label>
        @if (!empty($base['barangays']))
        <select class="form-control" name="brgy" id="brgy">
        @else
        <select class="form-control" name="brgy" id="brgy" disabled>
        @endif

            @if (!empty($base['barangays']))
                @foreach ($base['barangays'] as $brgy)
                    @if ($base['receipt']->col_barangay_id == $brgy['id'])
                    <option value="{{ $brgy['id'] }}" selected>{{ $brgy['name'] }}</option>
                    @else
                    <option value="{{ $brgy['id'] }}">{{ $brgy['name'] }}</option>
                    @endif
                @endforeach
            @endif

        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer"  value="{{ $base['receipt']->customer->name }}">
        <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="{{ $base['receipt']->customer->id }}">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
        <!-- <select class="form-control" name="customer_type" id="new_customer_type"> -->
        <small id="client_type_msg" style="color: red;"></small>
        <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            @foreach($base['sandgravel_types'] as $sandgravel_types)
                @if($base['receipt']->client_type ===  $sandgravel_types['id'] )
                    <option value="{{ $sandgravel_types['id'] }}" selected>{{ $sandgravel_types['description'] }}</option>
                @else
                    <option value="{{ $sandgravel_types['id'] }}">{{ $sandgravel_types['description'] }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" required="">
            @if( $base['receipt']->sex == 'male' )
                <option value="female">Female</option>
                <option value="male" selected="">Male</option>
            @elseif($base['receipt']->sex == 'female')
                <option value="female" selected="">Female</option>
                <option value="male">Male</option>
            @else
                <option selected="" ></option>
                <option value="female" >Female</option>
                <option value="male">Male</option>
            @endif
        </select>
    </div>



    <div class="form-group col-sm-4">
        <label for="user">Transaction Type</label>
        <select class="form-control" id="transaction_type" name="transaction_type">
            @foreach ($base['transaction_type'] as $transaction_type)
                @if ($base['receipt']->transaction_type == $transaction_type->id)
                <option value="{{ $transaction_type->id }}" selected>{{ $transaction_type->name }}</option>
                @else
                <option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_name">Bank Name</label>
        @if ($base['receipt']->bank_name == '')
            <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" disabled>
        @else
            <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="{{ $base['receipt']->bank_name }}">
        @endif
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_number">Number</label>
        @if ($base['receipt']->bank_number == '')
            <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" disabled>
        @else
            <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="{{ $base['receipt']->bank_number }}">
        @endif
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_date">Date</label>
        @if ($base['receipt']->bank_date == '')
            <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" disabled>
        @else
            <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="{{ date('m/d/Y', strtotime($base['receipt']->bank_date)) }}">
        @endif

    </div>
<?php
    $booklet = 'hidden';
    // $booklet = '';
    // if(($base['receipt']->RcptCertificate)){
    //     if($base['receipt']->RcptCertificate->col_rcpt_certificate_type_id != 3)
    //         $booklet = 'hidden'; 
    //     else
    //         $booklet = '';
    // }
?>
    <div class="form-group col-sm-8">
        <label for="bank_remark">Remarks</label>
        <!--{{-- @if ($base['receipt']->bank_remark == '')
            <textarea class="form-control bank_input" name="bank_remark" id="bank_remark" value="" disabled></textarea>
        @else
            <textarea class="form-control bank_input" name="bank_remark" id="bank_remark" value="">{{ $base['receipt']->bank_remark }}</textarea>
        @endif --}}-->
        <textarea class="form-control bank_input" name="bank_remark" id="bank_remark">
            @if(strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['receipt']->remarks))) != 0)
                <?php //echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks)."\n" : ''; ?>
                <?php //echo $base['receipt']->bank_remark != '' ? strip_tags($base['receipt']->bank_remark)."\n" : ''; ?>
                <?php 
                    // if(isset($base['cert'])) {
                    //     if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->detail))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->detail))) != 0) {
                    //         echo $base['cert']->detail != '' ? strip_tags($base['cert']->detail)."\n" : '';
                    //     }
                    // }
                ?> 

                @if($base['receipt']->remarks != '')
                    {!! strip_tags($base['receipt']->remarks) !!} <br>
                @endif

                @if($base['receipt']->bank_remark != '')
                    {!! strip_tags($base['receipt']->bank_remark) !!} <br>
                @endif

                @if(isset($base['cert']))
                    @if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->detail))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->detail))) != 0)
                        @if($base['cert']->detail != '')
                            {!! strip_tags($base['cert']->detail )!!}
                        @endif
                    @endif
                @endif
            @else
                <?php //echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks)."\n" : ''; ?>
                <?php 
                    // if(isset($base['cert'])) {
                    //     if(strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->bank_remark))) != 0) {
                    //         echo $base['cert']->detail != '' ? $base['cert']->detail."\n" : ''; 
                    //     }
                    // }
                ?>

                @if($base['receipt']->remarks != '')
                    {!! strip_tags($base['receipt']->remarks) !!} <br>
                @endif

                @if(isset($base['cert']))
                    @if(strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->bank_remark))) != 0)
                        @if($base['cert']->detail != '')
                            {!! strip_tags($base['cert']->detail) !!}
                        @endif
                    @endif
                @endif
            @endif    
        </textarea>
    </div>

    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th colspan="2">Account</th>
                    <th class="td_nature">Nature</th>
                    <th>Amount</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" data-transactionType="3" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td><input type="text" id="total" class="form-control" readonly></td>
                    <td></td>
                </tr>
            </tfoot>
            <tbody>
                @if(count($base['receipt']->items) > 0)
                @foreach($base['receipt']->items as $i => $item)
                <tr>
                    <td>
                        @if ($item->acct_title != null)

                        {{-- <input type="text" class="form-control account" value="{{ $item->acct_title->name .' ('. $item->acct_title->group->category->name .')' }}" required> --}}
                        <select name="account_list" id="account_list" class="form-control" required>
                            <option value="10" {{ $item->acct_title->id == 5 ? 'selected' : '' }}>Sales on Veterinary Products</option>
                            <option data-title="title" value="61" {{ $item->acct_title->id == 61 ? 'selected' : '' }}>Supervision and Regulation, Enforcement Fees (Quarantine Fees)</option>
                        </select>
                        <input type="hidden" class="form-control" name="account_id[]" value="{{ $item->acct_title->id }}">
                        <input type="hidden" class="form-control" name="account_type[]" value="title">
                            @if (isset($item->acct_title->rate))
                                <input type="hidden" class="form-control account_is_shared" value="{{ $item->acct_title->rate->is_shared }}" name="account_is_shared[]">
                            @else
                                <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                            @endif
                        @else
                        <?php
                            if($item->acct_subtitle){
                                if( $item->acct_subtitle->id == 4){
                                    $booklet = '';
                                    echo '<input type="hidden" name="bookletx" value="true" /> ';
                                }
                        ?>
                        {{-- <input type="text" class="form-control account" value="{{ $item->acct_subtitle->name .' ('. $item->acct_subtitle->title->group->category->name .')' }}" required> --}}
                        <select name="account_list" id="account_list" class="form-control" required>
                            <option value="10" {{ $item->acct_subtitle->id == 5 ? 'selected' : '' }}>Sales on Veterinary Products</option>
                            <option data-title="title" value="61" {{ $item->acct_subtitle->id == 61 ? 'selected' : '' }}>Supervision and Regulation, Enforcement Fees (Quarantine Fees)</option>
                        </select>
                        <input type="hidden" class="form-control" name="account_id[]" value="{{ $item->acct_subtitle->id }}">
                        <?php 
                            }else{
                        ?>
                        <input type="text" class="form-control account" value="" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="">
                        <?php
                        } ?>

                        <input type="hidden" class="form-control" name="account_type[]" value="subtitle">
                            @if (isset($item->acct_title->rate))
                                <input type="hidden" class="form-control account_is_shared" value="{{ $item->acct_title->rate->is_shared }}" name="account_is_shared[]">
                            @else
                                <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                            @endif

                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                        @if(isset($item->detail))
                            <input type="hidden" class="form-control account_rate" name="account_rate[]" value="{{ $item->detail->col_collection_rate_id }}">
                        @else
                            <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                        @endif
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" value="{{ $item->nature }}" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]"  step="0.01" value="{{ $item->value }}" required>
                    </td>
                    <td>
                    @if ($i > 0)
                        <button class="btn btn-warning btn-sm rem_row" type="button">
                        <i class="fa fa-minus"></i>
                        </button>
                    @endif
                    </td>
                </tr>
                @endforeach
                @else

                <tr>
                    <td>
                        <!-- <input type="text" class="form-control account" required disabled="disabled"> -->
                        <input type="text" class="form-control account" required>
                        <input type="hidden" class="form-control" name="account_id[]">
                        <input type="hidden" class="form-control" name="account_type[]">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                    </td>
                    <td>
                        <!-- <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button> -->
                        <button type="button" class="btn btn-sm btn-info account_addtl">Select</button>
                        <input type="hidden" class="form-control">
                        <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]"  step="0.01" required>
                    </td>
                    <td></td>
                </tr>

                @endif
            </tbody>
        </table>
    </div>

    <!--{{-- <div class="form-group col-sm-12">
        <label for="remarks">Receipt Remarks</label>
        @if(!is_null($base['receipt']->remarks))
            <textarea id="remarks" class="form-control" name="remarks">{{ $base['receipt']->remarks }}</textarea>
        @else
            <textarea id="remarks" class="form-control" name="remarks"></textarea>
        @endif
    </div> --}}-->

    <div class="col-md-12 {{ $booklet }}" id="sg_booklets">
        <table class="table table-bordered center" id="booklets_sg">
            <thead>
                <tr>
                    <th class="text-center">BOOKLET START</th>
                    <th class="text-center">BOOKLET END</th>
                    <th class="text-center"><button id="add_booklet_row" class="btn btn-sm btn-info"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
                @if(count($base['receipt']->sgbooklet) == 0)
                <tr>
                    <td><input type="number" class="form-control booklet_start" name="booklet_start[]"></td>
                    <td><input type="number" class="form-control booklet_end" name="booklet_end[]"></td>
                    <td></td>
                </tr>
                @else
                    @foreach($base['receipt']->sgbooklet as $booklet)
                        <tr>
                            <td>
                                <input type="hidden" name="booklet_id[]" value="{{ $booklet->id }}">
                                <input type="number" class="form-control booklet_start" value="{{ $booklet->booklet_start }}" name="booklet_start[]"></td>
                                <td><input type="number" class="form-control booklet_end" value="{{ $booklet->booklet_end }}" name="booklet_end[]"></td>
                                <td><a href="{{ route('delete.rcpt_booklet', $booklet->id) }}" class="btn btn-danger pull-right">Delete</a></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>


    @if ($base['receipt']->is_cancelled == 0)
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success btnf51" name="button" id="confirm">Update Receipt</button>
    </div>
    @endif

        <input type="hidden" id="form" name="form" value="{{ $base['receipt']->serial->formtype->id }}" />
    {{ Form::close() }}
</div>

<div id="account_panel">
</div>

@endif
@endsection

@section('js')
<script type="text/javascript">
    var collection_type = 'show_in_fieldlandtax';

    tinymce.init({
        selector: '#detail',
        toolbar: [
                    'undo redo | styleselect | bold underline italic  | link image alignleft aligncenter alignright fontsizeselect',
                  ],
        fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px",
        setup: function(detail) {
            detail.on('keyup', function() {
                var data = $(detail.getContent()).text();
                var data_xtags = data.replace(/<[a-z]*\/?>/gi, "");
                $('#bank_remark').val('');
                $('#bank_remark').val(data);
            });
        }
    });

    tinymce.init({
        selector: '#transfer_notary_public',
        toolbar: [
                    'undo redo | styleselect | bold underline italic  | link image alignleft aligncenter alignright fontsizeselect',
                  ],
        fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px",
    });

    // tinymce.init({
    //     selector: '#remarks',
    // });

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

$.fn.datepickerx = function(){
        $('.datepicker').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide'
        });
    };
$.fn.datepickerx();

$(document).on('keyup', '#bank_remark', function() {
    tinymce.get('detail').setContent('');
    tinymce.get('detail').setContent($(this).val());
});

</script>
    @include('collection::shared.transactions_js')
@endsection
