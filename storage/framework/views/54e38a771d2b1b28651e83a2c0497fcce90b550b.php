

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<style>
    #year {
        background:white !important;
    }
    .ui-datepicker-calendar,.ui-datepicker-month {
        display: none;
    }​
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::open([ 'method' => 'POST', 'route' => 'holiday_settings.store' ])); ?>

    
    <div class="form-group col-sm-6">
        <label for="Code">Year</label>
        <input type="number" class="form-control year_month" step="1" name="year" id="year" value="<?php echo e(date('Y')); ?>" required autofocus>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="Code">Month</label>
        <select class="form-control year_month" name="month" id="month">
        <?php foreach($base['months'] as $i => $month): ?>
            <?php if($i+1 == date('n')): ?>
            <option value="<?php echo e($i+1); ?>" selected><?php echo e($month); ?></option>
            <?php else: ?>
            <option value="<?php echo e($i+1); ?>"><?php echo e($month); ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
        </select>
    </div>
    
    <div class="col-sm-12">
        <table class="table" id="days">
            <thead>
                <tr>
                    <th>Weekday</th>
                    <th>Holiday?</th>
                </tr>
            </thead>
            <tbody>
            <?php for($x=1; $x<=$base['current_month_days']; $x++): ?>
                <?php if((date('N', strtotime(date('Y') .'-'. date('m') .'-'. $x)) >= 6) != 1): ?>
                <tr>
                    <td>
                        <?php echo e(date('F d, Y', strtotime(date('Y') .'-'. date('m') .'-'. $x))); ?>

                    </td>
                    <td>
                        <div class="checkbox">
                            <label><input type="checkbox" value="<?php echo e($x); ?>" name="holiday_date[]">Holiday</label>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="ADD">
    </div>
    <?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $('.year_month').change( function() {
        updatedays();
    });
    
    $('#year').keyup( function() {
        updatedays();
    });
    
    function updatedays() {
        var days = daysInMonth($('#month').val(), $('#year').val());
        $('#days tbody').html('');
        for (var i = 1; i < days; i++) {
            var date = new Date($('#year').val() +'-'+ $('#month').val() +'-'+ i);
            var day = date.getDay();
            if (day == 6 || day == 0) {
                continue;
            }
            
            var month = date.toLocaleString('en-us', {month: 'long'});
            
            $('#days tbody').append(
            '<tr>' +
                '<td>' +
                    month +' '+ date.getDate() +', '+ date.getFullYear() +
                '</td>' +
                '<td>' +
                    '<div class="checkbox">' +
                        '<label><input type="checkbox" value="'+ i +'" name="holiday_date[]">Holiday</label>' +
                    '</div>' +
                '</td>' +
            '</tr>'
            );
        }
    }
    
    function daysInMonth(month,year) {
        return new Date(year, month, 0).getDate();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>