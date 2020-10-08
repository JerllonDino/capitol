<!DOCTYPE html>
<html>
<head>

    <title>Receipt</title>
    <style>
        @page { margin: 0px; }
        body{
            font-size: 9px;
            font-family: arial, "sans-serif";
            margin:0;
            transform: rotate(90deg);
        }
        #collection_part {
            position: fixed;
            height: 100%;
            width: 950px;
            padding-top: -220px;
            padding-left: 252px;
        }
        .hidden {
            display: none;
        }
        table, td {
            border-collapse: collapse;
            /*border: 1px solid #000000; for debugging*/
        }
        td {
            width: 20px;
            height: 19px;
            position: fixed;
        }
    </style>
</head>
<body>

<span class="hidden">
{{ $height = 21 }}
{{ $width = 41}}
</span>
<div id="collection_part">
    <table>
    @for($y=0; $y<$height; $y++)
        <tr>
            @for($x=0; $x<$width; $x++)
            
                @if ($x == 25 && $y == 2)
                    <!-- PREVIOUS TAX RECEIPT NO -->
                    <td colspan="8"></td>
                    <span class="hidden">
                        {{ $x += 7}}
                    </span>
                    
                @elseif ($x == 25 && $y == 4)
                    <!-- PREVIOUS TAX DATE -->
                    <td colspan="5"></td>
                    <span class="hidden">
                        {{ $x += 4}}
                    </span>
                    
                @elseif ($x == 30 && $y == 4)
                    <!-- PREVIOUS TAX YEAR -->
                    <td colspan="3"></td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 34 && $y == 4)
                    <!-- DATE -->
                    <td colspan="6">{{ date('F d, Y') }}</td>
                    <span class="hidden">
                        {{ $x += 5}}
                    </span>
                    
                @elseif ($x == 11 && $y == 3)
                    <!-- MUNICIPALITY -->
                    <td colspan="8">{{ $receipt->municipality->name }}</td>
                    <span class="hidden">
                        {{ $x += 7}}
                    </span>
                    
                @elseif ($x == 0 && $y == 5)
                    <!-- PAYOR -->
                    <td colspan="13">{{ $receipt->customer->name }}</td>
                    <span class="hidden">
                        {{ $x += 12}}
                    </span>
                    
                @elseif ($x == 15 && $y == 5)
                    <!-- SUM -->
                    <td colspan="18">TWO HUNDRED TWENTY THOUSAND SEVENTY SEVEN HUNDRED AND EIGHTY FIVE</td>
                    <span class="hidden">
                        {{ $x += 17}}
                    </span>
                    
                @elseif ($x == 36 && $y == 6)
                    <!-- SUM IN FIGURES -->
                    <td colspan="5">123,456,789.89</td>
                    <span class="hidden">
                        {{ $x += 4}}
                    </span>
                    
                @elseif ($x == 28 && $y == 7)
                    <!-- CALENDAR YEAR -->
                    <td colspan="5">{{ $receipt->F56Detail->period_covered }}</td>
                    <span class="hidden">
                        {{ $x += 4}}
                    </span>
                    
                @elseif ($x == 0 && $y == 10)
                    <!-- DECLARED OWNER 1 -->
                    <td colspan="6"></td>
                    <span class="hidden">
                        {{ $x += 5}}
                    </span>
                    
                @elseif ($x == 0 && $y == 11)
                    <!-- DECLARED OWNER 2 -->
                    <td colspan="6"></td>
                    <span class="hidden">
                        {{ $x += 5}}
                    </span>
                    
                @elseif ($x == 0 && $y == 12)
                    <!-- DECLARED OWNER 3 -->
                    <td colspan="6"></td>
                    <span class="hidden">
                        {{ $x += 5}}
                    </span>
                    
                @elseif ($x == 0 && $y == 13)
                    <!-- DECLARED OWNER 4 -->
                    <td colspan="6"></td>
                    <span class="hidden">
                        {{ $x += 5}}
                    </span>
                    
                @elseif ($x == 0 && $y == 14)
                    <!-- DECLARED OWNER 5 -->
                    <td colspan="6"></td>
                    <span class="hidden">
                        {{ $x += 5}}
                    </span>
                    
                @elseif ($x == 0 && $y == 15)
                    <!-- DECLARED OWNER 6 -->
                    <td colspan="6"></td>
                    <span class="hidden">
                        {{ $x += 5}}
                    </span>
                
                @elseif ($x == 7 && $y == 10)
                    <!-- DECLARED LOCATION 1 -->
                    <td colspan="4">LOCATION 1</td>
                    <span class="hidden">
                        {{ $x += 3}}
                    </span>
                    
                @elseif ($x == 7 && $y == 11)
                    <!-- DECLARED LOCATION 2 -->
                    <td colspan="4">LOCATION 2</td>
                    <span class="hidden">
                        {{ $x += 3}}
                    </span>
                    
                @elseif ($x == 7 && $y == 12)
                    <!-- DECLARED LOCATION 3 -->
                    <td colspan="4">LOCATION 3</td>
                    <span class="hidden">
                        {{ $x += 3}}
                    </span>
                    
                @elseif ($x == 7 && $y == 13)
                    <!-- DECLARED LOCATION 4 -->
                    <td colspan="4">LOCATION 4</td>
                    <span class="hidden">
                        {{ $x += 3}}
                    </span>
                    
                @elseif ($x == 7 && $y == 14)
                    <!-- DECLARED LOCATION 5 -->
                    <td colspan="4">LOCATION 5</td>
                    <span class="hidden">
                        {{ $x += 3}}
                    </span>
                    
                @elseif ($x == 7 && $y == 15)
                    <!-- DECLARED LOCATION 6 -->
                    <td colspan="4">LOCATION 6</td>
                    <span class="hidden">
                        {{ $x += 3}}
                    </span>
                    
                @elseif ($x == 11 && $y == 10)
                    <!-- DECLARED LOT 1 -->
                    <td colspan="2">LOT 1</td>
                    <span class="hidden">
                        {{ $x += 1}}
                    </span>
                    
                @elseif ($x == 11 && $y == 11)
                    <!-- DECLARED LOT 2 -->
                    <td colspan="2">LOT 2</td>
                    <span class="hidden">
                        {{ $x += 1}}
                    </span>
                    
                @elseif ($x == 11 && $y == 12)
                    <!-- DECLARED LOT 3 -->
                    <td colspan="2">LOT 3</td>
                    <span class="hidden">
                        {{ $x += 1}}
                    </span>
                    
                @elseif ($x == 11 && $y == 13)
                    <!-- DECLARED LOT 4 -->
                    <td colspan="2">LOT 4</td>
                    <span class="hidden">
                        {{ $x += 1}}
                    </span>
                    
                @elseif ($x == 11 && $y == 14)
                    <!-- DECLARED LOT 5 -->
                    <td colspan="2">LOT 5</td>
                    <span class="hidden">
                        {{ $x += 1}}
                    </span>
                    
                @elseif ($x == 11 && $y == 15)
                    <!-- DECLARED LOT 6 -->
                    <td colspan="2">LOT 6</td>
                    <span class="hidden">
                        {{ $x += 1}}
                    </span>
                    
                @elseif ($x == 13 && $y == 10)
                    <!-- DECLARED TD 1 -->
                    <td colspan="3">123-456-789-1</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 13 && $y == 11)
                    <!-- DECLARED TD 2 -->
                    <td colspan="3">123-456-789-2</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 13 && $y == 12)
                    <!-- DECLARED TD 3 -->
                    <td colspan="3">123-456-789-3</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 13 && $y == 13)
                    <!-- DECLARED TD 4 -->
                    <td colspan="3">123-456-789-4</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 13 && $y == 14)
                    <!-- DECLARED TD 5 -->
                    <td colspan="3">123-456-789-5</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 13 && $y == 15)
                    <!-- DECLARED TD 6 -->
                    <td colspan="3">123-456-789-6</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 21 && $y == 10)
                    <!-- DECLARED ASSESSED TOTAL 1 -->
                    <td colspan="3">123,456,789.01</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 21 && $y == 11)
                    <!-- DECLARED ASSESSED TOTAL 2 -->
                    <td colspan="3">123,456,789.02</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 21 && $y == 12)
                    <!-- DECLARED ASSESSED TOTAL 3 -->
                    <td colspan="3">123,456,789.03</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 21 && $y == 13)
                    <!-- DECLARED ASSESSED TOTAL 4 -->
                    <td colspan="3">123,456,789.04</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 21 && $y == 14)
                    <!-- DECLARED ASSESSED TOTAL 5 -->
                    <td colspan="3">123,456,789.05</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 21 && $y == 15)
                    <!-- DECLARED ASSESSED TOTAL 6 -->
                    <td colspan="3">123,456,789.06</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 24 && $y == 10)
                    <!-- DECLARED TAX 1 -->
                    <td colspan="3">123,456,789.01</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 24 && $y == 11)
                    <!-- DECLARED TAX 2 -->
                    <td colspan="3">123,456,789.02</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 24 && $y == 12)
                    <!-- DECLARED TAX 3 -->
                    <td colspan="3">123,456,789.03</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 24 && $y == 13)
                    <!-- DECLARED TAX 4 -->
                    <td colspan="3">123,456,789.04</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 24 && $y == 14)
                    <!-- DECLARED TAX 5 -->
                    <td colspan="3">123,456,789.05</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 24 && $y == 15)
                    <!-- DECLARED TAX 6 -->
                    <td colspan="3">123,456,789.06</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 27 && $y == 10)
                    <!-- DECLARED INST NO 1 -->
                    <td colspan="1" style="text-align: right;">1</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 27 && $y == 11)
                    <!-- DECLARED INST NO 2 -->
                    <td colspan="1" style="text-align: right;">2</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 27 && $y == 12)
                    <!-- DECLARED INST NO 3 -->
                    <td colspan="1" style="text-align: right;">3</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 27 && $y == 13)
                    <!-- DECLARED INST NO 4 -->
                    <td colspan="1" style="text-align: right;">4</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 27 && $y == 14)
                    <!-- DECLARED INST NO 5 -->
                    <td colspan="1" style="text-align: right;">5</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 27 && $y == 15)
                    <!-- DECLARED INST NO 6 -->
                    <td colspan="1" style="text-align: right;">6</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 28 && $y == 10)
                    <!-- DECLARED INST AMT 1 -->
                    <td colspan="3">123,456,789.01</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 28 && $y == 11)
                    <!-- DECLARED INST AMT 2 -->
                    <td colspan="3">123,456,789.02</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 28 && $y == 12)
                    <!-- DECLARED INST AMT 3 -->
                    <td colspan="3">123,456,789.03</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 28 && $y == 13)
                    <!-- DECLARED INST AMT 4 -->
                    <td colspan="3">123,456,789.04</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 28 && $y == 14)
                    <!-- DECLARED INST AMT 5 -->
                    <td colspan="3">123,456,789.05</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 28 && $y == 15)
                    <!-- DECLARED INST AMT 6 -->
                    <td colspan="3">123,456,789.06</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 31 && $y == 10)
                    <!-- DECLARED FULL AMT 1 -->
                    <td colspan="3">123,456,789.01</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 31 && $y == 11)
                    <!-- DECLARED FULL AMT 2 -->
                    <td colspan="3">123,456,789.02</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 31 && $y == 12)
                    <!-- DECLARED FULL AMT 3 -->
                    <td colspan="3">123,456,789.03</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 31 && $y == 13)
                    <!-- DECLARED FULL AMT 4 -->
                    <td colspan="3">123,456,789.04</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 31 && $y == 14)
                    <!-- DECLARED FULL AMT 5 -->
                    <td colspan="3">123,456,789.05</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 31 && $y == 15)
                    <!-- DECLARED FULL AMT 6 -->
                    <td colspan="3">123,456,789.06</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 34 && $y == 10)
                    <!-- DECLARED PENALTY 1 -->
                    <td colspan="1">1</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 34 && $y == 11)
                    <!-- DECLARED PENALTY 2 -->
                    <td colspan="1">2</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 34 && $y == 12)
                    <!-- DECLARED PENALTY 3 -->
                    <td colspan="1">3</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 34 && $y == 13)
                    <!-- DECLARED PENALTY 4 -->
                    <td colspan="1">4</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 34 && $y == 14)
                    <!-- DECLARED PENALTY 5 -->
                    <td colspan="1">5</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 34 && $y == 15)
                    <!-- DECLARED PENALTY 6 -->
                    <td colspan="1">6</td>
                    <span class="hidden">
                    </span>
                    
                @elseif ($x == 36 && $y == 10)
                    <!-- DECLARED TOTAL 1 -->
                    <td colspan="3">123,456,789.01</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 36 && $y == 11)
                    <!-- DECLARED TOTAL 2 -->
                    <td colspan="3">123,456,789.02</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 36 && $y == 12)
                    <!-- DECLARED TOTAL 3 -->
                    <td colspan="3">123,456,789.03</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 36 && $y == 13)
                    <!-- DECLARED TOTAL 4 -->
                    <td colspan="3">123,456,789.04</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 36 && $y == 14)
                    <!-- DECLARED TOTAL 5 -->
                    <td colspan="3">123,456,789.05</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 36 && $y == 15)
                    <!-- DECLARED TOTAL 6 -->
                    <td colspan="3">123,456,789.06</td>
                    <span class="hidden">
                        {{ $x += 2}}
                    </span>
                    
                @elseif ($x == 26 && $y == 17)
                    <!-- TOTAL OF DECLARED -->
                    <td colspan="14">123,456,789.06</td>
                    <span class="hidden">
                        {{ $x += 13}}
                    </span>
                    
                @else
                <td> </td>
                @endif
            @endfor
        </tr>
    @endfor
    </table>
</div>

</body>
</html>