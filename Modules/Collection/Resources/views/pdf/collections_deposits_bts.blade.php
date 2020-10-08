<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 15px;
            margin-right: 15px;
        }
        /* class works for table row */
        table tr.page-break{
          /*page-break-after:always*/
        }


        /* class works for table */
        table.page-break{
          /*page-break-after:always*/
        }

        @media print {
         /*.page-break  { display: block; page-break-before: always; }*/
        }
        .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            border-top: 1px solid #868282;
            border-bottom: 1px solid #868282;
        }
       .table>thead:first-child>tr:first-child>th, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000;
        }
          .table-no-border>tbody>tr>td, .table-no-border>tbody>tr>th, .table-no-border>tfoot>tr>td, .table>tfoot>tr>th, .table-no-border>thead>tr>td, .table-no-border>thead>tr>th {
            border: none;
        }

        .table-borderedx{
                border: 1px solid #868282;
        }
         .table-border-right{
                border-right: 1px solid #868282;
         }

        .border-top{
                 border-top: 1px solid #000 !important;
        }

        .border-botts {
            border-bottom: 1px solid #000 !important;
        }

        .image_logo{
            width: 100px;
        }

        .val{
            font-weight: bold;
        }

        .pagenum:before {
            content: counter(page);
        }

        .footer {
            bottom:15px;
            position: fixed;
             width: 100%;
               color:#44474c;
        }
    </style>

            @if ($total_columns >= 18)
                {{ Html::style('/base/css/collections_deposti20.css') }}
            @elseif($total_columns >= 15)
                {{ Html::style('/base/css/collections_deposti15.css') }}
            @elseif($total_columns >= 10)
                {{ Html::style('/base/css/collections_deposti10.css') }}
            @elseif($total_columns >= 1)
                {{ Html::style('/base/css/collections_deposti1.css') }}
            @endif;
            
</head>
<body>

<div class="footer">
    Page <span class="pagenum"></span>
</div>

    <span class="hidden">
         {{ $gtotal = 0 }}
    </span>
    <table class="center ">
    <tr>
        <td>
            <img src="{{asset('asset/images/benguet_capitol.png')}}" class="image_logo" />
        </td>
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
            <td class="underline">{{ $report_date }}</td>
        </tr>
        <tr>
            <td>Name of Accountable Officer: <b>{{ $acctble_officer_name->value }} - {{ $acctble_officer_position->value }}</b></td>
            <td class="val">Report No.</td>
            <td class="underline">{{ $_GET['report_no'] }}</td>
        </tr>

    </table>

<h4>A. COLLECTIONS</h4>
<div class="table-responsive col-sm-6">
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
        <!-- VALUES PER RECEIPT -->
<tbody>
                <?php $total_rc = []; ?>
        @foreach ($receipts as $i => $receipt)
            <?php
                    if(!isset($total_rc[$receipt->serial_no])){
                         $total_rc[$receipt->serial_no] = 0;
                    }
            ?>

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
                            {{ number_format($title[$receipt->serial_no], 2) }}
                            <?php  $total_rc[$receipt->serial_no] += $title[$receipt->serial_no]; ?>

                            @endif
                        </td>
                    @endforeach

                    @foreach($account['subtitles'] as $j => $subtitle)
                        <td class=" val text-right">
                            @if (isset($subtitle[$receipt->serial_no]))
                            {{ number_format($subtitle[$receipt->serial_no], 2) }}
                            <?php  $total_rc[$receipt->serial_no] += $subtitle[$receipt->serial_no]; ?>
                            @endif
                        </td>
                    @endforeach
                @endforeach

                @foreach($shares as $i => $share)
                    <td class=" val text-right">
                        @if (isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0)
                        {{ number_format($share[$receipt->serial_no], 2) }}
                        <?php  $total_rc[$receipt->serial_no] += $share[$receipt->serial_no]; ?>
                        @endif
                    </td>
                    @foreach($share['barangays'] as $j => $barangay)
                    <td class=" val text-right">
                        @if (isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0)
                        {{ number_format($barangay[$receipt->serial_no], 2) }}
                        <?php  $total_rc[$receipt->serial_no] += $barangay[$receipt->serial_no]; ?>
                        @endif
                    </td>
                    @endforeach
                @endforeach

                <td class=" border-botts val text-right">
                    <?php 
                        $gtotal += $receipts_total[$receipt->serial_no];
                    ?>
                    {{ number_format($total_rc[$receipt->serial_no], 2) }}
                </td>
            @endif
        </tr>
        @endforeach
        <!-- TOTALS -->
        <tr class="page-break">
            <td class="val" colspan="2">GRAND TOTAL</td>
            @foreach($accounts as $i => $account)
                @foreach($account['titles'] as $j => $title)
                    <td class="val text-right">
                        {{ number_format($title['total'], 2) }}
                    </td>
                @endforeach

                @foreach($account['subtitles'] as $j => $subtitle)
                    <td class="val text-right">
                        {{ number_format($subtitle['total'], 2) }}
                    </td>
                @endforeach
            @endforeach

            @foreach($shares as $i => $share)
                <td class=" val text-right">
                    {{ number_format($share['total_share'], 2) }}
                </td>
                @foreach($share['barangays'] as $j => $barangay)
                <td class=" val text-right">
                    {{ number_format($barangay['total_share'], 2) }}
                </td>
                @endforeach
            @endforeach
            <td class=" val text-right">{{ number_format($gtotal, 2) }}</td>
        </tr>
