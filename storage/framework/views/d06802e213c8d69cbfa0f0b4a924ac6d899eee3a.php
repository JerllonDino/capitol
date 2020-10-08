

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Accountable Form</dt>
            <dd><?php echo e($base['serial']->formtype->name); ?></dd>
            <dt>Serial Begin</dt>
            <dd><?php echo e($base['serial']->serial_begin); ?></dd>
            <dt>Serial End</dt>
            <dd><?php echo e($base['serial']->serial_end); ?></dd>
            <dt>Serial In Use</dt>
            <dd><?php echo e($base['serial']->serial_current); ?></dd>
            <dt>Date Added</dt>
            <dd><?php echo e(date('F d, Y', strtotime($base['serial']->date_added))); ?></dd>
            <?php if($base['serial']->unit !== null): ?>
                <dt>Unit</dt>
                <dd><?php echo e($base['serial']->unit); ?></dd>
            <?php endif; ?>
            <?php if($base['serial']->acct_cat_id !== null): ?>
                <dt>Fund</dt>
                <dd><?php echo e($base['serial']->fund->name); ?></dd>
            <?php endif; ?>
            <?php if($base['serial']->municipality !== null): ?>
                <dt>Municipality</dt>
                <dd><?php echo e($base['serial']->municipality->name); ?></dd>
            <?php endif; ?>
        </dl>
    </div>
</div>
<div class="row">
    <?php echo e(Form::open([ 'route' => ['serial.destroy', $base['serial']['id']], 'method' => 'delete', 'id' => 'serialform' ])); ?>

        <?php if( $base['serial']->serial_begin == $base['serial']->serial_current ): ?>
        <div class="form-group col-sm-12">
            
            <?php if( Session::get('permission')['col_serial'] & $base['can_write'] ): ?>
            <a href="<?php echo e(route('serial.edit', $base['serial']['id'])); ?>" class="btn btn-info datatable-btn">
                Update
            </a>
            <?php endif; ?>
            
            <?php if( Session::get('permission')['col_serial'] & $base['can_delete'] ): ?>
            <button type="button" id="delete" class="btn btn-danger datatable-btn pull-right">
                Delete
            </button>
            <?php endif; ?>
            
        </div>
        <?php endif; ?>
    <?php echo e(Form::close()); ?>

</div>

<div id="delete_confirm">
    Are you sure you want to delete this serial?
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
                $('#serialform').submit();
            },
            
            'Cancel': function() {
                $(this).dialog('close');
            },
        },
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>