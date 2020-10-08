

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <table id="account_title" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
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
    $('#account_title').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '<?php echo e(route("collection.datatables", "title")); ?>',
        columns: [
            { data: 'code', name: 'col_acct_title.code' },
            { data: 'name', name: 'col_acct_title.name' },
            { data: 'cat_name', name: 'col_acct_category.name' },
            { data: null , name : '',
                render : function(data) {
                    var view = '';
                    var write = '';
                    var can_delete = '';
                    <?php if( Session::get('permission')['col_settings'] & $base['can_read'] ): ?>
                    view = '<a href="<?php echo e(route('account_title.index')); ?>/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>

                    <?php if( Session::get('permission')['col_settings'] & $base['can_write'] ): ?>
                    write = '<a href="<?php echo e(route('account_title.index')); ?>/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    <?php endif; ?>

                    <?php if( Session::get('permission')['col_settings'] & $base['can_delete'] ): ?>
                    can_delete = '<a href="<?php echo e(route('account_title.index')); ?>/'+data.id+'/destroy" class="btn btn-sm btn-danger datatable-btn" title="Delete"><i class="fa fa-trash"></i></a>';
                    <?php endif; ?>
                    return view + write + can_delete;
                },
                bSortable: false,
                searchable: false,
            }
        ],
        order : [[ 2, "asc" ]]
    });

    <?php if( Session::get('permission')['col_settings'] & $base['can_write'] ): ?>
        $("div.dt-custom").html(
            '<a href="<?php echo e(route("account_title.create")); ?>" class="btn btn-med btn-success">Add</a>'
        );
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>