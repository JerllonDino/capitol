        <?php  
                $summary_total = 0;
                $total_with_ada = 0;
                $has_ada = 0;
                $ada = 0;
        ?>

        @foreach ($trantypes as $i => $type)
                    @if ($i == 4)
                        @if ($type['total'] > 0)
                        <?php   $ada = $type['total'];
                                $has_ada = 1;
                        ?>
                        @endif
                        <?php  $total_with_ada += $type['total']; ?>
                    @else
                        <?php $total_with_ada += $type['total'];
                              $summary_total += $type['total']; 
                        ?>
                    @endif
        @endforeach
        <table class="table table-condensed">
			<tr>
				<td class="">Beginning Balance {{ $report_start }}</td>
				<td class=" val">

				</td>
			</tr>
			<tr>
				<td class="">Add: Collections {{ $date_range }}</td>
				<td class=" val">

				</td>
			</tr>
            @foreach ($trantypes as $i => $type)
                <tr>
                    <td class=" tdindent">{{ $type['name'] }}</td>
                    <td class=" val text-right">
                        {{ round($type['total'], 2) }}
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td class=""><b>Total</b></td>
                    <td class=" val text-right"><b>{{ round($total_with_ada, 2) }}</b></td>
                </tr>
			@if ($has_ada)
                <tr>
                    <td class=""><b>Less ADA</b></td>
                    <td class=" val text-right"><b>{{ round($ada, 2) }}</b></td>
                </tr>
            @endif
            <tr>
                <td class=""><b>Remittance/Deposit to Cashier/Treasurer</b></td>
                <td class=" val text-right"><b>{{ round($summary_total, 2) }}</b></td>
            </tr>
			<tr>
                <td class=""><b>Balance</b></td>
                <td class=" val text-right"><b></b></td>
            </tr>
        </table>
       <?php $bank_total = 0; ?>
<table class="table table-condensed table-bordered">
             <thead>
                        <tr>
                            <th class="">Drawee Bank</th>
                            <th class="">Check No.</th>
                            <th class="">Payee</th>
                            <th class="">Amount</th>
                        </tr>
            </thead>
                    <tbody>
                             @foreach($bank as $b)
                                <tr>
                                    <td class="">{{ $b['bank'] }}</td>
                                    <td class="">{{ $b['check_no'] }}</td>
                                    <td class="">Provincial Government of Benguet</td>
                                    <td class=" val">
                                        <?php $bank_total += $b['amt']; ?>
                                        {{ round($b['amt'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="" colspan="3">Total</td>
                                    <td class="  val">{{ round($bank_total, 2) }}</td>
                                </tr>
                    </tbody>
</table>
