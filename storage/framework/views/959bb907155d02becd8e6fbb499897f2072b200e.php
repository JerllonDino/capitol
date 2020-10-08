

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>SG TYPE</dt>
            <dd><?php echo e(strtoupper( $base['serial']->sgtype->sg_type )); ?></dd>
            <dt>Serial Begin</dt>
            <dd><?php echo e($base['serial']->serial_start); ?></dd>
            <dt>Serial End</dt>
            <dd><?php echo e($base['serial']->serial_end); ?></dd>
            <dt>Serial QTY</dt>
            <dd><?php echo e($base['serial']->serial_qty); ?></dd>
            
        </dl>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>