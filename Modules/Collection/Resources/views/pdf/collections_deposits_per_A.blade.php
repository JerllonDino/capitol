<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits A</title>
    {{ Html::style('/base/css/pdf.css') }}
    {{-- {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }} --}}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 1.5cm;
            margin-left: 15px;
            margin-right: 15px;
        }
        body {
            font-family: 'Helvetica'
        }
        /* class works for table row */
        table tr.page-break{
          /*page-break-after:always*/
        }


        /* class works for table */
        table.page-break{
          /*page-break-after:always*/
        }

        #collections>thead>tr>th,#collections>tbody>tr>td, #collections>tbody>tr>th{
            font-size: 9px;
            padding: 1px;
        }

        .small-launay>thead>tr>th,.small-launay>tbody>tr>th,.small-launay>tbody>tr>td{
            font-size: 9px;
            padding: 1px;
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
            font-size: 9px;
            text-align: center;
        }
         .firstpage { 
            position: absolute;
          top: -1.6cm; 
          width: 100%;
          height: 100px;
          margin: 0;
        }


        .headerxxxx{
            /*width: 70%;*/
            margin: 0 auto;
            margin-top: 2.2cm;
        }

        h4{
            padding: 1px;
            margin: 1px;
        }

        .detail_payor {
            min-width: 50px !important;
            max-width: 150px !important;
            word-wrap: break-word;
        }

        @if ($total_columns >= 18 and $papr_size === 'legal')

        #collections>thead>tr>th,#collections>tbody>tr>td, #collections>tbody>tr>th{
            font-size: 9px !important;
            padding: 1px;
        }

        .small-launay>thead>tr>th,.small-launay>tbody>tr>th,.small-launay>tbody>tr>td{
            font-size: 9px !important;
            padding: 1px;
        }
        @endif;
            
    </style>



</head>
<body>

<div class="footer">
    Page <span class="pagenum"></span>
</div>
    
        <?php 
          $gtotal = 0;
        ?>
<div class="firstpage">
    <table class="center ">
    <tr>
        <td>
            <img src="{{asset('asset/images/benguet_capitol.png')}}" class="image_logo" />
        </td>
        <td style="font-size: 12px;">
        REPORT OF COLLECTIONS AND DEPOSITS <br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>
</div>

<div class="otherpage">
    <table class="table table-condensed headerxxxx" >
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

