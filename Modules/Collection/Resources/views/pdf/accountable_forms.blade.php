<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style>
        
        html {
            margin-bottom: 8px;
            margin-top: 8px;
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

        .font-small{
            font-size: 12px;
        }

        .form_type{
            font-size: 12px !important;
            font-weight: bold;
        }

        table{
            font-size: 10px !important;
        }

    </style>
</head>
<body>
 <?php 
     $beg_total56 = 0 ;
     $rec_total56 = 0 ;
     $iss_total56 = 0 ;
     $end_total56 = 0 ;
     $beg_total51 = 0 ;
     $rec_total51 = 0 ;
     $iss_total51 = 0 ;
     $end_total51 = 0 ;
?>
<div style="text-align: center;">
    <h5><b>REPORT OF ACCOUNTABILITY FOR ACCOUNTABLE FORMS</b></h5>
    <small>PROVINCE OF BENGUET</small>
    <br>
    <h5><b>Month of {{ \Carbon\Carbon::parse($date)->format('F, Y') }}</b></h5>
    <br>
    <p style="display: inline-block; padding-right: 30px;">Accountable Officer: <u><b>{{ $accountable_officer['value'] }}</b></u></p>
    <p style="display: inline-block; padding-right: 30px;">Designation: <u><b>{{ $acctble_officer_position['value'] }}</b></u></p>
    <p style="display: inline-block; padding-right: 30px;">Report No.: <u><b></b></u></p>
</div>
<table class="table page-break table-condensed table-bordered">
    <thead>
        <!-- <tr>
            <th class="" rowspan="1"  colspan="13">ACCOUNTABILITY FOR ACCOUNTABLE FORMS</th>
        </tr> -->
        <tr>
            <th  rowspan="3">Name of Forms & No.</th>
            <th  colspan="3">Beginning Balance</th>
            <th  colspan="3">Receipt</th>
            <th  colspan="3">Issued</th>
            <th  colspan="3">Ending Balance</th>
            <th></th>
        </tr>
        <tr>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
            <th></th>
        </tr>
        <tr>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
            <th></th>
        </tr>

       
        </thead>
        <tbody>
            <!-- <tr><td colspan="13" class="form_type"> AF No. 51 </td></tr> -->
        <?php $i = 0; ?>
        @foreach ($category as $catkey => $catvalue)
            <?php $j = 0; ?>
            @if($catvalue->id != 2)
                <!-- BTS combined w/ Gen. Fund -->
                @foreach ($rcpt_acct51[$catvalue->name] as $rcpt)
                <?php 
                    // if($rcpt['end_qty'] <= 0)
                    //     continue;

                ?>
                <tr class="page-break">
                    <td class="form_type">
                        @if ($i == 0)
                            AF No. 51
                        @endif
                    </td>
                    <td class="text-center val">{{ $rcpt['beg_qty'] }}</td>
                    <td class="text-center val">{{ $rcpt['beg_from'] }}</td>
                    <td class="text-center val">{{ $rcpt['beg_to'] }}</td>
                    <td class="text-center val">{{ $rcpt['rec_qty'] }}</td>
                    <td class="text-center val">{{ $rcpt['rec_from'] }}</td>
                    <td class="text-center val">{{ $rcpt['rec_to'] }}</td>
                    <td class="text-center val">{{ $rcpt['iss_qty'] }}</td>
                    <td class="text-center val">{{ $rcpt['iss_from'] }}</td>
                    <td class="text-center val">{{ $rcpt['iss_to'] }}</td>
                    <td class="text-center val">{{ $rcpt['end_qty'] > 0 ? $rcpt['end_qty'] : "-" }}</td>
                    <td class="text-center val">{{ $rcpt['end_qty'] > 0 ? $rcpt['end_from'] : "-" }}</td>
                    <td class="text-center val">{{ $rcpt['end_qty'] > 0 ? $rcpt['end_to'] : "-" }}</td>
                    <td class="text-center val" style="background: ##c24554">
                        <?php 
                            $beg_total51 += $rcpt['beg_qty']?$rcpt['beg_qty']:0 ;
                            $rec_total51 += $rcpt['rec_qty']?$rcpt['rec_qty']:0 ;
                            $iss_total51 += $rcpt['iss_qty']?$rcpt['iss_qty']:0 ;
                            $end_total51 += $rcpt['end_qty']?$rcpt['end_qty']:0 ;
                        ?>
                        @if ($j == 0)
                            <b>{{ $catvalue->name }}</b>
                        @endif
                    </td>
                </tr>
                <?php $i++; $j++; ?>
                @endforeach
            @endif
        @endforeach
        <tr class="page-break">
            <td class="text-center val">Balance, AF NO. 51</td>
            <td class="text-center val"><b>{{ $beg_total51 }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
            <td class="text-center val"><b>{{ $rec_total51 }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
            <td class="text-center val"><b>{{ $iss_total51 }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
            <td class="text-center val"><b>{{ $end_total51 }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
            <td></td>
        </tr>

            <!-- <tr><td colspan="13" class="form_type"> AF No. 56 </td></tr> -->
            <?php 
                $i = 0; 
                $j = ''; 
            ?>
            @foreach ($rcpt_acct56 as $rcpt)
            <tr>
                <td class="form_type">
                    @if ($i == 0)
                        AF No. 56
                    @endif
                </td>
                <td class="text-center val" >{{ $rcpt['beg_qty'] }}</td>
                <td class="text-center val" >{{ $rcpt['beg_from'] }}</td>
                <td class="text-center val" >{{ $rcpt['beg_to'] }}</td>
                <td class="text-center val" >{{ $rcpt['rec_qty'] }}</td>
                <td class="text-center val" >{{ $rcpt['rec_from'] }}</td>
                <td class="text-center val" >{{ $rcpt['rec_to'] }}</td>
                <td class="text-center val" >{{ $rcpt['iss_qty'] }}</td>
                <td class="text-center val" >{{ $rcpt['iss_from'] }}</td>
                <td class="text-center val" >{{ $rcpt['iss_to'] }}</td>
                <td class="text-center val" >{{ $rcpt['end_qty'] }}</td>
                <td class="text-center val" >{{ $rcpt['end_from'] }}</td>
                <td class="text-center val" >{{ $rcpt['end_to'] }}</td>
                <td class="text-center val">
                    <?php 
                     $beg_total56 += $rcpt['beg_qty']?$rcpt['beg_qty']:0 ;
                     $rec_total56 += $rcpt['rec_qty']?$rcpt['rec_qty']:0 ;
                     $iss_total56 += $rcpt['iss_qty']?$rcpt['iss_qty']:0 ;
                     $end_total56 += $rcpt['end_qty']?$rcpt['end_qty']:0 ;
                    ?>
                    @if (strcasecmp($j, $rcpt['src']) != 0)
                        <b>{{ $rcpt['src'] }}</b>
                    @endif
                </td>
            </tr>
            <?php $i++; $j = $rcpt['src']; ?>
            @endforeach
            <tr>
                <td class="text-center val" >Balance, AF NO. 56</td>
                <td class="text-center val" ><b>{{ $beg_total56 }}</b></td>
                <td class="text-center val" ></td>
                <td class="text-center val" ></td>
                <td class="text-center val" ><b>{{ $rec_total56 }}</b></td>
                <td class="text-center val" ></td>
                <td class="text-center val" ></td>
                <td class="text-center val" ><b>{{ $iss_total56 }}</b></td>
                <td class="text-center val" ></td>
                <td class="text-center val" ></td>
                <td class="text-center val" ><b>{{ $end_total56 }}</b></td>
                <td class="text-center val" ></td>
                <td class="text-center val" ></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center;">
        <p><b>CERTIFICATION: </b></p>
        <p style="text-indent: 50px;">I hereby certify that the foregoing is a true statement of all accountable forms received,
         issued and transferred by me during the period above stated and the correctness of the beginning balances.</p>
        <br><br>
        <div>
    </div>
    <div style="text-align: center;">
        <center>
        <div style="display: inline-block; padding-right: 100px; vertical-align: top;">
            <u><b>{{ $accountable_officer['value'] }}</b></u>
            <br>
            <small>Name & Signature of the Accountable Officer</small>
        </div> 
        <div style="display: inline-block; vertical-align: top;"> 
            <u><b>{{ \Carbon\Carbon::now()->format('F j, Y') }}</b></u>
        </div>
        </center>
    </div>
</body>
</html>