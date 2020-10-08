

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['addtl']->user->realname); ?></dd>
			<dt>Municipality</dt>
            <dd>
            <?php if(!empty($base['addtl']->municipality->name)): ?>
            <?php echo e($base['addtl']->municipality->name); ?>

            <?php endif; ?>
            </dd>
			<dt>Barangay</dt>
            <dd>
            <?php if(!empty($base['addtl']->barangay->name)): ?>
            <?php echo e($base['addtl']->barangay->name); ?>

            <?php endif; ?>
            </dd>
			<dt>Date</dt>
            <dd><?php echo e(date('m/d/Y', strtotime($base['addtl']->date_of_entry))); ?></dd>
            <dt>Reference No.</dt>
            <dd><?php echo e($base['addtl']->refno); ?></dd>
        </dl>
    </div>
</div>

<div class="row">
<div class="form-group col-sm-12">
<table class="table">
	<thead>
		<tr>
			<th>Account</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($base['addtl']->items as $item): ?>
		<tr>
			<td>
            <?php if(isset($item->acct_title)): ?>
            <?php echo e($item->acct_title->name); ?>

            <?php else: ?>
            <?php echo e($item->acct_subtitle->name); ?>

            <?php endif; ?>
            </td>
			<td align="right"><?php echo e(number_format($item->value, 2)); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>