<h4>A. COLLECTIONS</h4>
<div class="table-responsive col-sm-6">
    <table id="collections" class="table table-bordered table-condensed table-responsive page-break small-launay">
    <thead style="text-align: center;">
        <tr class="page-break">
            <th class="" rowspan="2">OR Nos.</th>
            <th class=" detail_payor" rowspan="2">Payor</th>
            @foreach($accounts as $i => $account)
                <?php
                    if($i == 'Benguet Technical School (BTS)')
                        continue;
                ?>
                @if($account['count'] > 0)
                    @if($i == 'General Fund-Proper')
                        <?php
                            $add = count($accounts['General Fund-Proper']['titles']) + count($accounts['Benguet Technical School (BTS)']['titles']) + count($accounts['General Fund-Proper']['subtitles']) + count($accounts['Benguet Technical School (BTS)']['subtitles']);
                        ?>
                        <th class="" colspan="{{ $add }}">{{ $i }}</th>
                    @else
                        <th class="" colspan="{{ count($account['titles']) + count($account['subtitles']) }}">{{ $i }}</th>
                    @endif
                @endif
            @endforeach

            @if (count($shares) > 0)
            <th class="" colspan="{{ $share_columns }}">MUNICIPAL & BRGY SHARES</th>
            @endif

            <th class="" rowspan="2">TOTAL AMOUNT</th>
        </tr>
  
        <tr class="page-break">
            <?php 
                $count_accts = 0; 
                $count_titles = 0;
                foreach($accounts as $acc) {
                    $count_titles += count($acc['titles']);
                }
            ?>
            @foreach($accounts as $i => $account)
                @if($account['count'] > 0)
                    <?php $count_accts += $account['count']; ?>
                    @foreach($account['titles'] as $j => $title)
                        <?php 
                            $acronym = $j ;
                        ?>
                        @if($count_accts > 15 && strlen($acronym) > 20)
                            @if($i == 'Benguet Technical School (BTS)')
                                <th style="font-size: 9px;">{{ $acronym }} (BTS)</th>
                            @else
                                <th style="font-size: 9px;">{{ $acronym }}</th>
                            @endif
                        @else
                            @if($i == 'Benguet Technical School (BTS)')
                                <th>{{ $acronym }} (BTS)</th>
                            @else
                                <th>{{ $acronym }}</th>
                            @endif
                        @endif
                    @endforeach
                    @foreach($account['subtitles'] as $j => $subtitle)
                        <?php 
                            $acronym = $j ;
                        ?>
                        @if($count_accts > 15 && strlen($acronym) > 20)
                            @if($i == 'Benguet Technical School (BTS)')
                                <th style="font-size: 9px;">{{ $acronym }} (BTS)</th>
                            @else
                                <th style="font-size: 9px;">{{ $acronym }}</th>
                            @endif
                        @else
                            @if($i == 'Benguet Technical School (BTS)')
                                <th>{{ $acronym }} (BTS)</th>
                            @else
                                <th>{{ $acronym }}</th>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach

            @foreach($shares as $i => $share)
                <th>{{ isset($share['name']) ? $share['name'] : '' }}</th> -->
                @foreach($share['barangays'] as $j => $barangay)
                    <th>{{ isset($barangay['name']) ? $barangay['name'] : '' }}</th>
                @endforeach
<!-- adjust share -->
<!-- adjust share END -->
            @endforeach
        </tr>

        <tr class="border-botts page-break-before: always;">
            <th class="border-botts" colspan="{{ $total_columns + 1 }}">{{ $date_range }}</th>
        </tr>
</thead>
        <!-- VALUES PER RECEIPT -->
