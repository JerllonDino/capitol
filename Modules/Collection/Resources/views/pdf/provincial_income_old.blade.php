<!DOCTYPE html>
<html>
<head>
    <title>Provincial Income</title>
    <style>
        @page { margin: 10px; }
        body {
            margin: 15px;
            font-family: arial, "sans-serif";
            font-size: 8.5;
        }
        .center {
            text-align: center;
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
        .border_all_table {
            margin: 1px;
            padding-top: 15px;
        }
        .border_all {
            border: 1px solid #000000;
        }
        .title {
            padding-left: 25px;
        }
        .subtitle {
            padding-left: 50px;
        }
        .subtitleitems{
             padding-left: 75px;
        }
        .hidden {
            display: none;
        }
        #cert {
            width: 100%;
            padding-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <table class="center">
        <tr>
            <td>Republic of the Philippines</td>
        </tr>
        <tr>
            <td>PROVINCE OF BENGUET</td>
        </tr>
        <tr>
            <td>La Trinidad, Benguet</td>
        </tr>
        <tr>
            <td>OFFICE OF THE PROVINCIAL TREASURER</td>
        </tr>
        <tr>
            <td>REPORT OF PROVINCIAL INCOME</td>
        </tr>
        <tr>
            <td>For the Period, {{ date('F d, Y', strtotime($start_date)) }} to {{ date('F d, Y', strtotime($end_date)) }}</td>
        </tr>
    </table>

    <span class="hidden">
        {{ $GT_estimate_total = $GT_prevmonth_total = $GT_actual_total = $GT_grand_total = 0 }}
    </span>
    @foreach ($categories as $idx => $category)

    @if ($idx != 4)

        <span class="hidden">
            {{ $estimate_total = $prevmonth_total = $actual_total = $grand_total = 0 }}
        </span>
        <table class="border_all_table">
            <tr>
                <th class="border_all" rowspan="1" width="20em">Account Title</th>
                <th class="border_all" rowspan="2" width="7em">Account Code</th>
                <th class="border_all" rowspan="2" width="7em">Budget Estimate</th>
                <th class="border_all" rowspan="2" width="7em">Collection from {{ $date_startyear }} - {{ $date_prevmonth }}</th>
                <th class="border_all" rowspan="2" width="7em">Actual Collection</th>
                <th class="border_all" rowspan="2" width="7em">Total</th>
                <th class="border_all" rowspan="2" width="7em">% of Coll.</th>
            </tr>
            <tr>
                <th class="border_all" rowspan="1">{{ $category->name }}</th>
            </tr>
            @foreach ($category->group as $group)
                <tr>
                    <td class="border_all">{{ $group->name }}</td>
                    <td class="border_all" colspan="6"></td>
                </tr>
                @foreach ($group->title as $title)
                    @if ($title->show_in_monthly == 1)
                    <span class="hidden">
                            
                        @if (count($titles) != 0 && isset($titles[$title->id]))
                        {{ $estimate_total += $titles[$title->id]['estimate'] }}
                        {{ $prevmonth_total += $titles[$title->id]['startyear_prevmonth'] }}
                        {{ $actual_total += $titles[$title->id]['actual_value'] }}
                        {{ $grand_total += $titles[$title->id]['total_value'] }}

                        {{ $GT_estimate_total += $titles[$title->id]['estimate'] }}
                        {{ $GT_prevmonth_total += $titles[$title->id]['startyear_prevmonth'] }}
                        {{ $GT_actual_total += $titles[$title->id]['actual_value'] }}
                        {{ $GT_grand_total += $titles[$title->id]['total_value'] }}
                        @endif
                    </span>
                    <tr>
                        <td class="border_all title">{{ $title->name }}</td>
                        <td class="border_all center">{{ $title->code }}</td>
                        @if (count($titles) != 0 && isset($titles[$title->id]) )
                            <td class="border_all right">
                                {{ number_format($titles[$title->id]['estimate'], 2) }}
                            </td>
                            <td class="border_all right">
                                {{ number_format($titles[$title->id]['startyear_prevmonth'], 2) }}
                            </td>
                            <td class="border_all right">
                                {{ number_format($titles[$title->id]['actual_value'], 2) }}
                            </td>
                            <td class="border_all right">
                                {{ number_format($titles[$title->id]['total_value'], 2) }}
                            </td>
                            <td class="border_all right">
                                {{ number_format($titles[$title->id]['pct_collection'], 2) }}
                            </td>
                        @else
                            <td class="border_all"></td>
                            <td class="border_all"></td>
                            <td class="border_all"></td>
                            <td class="border_all"></td>
                            <td class="border_all"></td>
                        @endif

                    </tr>
                    @endif
                    @foreach ($title->subs as $subtitle)
                        @if ($subtitle->show_in_monthly == 1 )
                        <span class="hidden">
                            @if (count($subtitles) != 0 && isset($subtitles[$subtitle->id]['estimate']) )
                            {{ $estimate_total += $subtitles[$subtitle->id]['estimate'] }}
                            {{ $prevmonth_total += $subtitles[$subtitle->id]['startyear_prevmonth'] }}
                            {{ $actual_total += $subtitles[$subtitle->id]['actual_value'] }}
                            {{ $grand_total += $subtitles[$subtitle->id]['total_value'] }}

                            {{ $GT_estimate_total += $subtitles[$subtitle->id]['estimate'] }}
                            {{ $GT_prevmonth_total += $subtitles[$subtitle->id]['startyear_prevmonth'] }}
                            {{ $GT_actual_total += $subtitles[$subtitle->id]['actual_value'] }}
                            {{ $GT_grand_total += $subtitles[$subtitle->id]['total_value'] }}
                            @endif
                        </span>
                        <tr>
                            <td class="border_all subtitle">{{ $subtitle->name }}</td>
                            <td class="border_all center">{{ $subtitle->code }}</td>
                            @if (count($subtitles) != 0 && isset($subtitles[$subtitle->id]['estimate']) )
                                <td class="border_all right">
                                    {{ number_format($subtitles[$subtitle->id]['estimate'], 2) }}
                                </td>
                                <td class="border_all right">
                                    {{ number_format($subtitles[$subtitle->id]['startyear_prevmonth'], 2) }}
                                </td>
                                <td class="border_all right">
                                    {{ number_format($subtitles[$subtitle->id]['actual_value'], 2) }}
                                </td>
                                <td class="border_all right">
                                    {{ number_format($subtitles[$subtitle->id]['total_value'], 2) }}
                                </td>
                                <td class="border_all right">
                                    {{ number_format($subtitles[$subtitle->id]['pct_collection'], 2) }}
                                </td>
                            @else
                                <td class="border_all"></td>
                                <td class="border_all"></td>
                                <td class="border_all"></td>
                                <td class="border_all"></td>
                                <td class="border_all"></td>
                            @endif
                        </tr>
                        @endif

                        @foreach ($subtitle->subtitleitems as $subtitleitem)
                        @if ( $subtitleitem->show_in_monthly == 1 && isset($subtitleitems[$subtitleitem->id]['estimate']) )
                            @if (count($subtitles) != 0 && isset($subtitleitems[$subtitleitem->id]['estimate']) )
                            <?php 
                                $estimate_total += $subtitleitems[$subtitleitem->id]['estimate'];
                                $prevmonth_total += $subtitleitems[$subtitleitem->id]['startyear_prevmonth'];
                                $actual_total += $subtitleitems[$subtitleitem->id]['actual_value'];
                                $grand_total += $subtitleitems[$subtitleitem->id]['total_value'];
                            
                                $GT_estimate_total += $subtitleitems[$subtitleitem->id]['estimate'];
                                $GT_prevmonth_total += $subtitleitems[$subtitleitem->id]['startyear_prevmonth'];
                                $GT_actual_total += $subtitleitems[$subtitleitem->id]['actual_value'];
                                $GT_grand_total += $subtitleitems[$subtitleitem->id]['total_value'];
                            ?>
                          
                            @endif

                            <tr>
                                <td class="border_all subtitleitems">{{ $subtitleitem->item_name }}</td>
                                 <td class="border_all center"></td>
                                @if (count($subtitleitems) != 0)
                                    <td class="border_all right">{{ number_format($subtitleitems[$subtitleitem->id]['estimate'], 2) }}</td>
                                    <td class="border_all right">{{ number_format($subtitleitems[$subtitleitem->id]['startyear_prevmonth'], 2) }}</td>
                                    <td class="border_all right">{{ number_format($subtitleitems[$subtitleitem->id]['actual_value'], 2) }}</td>
                                    <td class="border_all right">{{ number_format($subtitleitems[$subtitleitem->id]['total_value'], 2) }}</td>
                                    <td class="border_all right">{{ number_format($subtitleitems[$subtitleitem->id]['pct_collection'], 2) }}</td>
                                @else
                                    <td class="border_all "></td>
                                    <td class="border_all "></td>
                                    <td class="border_all "></td>
                                    <td class="border_all "></td>
                                    <td class="border_all "></td>
                                @endif

                                
                            </tr>
                        @endif
                        @endforeach

                    @endforeach
                @endforeach
            @endforeach
            <tr>
                <td class="border_all"><b>TOTAL</b></td>
                <td class="border_all"></td>
                <td class="border_all right"><b>{{ number_format($estimate_total, 2) }}</b></td>
                <td class="border_all right"><b>{{ number_format($prevmonth_total, 2) }}</b></td>
                <td class="border_all right"><b>{{ number_format($actual_total, 2) }}</b></td>
                <td class="border_all right"><b>{{ number_format($grand_total, 2) }}</b></td>
                <td class="border_all right"><b>
                    @if ($estimate_total)
                    {{
                        number_format(
                            ($grand_total/$estimate_total) * 100,
                            2
                        )
                    }}
                    @else
                        0.00
                    @endif
                </b></td>
            </tr>
            <tr>
                <td colspan="7" class="border_all"></td>
            </tr>
        </table>

        @if ($category->name == "Benguet Equipment Services Enterprise (BESE)")
        <table class="border_all_table">
            <tr>
                <th class="border_all" colspan="2" width="27em">GRAND TOTAL - GENERAL FUND</th>
                <th class="border_all right" width="7em">{{ number_format($GT_estimate_total, 2) }}</th>
                <th class="border_all right" width="7em">{{ number_format($GT_prevmonth_total, 2) }}</th>
                <th class="border_all right" width="7em">{{ number_format($GT_actual_total, 2) }}</th>
                <th class="border_all right" width="7em">{{ number_format($GT_grand_total, 2) }}</th>
                <th class="border_all right" width="7em">
                    @if ($GT_estimate_total)
                    {{
                        number_format(
                            ($GT_grand_total/$GT_estimate_total) * 100,
                            2
                        )
                    }}
                    @else
                        0.00
                    @endif
                </th>
            </tr>
        </table>
        @endif
    @endif

    @endforeach
    <table id="cert">
        <tr>
            <td width="75%">Certified correct:</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td class="center"><b>{{ $provincial_name->value }}</b></td>
        </tr>
        <tr>
            <td></td>
            <td class="center">{{ $provincial_position->value }}</td>
        </tr>
    </table>
</body>
</html>
