

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <table id="account_category_list" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
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

<script>
    $('#account_category_list').DataTable({
        pageLength: 50,
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '<?php echo e(route("collection.datatables", "categ")); ?>',
        columns: [
            { data: 'name', name: 'name' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    <?php if( Session::get('permission')['col_settings'] & $base['can_read'] ): ?>
                    view = '<a href="<?php echo e(route('account_category.index')); ?>/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>
                    
                    <?php if( Session::get('permission')['col_settings'] & $base['can_write'] ): ?>
                    write = '<a href="<?php echo e(route('account_category.index')); ?>/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    <?php endif; ?>
                    return view + write;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });

    <?php if( Session::get('permission')['col_settings'] & $base['can_write'] ): ?>
        $("div.dt-custom").html(
            '<a href="<?php echo e(route("account_category.create")); ?>" class="btn btn-med btn-success">Add</a>'
        );
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>