<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        html{ margin: 0px; width: 12.10cm; height: 22cm;}
        @page { margin: 0px; width: 12.10cm; height: 22cm; }
        body , div , strong , p { margin: 0px; padding: 0px; }
        body {

            font-family: arial, "sans-serif";
            font-size: 0.9em;
        }



        /* turn to class to debug */
        #dbg {
            border: thin solid #000000;
        }

        .hidden {
            display: none;
        }

        #header {
       /*     margin-left: -25px;
            text-align: center;
            padding-top: 72px;
            position: fixed;*/
        }

        #date {
            padding-top: 3.25cm;
            padding-right: 0.75cm;
            position: fixed;
            float:right;
        }

        #payor {
            padding-top: 5.5cm;
            padding-left:  1.00cm;
            position: fixed;
            max-width: 320px;
        }

        #collection_part {
            padding-top: 3.70cm;
            padding-left: 0.25cm;
            position: fixed;
            font-size: 0.9em;
         }

        .coll_nature {
            float: left;
            width: 75%;

        }

        .coll_amt {
            text-align: right;
        }

        .coll_pad {
            clear: both;
            height: 2px;
        }

        #total {
            padding-top: 15cm;
            width: 10cm;
            text-align: right;
            position: fixed;
        }

        #total_words {
            word-break: keep-all;
            padding-top: 16.05cm;
            padding-left: 1cm;
            max-width: 9cm;
            text-indent: 3.25cm;
            position: fixed;
            font-size: 0.8em;
        }

        #collecting_offcer{
            padding-top:  19.45cm;
            padding-left: 5.65cm;
            font-size: 0.9em;
            position: fixed;
        }
        #collecting_offcer small{
            font-size: 11px;
        }
        hr {
          border:none;
          border-top:2px dotted #ccc;
          height:1px;
        }
    </style>
</head>
<body>

<span class="hidden">
{{ $total = 0 }}
</span>

<div class="row">

    <div id="header" class="dbg">
        <center style="font-weight: bold">PROVINCE OF BENGUET</center>
        <center style="font-weight: bold">OFFICE OF THE TREASURER</center>
        <center>{{$receipt->municipality->name}},Benguet</center>

        <div style="margin: 0 0 0 10px;font-size:11px">
            <table width="100%">
                <tr>
                    <td width="82%">
                        Previous Tax Receipt No.
                    </td>
                    <td><span> No. BGT {{$receipt->serial_no}}</span></td>
                </tr>
                <tr>
                    <td>{{($receipt->F56Detail->TDARPX->previousTaxType) ? $receipt->F56Detail->TDARPX->previousTaxType->previous_tax_name : $receipt->serial_no}}</td>
                    <td><span style="clear:right;"> {{ date('F d, Y', strtotime($receipt->date_of_entry)) }}</span></td>
                </tr>
                <tr>
                    <td>For the Year: {{$receipt->F56Detail->period_covered}} <br>
                        Recieved From: Miriam Vicente
                    </td>
                    <td>
                        <!-- <table style="padding-left:10px;font-size: 10px" >
                            <tr>
                                <td>
                                    <span style="border:1px solid black;font-weight: bold;padding-right: 2px">x</span>BASIC TAX<BR>
                                    <span style="border:1px solid black;font-weight: bold;padding-right: 2px">x</span>SPECIAL EDUCATION FUND
                                </td>
                            </tr>
                        </table> -->
                    </td>
                </tr>
            </table>
            

            
            <br>
            
        </div>
    </div>

    <div>
        
    </div>

    <div id="date" class="dbg">
    </div>

    <!-- @if (strlen($receipt->customer->name) > 64)
    <div id="payor" class="dbg" style="font-size:8;">
    @else
    <div id="payor" class="dbg">
    @endif
        Name of Declared Owner: 
    </div> -->
