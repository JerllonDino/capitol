

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php if( Session::get('permission')['col_receipt'] & $base['can_write'] ): ?>
    <div class="form-group col-sm-12">
        <?php if($base['receipt']->is_cancelled == 1): ?>
            <button type="submit" class="btn btn-info" id="confirm" disabled>Print</button>
        <?php else: ?>
            <a href="<?php echo e(route('pdf.receipt', ['id' => $base['receipt']->id])); ?>" class="btn btn-info">Print</a>
        <?php endif; ?>

		<?php if($base['receipt']->is_cancelled == 0): ?>
            <a href="<?php echo e(route('receipt.certificate.index', ['id' => $base['receipt']->id])); ?>" class="btn btn-info">Certificate</a>
		<?php else: ?>
            <a href="#" class="btn btn-info" disabled>Certificate</a>
		<?php endif; ?>

        <?php if($base['receipt']->serial->formtype->id == 2): ?>
            <?php if($base['receipt']->is_cancelled == 1): ?>
            <a href="#" class="btn btn-info" disabled>Form 56 Detail</a>
			<?php else: ?>
            <a href="<?php echo e(route('receipt.f56_detail_form', ['id' =>$base['receipt']->id])); ?>" class="btn btn-info">Form 56 Detail</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($base['receipt']->is_printed == 1): ?>
        <button type="button" class="btn btn-warning pull-right" id="cancel_btn">Cancel Receipt</button>
        <?php else: ?>
        <button type="button" class="btn btn-warning pull-right" id="cancel_btn" disabled>Cancel Receipt</button>
        <?php endif; ?>

    </div>
    <?php endif; ?>
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['receipt']->user->realname); ?></dd>
            <dt>AF Type</dt>
            <dd><?php echo e($base['receipt']->serial->formtype->name); ?></dd>
			<dt>Serial Number</dt>
            <dd><?php echo e($base['receipt']->serial_no); ?></dd>
			<dt>Payor/Customer</dt>

            <dd><?php echo e($base['receipt']->customer->name); ?></dd>
            <dt>Client Type</dt>
            <dd><?php echo e($base['receipt']->client_type_desc->description ?? ''); ?></dd>

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
			<dt>Remark</dt>
            <dd><?php echo e($base['receipt']->bank_remark); ?></dd>
            <dt>Status</dt>
            <dd>
            <?php if($base['receipt']->is_cancelled == 1): ?>
                Cancelled
                <p><?php echo e($base['receipt']->cancelled_remark); ?></p>
            <?php elseif($base['receipt']->is_printed == 1): ?>
                Printed
            <?php else: ?>
                Pending (To be printed)
            <?php endif; ?>
            </dd>
        </dl>
    </div>
</div>

<div id="cancel_panel">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['receipt.cancel', $base['receipt']->id]])); ?>

    <div class="form-group col-sm-12">
        <label for="bank_remark">Remark</label>
        <textarea class="form-control" name="cancel_remark" required></textarea>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" id="go">Go</button>
    </div>
    <?php echo e(Form::close()); ?>

</div>

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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>