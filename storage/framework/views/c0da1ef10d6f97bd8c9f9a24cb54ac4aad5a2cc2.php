

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Customer Name</dt>
            <dd><?php echo e($base['customer']->name); ?></dd>
            <dt>Customer Address</dt>
            <dd><?php echo e($base['customer']->address); ?></dd>
        </dl>
    </div>
</div>
<div class="row">
    <?php echo e(Form::open([ 'route' => ['customer.destroy', $base['customer']['id']], 'method' => 'delete', 'id' => 'form' ])); ?>

        <div class="form-group col-sm-12">

            <?php if( Session::get('permission')['col_customer'] & $base['can_write'] ): ?>
            <a href="<?php echo e(route('customer.edit', $base['customer']['id'])); ?>" class="btn btn-info datatable-btn">
                Update
            </a>
            <?php endif; ?>

            <?php if( Session::get('permission')['col_customer'] & $base['can_delete'] ): ?>
            <button type="button" id="delete" class="btn btn-danger datatable-btn pull-right">
                Delete
            </button>
            <?php endif; ?>

        </div>
    <?php echo e(Form::close()); ?>

</div>

<?php if( Session::get('permission')['col_receipt'] & $base['can_read'] ): ?>
<!-- <table id="seriallist" class="table table-striped table-hover" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Serial Number</th>
            <th>Date of Entry</th>
            <th>Actions</th>
        </tr>
    </thead>
</table> -->
<?php endif; ?>

<?php if( Session::get('permission')['col_receipt'] & $base['can_read'] ): ?>
<hr />
<form class="form-inline">
  <div class="form-group">
    <label for="show_year">YEAR</label>
    <input type="number" min="2017" max="<?php echo e($yr); ?>" class="form-control" name="show_year" id="show_year" placeholder="<?php echo e($yr); ?>" value="<?php echo e($yr); ?>">
  </div>

   <div class="form-group">
    <label for="show_mnth">Month</label>
    <select class="form-control" name="show_mnth" id="show_mnth">
        <option value="ALL">ALL</option>
            <?php foreach( $base['months'] as $mkey => $month): ?>
                <?php if($mnth == $mkey): ?>
                    <option value="<?php echo e($mkey); ?>" selected><?php echo e($month); ?> </option>

                <?php else: ?>
                    <option value="<?php echo e($mkey); ?>"><?php echo e($month); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
    </select>
  </div>
<button type="submit" class="btn btn-default" onclick="$(this).loadTable();" >SHOW</button>


