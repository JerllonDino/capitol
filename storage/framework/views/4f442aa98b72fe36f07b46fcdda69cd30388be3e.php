

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="form-group col-sm-6">
        <label for="month">Month</label>
        <select name="month" class="form-control" required disabled>
            <?php foreach($base['months'] as $i => $month): ?>
                <?php if($base['month'] == ($i + 1)): ?>
                    <option value="<?php echo e($i + 1); ?>" selected><?php echo e($month); ?></option>
                <?php else: ?>
                    <option value="<?php echo e($i + 1); ?>"><?php echo e($month); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="year">Year</label>
        <input type="number" class="form-control" step="1" name="year" value="<?php echo e($base['year']); ?>" readonly>
    </div>

    <div class="col-sm-12">
        <table class="table">

        <?php foreach($data['category'] as $category): ?>
            <tr>
                <td><div class="col-sm-12"><b><?php echo e($category->name); ?></b></div></td>
                <td><div class="col-sm-12 text-center">Value</div></td>
                <td style="width:20px; max-width:20px;"></td>
                <td><div class="col-sm-12 text-center">Reconciliation</div></td>
                <td></td>
                <td><div class="col-sm-12 text-center">Total</div></td>
            </tr>

            <?php foreach($category->group as $group): ?>
                <tr>
                    <td><div class="col-sm-12"><?php echo e($group->name); ?></div></td>
                    <td><div class="col-sm-12"></div></td>
                      <td></td>
                     <td><div class="col-sm-12"></div></td>
                     <td></td>
                     <td><div class="col-sm-12"></div></td>
                </tr>

                <?php foreach($group->title as $title): ?>
                 <?php
                    $total_value = 0;
                ?>
                    <tr>
                        <td><div class="col-sm-11 col-sm-offset-1"><?php echo e($title->name); ?></div></td>
                        <td>
                            <div class="col-sm-12">
                                <?php
                                    $title_mnthly_prov_incme = $title->mnhtly_prov_income()->where('year','=',$base['year'])->where('month','=',$base['month'])->first();
                                ?>
                                <?php if( $title_mnthly_prov_incme ): ?>
                                     <input class="form-control" type="number" step="0.01" name="title_value[]" value="<?php echo e($title_mnthly_prov_incme->value); ?>" readonly>
                                    <input class="form-control" type="hidden" name="title_id[]" value="<?php echo e($title->id); ?>">
                                <?php else: ?>
                                    <input class="form-control" type="number" step="0.01" name="title_value[]" value="0" readonly>
                                    <input class="form-control" type="hidden" name="title_id[]" value="<?php echo e($title->id); ?>">
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <strong>+</strong>
                        </td>
                         <td>
                            <div class="col-sm-12">

                                <?php if( $title_mnthly_prov_incme ): ?>
                                <?php $total_value = $title_mnthly_prov_incme->reconciliation_value + $title_mnthly_prov_incme->value ; ?>
                                     <input class="form-control title_reconciliation" type="number" step="0.01" name="title_reconciliation[]" value="<?php echo e($title_mnthly_prov_incme->reconciliation_value); ?>" readonly>
                                <?php else: ?>
                                    <input class="form-control title_reconciliation" type="number" step="0.01" name="title_reconciliation[]" value="0" readonly>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <strong>=</strong>
                        </td>
                        <td>
                            <input type="text"  class="form-control" name="total_value[]" readonly value="<?php echo e($total_value); ?>" />
                        </td>
                    </tr>

                    <?php foreach($title->subs as $subs): ?>
                     <?php
                                     $subtotal_value = 0;
                    ?>
                        <tr>
                            <td><div class="col-sm-10 col-sm-offset-2"><?php echo e($subs->name); ?></div></td>
                            <td>
                                <div class="col-sm-12">
                                <?php
                                    $substitle_mnthly_prov_incme = $subs->mnhtly_prov_income()->where('year','=',$base['year'])->where('month','=',$base['month'])->first();
                                ?>

                                <?php if( $substitle_mnthly_prov_incme ): ?>
                                    <input class="form-control" type="number" step="0.01" name="subtitle_value[]" value="<?php echo e($substitle_mnthly_prov_incme->value); ?>" readonly>
                                    <input class="form-control" type="hidden" name="subtitle_id[]" value="<?php echo e($subs->id); ?>">
                                <?php else: ?>
                                    <input class="form-control" type="number" step="0.01" name="subtitle_value[]" value="0">
                                    <input class="form-control" type="hidden" name="subtitle_id[]" value="<?php echo e($subs->id); ?>">
                                <?php endif; ?>
                                </div>
                            </td>
                            <td>
                            <strong>+</strong>
                        </td>
                            <td>
                                <div class="col-sm-12">
                                <?php if( $substitle_mnthly_prov_incme ): ?>
                                <?php $subtotal_value = $substitle_mnthly_prov_incme->reconciliation_value + $substitle_mnthly_prov_incme->value;  ?>
                                    <input class="form-control subtitle_reconciliation" type="number" step="0.01" name="subtitle_reconciliation[]" value="<?php echo e($substitle_mnthly_prov_incme->reconciliation_value); ?>">
                                <?php else: ?>
                                    <input class="form-control subtitle_reconciliation" type="number" step="0.01" name="subtitle_reconciliation[]" value="0">
                                <?php endif; ?>
                                </div>
                            </td>

                            <td>
                            <strong>=</strong>
                        </td>

                        <td>
                            <input type="text"  class="form-control" name="subtotal_value[]" readonly value="<?php echo e($subtotal_value); ?>" />
                        </td>
                        </tr>

                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>

        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>