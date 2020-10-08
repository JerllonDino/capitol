<!DOCTYPE html>
<html>
<head>
	<title>Notice of Real Property Delinquency</title>
	<style type="text/css">
		body * {
			padding: 0; margin: 0;
		}
		.text-center {
			text-align: center;
		}
		#notice_head {
			text-align: center;
		}
		table {
			width: 100%;
		}
		.records {
			text-align: center;
			padding: 5px;
		}
		.records > tbody > tr > td {
			font-size: 11px;
		}
	</style>
</head>
<body>
	@if(count($delinquents) > 0)
		@foreach($delinquents as $key => $delq)
			<div class="text-center">
				<p>Republic of the Philippines</p>
				<p>PROVINCE OF BENGUET</p>
				<p>La Trinidad</p>
				<p>OFFICE OF THE PROVINCIAL TREASURER</p>
			</div>
			<p id="notice_head"><b>N O T I C E&nbsp;&nbsp;&nbsp;O F&nbsp;&nbsp;&nbsp;R E A L&nbsp;&nbsp;&nbsp;P R O P E R T Y&nbsp;&nbsp;&nbsp;T A X&nbsp;&nbsp;&nbsp;D E L I N Q U E N C Y</b></p><br>
			<div style="display: inline-block;">{{ $delq->name }}</div>
			<div style="display: inline-block; float: right;">Date: {{ \Carbon\Carbon::now()->format('F d, Y') }}</div><br>
			<p>{{ $delq->address }}</p><br>
			<div>
				<p><b>Dear Sir/Madam:</b></p> <br>
				<p style="text-indent: 20px;">In compliance to the requirement of Section 254 of R.A. 7160 (Local Goverment Code of 1991), you are hereby informed of the tax delinquency on your property/ies described in the list of delinquent taxpayers certified and submitted by the Municipal Treasurer of <b><u>{{ $delq->address != '' || !is_null($delq->address) ? $delq->address : "______________________" }}</u></b> described as follows:</p> <br>
				<table class="records">
					<thead>
						<tr>
							<th width="4cm">ARP No./s</th>
							<th>LOCATION</th>
							<th>AREA(sq.m.)</th>
							<th>PERIOD COVERED</th>
							<th>TAX DUE</th>
							<th>PENALTIES</th>
							<th>TOTAL</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($tdarp_details))
							@if(isset($tdarp_details[$delq->col_customer_id]))
									<tr>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
									</tr>
								<?php $gtotal = 0; ?>
								@foreach($tdarp_details[$delq->col_customer_id] as $key => $arp)
									<?php
										$yeardif = \Carbon\Carbon::now()->diffInYears(\Carbon\Carbon::parse($delq->last_pd)); 
										$tax_due = $arp->assessed_value/100;
										$total_tax_due = $tax_due * $yeardif;
										$penalty = (\Carbon\Carbon::now()->diffInMonths(\Carbon\Carbon::parse($delq->last_pd))) * 0.02 * $tax_due;
										//$total = $total_tax_due + $penalty;
										$total = $tax_due + $penalty;
										$gtotal += $total;

										$measure = preg_replace('/[^A-Za-z0-9]/', '', $arp->measurement);
										$area = strcasecmp($measure, 'sqm') == 0 || strcasecmp($measure, 'sq') == 0 ? $arp->land_area : ($arp->land_area*10000);
									?>
									<tr>
										<td>{{ $arp->tax_dec_no }}</td>
										<td>{{ $municipalities[$arp->municipality]->name }}</td>
										<td>{{ $arp->land_area }}</td>
										<td>{{ \Carbon\Carbon::parse($delq->last_pd)->format('Y')."-".\Carbon\Carbon::now()->format('Y') }}</td>
										<td>{{ number_format($tax_due, 2) }}</td>
										<td>{{ number_format($penalty, 2) }}</td>
										<td>{{ number_format($total, 2) }}</td>
									</tr>
								@endforeach
								@if(count($tdarp_details[$delq->col_customer_id]) < 4)
									@for($i = 0; $i <= (4-count($tdarp_details[$delq->col_customer_id])); $i++)
										<tr>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
										</tr>
									@endfor
								@endif
							@else
								@for($i = 0; $i <= 4; $i++)
									<tr>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
									</tr>
								@endfor
							@endif
						@else
							@for($i = 0; $i <= 4; $i++)
								<tr>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
								</tr>
							@endfor
						@endif
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5" style="text-align: left;"><b>Total Amount Due</b></td>
							<td>Php</td>
							<td>{{ number_format($gtotal, 2) }}</td>
						</tr>
					</tfoot>
				</table>
				<br>
				<p style="text-indent: 20px;">In case any of the above stated taxes has already been paid, please furnish us with the number of the official receipt and the date of payment or photocopy of your receipt, otherwise we shall appreciate very much your early payment of the aforestated total amount of Php <b><u>{{ !empty($delinquents) ? number_format($gtotal, 2) : "_________________" }}</u></b>. If after fifteen (15) days from your receipt hereof you failed to pay the said amount, the remedies provided for under the law for the collection of delinquent taxes, shall be applied to enforce collection.</p>
				<div style="text-align: right;">
				<p>Very truly yours,</p> <br><br>
				<b>{{ $prov_trea->officer_name }}</b><br>
				Provincial Treasurer <br><br>
				<p>By:</p> <br><br>
				<b>{{ $prov_asst_trea->officer_name }}</b><br>
				Asst. Provincial Treasurer <br>
				</div>
			</div>
			<br>
			<hr>
			<sup style="float: right;"><small>Municipal Treasury Office file copy</small></sup><br>
			<p class="text-center"><b>NOTICE OF REAL PROPERTY TAX DELINQUENCY</b></p>
			<table>
				<tr>
					<td>DECLARED OWNER</td>
					<td><b>{{ $delq->name }}</b></td>
				</tr>
				<tr>
					<td>ADDRESS</td>
					<td><b>{{ $delq->address }}</b></td>
				</tr>
			</table>
			<table class="records">
				<thead>
					<tr>
						<th width="4cm">ARP No./s</th>
						<th>LOCATION</th>
						<th>AREA(sq.m.)</th>
						<th>PERIOD COVERED</th>
						<th>TAX DUE</th>
						<th>PENALTIES</th>
						<th>TOTAL</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($tdarp_details))
						@if(isset($tdarp_details[$delq->col_customer_id]))
								<tr>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
								</tr>
							<?php $gtotal = 0; ?>
							@foreach($tdarp_details[$delq->col_customer_id] as $key => $arp)
								<tr>
									<td>{{ $arp->tax_dec_no }}</td>
									<td>{{ $municipalities[$arp->municipality]->name }}</td>
									<td>{{ $arp->land_area }}</td>
									<td>{{ \Carbon\Carbon::parse($delq->last_pd)->format('Y')."-".\Carbon\Carbon::now()->format('Y') }}</td>
									<td>{{ number_format($tax_due, 2) }}</td>
									<td>{{ number_format($penalty, 2) }}</td>
									<td>{{ number_format($total, 2) }}</td>
								</tr>
							@endforeach
							@if(count($tdarp_details[$delq->col_customer_id]) < 4)
								@for($i = 0; $i <= (4-count($tdarp_details[$delq->col_customer_id])); $i++)
									<tr>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
									</tr>
								@endfor
							@endif
						@else
							@for($i = 0; $i <= 4; $i++)
								<tr>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
								</tr>
							@endfor
						@endif
					@else
						@for($i = 0; $i <= 4; $i++)
							<tr>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							</tr>
						@endfor
					@endif
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5">Total Amount Due</td>
						<td>Php</td>
						<td>{{ number_format($gtotal, 2) }}</td>
					</tr>
				</tfoot>
			</table>
			<br>
			<table class="sign2">
				<tr>
					<td>RECEIVED BY:</td>
					<td><div style="text-align: center; display: block;">____________________________________<br><small>Print Name & Signature</small></div></td>
					<td style="text-align: left;">DATE RECEIVED:</td>
				</tr>
				<tr>
					<td>RELATIONSHIP:</td>
					<td>____________________________________</td>
				</tr>
			</table>
			<div style="page-break-after: always;"></div>
		@endforeach
	@else
		<div class="text-center">
			<p>Republic of the Philippines</p>
			<p>PROVINCE OF BENGUET</p>
			<p>La Trinidad</p>
			<p>OFFICE OF THE PROVINCIAL TREASURER</p>
		</div>
		<p id="notice_head"><b>N O T I C E  O F  R E A L  P R O P E R T Y  T A X  D E L I N Q U E N C Y</b></p><br>
		<div style="display: inline-block;"></div>
		<div style="display: inline-block; float: right;">Date: {{ \Carbon\Carbon::now()->format('F d, Y') }}</div><br>
		<p></p><br>
		<div>
			<p><b>Dear Sir/Madam:</b></p> <br>
			<p style="text-indent: 20px;">In compliance to the requirement of Section 254 of R.A. 7160 (Local Goverment Code of 1991), you are hereby informed of the tax delinquency on your property/ies described in the list of delinquent taxpayers certified and submitted by the Municipal Treasurer of "Municiplaity" described as follows:</p> <br>
			<table class="records">
				<thead>
					<tr>
						<th>ARP No./s</th>
						<th>LOCATION</th>
						<th>AREA(sq.m.)</th>
						<th>PERIOD COVERED</th>
						<th>TAX DUE</th>
						<th>PENALTIES</th>
						<th>TOTAL</th>
					</tr>
				</thead>
				<tbody>
					@for($i = 0; $i <= 4; $i++)
						<tr>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
						</tr>
					@endfor
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" style="text-align: left;"><b>Total Amount Due</b></td>
						<td>Php</td>
						<td>_________________</td>
					</tr>
				</tfoot>
			</table>
			<br>
			<p style="text-indent: 20px;">In case any of the above stated taxes has already been paid, please furnish us with the number of the official receipt and the date of payment or photocopy of your receipt, otherwise we shall appreciate very much your early payment of the aforestated total amount of Php _________________. If after fifteen (15) days from your receipt hereof you failed to pay the said amount, the remedies provided for under the law for the collection of delinquent taxes, shall be applied to enforce collection.</p>
			<div style="text-align: right;">
				<p>Very truly yours,</p> <br><br>
				<b>{{ $prov_trea->officer_name }}</b><br>
				Provincial Treasurer <br><br>
				<p>By:</p> <br><br>
				<b>{{ $prov_asst_trea->officer_name }}</b><br>
				Asst. Provincial Treasurer <br>
			</div>
		</div>
		<br>
		<hr>
		<sup style="float: right;"><small>Municipal Treasury Office file copy</small></sup><br>
		<p class="text-center"><b>NOTICE OF REAL PROPERTY TAX DELINQUENCY</b></p>
		<table>
			<tr>
				<td>DECLARED OWNER</td>
				<td></td>
			</tr>
			<tr>
				<td>ADDRESS</td>
				<td></td>
			</tr>
		</table>
		<table class="records">
			<thead>
				<tr>
					<th>ARP No./s</th>
					<th>LOCATION</th>
					<th>AREA(sq.m.)</th>
					<th>PERIOD COVERED</th>
					<th>TAX DUE</th>
					<th>PENALTIES</th>
					<th>TOTAL</th>
				</tr>
			</thead>
			<tbody>
				@for($i = 0; $i <= 4; $i++)
					<tr>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
					</tr>
				@endfor
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5">Total Amount Due</td>
					<td>Php</td>
					<td>_________________</td>
				</tr>
			</tfoot>
		</table>
		<br>
		<table class="sign2">
			<tr>
				<td>RECEIVED BY:</td>
				<td>____________________________________</td>
				<td style="text-align: left;">DATE RECEIVED:</td>
			</tr>
			<tr>
				<td>RELATIONSHIP:</td>
				<td>____________________________________</td>
			</tr>
		</table>
	@endif
</body>
</html>