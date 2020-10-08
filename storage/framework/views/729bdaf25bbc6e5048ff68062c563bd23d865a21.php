<?php
	$sumx = array_sum($base['past_month']);
	$curr_mnth = $base['date_mnth']->format('M');
	$curr_mnth_full = $base['date_mnth']->format('F');
	$prev_mnth_full = $base['date_mnth']->subMonth()->format('F');
	$prev_mnth = $base['date_mnth']->subMonth()->format('M');
?>
<table>
	<tbody>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr><td colspan="16" style="text-align: center;"><strong>Republic of the Philippines</strong></td></tr>
	    <tr><td colspan="16" style="text-align: center;"><strong>PROVINCE OF BENGUET</strong></td></tr>
	    <tr><td colspan="16" style="text-align: center;"><strong>La Trinidad</strong></td></tr>
	    <tr><td colspan="16" style="text-align: center;"><strong>OFFICE OF THE PROVINCIAL TREASURER</strong></td></tr>
	    <tr></tr>
	    <tr></tr>
	    <tr><td colspan="16" style="text-align: center;"><strong>DISTRIBUTION OF SAND AND GRAVEL TAX & PENALTY COLLECTIONS</strong></td></tr>
	    <?php if($curr_mnth == "Jan"): ?>
	    <tr><td colspan="16" style="text-align: center;"><strong>For the Period <?php echo e($curr_mnth_full); ?> <?php echo e(\Carbon\Carbon::parse($curr_mnth_full)->startOfMonth()->format('d')); ?>-<?php echo e(\Carbon\Carbon::parse($curr_mnth_full)->endOfMonth()->format('d')); ?>, <?php echo e($base['year']); ?></strong></td></tr>
		<?php else: ?>
		<tr><td colspan="16" style="text-align: center;"><strong>For the Period <?php echo e($prev_mnth_full); ?> 1-<?php echo e($curr_mnth_full); ?> <?php echo e(\Carbon\Carbon::parse($curr_mnth_full)->endOfMonth()->format('d')); ?>, <?php echo e($base['year']); ?></strong></td></tr>
		<?php endif; ?>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<!-- <th rowspan="2" colspan="1">DATE</th>
			<th rowspan="2" colspan="1">PROVINCIAL SHARE</th>
			<th rowspan="1" colspan="13" style="text-align: center;">Sources of Aggregates</th>
			<th rowspan="2" colspan="1">TOTAL</th> -->
			<th>DATE</th>
			<th>PROVINCIAL SHARE</th>
			<th colspan="13" style="text-align: center;">Sources of Aggregates</th>
			<th>TOTAL</th>
		</tr>

		<tr>
			<th></th>
			<th></th>
			<th>ATOK</th>
			<th>BAKUN</th>
			<th>BOKOD</th>
			<th>BUGUIAS</th>
			<th>ITOGON</th>
			<th>KABAYAN</th>
			<th>KAPANGAN</th>
			<th>KIBUNGAN</th>
			<th>LA TRINIDAD</th>
			<th>MANKAYAN</th>
			<th>SABLAN</th>
			<th>TUBA</th>
			<th>TUBLAY</th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php if($curr_mnth != 'Jan'): ?>
		<tr>
			<?php if($curr_mnth == 'Feb'): ?>
				<td style="text-align: center;">January</td>
			<?php else: ?>
				<td style="text-align: center;">Jan - <?php echo e($prev_mnth); ?></td>
			<?php endif; ?>
			<td style="text-align: right;"><?php echo e(number_format(array_sum($base['past_month']['prov_share_prmun']),2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Atok'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Bakun'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Bokod'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Buguias'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Itogon'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Kabayan'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Kapangan'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Kibungan'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['La Trinidad'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Mankayan'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Sablan'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Tuba'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format($base['past_month']['mun_rpt']['Tublay'],2)); ?></td>
			<td style="text-align: right;"><?php echo e(number_format(array_sum($base['past_month']['mun_rpt']),2)); ?></td>
		</tr>
		<?php endif; ?>

		<?php
			$pmncal['Atok'] = 0;
			$pmncal['Bakun'] = 0;
			$pmncal['Bokod'] = 0;
			$pmncal['Buguias'] = 0;
			$pmncal['Itogon'] = 0;
			$pmncal['Kabayan'] = 0;
			$pmncal['Kapangan'] = 0;
			$pmncal['Kibungan'] = 0;
			$pmncal['La Trinidad'] = 0;
			$pmncal['Mankayan'] = 0;
			$pmncal['Sablan'] = 0;
			$pmncal['Tuba'] = 0;
			$pmncal['Tublay'] = 0;

			$provshare_total = 0;
			$count_rows = 0;
		?>

		<?php foreach($base['mun_rpt'] as $key => $details): ?>
			<?php  
				$pmncal['Atok'] += $details['Atok']['total_v'];
				$pmncal['Bakun'] += $details['Bakun']['total_v'];
				$pmncal['Bokod'] += $details['Bokod']['total_v'];
				$pmncal['Buguias'] += $details['Buguias']['total_v'];
				$pmncal['Itogon'] += $details['Itogon']['total_v'];
				$pmncal['Kabayan'] += $details['Kabayan']['total_v'];
				$pmncal['Kapangan'] += $details['Kapangan']['total_v'];
				$pmncal['Kibungan'] += $details['Kibungan']['total_v'];
				$pmncal['La Trinidad'] += $details['La Trinidad']['total_v'];
				$pmncal['Mankayan'] += $details['Mankayan']['total_v'];
				$pmncal['Sablan'] += $details['Sablan']['total_v'];
				$pmncal['Tuba'] += $details['Tuba']['total_v'];
				$pmncal['Tublay'] += $details['Tublay']['total_v'];
			?>
			<tr>
				<?php if($count_rows == 0): ?>
					<td style="text-align: center;"><?php echo e($curr_mnth.' '.$key); ?></td>
				<?php else: ?>
					<td style="text-align: center;"><?php echo e($key); ?></td>
				<?php endif; ?>
				<td style="text-align: right;"><?php echo e(number_format($details['prov_share'], 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Atok']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Bakun']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Bokod']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Buguias']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Itogon']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Kabayan']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Kapangan']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Kibungan']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['La Trinidad']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Mankayan']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Sablan']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Tuba']['total_v']), 2)); ?></td>
				<td style="text-align: right;"><?php echo e(number_format(($details['Tublay']['total_v']), 2)); ?></td>

				<td style="text-align: right;"><?php echo e(number_format(($details['Atok']['total_v'] 
														+ 	$details['Bakun']['total_v']
														+ 	$details['Bokod']['total_v']
														+ 	$details['Buguias']['total_v']
														+ 	$details['Itogon']['total_v']
														+ 	$details['Kabayan']['total_v']
														+ 	$details['Kapangan']['total_v']
														+ 	$details['Kibungan']['total_v']
														+ 	$details['La Trinidad']['total_v']
														+ 	$details['Mankayan']['total_v']
														+ 	$details['Sablan']['total_v']
														+ 	$details['Tuba']['total_v']
														+ 	$details['Tublay']['total_v']), 2)); ?>

				</td>
			</tr>
			<?php $count_rows++; ?>
		<?php endforeach; ?>
	</tbody>

	<?php 
		$pmncalx['Atok'] = $base['past_month']['mun_rpt']['Atok'] + $pmncal['Atok'];
		$pmncalx['Bakun'] = $base['past_month']['mun_rpt']['Bakun'] + $pmncal['Bakun'];
		$pmncalx['Bokod'] = $base['past_month']['mun_rpt']['Bokod'] + $pmncal['Bokod'];
		$pmncalx['Buguias'] = $base['past_month']['mun_rpt']['Buguias'] + $pmncal['Buguias'];
		$pmncalx['Itogon'] = $base['past_month']['mun_rpt']['Itogon'] + $pmncal['Itogon'];
		$pmncalx['Kabayan'] = $base['past_month']['mun_rpt']['Kabayan'] + $pmncal['Kabayan'];
		$pmncalx['Kapangan'] = $base['past_month']['mun_rpt']['Kapangan'] + $pmncal['Kapangan'];
		$pmncalx['Kibungan'] = $base['past_month']['mun_rpt']['Kibungan'] + $pmncal['Kibungan'];
		$pmncalx['La Trinidad'] = $base['past_month']['mun_rpt']['La Trinidad'] + $pmncal['La Trinidad'];
		$pmncalx['Mankayan'] = $base['past_month']['mun_rpt']['Mankayan'] + $pmncal['Mankayan'];
		$pmncalx['Sablan'] = $base['past_month']['mun_rpt']['Sablan'] + $pmncal['Sablan'];
		$pmncalx['Tuba'] = $base['past_month']['mun_rpt']['Tuba'] + $pmncal['Tuba'];
		$pmncalx['Tublay'] = $base['past_month']['mun_rpt']['Tublay'] + $pmncal['Tublay'];
	?>

	<tfoot>
		<tr>
			<th colspan="1">Total <?php echo e($curr_mnth_full); ?> 1-<?php echo e(\Carbon\Carbon::parse($curr_mnth_full)->endOfmonth()->format('d')); ?></th>
			<th class="text-right"><?php echo e(number_format($base['provincial_share'], 2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Atok'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Bakun'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Bokod'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Buguias'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Itogon'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Kabayan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Kapangan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Kibungan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['La Trinidad'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Mankayan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Sablan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Tuba'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncal['Tublay'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format(array_sum($pmncal),2)); ?></th>
		</tr>
		<?php if($curr_mnth != 'Jan'): ?>
		<tr>
			<th class="text-right">Total <?php echo e($prev_mnth_full); ?> 1-<?php echo e($curr_mnth_full.' '.\Carbon\Carbon::parse($curr_mnth_full)->endOfMonth()->format('d')); ?>, <?php echo e($base['year']); ?></th>
			<th class="text-right"><?php echo e(number_format((array_sum($base['past_month']['prov_share_prmun']) + $base['provincial_share']),2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Atok'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Bakun'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Bokod'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Buguias'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Itogon'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Kabayan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Kapangan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Kibungan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['La Trinidad'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Mankayan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Sablan'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Tuba'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format($pmncalx['Tublay'],2)); ?></th>
			<th class="text-right"><?php echo e(number_format(array_sum($pmncal) + array_sum($base['past_month']['mun_rpt']),2)); ?></th>
		</tr>
		<?php endif; ?>
	</tfoot>
</table>
<table>
	<tr><td>Prepared by:</td></tr>
	<tr><td></td></tr>
	<tr><td><?php echo e($base['acctble_officer_name']->value); ?></td></tr>
	<tr><td></td></tr>>
	<tr><td><?php echo e($base['acctble_officer_position']->value); ?></td></tr>
</table>