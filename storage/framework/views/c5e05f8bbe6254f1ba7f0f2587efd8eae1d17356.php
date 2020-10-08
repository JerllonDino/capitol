

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
<?php if( Session::get('permission')['col_cash_division'] & $base['can_write'] ): ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['cash_division.store']])); ?>

    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['user']->realname); ?></dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo e($base['user']->id); ?>">
        <input type="hidden" class="form-control" name="transaction" id="transaction" value="cash_division">
    </div>



    <div class="form-group col-sm-6">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="<?php echo e(date('m/d/Y')); ?>" required autofocus>
    </div>

    <div class="form-group col-sm-6">
        <label for="refno">Reference No.</label>
        <!--<input type="text" class="form-control" name="refno" value="" required>-->
         <textarea class="form-control" name="refno" value="" rows="2" required></textarea> 
    </div>

    <div class="form-group col-sm-6">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            <option selected></option>
            <?php foreach($base['municipalities'] as $municipality): ?>
                <option value="<?php echo e($municipality['id']); ?>"><?php echo e($municipality['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="barangay">Barangay</label>
        <select class="form-control" name="brgy" id="brgy" disabled>
        </select>
    </div>

     <div class="form-group col-sm-6">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" >
        <input type="hidden" class="form-control" name="customer_id" id="customer_id">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
             <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?>
                <option value="<?php echo e($sandgravel_types['id']); ?>"><?php echo e($sandgravel_types['description']); ?></option>
            <?php endforeach; ?>
            </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" >
            <option selected></option>
            <option value="female">Female</option>
            <option value="male">Male</option>

        </select>
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
                        <input type="text" class="form-control account" required>
                        <input type="hidden" class="form-control" name="account_id[]">
                        <input type="hidden" class="form-control" name="account_type[]">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" required>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    <?php echo e(Form::close()); ?>

</div>
<hr>

<div id="account_panel">
</div>

<div class="panel panel-default">
    <div class="panel-heading"><b>Adjustment</b></div>
    <div class="panel-body">
        <form action="<?php echo e(route('cashdiv.adjustment_add')); ?>" method="post" autocomplete="off">
            <?php echo e(csrf_field()); ?>

            <div class="form-group col-md-3">
                <label>Year</label>
                <select class="form-control" name="adj_yr" required> 
                    <option></option>
                    <?php
                        $year = \Carbon\Carbon::now()->format('Y');
                        for (; $year > 2015; $year--) { 
                            echo "<option value='".$year."'>".$year."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Month</label>
                <select class="form-control" name="adj_mnth" required>
                    <option></option>
                    <?php 
                        $month = ['1'=>'January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        foreach ($month as $key => $val) { 
                            echo "<option value='".$key."'>".$val."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Type</label>
                <select class="form-control" name="adj_type" required>
                    <option></option>
                    <option value="OPAg">OPAg</option>
                    <option value="PVET">PVET</option>
                    <option value="COLD CHAIN">COLD CHAIN</option>
                    <option value="CERTIFICATIONS OPP - DOJ">CERTIFICATIONS OPP - DOJ</option>
                    <option value="PROVINCIAL HEALTH OFFICE">PROVINCIAL HEALTH OFFICE</option>
                    <option value="RPT">RPT</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Adjustment Amount</label>
                <input type="number" step="0.01" name="adj_amt" class="form-control" required>
            </div>
            <br>
            <button class="btn btn-success" type="submit">Add</button>
            <a href="<?php echo e(route('cashdiv.adjustment_view')); ?>" class="btn btn-info">View Adjustments</a>
        </form>
    </div>
</div>
<?php endif; ?>
<?php if( Session::get('permission')['col_cash_division'] & $base['can_read'] ): ?>
<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>User</th>
            <th>Date</th>
            <th>REFNO</th>
            <th>CUSTOMER</th>
            <th>MUNICIPALITY</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo e(Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js')); ?>

<?php echo e(Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js')); ?>

<?php echo e(Html::script('/base/sweetalert/sweetalert2.min.js')); ?>

<script type="text/javascript">

 $.fn.loadTable = function(){
  if ( $.fn.DataTable.isDataTable('#seriallist') ) {
  $('#seriallist').DataTable().destroy();
}
    $('#seriallist').dataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '<?php echo e(route("collection.datatables", "cash_division")); ?>',
        columns: [
            { data: 'realname', name: 'realname' },
            { data:
                function(data) {
                    var date = new Date(data.date_of_entry);
                    var month = date.toLocaleString('en-us', {month: 'long'});
                    return month +' '+ date.getDate() +', '+ date.getFullYear();
                }
            },
            { data: 'refno', name: 'refno' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'name', name: 'name' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    var deletez = '';
                    <?php if( Session::get('permission')['col_cash_division'] & $base['can_read'] ): ?>
                    view = '<a href="<?php echo e(route('cash_division.index')); ?>/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>
                    <?php if( Session::get('permission')['col_cash_division'] & $base['can_write'] ): ?>
                    write = '<a href="<?php echo e(route('cash_division.index')); ?>/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    <?php endif; ?>
                    <?php if( Session::get('permission')['col_cash_division'] & $base['can_write'] ): ?>
                    if(data.deleted_at == null){
                        deletez = view + write+'<button onclick="$(this).deolete(\''+data.id+'\');"  class="btn btn-sm btn-danger datatable-btn" title="Edit"><i class="fa fa-trash"></i></button>';
                    }else{
                        deletez = '<button onclick="$(this).restore(\''+data.id+'\');"  class="btn btn-sm btn-warning datatable-btn" title="Edit"><i class="fa fa-undo"></i></button>';
                    }

                    <?php endif; ?>
                    return deletez;
                },
                bSortable: false,
                searchable: false,
            },
        ]
    });
}

    $.fn.deolete = function(deleteid){
        swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#a22314',
              cancelButtonColor: '#c9bebe',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                    $.ajax({
                        url: '<?php echo e(route("cash_div.delete")); ?>',
                        type: 'POST',
                        data:{
                          cash_div: deleteid,
                          _token: '<?php echo e(csrf_token()); ?>'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Deleted!',
                      text: 'Cash Div Data deleted',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        $.fn.loadTable();
                      }
                    })


              }
            });

    };
    $.fn.restore = function(deleteid){
        swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#a22314',
              cancelButtonColor: '#c9bebe',
              confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
              if (result.value) {
                    $.ajax({
                        url: '<?php echo e(route("cash_div.restore")); ?>',
                        type: 'POST',
                        data:{
                          cash_div: deleteid,
                          _token: '<?php echo e(csrf_token()); ?>'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Restored!',
                      text: 'Cash Div Data restored',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        $.fn.loadTable();
                      }
                    })


              }
            });

    };
     $.fn.loadTable();
</script>
<?php echo e(Html::script('/vendor/autocomplete/jquery.autocomplete.js')); ?>

<script type="text/javascript">
    var collection_type = 'show_in_cashdivision';
</script>
<?php echo $__env->make('collection::shared.transactions_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>