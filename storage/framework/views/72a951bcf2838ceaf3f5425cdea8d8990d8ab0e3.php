

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['pdf.client_type_gen']])); ?>

        <div class="form-group col-sm-4">
            <label for="month">Month</label>
            <select class="form-control" name="month" id="month" required autofocus>
               
                <?php foreach( $base['months'] as $mkey => $month): ?>
                    <?php if(\Carbon\Carbon::parse($month)->format('m') == date('m')): ?>
                        <option value="<?php echo e($mkey); ?>" selected><?php echo e($month); ?> </option>
                        <?php else: ?>
                        <option value="<?php echo e($mkey); ?>"><?php echo e($month); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-sm-4">
            <label for="year">Year</label>
            <input type="number" class="form-control" name="year" value="<?php echo e(date('Y')); ?>" id="year" step="1" max="<?php echo e(date('Y')); ?>" required>
        </div>

        <div class="form-group col-sm-4">
            <label for="customer_type">Client Type</label>
            <select class="form-control" name="customer_type" id="customer_type">
                <option></option>
                <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?>
                    <option value="<?php echo e($sandgravel_types['id']); ?>"><?php echo e($sandgravel_types['description']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-sm-4" id="transac_div" name="transac_div" style="display: none;">
            <label>Number of Transactions</label>
            <input class="form-control" type="number" id="transac_count" value="" readonly> 
        </div>

        <div class="form-group col-sm-4 pull-right" id="monitoring_sub" name="monitoring_sub" style="display: none;">
            <label>Account</label>
            <select class="form-control" name="monitoring_type" id="monitoring_type">
                <option value="0" selected="selected">All</option>
                <option value="6">Amusement Tax</option>
                <option value="61">Quarantine Fees</option>
                <option value="4">Taxes on Sand, Gravel & Other Quarry Products</option>
            </select>
        </div>

        <div class="form-group col-sm-4 pull-right" id="clients_not_monitoring" name="clients_not_monitoring" style="display: none;">
            <label for="customer_type">Account</label>
            <select class="form-control" name="clients" id="clients">
            </select>
        </div>
        
        <div class="form-group col-sm-12">
          <button type="submit" class="btn btn-primary" name="button" id="confirm">EXPORT TO PDF</button>
        </div>
    <?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $('.date').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });

    $(document).on('change', '#customer_type', function() {
        if($(this).val() == 1) { // monitoring
            $('#monitoring_sub').css('display', 'block');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'none');
        } else if($(this).val() > 1) {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'block');
            $.ajax({
                url: "<?php echo e(route('report.get_clients')); ?>",
                type: 'POST',
                data: {
                    customer_type : $(this).val(),
                    month: $('#month').val(),
                    year: $('#year').val(),
                    _token: '<?php echo e(csrf_token()); ?>',
                },
                success: function(data) {
                    $('#clients').empty();
                    $('#clients').append('<option value="0">All</option>');
                    $.each(data, function(key, value) {
                        $('#clients').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                },

            }); 
        } else {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'none');
            $('#clients_not_monitoring').css('display', 'none');
        }
    });
    $(document).on('change', '#month', function() {
        if($('#customer_type').val() == 1) { // monitoring
            $('#monitoring_sub').css('display', 'block');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'none');
        } else if($('#customer_type').val() > 1) {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'block');
            $.ajax({
                url: "<?php echo e(route('report.get_clients')); ?>",
                type: 'POST',
                data: {
                    customer_type : $('#customer_type').val(),
                    month: $('#month').val(),
                    year: $('#year').val(),
                    _token: '<?php echo e(csrf_token()); ?>',
                },
                success: function(data) {
                    $('#clients').empty();
                    $('#clients').append('<option value="0">All</option>');
                    $.each(data, function(key, value) {
                        $('#clients').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                },

            }); 
        } else {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'none');
            $('#clients_not_monitoring').css('display', 'none');
        }
    });
    $(document).on('change', '#year', function() {
        if($('#customer_type').val() == 1) { // monitoring
            $('#monitoring_sub').css('display', 'block');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'none');
        } else if($('#customer_type').val() > 1) {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'block');
            $.ajax({
                url: "<?php echo e(route('report.get_clients')); ?>",
                type: 'POST',
                data: {
                    customer_type : $('#customer_type').val(),
                    month: $('#month').val(),
                    year: $('#year').val(),
                    _token: '<?php echo e(csrf_token()); ?>',
                },
                success: function(data) {
                    $('#clients').empty();
                    $('#clients').append('<option value="0">All</option>');
                    $.each(data, function(key, value) {
                        $('#clients').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                },

            }); 
        } else {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'none');
            $('#clients_not_monitoring').css('display', 'none');
        }
    });
    $(document).ready(function() {
        if($('#customer_type').val() == 1) { // monitoring
            $('#monitoring_sub').css('display', 'block');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'none');
        } else if($('#customer_type').val() > 1) {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'block');
            $('#clients_not_monitoring').css('display', 'block');
            $.ajax({
                url: "<?php echo e(route('report.get_clients')); ?>",
                type: 'POST',
                data: {
                    customer_type : $('#customer_type').val(),
                    month: $('#month').val(),
                    year: $('#year').val(),
                    _token: '<?php echo e(csrf_token()); ?>',
                },
                success: function(data) {
                    $('#clients').empty();
                    $('#clients').append('<option value="0">All</option>');
                    $.each(data, function(key, value) {
                        $('#clients').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                },

            }); 
        } else {
            $('#monitoring_sub').css('display', 'none');
            $('#transac_div').css('display', 'none');
            $('#clients_not_monitoring').css('display', 'none');
        }
    });

    $.fn.count = function() {
        $.ajax({
            url: "<?php echo e(route('report.count_transac')); ?>",
            type: "POST",
            data: {
                '_token' : '<?php echo e(csrf_token()); ?>',
                'month' : $('#month').val(),
                'year' : $('#year').val(),
                'ctype' : $('#customer_type').val(),
                'mtype' : $('#monitoring_type').val(),
                'clients' : $('#clients').val()
            },
            success: function(data) {
                $('#transac_count').val(data);
            }
        });
    }

    $(document).on('change', '#customer_type, #month, #monitoring_type, #clients', function() {
        $.fn.count();
    });

    $(document).on('keyup', '#year', function() {
        $.fn.count();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>