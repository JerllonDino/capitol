

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'GET', 'route' => ['pdf.provincial_income']])); ?>

        <div class="form-group col-sm-6">
            <label for="month">Month</label>
            <select class="form-control" name="month" id="month" required autofocus>
               
                <?php foreach( $base['months'] as $mkey => $month): ?>
                    <?php if($month == date('m')): ?>
                    <option value="<?php echo e($mkey); ?>" selected><?php echo e($month); ?></option>
                    <?php else: ?>
                    <option value="<?php echo e($mkey); ?>"><?php echo e($month); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-sm-6">
            <label for="year">Year</label>
            <input type="number" class="form-control" name="year" value="<?php echo e(date('Y')); ?>" id="year" step="1" max="<?php echo e(date('Y')); ?>" required>
        </div>

        <div class="form-group col-sm-12">
          <button type="submit" class="btn btn-primary" name="button" id="confirm">EXPORT TO PDF</button>
          <button type="submit" class="btn btn-primary" name="button_excel" id="confirm">EXPORT TO EXCEL</button>
        </div>
    <?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $('.date').datepicker({
		changeMonth:true,
		changeYear:true,
		showAnim:'slide'
	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>