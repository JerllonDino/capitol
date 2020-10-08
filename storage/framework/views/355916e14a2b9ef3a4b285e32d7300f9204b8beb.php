

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

    .remove_existing_tdrp{
        background: #bf8383;
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
    <div class="col-md-12">
        <a class="btn btn-info hidden" href="<?php echo e(route('pdf.land_tax_collection',['sign',$base['receipt']->id])); ?>">Print w/ signatories</a>
        <a class="btn btn-info hidden" href="<?php echo e(route('pdf.land_tax_collection',['nsign',$base['receipt']->id])); ?>">Print w/o Signatories</a>
           <?php if( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ): ?>
                    <a href="<?php echo e(route('form56.view','')); ?>/<?php echo e($base['receipt']->id); ?>" class="btn  btn-info datatable-btn" title="Edit"><i class="fa fa-eye"></i></a>
            <?php endif; ?>

    </div>

    <?php echo $__env->make('collection::form56/settings/form_print', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php echo e(Form::open(['method' => 'POST', 'route' => ['form56.update',$base['receipt_id']]])); ?>

            <input type="hidden" name="isEdit" id="isEdit" value="isEdit">
            <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['receipt']->user->realname); ?>

                <input type="hidden" name="receipt_id" id="receipt_id" value="<?php echo e($base['receipt_id']); ?>" >
            </dd>
            <dt>AF Type</dt>
            <dd><?php echo e($base['receipt']->form->name); ?></dd>
            <dt>Serial Number</dt>
            <dd><?php echo e($base['receipt']->serial_no); ?></dd>
            <dt>Payor/Customer</dt>`
            <dd>
                 <div class="form-group col-sm-4">
                    <input type="text" class="form-control" name="customer" id="customer" value="<?php echo e($base['receipt']->customer->name); ?>" required>
                    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo e($base['receipt']->customer->id); ?>" >
                </div>

            </dd>
            <dt>Municipality</dt>
            <dd>
            <?php if(!empty($base['receipt']->serial->municipality->name)): ?>
            <?php echo e($base['receipt']->serial->municipality->name); ?>

                <input type="hidden" name="municipality" id="municipality" value="<?php echo e($base['receipt']->serial->municipality->id); ?>" data-code="<?php echo e($base['receipt']->serial->municipality->code); ?>" >
            <?php endif; ?>
            </dd>
            <!-- <dt>Barangay</dt>
            <dd> -->
            <!-- <?php if(!empty($base['receipt']->barangay->name)): ?>
            <?php echo e($base['receipt']->barangay->name); ?>

            <?php endif; ?> -->
            <!-- </dd> -->
            <dt>Date</dt>
            <dd>
                <div class="form-group col-sm-4">
                    <input type="text" class="form-control datepicker" name="date" id="date_timex" value="<?php echo e(date('m/d/Y  H:i:s', strtotime($base['receipt']->date_of_entry))); ?>"  required autofocus>
                </div>
            </dd>
            <!-- <dt>Report Date</dt>
            <dd>
                <div class="form-group col-sm-4">
                    <input type="text" class="form-control datepicker" name="report_date" id="date_timex" value="<?php /* date('m/d/Y', strtotime($base['receipt']->report_date)) */ ?>"  required autofocus>
                </div>
            </dd> -->
            <dt>Transaction Type</dt>
            <dd>
                     <div class="form-group col-sm-4">
                        <select class="form-control" id="transaction_type" name="transaction_type" required>
                            <!-- <option selected ></option> -->
                            <?php foreach($base['transaction_type'] as $transaction_type): ?>
                                <?php if($base['receipt']->transactiontype->id == $transaction_type->id ): ?>
                                    <option value="<?php echo e($transaction_type->id); ?>" selected><?php echo e($transaction_type->name); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo e($transaction_type->id); ?>"><?php echo e($transaction_type->name); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </dd>
            <dt>Bank Name</dt>
            <dd>
                <div class="form-group col-sm-4">
                    <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="<?php echo e($base['receipt']->bank_name); ?>" >
                </div>
            </dd>
            <dt>Number</dt>
            <dd>
                <div class="form-group col-sm-4">
                    <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="<?php echo e($base['receipt']->bank_number); ?>" >
                </div>
            </dd>
            <dt>Date</dt>
            <dd>
                <div class="form-group col-sm-4">
                    <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="<?php echo e($base['receipt']->bank_date); ?>" >
                </div>
            </dd>
            <?php
                $tax_types = ['Number', 'RPT Billing', 'New Owner', 'New Owner w/ Back Taxes', 'Newly Decared w/ back taxes', 'Collected by MTO', 'Collected by PTO'];
            ?>
            <dt>Tax Type</dt>
            <dd>
                <div class="form-group col-sm-4">
                    <select class="form-control" name="previous_tax_type">
                        <?php foreach($tax_types as $i => $type): ?>
                            <?php if(!is_null($base['receipt_tdarp'])): ?>
                                <?php if($i == $base['receipt_tdarp']->previous_tax_type_id): ?>
                                    <option value="<?php echo e($i); ?>" selected><?php echo e($type); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($type); ?></option>
                                <?php endif; ?>
                            <?php else: ?>
                                <option value="<?php echo e($i); ?>"><?php echo e($type); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </dd>
            <dt>Remark <i class="fa fa-info-circle" title="Please indicate if 'Paid under protest' or 'Held in trust' when necessary"></i></dt>
            <dd>
                  <div class="form-group col-sm-12">
                    <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value="<?php echo e($base['receipt']->bank_remark); ?>" >
                </div>

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

           <div class="col-sm-12">
    <hr />
    <h3>Previous Receipt Details</h3>

    <?php  
           // dd($base['receipt']->F56Previuos);
     ?>
     <div class="form-group col-sm-4">
        <label for="bank_date">Prev-Receipt NO.</label>
        <input type="text" class="form-control" id="prev_receipt_no" name="prev_receipt_no" value="<?php echo e($base['receipt']->F56Previuos->col_receipt_no ?? 'x'); ?>" >
    </div>
    <div class="form-group col-sm-4">
        <label for="bank_date">Prev-Date</label>
        <input type="text" class="form-control datepicker2" id="prev_date" name="prev_date" value="<?php echo e($base['receipt']->F56Previuos->col_receipt_date ?? ''); ?>" >
    </div>

    <div class="form-group col-sm-4">
        <label for="prev_for_the_year">For the YEAR</label>
        <input type="text" class="form-control"  id="prev_for_the_year" name="prev_for_the_year" value="<?php echo e($base['receipt']->F56Previuos->col_receipt_year ?? ''); ?>" >
    </div>

    <div class="form-group col-sm-12">
        <label>Previous Receipt Remarks</label>
        <textarea type="text" name="prev_remarks" rows="2" class="form-control" value="<?php echo e($base['receipt']->F56Previuos->col_prev_remarks ?? ''); ?>"></textarea>
    </div>
</div>



    <div class="form-group col-sm-12">
            <div id="mncpal_brgy_code_error"  ></div>
    <hr />
     <div class="form-group col-sm-12">
            <div id="mncpal_brgy_code_error"  ></div>
            <button id="add_row_form56" class="btn btn-sm btn-success pull-right" type="button"><i class="fa fa-plus"></i> TDRP FIELD</button>
        <table class="table" id="tablex">
            <thead>
                <tr>
                    <th>Declared Owner  </th>
                    <th>TD/ARP No. </th>
                    <th>BARANGAY</th>
                    <th>Classification</th>
                    <th>Assessment Value</th>
                    <!-- <th>Tax Due</th> --> <!-- for arp's 93 and below only -->
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
                <?php foreach( $base['receipt']->F56Detailmny as $key => $F56Detail ): ?>

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

                    <td>
                        <input type="hidden" class="f56_detail_deleted" name="f56_detail_deleted[]" id="f56_detail_deleted[]" value="false" >
                        <input type="hidden" name="f56_detail_id[]" id="f56_detail_id[]" value="<?php echo e($F56Detail->id); ?>" >
                        <input type="hidden" class="form-control tdarpno_id" name="tdarpno_id[]" value="<?php echo e($F56Detail->TDARPX ? $F56Detail->TDARPX->id : ''); ?>" required>
                        <input type="text" class="form-control declared_owner" name="declared_owner[]" value="<?php echo e($F56Detail->owner_name ??   ''); ?>" required>

                    </td>
                    <td>
                        <input type="text" class="form-control tdarpno" name="tdarpno[]" value="<?php echo e($F56Detail->TDARPX ?  $F56Detail->TDARPX->tdarpno : ''); ?>" required>
                    </td>


                    <td>
                        <select class="form-control tdrp_barangay" name="tdrp_barangay[]" id="tdrp_barangay[]"  >
                              <?php foreach($base['barangay'] as $brgy): ?>
                                <?php if($F56Detail->TDARPX && $F56Detail->TDARPX->barangay == $brgy->id ): ?>
                                    <option data-code="<?php echo e($brgy->code); ?>" value="<?php echo e($brgy->id); ?>" selected><?php echo e($brgy->name); ?></option>
                                <?php else: ?>
                                    <option data-code="<?php echo e($brgy->code); ?>" value="<?php echo e($brgy->id); ?>"><?php echo e($brgy->name); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-control f56_type" id="f56_type[]" name="f56_type[]" required>
                            <option selected ></option>
                            <?php foreach($base['f56_types'] as $type): ?>
                                <?php if($F56Detail->TDARPX &&  $F56Detail->TDARPX->f56_type == $type->id ): ?>
                                    <option value="<?php echo e($type->id); ?>" selected ><?php echo e($type->name); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                <?php endif; ?>


                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="text" class="form-control tdrp_assedvalue" value="<?php echo e(($F56Detail->tdrp_assedvalue)); ?>" name="tdrp_assedvalue[]"  name="tdrp_assedvalue[]" ></td>
                    <!-- <td><input type="text" class="form-control tdrp_taxdue" name="tdrp_taxdue[]" data-default-w="83" readonly></td> --> <!-- for arp's 93 and below only -->
                    <td><input type="text" class="form-control period_covered" name="period_covered[]" value="<?php echo e(($F56Detail->period_covered)); ?>" required></td>
                    <td>
                        <select class="form-control full_partial" id="full_partial[]" name="full_partial[]" required>
                            <option value="0" <?php  echo $F56Detail->full_partial == 0 ?  'selected' : '' ;?> >Full</option>
                            <option value="1" <?php  echo $F56Detail->full_partial == 1 ?  'selected' : '' ;?> >Partial - 1st Quarter</option>
                            <option value="2" <?php  echo $F56Detail->full_partial == 2 ?  'selected' : '' ;?> >Partial - 2nd Quarter</option>
                            <option value="3" <?php  echo $F56Detail->full_partial == 3 ?  'selected' : '' ;?> >Partial - 3rd Quarter</option>
                            <option value="4" <?php  echo $F56Detail->full_partial == 4 ?  'selected' : '' ;?> >Partial - 4th Quarter</option>
                            <option value="5" <?php  echo $F56Detail->full_partial == 5 ?  'selected' : '' ;?> >Partial Advance</option>
                            <option value="6" <?php  echo $F56Detail->full_partial == 6 ?  'selected' : '' ;?> >Balance Settlement</option>
                            <option value="7" <?php  echo $F56Detail->full_partial == 7 ?  'selected' : '' ;?> >Backtax</option>
                            <option value="8" <?php  echo $F56Detail->full_partial == 8 ?  'selected' : '' ;?> >Additional Payment</option>
                        </select>
                        <?php if(!is_null($F56Detail->ref_num)): ?>
                            <div id="ref_num_input">
                                <?php if($F56Detail->full_partial == 6): ?>
                                <i class="fa fa-info-circle" title="For Balance Settlements, please specify the OR number with partial payment that this payment would settle"></i>&nbsp;Referred OR&nbsp;
                                <?php elseif($F56Detail->full_partial == 8): ?>
                                <i class="fa fa-info-circle" title="For Additional Payments, please specify the OR number with incomplete payment that this payment would settle"></i>&nbsp;Referred OR&nbsp;
                                <?php endif; ?>
                                <input class="form-control" type="number" id="ref_num" name="ref_num[]" value="<?php echo e($F56Detail->ref_num); ?>"></input>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><input type="number" class="form-control basic_current" name="basic_current[]" value="<?php echo e(($F56Detail->basic_current)); ?>" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control basic_discount" name="basic_discount[]" value="<?php echo e(($F56Detail->basic_discount)); ?>" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control basic_previous" name="basic_previous[]" value="<?php echo e(($F56Detail->basic_previous)); ?>" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control basic_penalty_current" name="basic_penalty_current[]" value="<?php echo e(($F56Detail->basic_penalty_current)); ?>" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control basic_penalty_previous" name="basic_penalty_previous[]" value="<?php echo e(($F56Detail->basic_penalty_previous)); ?>" min="0" step="0.01" required></td>

                    <?php 
                        $sef = $F56Detail->basic_current - $F56Detail->basic_discount + $F56Detail->basic_previous + $F56Detail->basic_penalty_current + $F56Detail->basic_penalty_previous;
                     ?>

                    <td class="sefxx"><?php echo e($sef); ?></td>
                    <td class="basicxx"><?php echo e($sef); ?></td>
                    <td class="grand_total_net"><?php echo e($sef*2); ?></td>
                     <td>
                        <?php if($key > 0): ?>
                            <button type="button" class="btn btn-warning btn-sm rem_row_existing"><i class="fa fa-minus"></i></button>
                        <?php endif; ?>

                     </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
</div>
    <hr />

   <table class="table">
    <thead>
        <tr>
            <th>Account</th>
            <th>Nature</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($base['receipt']->items as $item): ?>

        <input type="hidden" name="account_id[]" value="<?php echo e($item->col_acct_title_id); ?>">
        <input type="hidden" name="account_type[]" value="title">
        <input type="hidden" class="form-control account_is_shared" name="account_is_shared[]" value="0">
        <input type="hidden" class="form-control" name="account_rate[]" value="0">

        <tr>
            <td>Real Property Tax-Basic (Net of Discount)</td>
            <td><input type="text" class="form-control" name="nature[]" required="required" maxlength="300" value="<?php echo e($item->nature); ?>" ></td>
            <td align="right"><input type="number" min="0" step=".01" class="form-control amounts" name="amount[]" required="required" value="<?php echo e($item->value); ?>"> </td>
        </tr>
        <?php endforeach; ?>
    </tbody>

</table>
    </div>


    <br/>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-info btn-sm">UPDATE</button>
    </div>
</div>
 <?php echo e(Form::close()); ?>

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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('collection::form56/form56_rules', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('collection::form56/js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('collection::shared/transactions_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            // $('.tdrp_assedvalue, .period_covered, .full_partial .tdrp_taxdue').focus(function(){
                $('.tdrp_assedvalue, .period_covered, .full_partial').focus(function(){
                var el_index_x = $('.tdrp_assedvalue').index(this);
                var el_index_y = $('.period_covered').index(this);
                var el_index_z = $('.full_partial').index(this);

                var el_index = $.fn_index_of_max_3(el_index_x,el_index_y,el_index_z);

                var arp = $('.tdarpno').eq(el_index).val();
                var split = arp.split("-");

                if($('.tdarpno').eq(el_index).val() != '' && $('.tdrp_assedvalue').eq(el_index).val() != '' && split[0] >= 94){
                  $('.sefxx').eq(el_index).empty();
                  $('.basicxx').eq(el_index).empty();
                  $('.grand_total_net').eq(el_index).empty();
                    var dx = [];
                        var period_covered = $('.period_covered').eq(el_index);
                        var assessed_value = $('.tdrp_assedvalue').eq(el_index);
                                $.ajax({
                                url: "<?php echo e(route('form56.form56_compute_benedict')); ?>",
                                type: 'POST',
                                data:{
                                  '_token' : '<?php echo e(csrf_token()); ?>',
                                  'assessed_value' :  parseFloat(assessed_value.val()),
                                  'type_p' :  $('.full_partial').eq(el_index).val(),
                                  'p_years' :  period_covered.val(),
                                  'tdrpno' :  $('.tdarpno').eq(el_index).val(),
                                },
                                dataType: 'json',
                                success: (data) => {
                                     var sef_basic = parseFloat(data['basic_current'].toFixed(2)) - parseFloat(data['basic_discount'].toFixed(2)) + parseFloat(data['basic_previous'].toFixed(2)) + parseFloat(data['basic_penalty_current'].toFixed(2)) + parseFloat(data['basic_penalty_previous'].toFixed(2));
                                     var tax_due = parseFloat(assessed_value.val())*.01;
                                     // console.log(sef_basic);
                                     $('.basic_current').eq(el_index).val(data['basic_current'].toFixed(2));
                                     $('.basic_discount').eq(el_index).val(data['basic_discount'].toFixed(2));
                                     $('.basic_previous').eq(el_index).val(data['basic_previous'].toFixed(2));
                                     $('.basic_penalty_current').eq(el_index).val(data['basic_penalty_current'].toFixed(2));
                                     $('.basic_penalty_previous').eq(el_index).val(data['basic_penalty_previous'].toFixed(2));
                                     var grand_total_net = parseFloat(sef_basic.toFixed(2)) *2;
                                     $('.sefxx').eq(el_index).text(sef_basic.toFixed(2));
                                     $('.basicxx').eq(el_index).text(sef_basic.toFixed(2));
                                     $('.grand_total_net').eq(el_index).text(grand_total_net.toFixed(2));
                                     // $('.tdrp_taxdue').eq(el_index).val(tax_due.toFixed(2));

                                      $.fn.computeAmountTotal();
                                }
                            });
                } else if(split[0] < 94) {
                  $('.basic_current').eq(el_index).val(0);
                  $('.basic_discount').eq(el_index).val(0);
                  $('.basic_previous').eq(el_index).val(0);
                  $('.basic_penalty_current').eq(el_index).val(0);
                  $('.basic_penalty_previous').eq(el_index).val(0);

                  $('.sefxx').eq(el_index).text(0);
                  $('.basicxx').eq(el_index).text(0);
                  $('.grand_total_net').eq(el_index).text(0);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>