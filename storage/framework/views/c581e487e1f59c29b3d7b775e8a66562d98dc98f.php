

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php if( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ): ?>
    <div class="form-group col-sm-12">
        <?php if($base['receipt']->af_type == 2): ?>
            <?php if($base['receipt']->is_cancelled == 1): ?>
            <a href="#" class="btn btn-info" disabled>Form 56 Detail</a>
            <?php else: ?>
            <a href="<?php echo e(route('field_land_tax.f56_detail_form', ['id' =>$base['receipt']->id])); ?>" class="btn btn-info">Form 56 Detail</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($base['receipt']->is_printed == 1): ?>
            <button type="button" class="btn btn-warning pull-right" id="cancel_btn">Cancel Receipt</button>
        <?php else: ?>
            <button type="button" class="btn btn-warning pull-right" id="cancel_btn" disabled>Cancel Receipt</button>
        <?php endif; ?>

        <?php if(isset($base['cert'])): ?>
            <!-- <a class="btn btn-info" id="print_btn" href="<?php /* route('pdf.cert', ['receipt' => $base['receipt'], 'prov_gov' => 'gov']) */ ?>">Print Certificate</a> -->

            <button type="button" class="btn btn-info" id="" data-toggle="modal" data-target="#paper_size_opt">Print</button>
        <?php else: ?>
            <a class="btn btn-info disabled" href="#">Print Certificate</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

<div class="modal" id="paper_size_opt">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <span><button type="button" class="close" data-dismiss="modal">&times;</button></span>
            </div>
            <div class="modal-body">
                <form method="get" action="<?php echo e(route('pdf.cert', ['receipt' => $base['receipt'], 'prov_gov' => 'gov', 'ppr_size' => 'ppr_size'])); ?>" id="print_form">
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
            <dd><?php echo e($base['receipt']->user->realname); ?></dd>
            <dt>AF Type</dt>
            <dd><?php echo e($base['receipt']->form->name); ?></dd>
            <dt>Serial Number</dt>
            <dd><?php echo e($base['receipt']->serial_no); ?></dd>
            <dt>Payor/Customer</dt>
            <dd><?php echo e($base['receipt']->customer->name); ?></dd>
            <dt>Municipality</dt>
            <dd>
            <?php if(!empty($base['receipt']->municipality->name)): ?>
            <?php echo e($base['receipt']->municipality->name); ?>

            <?php endif; ?>
            </dd>
            <dt>Barangay</dt>
            <dd>
            <?php if(!empty($base['receipt']->barangay->name)): ?>
            <?php echo e($base['receipt']->barangay->name); ?>

            <?php endif; ?>
            </dd>
            <dt>Date</dt>
            <dd><?php echo e(date('m/d/Y', strtotime($base['receipt']->date_of_entry))); ?></dd>
            <dt>Transaction Type</dt>
            <dd><?php echo e($base['receipt']->transactiontype->name); ?></dd>
            <dt>Bank Name</dt>
            <dd><?php echo e($base['receipt']->bank_name); ?></dd>
            <dt>Number</dt>
            <dd><?php echo e($base['receipt']->bank_number); ?></dd>
            <dt>Date</dt>
            <dd><?php echo e($base['receipt']->bank_date); ?></dd>
            <dt>Remarks</dt>
            <!--<?php /* <dd><?php echo e($base['receipt']->bank_remark); ?></dd> */ ?>-->
            <dd>
                <?php if(strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['receipt']->remarks))) != 0): ?>
                    <?php echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks).'<br>' : ''; ?>
                    <?php echo $base['receipt']->bank_remark != '' ? strip_tags($base['receipt']->bank_remark).'<br>' : ''; ?>
                    <?php 
                        if(isset($base['cert'])) {
                            if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->details))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->details))) != 0) {
                                echo $base['cert']->details != '' ? strip_tags($base['cert']->details).'<br>' : '';
                            }
                        }
                    ?> 
                <?php else: ?>
                    <?php echo $base['receipt']->remarks != '' ? strip_tags($base['receipt']->remarks).'<br>' : ''; ?>
                    <?php 
                        if(isset($base['cert'])) {
                            if(strcasecmp(trim(strip_tags($base['cert']->details)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(trim(strip_tags($base['cert']->details)), trim(strip_tags($base['receipt']->bank_remark))) != 0) {
                                echo $base['cert']->details != '' ? $base['cert']->details.'<br>' : ''; 
                            }
                        }
                    ?>
                <?php endif; ?>
            </dd>
            <dt>Status</dt>
            <dd>
            <?php if($base['receipt']->is_cancelled == 1): ?>
                Cancelled
                <p><?php echo e($base['receipt']->cancelled_remark); ?></p>
            <?php else: ?>
                Issued
            <?php endif; ?>
            </dd>
        </dl>
    </div>
</div>

<div id="cancel_panel">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['field_land_tax.cancel', $base['receipt']->id]])); ?>

    <div class="form-group col-sm-12">
        <label for="bank_remark">Remark</label>
        <textarea class="form-control" name="cancel_remark" required></textarea>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" id="go">Go</button>
    </div>
    <?php echo e(Form::close()); ?>

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
        <?php foreach($base['receipt']->items as $item): ?>
        <tr>
            <td><?php echo e($item->nature); ?></td>
            <td align="right"><?php echo e(number_format($item->value, 2)); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td><b>TOTAL VALUE</b></td>
            <td align="right"><b><?php echo e(number_format($base['total'], 2)); ?></b></td>
        </tr>
    </tfoot>
