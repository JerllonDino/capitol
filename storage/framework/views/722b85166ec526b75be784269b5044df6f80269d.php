

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<hr />


<div class="row">
    <h4>ACCOUNTABLE FORMS MONTHLY</h4>
<form method="POST" action="<?php echo e(route('report.montly_accountable_forms')); ?>">
<div class="col-sm-12">
<?php echo e(csrf_field()); ?>

 <div class="form-group col-sm-6">
            <label for="start_date">Start Date</label>
            <input type="text" class="form-control date" name="start_date" value="<?php echo e(date('m/d/Y')); ?>" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control date" name="end_date" value="<?php echo e(date('m/d/Y')); ?>" required>
        </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">EXPORT TO PDF</button>
    </div>

 </div>
 </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    // $('#account').select2();
    // $('#subtitle').select2();

    $('.date').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>