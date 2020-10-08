<center><strong>SAND AND GRAVEL TAX/ PENALTIES COLLECTED <br/><br> <u>FOR THE PERIOD <?php echo e($datex->format('F')); ?> <?php echo e($year); ?></u></strong></center><br>

<table>
    <thead>
        <tr>
            <th rowspan="2">DATE<br/><?php echo e($year); ?></th>
            <th rowspan="2">OFFICIAL<br/>RECEIPT NO.</th>
            <th rowspan="2">MONITORING<br/>PENALTIES</th>
            <th rowspan="2">PROVINCIAL<br/>CONTRACTORS</th>
            <th rowspan="1" colspan="2">S & G PERMITTEES</th>
            <th rowspan="2">MUNICIPAL<br/>REMITTANCES</th>
            <th rowspan="2">TOTALS</th>
        </tr>

        <tr>
            <th>INDUSTRIAL</th>
            <th>COMMERCIAL</th>
        </tr>

        <tr>
            <th><?php echo e($datex->format('F')); ?></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>0.00</th>
        </tr>
    </thead>
    <tbody>

        <?php
            $total = 0;
            $total1 = 0;
            $total2 = 0;
            $total5 = 0;
            $total6 = 0;
            $total16 = 0;
        ?>
        <?php foreach($dailygraveltypes as $key => $dly ): ?>
            <?php if(is_array($dly)): ?> 
                <?php foreach($dly as $c_type => $rcpt): ?>
                    <?php if(is_array($rcpt)): ?> 
                        <?php foreach($rcpt as $key2 => $value): ?>
                            <?php
                                $total += !is_null($value) ? $value : 0;
                                $total1 += $c_type == 1 ? $value : 0;
                                $total2 += $c_type == 2 ? $value : 0;
                                $total5 += $c_type == 5 ? $value : 0;
                                $total6 += $c_type == 6 ? $value : 0;
                                $total16 += $c_type == 16 ? $value : 0;
                            ?>
                            <tr>
                                <td><?php echo e($key); ?></td> 
                                <td><?php echo e($key2); ?></td> 
                                <td><?php echo e($c_type == 1 ? number_format($value,2) : ''); ?></td>
                                <td><?php echo e($c_type == 2 ? number_format($value,2) : ''); ?></td>
                                <td><?php echo e($c_type == 5 ? number_format($value,2) : ''); ?></td>
                                <td><?php echo e($c_type == 6 ? number_format($value,2) : ''); ?></td>
                                <td><?php echo e($c_type == 16 ? number_format($value,2) : ''); ?></td>
                                <td><?php echo e(number_format($value,2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php
                            $total += !is_null($rcpt) ? $rcpt : 0;
                            $total1 += $c_type == 1 ? $rcpt : 0;
                            $total2 += $c_type == 2 ? $rcpt : 0;
                            $total5 += $c_type == 5 ? $rcpt : 0;
                            $total6 += $c_type == 6 ? $rcpt : 0;
                            $total16 += $c_type == 16 ? $rcpt : 0;
                        ?>
                        <tr>
                            <td><?php echo e($key); ?></td> 
                            <td></td> 
                            <td><?php echo e($c_type == 1 ? number_format($rcpt,2) : ''); ?></td>
                            <td><?php echo e($c_type == 2 ? number_format($rcpt,2) : ''); ?></td>
                            <td><?php echo e($c_type == 5 ? number_format($rcpt,2) : ''); ?></td>
                            <td><?php echo e($c_type == 6 ? number_format($rcpt,2) : ''); ?></td>
                            <td><?php echo e($c_type == 16 ? number_format($rcpt,2) : ''); ?></td>
                            <td><?php echo e(number_format($rcpt,2)); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total Collections for Sharing</th>
            <th><?php echo e(number_format($total1,2)); ?></th>
            <th><?php echo e(number_format($total2,2)); ?></th>
            <th><?php echo e(number_format($total5,2)); ?></th>
            <th><?php echo e(number_format($total6,2)); ?></th>
            <th><?php echo e(number_format($total16,2)); ?></th>
            <th><?php echo e(number_format($total,2)); ?></th>
        </tr> 

        <tr>
            <th colspan="7">Total Provincial Share <?php echo e($datex->format('F')); ?> <?php echo e($year); ?> </th>
            <th><?php echo e(number_format($provShare,2)); ?></th>
        </tr>
    </tfoot>    
</table>

<table>
    <tr>
        <th colspan="3"><u>SUMMARY</u></th>
    </tr>
    <tr>
        <th colspan="3">&nbsp;</th>
    </tr>
    <tr>
        <td colspan="3"><u>Sand and Gravel Permittess:</u></td>
    </tr>

    <?php ($typestotal = 0); ?>
    <?php if(isset($graveltypes['Commercial'])): ?>
        <?php ($typestotal += $graveltypes['Commercial']->value); ?>
    <tr>
        <td></td>
        <td>Commercial</td>
        <td><?php echo e(number_format($total6,2)); ?></td>
    </tr>
    <?php endif; ?>
    <?php if(isset($graveltypes['Industrial '])): ?>
        <?php ($typestotal += $graveltypes['Industrial ']->value); ?>
    <tr>
        <td></td>
        <td>Industrial</td>
        <td><?php echo e(number_format($total5,2)); ?></td>
    </tr>

    <?php else: ?>
    <tr>
        <td></td>
        <td>Industrial</td>
        <td><?php echo e(number_format(0,2)); ?></td>
    </tr>
    <?php endif; ?>

    <tr>
        <td colspan="3"><u>Projects</u></td>
    </tr>

    <?php if(isset($graveltypes['Contractors (Prov.)'])): ?>
    <?php ($typestotal += $graveltypes['Contractors (Prov.)']->value); ?>
    <tr>
        <td></td>
        <td>Provincial</td>
        <td><?php echo e(number_format($total2,2)); ?></td>
    </tr>
    <?php endif; ?>

    <tr>
        <td></td>
        <td colspan="2"></td>
    </tr>

    <tr>
        <td></td>
        <td colspan="2"></td>
    </tr>

    <?php if(isset($graveltypes['Monitoring'])): ?>
    <?php ($typestotal += $graveltypes['Monitoring']->value); ?>
    <tr>
        <td><u>Sand and Gravel Penalties Through Monitoring</u></td>
        <td></td>
        <td><?php echo e(number_format($total1,2)); ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <td><u>Municipal Remittances</u></td>
        <td></td>
        <td><?php echo e(number_format($total16,2)); ?></td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td>TOTAL</td>
        <td><?php echo e(number_format($total,2)); ?></td>
    </tr>

</table>

<br />
    <table>
    <tbody>
        <tr>
            <td>Prepared by:<br><br></td>
            <td>&nbsp;</td>
        </tr>
         <tr>
             <td></td>

             <?php 
                $STR = strtolower($acctble_officer_name->value);
                $STR = strtoupper($STR);
              ?>
            <td><?php echo e($STR); ?></td>
        </tr>
        <tr>
             <td></td>
            <td><?php echo e($acctble_officer_position->value); ?></td>
        </tr>


    </tbody>
</table>

