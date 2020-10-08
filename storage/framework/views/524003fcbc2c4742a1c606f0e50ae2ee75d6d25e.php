<!DOCTYPE html>
<html>
<head>
    <title>SAND and GRAVEL</title>
    <style>
        @page  { margin: 0px 10px; }
        body {
            margin: 0px 10px; 
            font-family: arial, "sans-serif";
            font-size: 8.5;
        }

        
        .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

        .image_logo{
            width: 65px;
            padding-right: -50px;
        }
        .right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, td {
            padding: 2px;
        }

        .table,.table>thead>tr>th,.table>tbody>tr>td{
        	border:1px solid #ccc;
        }
        .text-center{
        	text-align: center;
        }

        .text-right{
        	text-align: right;
        }

        
        #sand_gravel_share{
         	border: 2px solid #000;
        }

        #sand_gravel_share > thead > tr > th,#sand_gravel_share > tbody > tr > td,#sand_gravel_share > tfoot > tr > th{
        	border-right: 2px solid #000;
        	border-left: 2px solid #000;
        }

        #sand_gravel_share > thead > tr > th{
        	border: 2px solid #000;
        }

        #sand_gravel_share > tfoot > tr > th{
        	border: 2px solid #000;
        	border-bottom: 3px solid #000;
        }

        #sand_gravel_share>thead>tr>th,#sand_gravel_share>tbody>tr>td{
		    font-size: 12px;
		    padding: 1px;
		}
       
    </style>
</head>
<body>
	<?php 
		$sumx = array_sum($past_month);
		$sumx += $provincial_sharex;
		$curr_mnth = $date_mnth->format('M');
		$curr_mnth_full = $date_mnth->format('F');
		$prev_mnth_full = $date_mnth->subMonth()->format('F');
		$prev_mnth = $date_mnth->subMonth()->format('M');
	?>
<table class="center" style="width: 580px;">
	<tr>
        <td>
            <img src="<?php echo e(asset('asset/images/benguet-logo.png')); ?>" class="image_logo" />
        </td>
        <td>
        Republic of the Philippines<br>
        PROVINCE OF BENGUET<br>
        La Trinidad<br>
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong><br><br><br>
        SOURCES OF SAND AND GRAVEL TAX & PENALTY COLLECTIONS<br>
        For the Period <?php echo e($curr_mnth_full); ?> 1-<?php echo e($curr_mnth_full); ?> <?php echo e(\Carbon\Carbon::parse($curr_mnth_full)->endOfMonth()->format('d')); ?>, <?php echo e($year); ?>

        </td>
    </tr>
</table>

