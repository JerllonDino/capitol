<!DOCTYPE html>
<html>
<head>
    <title>SAND and GRAVEL</title>
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
       
    </style>
</head>
<body>
    <table class="center">
        <tr>
        <td>
            <img src="{{asset('asset/images/benguet-logo.png')}}" class="image_logo" />
        </td>
        <td>
        REPUBLIC OF THE PHILIPPINES<br />
        <strong>BENGUET PROVINCE</strong><br />
        <strong>La Trinidad</strong><br/>
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

    <center><strong>SAND AND GRAVEL TAX/ PENALTIES COLLECTED <br/> <u>FOR THE PERIOD {{ $datex->format('F') }}, {{$year}}</u></strong></center>

<table class="table table-condensed table-bordered">
    <thead>
        <tr>
        <th>MUNICIPALITY</th>
        <th>PROVINCIAL SHARE</th>
        <th>MUNICIPAL SHARE</th>
        <th>BARANGAY SHARE</th>
        <th>TOTALS</th>
        </tr>
    </thead>
    <tbody>

        @php($provtotal = $muntotal = $brgytotal = $grandtotal = 0)

        <?php

        ?>
        @foreach($municipality as $mun)
        <tr>
            <?php 
                $row = 0;
                $row += $taxcollected[$mun['name']]->provincial_value; $provtotal += $taxcollected[$mun['name']]->provincial_value;
                $row += $taxcollected[$mun['name']]->municipal_value; $muntotal += $taxcollected[$mun['name']]->municipal_value;
                $row += $taxcollected[$mun['name']]->barangay_value; $brgytotal += $taxcollected[$mun['name']]->barangay_value;
            ?>
            <td > {{ strtoupper($mun['name']) }} </td>
            <td class="text-right"> {{ !empty($taxcollected[$mun['name']]->provincial_value) ? number_format($taxcollected[$mun['name']]->provincial_value,2) : " " }} </td> 
            <td class="text-right"> {{  !empty($taxcollected[$mun['name']]->municipal_value) ? number_format($taxcollected[$mun['name']]->municipal_value,2) :  " "}}</td> 
            <td class="text-right"> {{ !empty($taxcollected[$mun['name']]->barangay_value) ? number_format($taxcollected[$mun['name']]->barangay_value,2) : " "}}</td>
            <td class="text-right">{{  !empty($row) ? number_format($row,2) : "-"}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>GRAND TOTAL</th>
            <th class="text-right">{{number_format($provtotal,2)}}</th>
            <th class="text-right">{{number_format($muntotal,2)}}</th>
            <th class="text-right">{{number_format($brgytotal,2)}}</th>
            @php($grandtotal = $provtotal + $muntotal + $brgytotal)
            <th class="text-right">{{number_format($grandtotal,2)}}</th>
        </tr>
    </tfoot>    
</table>

<table>
    <tr>
        <th colspan="3"><u>SOURCES OF SAND AND GRAVEL TAX COLLECTIONS</u></th>
    </tr>
    <tr>
        <td></td>
        <td colspan="2">Sand and Gravel Permittess:</td>
    </tr>

    @php($typestotal = 0)
    @if(isset($graveltypes['Commercial']))
    @php($typestotal += $graveltypes['Commercial']->value)
    <tr>
        <td></td>
        <td class="text-right">Commercial</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Commercial']->value,2) }}</td>
    </tr>
    @endif

    @if(isset($graveltypes['Industrial']))
    @php($typestotal += $graveltypes['Industrial']->value)
    <tr>
        <td></td>
        <td class="text-right">Industrial</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Industrial']->value,2) }}</td>
    </tr>
    @endif

    <tr>
        <td></td>
        <td colspan="2">Projects</td>
    </tr>

    @if(isset($graveltypes['Contractors (Prov.)']))
    @php($typestotal += $graveltypes['Contractors (Prov.)']->value)
    <tr>
        <td></td>
        <td class="text-right">Provincial</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Contractors (Prov.)']->value,2) }}</td>
    </tr>
    @endif

    @if(isset($graveltypes['DPWH - CAR (ADA)']))
    @php($typestotal += $graveltypes['DPWH - CAR (ADA)']->value)
    <tr>
        <td></td>
        <td class="text-right">DPWH Remittances</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['DPWH - CAR (ADA)']->value,2) }}</td>
    </tr>
    @endif

    @if(isset($graveltypes['Monitoring']))
    @php($typestotal += $graveltypes['Monitoring']->value)
    <tr>
        <td></td>
        <td class="text-right">Sand and Gravel Penalties Through Monitoring</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Monitoring']->value,2) }}</td>
    </tr>
    @endif

    <tr>
        <td colspan="3" class="text-right bold-text">{{ number_format($typestotal,2) }}</td>
    </tr>

</table>

</body>
</html>

