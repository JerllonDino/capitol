

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Fullname</dt>
            <dd><?php echo e($base['user']['realname']); ?></dd>
            <dt>Username</dt>
            <dd><?php echo e($base['user']['username']); ?></dd>
            <dt>Position</dt>
            <dd><?php echo e($base['user']['position']); ?></dd>
            <dt>Email</dt>
            <dd><?php echo e($base['user']['email']); ?></dd>
            <dt>Group</dt>
            <dd>
                <a href="<?php echo e(route('group.show', $base['user']->group->id)); ?>">
                    <?php echo e($base['user']->group->name); ?>

                </a>
            </dd>
        </dl>
    </div>
</div>
<div class="row">
    <?php echo e(Form::open([ 'method' => 'delete', 'id' => 'userform', 'action' => ['UserController@destroy', $base['user']['id']] ])); ?>

        <div class="form-group col-sm-12">
            
            <?php if( Session::get('permission')['user'] & $base['can_write'] ): ?>
            <a href="<?php echo e(route('user.edit', $base['user']['id'])); ?>" class="btn btn-info datatable-btn">
                Update
            </a>
            <?php endif; ?>
            
            <?php if( Session::get('permission')['user'] & $base['can_delete'] ): ?>
            <button type="button" id="delete" class="btn btn-danger datatable-btn pull-right">
                Delete
            </button>
            <?php endif; ?>
            
        </div>
    <?php echo e(Form::close()); ?>

</div>

<div id="delete_confirm">
    Are you sure you want to delete '<?php echo e($base['user']['username']); ?>'?
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
    $('#delete').click( function() {
        $('#delete_confirm').dialog('open');
    });

    $('#delete_confirm').dialog({
        autoOpen: false,
        draggable:false,
        modal: true,
        resizable: false,
        title: 'Delete User?',
        width: 'auto',
        buttons: {
            'Delete': function() {
                $('#userform').submit();
            },
            
            'Cancel': function() {
                $(this).dialog('close');
            },
        },
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>