

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
    #sand_blk {
        display: none;
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
<?php if( Session::get('permission')['col_receipt'] & $base['can_write'] ): ?>

<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['receipt.another_save', $base['receipt']->id]])); ?>

    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['user']->realname); ?></dd>
			<dt>Form</dt>
            <dd><?php echo e($base['receipt']->serial->formtype->name); ?></dd>
			<dt>PARENT Serial Number</dt>
            <dd><?php echo e($base['receipt']->serial_no); ?></dd>
        </dl>
        <input type="hidden" class="form-control" name="receipt_id" id="receipt_id" value="<?php echo e($base['receipt_id']); ?>">
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo e($base['user']->id); ?>">
        <input type="hidden" class="form-control" name="transaction_source" id="transaction_source" value="receipt">
        <input type="hidden" class="form-control" name="serial_idx" id="serial_idx" value="<?php echo e($base['receipt']->serial->id); ?>">
    </div>

    <div class="form-group col-sm-4">
        <label for="date">Date</label>
		<input type="text" class="form-control datepicker" name="date" value="<?php echo e(date('m/d/Y  H:i:s')); ?>" required autofocus>
    </div>

     <div class="form-group col-sm-4">
        <label for="user">AF Type</label>
        <select class="form-control" id="form" name="form">
            <option value="1" selected>Form 51</option>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="serial_id">Series</label>
        <select class="form-control" name="serial_id" id="serial_id"  required>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer"  value="<?php echo e($base['receipt']->customer->name); ?>"  disabled />
        <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="<?php echo e($base['receipt']->customer->id); ?>">
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

	<div class="form-group col-sm-4">
        <label for="bank_remark">Remark</label>
        <?php if($base['receipt']->bank_remark == ''): ?>
            <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value="" disabled>
        <?php else: ?>
            <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value="<?php echo e($base['receipt']->bank_remark); ?>">
        <?php endif; ?>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_to_remarks">&nbsp;</label>
        <?php if($base['receipt']->bank_name == ''): ?>
        <input type="button" id="bank_to_remarks" name="bank_to_remarks" class="form-control btn btn-info" value="Add Bank to Receipt Remarks" disabled>
        <?php else: ?>
        <input type="button" id="bank_to_remarks" name="bank_to_remarks" class="form-control btn btn-info" value="Add Bank to Receipt Remarks">
        <?php endif; ?>
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
                <tr>
                    <td>
                       <input type="text" class="form-control account ui-autocomplete-input" required="required" autocomplete="off">
                       <input type="hidden" class="form-control" name="account_id[]">
                       <input type="hidden" class="form-control" name="account_type[]">
                       <input type="hidden" class="form-control account_is_shared" name="account_is_shared[]" value="0">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                        <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                    </td>
                    <td>
                       <input type="text" class="form-control nature" name="nature[]" required="required" maxlength="300" autocomplete="off">
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" value="" required>
                    </td>
                    <td>
                    </td>
                </tr>

            </tbody>
        </table>

        <label>Remarks</label>
        <textarea name="remarks" rows="8" cols="80"><?php echo e($base['receipt']->remarks); ?></textarea>
    </div>

    <div class="form-group" id="sand_blk">
        <input type="hidden" class="form-control sand_inputs addtl_inputs" name="sand_transaction" id="sand_transaction" value="0">
        <div class="form-group col-sm-3">
            <label for="sand_sandgravelprocessed">Less: <br>Sand and Gravel (processed)</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravelprocessed" id="sand_sandgravelprocessed" value="0" step="0.01" min="0">
        </div>

        <div class="form-group col-sm-3">
            <label for="sand_abc">Less: <br>Aggregate Base Course</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_abc" id="sand_abc" value="0" step="0.01" min="0">
        </div>

        <div class="form-group col-sm-3">
            <label for="sand_sandgravel">Less: <br>Sand and Gravel</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_sandgravel" id="sand_sandgravel" value="0" step="0.01" min="0">
        </div>

        <div class="form-group col-sm-3">
            <label for="sand_boulders">Less: <br>Boulders</label>
            <input type="number" class="form-control sand_inputs addtl_inputs" name="sand_boulders" id="sand_boulders" value="0" step="0.01" min="0">
        </div>
    </div>

	<?php if( $base['receipt']->is_cancelled == 0  ): ?>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Save</button>
    </div>
	<?php endif; ?>
    <?php echo e(Form::close()); ?>

</div>

<div id="account_panel">
</div>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script type="text/javascript">
     <?php if(isset($_GET['types']) &&  $_GET['types'] == 'field'): ?>
        var collection_type = 'show_in_fieldlandtax';
    <?php else: ?>
        var collection_type = 'show_in_landtax';
    <?php endif; ?>
</script>
 <?php echo $__env->make('collection::shared.transactions_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script type="text/javascript">
    tinymce.init({forced_root_block: "", selector:'textarea'});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>