</form>
<hr />
<h3> RECEIPT INFO</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Date of Entry</th>
            <th>Serial Number</th>
            <th>Remarks</th>
            <th>Account</th>
            <th>Nature</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $r_total = 0;
        ?>
        <?php foreach($receipts as $k => $receipt): ?>
        <?php
            $row_span = count($receipt->items);
            $rr = 0;
        ?>
            <tr>
                <td rowspan="<?php echo e($row_span); ?>"><?php echo e(\Carbon\Carbon::parse($receipt->date_of_entry)->toFormattedDateString()); ?></td>
                <td rowspan="<?php echo e($row_span); ?>"><?php echo e($receipt->serial_no); ?></td>
                <td rowspan="<?php echo e($row_span); ?>">
                    <?php
                        $receipt_rem = preg_replace('/[^A-Za-z0-9&\s]+/', "", trim(strip_tags($receipt->remarks)));
                        $receipt_rem = preg_replace('/&nbsp/', "", $receipt_rem);
                        $receipt_rem = preg_replace('~\R~', "", $receipt_rem);

                        $bank_rem = preg_replace('/[^A-Za-z0-9&\s]+/', "", trim(strip_tags($receipt->bank_remark)));
                        $bank_rem = preg_replace('/&nbsp/', "", $bank_rem);
                        $bank_rem = preg_replace('~\R~', "", $bank_rem);
                        if($receipt->certificate != null){
                            $cert_rem = preg_replace('/[^A-Za-z0-9&\s]+/', "", trim(strip_tags($receipt->certificate->detail)));
                            $cert_rem = preg_replace('/&nbsp/', '', $cert_rem);
                            $cert_rem = preg_replace('~\R~', '', $cert_rem);
                        }
                    ?>
                    <?php if(strcasecmp(strtoupper($receipt_rem), strtoupper($bank_rem)) != 0): ?>
                        <?php echo $receipt->remarks; ?><br />
                        <?php echo $receipt->bank_remark; ?>

                        
                        <?php if(!empty($receipt->certificate)): ?>
                            <?php if(strcasecmp(strtoupper($receipt_rem), strtoupper($cert_rem)) != 0 && strcasecmp(strtoupper($bank_rem), strtoupper($cert_rem)) != 0): ?>
                                <?php echo $cert_rem; ?> 
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php echo $receipt->remarks; ?><br />
                        <?php if(!empty($receipt->certificate)): ?>
                            <?php if(strcasecmp($receipt_rem, $cert_rem) != 0): ?>
                                <?php echo $cert_rem; ?> 
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                    <?php
                        $tr = '';
                        foreach($receipt->items as $i => $item):
                            $acct = '';
                            $nature = '';
                            if($item->col_acct_title_id != 0){
                                 $acct = $item->acct_title->name;
                            }else{
                                $acct = $item->acct_subtitle->name;
                            }

                            if($rr > 0):
                                $tr .= '<tr>';
                            endif;
                            $r_total += $item->value;
                            $tr .= '<td>'.$acct.'</td>
                                    <td>'.$item->nature.'</td>
                                    
                                    <td class="text-right">'.number_format($item->value,2).'</td>
                                </tr>';
                                $rr++;
                        endforeach;
                    ?>

                 <?php echo $tr; ?>


        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="5">Total</th>
            <th class="text-right"><?php echo e(number_format($r_total,2)); ?></th>
        </tr>
    </tfoot>
</table>
<hr />

<?php if(!($cashdivs->isEmpty())): ?>

<h3> CASH DIVISION INFO</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Date of Entry</th>
            <th>Account</th>
            <th>Nature</th>
            <th>Amount</th>

        </tr>
    </thead>
    <tbody>
        <?php
            $c_total = 0;
        ?>
        <?php foreach($cashdivs as $k => $cashdiv): ?>
        <?php
            $crow_span = count($cashdiv->items);
            $cc = 0;
        ?>
            <tr>
                <td rowspan="<?php echo e($crow_span); ?>"><?php echo e(\Carbon\Carbon::parse($cashdiv->date_of_entry)->toFormattedDateString()); ?></td>
                    <?php
                        $ctr = '';
                        foreach($cashdiv->items as $i => $item):
                            $acct = '';
                            $nature = '';
                            if($item->col_acct_title_id != 0){
                                 $acct = $item->acct_title->name;
                            }else{
                                $acct = $item->acct_subtitle->name;
                            }

                            if($cc > 0):
                                $ctr .= '<tr>';
                            endif;
                            $c_total += $item->value;
                            $ctr .= '<td>'.$acct.'</td>
                                    <td>'.$item->nature.'</td>
                                    <td class="text-right">'.number_format($item->value,2).'</td>
                                </tr>';
                                $cc++;
                        endforeach;
                    ?>
                 <?php echo $ctr; ?>


        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th class="text-right"><?php echo e(number_format($c_total,2)); ?></th>
        </tr>
    </tfoot>
</table>

<hr />
<?php endif; ?>

<h3>MUNICIPAL RECEIPTS</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Receipt Date</th>
            <th>Serial Number</th>
            <th>Remarks</th>
            <th>Account</th>
            <th>Nature</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($mncpal_receipts as $rcpt): ?>
            <?php
                $items_count = count($rcpt->getItems);
            ?>
            <tr>
                <td rowspan="<?php echo e($items_count); ?>"><?php echo e(\Carbon\Carbon::parse($rcpt->rcpt_date)->toFormattedDateString()); ?></td>
                <td rowspan="<?php echo e($items_count); ?>"><?php echo e($rcpt->rcpt_no); ?></td>
                <td rowspan="<?php echo e($items_count); ?>"><?php echo $rcpt->remarks; ?></td>
                <?php foreach($rcpt->getItems as $item): ?>
                        <td>
                            <?php if($item->col_acct_title_id > 0): ?>
                                <?php echo e($item->getAccount->name); ?>

                            <?php else: ?>
                                <?php echo e($item->getSubAccount->name); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php echo e($item->nature); ?></td>
                        <td><?php echo e($item->value); ?></td>
                    </tr>
                <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
    $xcount = 0;
