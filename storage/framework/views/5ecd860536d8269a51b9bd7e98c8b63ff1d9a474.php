

<?php $__env->startSection('css'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ): ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'PATCH', 'route' => ['field_land_tax.update', $base['receipt']->id], 'id'=>'store_form'])); ?>

    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['user']->realname); ?></dd>
            <dt>Form</dt>
            <dd><?php echo e($base['receipt']->form->name); ?>

                <?php
                    if( $base['receipt']->form->name == 'Form 56'){
                        echo '<input type="hidden" name="form_type" id="form" value="2" /> ';
                    }else{
                         echo '<input type="hidden" name="form_type" id="form" value="1" /> ';
                    }
                ?>
            </dd>
            <dt>Serial Number</dt>
            <dd><?php echo e($base['receipt']->serial_no); ?></dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo e($base['user']->id); ?>">
        <input type="hidden" class="form-control" name="serial_id" id="serial_id" value="<?php echo e($base['receipt']->serial->id); ?>">
    </div>

    <div class="form-group col-sm-4">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="<?php echo e(date('m/d/Y  H:i:s', strtotime($base['receipt']->date_of_entry))); ?>" required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="date">Report Date</label>
        <input type="text" class="form-control datepicker" name="report_date" value="<?php echo e(date('m/d/Y', strtotime($base['receipt']->report_date))); ?>" required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            <?php if($base['receipt']->col_municipality_id == ''): ?>
            <option selected disabled></option>
            <?php else: ?>
            <option disabled></option>
            <?php endif; ?>

            <?php foreach($base['municipalities'] as $municipality): ?>
                <?php if($base['receipt']->col_municipality_id == $municipality['id']): ?>
                <option value="<?php echo e($municipality['id']); ?>" selected><?php echo e($municipality['name']); ?></option>
                <?php else: ?>
                <option value="<?php echo e($municipality['id']); ?>"><?php echo e($municipality['name']); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group col-sm-4">
        <label for="barangay">Barangay</label>
        <?php if(!empty($base['barangays'])): ?>
        <select class="form-control" name="brgy" id="brgy">
        <?php else: ?>
        <select class="form-control" name="brgy" id="brgy" disabled>
        <?php endif; ?>

            <?php if(!empty($base['barangays'])): ?>
                <?php foreach($base['barangays'] as $brgy): ?>
                    <?php if($base['receipt']->col_barangay_id == $brgy['id']): ?>
                    <option value="<?php echo e($brgy['id']); ?>" selected><?php echo e($brgy['name']); ?></option>
                    <?php else: ?>
                    <option value="<?php echo e($brgy['id']); ?>"><?php echo e($brgy['name']); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer"  value="<?php echo e($base['receipt']->customer->name); ?>">
        <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="<?php echo e($base['receipt']->customer->id); ?>">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
        <!-- <select class="form-control" name="customer_type" id="new_customer_type"> -->
        <small id="client_type_msg" style="color: red;"></small>
        <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?>
                <?php if($base['receipt']->client_type ===  $sandgravel_types['id'] ): ?>
                    <option value="<?php echo e($sandgravel_types['id']); ?>" selected><?php echo e($sandgravel_types['description']); ?></option>
                <?php else: ?>
                    <option value="<?php echo e($sandgravel_types['id']); ?>"><?php echo e($sandgravel_types['description']); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" required="">
            <?php if( $base['receipt']->sex == 'male' ): ?>
                <option value="female">Female</option>
                <option value="male" selected="">Male</option>
            <?php elseif($base['receipt']->sex == 'female'): ?>
                <option value="female" selected="">Female</option>
                <option value="male">Male</option>
            <?php else: ?>
                <option selected="" ></option>
                <option value="female" >Female</option>
                <option value="male">Male</option>
            <?php endif; ?>
        </select>
    </div>



    <div class="form-group col-sm-4">
        <label for="user">Transaction Type</label>
        <select class="form-control" id="transaction_type" name="transaction_type">
            <?php foreach($base['transaction_type'] as $transaction_type): ?>
                <?php if($base['receipt']->transaction_type == $transaction_type->id): ?>
                <option value="<?php echo e($transaction_type->id); ?>" selected><?php echo e($transaction_type->name); ?></option>
                <?php else: ?>
                <option value="<?php echo e($transaction_type->id); ?>"><?php echo e($transaction_type->name); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_name">Bank Name</label>
        <?php if($base['receipt']->bank_name == ''): ?>
            <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" disabled>
        <?php else: ?>
            <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="<?php echo e($base['receipt']->bank_name); ?>">
        <?php endif; ?>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_number">Number</label>
        <?php if($base['receipt']->bank_number == ''): ?>
            <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" disabled>
        <?php else: ?>
            <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="<?php echo e($base['receipt']->bank_number); ?>">
        <?php endif; ?>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_date">Date</label>
        <?php if($base['receipt']->bank_date == ''): ?>
            <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" disabled>
        <?php else: ?>
            <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="<?php echo e(date('m/d/Y', strtotime($base['receipt']->bank_date))); ?>">
        <?php endif; ?>

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
        <!--<?php /* <?php if($base['receipt']->bank_remark == ''): ?>
            <textarea class="form-control bank_input" name="bank_remark" id="bank_remark" value="" disabled></textarea>
        <?php else: ?>
            <textarea class="form-control bank_input" name="bank_remark" id="bank_remark" value=""><?php echo e($base['receipt']->bank_remark); ?></textarea>
        <?php endif; ?> */ ?>-->
        <textarea class="form-control bank_input" name="bank_remark" id="bank_remark">
            <?php if(strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['receipt']->remarks))) != 0): ?>
                <?php //echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks)."\n" : ''; ?>
                <?php //echo $base['receipt']->bank_remark != '' ? strip_tags($base['receipt']->bank_remark)."\n" : ''; ?>
                <?php 
                    // if(isset($base['cert'])) {
                    //     if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->detail))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->detail))) != 0) {
                    //         echo $base['cert']->detail != '' ? strip_tags($base['cert']->detail)."\n" : '';
                    //     }
                    // }
                ?> 

                <?php if($base['receipt']->remarks != ''): ?>
                    <?php echo strip_tags($base['receipt']->remarks); ?> <br>
                <?php endif; ?>

                <?php if($base['receipt']->bank_remark != ''): ?>
                    <?php echo strip_tags($base['receipt']->bank_remark); ?> <br>
                <?php endif; ?>

                <?php if(isset($base['cert'])): ?>
                    <?php if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->detail))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->detail))) != 0): ?>
                        <?php if($base['cert']->detail != ''): ?>
                            <?php echo strip_tags($base['cert']->detail ); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <?php //echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks)."\n" : ''; ?>
                <?php 
                    // if(isset($base['cert'])) {
                    //     if(strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->bank_remark))) != 0) {
                    //         echo $base['cert']->detail != '' ? $base['cert']->detail."\n" : ''; 
                    //     }
                    // }
                ?>

                <?php if($base['receipt']->remarks != ''): ?>
                    <?php echo strip_tags($base['receipt']->remarks); ?> <br>
                <?php endif; ?>

                <?php if(isset($base['cert'])): ?>
                    <?php if(strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->bank_remark))) != 0): ?>
                        <?php if($base['cert']->detail != ''): ?>
                            <?php echo strip_tags($base['cert']->detail); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>    
        </textarea>
    </div>

    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th colspan="2">Account</th>
                    <th class="td_nature">Nature</th>
                    <th>Amount</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
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
                <?php if(count($base['receipt']->items) > 0): ?>
                <?php foreach($base['receipt']->items as $i => $item): ?>
                <tr>
                    <td>
                        <?php if($item->acct_title != null): ?>

                        <input type="text" class="form-control account" value="<?php echo e($item->acct_title->name .' ('. $item->acct_title->group->category->name .')'); ?>" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="<?php echo e($item->acct_title->id); ?>">
                        <input type="hidden" class="form-control" name="account_type[]" value="title">
                            <?php if(isset($item->acct_title->rate)): ?>
                                <input type="hidden" class="form-control account_is_shared" value="<?php echo e($item->acct_title->rate->is_shared); ?>" name="account_is_shared[]">
                            <?php else: ?>
                                <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                            <?php endif; ?>
                        <?php else: ?>
                        <?php
                            if($item->acct_subtitle){
                                if( $item->acct_subtitle->id == 4){
                                    $booklet = '';
                                    echo '<input type="hidden" name="bookletx" value="true" /> ';
                                }
                        ?>
                        <input type="text" class="form-control account" value="<?php echo e($item->acct_subtitle->name .' ('. $item->acct_subtitle->title->group->category->name .')'); ?>" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="<?php echo e($item->acct_subtitle->id); ?>">
                        <?php 
                            }else{
                        ?>
                        <input type="text" class="form-control account" value="" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="">
                        <?php
                        } ?>

                        <input type="hidden" class="form-control" name="account_type[]" value="subtitle">
                            <?php if(isset($item->acct_title->rate)): ?>
                                <input type="hidden" class="form-control account_is_shared" value="<?php echo e($item->acct_title->rate->is_shared); ?>" name="account_is_shared[]">
                            <?php else: ?>
                                <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                            <?php endif; ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                        <?php if(isset($item->detail)): ?>
                            <input type="hidden" class="form-control account_rate" name="account_rate[]" value="<?php echo e($item->detail->col_collection_rate_id); ?>">
                        <?php else: ?>
                            <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                        <?php endif; ?>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" value="<?php echo e($item->nature); ?>" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]"  step="0.01" value="<?php echo e($item->value); ?>" required>
                    </td>
                    <td>
                    <?php if($i > 0): ?>
                        <button class="btn btn-warning btn-sm rem_row" type="button">
                        <i class="fa fa-minus"></i>
                        </button>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>

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

                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!--<?php /* <div class="form-group col-sm-12">
        <label for="remarks">Receipt Remarks</label>
        <?php if(!is_null($base['receipt']->remarks)): ?>
            <textarea id="remarks" class="form-control" name="remarks"><?php echo e($base['receipt']->remarks); ?></textarea>
        <?php else: ?>
            <textarea id="remarks" class="form-control" name="remarks"></textarea>
        <?php endif; ?>
    </div> */ ?>-->

    <div class="col-md-12 <?php echo e($booklet); ?>" id="sg_booklets">
        <table class="table table-bordered center" id="booklets_sg">
            <thead>
                <tr>
                    <th class="text-center">BOOKLET START</th>
                    <th class="text-center">BOOKLET END</th>
                    <th class="text-center"><button id="add_booklet_row" class="btn btn-sm btn-info"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($base['receipt']->sgbooklet) == 0): ?>
                <tr>
                    <td><input type="number" class="form-control booklet_start" name="booklet_start[]"></td>
                    <td><input type="number" class="form-control booklet_end" name="booklet_end[]"></td>
                    <td></td>
                </tr>
                <?php else: ?>
                    <?php foreach($base['receipt']->sgbooklet as $booklet): ?>
                        <tr>
                            <td>
                                <input type="hidden" name="booklet_id[]" value="<?php echo e($booklet->id); ?>">
                                <input type="number" class="form-control booklet_start" value="<?php echo e($booklet->booklet_start); ?>" name="booklet_start[]"></td>
                                <td><input type="number" class="form-control booklet_end" value="<?php echo e($booklet->booklet_end); ?>" name="booklet_end[]"></td>
                                <td><a href="<?php echo e(route('delete.rcpt_booklet', $booklet->id)); ?>" class="btn btn-danger pull-right">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


    <?php if($base['receipt']->is_cancelled == 0): ?>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success btnf51" name="button" id="confirm">Update Receipt</button>
    </div>
    <?php endif; ?>

        <input type="hidden" id="form" name="form" value="<?php echo e($base['receipt']->serial->formtype->id); ?>" />
    <?php echo e(Form::close()); ?>

