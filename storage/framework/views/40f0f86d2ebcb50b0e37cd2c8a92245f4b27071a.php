

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if( Session::get('permission')['col_customer'] & $base['can_write'] ): ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['customer.store']])); ?>

    <div class="form-group col-sm-12">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" required autofocus>
    </div>
    
    
    <div class="form-group col-sm-12">
        <label for="address">Address</label>
        <textarea class="form-control" name="address"></textarea>
    </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    <?php echo e(Form::close()); ?>

</div>
<?php endif; ?>
<?php if( Session::get('permission')['col_customer'] & $base['can_read'] ): ?>
<table id="seriallist" class="table table-striped table-hover" cellspacing = 0 width = "100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Client Type</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo e(Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js')); ?>

<?php echo e(Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js')); ?>

<script type="text/javascript">
    $('#seriallist').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '<?php echo e(route("collection.datatables", "customer")); ?>',
        columns: [
            { data: 'name', name: 'name' },
            { data: 
                function(data) {
                    
                    return !data.customer_type ? '' : data.customer_type['description'];
                }, 
                bSortable: false,
                searchable: false,

            },
            { data: 'address', name: 'address' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    <?php if( Session::get('permission')['col_customer'] & $base['can_read'] ): ?>
                    var view = '<a href="<?php echo e(route('customer.index')); ?>/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>
                    <?php if( Session::get('permission')['col_customer'] & $base['can_write'] ): ?>
                    var write = '<a href="<?php echo e(route('customer.index')); ?>/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    <?php endif; ?>
                    return view + write;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>