<tbody>
        <?php $total_rc = []; $account_totals = []; ?>
        @foreach ($receipts as $i => $receipt)
            <?php
                if(!isset($total_rc[$receipt->serial_no])){
                     $total_rc[$receipt->serial_no] = 0;
                }
            ?>

        <tr class="page-break" style="min-height: 50px !important;">
            <td style="vertical-align:middle" class="height val">{{ $receipt->serial_no }}</td>

            @if (!isset($receipts_total[$receipt->serial_no]) || $receipt->is_cancelled == 1)
                <td style="vertical-align:middle; font-size: 12px;" class=" cancelled_remark" colspan="{{ $total_columns }}">
                    Cancelled - {{ $receipt->cancelled_remark }}
                </td>
            @else

                <td style="vertical-align: middle; font-size: 12px;" class="detail_payor val">
                    
                    {{ $receipt->customer->name }}
                </td>
                @foreach($accounts as $i => $account)
                    <!-- per client type -->
                    @foreach($account['titles'] as $ji => $title)
                        <?php
                            if(!isset($account_totals[$ji])) {
                                $account_totals[$ji] = 0;
                            }
                        ?>
                        <td style="vertical-align: middle; font-size: 12px;" class=" val text-right">
                            @if(isset($title[$receipt->serial_no]))
                                @if(strlen(number_format($title[$receipt->serial_no], 2)) >= 8)
                                    <p style="font-size: 10px;">{{ number_format($title[$receipt->serial_no], 2) }}</p>
                                @else
                                    <p style="font-size: 12px;">{{ number_format($title[$receipt->serial_no], 2) }}</p>
                                @endif
                                <?php 
                                    $total_rc[$receipt->serial_no] += $title[$receipt->serial_no];
                                    $account_totals[$ji] =+ $title[$receipt->serial_no];
                                ?>
                            @endif
                        </td>
                    @endforeach

                    @foreach($account['subtitles'] as $j => $subtitle)
                        <td style="vertical-align: middle; font-size: 12px;" class="val text-right">
                            @if (isset($subtitle[$receipt->serial_no]))
                                @if(strlen(number_format($subtitle[$receipt->serial_no], 2)) >= 8)
                                    <p style="font-size: 10px;">{{ number_format($subtitle[$receipt->serial_no], 2) }}</p>
                                @else
                                    <p style="font-size: 12px;">{{ number_format($subtitle[$receipt->serial_no], 2) }}</p>
                                @endif
                                <?php  $total_rc[$receipt->serial_no] += $subtitle[$receipt->serial_no]; ?>
                            @endif
                        </td>
                    @endforeach
                @endforeach

                @foreach($shares as $i => $share)
                    <td style="vertical-align:middle; font-size: 12px;" class="val text-right">
                        @if (isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0)
                            @if(strlen(number_format($share[$receipt->serial_no], 2)) >= 8)
                                <p style="font-size: 10px;">{{ number_format($share[$receipt->serial_no], 2) }}</p>
                            @else
                                <p style="font-size: 12px;">{{ number_format($share[$receipt->serial_no], 2) }}</p>
                            @endif
                            <?php  $total_rc[$receipt->serial_no] += $share[$receipt->serial_no]; ?>
                        @endif
                    </td>

                    @foreach($share['barangays'] as $j => $barangay)
                        <td style="vertical-align: middle; font-size: 12px;" class="val text-right">
                            @if (isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0)
                                @if(strlen(number_format($barangay[$receipt->serial_no], 2)) >= 8)
                                    <p style="font-size: 10px;">{{ number_format($barangay[$receipt->serial_no], 2) }}</p>
                                @else
                                    <p style="font-size: 12px;">{{ number_format($barangay[$receipt->serial_no], 2) }}</p>
                                @endif
                                <?php  $total_rc[$receipt->serial_no] += $barangay[$receipt->serial_no]; ?>
                            @endif
                        </td>
                    @endforeach
                @endforeach

                <td style="vertical-align: middle; font-size: 12px;" class="border-botts val text-right">
                    <?php 
                        $gtotal += $receipts_total[$receipt->serial_no];
                    ?>
                    {{ number_format($total_rc[$receipt->serial_no], 2) }}
                </td>
            @endif
        </tr>
        @endforeach
        <!-- TOTALS -->
        <tr class="page-break" style="min-height: 50px !important;">
            <td class="val" colspan="2">GRAND TOTAL</td>
            @foreach($accounts as $i => $account)
                @foreach($account['titles'] as $j => $title)
                    <td class="val text-right" style="font-size: 10px;">
                        @if(isset($title['total']))
                            {{ number_format($title['total'], 2) }}
                        @elseif(isset($account_totals[$j]))
                            {{ number_format($account_totals[$j], 2) }}
                        @endif
                    </td>
                @endforeach

                @foreach($account['subtitles'] as $j => $subtitle)
                    <td class="val text-right" style="font-size: 10px;">
                        @if(isset($subtitle['total']))
                            {{ number_format($subtitle['total'], 2) }}
                        @endif
                    </td>
                @endforeach
            @endforeach

            @foreach($shares as $i => $share)
                <td class="val text-right" style="font-size: 10px;">
                    {{ isset($share['total_share']) ? number_format($share['total_share'], 2) : '' }}
                </td>
                @foreach($share['barangays'] as $j => $barangay)
                <td class="val text-right" style="font-size: 10px;">
                    {{ isset($barangay['total_share']) ? number_format($barangay['total_share'], 2) : '' }}
                </td>
                @endforeach
            @endforeach
            <td class="val text-right">{{ number_format($gtotal, 2) }}</td>
        </tr>
</tbody>
</table>
</div>
</body>
</html>