?>
<?php foreach($receipts as $k => $receipt): ?>
    <?php
        $xcount += count($receipt->sgbooklet);
    ?>
<?php endforeach; ?>
<?php if($xcount > 0): ?>

<h3> SAND and GRAVEL BOOKLET</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Date of Entry</th>
            <th>Serial Number</th>
            <th>SG BOOKLET START</th>
            <th>SG BOOKLET END</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($receipts as $k => $receipt): ?>
            <?php
                $row_spansg = count($receipt->sgbooklet);
                $rr = 0;
            ?>
             <tr>
                <?php if($row_spansg > 0): ?>
                    <td rowspan="<?php echo e($row_spansg); ?>"><?php echo e(\Carbon\Carbon::parse($receipt->date_of_entry)->toFormattedDateString()); ?></td>
                    <td rowspan="<?php echo e($row_spansg); ?>"><?php echo e($receipt->serial_no); ?></td>
                <?php else: ?>
                    <td><?php echo e(\Carbon\Carbon::parse($receipt->date_of_entry)->toFormattedDateString()); ?></td>
                    <td><?php echo e($receipt->serial_no); ?></td>
                <?php endif; ?>
                <?php
                    $tr = '';
                    foreach($receipt->sgbooklet as $i => $sgbooklet):
                        if($rr > 0):
                            $tr .= '<tr>';
                        endif;
                        if($row_spansg > 0):
                            $tr .= '
                                <td>'.$sgbooklet->booklet_start.'</td>
                                <td>'.$sgbooklet->booklet_end.'</td>
                            </tr>';
                        else:
                            $tr .= '
                                <td></td>
                                <td></td>
                            </tr>';
                        endif;

                        $rr++;
                    endforeach;
                ?>
                <?php echo $tr; ?>

        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php endif; ?>

<div id="delete_confirm">
    Are you sure you want to delete customer/payor: '<?php echo e($base['customer']->name); ?>'?
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo e(Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js')); ?>

<?php echo e(Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js')); ?>

<script type="text/javascript">
    $('#delete').click( function() {
        $('#delete_confirm').dialog('open');
    });

    $('#delete_confirm').dialog({
        autoOpen: false,
        draggable:false,
        modal: true,
        resizable: false,
        title: 'Delete Customer/Payor?',
        width: 'auto',
        buttons: {
            'Delete': function() {
                $('#form').submit();
            },

            'Cancel': function() {
                $(this).dialog('close');
            },
        },
    });

    $(document).on('keyup', '#show_year', function() {
        var route = $('#rpt_rec').attr('href');
        var route2 = route.replace('year', $('#show_year').val());
        $('#rpt_rec').attr('href', route2);
    });

    $(document).on('change', '#show_mnth', function() {
        var route = $('#rpt_rec').attr('href');
        var route2 = route.replace('month', $('#show_mnth').val());
        $('#rpt_rec').attr('href', route2);
    });

    $(document).on('click', '#rpt_rec', function(e) {
        var route = $('#rpt_rec').attr('href');
        var route2 = route.replace('month', $('#show_mnth').val());
        var route3 = route2.replace('year', $('#show_year').val());
        $('#rpt_rec').attr('href', route3);
    }); 

    $('#seriallist').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '<?php echo e(route("collection.datatables", ["customer_rcpt", "customer" => $base["customer"]->id])); ?>',

        columns: [
            { data: 'serial_no', name: 'serial_no' },
            { data: 'date_of_entry', name: 'date_of_entry' },
            { data:
                function(data) {
                    var view = '';
                    <?php if( Session::get('permission')['col_receipt'] & $base['can_read'] ): ?>
                    view = '<a href="<?php echo e(route('receipt.index')); ?>/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    <?php endif; ?>
                    return view;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });


     // $('#seriallist_nw').DataTable();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>