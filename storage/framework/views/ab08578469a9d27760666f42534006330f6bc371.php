<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php echo e(Html::style('/base/sweetalert/sweetalert2.min.css')); ?>

<style>
    .td_amt {
        width: 150px;
    }
    .td_nature {
        width: 450px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    #sand_blk {
        display: none;
    }

    .btn-pink{
        background-image: linear-gradient(to bottom,#f66adc 0,#b11faa 100%);
        margin-left: 4px;
    }

    .btn-gray{
        color : #fff;
        background-image: linear-gradient(to bottom,#959294 0,#625e61 100%);
        margin-left: 4px;
    }

    .btn-green{
        color : #fff;
        background-image: linear-gradient(to bottom,#73e641 0,#4a9c18 100%);
        margin-left: 4px;
    }

    .btn-red{
        color : #fff;
        background-image: linear-gradient(to bottom,#ff0009 0,#9e1523 100%);
        margin-left: 4px;
    }

    .btn-another{
        color:#fff;
        background-image: linear-gradient(to bottom,#229568 0,#0b470e 100%);
        margin-left: 4px;
    }

    .btn-another-none{
        color:#fff;
        background-image: linear-gradient(to bottom,#5a755d 0,#435744 100%);
        margin-left: 4px;
    }

    #sg_booklets{
        background: burlywood;
    }

    .bg-cert-ttc{
        background-image: linear-gradient(to bottom,#0b147d 0,#0c2768 100%);
        margin-left: 4px;
    }

    .bg-cert-sg{
        background-image:linear-gradient(to bottom,#ae3bcb 0,#cd3bd1 100%);
        margin-left: 4px;
    }

    .bg-cert-pp{
        background-image: linear-gradient(to bottom,#0a933c 0,#0f661d 100%);
        margin-left: 4px;
    }
    .bg-cert-pp > a{
        color: #000;
    }


.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ): ?>
        <div class="row">
            <?php echo e(Form::open(['method'=>'POST', 'route'=>['mncpal.create'], 'id'=>'create_form'])); ?>

                <?php echo e(csrf_field()); ?>

                <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo e($base['user']->id); ?>">
                <div class="form-group col-md-4">
                    <label for="rcpt_no">Receipt No.</label>
                    <input type="number" name="rcpt_no" id="rcpt_no" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="rcpt_date">Receipt Date</label>
                    <input type="text" name="rcpt_date" id="rcpt_date" class="form-control datepicker" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="customer_id">Payor/Customer</label>
                    <input type="text" class="form-control" name="customer" id="customer" required>
                    <input type="hidden" class="form-control" name="customer_id" id="customer_id">
                </div>
                <div class="form-group col-sm-4">
                    <label for="mncpal_mnc">Municipality</label>
                    <select class="form-control" name="mncpal_mnc" id="mncpal_mnc" required>
                        <option value="0"></option>
                        <?php foreach($base['municipalities'] as $munic): ?>
                            <option value="<?php echo e($munic['id']); ?>"><?php echo e($munic['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="mncpal_brgy">Barangay</label>
                    <select class="form-control" name="mncpal_brgy" id="mncpal_brgy" disabled>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="customer_type">Client Type</label>
                    <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
            The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
                    <small id="client_type_msg" style="color: red;"></small>
                    <select class="form-control" name="customer_type" id="customer_type">
                        <option></option>
                        <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?>
                            <option value="<?php echo e($sandgravel_types['id']); ?>"><?php echo e($sandgravel_types['description']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <label for="transaction_type">Transaction Type</label>
                    <select class="form-control" id="transaction_type" name="transaction_type" required>
                        <?php foreach($base['transaction_type'] as $transaction_type): ?>
                            <option value="<?php echo e($transaction_type->id); ?>"><?php echo e($transaction_type->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="bank_name">Bank Name</label>
                    <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" disabled>
                </div>
                <div class="form-group col-sm-4">
                    <label for="bank_number">Bank Number</label>
                    <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" disabled>
                </div>
                <div class="form-group col-sm-4">
                    <label for="bank_date">Bank Date</label>
                    <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" disabled> 
                </div>
                <div class="form-group col-sm-12">
                    <label for="">Remarks</label>
                    <textarea class="form-control" name="mncpal_remarks" id="mncpal_remarks"></textarea>
                </div>

                <div class="form-group col-sm-12">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th colspan="2">Account</th>
                                <th class="td_nature">Nature</th>
                                <th>Amount</th>
                                <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td><input type="text" id="total" class="form-control" readonly></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control account " required>
                                    <input type="hidden" class="form-control" name="account_id[]">
                                    <input type="hidden" class="form-control" name="account_type[]">
                                    <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                                    <input type="hidden" class="form-control">
                                    <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control nature" name="nature[]" maxlength="300" required>
                                </td>
                                <td class="td_amt">
                                    <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" required>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <br>
                <div class="form-group col-sm-12">
                    <button type="submit pull-left" class="btn btn-success" id="submit">SAVE</button>
                </div>
            <?php echo e(Form::close()); ?>

        </div>
        <div id="account_panel">
        </div>
    <?php endif; ?>

    <div class="form-group col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label>Year</label>
                <input type="number" name="year" id="year" class="form-control" value="<?php echo e(\Carbon\Carbon::now()->format('Y')); ?>">
            </div>
        </div>
        <br><br>
        <div class="col-md-12">
            <table class="table table-responsive table-hover" id="mncpal_tbl">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Receipt No.</th>
                        <th>Receipt Date</th>
                        <th>Payor/Customer</th>
                        <th>Municipality</th>
                        <th>Barangay</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('collection::form56.js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('collection::shared/transactions_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script type="text/javascript">
        $('#mncpal_mnc').change(function() {
            if($(this).val() > 0) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route("collection.ajax")); ?>',
                    data: {
                        '_token': '<?php echo e(csrf_token()); ?>',
                        'action': 'get_barangays',
                        'input': $('#mncpal_mnc').val(),
                    },
                    success: function(response) {
                        $('#mncpal_brgy').prop('disabled', false);
                        $('#mncpal_brgy').find('option')
                            .remove()
                            .end()
                            .prop('disabled', false).append('<option value=""></option>');

                        $.each( response, function(key, brgy) {
                            $('#mncpal_brgy').append($('<option>', {
                                'data-code' :brgy.code,
                                value: brgy.id,
                                text: brgy.name
                            }));
                        });
                    },
                    error: function(response) {

                    },
                });
            } else {
                $('#mncpal_brgy').prop('disabled', true);
            }
        });

        $('#transaction_type').change(function() {
            if ($(this).val() > 1) {
                $('#bank_name').prop('disabled', false);
                $('#bank_number').prop('disabled', false);
                $('#bank_date').prop('disabled', false);
                $('#bank_name').prop('required', true);
                $('#bank_number').prop('required', true);
                $('#bank_date').prop('required', true);
            } else {
                $('#bank_name').prop('disabled', true);
                $('#bank_number').prop('disabled', true);
                $('#bank_date').prop('disabled', true);
                $('#bank_name').prop('required', false);
                $('#bank_number').prop('required', false);
                $('#bank_date').prop('required', false);
            }
        });

        $.fn.loadTbl = function() {
            if($.fn.DataTable.isDataTable('#mncpal_tbl')) {
                $('#mncpal_tbl').DataTable().destroy();
            }
            $('#mncpal_tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?php echo e(route("mncpal.rcpt.dt")); ?>',
                    data: {
                        'year' : $('#year').val()
                    }
                },
                columns: [
                    { data: 'realname', name: 'realname' },
                    { data: 'rcpt_no', name: 'rcpt_no' },
                    { data: 'rcpt_date', name: 'rcpt_date' },
                    { data: 'name', name: 'name' },
                    { data: 'mnc_name', name: 'mnc_name' },
                    { data: 'brgy_name', name: 'brgy_name' },
                    { data: null, render: 
                        function(data) {
                            var view_route = "<?php echo e(route('mncpal.rcpt.view', 'id')); ?>";
                            var view_route2 = view_route.replace('id', data.id);
                            var edit_route = "<?php echo e(route('mncpal.rcpt.edit', 'id')); ?>";
                            var edit_route2 = edit_route.replace('id', data.id);
                            var cert_route = "<?php echo e(route('mncpal.cert', 'id')); ?>";
                            var cert_route2 = cert_route.replace('id', data.id);
                            var buttons = '<a title="View" href="'+view_route2+'" class="btn btn-info btn-small"><i class="fa fa-eye"></i></a>\
                                <a title="Edit" href="'+edit_route2+'" class="btn btn-info btn-small"><i class="fa fa-pencil"></i></a>';
                            if(data.get_cert == null) {
                                buttons += '<a title="Certificate" class="btn btn-gray btn-small" href="'+cert_route2+'"><i class="fa fa-certificate"></i></a>';
                            } else {
                                if(data.get_cert.col_rcpt_certificate_type_id == 1) {
                                    buttons += '<a title="Certificate" class="btn bg-cert-pp btn-small" href="'+cert_route2+'"><i class="fa fa-certificate"></i></a>';
                                } else if(data.get_cert.col_rcpt_certificate_type_id == 2) {
                                    buttons += '<a title="Certificate" class="btn bg-cert-ttc btn-small" href="'+cert_route2+'"><i class="fa fa-certificate"></i></a>';
                                } else if(data.get_cert.col_rcpt_certificate_type_id == 3) {
                                    buttons += '<a title="Certificate" class="btn bg-cert-sg btn-small" href="'+cert_route2+'"><i class="fa fa-certificate"></i></a>';
                                } else if(data.get_cert.col_rcpt_certificate_type_id == 4) {
                                    buttons += '<a title="Certificate" class="btn btn-small datatable-btn" href="'+cert_route2+'"><i class="fa fa-certificate"></i></a>';
                                }
                            }
                            return buttons;
                        }
                    },
                ]
            })
        }
        $.fn.loadTbl();
        $('#year').change(function() {
            $.fn.loadTbl();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>