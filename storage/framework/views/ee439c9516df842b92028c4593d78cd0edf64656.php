

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if( Session::get('permission')['col_serial'] & $base['can_write'] ): ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['serial.store']])); ?>

    <div class="form-group col-sm-4">
        <label for="start">Start</label>
        <input type="number" class="form-control" name="start" id="start" step="1" min="0" required autofocus>
    </div>

    <div class="form-group col-sm-4">
        <label for="end">End</label>
        <input type="number" class="form-control" name="end" id="end" step="1" min="0" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="form">Form</label>
        <select class="form-control" name="form" id="form" required>
            <option value="" selected disabled></option>
            <?php foreach($res as $result): ?>
            <option value="<?php echo e($result->id); ?>"><?php echo e($result->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label for="unit">Unit</label>
        <input type="text" class="form-control f51_inputs" name="unit" id="unit" value="" disabled>
    </div>

    <div class="form-group col-sm-3">
        <label for="acct_cat_id">Fund</label>
        <select class="form-control f51_inputs" name="acct_cat_id" id="acct_cat_id" disabled>
            <option selected disabled></option>
            <?php foreach($base['acct_cat'] as $acct_cat): ?>
                <option value="<?php echo e($acct_cat->id); ?>"><?php echo e($acct_cat->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality" disabled>
            <option value="" selected disabled></option>
            <?php foreach($base['municipality'] as $mun): ?>
            <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label for="date">Date</label>
        <input type="text" class="form-control" name="date" id="date" value="<?php echo e(date('m/d/Y')); ?>" required>
    </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    <?php echo e(Form::close()); ?>

</div>
<hr>
<?php endif; ?>
<?php if( Session::get('permission')['col_serial'] & $base['can_read'] ): ?>
<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Form</th>
            <th>Start</th>
            <th>Current</th>
            <th>End</th>
            <th>Date</th>
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
        ajax: '<?php echo e(route("collection.datatables", "serial")); ?>',
        columns: [
            { data: 'name', name: 'col_acctble_form.name' },
            { data: 'serial_begin', name: 'col_serial.serial_begin' },
            { data: 'serial_current', name: 'col_serial.serial_current' },
            { data: 'serial_end', name: 'col_serial.serial_end' },
            { data: 'date_added' , name : 'col_serial.date_added' ,
                render : function(data) {
                    var date = new Date(data);
                    var month = date.toLocaleString('en-us', {month: 'long'});
                    return month +' '+ date.getDate() +', '+ date.getFullYear();
                }
            },
            { data: null, name : '' ,
               render : function(data) {
                    var complete = '';
                    var view = '';
                    var write = '';
                    <?php if( Session::get('permission')['col_serial'] & $base['can_read'] ): ?>
                     if(data.serial_current != 0)
                        view = '<a href="<?php echo e(route('serial.index')); ?>/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>

                    <?php if( Session::get('permission')['col_serial'] & $base['can_write'] ): ?>
                        if(data.serial_current != 0)
                            write = '<a href="<?php echo e(route('serial.index')); ?>/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    <?php endif; ?>

                     <?php if( Session::get('permission')['col_serial'] & $base['can_write'] ): ?>
                        if(data.serial_current == 0)
                            complete = '<strong style="color:red;">completed</strong>';
                    <?php endif; ?>

                    return view + write + complete;
                },
                bSortable: false,
                searchable: false,
            }
        ],
        order : [[ 2, "desc" ]]
    });

    $(document).ready(function(){
        $('#date').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide'
        });
    });

    $('#form').change( function() {
        if ($(this).val() == 2) {
            // form 56
            $('.f51_inputs').attr('disabled', true);
            $('.f51_inputs').attr('required', false);
            $('.f51_inputs').val('');

            $('#municipality').attr('disabled', false);
            $('#municipality').attr('required', true);
            $('#municipality').val('');
        } else {
            $('.f51_inputs').attr('disabled', false);
            $('.f51_inputs').attr('required', true);
            $('.f51_inputs').val('');

            $('#municipality').attr('disabled', true);
            $('#municipality').attr('required', false);
            $('#municipality').val('');
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>