<?php
$cert_sandgravelprocessed = 0; $cert_abc = 0; $cert_sandgravel = 0;$cert_boulders = 0 ;
$mun_brg = '';
?>
    <div id="collection_part" class="dbg">
        
        <table style="font-size:9px" width="100%">
            <tr style="text-align:center">
            <td rowspan="2" class="border">NAME OF <br>DECLARED OWNER</td>
            <td rowspan="2" class="border">LOCATION <br>No./Street/Barangay</td>
            <td rowspan="2" class="border">LOT <br>BLOCK NO.</td>
            <td rowspan="2" class="border">TAX <br>DEC. NO</td>
            <td colspan='3' class="border">ASSESSED VALUE</td>
            <td rowspan="2" class="border">TAX DUE</td>
            <td colspan='2' class="border">INSTALLMENT</td>
            <td rowspan="2" class="border">FULL PAYMENT</td>
            <td rowspan="2" class="border">penalty %</td>
            <td rowspan="2" class="border" >TOTAL</td>
        </tr>
        <tr style="text-align:center">
            <td class="border">Land</td>
            <td class="border">Improvement</td>
            <td class="border">Total</td>
            <td class="border">No.</td>
            <td class="border">Payment</td>
        </tr>
        @foreach($receipt->F56Detailmny as $key => $res)
            <tr>
                <td class="border">
                    @if($key == 0)
                        {{ $receipt->customer->name }}
                    @endif
                </td>
                <td class="border">
                    {{$res->TDARPX->barangay_name->name}}
                </td>
                <td class="border">
                    {{$res->TDARPX->tdarpno}}
                </td>
                <td class="border"></td>
                <td class="border">{{$res->tdrp_assedvalue}}</td>
                <td class="border"></td>
                <td class="border">{{$res->tdrp_assedvalue}}</td>
                <td class="border">{{number_format($receipt->F56Detailmny[0]->tdrp_assedvalue * 0.01,2)}}</td>
                <td class="border">{{$res->period_covered}}</td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
            <tr>
                <td class="border"></td>
                <td class="border">
                    {{$res->F56Type->name}}
                </td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border">BASIC</td>
                <td class="border">{{number_format($res->basic_current,2)}}</td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
            <tr>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border"></td>
                <td class="border">SEF</td>
                <td class="border">{{number_format($res->basic_current,2)}}</td>
                <td class="border"></td>
                <td class="border"></td>
            </tr>
            <?php
                $total += 2 * $res->basic_current;
            ?>
        @endforeach
            <tr style="font-size: 10px;font-style: bold">
                <td class="border" colspan="5" rowspan="2"> 
                    <table width="100%" style="font-size: 10px">
                        <tr>
                            <td>
                                <div style="width:80%">
                                    Payment without pernalty may be made within the periods stated below is by installment
                                </div>
                                <table width="90%" style="margin-top: 5px">
                                    <tr>
                                        <td width="30%">1st Inst.</td>
                                        <td width="20%">_</td>
                                        <td width="50%">Jan 1. to Mar. 31</td>
                                    </tr>
                                    <tr>
                                        <td>2nd Inst.</td>
                                        <td>_</td>
                                        <td>Apr. 1 to Jun. 30</td>
                                    </tr>
                                    <tr>
                                        <td>3rd Inst.</td>
                                        <td>_</td>
                                        <td>Jul. 1 to Sept. 30</td>
                                    </tr>
                                    <tr>
                                        <td>4th Inst</td>
                                        <td>_</td>
                                        <td>Oct. 1 to Dec. 31</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                                <table width="100%">
                                    <tr>
                                        <td colspan="2" style='text-align:center'>MODE OF PAYMENT</td>
                                    </tr>
                                    <tr>
                                        <td width="70%" height="15px">CASH</td>
                                        <td>
                                            Php {{ number_format($total, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="15px">CHECK</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td height="15px">TW/PMO</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center" height="15px" >TOTAL

                                Php {{ number_format($total, 2) }}

                                        </td>
                                        <td></td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="border" colspan="3">Total:<br>Sum of</td>
                <td class="border" colspan="2"></td>
                <td class="border" colspan="3" style="float:right">
                    Php {{ number_format($total, 2) }}<br>
                    {{ convert_number_to_words(number_format($total, 2, '.', '')) }}
                </td>
            </tr><tr style="font-size: 10px;font-style: bold;text-align: center">
                <td class="border" colspan="4">
                    ISABEL D. KIW-AN<BR>
                    LRCO IV
                </td>
                <td class="border" colspan="4">
                    IMELDA I. MACANES<BR>
                    PROVINCIAL TREASURER
                </td>
            </tr>
        </table>
         <hr />
             

        <!-- @foreach($receipt->F56Detailmny as $key => $rec)

                <div class="coll_nature dbg">
                    BASIC TAX {{$receipt->period_covered}}
                </div>
                <div class="coll_amt dbg">
                   {{ number_format($rec->basic_current, 2) }}
                </div>
            <div class="coll_pad"></div>

                <div class="coll_nature dbg">
                    SPECIAL EDUCATION FUND {{$receipt->period_covered}}
                </div>
                <div class="coll_amt dbg">
                   {{ number_format($rec->basic_current, 2) }}
                </div>
            <div class="coll_pad"></div>

            <?php
                $total += 2 * $rec->basic_current;
            ?>

        @endforeach

        <hr /> -->
        
    </div>



    <div id="total" class="dbg">
        <!-- Php {{ number_format($total, 2) }} -->
    </div>


    <div id="total_words" class="dbg">
    </div>

    <div id="collecting_offcer" class="dbg">
        IMELDA I. MACANES<br/>
        <small>PROVINCIAL TREASURER</small>
    </div>
</div>


</body>
</html>