<table id="sand_gravel_share" class="table table-condensed table-bordered">
	
	<thead>
		<tr>
			<th rowspan="2" class="text-center" style="width: 70px;">DATE</th>
			<th rowspan="2" class="text-center">PROVINCIAL SHARE</th>
			<th rowspan="1" colspan="13" style="text-align: center;">Sources of Aggregates</th>
			<th rowspan="2" class="text-center">TOTAL</th>
		</tr>

		<tr>
			<th class="text-center">ATOK</th>
			<th class="text-center">BAKUN</th>
			<th class="text-center">BOKOD</th>
			<th class="text-center">BUGUIAS</th>
			<th class="text-center">ITOGON</th>
			<th class="text-center">KABAYAN</th>
			<th class="text-center">KAPANGAN</th>
			<th class="text-center">KIBUNGAN</th>
			<th class="text-center">LA TRINIDAD</th>
			<th class="text-center">MANKAYAN</th>
			<th class="text-center">SABLAN</th>
			<th class="text-center">TUBA</th>
			<th class="text-center">TUBLAY</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<?php if($curr_mnth == 'Feb'): ?>
				<td class="text-center">January</td>
			<?php else: ?>
				<td class="text-center">As of <?php echo e($prev_mnth_full); ?> <?php echo e($year); ?></td>
			<?php endif; ?>
			<td class="text-right"><?php echo e(number_format(array_sum($past_month['prov_share_prmun']),2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Atok'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Bakun'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Bokod'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Buguias'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Itogon'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Kabayan'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Kapangan'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Kibungan'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['La Trinidad'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Mankayan'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Sablan'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Tuba'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format($past_month['mun_rpt']['Tublay'],2)); ?></td>
			<td class="text-right"><?php echo e(number_format(array_sum($past_month['mun_rpt']),2)); ?></td>
		</tr>


		<?php $count = 0; 
			

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
		?>
		?>
		<?php foreach($mun_rpt as $key => $details): ?>
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
		<?php if(array_sum($mun_rpt[$key]) != 0): ?>
			<tr>
				<?php if( $count == 0 ): ?>
					<td class="text-center"><?php echo e($datex->format('M').' '.$key); ?></td>
				<?php else: ?>
					<td class="text-center"><?php echo e($key); ?></td>
				<?php endif; ?>
				<td class="text-right"><?php echo e(number_format($details['prov_share'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Atok']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Bakun']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Bokod']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Buguias']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Itogon']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Kabayan']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Kapangan']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Kibungan']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['La Trinidad']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Mankayan']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Sablan']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Tuba']['total_v'], 2)); ?></td>
				<td class="text-right"><?php echo e(number_format($details['Tublay']['total_v'], 2)); ?></td>

				<td class="text-right"><?php echo e(number_format(($details['Atok']['total_v'] 
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
			<?php $count++; ?>
		<?php endif; ?>
		<?php endforeach; ?>
	</tbody>
	<?php 
			$pmncalx['Atok'] = $past_month['mun_rpt']['Atok'] + $pmncal['Atok'];
			$pmncalx['Bakun'] = $past_month['mun_rpt']['Bakun'] + $pmncal['Bakun'];
			$pmncalx['Bokod'] = $past_month['mun_rpt']['Bokod'] + $pmncal['Bokod'];
			$pmncalx['Buguias'] = $past_month['mun_rpt']['Buguias'] + $pmncal['Buguias'];
			$pmncalx['Itogon'] = $past_month['mun_rpt']['Itogon'] + $pmncal['Itogon'];
			$pmncalx['Kabayan'] = $past_month['mun_rpt']['Kabayan'] + $pmncal['Kabayan'];
			$pmncalx['Kapangan'] = $past_month['mun_rpt']['Kapangan'] + $pmncal['Kapangan'];
			$pmncalx['Kibungan'] = $past_month['mun_rpt']['Kibungan'] + $pmncal['Kibungan'];
			$pmncalx['La Trinidad'] = $past_month['mun_rpt']['La Trinidad'] + $pmncal['La Trinidad'];
			$pmncalx['Mankayan'] = $past_month['mun_rpt']['Mankayan'] + $pmncal['Mankayan'];
			$pmncalx['Sablan'] = $past_month['mun_rpt']['Sablan'] + $pmncal['Sablan'];
			$pmncalx['Tuba'] = $past_month['mun_rpt']['Tuba'] + $pmncal['Tuba'];
			$pmncalx['Tublay'] = $past_month['mun_rpt']['Tublay'] + $pmncal['Tublay'];
	?>

	<tfoot>
		<tr>
			<th colspan="1">Total <?php echo e($curr_mnth_full); ?> 1-<?php echo e(\Carbon\Carbon::parse($curr_mnth_full)->endOfMonth()->format('d')); ?></th>
			<th class="text-right"><?php echo e(number_format($provincial_share, 2)); ?></th>
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
		<!--<?php /* <tr>
			<th colspan="2">Provincial Share - <?php echo e(-- $datex->format('M')); ?> <?php echo e($datex->format('Y') */ ?></th>
			<th class="text-right" colspan="14"><?php /* number_format($provincial_share , 2) */ ?></th>
		</tr> --); ?>-->
		<tr>
			<!-- <td><b>Total <?php /* $prev_mnth_full */ ?> 1-<?php /* $curr_mnth_full.' '.\Carbon\Carbon::parse($curr_mnth_full)->endOfMonth()->format('d') */ ?>, <?php /* $year */ ?></b></td> -->
			<td><b>TO DATE</b></td>
			<th class="text-right"><?php echo e(number_format((array_sum($past_month['prov_share_prmun']) + $provincial_share),2)); ?></th>
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
			<th class="text-right"><?php echo e(number_format(array_sum($pmncal) + array_sum($past_month['mun_rpt']),2)); ?></th>
		</tr>

		
	</tfoot>
</table>
<div>
	<p>Prepared by:</p>
	<br><br>
	<b><?php echo e($acctble_officer_name->value); ?></b>
	<br>
	<b><?php echo e($acctble_officer_position->value); ?></b>
</div>
</body>
</html>