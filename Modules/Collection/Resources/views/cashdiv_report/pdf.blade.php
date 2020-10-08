<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
   <link rel="icon" type="image/png" href="{{asset('asset/images/benguet_capitol.png')}}" />
    <title>ACCOUNTS REPORT</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 10px;
            margin-right: 10px;
        }
        /* class works for table row */
        table tr.page-break{
          page-break-after:always
        }

        .theader>tbody>tr>td{
            border: none;
        }


        /* class works for table */
        table.page-break{
          page-break-after:always
        }

         table tfoot tr.page-break-before{
                page-break-after: always;
         }

        @media print {
         .page-break  { display: block; page-break-before: always; }
        }
         .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

           .image_logo{
                width: 80px;
            }

            .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
            border : 1px solid #000;
            padding: 0px 1px;
            vertical-align: middle;
            font-size: {{$fsize}};

        }


      td.total_group {
            border-top: 3px double #000 !important;
            border-bottom: 2px solid #000 !important;
            font-size: 14px;
            font-weight: bold;
        }
      td.total_categ{
            border-top: 3px double #1d60ef !important;
            border-bottom: 2px solid #1d60ef !important;
            background: #f5f5f5;
           font-size: 16px;
            font-weight: bold;

        }



        .header,
        .footer {
            width: 100%;
            text-align: center;
        }
        .header {
            top: 0px;
            min-height: 250px;
        }
        .footer {
          position: fixed;
            bottom:15px;
        }
        .pagenum:before {
            content: counter(page);
        }

        td.total{
          border-top: 3px double #ccc !important;
            border-bottom: 2px solid #ccc !important;
            background: none;
           font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="header">
    <table class="table theader" border="0" width="100%" cellpadding="0" style="border-collapse: collapse; ">
    <tr>
        <td align="right" width="{{$img_size}}"><img src="{{ asset('asset/images/benguet_capitol.png') }}" style="height: 70px; width: 70px; "></td>
        <td align="left" width="{{$title_size}}" >
             <table border="0" style="width: 100%; border-collapse: collapse;" align="center" cellpadding="0">
                <tbody>
                    <tr><td style="text-align: center; font-family: britannic"><strong>REPUBLIC OF THE PHILIPPINES</strong></td></tr>
                    <tr><td style="text-align: center;">PROVINCE OF BENGUET</td></tr>
                    <tr><td style="text-align: center; font-weight: bold;">OFFICE OF THE PROVINCIAL TREASURER</td></tr>
                </tbody>
            </table>
        </td>
        <td width="25%"></td>
    </tr>
</table>

</div>

<div class="footer">
    Page <span class="pagenum"></span>
</div>

@if( $cash_div_type == 'OPAg')
<h4 style="margin: 0; padding: 0;" class="text-center"> Monthly Collections on Sales of Agricultural Products and Lodging (OPAg) </h4>
<h4 style="margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h4>
<br />
<table class="table table-bordered"  style="width: 80%; margin:0 auto;" >
    <thead>
        <tr>

            <th rowspan="1" colspan="1" class="text-center">DATE</th>
            <th rowspan="1" colspan="1" class="text-center">REFERENCE</th>
            <th colspan="1" rowspan="1"  class="text-center">SALES</th>
            <th colspan="1" rowspan="1"   class="text-center" >LODGING</th>
            <th rowspan="1"  colspan="1" class="text-center">TOTAL</th>
        </tr>

    </thead>

    <tbody>
      <?php $opag_sale_total = 0;
            $opag_lodging_total = 0;
            $opag_total = [];
      ?>

      @foreach($opag as $key => $value)
       <?php $opag_total[$key] = 0; ?>
           <tr>
               <td>{{ $key }}</td>
               <td>{{ ($value['sales'][0]->refno)}}</td>
               @if(isset($value['sales']))
                  <td class="text-right">{{ number_format($value['sales'][0]->value,2)}}</td>
                   <?php
                      $opag_total[$key] += $value['sales'][0]->value;
                      $opag_sale_total += $value['sales'][0]->value;
                    ?>
               @else
                  <td></td>
               @endif

               @if(isset($value['lodging']))
                  <td class="text-right">{{ number_format($value['lodging'][0]->value,2)}}</td>
                  <?php $opag_total[$key] += $value['lodging'][0]->value;
                        $opag_lodging_total += $value['lodging'][0]->value;
                   ?>
               @else
                  <td></td>     
               @endif 

                <td class="text-right ">{{ number_format($opag_total[$key],2)  }}</td>
            </tr>
      @endforeach
            {{-- <tr>
              <td colspan="2" class="text-left total">TOTAL</td>
              <td class="text-right total">{{number_format($opag_sale_total,2)}}</td>
              <td class="text-right total">{{($opag_lodging_total == 0 ? '-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : number_format($opag_lodging_total,2) )}}</td>
              <td class="text-right total">{{number_format( array_sum($opag_total),2)}}</td>
            </tr> --}}

            <tr>
              <td colspan="2" class="text-left total">Sub Total</td>
              <td class="text-right total">{{number_format($opag_sale_total,2)}}</td>
              <td class="text-right total">{{($opag_lodging_total == 0 ? '-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : number_format($opag_lodging_total,2) )}}</td>
              <td class="text-right total">{{number_format( array_sum($opag_total),2)}}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">Adjustments</td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total">{{ !is_null($adjustments) || count($adjustments) > 0 ? number_format($adjustments->sum,2) : 0.00 }}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">TOTAL</td>
              <td class="text-right total">{{ number_format($opag_sale_total,2) }}</td>
              <td class="text-right total">{{ ($opag_lodging_total == 0 ? '-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : number_format($opag_lodging_total,2)) }}</td>
              <td class="text-right total">{{ number_format(!is_null($adjustments) && count($adjustments) > 0 ? array_sum($opag_total) + $adjustments->sum : array_sum($opag_total),2) }}</td>
            </tr>

    </tbody>
</table>

   <table style="margin-left: 60px; margin-top: 20px;">
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;"><strong>{{ (strtoupper($acctble_officer_name->value)) }}</strong></td>
        </tr>
        <tr>
            <td ></td>
            <td colspan="2" style="text-align: center; ">{{ $acctble_officer_position->value }}</td>
        </tr>
    </table>


@elseif( $cash_div_type == 'PVET')
<h4 style="margin: 0; padding: 0;" class="text-center">Monthly Collections on Sales of Veterinary Products and Quarantine Regulatory Fees</h4>
<h4 style="margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h4>
<br />
<table class="table table-bordered" style="margin: 0 20px;">
    <thead>
        <tr>
            <th colspan="1" rowspan="2" class="text-center">DATE</th>
            <th colspan="1" rowspan="2" class="text-center">REFERENCE</th>
            <th colspan="3" rowspan="1"  class="text-center" >PVET</th>
            <th colspan="1" rowspan="1"  class="text-center" >PTO</th>
            <th colspan="1" rowspan="2" class="text-center">Total</th>
        </tr>
        <tr>
            <th  class="text-center"  >SALES</th>
            <th  class="text-center"  >QRFEES</th>
            <th  class="text-center"  >CERT</th>
            <th  class="text-center"  >QRFEES</th>
        </tr>
    </thead>
    <tbody>
      @php
            $pvet_total = [];
            $pvett61_total = 0;
            $pvett19_total = 0;
            $pvettpto_total = 0;
            $pvetst5_total = 0;
            $day = [];
            // dd($pvet);
      @endphp
              @foreach($pvet as $key => $value)
                  @php
                     $day[$key]['total'] = 0;
                      $count = 1;
                      if(isset($value[61])) {
                        foreach ($value[61] as $keyx => $value61) {
                          $day[$key]['pto'] = 0;
                          if($count == count($value[61])){
                            $day[$key]['pto'] = $value['pto']->value;
                          }
                          $day[$key]['data'][$value61->refno]['sales'] = isset($value['sales'][$keyx]) ? $value['sales'][$keyx]->value : 0 ;
                          $day[$key]['data'][$value61->refno][61] = isset($value[61][$keyx]) ? $value[61][$keyx]->value : 0 ;
                          $day[$key]['data'][$value61->refno][19] = isset($value[61][$keyx]) ? $value[19][$keyx]->value : 0 ;
                          $day[$key]['total'] += $day[$key]['pto'] + $day[$key]['data'][$value61->refno]['sales'] + $day[$key]['data'][$value61->refno][61] + $day[$key]['data'][$value61->refno][19];
                          $pvett61_total += $day[$key]['data'][$value61->refno][61];
                          $pvett19_total += $day[$key]['data'][$value61->refno][19];
                          $pvetst5_total += $day[$key]['data'][$value61->refno]['sales'];
                          $pvettpto_total += $day[$key]['pto'];
                          $pvet_total[$key] = $day[$key]['total'];

                          $count++;
                        }
                      } elseif((isset($value['pto'])) && !isset($value[61]) && !isset($value[19])) {
                        $day[$key]['pto'] = $value['pto']->value;
                        $day[$key]['total'] += $value['pto']->value;
                        $pvettpto_total += $value['pto']->value;
                        $pvet_total[$key] = $value['pto']->value;
                      }
                  @endphp
            @endforeach
              @foreach($day as $d => $value)
                @if(isset($value['data']))
                @php
                  $countx = 0;
                @endphp
                  <tr>
                    <td class="text-center" rowspan="{{count($value['data'])}}">{{ $d }}</td>
                    @foreach($value['data'] as $b => $data)
                      @if($countx > 0)
                        <tr>
                      @endif
                          <td >{{$b}}</td>
                          <td class="text-right">{{$data['sales'] == 0 ? '' : number_format($data['sales'],2)  }}</td>
                          <td class="text-right">{{$data[61] == 0 ? '' : number_format($data[61],2)  }}</td>
                          <td class="text-right">{{$data[19] == 0 ? '' : number_format($data[19],2)  }}</td>
                          @if($countx == 0)
                            <td class="text-right" rowspan="{{count($value['data'])}}">{{ $value['pto'] == 0 ? '' : number_format($value['pto'],2)  }}</td>
                            <td class="text-right" rowspan="{{count($value['data'])}}">{{ $value['total'] == 0 ? '' : number_format($value['total'],2)  }}</td>
                          @endif

                          @php
                            $countx++;
                          @endphp

                          @endforeach
                        </tr>
                @else
                  @php
                    $countx = 0;
                  @endphp
                  <tr>
                    <td class="text-center">{{ $d }}</td>
                      @if($countx > 0)
                        <tr>
                      @endif
                            <td></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            @if($countx == 0)
                              <td class="text-right">{{ $value['pto'] == 0 ? '' : number_format($value['pto'],2)  }}</td>
                              <td class="text-right">{{ $value['total'] == 0 ? '' : number_format($value['total'],2)  }}</td>
                            @endif

                            @php
                                 $countx++;
                            @endphp
                        </tr>
                @endif
              @endforeach

            <tr>
              <td colspan="2" class="text-left total">Sub Total</td>
              <td class="text-right total">{{number_format( ($pvetst5_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvett61_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvett19_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvettpto_total),2)}}</td>
              <td class="text-right total">{{number_format(array_sum($pvet_total),2) }}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">Adjustments</td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total">{{ !is_null($adjustments) || count($adjustments) > 0 ? number_format($adjustments->sum,2) : 0.00 }}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">TOTAL</td>
              <td class="text-right total">{{number_format( ($pvetst5_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvett61_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvett19_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvettpto_total),2)}}</td>
              <td class="text-right total">{{number_format((!is_null($adjustments) && count($adjustments) > 0) ? array_sum($pvet_total) - $adjustments->sum : array_sum($pvet_total),2) }}</td>
            </tr>

    </tbody>
</table>
   <table style="margin-left: 20px; margin-top: 20px;">
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;"><strong>{{ (strtoupper($acctble_officer_name->value)) }}</strong></td>
        </tr>
        <tr>
            <td ></td>
            <td colspan="2" style="text-align: center; ">{{$acctble_officer_position->value }}</td>
        </tr>
    </table>
@elseif( $cash_div_type == 'COLD CHAIN')
<h4 style="margin: 0; padding: 0;" class="text-center">Monthly Collections – Benguet Cold Chain Project</h4>
<br />
<h4 style="margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h4>
<br />
<table class="table table-bordered" style="width: 600px; margin: 0 auto;">
    <thead>
        <tr>
            <th rowspan="2" colspan="1" class="text-center">DATE</th>
            <th rowspan="2" colspan="1" class="text-center">REFERENCE</th>
            <th rowspan="1" colspan="1"   class="text-center">COLD CHAIN</th>
            <th rowspan="2"  colspan="1" class="text-center">TOTAL</th>
        </tr>
        <tr>
            <th  class="text-center" >RENTAL</th>
            {{-- <th  class="text-center" >STORAGE RENTAL</th> --}}
            {{-- <th  class="text-center" >CRATES</th> --}}
        </tr>
    </thead>

    <tbody>
       <?php $coldchain_total= [] ; ?>
       @foreach($coldchain as $key => $value)
       <?php $coldchain_total[$key] = 0; ?>
                   <tr>
                       <td> {{  $key }}</td>
                       <td>{{ ($value[0]->refno)}}</td>
                                        <td  class="text-right">{{ number_format($value[0]->value,2)}}</td>
                                         <?php $coldchain_total[$key] += $value[0]->value; ?>

                        <td  class="text-right">{{ number_format($coldchain_total[$key],2)  }}</td>

                    </tr>
            @endforeach
            
            <tr>
              <td colspan="2"  class="text-left total">Sub Total</td>
              <td class="text-center total"></td>
              <td class="text-right total">{{number_format( array_sum($coldchain_total),2)}}</td>
            </tr>

            <tr>
              <td colspan="2"  class="text-left total">Adjustments</td>
              <td class="text-center total"></td>
              <td class="text-right total">{{ !is_null($adjustments) && count($adjustments) > 0 ? number_format($adjustments->sum,2) : 0.00}}</td>
            </tr>
    
            <tr>
              <td colspan="2"  class="text-left total">TOTAL</td>
              <td class="text-center total"></td>
              <td class="text-right total">{{number_format(!is_null($adjustments) && count($adjustments) > 0 ? array_sum($coldchain_total) - $adjustments->sum : array_sum($coldchain_total),2)}}</td>
            </tr>
    </tbody>
</table>

   <table style="margin-left: 72px; margin-top: 20px;">
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;"><strong>{{ (strtoupper($acctble_officer_name->value)) }}</strong></td>
        </tr>
        <tr>
            <td ></td>
            <td colspan="2" style="text-align: center; ">{{$acctble_officer_position->value }}</td>
        </tr>
    </table>

@elseif( $cash_div_type == 'CERTIFICATIONS OPP - DOJ')
<h4 style="margin: 0; padding: 0;" class="text-center">Monthly Collections - Certifications OPP-DOJ</h4>
<br />
<h4 style="margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h4>
<br />
<table class="table table-bordered" style="margin: 0 auto; width: 600px;" >
    <thead>
        <tr>
            <th rowspan="2" colspan="1" class="text-center">DATE</th>
            <th rowspan="2" colspan="1" class="text-center">REFERENCE</th>
            <th rowspan="2" colspan="1"  class="text-center">CERTIFICATIONS</th>
            <th rowspan="2"  colspan="1" class="text-center">TOTAL</th>
        </tr>
        <tr>
        </tr>
    </thead>

    <tbody>
       <?php $opp_total = []; ?>
       @foreach($opp as $key => $value)
       <?php $opp_total[$key] = 0; ?>
                   <tr>
                       <td>{{  $key }}</td>
                       <td>{{ ($value[0]->refno)}}</td>
                                        <td   class="text-right">{{ number_format($value[0]->value,2)}}</td>
                                         <?php $opp_total[$key] += $value[0]->value; ?>

                        <td class="text-right">{{ number_format($opp_total[$key],2)  }}</td>

                    </tr>
            @endforeach

            <tr>
              <td colspan="2" class="text-left total">Sub Total</td>
              <td class="text-right total">{{number_format( array_sum($opp_total),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($opp_total),2)}}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">Adjustments</td>
              <td class="text-right total"></td>
              <td class="text-right total">{{ number_format((!is_null($adjustments) && count($adjustments) > 0 ? $adjustments->sum : 0.00 ),2) }}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">TOTAL</td>
              <td class="text-right total">{{number_format( array_sum($opp_total),2)}}</td>
              <td class="text-right total">{{number_format(!is_null($adjustments) && count($adjustments) > 0 ? array_sum($opp_total) - $adjustments->sum : array_sum($opp_total),2)}}</td>
            </tr>
    </tbody>
</table>

   <table style="margin-left: 72px; margin-top: 20px;">
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;"><strong>{{ (strtoupper($acctble_officer_name->value)) }}</strong></td>
        </tr>
        <tr>
            <td ></td>
            <td colspan="2" style="text-align: center; ">{{$acctble_officer_position->value }}</td>
        </tr>
    </table>

@elseif( $cash_div_type == 'PROVINCIAL HEALTH OFFICE')
<h4 style="margin: 0; padding: 0;" class="text-center">MONTHLY COLLECTIONS - PROVINCIAL HEALTH OFFICE</h4>
<br />
<h4 style="margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h4>
<br />
<table class="table table-bordered" style="margin: 0 auto; width: 90%;">
    <thead>
        <tr>
                <th rowspan="2" class="text-center">DATE</th>
                <th rowspan="2" class="text-center">HOSPITALS</th>
                <th rowspan="2" class="text-center">PERIOD COVERED</th>
                <th rowspan="1" class="text-center">DRUGS and MEDICINES</th>
                <th rowspan="2" class="text-center">MED/LAB/DEN/Xray SUPPLIES</th>
                <th rowspan="2" class="text-center">HOSPITAL FEES</th>
                <th rowspan="2" class="text-center">OTHER SERVICES </th>
                <th rowspan="2" class="text-center">AFFILIATION </th>
                <th rowspan="2" class="text-center">TOTALS </th>
        </tr>
        <tr>
            <th class="text-center" >GAIN</th>
        </tr>
    </thead>


    <tbody>
<?php $totals = [];  $tot_drugmeds = []; $tot_medlabsden = []; $tot_hospitals = []; $tot_hothersrvcs = [];  ?>
    @foreach($hospitals as $key => $details)
    <tr>
            <td>{{$key}}</td>
                <?php  $c = 0; ?>
                @foreach($details as $keyz => $detail)
                        @if($c > 0)
                            <tr>
                                <td></td>
                        @endif
                         <?php $totals[$keyz] = 0; ?>
                            <td>{{$keyz}}</td>

                            @if( isset($detail['drugsmeds']) )
                             <td class="text-left">{{ strtoupper($detail['drugsmeds'][0]->refno) }}</td>
                            @elseif(isset($detail['medlabsden']))
                              <td class="text-left">{{ strtoupper($detail['medlabsden'][0]->refno) }}</td>
                            @elseif(isset($detail['hospitals']))
                              <td class="text-left">{{ strtoupper($detail['hospitals'][0]->refno) }}</td>
                            @elseif(isset($detail['hothersrvcs']))
                              <td class="text-left">{{ strtoupper($detail['hothersrvcs'][0]->refno) }}</td>
                            @else
                              <td class="text-left"></td>
                            @endif


                             @if( isset($detail['drugsmeds']) )
                                <td class="text-right">{{ number_format($detail['drugsmeds'][0]->value,2) }}</td>
                                <?php $totals[$keyz] += $detail['drugsmeds'][0]->value;
                                      $tot_drugmeds[] += $detail['drugsmeds'][0]->value;
                                 ?>
                            @else
                                <td class="text-right"></td>
                            @endif

                            @if( isset($detail['medlabsden']) )
                                <td class="text-right">{{ number_format($detail['medlabsden'][0]->value,2) }}</td>
                                <?php $totals[$keyz] += $detail['medlabsden'][0]->value;
                                      $tot_medlabsden[] += $detail['medlabsden'][0]->value;
                                 ?>
                            @else
                                <td class="text-right"></td>
                            @endif

                            @if( isset($detail['hospitals']) )
                                <td class="text-right">{{ number_format($detail['hospitals'][0]->value,2) }}</td>
                                <?php $totals[$keyz] += $detail['hospitals'][0]->value;
                                      $tot_hospitals[] += $detail['hospitals'][0]->value;
                                 ?>
                            @else
                                <td class="text-right"></td>
                            @endif

                            @if( isset($detail['hothersrvcs']) )
                                <td class="text-right">{{ number_format($detail['hothersrvcs'][0]->value,2) }}</td>
                                <?php $totals[$keyz] += $detail['hothersrvcs'][0]->value;
                                      $tot_hothersrvcs[] += $detail['hothersrvcs'][0]->value;
                                 ?>
                            @else
                                <td class="text-right"></td>
                            @endif
                                <td class="text-right"></td>
                            <td  class="text-right">{{  number_format($totals[$keyz],2) }}</td>

                    <?php  $c++; ?>
                @endforeach
    </tr>
    @endforeach
            <tr>
              <td colspan="2" class="text-left total">Sub Total</td>
              <td class="text-right total"></td>
              <td class="text-right total">{{number_format( array_sum($tot_drugmeds),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_medlabsden),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_hospitals),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_hothersrvcs),2)}}</td>
              <td class="text-right total"></td>
              <?php $tt = array_sum($tot_drugmeds) + array_sum($tot_medlabsden) + array_sum($tot_hospitals) + array_sum($tot_hothersrvcs); ?>
              <td class="text-right total">{{number_format( $tt,2)}}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">Adjustments</td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total"></td>
              <td class="text-right total">{{ !is_null($adjustments) && count($adjustments) > 0 ? number_format($adjustments->sum,2) : 0.00 }}</td>
            </tr>

            <tr>
              <td colspan="2" class="text-left total">TOTAL</td>
              <td class="text-right total"></td>
              <td class="text-right total">{{number_format( array_sum($tot_drugmeds),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_medlabsden),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_hospitals),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_hothersrvcs),2)}}</td>
              <td class="text-right total"></td>
              <?php $tt = !is_null($adjustments) && count($adjustments) > 0 ? (array_sum($tot_drugmeds) + array_sum($tot_medlabsden) + array_sum($tot_hospitals) + array_sum($tot_hothersrvcs)) - $adjustments->sum : array_sum($tot_drugmeds) + array_sum($tot_medlabsden) + array_sum($tot_hospitals) + array_sum($tot_hothersrvcs); ?>
              <td class="text-right total">{{number_format( $tt,2)}}</td>
            </tr>


    </tbody>
</table>

 <table style="margin-left: 50px; margin-top: 20px;">
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;"><strong>{{ (strtoupper($acctble_officer_name->value)) }}</strong></td>
        </tr>
        <tr>
            <td ></td>
            <td colspan="2" style="text-align: center; ">{{$acctble_officer_position->value }}</td>
        </tr>
    </table>

@elseif( $cash_div_type == 'RPT')
<h4 style="margin: 0; padding: 0;" class="text-center">MONTHLY COLLECTIONS ON REAL PROPERTY TAXES</h4>
<br />
<h4 style="margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h4>

<table  class="table table-bordered" style="width: 90%; margin: 0 auto;">
  <thead>
    <tr>
      <th class="text-center">Date</th>
      <th class="text-center">Municipalities</th>
      <th class="text-center">RPT (Net) Basic {{date("Y")}} </th>
      <th class="text-center">Penalties</th>
      <th class="text-center">Discount</th>
      <th class="text-center">RPT (Net) SEF {{date("Y")}} </th>
      <th class="text-center">Penalties</th>
      <th class="text-center">Discount</th>
      <th class="text-center">Advance</th>
      <th class="text-center">PTR</th>
      <th class="text-center">Penalties PTR</th>
      <th class="text-center">Permit Fees</th>
      <th class="text-center">Permit Fees Penalties</th>
      <th class="text-center">Sand & Gravel</th>
      <th class="text-center">Mining Taxes</th>
      <th class="text-center">Accountable Forms</th>
      <th class="text-center">TOTALS</th>
    </tr>
  </thead>

<tbody>
  <?php 
    $totals_xx = []; $totals_xxx = []; $totals_rpt_basic = [];  $totals_rpt_basic_penalty = []; $totals_special_educfund = []; $totals_sef_penalty = []; $totals_prof_tax = [];
    $totals_prof_tax_fines = []; $totals_permit_fees = [];    $totals_permit_fees_fines = [];  $totals_tax_sand_gravel = []; $totals_mining_tax = []; $totals_acct_forms = [];
    $discount_basic = $discount_sef = $advance = 0; $discount_mun = [];
  ?>{{--dd($mun_rpt)--}}
  @foreach($mun_rpt as $key => $details)
    @foreach($details as $keyz => $detail)
      @if(array_sum($detail) > 0) 
        <tr>
          <td>{{ $key }}</td>
            <?php $c = 0; ?>
        @if($c > 0)
            <tr>
                <td></td>
        @endif
         <?php $totals_xx[$keyz] = 0; ?>
            <td>{{$keyz}}</td>
            @if( isset($detail['rpt_basic']) )
                <td class="text-right">{{ number_format($detail['rpt_basic'],2) }}</td>
                <?php 
                  $totals_xx[$keyz] += $detail['rpt_basic'];
                  $totals_rpt_basic[] += $detail['rpt_basic'];
                ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            @if( isset($detail['rpt_basic_penalty']) )
                <td class="text-right">{{ number_format($detail['rpt_basic_penalty'],2) }}</td>
                <?php 
                  $totals_xx[$keyz] += $detail['rpt_basic_penalty'];
                  $totals_rpt_basic_penalty[] += $detail['rpt_basic_penalty'];
                ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            <!-- discount, basic -->
            @if( isset($detail['discount']) )
                <td class="text-right">{{ number_format($detail['discount_basic'],2) }}</td>
                <?php
                  $totals_xx[$keyz] -= $detail['discount_basic'];
                  $discount_basic += $detail['discount_basic'];
                  $discount_mun[$keyz] =+ $detail['discount_basic'];
                ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            @if( isset($detail['special_educfund']) )
                <td class="text-right">{{ number_format($detail['special_educfund'],2) }}</td>
                <?php 
                  $totals_xx[$keyz] += $detail['special_educfund'];
                  $totals_special_educfund[] += $detail['special_educfund'];
                ?>
            @else
                <td class="text-right">0.00</td>
            @endif


            @if( isset($detail['sef_penalty']) )
                <td class="text-right">{{ number_format($detail['sef_penalty'],2) }}</td>
                <?php 
                  $totals_xx[$keyz] += $detail['sef_penalty'];
                  $totals_sef_penalty[] += $detail['sef_penalty'];
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            <!-- discount, sef -->
            @if( isset($detail['discount']) )
                <td class="text-right">{{ number_format($detail['discount_sef'],2) }}</td>
                <?php
                  $totals_xx[$keyz] -= $detail['discount_sef'];
                  $discount_sef += $detail['discount_sef'];
                  $discount_mun[$keyz] =+ $detail['discount_sef'];
                ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            <!-- advance -->
            @if( isset($detail['advance']) )
                <td class="text-right">{{ number_format($detail['advance'], 2) }}</td>
                <?php
                  $advance += $detail['advance'];
                ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            @if( isset($detail['prof_tax']) )
                <td class="text-right">{{ number_format($detail['prof_tax'][0]->value,2) }}</td>
                <?php $totals_xx[$keyz] += $detail['prof_tax'][0]->value;
                      $totals_prof_tax[] += $detail['prof_tax'][0]->value;
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif


            @if( isset($detail['prof_tax_fines']) )
                <td class="text-right">{{ number_format($detail['prof_tax_fines'][0]->value,2) }}</td>
                <?php $totals_xx[$keyz] += $detail['prof_tax_fines'][0]->value;
                      $totals_prof_tax_fines[] += $detail['prof_tax_fines'][0]->value;
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif


            @if( isset($detail['permit_fees']) )
                <td class="text-right">{{ number_format($detail['permit_fees'][0]->value,2) }}</td>
                <?php $totals_xx[$keyz] += $detail['permit_fees'][0]->value;
                      $totals_permit_fees[] += $detail['permit_fees'][0]->value;
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            @if( isset($detail['permit_fees_fines']) )
                <td class="text-right"> {{ number_format($detail['permit_fees_fines'][0]->value,2) }}</td>
                <?php $totals_xx[$keyz] += $detail['permit_fees_fines'][0]->value;
                      $totals_permit_fees_fines[] += $detail['permit_fees_fines'][0]->value;
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            @if( isset($detail['tax_sand_gravel']) )
                <td class="text-right">{{ number_format($detail['tax_sand_gravel'][0]->value,2) }}</td>
                <?php $totals_xx[$keyz] += $detail['tax_sand_gravel'][0]->value;
                      $totals_tax_sand_gravel[] += $detail['tax_sand_gravel'][0]->value;
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            @if( isset($detail['mining_tax']) )
                <td class="text-right">{{ number_format($detail['mining_tax'][0]->value,2) }}</td>
                <?php $totals_xx[$keyz] += $detail['mining_tax'][0]->value;
                      $totals_mining_tax[] += $detail['mining_tax'][0]->value;
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            @if( isset($detail['acct_forms']) )
                <td class="text-right">{{ number_format($detail['acct_forms'][0]->value,2) }}</td>
                <?php $totals_xx[$keyz] += $detail['acct_forms'][0]->value;
                      $totals_acct_forms[] += $detail['acct_forms'][0]->value;
                 ?>
            @else
                <td class="text-right">0.00</td>
            @endif

            {{-- <td class="text-right">{{  number_format($totals_xx[$keyz],2) }}</td> --}}
            <td class="text-right">{{  number_format($totals_xx[$keyz],2) }}</td>

            <?php  $c++; ?>
            <?php $totals_xxx[] += $totals_xx[$keyz]; ?>
          </tr>
        @endif
      @endforeach
  @endforeach
      <tr>
        <td colspan="2" class="text-left total">Sub Total</td>
        <td class="text-right total">{{number_format( array_sum($totals_rpt_basic),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_rpt_basic_penalty),2)}}</td>
        <td class="text-right total">{{number_format($discount_basic,2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_special_educfund),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_sef_penalty),2)}}</td>
        <td class="text-right total">{{number_format($discount_sef,2)}}</td>
        <td class="text-right total">{{number_format($advance,2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_prof_tax),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_prof_tax_fines),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_permit_fees),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_permit_fees_fines),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_tax_sand_gravel),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_mining_tax),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_acct_forms),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_xxx),2)}}</td>
      </tr>

      <tr>
       <td colspan="2" class="text-left total">Adjustments</td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total"></td>
       <td class="text-right total">{{ !is_null($adjustments) && count($adjustments) > 0 ? number_format($adjustments->sum,2) : 0.00 }}</td>
      </tr>
      <tr>
        <td colspan="2" class="text-left total">TOTAL</td>
        <td class="text-right total">{{number_format( array_sum($totals_rpt_basic),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_rpt_basic_penalty),2)}}</td>
        <td class="text-right total">{{number_format($discount_basic,2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_special_educfund),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_sef_penalty),2)}}</td>
        <td class="text-right total">{{number_format($discount_sef,2)}}</td>
        <td class="text-right total">{{number_format($advance,2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_prof_tax),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_prof_tax_fines),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_permit_fees),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_permit_fees_fines),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_tax_sand_gravel),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_mining_tax),2)}}</td>
        <td class="text-right total">{{number_format( array_sum($totals_acct_forms),2)}}</td>
        <td class="text-right total">{{number_format( !is_null($adjustments) && count($adjustments) > 0 ? array_sum($totals_xxx) - $adjustments->sum : array_sum($totals_xxx),2)}}</td>
      </tr>
</tbody>
</table>

 <table style="margin-left: 50px; margin-top: 20px;">
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;"><strong>{{ (strtoupper($acctble_officer_name->value)) }}</strong></td>
        </tr>
        <tr>
            <td ></td>
            <td colspan="2" style="text-align: center; ">{{ $acctble_officer_position->value }}</td>
        </tr>
    </table>

@endif




</div>

</body>

</html>
