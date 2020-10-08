<!DOCTYPE html>
<html>
<head>
    <title>SAND and GRAVEL</title>
    <style>
        @page { margin: 0.5in 10px; }
        body {
            margin: 0px 10px; 
            font-family: arial, "sans-serif";
            font-size: 8.5;
        }

        
         .center {
                width: 325px;
                text-align: center;
                margin: 10px auto;
        }

           .image_logo{
                width: 80px;
                height:80px;
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

        .table-no-bordered, .table-no-bordered>thead>tr>th, .table-no-bordered>tbody>tr>td{
                border:none !important;
        }
       
    </style>
</head>
<body>
    <table class="center">
        <tr>
        <td style="width:10px; padding:0px;">
            <img src="{{asset('asset/images/benguet-logo.png')}}" class="image_logo" alt/>
        </td>
        <td style=" width: 230px">
        REPUBLIC OF THE PHILIPPINES<br />
        BENGUET PROVINCE<br />
        La Trinidad<br/>
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

    <center><strong>SAND AND GRAVEL TAX/ PENALTIES COLLECTED <br/><br> <u>FOR THE PERIOD {{ $datex->format('F') }} {{$year}}</u></strong></center><br>

<table class="table table-condensed table-bordered">
    <thead>
        <tr>
        <th rowspan="2">DATE<br/>{{ $year}}</th>
        <th rowspan="2">MONITORING<br/>PENALTIES</th>
        <th rowspan="2">PROVINCIAL<br/>CONTRACTORS</th>
        <th rowspan="2">DPWH-CAR<br/>REMITTANCE</th>
        <th rowspan="2">MUN/BRGY<br/>REMITTANCE</th>
        <th rowspan="1" colspan="2">S & G PERMITTEES</th>
        <th rowspan="2">TOTALS</th>
        </tr>

        <tr>
            <th>INDUSTRIAL</th>
            <th>COMMERCIAL</th>
        </tr>

        <tr>
            <th>{{ $datex->format('F') }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>0.00</th>
        </tr>
    </thead>
    <tbody>

        <?php
            $total = [];
            $t1 = $t2 = $t3 = $t416 = $t5 = $t6 = 0;
        ?>
        @foreach( $dailygraveltypes as $key => $dly )

            <?php
                $mun_bgy = $dly[4] + $dly[16];
                $total[$key] = $dly[1] + $dly[2] + $dly[3] + $mun_bgy + $dly[5] + $dly[6];

                $t1 += $dly[1];
                $t2 += $dly[2];
                $t3 += $dly[3];
                $t416 += $mun_bgy;
                $t5 += $dly[5];
                $t6 += $dly[6];

                
            ?>
                <tr>
                    <td class="text-center">{{ $key }}</td>
                    <td class="text-right">{{ $dly[1] ? number_format($dly[1],2) : '' }}</td>
                    <td class="text-right">{{ $dly[2] ? number_format($dly[2],2) : '' }}</td>
                    <td class="text-right">{{ $dly[3] ? number_format($dly[3],2) : '' }}</td>
                    <td class="text-right">{{ $mun_bgy ? number_format($mun_bgy,2) : '' }}</td>
                    <td class="text-right">{{ $dly[5] ? number_format($dly[5],2) : '' }}</td>
                    <td class="text-right">{{ $dly[6] ? number_format($dly[6],2) : '' }}</td>
                    <td class="text-right">{{ number_format($total[$key],2) }}</td>
                </tr>
        @endforeach
    </tbody>
    <tfoot>
       <tr>
           <th>Total</th>
           <th class="text-right">{{ number_format($t1,2) }}</th>
           <th class="text-right">{{ number_format($t2,2) }}</th>
           <th class="text-right">{{ number_format($t3,2) }}</th>
           <th>&nbsp;</th>
           <th class="text-right">{{ number_format($t5,2) }}</th>
           <th class="text-right">{{ number_format($t6,2) }}</th>
           <th class="text-right">{{ number_format( ( array_sum($total) - $t416 ),2) }}</th>
       </tr> 

       <tr>
           <th colspan="3">Provincial Share {{ $datex->format('F') }} {{$year}} </th>
           <th>&nbsp;</th>
           <th class="text-right">{{ number_format($t416,2) }}</th>
           <th>&nbsp;</th>
           <th>&nbsp;</th>
           <!-- <th class="text-right">{{ number_format($t416,2) }}</th> -->
           <th class="text-right">{{ number_format($provShare,2) }}</th>
       </tr>
       
       <tr>
           <th colspan="7">Total Collections</th>
           <th class="text-right">{{ number_format(  array_sum($total),2) }}</th>
       </tr>
    </tfoot>    
</table>

<table style="width: 70%; margin: 0 auto;">
    <tr>
        <th colspan="3"><u>SUMMARY</u></th>
    </tr>
    <tr>
        <th colspan="3">&nbsp;</th>
    </tr>
    {{-- <tr>
        <th colspan="3"><u>SOURCES OF SAND AND GRAVEL TAX COLLECTIONS</u></th>
    </tr> --}}
    <tr>
        <td colspan="3"><u>Sand and Gravel Permittess:</u></td>
    </tr>

    @php($typestotal = 0)
    @if(isset($graveltypes['Commercial']))
        @php($typestotal += $graveltypes['Commercial'])
    <tr>
        <td></td>
        <td class="text-left">Commercial</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Commercial'],2) }}</td>
    </tr>
    @endif

    @if(isset($graveltypes['Industrial ']))
        @php($typestotal += $graveltypes['Industrial '])
    <tr>
        <td></td>
        <td class="text-left">Industrial</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Industrial '],2) }}</td>
    </tr>

    @else
    <tr>
        <td></td>
        <td class="text-left">Industrial</td>
        <td class="text-right bold-text">{{ number_format(0,2) }}</td>
    </tr>
    @endif

    <tr>
        <td colspan="3"><u>Projects</u></td>
    </tr>

    @if(isset($graveltypes['Contractors (Prov.)']))
    @php($typestotal += $graveltypes['Contractors (Prov.)'])
    <tr>
        <td></td>
        <td class="text-left">Provincial</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Contractors (Prov.)'],2) }}</td>
    </tr>
    @endif

    @if(isset($graveltypes['DPWH - CAR (ADA)']))
    @php($typestotal += $graveltypes['DPWH - CAR (ADA)'])
    <tr>
        <td></td>
        <td class="text-left">DPWH Remittances</td>
        <td class="text-right bold-text">{{ number_format($graveltypes['DPWH - CAR (ADA)'],2) }}</td>
    </tr>
    @endif

    <tr>
        <td></td>
        <td colspan="2"></td>
    </tr>

    <tr>
        <td></td>
        <td colspan="2"></td>
    </tr>

    @if(isset($graveltypes['Monitoring']))
    @php($typestotal += $graveltypes['Monitoring'])
    <tr>
        <td class="text-left"><u>Sand and Gravel Penalties Through Monitoring</u></td>
        <td></td>
        <td class="text-right bold-text">{{ number_format($graveltypes['Monitoring'],2) }}</td>
    </tr>
    @endif
    @php($typestotal += $t416)
    <tr>
        <td class="text-left"><u>Municipal Remittances</u></td>
        <td></td>
        <td class="text-right bold-text">{{ number_format($t416,2) }}</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td  class="text-left bold-text">TOTAL</td>
        <td  class="text-right bold-text" style="border-bottom: 3px double #000; border-top: 1px solid #000;">{{ number_format($typestotal,2) }}</td>
    </tr>

</table>

<br />
    <table class="table table-no-bordered" style="width: 300px;">
    <tbody>
        <tr>
            <td style="width: 50px; ">Prepared by:<br><br></td>
            <td style="width: 150px;" >&nbsp;</td>
        </tr>
         <tr>
             <td></td>

             @php
                $STR = strtolower($acctble_officer_name->value);
                $STR = strtoupper($STR);
             @endphp
            <td style="font-weight: bold; text-align: center; ">{{ $STR }}</td>
        </tr>
        <tr>
             <td></td>
            <td style="font-weight: bold;  text-align: center;">{{ $acctble_officer_position->value }}</td>
        </tr>


    </tbody>
</table>


</body>
</html>

