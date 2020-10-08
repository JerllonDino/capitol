

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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

<?php
    $month_e = '';
    if( $base['month_e']->count() <12 ){
         $month_ex = $base['month_e']->count() + 1;
      if($base['month_e']->count() == 0){
        $month_ex = 12;
      }


    
    $month_ec = (date('F', mktime(0, 0, 0, $month_ex, 10)));
    $month_es = (date('m', mktime(0, 0, 0, $month_ex, 10)));
      
     if( $month_es < date('m')    ){
            $month_e = '<form method="POST" class="pull-right" action="'.route('monthly_provincial_income.auto_gen').'"  > '.csrf_field().'<button class="btn btn-info" >GENEREATE '.$month_ec.' </button> <input type="hidden" name="month_e" value="'.$month_ec.' " /> <input type="number"  name="year" value="'.date("Y").'" /> </form>';
     }

    }
?>

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
        ajax: '<?php echo e(route("collection.datatables", "monthly_provincial_income")); ?>',
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
                    var regenerate = '';
                    var deletex = '';
                    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_read'] ): ?>
                    view = '<a href="<?php echo e(route('monthly_provincial_income.index')); ?>/'+data.year+'/'+data.month+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>

                    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write']  ): ?>
                        if(data.auto_generated == 1){
                            regenerate = '<form method="POST" action="<?php echo e(route('monthly_provincial_income.auto_regen')); ?>" class="form-horizontal col-md-2" ><?php echo e(csrf_field()); ?> <input type="hidden" name="year" value="'+data.year+'" /><input type="hidden" name="month_ex" value="'+data.month+'" /> <button type="submit" class="btn btn-warning"  >Regenerate</button>  </form>  ';
                        }
                    <?php endif; ?>


                    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write']  ): ?>
                        if(data.auto_generated == 1){
                            deletex = '<form method="POST" action="<?php echo e(route('monthly_provincial_income.auto_regen_delete')); ?>" class="form-horizontal col-md-2" ><?php echo e(csrf_field()); ?> <input type="hidden" name="year" value="'+data.year+'" /><input type="hidden" name="month_ex" value="'+data.month+'" /> <button type="submit" class="btn btn-danger"  >DELETE</button>  </form>  ';
                        }
                    <?php endif; ?>

                    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write'] ): ?>
                    write = '<a href="<?php echo e(route('monthly_provincial_income.index')); ?>/'+data.year+'/'+data.month+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    <?php endif; ?>
                    return view + write +  regenerate;
                },
                bSortable: false,
                searchable: false,
            }
        ],
         order : [[ 1, "desc" ]]
    });

    <?php if( Session::get('permission')['col_monthly_provincial_income'] & $base['can_write'] ): ?>
        $("div.dt-custom").html(
            ' <?php echo $month_e; ?>'
        );


    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>