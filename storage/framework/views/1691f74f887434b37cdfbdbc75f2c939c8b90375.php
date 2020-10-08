

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<hr />


<div class="row">
    <h4></h4>
<form method="POST" action="<?php echo e(route('form56_settings.save')); ?>">
<div class="col-sm-12">
<?php echo e(csrf_field()); ?>

         <div class="form-group col-sm-12">
            <label for="start_date">Effictivity Year</label>
            <input type="number" min="2000" max="<?php echo e(DATE('Y')); ?>" step="1" class="form-control" name="year" value="<?php echo e($base['f56_settings']->effictivity_year ?? ''); ?>" required>
        </div>
        <div class="form-group col-sm-12">
            <label for="start_date">TAX PERCENTAGE TO BE COLLECTED</label>
            <input type="number" min="0" step="0.1" class="form-control" name="tax_percent" value="<?php echo e($base['f56_settings']->tax_percentage ?? ''); ?>" required>
        </div>
        <div class="form-group col-sm-12">
            <label for="start_date">PAID IN FULL BEFORE JANUARY 1</label>
            <input type="number" min="0" step="0.1" class="form-control" name="paid_in_full_december" value="<?php echo e($base['f56_settings']->disc_before_jan ?? ''); ?>" required>
        </div>
        <div class="form-group col-sm-12">
            <label for="end_date">PAID IN FULL FROM JANUARY 1 TO MARCH 31</label>
            <input type="number" min="0" step="0.1" class="form-control" name="paid_in_full_from" value="<?php echo e($base['f56_settings']->disc_from_jan_march ?? ''); ?>" required>
        </div>

         <div class="form-group col-sm-12">
            <label for="end_date">PENALTY PER MONTH</label>
            <input type="number" min="0" step="0.1" class="form-control" name="monthly_penalty" value="<?php echo e($base['f56_settings']->penalty_per_mnth ?? ''); ?>" required>
        </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">Update</button>
    </div>

 </div>
 </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>