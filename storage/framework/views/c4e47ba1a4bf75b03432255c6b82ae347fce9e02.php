

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-sm-4 col-sm-offset-4" id="login-form">
        <div class="form-group">
            <center>
                <?php if(!empty($logo)): ?>
                <?php echo e(Html::image($logo, "Logo", array('width' => 250, 'height' => 250))); ?>

                <?php endif; ?>
            </center>
        </div>
        
        <?php echo $__env->make('message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        
        <?php echo e(Form::open([ 'route' => ['session.login'], 'method' => 'post' ])); ?>

            <div class="form-group">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" autofocus required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="submit" class="col-sm-12 btn btn-success" id="submit" value="Log in">
            </div>
        <?php echo e(Form::close()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nonav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>