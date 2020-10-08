<!DOCTYPE html>
<html>
<head>

    <title>Receipt</title>
    <style>
        @page { margin: 0px; 
            size: 960px 456px;}
        body{
            font-size: 10px;

            font-family: arial, "sans-serif";
            /*background-image: url({{ URL::asset('form56.png') }});*/
            
        }
        .hidden {
            /*display: none;*/
        }

        .text-hidden{
            color: #FFFF;
            color: #000;
        }
        table{
            border-collapse: collapse;
        }
        .border{
            border: 1px solid #000;
        }
        .item{
            height: 11px;
            padding: 4px;
            font-size: 10px;
            position:absolute;
        }

        .text-center{
            text-align: center;
        }

        .rotated_vertical {
            -webkit-transform:rotate(270deg);
            -moz-transform:rotate(270deg);
            -ms-transform:rotate(270deg);
            -o-transform:rotate(270deg);
            transform:rotate(270deg);
            transform-origin: 50%;
            width: 20px;
        }
    </style>
</head>
<body>
    <?php $total = 0; ?>
     @foreach($receipt->F56Detailmny as $key => $res)
            <?php
                    $data = $res->basic_discount != '0.00'? number_format($res->basic_current,2) - $res->basic_discount : number_format($res->basic_current,2) + $res->basic_penalty_current;
                    $total += 2 * $data;
                ?>
    @endforeach
    <table width="100%" style="margin: 0 18px 0 8px; padding-top:20px">
        <tr>
            <td colspan=2 rowspan=2 height='20%'>
                <table width="100%">
                    <tr>
                        <td style="margin:0" width="15%">
                            <!-- <div style="text-align:center">
                                
                            Accountable Form No. 56<br>
                            (Revised Jan. 1994)<br><br>
                            <span style="font-size: 12px;font-weight: bold;text-align: center">OFFICIAL RECEIPT</span><br>
                            </div>
                            <b style="font-size: 10px;">ORIGINAL</b> -->

                        </td>
                        <!-- <td width="10%"><span id="logo" ><img src="{{asset('phlogo.png')}}" style="width:75px;height:75px;"></span></td> -->
                        <td style="text-align: center" width="40%">  
