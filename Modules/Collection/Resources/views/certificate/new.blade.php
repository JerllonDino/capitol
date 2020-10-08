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
</style>
@endsection

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        @if (isset($base['cert']))
            <a class="btn btn-info" href="{{ route('pdf.cert', ['receipt' => $base['receipt']]) }}">Print</a>
        @else
            <a class="btn btn-info disabled" href="#">Print</a>
        @endif

        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['recipt_info']->user->realname }}</dd>
            <dt>AF Type</dt>
            <dd>{{ $base['recipt_info']->serial->formtype->name }}</dd>
            <dt>Serial Number</dt>
            <dd>{{ $base['recipt_info']->serial_no }}</dd>
            <dt>Client Type</dt>
            <dd>{{ $base['recipt_info']->client_type_desc->description ?? '' }}</dd>

            <dt>Municipality</dt>
            <dd>
            @if (!empty($base['recipt_info']->municipality->name))
            {{ $base['recipt_info']->municipality->name }}
            @endif
            </dd>
            <dt>Barangay</dt>
            <dd>
            @if (!empty($base['recipt_info']->barangay->name))
            {{ $base['recipt_info']->barangay->name }}
            @endif
            </dd>
        </dl>
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
    {{ Form::open(['method' => 'POST', 'route' => ['receipt.certificate.store', 'receipt' => $base['receipt']]]) }}
    <div class="form-group col-sm-2">
        <label for="type">Type</label>
        <select name="type" class="form-control" id="cert_type" required autofocus>
            <option value="" selected disabled></option>
            @foreach ($base['rcpt_certificatetype'] as $type)
              @if($base['withcert'])
                    @if($base['withcert']->cert_type === $type->name)
                        <option selected value="{{ $type->id }}">{{ $type->name }}</option>
                        @else
                          <option  value="{{ $type->id }}">{{ $type->name }}</option>
                    @endif
            @else
                          <option  value="{{ $type->id }}">{{ $type->name }}</option>
            @endif

            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y') }}" required>
    </div>
    <?php  
        // dd($base['officer']);
    ?>

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee PREPARED BY:</label>
        <select class="form-control" name="prepared_by">
            <option value="" selected>-- Select Signee --</option>
            <?php foreach($base['officer'] as $position): ?>  
                <option value="{{ ($position->id) }}">  {{($position->officer_name)}}</option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee</label>
        <select class="form-control" name="prepared_by">
            <option value="" selected>-- Select Signee --</option>
            <?php foreach($base['officer'] as $position): ?>                
                 <option value="{{ ($position->id) }}">  {{($position->officer_name)}}</option>
            <?php endforeach; ?>
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

    <div class="form-group col-sm-6">
        <label for="address">Address</label>
        <input type="text" class="form-control" name="address" id="address" value="{{ $base['recipt_info']->customer->address }}" >
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
            <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_clearance_number" id="provincial_clearance_number" value="" required>
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
            <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ptr_number" id="transfer_ptr_number" value="" required>
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
    </div>

    <div id="sand_gravel" class="hidden addtl_div">
        <div class="form-group col-sm-6">
            <label for="sand_requestor">Requestor</label>
            <input type="text" class="form-control sand_inputs addtl_inputs" name="sand_requestor" id="sand_requestor" value="{{ $base['recipt_info']->customer->name }}" required>
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
                            dd($x);
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
    {{ Form::close() }}
</div>
@else
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['receipt.certificate.store', 'receipt' => $base['receipt']]]) }}
    <div class="form-group col-sm-2">
        <label for="type">Type</label>
        <select name="type" class="form-control" id="cert_type" required autofocus>
            @foreach ($base['rcpt_certificatetype'] as $type)
                @if ($base['cert']->col_rcpt_certificate_type_id == $type->id)
                <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                @else
                <option value="{{ $type->id }}">{{ $type->name }}</option>
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
            <option value="" selected>-- Select Signee --</option>
            <?php foreach($base['position'] as $position): ?>                
                <option value="<?php echo e($position->position); ?>"><?php echo e($position->position); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee</label>
        <select class="form-control" name="prepared_by">
            <option value="" selected>-- Select Signee --</option>
            <?php foreach($base['position'] as $position): ?>                
                <option value="<?php echo e($position->position); ?>"><?php echo e($position->position); ?></option>
            <?php endforeach; ?>
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

    <div class="form-group col-sm-6">
        <label for="address">Address</label>
        <input type="text" class="form-control" name="address" id="address" value="{{ $base['cert']->address }}" >
    </div>

    <div class="form-group col-sm-12">
        <label for="detail">Detail</label>

        <?php
                $details = '';

                if($base['cert']->detail){
                    $details = $base['cert']->detail;
                }else if($base['recipt_info']->remarks){
                    $details = $base['recipt_info']->remarks;
                }
        ?>
        <textarea id="detail" class="form-control" name="detail">{{  $details }}</textarea>
    </div>

<fieldset>
                <legend><strong>TO BE FILLED UP</strong></legend>
    <div id="provincial_permit" class="addtl_div">
        <div class="form-group col-sm-6">
            <label for="provincial_note">Note</label>
            <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_note" id="provincial_note" value="{{ $base['cert']->provincial_note }}">
        </div>

        <div class="form-group col-sm-6">
            <label for="provincial_gov">Governor Signee</label>
            <select class="form-control provincial_inputs addtl_inputs" name="provincial_gov" id="provincial_gov" required>
                @if ($base['cert']->actingprovincial_governor == null)
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
            <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_clearance_number" id="provincial_clearance_number" value="{{ $base['cert']->provincial_clearance_number }}" required>
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
                        <th>PROVINCAL FEES/CHARGES ccc</th>
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


    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    {{ Form::close() }}
</div>
@endif

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
        $('.other_fees_charges').removeClass('hidden');
        div = '#provincial_permit';
        group = '.provincial_inputs';
    } else if (type == 2) {
        $('.other_fees_charges').addClass('hidden');
        div = '#transfer_tax';
        group = '.transfer_inputs';
    } else if (type == 3) {
        $('.other_fees_charges').addClass('hidden');
        div = '#sand_gravel';
        group = '.sand_inputs';
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
</script>
@endsection
