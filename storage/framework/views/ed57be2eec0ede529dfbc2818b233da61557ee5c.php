

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

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
    <?php echo e(Form::open(['method' => 'PATCH', 'route' => ['cash_division.update', $base['addtl']->id]])); ?>

    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd><?php echo e($base['user']->realname); ?></dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo e($base['user']->id); ?>">
    </div>

    <div class="form-group col-sm-6">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="<?php echo e(date('m/d/Y', strtotime($base['addtl']->date_of_entry))); ?>" required autofocus>
    </div>

    <div class="form-group col-sm-6">
        <label for="refno">Reference No.</label>
        <input type="text" class="form-control" name="refno" value="<?php echo e($base['addtl']->refno); ?>" required>
    </div>



    <div class="form-group col-sm-6">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            <?php if($base['addtl']->col_municipality_id == ''): ?>
            <option selected disabled></option>
            <?php else: ?>
            <option disabled></option>
            <?php endif; ?>

            <?php foreach($base['municipalities'] as $municipality): ?>
                <?php if($base['addtl']->col_municipality_id == $municipality['id']): ?>
                <option value="<?php echo e($municipality['id']); ?>" selected><?php echo e($municipality['name']); ?></option>
                <?php else: ?>
                <option value="<?php echo e($municipality['id']); ?>"><?php echo e($municipality['name']); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group col-sm-6">
        <label for="barangay">Barangay</label>
        <?php if(!empty($base['barangays'])): ?>
        <select class="form-control" name="brgy" id="brgy">
        <?php else: ?>
        <select class="form-control" name="brgy" id="brgy" disabled>
        <?php endif; ?>
            <option value="0"></option>
            <?php if(!empty($base['barangays'])): ?>
                <?php foreach($base['barangays'] as $brgy): ?>
                    <?php if($base['addtl']->col_barangay_id == $brgy['id']): ?>
                    <option value="<?php echo e($brgy['id']); ?>" selected><?php echo e($brgy['name']); ?></option>
                    <?php else: ?>
                    <option value="<?php echo e($brgy['id']); ?>"><?php echo e($brgy['name']); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>



    <div class="form-group col-sm-6">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" value="<?php echo e($base['addtl']->customer->name); ?>" >
        <input type="hidden" class="form-control" name="customer_id" id="customer_id" value="<?php echo e($base['addtl']->customer->id); ?>">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
             <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?>
                <?php if($sandgravel_types['id'] == $base['addtl']->client_type ): ?>
                    <option value="<?php echo e($sandgravel_types['id']); ?>" selected><?php echo e($sandgravel_types['description']); ?></option>
                <?php else: ?>
                    <option value="<?php echo e($sandgravel_types['id']); ?>"><?php echo e($sandgravel_types['description']); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>
    </div>


    <div class="form-group col-sm-2">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" required="">

            <?php if( $base['addtl']->sex == 'male' ): ?>
                <option value="female">Female</option>
                <option value="male" selected="">Male</option>
            <?php elseif($base['addtl']->sex == 'female'): ?>
                <option value="female" selected="">Female</option>
                <option value="male">Male</option>
            <?php else: ?>
                 <option selected="" ></option>
                <option value="female" >Female</option>
                <option value="male">Male</option>
            <?php endif; ?>
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

                <?php foreach($base['addtl']->items as $i => $item): ?>
                <tr>
                    <td>
                        <?php if($item->acct_title != null): ?>
                        <input type="text" class="form-control account" value="<?php echo e($item->acct_title->name .' ('. $item->acct_title->group->category->name .')'); ?>" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="<?php echo e($item->acct_title->id); ?>">
                        <input type="hidden" class="form-control" name="account_type[]" value="title">
                            <?php if(isset($item->acct_title->rate->is_shared)): ?>
                            <input type="hidden" class="form-control account_is_shared" value="<?php echo e($item->acct_title->rate->is_shared); ?>" name="account_is_shared[]">
                            <?php else: ?>
                            <input type="hidden" class="form-control account_is_shared" value="" name="account_is_shared[]">
                            <?php endif; ?>
                        <?php else: ?>
                        <input type="text" class="form-control account" value="<?php echo e($item->acct_subtitle->name .' ('. $item->acct_subtitle->title->group->category->name .')'); ?>" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="<?php echo e($item->acct_subtitle->id); ?>">
                        <input type="hidden" class="form-control" name="account_type[]" value="subtitle">
                            <?php if(isset($item->acct_title->rate->is_shared)): ?>
                            <input type="hidden" class="form-control account_is_shared" value="<?php echo e($item->acct_title->rate->is_shared); ?>" name="account_is_shared[]">
                            <?php else: ?>
                            <input type="hidden" class="form-control account_is_shared" value="" name="account_is_shared[]">
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                    </td>
                    <td>
                        <?php /* <?php if($item->acct_title != null): ?>
                        <input type="text" class="form-control" name="nature[]" value="<?php echo e($item->acct_title->name); ?>" maxlength="300" required>
                        <?php else: ?>
                        <input type="text" class="form-control" name="nature[]" value="<?php echo e($item->acct_subtitle->name); ?>" maxlength="300" required>
                        <?php endif; ?> */ ?>
                        <input type="text" class="form-control" name="nature[]" value="<?php echo e($item->nature); ?>" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" value="<?php echo e($item->value); ?>" required>
                    </td>
                    <td>
                    <?php if($i > 0): ?>
                        <button class="btn btn-warning btn-sm rem_row" type="button">
                        <i class="fa fa-minus"></i>
                        </button>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    <?php echo e(Form::close()); ?>

</div>

<div id="account_panel">
</div>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo e(Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js')); ?>

<?php echo e(Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js')); ?>

<?php echo e(Html::script('/vendor/autocomplete/jquery.autocomplete.js')); ?>

<script type="text/javascript">
    var collection_type = 'show_in_cashdivision';
</script>

<?php echo $__env->make('collection::shared.transactions_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>