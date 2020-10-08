

<?php $__env->startSection('css'); ?>
<style>
hr{
    border-top: 1px solid #881d1d;
}
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


.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ): ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['form56.store']])); ?>

    <input type="hidden" name="with_cert" value="null" />
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['user']->realname); ?></dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo e($base['user']->id); ?>">
        <input type="hidden" class="form-control" name="transaction_source" id="transaction_source" value="field_land_tax">
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <div class="col-md-4">
            <label for="date">AUTO TIMER</label>
        </div>
            <div class="col-md-4">
                <input type="checkbox" class="form-control " value="true" checked="" name="auto_timer" id="auto_timer" />
            </div>
        </div>
    </div>

    <div class="form-group col-sm-4">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" id="date_timex"  required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="user">AF Type</label>

        <select class="form-control" id="form" name="form"  readonly>
            <?php foreach($base['form'] as $form): ?>
                <?php if( $form->id == '2'): ?>
                    <option value="<?php echo e($form->id); ?>" selected><?php echo e($form->name); ?></option>
                <?php endif; ?>

            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="serial_id">Series</label>
        <select class="form-control" name="serial_id" id="serial_id"  required>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" required>
        <input type="hidden" class="form-control" name="customer_id" id="customer_id">
    </div>
    <!-- <div class="form-group col-sm-4">
        <label for="customer_type">View Previous Client Type/s <small id="client_type_msg" style="color: red;"></small></label>
        <select class="form-control" id="customer_type"> -->
            <!-- <option></option> -->
            <?php /* <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?> */ ?>
                <!-- <option value="<?php /* $sandgravel_types['id'] */ ?>"><?php /* $sandgravel_types['description'] */ ?></option> -->
            <?php /* <?php endforeach; ?> */ ?>
        <!-- </select>
    </div> -->
    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
        <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
        <small id="client_type_msg" style="color: red;"></small>
        <select class="form-control" name="customer_type" id="customer_type">
        <!-- <select class="form-control" name="customer_type" id="new_customer_type"> -->
            <option></option>
            <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?>
                <option value="<?php echo e($sandgravel_types['id']); ?>"><?php echo e($sandgravel_types['description']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="municipality">Municipality</label>
        <input type="hidden" name="municipality" id="municipality"  >
        <input type="text" class="form-control" name="municipality_name" id="municipality_name" readonly >
        <input type="hidden" name="municipality_code" id="municipality_code">
    </div>


    <div class="form-group col-sm-4">
        <label for="user">Transaction Type</label>
        <select class="form-control" id="transaction_type" name="transaction_type" required>
            <!-- <option selected ></option> -->
            <?php foreach($base['transaction_type'] as $transaction_type): ?>
                <option value="<?php echo e($transaction_type->id); ?>"><?php echo e($transaction_type->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_name">Bank Name</label>
        <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" >
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_number">Number</label>
        <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" >
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_date">Date</label>
        <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" >
    </div>

    <div class="form-group col-sm-4">


                    <!-- should be inserted with a new coloumn on recipt or new table check m ulit ung  paginsert  -->

        <label for="bank_remark">Tax Type</label>
        <select class="form-control" name="previous_tax_type">
            <option value="0">Number</option>
            <option value="1">RPT Billing</option>
            <option value="2">New Owner</option>
            <option value="3">New Owner w/ back taxes</option>
            <option value="4">Newly Decared w/ back taxes</option>
            <option value="5">Collected by MTO</option>
            <option value="6">Collected by PTO</option>
        </select>
    </div>

    <div class="form-group col-sm-12">
        <label for="bank_remark">Remarks <!-- <button class="btn btn-sm btn-warning" type="button" title="Please indicate if 'Paid under protest' or 'Held in trust' when necessary"><i class="fa fa-info-circle"></i></button> --></label>
        <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types.
Please indicate if 'Paid under protest' or 'Held in trust' when necessary."><i class="fa fa-info-circle"></i> NOTE</small> <br>
        <small id="info_bank_rem" style="color: red;"></small>
        <!-- <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value=""> -->
        <textarea class="form-control bank_input" name="bank_remark" id="bank_remark"></textarea>
    </div>

    <!-- <div class="form-group col-sm-12">
        <label for="remarks">Receipt Remarks</label>
        <textarea id="remarks" class="form-control" name="remarks"></textarea>
    </div> -->

    <div class="col-sm-12">
    <hr />
    <h3>Previous Receipt Details <small style="color: red;" id="prev_rcpt_info"></small> </h3>
    <div class="form-group col-sm-3">
        <label for="bank_date">Prev-Receipt NO.</label>
        <input type="text" class="form-control" id="prev_receipt_no" name="prev_receipt_no" value="">
    </div>
    <div class="form-group col-sm-3">
        <label for="bank_date">Prev-Date</label>
        <input type="text" class="form-control datepicker2" id="prev_date" name="prev_date" value="">
    </div>
    <div class="form-group col-sm-3">
        <label for="prev_for_the_year">For the YEAR</label>
        <input type="text" class="form-control"  id="prev_for_the_year" name="prev_for_the_year" value="">
    </div>
    <div class="form-group col-sm-3">
        <label for="prev_tdarp">Tax Declaration No. <i class="fa fa-info-circle" title="This field is required but will not appear on the receipt. The system will search for the previous receipt based on the Tax Declaration No. provided below"></i> Note</label>
        <input type="text" class="form-control"  id="prev_tdarp" name="prev_tdarp" value="">
    </div>
    <br>
    <div class="form-group">
        <label>Previous Receipt Remarks</label>
        <textarea type="text" name="prev_remarks" rows="2" class="form-control"></textarea>
    </div>
    <hr />
</div>

    <div class="col-sm-12">
         <hr />
          <div class="form-group col-sm-6">
                <label for="period_covered">View TDRP By TAX DEC NO.</label>
                <input type="text" class="form-control" name="tax_dec_no_bms" id="tax_dec_no_bms" value=""><br/>
                <button type="button" class="btn btn-sm btn-primary" onclick="$(this).bms_showTDRP();" >GO</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="$(this).bms_showTDRPclear();" >Clear</button>
            </div>
    </div>

    <div class="form-group col-sm-12 table-responsive">
            <div id="mncpal_brgy_code_error"  ></div>
            <button id="add_row_form56" class="btn btn-sm btn-success pull-right" type="button"><i class="fa fa-plus"></i> TDRP FIELD</button>
        <table class="table" id="tablex">
            <thead>
                <tr>
                    <th>Declared Owner  </th>
                    <th>TD/ARP No.  </th>
                    <th>BARANGAY</th>
                    <th>Classification</th>
                    <th>Assessment Value</th>
                    <!-- <th>Tax Due</th> --> <!-- for arp's 93 and below only -->
                    <th>Period Covered</th>
                    <th>Full/Partial</th>
                    <th>Current/Advance Year Gross Amt.</th>
                    <th>Discount</th>
                    <th>Previous Year/s</th>
                    <th>Penalty for Current Year</th>
                    <th>Penalty for Previous Year/s</th>
                    <th>SEF</th>
                    <th>BASIC</th>
                    <th>Grand Total (net)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control declared_owner" name="declared_owner[]" required data-default-w="100">
                    </td>
                    <td>
                        <input type="text" class="form-control tdarpno" name="tdarpno[]" required data-default-w="74" style="display: inline;">
                    </td>
                    <td>
                        <select class="form-control tdrp_barangay" name="tdrp_barangay[]" id="tdrp_barangay[]" data-default-w="80"  >
                           <option selected></option>
                            <?php foreach($base['brgys'] as $key => $brgy): ?>
                                <option value="<?php echo e($brgy['id']); ?>"><?php echo e($brgy['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-control f56_type" id="f56_type[]" name="f56_type[]" required  data-default-w="92">
                            <option selected ></option>
                            <?php foreach($base['f56_types'] as $type): ?>
                                <option  value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="text" class="form-control tdrp_assedvalue" name="tdrp_assedvalue[]"  name="tdrp_assedvalue[]" data-default-w="83" ></td>
                    <!-- <td><input type="text" class="form-control tdrp_taxdue" name="tdrp_taxdue[]" data-default-w="83" readonly></td> --> <!-- for arp's 93 and below only -->
                    <td><input type="text" class="form-control period_covered" name="period_covered[]" value="<?php echo e(date('Y')); ?>" required data-default-w="67"></td>
                    <td>
                        <select class="form-control full_partial" id="full_partial[]" name="full_partial[]" required  data-default-w="72">
                            <option value="0" selected >Full</option>
                            <option value="1" >Partial - 1st Quarter</option>
                            <option value="2" >Partial - 2nd Quarter</option>
                            <option value="3" >Partial - 3rd Quarter</option>
                            <option value="4" >Partial - 4th Quarter</option>
                            <option value="5" >Partial Advance</option>
                            <option value="6" >Balance Settlement</option>
                            <option value="7" >Backtax</option>
                            <option value="8" >Additional Payment</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control basic_current" name="basic_current[]" value="0" min="0" step="0.01" required data-default-w="40" ></td>
                    <td><input type="number" class="form-control basic_discount" name="basic_discount[]" value="0" min="0" step="0.01" required data-default-w="40"></td>
                    <td><input type="number" class="form-control basic_previous" name="basic_previous[]" value="0" min="0" step="0.01" required data-default-w="40"></td>
                    <td><input type="number" class="form-control basic_penalty_current" name="basic_penalty_current[]" value="0" min="0" step="0.01" required data-default-w="40"></td>
                    <td><input type="number" class="form-control basic_penalty_previous" name="basic_penalty_previous[]" value="0" min="0" step="0.01" required data-default-w="40"></td>

                    <td class="sefxx"></td>
                    <td class="basicxx"></td>
                    <td class="grand_total_net"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th colspan="2">Account</th>
                    <th class="td_nature">Nature</th>
                    <th>Amount</th>
                    <th></th>
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
                <tr>
                    <td>
                        <input type="text" class="form-control account" required >
                        <input type="hidden" class="form-control" name="account_id[]">
                        <input type="hidden" class="form-control" name="account_type[]">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]"  readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" >Select</button>
                        <input type="hidden" class="form-control">
                        <input type="hidden" class="form-control" name="account_rate[]" value="0">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" maxlength="300" required   readonly>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]"  step="0.01" required   readonly>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br/>

    <div class="form-group col-sm-12">
        <?php if(isset($base['serial']->serial_begin)): ?>
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
        <?php else: ?>
        <button type="submit" class="btn btn-success" name="button" id="confirm" >Add</button>
        <?php endif; ?>
    </div>
    <?php echo e(Form::close()); ?>

</div>
<hr>

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



<?php endif; ?>
<?php if( Session::get('permission')['col_field_land_tax'] & $base['can_read'] ): ?>
<form class="form-inline">
  <div class="form-group">
    <label for="show_year">YEAR</label>
    <input type="number" min="2017" max="<?php echo e(date('Y')); ?>" class="form-control" id="show_year" placeholder="<?php echo e(date('Y')); ?>" value="<?php echo e(date('Y')); ?>">
  </div>
<button type="button" class="btn btn-default" onclick="$(this).loadTable();">SHOW</button>
<a href="<?php echo e(route('rpt.delinquent')); ?>" class="btn btn-info pull-right" id="delinquent" name="delinquent">Delinquent Tax Payers</a>
</form>
<br>
<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>User</th>
            <th>Municipality</th>
            <th>Brgy</th>
            <th>Serial</th>
            <th>Date</th>
            <th>Customer/Payor</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('collection::form56/form56_rules', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('collection::form56/js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('collection::shared/transactions_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <script type="text/javascript">
        // $(document).on('change', '#customer', function() {
        //     console.log('tdarp '+$('#prev_tdarp').val());
        //     console.log('customer '+$('#customer').val());
        //     $('#prev_tdarp').css('box-shadow', 'none');
        //     $('#customer').css('box-shadow', 'none');
        //     if($('#prev_tdarp').val() != "" && $('#customer').val() != "") {
        //         $('#prev_tdarp').css('box-shadow', '1px 1px 15px #f5802c');
        //         $('#customer').css('box-shadow', '1px 1px 15px #f5802c');
        //     }
        // });

        // $(document).on('change', '#prev_tdarp', function() {
        //     console.log('tdarp '+$('#prev_tdarp').val());
        //     console.log('customer '+$('#customer').val());
        //     $('#prev_tdarp').css('box-shadow', 'none');
        //     $('#customer').css('box-shadow', 'none');
        //     if($('#prev_tdarp').val() != "" && $('#customer').val() != "") {
        //         $('#prev_tdarp').css('box-shadow', '1px 1px 15px #f5802c');
        //         $('#customer').css('box-shadow', '1px 1px 15px #f5802c');
        //     }
        // });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>