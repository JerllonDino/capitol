    <table id="collections" class="table table-bordered table-condensed table-responsive page-break">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="2">OR Nos.</th>
            <th class=" detail_payor" rowspan="2">Payor</th>
            @foreach($accounts as $i => $account)
                <th class="" colspan="{{ count($account['titles']) + count($account['subtitles']) }}">{{ $i }}</th>
            @endforeach

            @if (count($shares) > 0)
            <th class="" colspan="{{ $share_columns }}">MUNICIPAL & BRGY SHARES</th>
            @endif

            <th class="" rowspan="2">TOTAL AMOUNT</th>
        </tr>
        <tr class="page-break">
            @foreach($accounts as $i => $account)
                @foreach($account['titles'] as $j => $title)
                <?php 
                $acronym = $j ;
                ?>
                    <th>{{ $acronym }}</th>
                @endforeach
                @foreach($account['subtitles'] as $j => $subtitle)
                    <?php 
                    $acronym = $j ;
                    ?>
                    <th>{{ $acronym }}</th>
                @endforeach
            @endforeach
            @foreach($shares as $i => $share)
                <th>{{ $share['name'] }}</th>
                @foreach($share['barangays'] as $j => $barangay)
                <th>{{ $barangay['name'] }}</th>
                @endforeach
            @endforeach
        </tr>
        <tr class="border-botts page-break">
            <th class="border-botts" colspan="{{ $total_columns + 1 }}">{{ $date_range }}</th>
        </tr>
</thead>
        
<tbody>
<?php $gtotal = 0; ?>
        @foreach ($receipts as $i => $receipt)
        <tr class="page-break">
            <td class=" val">{{ $receipt->serial_no }}</td>
            @if (!isset($receipts_total[$receipt->serial_no]))
                <td class=" cancelled_remark" colspan="{{ $total_columns }}">
                    Cancelled - {{ $receipt->cancelled_remark }}
                </td>
            @else
                <td class=" detail_payor val">{{ $receipt->customer->name }}</td>
                @foreach($accounts as $i => $account)
                    @foreach($account['titles'] as $ji => $title)
                        <td class=" val text-right">
                            @if (isset($title[$receipt->serial_no]))
                            {{ round($title[$receipt->serial_no], 2) }}
                            @endif
                        </td>
                    @endforeach

                    @foreach($account['subtitles'] as $j => $subtitle)
                        <td class=" val text-right">
                            @if (isset($subtitle[$receipt->serial_no]))
                            {{ round($subtitle[$receipt->serial_no], 2) }}
                            @endif
                        </td>
                    @endforeach
                @endforeach

                @foreach($shares as $i => $share)
                    <td class=" val text-right">
                        @if (isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0)
                        {{ round($share[$receipt->serial_no], 2) }}
                        @endif
                    </td>
                    @foreach($share['barangays'] as $j => $barangay)
                    <td class=" val text-right">
                        @if (isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0)
                        {{ round($barangay[$receipt->serial_no], 2) }}
                        @endif
                    </td>
                    @endforeach
                @endforeach

                <td class=" border-botts val text-right">
                    <span class="hidden">
                        {{ $gtotal += $receipts_total[$receipt->serial_no] }}
                    </span>
                    {{ round($receipts_total[$receipt->serial_no], 2) }}
                </td>
            @endif
        </tr>
        @endforeach
        <tr class="page-break">
            <td class="val" colspan="2">GRAND TOTAL</td>
            @foreach($accounts as $i => $account)
                @foreach($account['titles'] as $j => $title)
                    <td class="val text-right">
                        {{ round($title['total'], 2) }}
                    </td>
                @endforeach

                @foreach($account['subtitles'] as $j => $subtitle)
                    <td class="val text-right">
                        {{ round($subtitle['total'], 2) }}
                    </td>
                @endforeach
            @endforeach

            @foreach($shares as $i => $share)
                <td class=" val text-right">
                    {{ round($share['total_share'], 2) }}
                </td>
                @foreach($share['barangays'] as $j => $barangay)
                <td class=" val text-right">
                    {{ round($barangay['total_share'], 2) }}
                </td>
                @endforeach
            @endforeach
            <td class=" val text-right">{{ round($gtotal, 2) }}</td>
        </tr>
    </tbody>
</table>
