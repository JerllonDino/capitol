<table>
	<thead>
		<tr>
			<th >Date</th>
			<th >Municipalities</th>
			<th > RPT (NET) BASIC {{date("Y")}} </th>
			<th >PENALTIES</th>
			<th >RPT (NET) SEF  {{date("Y")}} </th>
			<th >PENALTIES</th>
			<th >PTR</th>
			<th >PENALTY PTR</th>
			<th >Permit Fees</th>
			<th >Permit Fees Penalties</th>
			<th>SAND & GRAVEL</th>
			<th>MINING TAX</th>
			<th>ACCOUNTABLE FORMS</th>
			<th>TOTALS</th>
		</tr>
	</thead>

<tbody>
	@if(count($base['mun_rpt']) > 0 && $base['mun_rpt'] != "")
		@foreach($base['mun_rpt'] as $key => $details)
			<tr>
				<td>{{$key}}</td>
					<?php  $c = 0; ?>
	                @foreach($details as $keyz => $detail)
	                        @if($c > 0)
	                            <tr>
	                                <td></td>
	                        @endif
	                         <?php $totals[$keyz] = 0; ?>
	                            <td>{{$keyz}}</td>

	                            @if( isset($detail['rpt_basic']) )
	                                <td>{{ $detail['rpt_basic'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['rpt_basic'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif

	                            @if( isset($detail['rpt_basic_penalty']) )
	                                <td>{{ $detail['rpt_basic_penalty'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['rpt_basic_penalty'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif

	                            @if( isset($detail['special_educfund']) )
	                                <td>{{ $detail['special_educfund'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['special_educfund'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif


	                            @if( isset($detail['sef_penalty']) )
	                                <td>{{ $detail['sef_penalty'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['sef_penalty'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif


	                            @if( isset($detail['prof_tax']) )
	                                <td>{{ $detail['prof_tax'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['prof_tax'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif


	                            @if( isset($detail['prof_tax_fines']) )
	                                <td>{{ $detail['prof_tax_fines'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['prof_tax_fines'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif


	                            @if( isset($detail['permit_fees']) )
	                                <td>{{ $detail['permit_fees'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['permit_fees'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif

	                            @if( isset($detail['permit_fees_fines']) )
	                                <td>{{ $detail['permit_fees_fines'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['permit_fees_fines'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif

	                            @if( isset($detail['tax_sand_gravel']) )
	                                <td>{{ $detail['tax_sand_gravel'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['tax_sand_gravel'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif

	                            @if( isset($detail['mining_tax']) )
	                                <td>{{ $detail['mining_tax'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['mining_tax'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif

	                            @if( isset($detail['acct_forms']) )
	                                <td>{{ $detail['acct_forms'][0]->value }}</td>
	                                <?php $totals[$keyz] += $detail['acct_forms'][0]->value; ?>
	                            @else
	                                <td>0</td>
	                            @endif

	                            <td>{{  $totals[$keyz] }}</td>


	                            <?php  $c++; ?>

	                @endforeach
			</tr>
	    @endforeach
	@endif
</tbody>

</table>