</div>

<div id="account_panel">
</div>
<!-- details -->
<?php if( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ): ?>
<?php echo e(Form::open(['method' => 'POST', 'route' => ['flt.detail_update'] ])); ?>

    <?php $hide_other_fees = 'hidden'; ?>
    <?php if($base['withcert']): ?>
        <?php if($base['withcert']->cert_type === 'Provincial Permit'): ?>
            <?php $hide_other_fees = ''; ?>
        <?php endif; ?>
    <?php endif; ?>
    <hr>
    <div class="form-group col-sm-2">
        <label for="type">Type</label>
        <select name="type" class="form-control" id="cert_type" required autofocus>
            <option value="" selected disabled></option>
            <?php foreach($base['rcpt_certificatetype'] as $type): ?>
                <?php if($base['withcert']): ?>
                    <?php if($base['withcert']->cert_type === $type->name): ?>
                        <option selected value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                        <?php else: ?>
                          <option  value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                    <?php endif; ?>
                <?php elseif(!is_null($base['cert'])): ?>
                    <?php if($base['cert']->col_rcpt_certificate_type_id === $type->id): ?>
                        <option selected value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                        <?php else: ?>
                          <option  value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                    <?php endif; ?>
                <?php else: ?>
                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="<?php echo e(date('m/d/Y')); ?>" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee PREPARED BY:</label>
        <select class="form-control" name="prepared_by">
                <option value="provtreasurer" selected>Provincial Treasurer</option>
                <option value="asstprovtreasurer">Local Revenue Collection Officer III</option>
                <option value="asstprovtreasurer1">Local Revenue Collection Officer I</option>   
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Treasurer Signee</label>
        <select class="form-control" name="signee">
            <option value="provtreasurer">Provincial Treasurer</option>
            <option value="asstprovtreasurer">Assistant Provincial Treasurer</option>
            <option value="asstprovtreasurer1">Assistant Provincial Treasurer 1</option>
            <option value="forinabsence">Local Treasury Operations Officer IV</option>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="signee">Provincial Governor:</label>
        <select name="prov_gov" id="prov_gov" class="form-control">
            <?php foreach($base['prov_gov'] as $gov): ?>
                <?php if(!empty($base['cert'])): ?>
                    <!-- <input type="text" class="form-control" name="prov_gov" id="prov_gov" value="<?php /* !is_null($base['cert']->provincial_governor) ? $base['cert']->provincial_governor : '' */ ?>"> -->

                    <?php if(strcasecmp($gov->officer_name, $base['cert']->provincial_governor) == 0): ?>
                        <option value="<?php echo e($gov->id); ?>" selected><?php echo e($gov->officer_name); ?></option>
                    <?php else: ?>
                        <option value="<?php echo e($gov->id); ?>"><?php echo e($gov->officer_name); ?></option>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- <input type="text" class="form-control" name="prov_gov" id="prov_gov" value="<?php /* !is_null($base['prov_gov']) ? $base['prov_gov']->officer_name : '' */ ?>"> -->

                    <?php if(strcasecmp($gov->officer_name, $base['latest_prov_gov']->officer_name) == 0): ?>
                        <option value="<?php echo e($gov->id); ?>" selected><?php echo e($gov->officer_name); ?></option>
                    <?php else: ?>
                        <option value="<?php echo e($gov->id); ?>"><?php echo e($gov->officer_name); ?></option>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        
    </div>

    <?php 
        $recipientx = '';
        if(isset($base['cert']) &&  $base['cert']->recipient != ''){
            $recipientx = $base['cert']->recipient;
        }else{
             $recipientx = $base['receipt']->customer->name ;
        }
    ?>

    <div class="form-group col-sm-6">
        <label for="recipient">Recipient</label>
        <input type="text" class="form-control" name="recipient" id="recipient" value="<?php echo e($recipientx); ?>" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="address">Address</label>
        <?php if(!empty($base['cert'])): ?>
            <input type="text" class="form-control" name="address" id="address" value="<?php echo e($base['cert']->address); ?>">
        <?php else: ?>
            <input type="text" class="form-control" name="address" id="address" value="<?php echo e($base['receipt']->customer->address); ?>">
        <?php endif; ?>
    </div>
