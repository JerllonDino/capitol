<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 40px;
            margin-top: 60px;
            margin-left: 15px;
            margin-right: 15px;
        }
        /* class works for table row */
        table tr.page-break{
          page-break-after:always
        }


        /* class works for table */
        table.page-break{
          page-break-after:always
        }

        @media print {
         .page-break  { display: block; page-break-before: always; }
        }
        .center {
                width: 450px;
                text-align: center;
                margin: 90px auto;
               
        }

        .table{
            margin-bottom: 0px;
          
        }



       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            border-top: 1px solid #868282;
            border-bottom: 1px solid #868282;
            padding: 0;
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
            height:25px
        }

        .footer {
            bottom:15px;
            position: fixed;
            width: 100%;
            color:#717477;
            font-size: 9px;
            text-align: center;
        }

        div.container4 {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        padding-right: 50px;
        font-size: 13px;
       
        transform: translate(-50%, -50%) }

        .pagenum:before {
            /*content: counter(page);*/
        }


        /*.single-space > * {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }*/
    </style>

          
</head>
<body>

<div class=container4>
    
<?php  
    $gtotal = 0; 
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

    $account_totals = [];
    foreach ($receipts as $i => $receipt) {
        foreach($accounts as $i => $account) {
            foreach($account['titles'] as $ji => $title) {
                if(isset($title[$receipt->serial_no])) {
                    if(!isset($account_totals[$ji])) {
                        $account_totals[$ji] = 0;
                    }
                    $account_totals[$ji] =+ $title[$receipt->serial_no];
                }
            }
        }
    }
        
?>

<table>
<tbody>
    <tr >
        <td  style="border-left: none; border-right: none; border-bottom: none; width: 550px;">
             <table class="table small-launay table-no-border">
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
                                @if(isset($title['total']))
                                    <?php
                                        $total += $title['total'];
                                    ?>
                                    {{ number_format($title['total'], 2) }}
                                @elseif(isset($account_totals[$j]))
                                    <?php
                                        $total += $account_totals[$j];
                                    ?>
                                    {{ number_format($account_totals[$j], 2) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        @foreach($account['subtitles'] as $j => $subtitle)
                        <tr >
                            <td>{{ $j }}</td>
                            <td class="val text-right">
                                @if(isset($subtitle['total']))
                                     <?php
                                        $total += $subtitle['total'];
                                    ?>
                                   
                                    {{ number_format($subtitle['total'], 2) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                    @if ($bac_type_1 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC Goods & Services</td>
                        <td class="val">
                             <?php
                                   $total += $bac_type_1;
                                ?>
                            
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

        <td  style="border-left: none; border-right: none; border-bottom: none;  width: 550px; vertical-align: top;">
        <table class="table table-no-border table-condensed small-launay">
        <tr>
        <td class="table-border-right">
      
        </td>
        <td style="padding: 0;"  >
            @if ($_GET['type'] == 1)
            <table class="table">
                <tr>
                    <td><b>Municipal/Barangay Share</b></td>
                    <td>
                         <?php
                            $total = 0;
                        
                        ?>
                    </td>
                </tr>
                @foreach ($shares as $i => $share)
                    <tr >
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val">
                             <?php
                                    if (isset($amusement_shares[$i])){$share_value = $share['total_share'] - $amusement_shares[$i]['total_share'];}
                                    else{$share_value = $share['total_share'];}
                                $total += $share_value;
                              ?>
                            {{ number_format($share_value, 2) }}
                        </td>
                    </tr>
                    @foreach ($share['barangays'] as $j => $barangay)
                        <tr >
                            <td><div class="brgy">{{ $barangay['name'] }}</div></td>
                            <td class="val text-right">
                                <?php $total += $barangay['total_share']; ?>
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
                       <?php 
                            $total = 0;
                        ?>
                    </td>
                </tr>
                @foreach ($amusement_shares as $i => $share)
                    <tr>
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val text-right">
                            <?php
                                $total += $share['total_share'];
                            ?>
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

<!-- <br> -->
<h4>B. REMITTANCES/DEPOSITS</h4>
    <table class="table table-condensed">
        <tr>
            <th class="" width="200">ACCOUNTABLE OFFICER/BANK</th>
            <th class="" width="500" style="text-align:center">REFERENCE</th>
            <th class="" width="100" >TOTAL</th>
        </tr>
        <tr >
            <td class="">
                @if ($_GET['type'] == 5)
                    @if(isset($trust_fund_officer_name->value))
                        {{ $trust_fund_officer_name->value }}
                    @else 
                        {{ $trust_fund_officer_name }}
                    @endif
                @elseif($_GET['type'] == 2)
                    @if(isset($bts_officer_name->value))
                        {{ $bts_officer_name->value }}
                    @else 
                        {{ $bts_officer_name }}
                    @endif
                @elseif($_GET['type'] == 3)
                    @if(isset($bese_report_officer->value))
                        {{ $bese_report_officer->value }}
                    @else 
                        {{ $bese_report_officer }}
                    @endif
                @else
                    @if(isset($officer_name->value))
                        {{ $officer_name->value }}
                    @else 
                        {{ $officer_name }}
                    @endif
                @endif
            </td>
            <td class="" style="text-align:center">{{ $_GET['report_no'] }}</td>
            <td class="">PHP {{ number_format($total_with_ada, 2) }}</td>
        </tr>
    </table>

</div>
</body>

</html>
