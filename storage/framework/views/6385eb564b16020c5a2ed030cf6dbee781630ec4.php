

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::open(array('id'=>'form'))); ?>

    <div class="form-group col-sm-6">
        <label for="date_from">Date From</label>
        <input type="text" class="form-control" id="datefrom" name="datefrom" value="">
    </div>
    <div class="form-group col-sm-6">
        <label for="date_to">Date To</label>
        <input type="text" class="form-control" id="dateto" name="dateto" value="">
    </div>

    <div class="form-group col-sm-4">
        <label for="transaction">Transaction</label>
        <select class="form-control" id="transaction" name="transaction">
            <option value=""></option>
            <?php foreach($base['transaction'] as $transact): ?>
                <option title="<?php echo e($transact); ?>" value="<?php echo e($transact); ?>" ><?php echo e($transact); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group col-sm-4">
        <label for="type">Type</label>
        <select class="form-control" id="type" name="type">
            <option value=""></option>
            <option value="created">Created</option>
            <option value="deleted">Deleted</option>
            <option value="updated">Updated</option>
        </select>
    </div>
    <div class="form-group col-sm-4">
        <label for="username">User</label>
        <select class="form-control" id="user" name="user">
            <option value=""></option>
            <?php foreach($base['user'] as $user): ?>
                <option title="<?php echo e($user['username']); ?>" value="<?php echo e($user['id']); ?>" ><?php echo e($user['realname']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
	<div class="form-group col-sm-12">
        <input type="submit" class="btn btn-success" id="btn_filter" value="Filter">
    </div>
    <?php echo e(Form::close()); ?>

</div>

<div class="row">
    <div class="col-lg-12 div1">
        <table id="example" class="dtable table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Type</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                    <th>User</th>
                    <th>IP</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-lg-12 div2 hidden">
        <table id="example2" class="dtable table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Type</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                    <th>User</th>
                    <th>IP</th>
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
    $(document).ready(function(){
        $('#datefrom, #dateto').datepicker({
            changeMonth: true,
            changeYear: true,
            showAnim: 'slide'
        });
    });
    var ar = 0;
    $("form").on("submit", function(e) {
        e.preventDefault();
        
        $('#example2').dataTable().fnDestroy(); //destroy then reinitialize table
        var data = $(this).serialize(); //get all form data
      
        var url = '<?php echo e(route("datatables.filter", ["data"=>"change"])); ?>';
        url2 = url.replace('change', data); //change url

        $(".div1").addClass('hidden');
        $(".div2").removeClass('hidden');
        $('#example2').dataTable({
            pageLength: 50,
            dom: '<"dt-custom">frtip',
            processing: true,
            serverSide: true,
            scrollCollapse: true,
            ajax: url2,
            columnDefs: [
                { "targets": [0,1,2,4,5] , "width": "11%" },
                { "targets": 3 , "width": "45%" }
            ],
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'type', name: 'type', width: '5%' },
                { data: 'old', name: 'old', width: '20%' },
                { data: 'new', name: 'new', width: '20%' },
                { data: 'realname', name: 'dnlx_user.realname' },
                { data: 'ip_address', name: 'ip_address' }
            ]
        });
    })
    $('#example').dataTable({
        pageLength: 50,
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollCollapse: true,
        ajax: '<?php echo e(route("datatables", "audit")); ?>',
        columnDefs: [
            { "targets": [0,1,2,4,5] , "width": "11%" },
            { "targets": 3 , "width": "45%" }
        ],
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'type', name: 'type', width: '5%' },
            { data: 'old', name: 'old', width: '20%' },
            { data: 'new', name: 'new', width: '20%' },
            { data: 'realname', name: 'dnlx_user.realname' },
            { data: 'ip_address', name: 'ip_address' }
        ]
    });
    $("#datefrom").on("change", function(){
        if($(this).val() != "") {
            $(this).prop('required', true);
            $("#dateto").prop('required', true);
        }
    })
    $("#dateto").on("change", function(){
        if($(this).val() != "") {
            $(this).prop('required', true);
            $("#datefrom").prop('required', true);
        }
    })
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>