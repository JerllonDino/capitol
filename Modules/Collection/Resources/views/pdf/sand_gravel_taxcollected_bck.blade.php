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

    <center><strong>SAND AND GRAVEL TAX/ PENALTIES COLLECTED <br/> FOR THE MONTH OF {{ $datex->format('F') }}, {{$year}}</strong></center>

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
        @foreach($municipality as $mun)
        <tr>
            @php($row = 0)
            <td> {{ strtoupper($mun['name']) }} </td>
            <td> {{ !empty($taxcollected[$mun['name']]->provincial_value) ? number_format($taxcollected[$mun['name']]->provincial_value,2) : " " }} </td> 
            @php($row += $taxcollected[$mun['name']]->provincial_value)
            <td> {{ !empty($taxcollected[$mun['name']]->municipal_value) ? number_format($taxcollected[$mun['name']]->municipal_value,2) : " "}}</td> 
            @php($row += $taxcollected[$mun['name']]->municipal_value)
            <td> {{ !empty($taxcollected[$mun['name']]->barangay_value) ? number_format($taxcollected[$mun['name']]->barangay_value,2) : " "}}</td>
            @php($row += $taxcollected[$mun['name']]->barangay_value)
            <td>{{  !empty($row) ? number_format($row,2) : "-"}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        
    </tfoot>    
</table>

</body>
</html>

