

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
<div class='row'>
    <?php if( Session::has('success') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>
            <strong><?php echo e(Session::get('success')); ?></strong>
        </div>
        <?php endif; ?>
     
        <?php if( Session::has('error') ): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>
            <strong><?php echo e(Session::get('error')); ?></strong>
        </div>
        <?php endif; ?>
     
        <?php if(count($errors) > 0): ?>
        <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
          <div>
            <?php foreach($errors->all() as $error): ?>
            <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
     
    <form action="<?php echo e(route('import')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo e(csrf_field()); ?>

        Choose your xls/csv File : <input type="file" name="file" class="form-control">
     
        <input type="submit" class="btn btn-primary btn-lg" style="margin-top: 3%">
    </form>
    </div>
    
<div class="row">
    <div class="col-lg-12">
        <table id="mpi" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Year</th>
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
    $('#mpi').DataTable({
        pageLength: 50,
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '<?php echo e(route("collection.datatables", "monthly_sand_gravel")); ?>',
        columns: [
            { data:
                function(data) {
                    var monthNames = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December',
                    ];
                    return monthNames[data.month - 1];
                },
                name: 'month'
            },
            { data: 'year', name: 'year' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_read'] ): ?>
                    view = '<a href="<?php echo e(route('sandgravel.monthly_view',[null,null])); ?>/'+data.year+'/'+data.month+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>

                    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write'] ): ?>
                    write = '<a href="<?php echo e(route('sandgravel.monthly_edit',[null,null])); ?>/'+data.year+'/'+data.month+'" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    <?php endif; ?>
                    return view + write;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });

    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write'] ): ?>
        $("div.dt-custom").html(
            '<a href="<?php echo e(route("sandgravel.monthly_create")); ?>" class="btn btn-med btn-success">Add</a>'
        );


    <?php endif; ?>
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>