<!-- cert details -->
<?php /* dd($base['cert']->detail) */ ?>
    <hr>
    <input type="hidden" name="cert_id" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->id : ''); ?>">
    <input type="hidden" name="receipt_id" value="<?php echo e($base['receipt']->id); ?>">
    <input type="hidden" name="receipt_serial" value="<?php echo e($base['receipt']->serial_no); ?>">
    <div class="form-group col-sm-12">
        <label for="detail">Certificate Details</label>
        <textarea id="detail" class="form-control" name="detail">
            <?php if(strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['receipt']->remarks))) != 0): ?>
                <?php //echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks).'<br>' : ''; ?>
                <?php //echo $base['receipt']->bank_remark != '' ? strip_tags($base['receipt']->bank_remark).'<br>' : ''; ?>
                <?php 
                    // if(isset($base['cert'])) {
                    //     if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->detail))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->detail))) != 0) {
                    //         echo $base['cert']->detail != '' ? strip_tags($base['cert']->detail).'<br>' : '';
                    //     }
                    // }
                ?> 

                <?php if($base['receipt']->remarks != ''): ?>
                    <?php echo $base['receipt']->remarks; ?> <br>
                <?php endif; ?>

                <?php if($base['receipt']->bank_remark != ''): ?>
                    <?php echo $base['receipt']->bank_remark; ?> <br>
                <?php endif; ?>

                <?php if(isset($base['cert'])): ?>
                    <?php if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->detail))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->detail))) != 0): ?>
                        <?php if($base['cert']->detail != ''): ?>
                            <?php echo $base['receipt']->details; ?> <br>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <?php //echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks).'<br>' : ''; ?>
                <?php 
                    // if(isset($base['cert'])) {
                    //     if(strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(strip_tags($base['cert']->detail), trim(strip_tags($base['receipt']->bank_remark))) != 0) {
                    //         echo $base['cert']->detail != '' ? $base['cert']->detail.'<br>' : ''; 
                    //     }
                    // }
                ?>

                <?php if($base['receipt']->remarks != ''): ?>
                    <?php echo $base['receipt']->remarks; ?>

                <?php endif; ?>

                <?php if(isset($base['cert'])): ?>
                    <?php if(strcasecmp(trim(strip_tags($base['cert']->detail)), trim(strip_tags($base['receipt']->remarks))) != 0): ?>
                        <?php if($base['cert']->detail != ''): ?>
                            <?php echo $base['cert']->detail; ?>

                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php /* <?php if(isset($base['cert'])): ?> */ ?>
                <?php /* $base['cert']->detail */ ?>
            <?php /* <?php endif; ?> */ ?>
        </textarea>
        <?php /* dd($base['cert']->detail) */ ?>
    </div>

    <fieldset class="hidden">
        <legend><strong>TO BE FILLED UP</strong></legend>
        <div id="provincial_permit" class="hidden addtl_div">
            <div class="form-group col-sm-6">
                <label for="prv_requestor">Requestor</label>
                <input type="text" class="form-control sand_inputs addtl_inputs" name="prv_requestor" id="prv_requestor" value="<?php echo e(!is_null($base['cert']) ? ($base['cert']->sand_requestor) : ''); ?>" required>
            </div>
            
            <div class="form-group col-sm-6">
                <label for="provincial_note">Note</label>
                <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_note" id="provincial_note" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->provincial_note : ''); ?>">
            </div>

            <div class="form-group col-sm-6">
                <label for="provincial_gov">Governor Signee</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_gov" id="provincial_gov" required>
                    <?php if(!is_null($base['cert'])): ?>
                        <?php if($base['cert']->actingprovincial_governor == null): ?>
                            <option value="1" selected>Provincial Governor</option>
                            <option value="0">Acting Provincial Governor</option>
                        <?php else: ?>
                            <option value="1">Provincial Governor</option>
                            <option value="0" selected>Acting Provincial Governor</option>
                        <?php endif; ?>
                    <?php else: ?>
                        <option value="1" selected>Provincial Governor</option>
                        <option value="0">Acting Provincial Governor</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_clearance_number">Clearance Number</label>
                <input type="text" class="form-control provincial_inputs addtl_inputs" name="provincial_clearance_number" id="provincial_clearance_number" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->provincial_clearance_number : ''); ?>">
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_type">Type</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_type" id="provincial_type" required>
                    <?php if(!is_null($base['cert'])): ?>
                        <?php if($base['cert']->provincial_type == "new"): ?>
                            <option value="new" selected>New</option>
                            <option value="renewal">Renewal</option>
                        <?php else: ?>
                            <option value="new">New</option>
                            <option value="renewal" selected>Renewal</option>
                        <?php endif; ?>
                    <?php else: ?>
                        <option value="new" selected>New</option>
                        <option value="renewal">Renewal</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group col-sm-4">
                <label for="provincial_bidding">For Bidding?</label>
                <select class="form-control provincial_inputs addtl_inputs" name="provincial_bidding" id="provincial_bidding" required>
                    <?php if(!is_null($base['cert'])): ?>
                        <?php if($base['cert']->provincial_bidding == 0): ?>
                            <option value="0" selected>No</option>
                            <option value="1">Yes</option>
                        <?php else: ?>
                            <option value="0">No</option>
                            <option value="1" selected>Yes</option>
                        <?php endif; ?>
                    <?php else: ?>
                        <option value="0" selected>No</option>
                        <option value="1">Yes</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div id="transfer_tax" class="hidden addtl_div">
            <div class="form-group col-sm-12">
                <label for="transfer_notary_public">Notary Public</label>
                <textarea class="form-control transfer_inputs addtl_inputs" name="transfer_notary_public" id="transfer_notary_public" required> <?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_notary_public : ''); ?></textarea>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_ptr_number">PTR Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ptr_number" id="transfer_ptr_number" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_ptr_number : ''); ?>" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_doc_number">Doc. Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_doc_number" id="transfer_doc_number" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_doc_number : ''); ?>" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_page_number">Page Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_page_number" id="transfer_page_number" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_page_number : ''); ?>" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_book_number">Book Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_book_number" id="transfer_book_number" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_book_number : ''); ?>" required>
            </div>

            <div class="form-group col-sm-4">
                <label for="transfer_series">Series</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_series" id="transfer_series" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_series : ''); ?>" required>
            </div>
        </div>

        <div id="sand_gravel" class="hidden addtl_div">
            <div class="form-group col-sm-6">
                <label for="sand_requestor">Requestor</label>
                <input type="text" class="form-control sand_inputs addtl_inputs" name="sand_requestor" id="sand_requestor" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->sand_requestor : ''); ?>" required>
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_requestor_addr">Requestor Address</label>
                <input type="text" class="form-control sand_inputs addtl_inputs" name="sand_requestor_addr" id="sand_requestor_addr" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->sand_requestor_addr : ''); ?>" >
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_requestor_sex">Requestor Sex</label>
                <select class="form-control sand_inputs addtl_inputs" name="sand_requestor_sex" id="sand_requestor_sex">
                    <?php if(!is_null($base['cert'])): ?>
                        <?php if($base['cert']->sand_requestor_sex == 0): ?>
                            <option value="1">Male</option>
                            <option value="0" selected>Female</option>
                        <?php else: ?>
                            <option value="1" selected>Male</option>
                            <option value="0">Female</option>
                        <?php endif; ?>
                    <?php else: ?>
                        <option value="1" selected>Male</option>
                        <option value="0">Female</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group col-sm-6">
                <label for="sand_type">Type</label>
                <select class="form-control sand_inputs addtl_inputs" name="sand_type" id="sand_type">
                    <?php if(!is_null($base['cert'])): ?>
                        <?php if($base['cert']->sand_type == 0): ?>
                            <option value="0" selected>Partial</option>
                            <option value="1">Full</option>
                        <?php else: ?>
                            <option value="0">Partial</option>
                            <option value="1" selected>Full</option>
                        <?php endif; ?>
                    <?php else: ?>
                        <option value="0" selected>Partial</option>
                        <option value="1">Full</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_sandgravelprocessed">Less: <br>Sand and Gravel (processed)</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravelprocessed" id="sand_sandgravelprocessed" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->sand_sandgravelprocessed : ''); ?>" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_abc">Less: <br>Aggregate Base Course</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_abc" id="sand_abc" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->sand_abc : ''); ?>" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_sandgravel">Less: <br>Sand and Gravel</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravel" id="sand_sandgravel" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->sand_sandgravel : ''); ?>" step="0.01" min="0" required>
            </div>

            <div class="form-group col-sm-3">
                <label for="sand_boulders">Less: <br>Boulders</label>
                <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_boulders" id="sand_boulders" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->sand_boulders : ''); ?>" step="0.01" min="0" required>
            </div>
        </div>
    </fieldset>

    <br>

    <div class="col-sm-12 other_fees_charges <?php echo e($hide_other_fees); ?>">
        <table class="table table-hovered table-bordered" id="PROVINCALFEES">
            <thead>
                <th>PROVINCAL FEES/CHARGES</th>
                <th>AMOUNT</th>
                <th>OR NUMBER</th>
                <th>DATE</th>
                <th>Initials</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php if(count($base['OtherFeesCharges']) > 0): ?>
                <?php for($x = 0 ; $x < count($base['OtherFeesCharges']) ; $x++): ?>

                <?php
                    $clearsxx = '<button id="add_row" class="btn btn-sm btn-danger" type="button" onclick="$(this).deleteMunicipalOtherFees(\''. $base['OtherFeesCharges'][$x]->id .'\');">clear</button>';
                    if( $x === 0 ){
                        $clearsxx = ' <button id="add_row_other_fees_charges" class="btn btn-sm btn-danger" type="button" onclick="$(this).deleteMunicipalOtherFees(\''. $base['OtherFeesCharges'][$x]->id .'\');">clear</button> <button id="add_rowx" onclick="$(this).addMunicipalOtherFees();" class="btn btn-sm btn-success" type="button">add</button>';
                    }
                ?>

                    <tr>
                        <td>
                            <input type="hidden" name="other_fees_id[]" value="<?php echo e($base['OtherFeesCharges'][$x]->id); ?>">
                            <input type="text" class="form-control" name="fees_charges[]" value="<?php echo e($base['OtherFeesCharges'][$x]->fees_charges); ?>"></td>
                        <td><input type="number" class="form-control"  min="0" step="0.05"  name="fees_ammount[]" value="<?php echo e($base['OtherFeesCharges'][$x]->ammount); ?>" ></td>
                        <td><input type="text" class="form-control"  name="fees_or_number[]" value="<?php echo e($base['OtherFeesCharges'][$x]->or_number); ?>"></td>
                        <td><div class="form-group"><input type="text" class="form-control datepicker"  name="other_date[]" value="<?php echo e(\Carbon\Carbon::parse($base['OtherFeesCharges'][$x]->fees_date)->format('F d, Y')); ?>" /></div></td>
                        <td><input type="text" class="form-control"  name="fees_initials[]" value="<?php echo e($base['OtherFeesCharges'][$x]->initials); ?>"></td>
                        <td><?php echo $clearsxx; ?></td>
                    </tr>
                <?php endfor; ?>
            <?php else: ?>
                 <tr>
                    <td><input type="text" class="form-control" name="fees_charges[]"></td>
                    <td><input type="number" class="form-control"  min="0" step="0.05"  name="fees_ammount[]"></td>
                    <td><input type="text" class="form-control"  name="fees_or_number[]"></td>
                    <td><div class="form-group"><input type="text" class="form-control datepicker"  name="other_date[]" /></div></td>
                    <td><input type="text" class="form-control"  name="fees_initials[]" ></td>
                    <td></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <br>
    <button class="btn btn-success pull-left" type="subkit">Update Certificate</button>
<?php echo e(Form::close()); ?>

<?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
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
    <?php echo $__env->make('collection::shared.transactions_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>