

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if( Session::get('permission')['backup'] & $base['can_write'] ): ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['backup.store']])); ?>

    
    <div class="form-group col-sm-6">
        <label for="date">Date</label>
        <input type="text" class="form-control" name="date" id="date" value="<?php echo e(date('m/d/Y')); ?>" readonly required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="remark">Remark</label>
        <input type="text" class="form-control" name="remark" id="remark" required autofocus>
    </div>
    
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    
    <?php echo e(Form::close()); ?>

</div>
<hr>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <table id="userlist" class="dtable table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Remark</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo e(Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js')); ?>

<?php echo e(Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js')); ?>

<script type="text/javascript">
$('#userlist').DataTable({
    dom: '<"dt-custom">frtip',
    processing: true,
    serverSide: true,
    ajax: '<?php echo e(route("datatables", "backup")); ?>',
    columns: [
        { data: 'date_of_entry', name: 'date_of_entry' },
        { data: 'remark', name: 'remark' },
        { data:
            function(data) {
                var view = '';
                <?php if( Session::get('permission')['backup'] & $base['can_write'] ): ?>
                view = '<a href="<?php echo e(route('backup.index')); ?>/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                <?php endif; ?>
                return view;
            },
            bSortable: false,
            searchable: false,
        }
    ]
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>