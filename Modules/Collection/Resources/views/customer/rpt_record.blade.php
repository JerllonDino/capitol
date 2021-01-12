<!DOCTYPE html>
<html>
<head>
	<title>RPT Record</title>
	{{-- Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') --}}
	<style type="text/css">
		body {
			font-size: 11px;
		}
		table, tr, td, th {
			border: 1px solid #0000000;
			border-collapse: collapse;
			border-color: black;
		}
		table {
			width: inherit;
			text-align: center;
			font-size: 11px;
			padding: 0;
			margin: 0;
			border-color: black;
		}
		.underline {
			border-bottom: 1px solid #000000;
			width: 350px;
			padding: 0;
			text-align: center;
			display: inline-block;
			vertical-align: bottom;
		}
		.properties {
			border: hidden;
		}
		.properties tr td {
			text-align: left;
			border-left: hidden;
			border-right: hidden;
		}
		.properties tr td:nth-child(1) {
			border-bottom: hidden;
			border-top: hidden;
			width: 20%;
		}
		.properties tr td:nth-child(2n) {
			border-bottom: 1px solid #000000;
			width: 80%;
			text-indent: 10px;
		}
		table {
			page-break-inside: avoid;
		}

		main {
            /*border-left: 2px solid #000000;
            border-right: 2px solid #000000;*/
            /*border-bottom: 2px solid #000000;*/
            padding-left: 0;
            padding-right: 0;
            margin-left: 0;
            margin-right: 0;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 160px;

            text-align: center;
            margin-bottom: 160px;
            margin-right: 10px;
            margin-left: 10px;
            font-size: 11px;
        }

        .pagenum:before {
            position: fixed;
            bottom: 0cm;
            line-height: .7cm;
            content: counter(page);
        }
	</style>
</head>
<body>
	<footer>
	    <div class="pagenum"></div>
	</footer>

	<main>
		<div style="width: 100%; padding: 5px;"> <!-- 900px width -->
			@if(isset($msg_no_record))
				<p style="text-align: center; color: red;">{{ $msg_no_record }}</p>
			@endif
			@if(count($property_rec) > 0)
				@for($j = 0; $j < count($property_rec); $j++)
					
					@if($j > 0)
						@if($property_rec[$j]['owner_id'] != $property_rec[$j-1]['owner_id'])
							<h3 style="text-align: center;">REAL PROPERTY TAX RECORD</h3>
							<table class="properties">
								<tr>
									<td><b>Owner: </b></td>
									<td>{!! strtoupper($property_rec[$j]['owner_name']) !!}</td>
								</tr>
								<tr>
									<td><b>Address: </b></td>
									<td>{{ ucwords(strtolower($property_rec[$j]['owner_address'])) }}</td>
								</tr>
								<tr>
									<td><b>Arp No.: </b></td>
									<td>{{ $property_rec[$j]['arp_no'] }}</td>
								</tr>
								<tr>
									<td><b>Title: </b></td>
									<td>{{ $property_rec[$j]['title'] }}</td>
								</tr>
								<tr>
									<td><b>Location: </b></td>
									<td>{{ ucwords(strtolower($property_rec[$j]['location'])) }}</td>
								</tr>
								<tr>
									<td><b>Classification: </b></td>
									<td>{{ ucwords(strtolower($property_rec[$j]['class'])) }}</td>
								</tr>
								<tr>
									<td><b>Actual Use: </b></td>
									<td>{{ ucwords(strtolower($property_rec[$j]['actual_use'])) }}</td>
								</tr>
								<tr>
									<td><b>Assessed Value: </b></td>
									<td>{{ number_format($property_rec[$j]['assessed_val'], 2) }}</td>
								</tr>
								<tr>
									<td><b>Tax Due: </b></td>
									<td>{{ number_format($property_rec[$j]['tax_due'], 2) }}</td>
								</tr>
								<tr>
							</table>
						@else
							<br>
							<table class="properties">
								<tr>
									<td><b>Classification: </b></td>
									<td>{{ ucwords(strtolower($property_rec[$j]['class'])) }}</td>
								</tr>
								<tr>
									<td><b>Actual Use: </b></td>
									<td>{{ ucwords(strtolower($property_rec[$j]['actual_use'])) }}</td>
								</tr>
								<tr>
									<td><b>Assessed Value: </b></td>
									<td>{{ number_format($property_rec[$j]['assessed_val'], 2) }}</td>
								</tr>
								<tr>
									<td><b>Tax Due: </b></td>
									<td>{{ number_format($property_rec[$j]['tax_due'], 2) }}</td>
								</tr>
							</table>
							<?php 
								if($j < count($property_rec)-1)
									continue; 
							?>
						@endif
					@else
						<h3 style="text-align: center;">REAL PROPERTY TAX RECORD</h3>
						<table class="properties">
							<tr>
								<td><b>Owner: </b></td>
								<td>{!! strtoupper($property_rec[$j]['owner_name']) !!}</td>
							</tr>
							<tr>
								<td><b>Address: </b></td>
								<td>{{ ucwords(strtolower($property_rec[$j]['owner_address'])) }}</td>	
							</tr>
							<tr>
								<td><b>Arp No.: </b></td>
								<td>{{ $property_rec[$j]['arp_no'] }}</td>
							</tr>
							<tr>
								<td><b>Title: </b></td>
								<td>{{ $property_rec[$j]['title'] }}</td>
							</tr>
							<tr>
								<td><b>Location: </b></td>
								<td>{{ ucwords(strtolower($property_rec[$j]['location'])) }}</td>
							</tr>
							<tr>
								<td><b>Classification: </b></td>
								<td>{{ ucwords(strtolower($property_rec[$j]['class'])) }}</td>
							</tr>
							<tr>
								<td><b>Actual Use: </b></td>
								<td>{{ ucwords(strtolower($property_rec[$j]['actual_use'])) }}</td>
							</tr>
							<tr>
								<td><b>Assessed Value: </b></td>
								<td>{{ number_format($property_rec[$j]['assessed_val'], 2) }}</td>
							</tr>
							<tr>
								<td><b>Tax Due: </b></td>
								<td>{{ number_format($property_rec[$j]['tax_due'], 2) }}</td>
							</tr>
						</table>
						@if($j == 0 && count($property_rec) > 2)
							@if($property_rec[$j]['owner_id'] == $property_rec[$j+1]['owner_id'])
								<?php continue; // display record of payments after the last record of property ?>
							@endif
						@endif
					@endif

					@if($j == count($property_rec)-1) 
						@if(!is_null($property_rec[$j]['cancelled_by']) && count($property_rec[$j]['cancelled_by']) > 0)
							<br>
							<table class="properties">
								<tr>
									<td><b>Cancelled By: </b></td>								
									<td style="color: red;">
									@for($i = 0; $i < count($property_rec[$j]['cancelled_by']); $i++)
										<?php
											$enc_id = Crypt::encrypt($property_rec[$j]['id'][$i]);
											$enc_td = Crypt::encrypt($property_rec[$j]['cancelled_by'][$i]);
										?>
										<a href="{{ URL::route('rpt_record_get', [$enc_id, $enc_td, true]) }}" target="_blank">{{ trim($property_rec[$j]['cancelled_by'][$i]) }}</a>
										{!! isset($property_rec[$j]['owner_new'][$i]) ? ' in the name of ' . ucwords(strtolower(preg_replace('/<[^>]*>*/', "", $property_rec[$j]['owner_new'][$i]))) : '' !!}  
										{{ isset($property_rec[$j]['effectivity_new'][$i]) ? ' effective '. $property_rec[$j]['effectivity_new'][$i] : '' }}
										<br>
									@endfor
									</td>
								</tr>
							</table>
						@endif
					@endif

					<?php $count = 0; ?>
					@if($j == 0 && count($property_rec) > 2) {{-- START records --}}
						@if($property_rec[$j]['owner_id'] != $property_rec[$j+1]['owner_id'])
							<table id="payments">
								<thead>
									<tr>
										<th colspan="2" rowspan="1">Official Receipt</th>
										<th rowspan="3">Collected By</th>
										<th rowspan="3">Year Covered</th>
										<th colspan="2">Rpt Basic</th>
										<th colspan="2">1% Additional (SEF)</th>
										<th rowspan="3">Total Taxes Paid</th>
									</tr>
									<tr>
										<th rowspan="2">Date</th>
										<th rowspan="2">Number</th>
										<th rowspan="2">Tax</th>
										<th rowspan="1">Penalty</th>
										<th rowspan="2">Tax</th>
										<th rowspan="1">Penalty</th>
									</tr>
									<tr>
										<th rowspan="1">(Discount)</th>
										<th rowspan="1">(Discount)</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="9%" style="padding: 7px;">-</td>
										<td width="9%" style="padding: 7px;">-</td>
										<td width="10%" style="padding: 7px;">-</td>
										<td width="9%" style="padding: 7px;">-</td>
										<td width="9%" style="padding: 7px;">-</td>
										<td width="9%" style="padding: 7px;">-</td>
										<td width="9%" style="padding: 7px;">-</td>
										<td width="9%" style="padding: 7px;">-</td>
										<td width="10%" style="padding: 7px;">-</td>
									</tr>
								</tbody>
							</table>
						@endif
					@elseif($j == count($property_rec)-1)
						<table id="payments">
							<thead>
								<tr>
									<th colspan="2" rowspan="1">Official Receipt</th>
									<th rowspan="3">Collected By</th>
									<th rowspan="3">Year Covered</th>
									<th colspan="2">Rpt Basic</th>
									<th colspan="2">1% Additional (SEF)</th>
									<th rowspan="3">Total Taxes Paid</th>
								</tr>
								<tr>
									<th rowspan="2">Date</th>
									<th rowspan="2">Number</th>
									<th rowspan="2">Tax</th>
									<th rowspan="1">Penalty</th>
									<th rowspan="2">Tax</th>
									<th rowspan="1">Penalty</th>
								</tr>
								<tr>
									<th rowspan="1">(Discount)</th>
									<th rowspan="1">(Discount)</th>
								</tr>
							</thead>
							<tbody>
								@if(count($payment_rec) > 0)
									@foreach($payment_rec as $tax_dec => $this_data)
										@foreach($this_data[$property_rec[$j]['arp_no']] as $serial => $det)
											<?php 
												$serial_cnt[$serial] = 0; 
												$rcpt_total = 0;
											?>
											@for($i = 0; $i < count($det['penalty']); $i++)
												<tr>
													@if($serial_cnt[$serial] == 0)
													<td width="9%">{{ \Carbon\Carbon::parse($det['date'][$i])->format('dMY') }}</td>
													<td width="9%">{{ $serial }}</td>
													@else
													<td width="9%"></td>
													<td width="9%"></td>
													@endif
													<td>
														@if($det['tax_type'][$i] == 5)
															<!-- MTO -->
															MTO
														@elseif($det['tax_type'][$i] == 6)
															PTO
														@endif
													</td>
													<td width="10%">{{ $det['period_covered'][$i] }}</td>
													<td width="9%">{{ number_format($det['tax'][$i], 2) }}</td>
													<td width="9%">
														@if($det['discount'][$i] > 0)
														({{ number_format($det['discount'][$i], 2) }})
														@else
														{{ number_format($det['penalty'][$i], 2) }}
														@endif
													</td>
													<td width="9%">{{ number_format($det['tax'][$i], 2) }}</td>
													<td width="9%">
														@if($det['discount'][$i] > 0)
														({{ number_format($det['discount'][$i], 2) }})
														@else
														{{ number_format($det['penalty'][$i], 2) }}
														@endif
													</td>
													<td width="9%">
														@if($det['discount'][$i] > 0)
															<?php $rcpt_total += ($det['tax'][$i]*2) - ($det['discount'][$i]*2); ?>
															{{ number_format(($det['tax'][$i]*2) - ($det['discount'][$i]*2), 2) }}
														@else
															<?php $rcpt_total += ($det['tax'][$i]*2) + ($det['penalty'][$i]*2); ?>
															{{ number_format(($det['tax'][$i]*2) + ($det['penalty'][$i]*2), 2) }}
														@endif
													</td>
												</tr>
												<?php $count++; $serial_cnt[$serial]++; ?>
											@endfor
										@endforeach
									@endforeach
								@else
									@for($i=0; $i < 10; $i++)
										<tr>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="10%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="10%" style="padding: 7px;"></td>
										</tr>
										<?php $count++; ?>
									@endfor
								@endif
								<?php $add_row = 9 - $count; ?>
								@if($add_row > 0)
									@for($i=0; $i < $add_row; $i++)
										<tr>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="10%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="10%" style="padding: 7px;"></td>
										</tr>
									@endfor
								@endif
							</tbody>
						</table>
					@elseif($j > 0 && $j < count($property_rec)-1)
						@if($property_rec[$j]['owner_id'] == $property_rec[$j-1]['owner_id'])
							<table id="payments">
								<thead>
									<tr>
										<th colspan="2" rowspan="1">Official Receipt</th>
										<th rowspan="3">Collected By</th>
										<th rowspan="3">Year Covered</th>
										<th colspan="2">Rpt Basic</th>
										<th colspan="2">1% Additional (SEF)</th>
										<th rowspan="3">Total Taxes Paid</th>
									</tr>
									<tr>
										<th rowspan="2">Date</th>
										<th rowspan="2">Number</th>
										<th rowspan="2">Tax</th>
										<th rowspan="1">Penalty</th>
										<th rowspan="2">Tax</th>
										<th rowspan="1">Penalty</th>
									</tr>
									<tr>
										<th rowspan="1">(Discount)</th>
										<th rowspan="1">(Discount)</th>
									</tr>
								</thead>
								<tbody>
									@if(count($payment_rec) > 0)
										@foreach($payment_rec as $tax_dec => $this_data)
											@foreach($this_data[$property_rec[$j]['arp_no']] as $serial => $det)
												<?php 
													$serial_cnt[$serial] = 0; 
													$rcpt_total = 0;
												?>
												@for($i = 0; $i < count($det['penalty']); $i++)
													<tr>
														@if($serial_cnt[$serial] == 0)
														<td width="9%">{{ \Carbon\Carbon::parse($det['date'][$i])->format('dMY') }}</td>
														<td width="9%">{{ $serial }}</td>
														@else
														<td width="9%"></td>
														<td width="9%"></td>
														@endif
														<td>
															@if($det['tax_type'][$i] == 5)
																<!-- MTO -->
																MTO
															@elseif($det['tax_type'][$i] == 6)
																PTO
															@endif
														</td>
														<td width="10%">{{ $det['period_covered'][$i] }}</td>
														<td width="9%">{{ number_format($det['tax'][$i], 2) }}</td>
														<td width="9%">
															@if($det['discount'][$i] > 0)
															({{ number_format($det['discount'][$i], 2) }})
															@else
															{{ number_format($det['penalty'][$i], 2) }}
															@endif
														</td>
														<td width="9%">{{ number_format($det['tax'][$i], 2) }}</td>
														<td width="9%">
															@if($det['discount'][$i] > 0)
															({{ number_format($det['discount'][$i], 2) }})
															@else
															{{ number_format($det['penalty'][$i], 2) }}
															@endif
														</td>
														<td width="9%">
															@if($det['discount'][$i] > 0)
																<?php $rcpt_total += ($det['tax'][$i]*2) - ($det['discount'][$i]*2); ?>
																{{ number_format(($det['tax'][$i]*2) - ($det['discount'][$i]*2), 2) }}
															@else
																<?php $rcpt_total += ($det['tax'][$i]*2) + ($det['penalty'][$i]*2); ?>
																{{ number_format(($det['tax'][$i]*2) + ($det['penalty'][$i]*2), 2) }}
															@endif
														</td>
													</tr>
													<?php $count++; $serial_cnt[$serial]++; ?>
												@endfor
											@endforeach
										@endforeach
										<?php $add_row = 9 - $count; ?>
										@if($add_row > 0)
											@for($i=0; $i < $add_row; $i++)
												<tr>
													<td width="9%" style="padding: 7px;"></td>
													<td width="9%" style="padding: 7px;"></td>
													<td width="10%" style="padding: 7px;"></td>
													<td width="9%" style="padding: 7px;"></td>
													<td width="9%" style="padding: 7px;"></td>
													<td width="9%" style="padding: 7px;"></td>
													<td width="9%" style="padding: 7px;"></td>
													<td width="9%" style="padding: 7px;"></td>
													<td width="10%" style="padding: 7px;"></td>
												</tr>
											@endfor
										@endif
									@else
										@for($i=0; $i < 10; $i++)
											<tr>
												<td width="9%" style="padding: 7px;"></td>
												<td width="9%" style="padding: 7px;"></td>
												<td width="10%" style="padding: 7px;"></td>
												<td width="9%" style="padding: 7px;"></td>
												<td width="9%" style="padding: 7px;"></td>
												<td width="9%" style="padding: 7px;"></td>
												<td width="9%" style="padding: 7px;"></td>
												<td width="9%" style="padding: 7px;"></td>
												<td width="10%" style="padding: 7px;"></td>
											</tr>
											<?php $count++; ?>
										@endfor
									@endif
								</tbody>
							</table>
						@endif
					@endif {{-- END records --}}
				@endfor
			@endif
			<br>
			@if(count($prev_tax_dec_rec) > 0) {{-- START records for previous tax declaration/s --}}
			<?php $count = 0; ?>
			<h4 style="text-align: center;">Previous TDRP No/s</h4>
				@foreach($prev_tax_dec_rec as $prev_rec)			
					<table class="properties">
						<tr>
							<td><b>Owner: </b></td>
							<td>{!! strtoupper($prev_rec['owner_name']) !!}</td>
						</tr>
						<tr>
							<td><b>Address: </b></td>
							<td>{{ ucwords(strtolower($prev_rec['owner_address'])) }}</td>
						</tr>
						<tr>
							<td><b>Arp No.: </b></td>
							<td>
								<?php
									$enc_id = Crypt::encrypt($prev_rec['id']);
									$enc_td = Crypt::encrypt($prev_rec['arp_no']);
								?>
								<a href="{{ URL::route('rpt_record_get', [$enc_id, $enc_td, true]) }}" target="_blank">{{ $prev_rec['arp_no'] }} </a>
							</td>
						</tr>
						<tr>
							<td><b>Title: </b></td>
							<td>{{ $prev_rec['title'] }}</td>
						</tr>
						<tr>
							<td><b>Location: </b></td>
							<td>{{ ucwords(strtolower($prev_rec['location'])) }}</td>
						</tr>
						<tr>
							<td><b>Classification: </b></td>
							<td>{{ ucwords(strtolower($prev_rec['classification'])) }}</td>
						</tr>
						<tr>
							<td><b>Actual Use: </b></td>
							<td>{{ ucwords(strtolower($prev_rec['actual_use'])) }}</td>
						</tr>
						<tr>
							<td><b>Assessed Value: </b></td>
							<td>{{ number_format($prev_rec['assess_val'], 2) }}</td>
						</tr>
						<tr>
							<td><b>Tax Due: </b></td>
							<td>{{ number_format($prev_rec['tax_due'], 2) }}</td>
						</tr>
					</table>

					<table id="payments">
						<thead>
							<tr>
								<th colspan="2" rowspan="1">Official Receipt</th>
								<th rowspan="3">Collected By</th>
								<th rowspan="3">Year Covered</th>
								<th colspan="2">Rpt Basic</th>
								<th colspan="2">1% Additional (SEF)</th>
								<th rowspan="3">Total Taxes Paid</th>
							</tr>
							<tr>
								<th rowspan="2">Date</th>
								<th rowspan="2">Number</th>
								<th rowspan="2">Tax</th>
								<th rowspan="1">Penalty</th>
								<th rowspan="2">Tax</th>
								<th rowspan="1">Penalty</th>
							</tr>
							<tr>
								<th rowspan="1">(Discount)</th>
								<th rowspan="1">(Discount)</th>
							</tr>
						</thead>
						<tbody>					
							@if(count($prev_tax_dec_pay) > 0)
								@foreach($prev_tax_dec_pay as $tax_dec => $this_data)								
									@if(isset($prev_tax_dec_pay[$prev_rec['arp_no']]))
										@foreach($prev_tax_dec_pay[$prev_rec['arp_no']] as $serial => $det)
											<?php 
												$serial_cnt[$serial] = 0; 
												$rcpt_total = 0;
											?>
											{{-- @for($i = 0; $i < count($det['penalty']); $i++) --}}
												<tr>	
													@if($serial_cnt[$serial] == 0)
													<td width="9%">{{ \Carbon\Carbon::parse($det['date'])->format('dMY') }}</td>
													<td width="9%">{{ $serial }}</td>
													@else
													<td width="9%"></td>
													<td width="9%"></td>
													@endif
													<td>
														@if($det['tax_type'] == 5)
															<!-- MTO -->
															MTO
														@elseif($det['tax_type'] == 6)
															PTO
														@endif
													</td>
													<td width="10%">{{ $det['period_covered'] }}</td>
													<td width="9%">{{ number_format($det['tax'], 2) }}</td>
													<td width="9%">
														@if($det['discount']> 0)
														({{ number_format($det['discount'], 2) }})
														@else
														{{ number_format($det['penalty'], 2) }}
														@endif
													</td>
													<td width="9%">{{ number_format($det['tax'], 2) }}</td>
													<td width="9%">
														@if($det['discount'] > 0)
														({{ number_format($det['discount'], 2) }})
														@else
														{{ number_format($det['penalty'], 2) }}
														@endif
													</td>
													<td width="9%">
														@if($det['discount'] > 0)
															<?php $rcpt_total += ($det['tax']*2) - ($det['discount']*2); ?>
															{{ number_format(($det['tax']*2) - ($det['discount']*2), 2) }}
														@else
															<?php $rcpt_total += ($det['tax']*2) + ($det['penalty']*2); ?>
															{{ number_format(($det['tax']*2) + ($det['penalty']*2), 2) }}
														@endif
													</td>
												</tr>
												<?php $count++; $serial_cnt[$serial]++; ?>
											{{-- @endfor --}}
										@endforeach
									@endif
								@endforeach
								<?php $add_row = 9 - $count; ?>
								@if($add_row > 0)
									@for($i=0; $i < $add_row; $i++)
										<tr>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="10%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="9%" style="padding: 7px;"></td>
											<td width="10%" style="padding: 7px;"></td>
										</tr>
									@endfor
								@endif
							@else
								@for($i=0; $i < 10; $i++)
									<tr>
										<td width="9%" style="padding: 7px;"></td>
										<td width="9%" style="padding: 7px;"></td>
										<td width="10%" style="padding: 7px;"></td>
										<td width="9%" style="padding: 7px;"></td>
										<td width="9%" style="padding: 7px;"></td>
										<td width="9%" style="padding: 7px;"></td>
										<td width="9%" style="padding: 7px;"></td>
										<td width="9%" style="padding: 7px;"></td>
										<td width="10%" style="padding: 7px;"></td>
									</tr>
								@endfor
							@endif
						</tbody>
					</table>
					<br>
				@endforeach
			@endif {{-- END records for previous tax declaration/s --}}
		</div>
	</main>
</body>
</html>