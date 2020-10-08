

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::model($user, ['method' => 'POST', 'action' => ['SessionController@update_profile', $user -> id] ])); ?>

            <div class="form-group col-sm-12">
                <label for="realname">Real Name</label>
                <input type="text" class="form-control" name="realname" value="<?php echo e($user->realname); ?>" autofocus required>
            </div>
            
            <div class="form-group col-sm-12">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo e($user->username); ?>" readonly>
            </div>
            
            <div class="form-group col-sm-6">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo e($user->email); ?>">
            </div>
            
            <div class="form-group col-sm-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            
            <div class="form-group col-sm-6">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" name="new_password">
            </div>
            
            <div class="form-group col-sm-6">
                <label for="retype_password">Retype New Password</label>
                <input type="password" class="form-control" name="retype_password">
            </div>
            
            <div class="form-group col-sm-12">
                <input type="submit" class="btn btn-success" id="submit" value="Update">
            </div>
    <?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>