</tbody>

</table>

<table>
<tbody>
    <tr >
        <td  style="border-left: none; border-right: none; border-bottom: none; width: 550px;">
             <table class="table table-condensed">
                    <tr >
                        <td><b>SUMMARY OF COLLECTION {{ $share_columns }}</b></td>
                        <td >
                            <span class="hidden">
                            {{ $total = 0 }}
                            </span>
                        </td>
                    </tr>
                    @foreach($accounts as $i => $account)
                        @foreach($account['titles'] as $j => $title)
                        <tr >
                            <td>{{ $j }}</td>
                            <td class="val text-right">
                                <span class="hidden">
                                {{ $total += $title['total'] }}
                                </span>
                                {{ number_format($title['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach

                        @foreach($account['subtitles'] as $j => $subtitle)
                        <tr >
                            <td>{{ $j }}</td>
                            <td class="val text-right">
                                <span class="hidden">
                                {{ $total += $subtitle['total'] }}
                                </span>
                                {{ number_format($subtitle['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                    @if ($bac_type_1 > 0 && $_GET['type'] == 1)
                    <tr >
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
                    <tr >
                        <td>BAC INFRA</td>
                        <td class="val text-right">
                            <span class="hidden">
                            {{ $total += $bac_type_2 }}
                            </span>
                            {{ number_format($bac_type_2, 2) }}
                        </td>
                    </tr>
                    @endif

                    @if ($bac_type_3 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC Drugs & Meds</td>
                        <td class="val text-right">
                            <span class="hidden">
                            {{ $total += $bac_type_3 }}
                            </span>
                            {{ number_format($bac_type_3, 2) }}
                        </td>
                    </tr>
                    @endif

                    <tr class="set-border-tb">
                        <td ><b>TOTAL</b></td>
                        <td class="val text-right">
                            <b>{{ number_format($total, 2) }}</b>
                        </td>
                    </tr>

                </table>

        </td>

        <td  style="border-left: none; border-right: none; border-bottom: none;  width: 550px;">
        <table class="table table-no-border table-condensed">
        <tr>
        <td class="table-border-right">
      
        </td>
        <td  >
            @if ($_GET['type'] == 1)
            <table class="table">
                <tr>
                    <td><b>Municipal/Barangay Share</b></td>
                    <td>
                        <span class="hidden">
                        {{ $total = 0 }}
                        </span>
                    </td>
                </tr>
                @foreach ($shares as $i => $share)
                    <tr >
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
                        <tr >
                            <td><div class="brgy">{{ $barangay['name'] }}</div></td>
                            <td class="val text-right">
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
                    <td class="val text-right">
                        <b>{{ number_format($total, 2) }}</b>
                    </td>
                </tr>
            </table>
            @endif


            @if ($_GET['type'] == 1)
            <table class="table">
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
                        <td class="val text-right">
                            <span class="hidden">
                            {{ $total += $share['total_share'] }}
                            </span>
                            {{ number_format($share['total_share'], 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="set-border-tb">
                    <td class=""><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b>{{ number_format($total, 2) }}</b>
                    </td>
                </tr>
            </table>
            @endif
    </td>
    </tr>
    </table>

    </td>
</tr>
</tbody>
</table>


         




    


 <?php 
	$summary_total = 0;
	$total_with_ada = 0;
	$has_ada = 0;
	$ada = 0;
		foreach ($trantypes as $i => $type){
					if ($i == 4){
						if ($type['total'] > 0){
						  $ada = $type['total'];
						  $has_ada = 1;
						}
						$total_with_ada += $type['total'];
					}else{
                        $total_with_ada += $type['total'];
                        $summary_total += $type['total'];
                    }
						
					
        }
		
?>

<h4>B. REMITTANCES/DEPOSITS</h4>
    <table class="table table-condensed">
        <tr>
            <th class="">ACCOUNTABLE OFFICER/BANK</th>
            <th class="">REFERENCE</th>
            <th class="">TOTAL</th>
        </tr>
        <tr >
            <td class="">
                @if ($_GET['type'] == 5)
                    {{ $trust_fund_officer_name->value }}
                @elseif($_GET['type'] == 2)
                    {{ $bts_officer_name->value }}
                @elseif($_GET['type'] == 3)
                    {{ $bese_report_officer->value }}
                @else
                    {{ $officer_name->value }}
                @endif
            </td>
            <td class="">{{ $_GET['report_no'] }}</td>
            <td class=" val">PHP {{ number_format($total_with_ada, 2) }}</td>
        </tr>
    </table>

    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->.
    <h4>C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
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


    <!-- SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS -->

    <table class="table  table-no-border">
    <tr>
        <td colspan="2">  <h4>D. SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS</h4></td>
    </tr>
        <tr>
        <td class="table-border-right">
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
                        {{ number_format($type['total'], 2) }}
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td class=""><b>Total</b></td>
                    <td class=" val text-right"><b>{{ number_format($total_with_ada, 2) }}</b></td>
                </tr>
			@if ($has_ada)
                <tr>
                    <td class=""><b>Less ADA</b></td>
                    <td class=" val text-right"><b>{{ number_format($ada, 2) }}</b></td>
                </tr>
            @endif
            <tr>
                <td class=""><b>Remittance/Deposit to Cashier/Treasurer</b></td>
                <td class=" val text-right"><b>{{ number_format($summary_total, 2) }}</b></td>
            </tr>
			<tr>
                <td class=""><b>Balance</b></td>
                <td class=" val text-right"><b></b></td>
            </tr>
        </table>
        </td>

        <td >
            <span class="hidden">
            {{ $bank_total = 0 }}
            </span>
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
                            @php
                                $compiled_rec = array();
                            
                            foreach($bank as $b){
                                if(!isset($compiled_rec[$b['check_no']])){
                                    $compiled_rec[$b['check_no']]['amt'] = 0;
                                 }
                                $compiled_rec[$b['check_no']]['amt'] += $b['amt'];
                                $compiled_rec[$b['check_no']]['bank'] = $b['bank'];
                            }
                            @endphp
                            
                             @foreach($compiled_rec as $key => $b)
                                <tr>
                                    <td class="">{{ $b['bank'] }}</td>
                                    <td class="">{{ $key }}</td>
                                    <td class="">Provincial Government of Benguet</td>
                                    <td class=" val">
                                        <span class="hidden">
                                        {{ $bank_total += $b['amt'] }}
                                        </span>
                                        {{ number_format($b['amt'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="" colspan="3">Total</td>
                                    <td class="  val">{{ number_Format($bank_total, 2) }}</td>
                                </tr>
                    </tbody>
                </table>
        </td>
    </tr>
    </table>
    <br>

    <!-- CERTIFICATION/VERIFICATION AND ACKNOWLEDGEMENT -->
    <table  class="table table-borderedx">
        <tr>
        <td class="table-border-right">
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts" >CERTIFICATION</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            and accountability for Accountable Forms is true and correct.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts">{{ $acctble_officer_name->value }}</th>
                            <th></th>
                            <th class="border-botts">{{ date('F d, Y') }}</th>
                        </tr>
                        <tr>
                            <th colspan="2">{{ $acctble_officer_position->value }}</th>
                            <th>Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        <td>
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts">VERIFICATION AND ACKNOWLEDGEMENT</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            has been verified and acknowledge receipt of
                            <b><u>{{ $total_in_words }}</u> (PHP {{ number_format($summary_total, 2) }})</b>.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts">
                                @if ($_GET['type'] == 5)
                                    {{ $trust_fund_officer_name->value }}
                                @elseif($_GET['type'] == 2)
                                    {{ $bts_report_officer->value }}
                                @elseif($_GET['type'] == 3)
                                    {{ $bese_report_officer->value }}
                                @else
                                    {{ $officer_name->value }}
                                @endif
                            </th>
                            <th></th>
                            <th class="border-botts">{{ date('F d, Y') }}</th>
                        </tr>
                        <tr>
                            <th>
                                @if ($_GET['type'] == 2) 
                                    {{ $bts_report_officer_position->value }}
                                @elseif($_GET['type'] == 3)
                                    {{ $bese_report_officer_position->value }}
                                @elseif($_GET['type'] == 5)
                                    {{ $trustfund_officer_position->value }}
                                @else
                                    {{ $officer_position->value }}
                                @endif

                            </th>
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
