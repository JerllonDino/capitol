<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
     <link rel="icon" type="image/png" href="<?php echo e(asset('asset/images/benguet_capitol.png')); ?>" />
    <title><?php echo e($base['site_title']); ?> | <?php echo e($base['page_title']); ?></title>

    <!-- Bootstrap CSS -->
    <?php echo e(Html::style('/bootstrap-3.4.0/css/bootstrap.min.css')); ?>

    <?php echo e(Html::style('/bootstrap-3.4.0/css/bootstrap-theme.min.css')); ?>


    <!-- jQuery UI CSS -->
    <?php echo e(Html::style('/jquery-ui-1.12.1/jquery-ui.min.css')); ?>

    <?php echo e(Html::style('/jquery-ui-1.12.1/jquery-ui.structure.min.css')); ?>

    <?php echo e(Html::style('/jquery-ui-1.12.1/jquery-ui.theme.min.css')); ?>


    <!-- Featherlight CSS -->
    <?php echo e(Html::style('/featherlight-1.5.0/featherlight.min.css')); ?>

    <?php echo e(Html::style('/featherlight-1.5.0/featherlight.gallery.min.css')); ?>


    <!-- Theme CSS -->
    <?php echo e(Html::style('/sb-admin-1.0.4/css/sb-admin.css')); ?>

    <?php echo e(Html::style('/sb-admin-1.0.4/font-awesome/css/font-awesome.min.css')); ?>

    <?php echo e(Html::style('/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')); ?>

    <?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>


    <!-- Custom CSS -->
    <?php echo e(Html::style('/base/css/style.css')); ?>


    <!-- Other CSS -->
    <?php echo $__env->yieldContent('css'); ?>
</head>
<body>
    <?php echo $__env->yieldContent('page'); ?>

    <!-- jQuery -->
    <?php echo e(Html::script('/jquery-2.2.4/jquery-2.2.4.min.js')); ?>


	<!-- Bootstrap JS -->
    <?php echo e(Html::script('/bootstrap-3.4.0/js/bootstrap.min.js')); ?>


    <!-- jQuery UI -->
    <?php echo e(Html::script('/jquery-ui-1.12.1/jquery-ui.min.js')); ?>


    <!-- Featherlight JS -->
    <?php echo e(Html::script('/featherlight-1.5.0/featherlight.min.js')); ?>

    <?php echo e(Html::script('/featherlight-1.5.0/featherlight.gallery.min.js')); ?>

    <?php echo e(Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js')); ?>

    <?php echo e(Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js')); ?>

    <?php echo e(Html::script('/base/sweetalert/sweetalert2.min.js')); ?>


    <?php echo e(Html::script('/vendor/moment.min.js')); ?>

    <?php echo e(Html::script('/vendor/collapse.js')); ?>

    <?php echo e(Html::script('/vendor/transition.js')); ?>

    <?php echo e(Html::script('vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')); ?>

    <?php echo e(Html::script('/vendor/autocomplete/jquery.autocomplete.js')); ?>

    <?php echo e(Html::script('/tinymce-4.5.6/tinymce.min.js')); ?>

    <!-- Custom JS -->
    <?php echo e(Html::script('/base/js/script.js')); ?>


    <!-- Other JS -->
    <?php echo $__env->yieldContent('js'); ?>
</body>
</html>