<!--                             Republic of the Philippines<br>
                            <div style="font-size: 12px;font-weight: bold">Province of Benguet</div>
                            <div style="font-size: 12px;font-weight: bold">OFFICE OF THE TREASURER</div>
                            {{$receipt->municipality->name}},Benguet<br>
                            <div style="border-top: 1px solid black;margin-left: 100px;margin-right: 100px">(Municipality)</div> -->

                        </td>
                        <!-- <td width="10%"><span id="logo" ><img src="{{asset('benguet.png')}}" style="width:75px;height:75px;"></span></td> -->
                        @php
                        $tax_type = '';
                        
                            if(isset($receipt->F56Detail->col_f56_previous_receipt)){
                               $tax_type = $receipt->F56Detail->TDARPX->previousTaxType->previous_tax_name ;    
                                              
                            }

                            $prev_date = '';
                            $prev_receipt = '';
                            $prev_year = '';
                            if(isset($receipt->F56Previuos)){
                                $prev_year = $receipt->F56Previuos->col_receipt_year;
                                $prev_receipt = $receipt->F56Previuos->col_receipt_no;
                                $prev_date = new Carbon\Carbon($receipt->F56Previuos->col_receipt_date);  
                                $prev_date = $prev_date->format('F d., Y');    
                            }
                        @endphp
                        <td >
                            <div style="height:70px;margin-left: 140px;">
                                <table width="95%" height="100%" style="margin-top:10">
                                    <tbody>
                                        <tr>
                                            <td colspan=2 height='35px' class="border text-center" >
                                                <!-- PREVIOUS TAX RECEIPT NO. -->
                                                <br>
                                                <small>{{ $tax_type }} </small>
                                                {{ ($prev_receipt)  }} 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="28px"  width="100" class="border text-center" >

                                                <br>
                                            {{  $prev_date }} 
                                        </td>
                                            <td class="border text-center">
                                                <!-- FOR THE YEAR -->
                                                <br>
                                             {{ $prev_year }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="18%" class="border">


                <b class="text-hidden">No. BGT <b> 
                <span style="font-size: 16px;padding:0;margin:0"></span></td>
        </tr>
        <tr>
            @php
                        $date_entry = new Carbon\Carbon($receipt->date_of_entry);
                        $period_cv = $receipt->F56Detailmny;

                        $period_covered = [];
                         foreach ($period_cv as $key => $value){
                            $p = explode('-',$value->period_covered);
                            foreach($p as $key => $pvalue){
                                 if(!in_array($pvalue,$period_covered)){
                                    $period_covered[] = $pvalue;
                                }
                            }
                           
                        }
                        sort($period_covered);
                        $first = reset($period_covered);
                        $last = end($period_covered);

                        $p_calendar_year = $first.' -'.$last; 
                        if($first == $last){
                            $p_calendar_year = $first;
                        }
                        $total_words = '';

                        $total_amnt = number_format($total, 2, '.', '');
                        $total_amnt_e = explode('.',$total_amnt);

                        $total_words = convert_number_to_words($total_amnt_e[0]).' and '.$total_amnt_e[1].'/100';
                        


             @endphp
            <td class="border" style="text-indent: 13px;">
                <!-- DATE -->
                {{ $date_entry->format('F d, Y') }}
            </td>
        </tr>
        <tr>
            <td class="border" height="20" style="text-indent: 40px;">
                {{$receipt->customer->name}}
            </td>
            <td class="border" style="text-indent: 40px;">{{ $total_words }} only</td>
            <td class="border" style="text-indent: 13px;">{{ number_format($total, 2) }}</td>
        </tr>
        <tr>
            <td colspan=2 class="border" height="28">
                <table width="100%" class="text-hidden">
                    <tr>
                        <td width="12%" class="text-hidden" >Philippine currency, in</td>
                        <td width="7%" class="text-hidden"><span style="border:1px solid"></span>full<br>installment</td>
                        <td width="75%" class="text-hidden">payment of REAL PROPERTY TAX upon property(ies) described below for the Calendar Year > {{ $p_calendar_year }} </td>
                    </tr>
                </table>
            </td>
            <td class="border">
                BASIC TAX<br>
                SPECIAL EDUCATION FUND
            </td>
        </tr>
    </table>
    <table width="100%" style="margin: 4px 25px 0 8px; border-color: #ffffff00" >
        <tr style="text-align:center;">
            <td class="border" style="width: 105px;"><span class="text-hidden">Name Of <br>DECLARED OWNER</span></td>
            <td class="border" width="50"><span class="text-hidden">Location<br>No./Street/Barangay</span></td>
            <td class="border" width="90"><span class="text-hidden">LOT<br>BLOCK NO.</span></td>
            <td class="border" width="47"><span class="text-hidden">TAX<br>DEC. NO</span></td>
            <td class="border" width="40"><span class="text-hidden">Land</span></td>
            <td class="border" width="40"><span class="text-hidden">Improvement</span></td>
            <td class="border" width="50"><span class="text-hidden">Total</span></td>
            <td class="border" width="50"><span class="text-hidden">TAX DUE</span></td>
            <td class="border" width="10"><span class="text-hidden">NO.</span></td>
            <td class="border" width="40"><span class="text-hidden">Payment</span></td>
            <td class="border" width="45"><span class="text-hidden">Full Payment</span></td>
            <td class="border" width="20"><span class="text-hidden">Penalty</span></td>
            <td class="border" ><span class="text-hidden">TOTAL</span></td>
        </tr>

        @foreach($receipt->F56Detailmny as $key => $f56)

            <tr>
                <td class="border text-left" >{{  $f56->owner_name }}</td>
                <td class="border text-center" >{{ ($f56->TDARPX->barangay_name->name) }}</td>
                <td class="border text-center" colspan="2"  >{{ ($f56->TDARPX->tdarpno) }} - {{ ($f56->F56Type->name) }}</td>
                <td class="border text-center" colspan="2" >{{ ($f56->tdrp_assedvalue) }}</td>
                <td class="border text-center" >{{ ($f56->tdrp_assedvalue) }}</td>
                <td class="border text-center" >{{ ($f56->basic_current) }}</td>
                <td class="border text-center rotated_vertical" >{{ ($f56->period_covered) }}</td>
                <td class="border text-center" rowspan="2">{{ ($f56->period_covered) }}</td>
            </tr>

            

        @endforeach

        
               
        <tr>
            <td colspan=5 rowspan="2"  style="border:0px #ffffff00" >
                <table width="100%">
                    <tr>
                        <td >
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
                            </table>
                        </td>
                        <td width="60%">
                            <table width="100%" >
                                <tr>
                                    <td colspan="2" style='text-align:center;color:#ffffff00'>MODE OF PAYMENT</td>
                                </tr>
                                <tr>
                                    <td width="70%" height="15px" class="text-hidden">CASH</td>
                                    <td>P</td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden">CHECK</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden">TW/PMO</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;color:#ffffff00" height="15px" >TOTAL</td>
                                    <td> {{ number_format($total, 2) }}</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="8" class="border" height="20"><span class="text-hidden">TOTAL ></span> {{ number_format($total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" class="border">
                <div style="text-align: center">
                    {{$acctble_officer_name->value}}<BR>
                    {{$acctble_officer_position->value}}
                </div>
                
            </td>
            <td colspan="4" class="border">
                <div style="text-align: center">
                    IMELDA I. MACANES<BR>
                    PROVINCIAL TREASURER
                </div>
            </td>
        </tr>
    </table>
<div class="bg">
</div>

</body>
</html>