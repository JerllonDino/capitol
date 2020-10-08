<!DOCTYPE html>
<html>
<head>
    <title>SAND and GRAVEL Delivery Receipt</title>
    <style>
        @page { margin: 0px 10px; }
        body {
            margin: 0px 10px; 
            font-family: arial, "sans-serif";
            font-size: 8.5;
        }

        
         .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

           .image_logo{
                width: 80px;
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
        thead{
            font-weight: bold;
            text-align: center;
        }

        .table,.table>thead>tr>th,.table>tbody>tr>td{
            border:1px solid #ccc;
        }
        .text-center{
            text-align: center;
        }

        .text-right{
            text-align: right;
        }

        
        #sand_gravel_share{
            border: 2px solid #000;
        }

        #sand_gravel_share > thead > tr > th,#sand_gravel_share > tbody > tr > td,#sand_gravel_share > tfoot > tr > th{
            border-right: 2px solid #000;
            border-left: 2px solid #000;
        }

        #sand_gravel_share > thead > tr > th{
            border: 2px solid #000;
        }

        #sand_gravel_share > tfoot > tr > th{
            border: 2px solid #000;
            border-bottom: 3px solid #000;
        }

        #sand_gravel_share>thead>tr>th,#sand_gravel_share>tbody>tr>td{
            font-size: 12px;
            padding: 1px;
        }

        .bold-text{
            font-weight: bold;
        }

        .table, .table>thead>tr>th, .table>tfoot>tr>th, .table>tbody>tr>td{
            border: 2px solid #000;
        }
       
    </style>
</head>
<body>
    <table  style=" width: 100%; margin-left: 135px;">
        <tr>
        <td style="padding: 0; margin:0;  width: 15% ">
            <img style="padding: 0; margin:0;" src="{{asset('asset/images/benguet-logo.png')}}" class="image_logo" />
        </td>
        <td >
        REPUBLIC OF THE PHILIPPINES<br />
        <strong>BENGUET PROVINCE</strong><br />
        <strong>La Trinidad</strong><br/>
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>
<br /><br />
    <center><strong>DELIVERY RECEIPTS ISSUED FOR THE PERIOD {{ strtoupper($datex->format('F')) }}, {{$year}}</strong></center>
<br />
<table class="table table-condensed table-bordered">
    <thead>
        <tr>
        <th rowspan="2">MUNICIPALITY</th>
        <th rowspan="2">PERMITTEES</th>
        <th rowspan="1" colspan="2" >DELIVERY RECEIPTS</th>
        <th rowspan="1" colspan="2" >SAND AND GRAVEL TAX</th>
        <th rowspan="2">TOTAL</th>
        </tr>

        <tr>
            
            <th>COMMERCIAL</th>
            <th>INDUSTRIAL</th>

            <th>COMMERCIAL</th>
            <th>INDUSTRIAL</th>
        </tr>

        
    </thead>
    <tbody>

        <?php
            $totals = [];
            $count = 0;
            $m = '';
            $ds5 = 0;
            $ds6 = 0;
            $sg5 = 0;
            $sg6 = 0;
        ?>
        
        @foreach( $delivery_reciept as $key => $dd)
            @foreach( $dd as $dkey => $d) 
                <?php $totals[$count] = 0; ?>   

                    <tr>
                            @if($m != $key)
                                <td class="text-left"> {{ strtoupper($key) }} </td>
                                <?php $m = $key; ?>
                            @else
                                <td></td>
                            @endif

                            <td class="text-left">{{ strtoupper($d['customer_name']) }}</td>


                            @if(isset($d['count_ds6']))
                                <td class="text-center">{{ $d['count_ds6'] }}</td>
                                <?php $ds6 += $d['count_ds6']; ?>
                            @else
                                <td></td>
                            @endif

                            @if(isset($d['count_ds5']))
                                <td class="text-center">{{ $d['count_ds5'] }}</td>
                                <?php $ds5 += $d['count_ds5']; ?>
                            @else
                                <td></td>
                            @endif

                            @if(isset($d['value6']))
                                <td class="text-right">{{ number_format($d['value6'],2) }}</td>
                                <?php $totals[$count] += $d['value6']; $sg6 += $d['value6']; ?>
                            @else
                                <td></td>
                            @endif

                            @if(isset($d['value5']))
                                <td class="text-right">{{ number_format($d['value5'],2) }}</td>
                                <?php $totals[$count] += $d['value5'];  $sg5 += $d['value5']; ?>
                            @else
                                <td></td>
                            @endif

                                <td class="text-right">{{ number_format($totals[$count],2) }}</td>

                    </tr>
                    <?php 
                        $count++;
                    ?>
             @endforeach
        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <th class="text-left">SUB-TOTAL</th>
            <th></th>
            <th class="text-center"><?=$ds6?></th>
            <th class="text-center"><?=$ds5?></th>
            <th class="text-right"><?=number_format($sg6,2)?></th>
            <th class="text-right"><?=number_format($sg5,2)?></th>
            <th class="text-right"><?=number_format(array_sum($totals),2)?></th>
        </tr>

        <tr>
            <th class="text-left">COST OF Delivery Receipts</th>
            <th></th>
            <th class="text-center"></th>
            <th class="text-center"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
        </tr>

        <tr>
            <th class="text-left">TOTAL</th>
            <th></th>
            <th class="text-center"></th>
            <th class="text-center"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
        </tr>

       
    </tfoot>    
</table>
<br />
    <table>
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2"><strong>{{ strtoupper($acctble_officer_name->value) }}</strong></td>
            
        </tr>

        <tr>
            <td></td>
            <td colspan="2"><strong>{{ strtoupper($acctble_officer_position->value) }}</strong></td>
            
        </tr>

        
    </table>


</body>
</html>

