<!DOCTYPE html>
<html>
<head>
    <title>Certificate - Provincial Permit</title>
    <style>
        body {
            font-family: arial, "sans-serif";
            margin: 0px;
            font-size: 16px;
        }
        #items {
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .header-container {
            width: 80%;
            text-align: center;
            margin-right: auto;
            margin-left: auto;
        }
        .header {
            width: 95%;
            display: block;
            font-weight: strong;
        }
        #logo {
            height: 80px;
            position: fixed;
            top: 0;
            left: 13%;
        }
        #header-dt {
            float: right;
            text-align: center;
            position: fixed;
            margin-top: 75px;
        }
        #cert {
            margin-top: 70px;
            font-size: 1.5em;
            text-align: center;
            width: 100%;
            margin-bottom: 1.2cm;
        }
        #officers {
            width: 100%;
            padding-left: 50%;
            padding-top: 1.5cm;
        }
        .underline {
            /*border-bottom: 2px solid #000000;*/
        }
        .underline2{
            /*border-top : 2px solid  #000;*/
           border-bottom:   4px double #000000;
        }
        .blk {
            padding-top: 10px;
        }
        .center {
            text-align: center;
        }
        .val {
            text-align: right;
        }
        .hidden {
            display: none;
        }
        .title {
            padding-top: 20px;
        }
        #bottom {
            position: fixed;
            top: 80%;
        }
        .indent {
            padding-left: 30px;
        }
        #detail {
            width: 100%;
            font-size: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        {{ Html::image('/asset/images/benguet_capitol.png', "Logo", array('id' => 'logo')) }}
        <span class="header">Republic of the Philippines</span>
        <span class="header">PROVINCE OF BENGUET</span>
        <span class="header">La Trinidad</span>
        <span class="header">OFFICE OF THE PROVINCIAL TREASURER</span>
    </div>
    <table id="header-dt">
        <tr>
            <td></td>
            <td class="underline" width="125">{{ date('F d, Y', strtotime($cert->date_of_entry)) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Date</td>
        </tr>
    </table>
    <div id="cert">
        <b>C E R T I F I C A T I O N</b>
    </div>
    <div class="blk">
        <span class="indent">THIS IS TO CERTIFY that</span>
        <u><b>{{ $cert->recipient }}</b></u>
        <span>contractor of</span>
        <u><b>{{ $cert->address }}</b></u>
        <span>has paid the following obligation on the following contracts:</span>
    </div>
    <div class="blk">
        <u>{!! $cert->detail !!}</u>
    </div>
    <span class="hidden">
    {{ $cert_sandgravelprocessed = 0 }}
    {{ $cert_abc = 0 }}
    {{ $cert_sandgravel = 0 }}
    {{ $cert_boulders = 0 }}
    </span>
    <table id="items">
        <span class="hidden">
        {{ $total = 0 }}
        </span>
         @foreach($receipts as $receipt)
        <tr>
            <td><b>Under Official Receipt No.</b></td>
            <td class="center underline">{{ $receipt->serial_no }}</td>
            <td></td>
        </tr>
        <tr>
            <td><b>Dated</b></td>
            <td class="center underline">{{ date('F d, Y', strtotime($receipt->date_of_entry)) }}</td>
            <td></td>
        </tr>
        <tr>
            <td><b>Sand and gravel Tax:</b></td>
            <td></td>
            <td></td>
        </tr>

        <?php
                $cert_sandgravelprocessedx = 0;
                $cert_sandgravelprocessedxx = 0;
                $cert_abcx = 0;
                $cert_abcxx = 0;
                $cert_sandgravelx = 0;
                $cert_sandgravelxx = 0;
                $cert_bouldersx = 0;
                $cert_bouldersxx = 0;
        ?>
        @foreach ($receipt->items as $item)
            @if(isset($item->detail) )
            <?php
            
                $sg_array = array('158','159','160','161');
            
                    $show_sg = true;
                    if ($item->detail->col_collection_rate_id ==  158){
                        $cert_abc += $item->value/$item->detail->value; 
                        $show_sg = false;
                    }
                    elseif ($item->detail->col_collection_rate_id == 159 ){
                        $cert_sandgravel += $item->value/$item->detail->value; 
                        $show_sg = false;
                    }
                    elseif ($item->detail->col_collection_rate_id ==  160){
                        $cert_boulders += $item->value/$item->detail->value; 
                        $show_sg = false;
                    }
                    elseif ($item->detail->col_collection_rate_id ==  161){
                          $cert_sandgravelprocessed += $item->value/$item->detail->value; 
                          $show_sg = false;
                    }
                              
                            
                    if($cert_sandgravelprocessed >= $cert->sand_sandgravelprocessed){
                        $cert_sandgravelprocessedx = $cert_sandgravelprocessed;
                        $cert_sandgravelprocessedxx = $cert_sandgravelprocessed - $cert->sand_sandgravelprocessed;
                    }else{
                        $cert_sandgravelprocessedxx = $cert_sandgravelprocessed;
                        $cert_sandgravelprocessedx = $cert_sandgravelprocessed + $cert->sand_sandgravelprocessed;
                    }


                    if($cert_abc >= $cert->sand_abc){
                        $cert_abcx = $cert_abc;
                        $cert_abcxx = $cert_abc - $cert->sand_abc;
                    }else{
                        $cert_abcx = $cert_abc + $cert->sand_abc;
                        $cert_abcxx = $cert_abc;
                    }

                    if($cert_sandgravel >= $cert->sand_sandgravel){
                        $cert_sandgravelx = $cert_sandgravel + $cert->sand_sandgravel;
                        $cert_sandgravelxx = $cert_sandgravelx - $cert->sand_sandgravel;
                    }else{
                        $cert_sandgravelx = $cert_sandgravel + $cert->sand_sandgravel;
                        $cert_sandgravelxx = $cert_sandgravel;
                    }
                    if($cert_boulders >= $cert->sand_boulders){
                        $cert_bouldersx = $cert_boulders;
                        $cert_bouldersxx = $cert_boulders - $cert->sand_boulders;
                    }else{
                        $cert_bouldersx = $cert_boulders + $cert->sand_boulders;
                        $cert_bouldersxx = $cert_boulders ;
                    }
                    
                   
                    $total += $item->value ;
                    
            ?>
              
                <tr>
                    <td class="">
                        <b>{{ $item->nature }}</b>
                    </td>
                    <td class="center underline " style="text-align: right;">
                        
                    </td>
                    <td class="val underline">
                      {{ number_format($item->value, 2) }}
                    </td>
                </tr>
            @else
                <tr>
                    <td class="">
                        <b>{{ $item->nature }}</b>
                    </td>
                    <td class="center underline"  style="text-align: right;">PHP</td>
                    <td class="val underline">
                    <span class="hidden">
                    {{ $total += $item->value }}
                    </span>
                   {{ number_format($item->value, 2) }}
                    </td>
                </tr>
            @endif
        @endforeach
        @endforeach
        <tr>
            <td><b>Total Amount Paid</b></td>
            <td  style="text-align: right;">PHP</td>
            <td class="val underline2">{{ number_format($total, 2) }}</td>
        </tr>
    </table>
    <br>
    <table id="officers">
        <tr>
            <td></td>
            <td class="center"><b>{{ $cert->provincial_treasurer }}</b></td>
        </tr>
        <tr>
            <td></td>
            <td class="center">Provincial Treasurer</td>
        </tr>
    </table>
    <br>
    <table id="detail">
        <tr>
            <th width="40%"></th>
            <th>Sand and Gravel Processed (in CU.M)</th>
            <th>Aggregate Base Course (in CU.M)</th>
            <th>Gravel/Sand (in CU.M)</th>
            <th>Boulders (in CU.M)</th>
        </tr>
        <tr>
            <td>Total Volume as per Approved Agency Estimate</td>
            <td class="underline val">
            {{ number_format( $cert_sandgravelprocessedx ,2) }}
            </td>
            <td class="underline val">
            {{ number_format( $cert_abcx,2) }}
            </td>
            <td class="underline val">
            
            {{ number_format( $cert_sandgravelx ,2) }}
            </td>
            <td class="underline val">
            
            {{ number_format( $cert_bouldersx ,2) }}
            </td>
        </tr>
        <tr>
            <td>Less: Supported by Official Receipt</td>
            <td class="underline val">{{ number_format($cert->sand_sandgravelprocessed, 2) }}</td>
            <td class="underline val">{{ number_format($cert->sand_abc, 2) }}</td>
            <td class="underline val">{{ number_format($cert->sand_sandgravel, 2) }}</td>
            <td class="underline val">{{ number_format($cert->sand_boulders, 2) }}</td>
        </tr>
        <tr>
            <td>Balance</td>
            <td class="underline val">{{ number_format($cert_sandgravelprocessedxx, 2) }} <!-- 161 --></td>
            <td class="underline val">{{ number_format($cert_abcxx, 2) }} <!-- 158 --></td>
            <td class="underline val">{{ number_format($cert_sandgravelxx, 2) }} <!-- 159 --></td>
            <td class="underline val">{{ number_format($cert_bouldersxx, 2) }} <!-- 160 --></td>

        </tr>
    </table>
</body>
</html>

