<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    <style>
		html {
			margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 15px;
            margin-right: 15px;
		}
        body {
            font-family: arial, "sans-serif";
            @if ($total_columns > 20)
                font-size: 6;
            @elseif($total_columns > 15)
                font-size: 7;
            @elseif($total_columns > 10)
                font-size: 8;
            @elseif($total_columns > 1)
                font-size: 9;
            @endif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, td {
            padding: 2px;
            vertical-align: middle !important;
        }
        .center {
            width: 100%;
            text-align: center;
        }
        .border_all_table {
            margin: 1px;
            /*padding-top: 5px;*/
        }
        .border_all {
            border: 1px solid #000000;
        }
         
        .val {
            text-align: right;
        }
        .cancelled_remark {
            color: red;
        }
        .hidden {
            display: none;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .brgy {
            margin-left: 20px;
        }
        .newpage {
            page-break-before: always;
        }
        .border-top {
            border-top: 1px solid #000000;
        }
		.tdindent {
			padding-left: 30px;
		}
		.detail_payor {
			white-space: nowrap;
		}
        .set-border-tb>td{
            border-top:1px solid #000000;
            border-bottom:1px solid #000000;

        }

        .SUMMARY_OF_COLLECTION_Municipal_Barangay_Share{
            position:relative; width: 100%; 
            /*margin-top: 1.25cm;*/
           
            
        }
        .SUMMARY_OF_COLLECTION,.Municipal_Barangay_Share{
        }

        .SUMMARY_OF_COLLECTION{
             /*width:35%;
             float:left;
             background: red;*/
        }

        .Municipal_Barangay_Share{
            /*width:35%;  
            margin-left: 55%;  
            float:right;
            background: lightblue;*/
        }

        .REMITTANCES_DEPOSITS{
            /*margin-top: 1cm;*/
        }

    </style>
</head>
<body>
    <span class="hidden">
        {{ $gtotal = 0 }}
    </span>
    <table class="center">
        <tr>
            <td>REPORT OF COLLECTIONS AND DEPOSITS</td>
        </tr>
        <tr>
            <td><b>PROVINCIAL GOVERNMENT OF BENGUET</b></td>
        </tr>
        <tr>
            <td><b>OFFICE OF THE PROVINCIAL TREASURER</b></td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td>Fund: <b>{{ $fund->name }}</b></td>
            <td class="val">Date</td>
            <td class="underline">{{ date('F d, Y') }}</td>
        </tr>
        <tr>
            <td>Name of Accountable Officer: <b>{{ $acctble_officer_name->value }} - {{ $acctble_officer_position->value }}</b></td>
            <td class="val">Report No.</td>
            <td class="underline">{{ $_GET['report_no'] }}</td>
        </tr>
        <tr>
            <td colspan="3">A. COLLECTIONS</td>
        </tr>
    </table>

    <table class="border_all_table">
        <tr>
            <th class="border_all" rowspan="2">OR Nos.</th>
            <th class="border_all detail_payor" rowspan="2">Payor</th>
            @foreach($accounts as $i => $account)
                <th class="border_all" colspan="{{ count($account['titles']) + count($account['subtitles']) }}">{{ $i }}</th>
            @endforeach

            @if (count($shares) > 0)
            <th class="border_all" colspan="{{ $share_columns }}">MUNICIPAL & BRGY SHARES</th>
            @endif

            <th class="border_all" rowspan="2">TOTAL AMOUNT</th>
        </tr>
        <tr>

            @foreach($accounts as $i => $account)
                @foreach($account['titles'] as $j => $title)
                    <th class="border_all">{{ $j }}</th>
                @endforeach

                @foreach($account['subtitles'] as $j => $subtitle)
                    <th class="border_all">{{ $j }}</th>
                @endforeach
            @endforeach

            @foreach($shares as $i => $share)
                <th class="border_all">{{ $share['name'] }}</th>
                @foreach($share['barangays'] as $j => $barangay)
                <th class="border_all">{{ $barangay['name'] }}</th>
                @endforeach
            @endforeach
        </tr>
        <tr>
            <td class="border_all" colspan="{{ $total_columns + 1 }}">{{ $date_range }}</td>
        </tr>
        <!-- VALUES PER RECEIPT -->
        @foreach ($receipts as $i => $receipt)
        <tr>
            <td class="border_all">{{ $receipt->serial_no }}</td>


            @if (!isset($receipts_total[$receipt->serial_no]))
                <td class="border_all cancelled_remark" colspan="{{ $total_columns }}">
                    Cancelled - {{ $receipt->cancelled_remark }}
                </td>
            @else
                <td class="border_all detail_payor">{{ $receipt->customer->name }}</td>
                @foreach($accounts as $i => $account)
                    @foreach($account['titles'] as $ji => $title)
                        <td class="border_all val">
                            @if (isset($title[$receipt->serial_no]))
                            {{ number_format($title[$receipt->serial_no], 2) }}
                            @endif
                        </td>
                    @endforeach

                    @foreach($account['subtitles'] as $j => $subtitle)
                        <td class="border_all val">
                            @if (isset($subtitle[$receipt->serial_no]))
                            {{ number_format($subtitle[$receipt->serial_no], 2) }}
                            @endif
                        </td>
                    @endforeach
                @endforeach

                @foreach($shares as $i => $share)
                    <td class="border_all val">
                        @if (isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0)
                        {{ number_format($share[$receipt->serial_no], 2) }}
                        @endif
                    </td>
                    @foreach($share['barangays'] as $j => $barangay)
                    <td class="border_all val">
                        @if (isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0)
                        {{ number_format($barangay[$receipt->serial_no], 2) }}
                        @endif
                    </td>
                    @endforeach
                @endforeach

                <td class="border_all val">
                    <span class="hidden">
                        {{ $gtotal += $receipts_total[$receipt->serial_no] }}
                    </span>
                    {{ number_format($receipts_total[$receipt->serial_no], 2) }}
                </td>
            @endif
        </tr>
        @endforeach

        <!-- TOTALS -->
        <tr>
            <th class="border_all" colspan="2">GRAND TOTAL</th>
            @foreach($accounts as $i => $account)
                @foreach($account['titles'] as $j => $title)
                    <th class="border_all val">
                        {{ number_format($title['total'], 2) }}
                    </th>
                @endforeach

                @foreach($account['subtitles'] as $j => $subtitle)
                    <th class="border_all val">
                        {{ number_format($subtitle['total'], 2) }}
                    </th>
                @endforeach
            @endforeach

            @foreach($shares as $i => $share)
                <th class="border_all val">
                    {{ number_format($share['total_share'], 2) }}
                </th>
                @foreach($share['barangays'] as $j => $barangay)
                <th class="border_all val">
                    {{ number_format($barangay['total_share'], 2) }}
                </th>
                @endforeach
            @endforeach
            <th class="border_all val">{{ number_format($gtotal, 2) }}</th>
        </tr>
        <tr>
            <th class="border_all" colspan="2"></th>
            @foreach($accounts as $i => $account)
                @foreach($account['titles'] as $j => $title)
                    <th class="border_all val">
                    </th>
                @endforeach

                @foreach($account['subtitles'] as $j => $subtitle)
                    <th class="border_all val">
                    </th>
                @endforeach
            @endforeach

            @foreach($shares as $i => $share)
                <th class="border_all val">
                </th>
                @foreach($share['barangays'] as $j => $barangay)
                <th class="border_all val">
                </th>
                @endforeach
            @endforeach
            <th class="border_all val"></th>
        </tr>
    </table>
  
    
    <table>
        <tr>
        <td>
            <div style="" class="SUMMARY_OF_COLLECTION">
                <table >
                    <tr>
                        <td><u><b>SUMMARY OF COLLECTION</b></u></td>
                        <td>
                            <span class="hidden">
                            {{ $total = 0 }}
                            </span>
                        </td>
                    </tr>
                    @foreach($accounts as $i => $account)
                        @foreach($account['titles'] as $j => $title)
                        <tr>
                            <td>{{ $j }}</td>
                            <td class="val">
                                <span class="hidden">
                                {{ $total += $title['total'] }}
                                </span>
                                {{ number_format($title['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach

                        @foreach($account['subtitles'] as $j => $subtitle)
                        <tr>
                            <td>{{ $j }}</td>
                            <td class="val">
                                <span class="hidden">
                                {{ $total += $subtitle['total'] }}
                                </span>
                                {{ number_format($subtitle['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                    @if ($bac_type_1 > 0 && $_GET['type'] == 1)
                    <tr>
                        <td>BAC Goods & Services</td>
                        <td class="val">
                            <span class="hidden">
                            {{ $total += $bac_type_1 }}
                            </span>
                            {{ number_format($bac_type_1, 2) }}
                        </td>
                    </tr>
                    @endif

                    @if ($bac_type_2 > 0 && $_GET['type'] == 1)
                    <tr>
                        <td>BAC INFRA</td>
                        <td class="val">
                            <span class="hidden">
                            {{ $total += $bac_type_2 }}
                            </span>
                            {{ number_format($bac_type_2, 2) }}
                        </td>
                    </tr>
                    @endif

                    @if ($bac_type_3 > 0 && $_GET['type'] == 1)
                    <tr>
                        <td>BAC Drugs & Meds</td>
                        <td class="val">
                            <span class="hidden">
                            {{ $total += $bac_type_3 }}
                            </span>
                            {{ number_format($bac_type_3, 2) }}
                        </td>
                    </tr>
                    @endif

                    <tr class="set-border-tb">
                        <td ><b>TOTAL</b></td>
                        <td class="val ">
                            <b>{{ number_format($total, 2) }}</b>
                        </td>
                    </tr>

                </table>
            </div>
        </td>
        <td style="width: 10%;"></td>
        <td>
        <div style="" class="Municipal_Barangay_Share">
            @if ($_GET['type'] == 1)
            <table>
                <tr>
                    <td><u><b>Municipal/Barangay Share</b></u></td>
                    <td>
                        <span class="hidden">
                        {{ $total = 0 }}
                        </span>
                    </td>
                </tr>
                @foreach ($shares as $i => $share)
                    <tr>
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val">
                            <span class="hidden">
                            @if (isset($amusement_shares[$i]))
                                {{ $share_value = $share['total_share'] - $amusement_shares[$i]['total_share'] }}
                            @else
                                {{ $share_value = $share['total_share'] }}
                            @endif
                            {{ $total += $share_value }}
                            </span>
                            {{ number_format($share_value, 2) }}
                        </td>
                    </tr>
                    @foreach ($share['barangays'] as $j => $barangay)
                        <tr>
                            <td><div class="brgy">{{ $barangay['name'] }}</div></td>
                            <td class="val">
                                <span class="hidden">
                                {{ $total += $barangay['total_share'] }}
                                </span>
                                {{ number_format($barangay['total_share'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr class="set-border-tb">
                    <td ><b>TOTAL</b></td>
                    <td class="val">
                        <b>{{ number_format($total, 2) }}</b>
                    </td>
                </tr>
            </table>
            @endif


            @if ($_GET['type'] == 1)
            <table>
                <tr>
                    <td><b>Amusement Share</b></td>
                    <td>
                        <span class="hidden">
                        {{ $total = 0 }}
                        </span>
                    </td>
                </tr>
                @foreach ($amusement_shares as $i => $share)
                    <tr>
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val">
                            <span class="hidden">
                            {{ $total += $share['total_share'] }}
                            </span>
                            {{ number_format($share['total_share'], 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="set-border-tb">
                    <td class=""><b>TOTAL</b></td>
                    <td class="val">
                        <b>{{ number_format($total, 2) }}</b>
                    </td>
                </tr>
            </table>
            @endif
        </div>
    </td>
    </tr>
    </table>

    <span class="hidden">
		{{ $summary_total = 0 }}
		{{ $total_with_ada = 0 }}
		{{ $has_ada = 0 }}
		{{ $ada = 0 }}
		@foreach ($trantypes as $i => $type)
					@if ($i == 4)
						@if ($type['total'] > 0)
						{{ $ada = $type['total']}}
						{{ $has_ada = 1 }}
						@endif
						{{ $total_with_ada += $type['total'] }}
					@else
						{{ $total_with_ada += $type['total'] }}
						{{ $summary_total += $type['total'] }}
					@endif
		@endforeach
    </span>


    <table class="border_all_table">
        <tr>
            <td colspan="3">B. REMITTANCES/DEPOSITS</td>
        </tr>
        <tr>
            <th class="border_all">ACCOUNTABLE OFFICER/BANK</th>
            <th class="border_all">REFERENCE</th>
            <th class="border_all">TOTAL</th>
        </tr>
        <tr>
            <td class="border_all">
                @if ($_GET['type'] == 5)
                    {{ $trust_fund_officer_name->value }}
                @else
                    {{ $officer_name->value }}
                @endif
            </td>
            <td class="border_all">{{ $_GET['report_no'] }}</td>
            <td class="border_all val">PHP {{ number_format($summary_total, 2) }}</td>
        </tr>
    </table>

    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->.
    <table class="border_all_table ">
        <tr>
            <td colspan="13">C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</td>
        </tr>
        <tr>
            <th class="border_all" rowspan="3">Name of Forms & No.</th>
            <th class="border_all" colspan="3">Beginning Balance</th>
            <th class="border_all" colspan="3">Receipt</th>
            <th class="border_all" colspan="3">Issued</th>
            <th class="border_all" colspan="3">Ending Balance</th>
        </tr>
        <tr>
            <th class="border_all" rowspan="2">Qty.</th>
            <th class="border_all" colspan="2">Inclusive Serial Nos.</th>
            <th class="border_all" rowspan="2">Qty.</th>
            <th class="border_all" colspan="2">Inclusive Serial Nos.</th>
            <th class="border_all" rowspan="2">Qty.</th>
            <th class="border_all" colspan="2">Inclusive Serial Nos.</th>
            <th class="border_all" rowspan="2">Qty.</th>
            <th class="border_all" colspan="2">Inclusive Serial Nos.</th>
        </tr>
        <tr>
            <th class="border_all">From</th>
            <th class="border_all">To</th>
            <th class="border_all">From</th>
            <th class="border_all">To</th>
            <th class="border_all">From</th>
            <th class="border_all">To</th>
            <th class="border_all">From</th>
            <th class="border_all">To</th>
        </tr>
        <tr>
            <td class="border_all" colspan="13">
                Accountable Form 51
                <span class="hidden">
                {{ $beg_total = 0 }}
                {{ $rec_total = 0 }}
                {{ $iss_total = 0 }}
                {{ $end_total = 0 }}
                </span>
            </td>
        </tr>
        @foreach ($rcpt_acct as $rcpt)
        <tr>
            <td class="border_all">
                <span class="hidden">
                {{ $beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0 }}
                {{ $rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0 }}
                {{ $iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0 }}
                {{ $end_total += $rcpt['end_qty']?$rcpt['end_qty']:0 }}
                </span>
            </td>
            <td class="border_all val">{{ $rcpt['beg_qty'] }}</td>
            <td class="border_all val">{{ $rcpt['beg_from'] }}</td>
            <td class="border_all val">{{ $rcpt['beg_to'] }}</td>
            <td class="border_all val">{{ $rcpt['rec_qty'] }}</td>
            <td class="border_all val">{{ $rcpt['rec_from'] }}</td>
            <td class="border_all val">{{ $rcpt['rec_to'] }}</td>
            <td class="border_all val">{{ $rcpt['iss_qty'] }}</td>
            <td class="border_all val">{{ $rcpt['iss_from'] }}</td>
            <td class="border_all val">{{ $rcpt['iss_to'] }}</td>
            <td class="border_all val">{{ $rcpt['end_qty'] }}</td>
            <td class="border_all val">{{ $rcpt['end_from'] }}</td>
            <td class="border_all val">{{ $rcpt['end_to'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td class="border_all val"></td>
            <td class="border_all val"><b>{{ $beg_total }}</b></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"><b>{{ $rec_total }}</b></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"><b>{{ $iss_total }}</b></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"><b>{{ $end_total }}</b></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
        </tr>
        <tr>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
            <td class="border_all val"></td>
        </tr>
    </table>


    <!-- SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS -->
    <table>
        <tr>
        <td>
        <table class="border_all_table">
            <tr>
                <td class="" colspan="2">D. SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS</td>
            </tr>
			<tr>
				<td class="border_all">Beginning Balance {{ $report_start }}</td>
				<td class="border_all val">

				</td>
			</tr>
			<tr>
				<td class="border_all">Add: Collections {{ $date_range }}</td>
				<td class="border_all val">

				</td>
			</tr>
            @foreach ($trantypes as $i => $type)
                <tr>
                    <td class="border_all tdindent">{{ $type['name'] }}</td>
                    <td class="border_all val">
                        {{ number_format($type['total'], 2) }}
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td class="border_all"><b>Total</b></td>
                    <td class="border_all val"><b>{{ number_format($total_with_ada, 2) }}</b></td>
                </tr>
			@if ($has_ada)
                <tr>
                    <td class="border_all"><b>Less ADA</b></td>
                    <td class="border_all val"><b>{{ number_format($ada, 2) }}</b></td>
                </tr>
            @endif
            <tr>
                <td class="border_all"><b>Remittance/Deposit to Cashier/Treasurer</b></td>
                <td class="border_all val"><b>{{ number_format($summary_total, 2) }}</b></td>
            </tr>
			<tr>
                <td class="border_all"><b>Balance</b></td>
                <td class="border_all val"><b></b></td>
            </tr>
        </table>
        </td>
        
        <td>
            <span class="hidden">
            {{ $bank_total = 0 }}
            </span>
             <table class="border_all_table">
                    <thead>
                        <tr>
                            <th class="border_all">Drawee Bank</th>
                            <th class="border_all">Check No.</th>
                            <th class="border_all">Payee</th>
                            <th class="border_all">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                             @foreach($bank as $b)
                                <tr>
                                    <td class="border_all">{{ $b['bank'] }}</td>
                                    <td class="border_all">{{ $b['check_no'] }}</td>
                                    <td class="border_all">Provincial Government of Benguet</td>
                                    <td class="border_all val">
                                        <span class="hidden">
                                        {{ $bank_total += $b['amt'] }}
                                        </span>
                                        {{ number_format($b['amt'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="border_all" colspan="3">Total</td>
                                    <td class="border_all  val">{{ number_Format($bank_total, 2) }}</td>
                                </tr>
                    </tbody>
                </table>
        </td>
    </tr>
    </table>
    <br>

    <!-- CERTIFICATION/VERIFICATION AND ACKNOWLEDGEMENT -->
    <table>
        <tr>
        <td>
        <table class="border_all_table">
            <tr>
                <th class="border_all">CERTIFICATION</th>
            </tr>
            <tr>
                <td class="border_all">
                    <table>
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            and accountability for Accountable Forms is true and correct.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <th class="underline">{{ $acctble_officer_name->value }}</th>
                            <th></th>
                            <th class="underline">{{ date('F d, Y') }}</th>
                        </tr>
                        <tr>
                            <th>{{ $acctble_officer_position->value }}</th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        <td>
        <table class="border_all_table">
            <tr>
                <th class="border_all" style="padding-bottom: 5px;">VERIFICATION AND ACKNOWLEDGEMENT</th>
            </tr>
            <tr>
                <td class="border_all">
                    <table>
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            has been verified and acknowledge receipt of
                            <b><u>{{ $total_in_words }}</u> (PHP {{ number_format($summary_total, 2) }})</b>.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <th class="underline">
                                @if ($_GET['type'] == 5)
                                    {{ $trust_fund_officer_name->value }}
                                @else
                                    {{ $officer_name->value }}
                                @endif
                            </th>
                            <th></th>
                            <th class="underline">{{ date('F d, Y') }}</th>
                        </tr>
                        <tr>
                            <th>{{ $officer_position->value }}</th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>
     
</body>
</html>