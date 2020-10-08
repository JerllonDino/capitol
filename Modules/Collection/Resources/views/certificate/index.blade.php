@extends('nav')

@section('css')
<style>
    .hidden {
        display: none;
    }
    fieldset
    {
        border: 1px solid #da7a7a  !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }

    legend
    {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px;
        /*width: 35%;*/
        border: 1px solid #da7a7a ;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        background-color: #f7d2d2;
    }
    legend>strong{
        color:red;
    }
    .modal .modal-dialog .modal-content {
        height: 200px !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        @if (isset($base['cert']))
            <!-- <a class="btn btn-info" id="print_btn" href="{{-- route('pdf.cert', ['receipt' => $base['receipt'], 'prov_gov' => 'gov']) --}}">Print</a> -->
            <button type="button" class="btn btn-info" id="" data-toggle="modal" data-target="#paper_size_opt">Print</button>
        @else
            <a class="btn btn-info disabled" href="#">Print</a>
        @endif

        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>
                @if(isset($base['is_mncpal_cert']))
                    {{ $base['user']->realname }}
                @else
                    {{ $base['recipt_info']->user->realname }}
                @endif
            </dd>
            <dt>AF Type</dt>
            <dd>
                @if(isset($base['is_mncpal_cert']))
                    N/A
                @else
                    {{ $base['recipt_info']->serial->formtype->name }}
                @endif
            </dd>
            <dt>Serial Number</dt>
            <dd>
                @if(isset($base['is_mncpal_cert']))
                    {{ $base['recipt_info']->rcpt_no }}
                @else
                    {{ $base['recipt_info']->serial_no }}
                @endif
            </dd>
            <dt>Client Type</dt>
            <dd>
                @if(isset($base['is_mncpal_cert']))
                    @if(!is_null($base['ctype']))
                        {{ $base['ctype']->description }}
                    @endif
                @else
                    {{ $base['recipt_info']->client_type_desc->description ?? '' }}
                @endif
            </dd>

            <dt>Municipality</dt>
            <dd>
                @if(isset($base['is_mncpal_cert']))
                    @if(!is_null($base['recipt_info']->col_municipality_id))
                        {{ !is_null($base['rcpt_mnc']) ? $base['rcpt_mnc']->name : '' }}
                    @endif
                @else
                    @if (!empty($base['recipt_info']->municipality->name))
                        {{ $base['recipt_info']->municipality->name }}
                    @endif
                @endif
            </dd>
            <dt>Barangay</dt>
            <dd>
                @if(isset($base['is_mncpal_cert']))
                    @if(!is_null($base['recipt_info']->col_barangay_id))
                        {{ !is_null($base['rcpt_brgy']) ? $base['rcpt_brgy']->name : '' }}
                    @endif
                @else
                    @if (!empty($base['recipt_info']->barangay->name))
                        {{ $base['recipt_info']->barangay->name }}
                    @endif
                @endif
            </dd>
        </dl>
    </div>
</div>

<div class="modal" id="paper_size_opt">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="padding-bottom: 260px;">
            <div class="modal-header">
                <span><button type="button" class="close" data-dismiss="modal">&times;</button></span>
            </div>
            <div class="modal-body">
                @if(isset($base['is_mncpal_cert']))
                    <form method="get" action="{{ route('mncpal.print.cert', ['receipt' => $base['receipt'], 'prov_gov' => 'gov', 'ppr_size' => 'ppr_size', 'height' => 'height', 'width' => 'width' ]) }}" id="print_form">
                @else
                    <form method="get" action="{{ route('pdf.cert', ['receipt' => $base['receipt'], 'prov_gov' => 'gov', 'ppr_size' => 'ppr_size', 'height' => 'height', 'width' => 'width' ]) }}" id="print_form">
                @endif
                    <div class="form-group">
                        <label>Choose Paper Size</label>
                        <select class="form-control" name="ppr_size" id="ppr_size">
                            <option value="letter" selected>Letter (8.5x11")</option>
                            <option value="a4">A4</option>
                            <option value="legal">Legal (8.5x13")</option>
                            <option value="custom">Custom</option>
                        </select>
                        <br>
                        <div id="custom_extra" style=" visibility: hidden;">
                            <div class="col-sm-6">
                                <label>Height (in.)</label>
                                <input type="number" step="0.01" name="height" id="height" class="form-control" value="0">
                            </div>
                            <div class="col-sm-6">
                                <label>Width (in.)</label>
                                <input type="number" step="0.01" name="width" id="width" class="form-control" value="0">
                            </div>
                        </div>
                    </div>    
                    <br><br>
                    <!-- <div class="modal-footer"> -->
                        <button class="btn btn-success btn-sm pull-right" type="submit" id="print_btn">Print</button>
                    <!-- </div> -->
                </form>
            </div>
        </div>
    </div>
</div>

<hr />
<?php $hide_other_fees = 'hidden'; ?>
@if($base['withcert'])
    @if($base['withcert']->cert_type === 'Provincial Permit')
            <?php $hide_other_fees = ''; ?>
    @endif
@endif

@if (!isset($base['cert']))
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['receipt.certificate.store', 'receipt' => $base['receipt']], 'id' => 'cert_submit']) }}
    <div class="form-group col-sm-2">
        <label for="type">Type</label>
        <select name="type" class="form-control" id="cert_type" required autofocus>
            <option value="" selected disabled></option>
            @foreach ($base['rcpt_certificatetype'] as $type)
                @if(isset($base['is_mncpal_cert']))
                    @if($base['withcert'])
                        @if($base['withcert']->cert_type === $type->name)
                            <option selected value="{{ $type->id }}">{{ $type->name }}</option>
                        @else
                            @if($type->id == 1) {{-- for mncpal receipt prov permit only --}}
                                <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                            @endif
                        @endif
                    @else
                        @if($type->id == 1)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endif
                    @endif
                @else
                    @if($base['withcert'])
                        @if($base['withcert']->cert_type === $type->name)
                            <option selected value="{{ $type->id }}">{{ $type->name }}</option>
                        @else
                            <option  value="{{ $type->id }}">{{ $type->name }}</option>
                        @endif
                    @else
                        <option  value="{{ $type->id }}">{{ $type->name }}</option>
                    @endif
                @endif
                
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y') }}" required>
    </div>


    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee PREPARED BY:</label>
        <select class="form-control" name="prepared_by">
                <option value="provtreasurer" selected>Provincial Treasurer</option>
                <option value="asstprovtreasurer">Local Revenue Collection Officer III</option>
                <option value="asstprovtreasurer1">Local Revenue Collection Officer I</option>   
        </select>
    </div>

    {{-- <div class="form-group col-sm-4">
        <label for="signee">Provincial Governor:</label>
        <select class="form-control" name="prov_gov">
        </select>
    </div> --}}

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee</label>
        <select class="form-control" name="signee">
            <option value="provtreasurer">Provincial Treasurer</option>
            <option value="asstprovtreasurer">Assistant Provincial Treasurer</option>
            <option value="asstprovtreasurer1">Assistant Provincial Treasurer 1</option>
            <option value="forinabsence">Local Treasury Operations Officer IV</option>
        </select>
    </div>
     <?php 
        $recipientx = '';
        if(isset($base['cert']) &&  $base['cert']->recipient != ''){
            $recipientx = $base['cert']->recipient;
        }else{
            if(isset($base['is_mncpal_cert'])) 
                $recipientx = $base['recipt_info']->getCustomer->name;
            else
                $recipientx = $base['recipt_info']->customer->name;
        }
    ?>
    <div class="form-group col-sm-6">
        <label for="recipient">Recipient</label>
        <input type="text" class="form-control" name="recipient" id="recipient" value="{{ $recipientx  }}" required>
    </div>

    <div class="form-group col-sm-6" id="recipient_sex_div" style="display: none;">
        <label>Recipient Sex</label>
        <select class="form-control" name="recipient_sex">
            <option value=""></option>
            <?php
                $recipient_sex = ['Female', 'Male', 'N/A (for organizations)']
            ?>
            @foreach($recipient_sex as $key => $val)
                <option value="{{ $key }}">{{ $val }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="address">Address</label>
        @if(isset($base['is_mncpal_cert']))
            <input type="text" class="form-control" name="address" id="address" value="{{ $base['recipt_info']->getCustomer->address }}" >
        @else
            <input type="text" class="form-control" name="address" id="address" value="{{ $base['recipt_info']->customer->address }}" >
        @endif
    </div>

    <div class="form-group col-sm-3 col-sm-offset-6">
        <label>Include Entries From</label>
        <input type="text" class="form-control datepicker" name="incl_date_frm" value="{{ isset($base['cert']->include_from) ? \Carbon\Carbon::parse($base['cert']->include_from)->format('m/d/Y') : '' }}">
    </div>
    <div class="form-group col-sm-3">
        <label>Include Entries To</label>
        <input type="text" class="form-control datepicker" name="incl_date_to" value="{{ isset($base['cert']->include_from) ? \Carbon\Carbon::parse($base['cert']->include_to)->format('m/d/Y') : '' }}">
    </div>

    <div class="form-group col-sm-12">
        <label for="detail">Detail</label>
        <textarea id="detail" class="form-control" name="detail">{{$base['recipt_info']->remarks}}</textarea>
    </div>
    <fieldset>
        <legend><strong>TO BE FILLED UP</strong></legend>
        <div id="provincial_permit" class="hidden addtl_div">
            <div class="form-group col-sm-6">
                <label for="provincial_note">Note</label>
                <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_note" id="provincial_note" value="">
            </div>

            <div class="form-group col-sm-6">
                <label for="provincial_gov">Governor Signee</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_gov" id="provincial_gov" required>
                    <option value="1">Provincial Governor</option>
                    <option value="0">Acting Provincial Governor</option>
                </select>
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_clearance_number">Clearance Number</label>
                <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_clearance_number" id="provincial_clearance_number" value="">
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_type">Type</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_type" id="provincial_type" required>
                    <option value="new">New</option>
                    <option value="renewal">Renewal</option>
                </select>
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_bidding">For Bidding?</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_bidding" id="provincial_bidding" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>

        <div id="transfer_tax" class="hidden addtl_div">
            <div class="form-group col-sm-12">
                <label for="transfer_notary_public">Notary Public</label>
                <textarea class="form-control transfer_inputs addtl_inputs" name="transfer_notary_public" id="transfer_notary_public" required> </textarea>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_ptr_number">PTR Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ptr_number" id="transfer_ptr_number" value="">
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_doc_number">Doc. Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_doc_number" id="transfer_doc_number" value="" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_page_number">Page Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_page_number" id="transfer_page_number" value="" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_book_number">Book Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_book_number" id="transfer_book_number" value="" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_series">Series</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_series" id="transfer_series" value="" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="">Reference Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ref_num" id="transfer_ref_num" value="">
            </div>
        </div>

        <div id="sand_gravel" class="hidden addtl_div">
            <div class="form-group col-sm-6">
                <label for="sand_requestor">Requestor</label>
                @if(isset($base['is_mncpal_cert']))
                    <input type="text" class="form-control sand_inputs addtl_inputs" name="sand_requestor" id="sand_requestor" value="{{ $base['recipt_info']->getCustomer->name }}" required>
                @else
                    <input type="text" class="form-control sand_inputs addtl_inputs" name="sand_requestor" id="sand_requestor" value="{{ $base['recipt_info']->customer->name }}" required>
                @endif
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_requestor_addr">Requestor Address</label>
                <input type="text" class="form-control  addtl_inputs" name="sand_requestor_addr" id="sand_requestor_addr" value="" >
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_requestor_sex">Requestor Sex</label>
                <select class="form-control sand_inputs addtl_inputs" name="sand_requestor_sex" id="sand_requestor_sex">
                    <option value="1">Male</option>
                    <option value="0">Female</option>
                </select>
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_type">Type</label>
                <select class="form-control sand_inputs addtl_inputs" name="sand_type" id="sand_type">
                    <option value="0">Partial</option>
                    <option value="1">Full</option>
                </select>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_sandgravelprocessed">Less: <br>Sand and Gravel (processed)</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravelprocessed" id="sand_sandgravelprocessed" value="0" step="0.01" min="0" value="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_abc">Less: <br>Aggregate Base Course</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" value="0" name="sand_abc" id="sand_abc" value="0" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_sandgravel">Less: <br>Sand and Gravel</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" value="0" name="sand_sandgravel" id="sand_sandgravel" value="0" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_boulders">Less: <br>Boulders</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" value="0" name="sand_boulders" id="sand_boulders" value="0" step="0.01" min="0" required>
            </div>
        </div>
    </fieldset>
    <br /><br />


    <div class="col-sm-12 other_fees_charges {{ $hide_other_fees }} " id="PROVINCALFEES">
        <table class="table table-hovered table-bordered">
            <thead>
                    <th>PROVINCAL FEES/CHARGES</th>
                    <th>AMOUNT</th>
                    <th>OR NUMBER</th>
                    <th>DATE</th>
                     <th>INITIALS</th>
                    <th>action</th>
            </thead>
            <tbody>
                @if(count($base['OtherFeesCharges']) > 0)
                    @for($x = 0 ; $x < count($base['OtherFeesCharges']) ; $x++)
                    <?php
                        $clearsx = ' <button id="add_row" class="btn btn-sm btn-danger" type="button">clear</button>';
                        if( $x === 0 ){
                            $clearsx = ' <button id="add_row_other_fees_charges" class="btn btn-sm btn-danger" type="button">clear</button> <button id="add_rowx" class="btn btn-sm btn-info" type="button">ass</button>';
                        }
                    ?>
                        <tr>
                            <td><input type="text" class="form-control" name="fees_charges[]" value="{{ $base['OtherFeesCharges'][$x]->fees_charges }}"></td>
                            <td><input type="number" class="form-control"  min="0" step="0.05"  name="fees_ammount[]" value="{{ $base['OtherFeesCharges'][$x]->ammount }}" ></td>
                            <td><input type="text" class="form-control"  name="fees_or_number[]" value="{{ $base['OtherFeesCharges'][$x]->or_number }}"></td>
                            <td><div class="form-group"><input type="text" class="form-control datepicker"  name="other_date[]" value="{{ $base['OtherFeesCharges'][$x]->fees_date }}" /></div></td>
                            <td><input type="text" class="form-control"  name="fees_initials[]" value="{{ $base['OtherFeesCharges'][$x]->initials }}"></td>
                            <td> {{ $clearsx }} </td>
                        </tr>
                    @endfor
                @else
                     <tr>
                        <td><input type="text" class="form-control" name="fees_charges[]"></td>
                        <td><input type="number" class="form-control"  min="0" step="0.05"  name="fees_ammount[]"></td>
                        <td><input type="text" class="form-control"  name="fees_or_number[]"></td>
                        <td><div class="form-group"><input type="text" class="form-control datepicker"  name="other_date[]" /></div></td>
                        <td><input type="text" class="form-control"  name="fees_initials[]" ></td>
                        <td> <button id="add_row_other_fees_charges" onclick="$(this).addMunicipalOtherFees()" class="btn btn-sm btn-success" type="button">add</button> </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>



    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    <input type="hidden" name="is_mncpal_cert" value="{{ isset($base['is_mncpal_cert']) ? 1 : 0 }}">
    {{ Form::close() }}
</div>
@else
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['receipt.certificate.store', 'receipt' => $base['receipt']], 'id' => 'cert_submit']) }}
    <div class="form-group col-sm-2">
        <label for="type">Type</label>
        <select name="type" class="form-control" id="cert_type" required autofocus>
            @foreach ($base['rcpt_certificatetype'] as $type)
                @if ($base['cert']->col_rcpt_certificate_type_id == $type->id)
                    @if(isset($base['is_mncpal_cert']))
                        @if($type->id == 1)
                            <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                        @endif
                    @else
                        <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                    @endif
                @else
                    @if(isset($base['is_mncpal_cert']))
                        @if($type->id == 1)
                            <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                        @endif
                    @else
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endif
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y', strtotime($base['cert']->date_of_entry)) }}" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee PREPARED BY:</label>
        <select class="form-control" name="prepared_by">
            @if ($base['cert']->signee == null)
                <option value="provtreasurer" selected>Provincial Treasurer</option>
                <option value="asstprovtreasurer">Local Revenue Collection Officer III</option>
                <option value="asstprovtreasurer1">Local Revenue Collection Officer I</option>
                
            @else

                <option value="provtreasurer" <?=$base['cert']->prepared_by=='provtreasurer'? 'selected':''?> >Provincial Treasurer</option>
                <option value="asstprovtreasurer" <?=$base['cert']->prepared_by=='asstprovtreasurer'? 'selected':''?> >Local Revenue Collection Officer III</option>
                <option value="asstprovtreasurer1"  <?=$base['cert']->prepared_by=='asstprovtreasurer1'? 'selected':''?> >Local Revenue Collection Officer I</option>
            @endif
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee</label>
        <select class="form-control" name="signee">
            @if ($base['cert']->signee == null)
                <option value="provtreasurer" selected>Provincial Treasurer</option>
                <option value="asstprovtreasurer">Assistant Provincial Treasurer</option>
                <option value="asstprovtreasurer1">Assistant Provincial Treasurer 1</option>
                <option value="forinabsence">Local Treasury Operations Officer IV</option>
            @else
                <option value="provtreasurer" <?=$base['cert']->signee=='provtreasurer'? 'selected':''?> >Provincial Treasurer</option>
                <option value="asstprovtreasurer"  <?=$base['cert']->signee=='asstprovtreasurer'? 'selected':''?> >Assistant Provincial Treasurer</option>
                <option value="asstprovtreasurer1" <?=$base['cert']->signee=='asstprovtreasurer1'? 'selected':''?> >Assistant Provincial Treasurer 1</option>
                <option value="forinabsence" <?=$base['cert']->signee=='forinabsence'? 'selected':''?> >Local Treasury Operations Officer IV</option>
            @endif
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Provincial Governor:</label>
        {{-- @if(!empty($base['cert'])) --}}
            <!-- <input type="text" name="prov_gov" id="prov_gov" value="{{-- !is_null($base['cert']->provincial_governor) ? $base['cert']->provincial_governor : '' --}}" class="form-control"> -->
        {{-- @else --}}
            <!-- <input type="text" name="prov_gov" id="prov_gov" value="{{-- !is_null($base['prov_gov']) ? $base['prov_gov']->officer_name : '' --}}" class="form-control"> -->
        {{-- @endif --}}

        <select name="prov_gov" id="prov_gov" class="form-control">
            @foreach($base['prov_gov'] as $gov)
                @if(!empty($base['cert']))
                    {{-- @if(strcasecmp($gov->officer_name, $base['cert']->provincial_governor) == 0) --}}
                    @if(strcasecmp($gov->officer_name, $base['latest_prov_gov']->officer_name) == 0)
                        <option value="{{ $gov->id }}" selected>{{ $gov->officer_name }}</option>
                    @else
                        <option value="{{ $gov->id }}">{{ $gov->officer_name }}</option>
                    @endif
                @else
                    @if(strcasecmp($gov->officer_name, $base['latest_prov_gov']->officer_name) == 0)
                        <option value="{{ $gov->id }}" selected>{{ $gov->officer_name }}</option>
                    @else
                        <option value="{{ $gov->id }}">{{ $gov->officer_name }}</option>
                    @endif
                @endif
            @endforeach
        </select>
    </div>

    <?php 
    $recipientx = '';

    if(isset($base['cert']) &&  $base['cert']->recipient != ''){
        $recipientx = $base['cert']->recipient;
    }else{
        $recipientx = $base['recipt_info']->customer->name ;
    }

    ?>

    <div class="form-group col-sm-6">
        <label for="recipient">Recipient</label>
        <input type="text" class="form-control" name="recipient" id="recipient" value="{{ $recipientx  }}" required>
    </div>

    <div class="form-group col-sm-2" id="recipient_sex_div" style="display: none;">
        <label>Recipient Sex</label>
        <select class="form-control" name="recipient_sex">
            <option value=""></option>
            <?php
                $recipient_sex = ['Female', 'Male', 'N/A (for organizations)']
            ?>
            @foreach($recipient_sex as $key => $val)
                @if($base['cert']->sand_requestor_sex == $key && $base['cert']->sand_requestor_sex != "")
                    <option value="{{ $key }}" selected="">{{ $val }}</option>
                @else
                    <option value="{{ $key }}">{{ $val }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="address">Address</label>
        <input type="text" class="form-control" name="address" id="address" value="{{ $base['cert']->address }}" >
    </div>

    <div class="form-group col-sm-3">
        <label>Include Entries From</label>
        <input type="text" class="form-control datepicker" name="incl_date_frm" value="{{ !is_null($base['cert']->include_from) ? \Carbon\Carbon::parse($base['cert']->include_from)->format('m/d/Y') : '' }}">
    </div>
    <div class="form-group col-sm-3">
        <label>Include Entries To</label>
        <input type="text" class="form-control datepicker" name="incl_date_to" value="{{ !is_null($base['cert']->include_from) ? \Carbon\Carbon::parse($base['cert']->include_to)->format('m/d/Y') : '' }}">
    </div>

    <div class="form-group col-sm-12">
        <label for="detail">Detail</label>

        <?php
            $details = '';

            if($base['cert']->detail){
                $details = $base['cert']->detail;
                $details2 = preg_replace('/<[^>]*>*/', "", $base['cert']->detail);
            }else if($base['recipt_info']->remarks){
                $details = $base['recipt_info']->remarks;
                $details2 = preg_replace('/<[^>]*>*/', "", $base['recipt_info']->remarks);
            }
        ?>
        @if(strlen($base['cert']->detail) >= 5000)
            <textarea id="detail" class="form-control" name="detail">{{  $details2 }}</textarea>
        @else
            <textarea id="detail" class="form-control" name="detail">{{  $details }}</textarea>
        @endif
    </div>

    <fieldset>
        <legend><strong>TO BE FILLED UP</strong></legend>
        <div id="provincial_permit" class="addtl_div">
            <div class="form-group col-sm-6">
                <label for="prv_requestor">Requestor</label>
                <input type="text" class="form-control provincial_inputs addtl_inputs" name="prv_requestor" id="prv_requestor" value="{{ !is_null($base['cert']) ? $base['cert']->sand_requestor : '' }}">
            </div>

            <div class="form-group col-sm-6">
                <label for="provincial_note">Note</label>
                <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_note" id="provincial_note" value="{{ $base['cert']->provincial_note }}">
            </div>

            <div class="form-group col-sm-6">
                <label for="provincial_gov">Governor Signee</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_gov" id="provincial_gov" required>
                    @if($base['cert']->actingprovincial_governor == null)
                        <option value="1" selected>Provincial Governor</option>
                        <option value="0">Acting Provincial Governor</option>
                    @else
                        <option value="1">Provincial Governor</option>
                        <option value="0" selected>Acting Provincial Governor</option>
                    @endif
                </select>
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_clearance_number">Clearance Number</label>
                <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_clearance_number" id="provincial_clearance_number" value="{{ $base['cert']->provincial_clearance_number }}">
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_type">Type</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_type" id="provincial_type" required>
                    @if ($base['cert']->provincial_type == "new")
                        <option value="new" selected>New</option>
                        <option value="renewal">Renewal</option>
                    @else
                        <option value="new">New</option>
                        <option value="renewal" selected>Renewal</option>
                    @endif
                </select>
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_bidding">For Bidding?</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_bidding" id="provincial_bidding" required>
                    @if ($base['cert']->provincial_bidding == 0)
                        <option value="0" selected>No</option>
                        <option value="1">Yes</option>
                    @else
                        <option value="0">No</option>
                        <option value="1" selected>Yes</option>
                    @endif
                </select>
            </div>
        </div>

        <div id="transfer_tax" class="addtl_div">
            <div class="form-group col-sm-12">
                <label for="transfer_notary_public">Notary Public</label>
                <textarea class="form-control transfer_inputs addtl_inputs" name="transfer_notary_public" id="transfer_notary_public" required> {{ $base['cert']->transfer_notary_public }}</textarea>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_ptr_number">PTR Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ptr_number" id="transfer_ptr_number" value="{{ $base['cert']->transfer_ptr_number }}" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_doc_number">Doc. Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_doc_number" id="transfer_doc_number" value="{{ $base['cert']->transfer_doc_number }}" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_page_number">Page Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_page_number" id="transfer_page_number" value="{{ $base['cert']->transfer_page_number }}" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_book_number">Book Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_book_number" id="transfer_book_number" value="{{ $base['cert']->transfer_book_number }}" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_series">Series</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_series" id="transfer_series" value="{{ $base['cert']->transfer_series }}" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="">Reference Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ref_num" id="transfer_ref_num" value="{{ $base['cert']->transfer_ref_num }}">
            </div>
        </div>

        <div id="sand_gravel" class="hidden addtl_div">
            <div class="form-group col-sm-6">
                <label for="sand_requestor">Requestor</label>
                <input type="text" class="form-control sand_inputs addtl_inputs" name="sand_requestor" id="sand_requestor" value="{{ $base['cert']->sand_requestor }}" required>
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_requestor_addr">Requestor Address</label>
                <input type="text" class="form-control sand_inputs addtl_inputs" name="sand_requestor_addr" id="sand_requestor_addr" value="{{ $base['cert']->sand_requestor_addr }}" >
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_requestor_sex">Requestor Sex</label>
                <select class="form-control sand_inputs addtl_inputs" name="sand_requestor_sex" id="sand_requestor_sex">
                    @if ($base['cert']->sand_requestor_sex == 0)
                        <option value="1">Male</option>
                        <option value="0" selected>Female</option>
                    @else
                        <option value="1" selected>Male</option>
                        <option value="0">Female</option>
                    @endif
                </select>
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_type">Type</label>
                <select class="form-control sand_inputs addtl_inputs" name="sand_type" id="sand_type">
                    @if ($base['cert']->sand_type == 0)
                        <option value="0" selected>Partial</option>
                        <option value="1">Full</option>
                    @else
                        <option value="0">Partial</option>
                        <option value="1" selected>Full</option>
                    @endif
                </select>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_sandgravelprocessed">Less: <br>Sand and Gravel (processed)</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravelprocessed" id="sand_sandgravelprocessed" value="{{ $base['cert']->sand_sandgravelprocessed }}" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_abc">Less: <br>Aggregate Base Course</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_abc" id="sand_abc" value="{{ $base['cert']->sand_abc }}" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_sandgravel">Less: <br>Sand and Gravel</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravel" id="sand_sandgravel" value="{{ $base['cert']->sand_sandgravel }}" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_boulders">Less: <br>Boulders</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_boulders" id="sand_boulders" value="{{ $base['cert']->sand_boulders }}" step="0.01" min="0" required>
            </div>
        </div>
    </fieldset>

    <br /><br />


    <div class="col-sm-12 other_fees_charges {{ $hide_other_fees }}">
        <table class="table table-hovered table-bordered" id="PROVINCALFEES">
            <thead>
                <th>PROVINCAL FEES/CHARGES</th>
                <th>AMOUNT</th>
                <th>OR NUMBER</th>
                <th>DATE</th>
                <th>Initials</th>
                <th>action</th>
            </thead>
            <tbody>
                @if(count($base['OtherFeesCharges']) > 0)
                @for($x = 0 ; $x < count($base['OtherFeesCharges']) ; $x++)

                <?php
                    $clearsxx = '<button id="add_row" class="btn btn-sm btn-danger" type="button" onclick="$(this).deleteMunicipalOtherFees(\''. $base['OtherFeesCharges'][$x]->id .'\');">clear</button>';
                    if( $x === 0 ){
                        $clearsxx = ' <button id="add_row_other_fees_charges" class="btn btn-sm btn-danger" type="button" onclick="$(this).deleteMunicipalOtherFees(\''. $base['OtherFeesCharges'][$x]->id .'\');">clear</button> <button id="add_rowx" onclick="$(this).addMunicipalOtherFees();" class="btn btn-sm btn-success" type="button">add</button>';
                    }
                ?>

                    <tr>
                        <td>
                            <input type="hidden" name="other_fees_id[]" value="{{ $base['OtherFeesCharges'][$x]->id }}">
                            <input type="text" class="form-control" name="fees_charges[]" value="{{ $base['OtherFeesCharges'][$x]->fees_charges }}"></td>
                        <td><input type="number" class="form-control"  min="0" step="0.05"  name="fees_ammount[]" value="{{ $base['OtherFeesCharges'][$x]->ammount }}" ></td>
                        <td><input type="text" class="form-control"  name="fees_or_number[]" value="{{ $base['OtherFeesCharges'][$x]->or_number }}"></td>
                        <td><div class="form-group"><input type="text" class="form-control datepicker"  name="other_date[]" value="{{ $base['OtherFeesCharges'][$x]->fees_date }}" /></div></td>
                        <td><input type="text" class="form-control"  name="fees_initials[]" value="{{ $base['OtherFeesCharges'][$x]->initials }}"></td>
                        <td>{!! $clearsxx !!}</td>
                    </tr>
                @endfor
            @else
                 <tr>
                    <td><input type="text" class="form-control" name="fees_charges[]"></td>
                    <td><input type="number" class="form-control"  min="0" step="0.05"  name="fees_ammount[]"></td>
                    <td><input type="text" class="form-control"  name="fees_or_number[]"></td>
                    <td><div class="form-group"><input type="text" class="form-control datepicker"  name="other_date[]" /></div></td>
                    <td><input type="text" class="form-control"  name="fees_initials[]" ></td>
                    <td></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <input type="hidden" name="is_mncpal_cert" value="{{ isset($base['is_mncpal_cert']) ? 1 : 0 }}">
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    {{ Form::close() }}
    </div>
@endif

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
{{ Html::script('/tinymce-4.5.6/tinymce.min.js') }}
<script>
$(document).ready(function() {
    
    

    if ($('#cert_type').val() !== null) {
        show_addtl_inputs($('#cert_type').val());
    }
});
$.fn.datepickerx = function(){
        $('.datepicker').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide'
        });
    };
