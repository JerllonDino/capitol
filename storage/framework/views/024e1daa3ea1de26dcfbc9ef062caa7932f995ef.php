

<?php $__env->startSection('page'); ?>
<div id="nonav-wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><?php echo e($base['site_title']); ?></a>
        </div>
    </nav>
    
    <div id="page-wrapper">
        <div class="container-fluid">
            
            <?php echo $__env->yieldContent('content'); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>