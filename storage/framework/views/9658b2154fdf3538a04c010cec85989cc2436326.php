

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>ID</dt>
            <dd><?php echo e($account->id); ?></dd>
            <dt>Category</dt>
            <dd><?php echo e($account->name); ?></dd>
        </dl>
    </div>
</div>
<div class="row">
    <?php echo e(Form::open([ 'method' => 'DELETE', 'id' => 'categoryform', 'route' => ['account_category.destroy', $account->id] ])); ?>

    <div class="form-group col-sm-12">
        <?php if( Session::get('permission')['col_serial'] & $base['can_write'] ): ?>
        <a href="<?php echo e(route('account_category.edit', $account->id)); ?>" class="btn btn-info datatable-btn">
            Update
        </a>
        <?php endif; ?>
    </div>
    <?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
  var confirm;
    $(document).ready(function(){

      confirm = $('#dialog').dialog({
        autoOpen:false,
        closeOnEscape: false,
        open: function(event, ui) {
            $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
        },
        show:{
          effect:'bounce',
          duration:1000
        },
        hide:{
          effect:'shake',
          duration:500
        },
        resizable:false,
        height:"auto",
        width:300,
        modal:true
      });
    });

    $('#close').click(function(){
      confirm.dialog('close');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>