</table>
</div>
</div>

<!--<?php /* <div class="form-group col-sm-12">
    <label for="remarks">Receipt Remarks</label>
    <?php if(!is_null($base['receipt']->remarks)): ?>
        <textarea id="remarks" class="form-control" name="remarks"><?php echo e($base['receipt']->remarks); ?></textarea>
    <?php else: ?>
        <textarea id="remarks" class="form-control" name="remarks"></textarea>
    <?php endif; ?>
</div> */ ?>-->

<?php if( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ): ?>
<?php echo e(Form::open(['method' => 'POST', 'route' => ['flt.detail_update'], 'id' => 'cert_submit' ])); ?>

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
                    <!-- <input class="form-control" type="text" name="prov_gov" id="prov_gov" value="<?php /* !is_null($base['cert']->provincial_governor) ? $base['cert']->provincial_governor : '' */ ?>"> -->

                    <?php /* <?php if(strcasecmp($gov->officer_name, $base['cert']->provincial_governor) == 0): ?> */ ?>
                    <?php if(strcasecmp($gov->officer_name, $base['latest_prov_gov']->officer_name) == 0): ?>
                        <option value="<?php echo e($gov->id); ?>" selected><?php echo e($gov->officer_name); ?></option>
                    <?php else: ?>
                        <option value="<?php echo e($gov->id); ?>"><?php echo e($gov->officer_name); ?></option>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- <input class="form-control" type="text" name="prov_gov" id="prov_gov" value="<?php /* !is_null($base['prov_gov']) ? $base['prov_gov']->officer_name : '' */ ?>"> -->

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
        <?php if(!empty($base['cert']->address)): ?>
            <input type="text" class="form-control" name="address" id="address" value="<?php echo e($base['cert']->address); ?>" >
        <?php else: ?>
            <input type="text" class="form-control" name="address" id="address" value="<?php echo e($base['receipt']->customer->address); ?>" >
        <?php endif; ?>
    </div>

    <div class="form-group col-sm-3">
        <label>Include Entries From</label>
        <input type="text" class="form-control datepicker" name="incl_date_frm" value="<?php echo e(isset($base['cert']->include_from) ? \Carbon\Carbon::parse($base['cert']->include_from)->format('m/d/Y') : ''); ?>">
    </div>
    <div class="form-group col-sm-3">
        <label>Include Entries To</label>
        <input type="text" class="form-control datepicker" name="incl_date_to" value="<?php echo e(isset($base['cert']->include_to) ? \Carbon\Carbon::parse($base['cert']->include_to)->format('m/d/Y') : ''); ?>">
    </div>
    

<!-- cert details -->
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
                    //     if(strcasecmp(trim(strip_tags($base['receipt']->remarks)), trim(strip_tags($base['cert']->details))) != 0 && strcasecmp(trim(strip_tags($base['receipt']->bank_remark)), trim(strip_tags($base['cert']->details))) != 0) {
                    //         echo $base['cert']->details != '' ? strip_tags($base['cert']->details).'<br>' : '';
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
                    //     if(strcasecmp(trim(strip_tags($base['cert']->details)), trim(strip_tags($base['receipt']->remarks))) != 0 || strcasecmp(trim(strip_tags($base['cert']->details)), trim(strip_tags($base['receipt']->bank_remark))) != 0) {
                    //         echo $base['cert']->details != '' ? $base['cert']->details.'<br>' : ''; 
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
        </textarea>
    </div>

    <fieldset class="hidden">
        <legend><strong>TO BE FILLED UP</strong></legend>
        <div id="provincial_permit" class="hidden addtl_div">
            <div class="form-group col-sm-6">
                <label for="sand_requestor">Requestor</label>
                <input type="text" class="form-control addtl_inputs" name="sand_requestor" id="sand_requestor" value="<?php echo e(!is_null($base['cert']) ? ($base['cert']->sand_requestor) : ''); ?>">
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
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ptr_number" id="transfer_ptr_number" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_ptr_number : ''); ?>">
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

            <div class="form-group col-sm-4">
                <label for="">Reference Number</label>
                <input type="text" class="form-control transfer_inputs addtl_inputs" name="transfer_ref_num" id="transfer_ref_num" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->transfer_ref_num : ''); ?>">
            </div>
        </div>

        <div id="sand_gravel" class="hidden addtl_div">
            <div class="form-group col-sm-6">
                <label for="sand_requestor">Requestor</label>
                <input type="text" class="form-control addtl_inputs" name="sand_requestor" id="sand_requestor" value="<?php echo e(!is_null($base['cert']) ? $base['cert']->sand_requestor : ''); ?>" >
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
    <button class="btn btn-success pull-right" type="submit" id="confirm">Update</button>
<?php echo e(Form::close()); ?>

<?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>