$.fn.datepickerx();

$.fn.removeOtherFees = function(){
    $('button.remove_other_fees').click(function(){
      $(this).parent().parent().remove();
    });
};

$.fn.addMunicipalOtherFees = function(){
    var addxxx = '<tr class="added_tr">'+
                        '<td><input type="text" class="form-control" name="fees_charges[]"></td>'+
                        '<td><input type="number" class="form-control"  min="0" step="0.05"  name="fees_ammount[]"></td>'+
                        '<td><input type="text" class="form-control"  name="fees_or_number[]"></td>'+
                        '<td><div class="form-group"><input type="text" class="form-control datepicker"  name="other_date[]" /></div></td>'+
                        '<td><input type="text" class="form-control"  name="fees_initials[]" ></td>'+
                        '<td> <button  class="btn btn-sm btn-danger remove_other_fees"  type="button">remove</button> </td>'+
                    '</tr>';

    $('#PROVINCALFEES tbody').append(addxxx);
    $.fn.datepickerx();
    $.fn.removeOtherFees();
};

$('#cert_type').change( function() {
    show_addtl_inputs(this.value);
});

function show_addtl_inputs(type) {
    var group = '';
    var div = '';
    if (type == 1) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').removeClass('hidden');
        div = '#provincial_permit';
        group = '.provincial_inputs';
        $('#recipient_sex_div').css('display', 'none');
    } else if (type == 2) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').addClass('hidden');
        div = '#transfer_tax';
        group = '.transfer_inputs';
        $('#recipient_sex_div').css('display', 'none');
    } else if (type == 3) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').addClass('hidden');
        div = '#sand_gravel';
        group = '.sand_inputs';
        $('#recipient_sex_div').css('display', 'none');
    } else if(type == 4) {
        $('fieldset').removeClass('hidden');
        $('.other_fees_charges').removeClass('hidden');
        div = '#provincial_permit';
        // group = '.provincial_inputs';
        $('#recipient_sex_div').css('display', 'block');
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


$.fn.deleteMunicipalOtherFees = function(receipt){
    $.ajax({
          type: 'POST',
          url: '{{route('report.clear_other_municpal_fees')}}',
          data: {
            _token : '{{csrf_token()}}',
            receipt: receipt
          },
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            location.reload();
          }
        });
};

$(document).on('change', '#prov_gov', function() {
    var str = $('#print_btn').attr('href');
    var str2 = str.replace('gov', $(this).val());
    $('#print_btn').attr('href', str2);
});

$(document).on('click', '#print_btn', function(e) {
    e.preventDefault();
    var route = $('#print_form').attr('action');
    var route2 = route.replace('ppr_size', $('#ppr_size').val());
    var route3 = route2.replace('height', $('#height').val());
    var route4 = route3.replace('width', $('#width').val());
    $('#print_form').attr('action', route4);
    $('#print_form').submit();
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
$(document).on('change', '#ppr_size', function() {
    if($(this).val() == "custom") {
        $('#custom_extra').css('visibility', 'visible');
    } else {
        $('#custom_extra').css('visibility', 'hidden');
    }
});
</script>
@endsection
