

<?php $__env->startSection('css'); ?>
<style>

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">



<br /><br /><br />
<div class="col-sm-12">
<form method="POST" action="<?php echo e(route('report.sandgravel_report_municpality_generate')); ?>">
<?php echo e(csrf_field()); ?>

    <div class="form-group col-sm-4">
        <label for="month">Month</label>
        <select class="form-control" name="month" id="month" required>
            <?php foreach($base['months'] as $i => $month): ?>
                <?php if($i == date('m')): ?>
                <option value="<?php echo e($i); ?>" selected><?php echo e($month); ?></option>
                <?php else: ?>
                <option value="<?php echo e($i); ?>"><?php echo e($month); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="year">Year</label>
        <input type="number" class="form-control" name="year" value="<?php echo e(date('Y')); ?>" id="year" step="1" max="<?php echo e(date('Y')); ?>" required>
    </div>
</div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-primary" id="display" name="button_pdf" id="confirm">EXPORT TO PDF-Source of Aggregates</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_excel" id="confirm">EXPORT TO EXCEL-Source of Aggregates</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxsharing" id="confirm">EXPORT TO PDF-Tax & Penalties Sharing</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxsharing_excel" id="confirm">EXPORT TO EXCEL-Tax & Penalties Sharing</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxcollected_clienttype" id="confirm">EXPORT TO PDF-Tax &Penalties Collection by client type</button>
      <button type="submit" class="btn btn-primary" id="display" name="button_taxcollected_clienttype_excel" id="confirm">EXPORT TO EXCEL-Tax &Penalties Collection by client type</button>

      
      <?php /* <button type="submit" class="btn btn-primary" id="display" name="button_taxcollected" id="confirm">Tax &Penalties Collection by client type</button> */ ?>
    </div>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" id="display" name="button_delivery_reciept" id="confirm">Delivery Reciept</button>
    </div>
</form>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>