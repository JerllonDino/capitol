<!DOCTYPE html>
<html>
<head>

    <title>Receipt</title>
    <style>
        html{ margin: 0px; width: 12.50cm; height: 25.5cm;}
        @page { margin: 0px; 
            size: 25.5cm 12.50cm ;}
        body{
            margin: 40px 0 0 0 ;
            font-size: 0.8em;
            font-family: arial, "sans-serif" !important;
            /*background-image: url({{ URL::asset('form56.png') }});*/
            
        }
        .hidden {
            /*display: none;*/
        }

        .text-hidden{
            color: #FFFF !important;
            /*color: #000 !important;*/
        }

        .border-hidden{
            /*border:1px solid #000 !important;*/
            border: hidden !important;
            border-color: #FFF !important;
        }
        table{
            border-collapse: collapse;
        }
       
        .text-center{
            text-align: center;
        }

        .text-right{
            text-align: right !important;
        }
        .text-left{
            text-align: left;
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

        .vertical-top{
            vertical-align: top;
        }
    </style>
</head>
<body>
    <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -15px;">
        <tr>
            <td colspan=2 rowspan=2 height='15%' style="padding: 0; margin: 0; background: ##a7e57b;">
                <table width="100%" class="border-hidden" style="padding: 0; margin: 0;">
                    <tr >
                        <td style="margin:0" width="15%" ></td>
                        <td style="text-align: center;" width="50%">
                            @if($wmunicipality)
                                <b>{{strtoupper($receipt->municipality->name)}}, BENGUET</b>
                            @endif
                        </td>
                        <td style="padding: 0; margin: 0;">

                        @php
                            $tax_type = '';
                        
                            if(isset($receipt->F56Detail->col_f56_previous_receipt)){
                               $tax_type = $receipt->F56Detail->TDARPX->previousTaxType->previous_tax_name ;    
                            }

                            $prev_date = '';
                            $prev_receipt = '';
                            $prev_year = '';
                            if(isset($receipt->F56Previuos)){
                                $prev_year = $receipt->F56Previuos->col_receipt_year != '0000' ? $receipt->F56Previuos->col_receipt_year : '';
                                $prev_receipt = $receipt->F56Previuos->col_receipt_no != '0' ? $receipt->F56Previuos->col_receipt_no : '';
                                $prev_date =  new Carbon\Carbon($receipt->F56Previuos->col_receipt_date) ;
                                $prev_date = $receipt->F56Previuos->col_receipt_date != '0000-00-00' ? $prev_date->toFormattedDateString() : '';    
                            }
                        @endphp
                            <div style="height:60px;margin-left: 140px; margin-top: -10px; background: ##b480fc;">
                                <table width="95%" style="margin-top:0px;" class="border-hidden">
                                    <tbody>
                                        <tr>
                                            <td colspan=2 height='25px' class="border-hidden text-center" style="font-size: 12px;" >
                                                <!-- PREVIOUS TAX RECEIPT NO. -->
                                                <small>{{ $tax_type }} </small>
                                                {{ ($prev_receipt)  }} 

                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="28px"  width="100" class="border-hidden text-left"  style="font-size: 12px;" >
                                            {{  $prev_date }} 
                                        </td>
                                            <td class="border-hidden text-left" style="font-size: 12px; width:2.7cm;">
                                                <!-- FOR THE YEAR -->
                                             {{ $prev_year }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="18%" class="border-hidden" style="background: ##eda6eb;">


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

                       
                        


             @endphp
            <td class="border-hidden text-right" >
                <!-- DATE -->
            <div style="margin-bottom:13px">
                {{ $date_entry->format('F d, Y') }}
            </div>
            </td>
        </tr>
        <tr>
            <td class="border-hidden text-center" height="20" style="background: ##a7e57b; padding-left: -150px;">
                {{$receipt->customer->name}}
            </td>
            <td class="border-hidden" style="padding-left: -30px;">{{ $total_words }} only</td>
            <td class="border-hidden text-right" style="text-indent: 13px;"><br />{{ number_format($form56['total'], 2) }}</td>
        </tr>
        <tr>
            <td colspan=2 class="border-hidden" height="28">
                <table width="100%" class="">
                    <tr>
                        <td width="12%" class="text-hidden" >Philippine currency, in</td>
                        <td width="7%"><!-- <span style="border:1px solid"></span> -->
                            <input type="checkbox" style="margin: 0; padding-left: 25px; padding-top: 10px; font-size: 12px;" checked="checked"><br>
                            <!-- full<br>
                            installment -->
                        </td>
                        <td width="100%" style="padding-top: 10px; padding-left: 25px;"><span class="text-hidden">payment of REAL PROPERTY TAX upon property(ies) described below for the Calendar Year ></span>{{ $p_calendar_year }} </td>
                    </tr>
                </table>
            </td>
            <td class="border-hidden">
                <input type="checkbox" style="margin: 0; padding-left: 6px; font-size: 12px; padding-top: 11px;" checked="checked"><br>
                <input type="checkbox" style="margin: 0; padding-left: 6px; font-size: 12px;" checked="checked">
                <!-- BASIC TAX<br>
                SPECIAL EDUCATION FUND -->
            </td>
        </tr>
    </table>
    <table width="100%" style="margin: 4px 55px 0 8px; border-color: #ffffff00; background-color: ##42cbf4; position: absolute; top: 130px;" >
        <tr style="text-align:center;">
            <td class="border-hidden" style="width: 3.3cm;"><span class="text-hidden">Name Of <br>DECLARED OWNER</span></td>
            <td class="border-hidden" style="width: 3.3cm;" ><span class="text-hidden">Location<br>No./Street/Barangay</span></td>
            <td class="border-hidden"><span class="text-hidden">LOT<br>BLOCK NO.</span></td>
            <td class="border-hidden"><span class="text-hidden">TAX<br>DEC. NO</span></td>
            <td class="border-hidden"><span class="text-hidden">Land</span></td>
            <td class="border-hidden"><span class="text-hidden">Improvement</span></td>
            <td class="border-hidden"><span class="text-hidden">Total</span></td>
            <td class="border-hidden"><span class="text-hidden">TAX DUE</span></td>
            <td class="border-hidden"><span class="text-hidden">NO.</span></td>
            <td class="border-hidden"><span class="text-hidden">Payment</span></td>
            <td class="border-hidden"><span class="text-hidden">Full Payment</span></td>
            <td class="border-hidden"><span class="text-hidden">Penalty</span></td>
            <td class="border-hidden"><span class="text-hidden">TOTAL</span></td>
        </tr>
        @php
            $count_tr = 0;
            $period_covered  = '';

        @endphp
        @php 
                $count_tdrp = (count($receipt->F56Detailmny));
                $owner = '';

        @endphp
        <tr style="background: ##ef7385;">
            <td class="border-hidden text-left vertical-top" style="height: 155px; ">
            @foreach($form56['tax_decs'] as $key => $f56x)
                {{ $key }}<br />
            @endforeach
            </td>

            <td class="border-hidden text-left vertical-top" >
            @foreach($form56['tax_decs'] as $keyx => $f56x)
                @foreach($f56x as $key => $f56)
                    {{ $f56['barangay_name'] }} {{ $f56['tax_type'] }}<br />
                @endforeach
            @endforeach
            </td>

            <td class="border-hidden text-left vertical-top" style="width: 3.5cm; background: ##689cf2;" colspan="2" >
            @foreach($form56['tax_decs'] as $keyx => $f56x)
                @foreach($f56x as $key => $f56)
                    {{ $f56['tax_dec'] }}<br />
                @endforeach
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 1.5cm; background: ##4cef9b;" >
            @foreach($form56['tax_decs'] as $keyx => $f56x)
                @foreach($f56x as $key => $f56)
                    {{ number_format($f56['tdrp_assedvalue'],2) }}<br />
                @endforeach
            @endforeach
            </td>


            <td class="border-hidden text-left vertical-top" style="width: 1.5cm; background: ##f276c4;" ></td>

            <td class="border-hidden text-right vertical-top" style="width: 3.2cm; background: ##a276c4; position: relative;" >
            @foreach($form56['tax_decs'] as $keyx => $f56x)
                @foreach($f56x as $key => $f56)
                    @foreach($form56['yearly'] as $key => $y)
                        {{ number_format($f56['tdrp_assedvalue'],2) }} &nbsp;&nbsp;&nbsp; {{ number_format($f56['tax_due'],2) }} ({{ ($key) }}) <br />
                    @endforeach
                @endforeach
            @endforeach

            

            </td>

           <!--  <td class="border-hidden text-right vertical-top" style="width: 1.5cm; background: ##3dedde;" >
            @foreach($form56['tax_decs'] as $keyx => $f56x)
                @foreach($f56x as $key => $f56)
                    {{ number_format($f56['tax_due'],2) }}<br />
                @endforeach
            @endforeach
            </td> -->

            <!-- <td class="border-hidden text-left vertical-top" style="max-width: 10px; width: 10px; background: #7ae83e;  position: relative; " >
             
            </td> -->

            <td class="border-hidden text-left vertical-top" style="width: 3cm; background: ##cde25f; text-align: center;">
                <!-- <div style="text-align: center; background-color:#3FBF7F; padding: 0; margin: 0;">
            @foreach($form56['yearly'] as $key => $y)
                    <p style="text-align: center;">( {{ ($key) }} )</p><br />
            @endforeach
             </div> -->
            @foreach($form56['yearly'] as $key => $y)
            <!-- <p style="text-align: center; background-color:##3FBF7F; padding: 0; margin: 0;">( {{ ($key) }} )</p><br /> -->
                <!-- <div style="text-align: center; background-color:##3FBF7F; padding: 0; margin: 0;"> -->
                    BASIC<br />
                    SEF<!-- ({{ ($key) }}) --><br />
                <!-- </div> -->
                    <!-- BASIC<br />
                    SEF<br /> -->
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 1.1cm; background: ##e8aa4e;" >
            @foreach($form56['yearly'] as $key => $y)
                    {{ number_format($y['sef'],2) }}<br />
                    {{ number_format($y['sef'],2) }}<br />
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 1cm; background: ##e56b60;" >
            @foreach($form56['yearly'] as $key => $y)
                    {{ $y['penalty'] == 0 ? '' : number_format($y['penalty'],2) }}  
                    {{ $y['discount'] == 0 ? '' : '('.number_format($y['discount'],2).')' }} 

                    <br />
                    {{ $y['penalty'] == 0 ? '' : $y['penalty'] }}
                    {{ $y['discount'] == 0 ? '' : '('.number_format($y['discount'],2).')' }} 
                    <br />
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 2.1cm; background: ##7fe83e;" >
            @foreach($form56['yearly'] as $key => $y)
                    {{ number_format($y['total'],2) }}<br />
                    {{ number_format($y['total'],2) }}<br />
            @endforeach
            </td>
    </tr>
        
        
               
        <tr class="">
            <td colspan=5 rowspan="2"  style="border:0px #ffffff00" >
                <table width="100%">
                    <tr>
                        <td class="text-hidden">
                            <div style="width:80%">
                                <!-- Payment without pernalty may be made within the periods stated below is by installment -->
                            </div>
                            <table width="90%" style="margin-top: 5px">
                                <tr>
                                    <td width="30%">1st Inst.</td>
                                    <td width="20%">_</td>
                                    <td width="50%"><!-- Jan 1. to Mar. 31 --></td>
                                </tr>
                                <tr>
                                    <td>2nd Inst.</td>
                                    <td>_</td>
                                    <td><!-- Apr. 1 to Jun. 30 --></td>
                                </tr>
                            </table>
                        </td>
                        <td width="60%" class="">
                            <table width="100%" >
                                <tr>
                                    <td colspan="2" class="text-hidden" ><!-- MODE OF PAYMENT --></td>
                                </tr>
                                <tr>
                                    <td width="70%" height="15px" class="text-hidden">CASH</td>
                                    <td>{{ number_format($form56['total'], 2) }}</td>
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
                                    <td  height="15px" class="text-hidden" >TOTAL</td>
                                    <td> {{ number_format($form56['total'], 2) }}</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="6" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -50px;"><span class="text-hidden">TOTAL ></span> {{ number_format($form56['total'], 2) }}</td>
        </tr>
        <tr>
            <td colspan="3" class="border-hidden">
                <div style="text-align: center;">
                    {{$sign ? $acctble_officer_name->value : ''}}
                    <BR>
                    {{$sign ? $acctble_officer_position->value : ''}}
                </div>
                
            </td>
            <td colspan="3" class="border-hidden">
                <div style="text-align: center;">
                    <!-- IMELDA I. MACANES -->
                    {{$sign ? 'IMELDA I. MACANES ' : ''}}
                    <BR>
                    <!-- PROVINCIAL TREASURER -->
                    {{$sign ? 'PROVINCIAL TREASURER ' : ''}}
                </div>
            </td>
        </tr>
    </table>
<div class="bg">
</div>

</body>
</html>