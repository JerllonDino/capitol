

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::model($result, ['method' => 'PATCH', 'action' => ['UserController@update', $result -> id] ])); ?>

        <div class="form-group col-sm-12">
            <label for="realname">Real Name</label>
            <input type="text" class="form-control" id="realname" name="realname" value="<?php echo e($result -> realname); ?>" autofocus required>
        </div>
        <div class="form-group col-sm-12">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo e($result -> username); ?>" readonly>
        </div>
        <div class="form-group col-sm-12">
            <label for="position">Position</label>
            <input type="text" class="form-control" id="position" name="position" value="<?php echo e($result -> position); ?>" required>
        </div>
        <div class="form-group col-sm-6">
            <label for="password">New Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group col-sm-6">
            <label for="retype_password">Retype New Password</label>
            <input type="password" class="form-control" id="retype_password" name="retype_password">
        </div>
        <div class="form-group col-sm-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo e($result -> email); ?>">
        </div>
        <div class="form-group col-sm-6">
            <label for="group">Group</label>
            <select class="form-control" id="group" name="group" required>
            <option value = "<?php echo e($grp -> id); ?>" hidden><?php echo e($grp -> name); ?></option>
                <?php foreach($base['groups'] as $group): ?>
                    <?php if(Request::old('group') == $group->id): ?>
                    <option title="<?php echo e($group->description); ?>" value="<?php echo e($group->id); ?>" selected><?php echo e($group->name); ?></option>
                    <?php else: ?>
                    <option title="<?php echo e($group->description); ?>" value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-success" id="submit" value="Update">
        </div>
    <?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>