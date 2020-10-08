        <table class="center ">
    <tr>
       
        <td>
        REPORT OF COLLECTIONS AND DEPOSITS <br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

    <table class="table table-condensed ">
        <tr>
            <td>Fund: <b>{{ $fund->name }}</b></td>
            <td>Date</td>
            <td class="underline">{{ date('F d, Y') }}</td>
        </tr>
        <tr>
            <td>Name of Accountable Officer: <b>{{ $acctble_officer_name->value }} - {{ $acctble_officer_position->value }}</b></td>
            <td class="val">Report No.</td>
            <td class="underline">{{ $_GET['report_no'] }}</td>
        </tr>

    </table>

<h4>A. COLLECTIONS</h4>

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


<table>
                    <tr >
                        <td><b>SUMMARY OF COLLECTION</b></td>
                        <td >
                            <?php $total = 0; ?>
                        </td>
                    </tr>
                    @foreach($accounts as $i => $account)
                        @foreach($account['titles'] as $j => $title)
                        <tr >
                            <td>{{ $j }}</td>
                            <td class="val text-right">
                                <?php $total += $title['total']; ?> 
                                {{ round($title['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach

                        @foreach($account['subtitles'] as $j => $subtitle)
                        <tr >
                            <td>{{ $j }}</td>
                            <td class="val text-right">
                               <?php $total += $subtitle['total']; ?> 
                                {{ round($subtitle['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                    @if ($bac_type_1 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC Goods & Services</td>
                        <td class="val">
                            <?php $total += $bac_type_1; ?>
                            {{ round($bac_type_1, 2) }}
                        </td>
                    </tr>
                    @endif

                    @if ($bac_type_2 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC INFRA</td>
                        <td class="val text-right">
                            <?php $total += $bac_type_2; ?>
                            {{ round($bac_type_2, 2) }}
                        </td>
                    </tr>
                    @endif

                    @if ($bac_type_3 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC Drugs & Meds</td>
                        <td class="val text-right">
                            <?php $total += $bac_type_3; ?>

                            {{ round($bac_type_3, 2) }}
                        </td>
                    </tr>
                    @endif

                    <tr class="set-border-tb">
                        <td ><b>TOTAL</b></td>
                        <td class="val text-right">
                            <b>{{ round($total, 2) }}</b>
                        </td>
                    </tr>

                </table>
    @if ($_GET['type'] == 1)
            <table class="table">
                <tr>
                    <td><b>Municipal/Barangay Share</b></td>
                    <td>
                       <?php $total = 0; ?>
                    </td>
                </tr>
                @foreach ($shares as $i => $share)
                    <tr >
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val">
                            @if (isset($amusement_shares[$i]))
                                <?php  $share_value = $share['total_share'] - $amusement_shares[$i]['total_share']; ?>
                            @else
                                <?php $share_value = $share['total_share']; ?>
                            @endif
                            <?php $total += $share_value; ?>

                            {{ round($share_value, 2) }}
                        </td>
                    </tr>
                    @foreach ($share['barangays'] as $j => $barangay)
                        <tr >
                            <td><div class="brgy">{{ $barangay['name'] }}</div></td>
                            <td class="val text-right">
                               
                                <?php $total += $barangay['total_share']; ?>
                                
                                {{ round($barangay['total_share'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr class="set-border-tb">
                    <td ><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b>{{ round($total, 2) }}</b>
                    </td>
                </tr>
            </table>

            <table class="table">
                <tr>
                    <td><b>Amusement Share</b></td>
                    <td>
                        <?php $total = 0; ?>
                    </td>
                </tr>
                @foreach ($amusement_shares as $i => $share)
                    <tr>
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val text-right">
                          <?php $total += $share['total_share']; ?>
                            {{ round($share['total_share'], 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="set-border-tb">
                    <td class=""><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b>{{ round($total, 2) }}</b>
                    </td>
                </tr>
            </table>
@endif


<h4>REMITTANCES/DEPOSITS</h4>
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



 <h4>ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
<table class="table table-bordered table-condensed page-break">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="3">Name of Forms & No.</th>
            <th class="" colspan="3">Beginning Balance</th>
            <th class="" colspan="3">Receipt</th>
            <th class="" colspan="3">Issued</th>
            <th class="" colspan="3">Ending Balance</th>
        </tr>
        <tr class="page-break">
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
        </tr>
        <tr class="page-break">
            <th class="">From</th>
            <th class="">To</th>
            <th class="">From</th>
            <th class="">To</th>
            <th class="">From</th>
            <th class="">To</th>
            <th class="">From</th>
            <th class="">To</th>
        </tr>
        <tr class="page-break">
            <th class="" colspan="13">
                Accountable Form 51
                <span class="hidden">
                {{ $beg_total = 0 }}
                {{ $rec_total = 0 }}
                {{ $iss_total = 0 }}
                {{ $end_total = 0 }}
                </span>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rcpt_acct as $rcpt)
        <tr class="page-break">
            <td class="">
                <span class="hidden">
                {{ $beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0 }}
                {{ $rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0 }}
                {{ $iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0 }}
                {{ $end_total += $rcpt['end_qty']?$rcpt['end_qty']:0 }}
                </span>
            </td>
            <td class=" val">{{ $rcpt['beg_qty'] }}</td>
            <td class=" val">{{ $rcpt['beg_from'] }}</td>
            <td class=" val">{{ $rcpt['beg_to'] }}</td>
            <td class=" val">{{ $rcpt['rec_qty'] }}</td>
            <td class=" val">{{ $rcpt['rec_from'] }}</td>
            <td class=" val">{{ $rcpt['rec_to'] }}</td>
            <td class=" val">{{ $rcpt['iss_qty'] }}</td>
            <td class=" val">{{ $rcpt['iss_from'] }}</td>
            <td class=" val">{{ $rcpt['iss_to'] }}</td>
            <td class=" val">{{ $rcpt['end_qty'] }}</td>
            <td class=" val">{{ $rcpt['end_from'] }}</td>
            <td class=" val">{{ $rcpt['end_to'] }}</td>
        </tr>
        @endforeach
        <tr class="page-break">
            <td class=" val"></td>
            <td class=" val"><b>{{ $beg_total }}</b></td>
            <td class=" val"></td>
            <td class=" val"></td>
            <td class=" val"><b>{{ $rec_total }}</b></td>
            <td class=" val"></td>
            <td class=" val"></td>
            <td class=" val"><b>{{ $iss_total }}</b></td>
            <td class=" val"></td>
            <td class=" val"></td>
            <td class=" val"><b>{{ $end_total }}</b></td>
            <td class=" val"></td>
            <td class=" val"></td>
        </tr>
        </tbody>

    </table>

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

