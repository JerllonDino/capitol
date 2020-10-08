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
            padding: 1px;
            vertical-align: middle;
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
            background: #f5f5f5;
           font-size: 16px;
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
        <strong>BENGUET PROVINCE , La Trinidad</strong><br/>
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        <br/>
        <strong>REPORT ON CASH DIVISION COLLECTION </strong>
        </td>
        </tr>
    </table>

<div class="footer">
    Page <span class="pagenum"></span>
</div>



@if( $cash_div_type == 'OPAg')
<h3 style="color:#016101; font-weight: bold; margin: 0; padding: 0;" class="text-center"> Monthly Collections on Sales of Agricultural Products and Lodging (OPAg) </h3>
<h3 style="color:#8e0707; font-weight: bold; margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h3>
<table class="table table-bordered" >
    <thead>
        <tr>

            <th rowspan="1" colspan="1" class="text-center">DATE</th>
            <th rowspan="1" colspan="1" class="text-center">REFERENCE</th>
            <th colspan="1" rowspan="1"  class="text-center">SALES</th>
            <th colspan="1" rowspan="1"   class="text-center" >LODGING</th>
            <th rowspan="1"  colspan="1" class="text-center">Totals</th>
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
                       <td>{{  $key }}</td>
                       <td>{{ ($value['sales'][0]->refno)}}</td>
                       @if(isset($value['sales']))
                                        <td class="text-right"> {{ number_format($value['sales'][0]->value,2)}}</td>
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
            <tr>
              <td colspan="2" class="text-center total">TOTAL</td>
              <td class="text-right total">{{number_format($opag_sale_total,2)}}</td>
              <td class="text-right total">{{($opag_lodging_total == 0 ? '-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : number_format($opag_lodging_total,2) )}}</td>
              <td class="text-right total">{{number_format( array_sum($opag_total),2)}}</td>
            </tr>

    </tbody>
</table>

<hr />
@elseif( $cash_div_type == 'PVET')
<h3 style="color:#016101; font-weight: bold; margin: 0; padding: 0;" class="text-center">Monthly Collections on Sales of Veterinary Products and Quarantine Regulatory Fees</h3>
<h3 style="color:#8e0707; font-weight: bold; margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="1" rowspan="2" class="text-center">DATE</th>
            <th colspan="1" rowspan="2" class="text-center">REFERENCE</th>
            <th colspan="3" rowspan="1"  class="text-center" >PVET</th>
            <th colspan="1" rowspan="1"  class="text-center" >PTO</th>
            <th colspan="1" rowspan="2" class="text-center">Totals</th>
        </tr>
        <tr>
            <th  class="text-center"  >SALES</th>
            <th  class="text-center"  >QRFEES</th>
            <th  class="text-center"  >CERT</th>
            <th  class="text-center"  >QRFEES</th>
        </tr>
    </thead>

    <tbody>
      <?php
            $pvet_total = [];
            $pvett61_total = 0;
            $pvett19_total = 0;
            $pvettpto_total = 0;
            $pvetst5_total = 0;

      ?>
       @foreach($pvet as $key => $value)
           @php $pvet_total[$key] = 0;
           @endphp
                   <tr>
                        <td rowspan="{{ count($value[61]) }}">{{  $key }}  </td>
                        @foreach($value[61] as $key61 => $value61)
                                          <td>{{ $key61 }}</td>


                          @if(isset($value['sales']))
                                        @php
                                          dd($value['sales']);
                                        @endphp
                                          <td class="text-right"> {{ number_format($value['sales'][$key61]->value,2)}}</td>
                                           <?php
                                              $pvet_total[$key] += $value['sales'][$key61]->value;
                                              $pvetst5_total += $value['sales'][$key61]->value;
                                            ?>
                         @else
                                          <td></td>
                         @endif

                         @if(isset($value[61]))
                                          <td class="text-right">{{ number_format($value[61]->value,2)}}</td>
                                          <?php
                                              $pvet_total[$key] += $value[61]->value;
                                              $pvett61_total += $value[61][0]->value;
                                           ?>
                         @else
                                          <td></td>
                         @endif

                          @if(isset($value[19]))
                                          <td class="text-right">{{ number_format($value[19][0]->value,2)}}</td>
                                          <?php
                                              $pvet_total[$key] += $value[19][0]->value;
                                              $pvett19_total += $value[19][0]->value;
                                           ?>
                         @else
                                          <td></td>
                         @endif
                       @endforeach

                       {{-- PTO --}}
                        @if(isset($value['pto']))
                                        <td class="text-right" rowspan="{{ count($value[61]) }}">{{ number_format($value['pto'][0]->value,2)}}</td>
                                        <?php
                                            $pvet_total[$key] += $value['pto'][0]->value;
                                            $pvettpto_total += $value['pto'][0]->value;
                                         ?>
                       @else
                                        <td></td>
                       @endif
                        <td class="text-right ">{{ number_format($pvet_total[$key],2)  }}</td>
                    </tr>
            @endforeach
            <tr>
              <td colspan="2" class="text-center total">TOTAL</td>
              <td class="text-right total">{{number_format( ($pvetst5_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvett61_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvett19_total),2)}}</td>
              <td class="text-right total">{{number_format( ($pvettpto_total),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($pvet_total),2)}}</td>
            </tr>

    </tbody>
</table>
<hr />
@elseif( $cash_div_type == 'COLD CHAIN')
<h3 style="color:#016101; font-weight: bold; margin: 0; padding: 0;" class="text-center">Monthly Collections - Benguet Cold Chain Project</h3>
<h3 style="color:#8e0707; font-weight: bold; margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2" colspan="1" class="text-center">DATE</th>
            <th rowspan="2" colspan="1" class="text-center">REFERENCE</th>
            <th rowspan="1" colspan="1"   class="text-center">COLD CHAIN</th>
            <th rowspan="2"  colspan="1" class="text-center">Totals</th>
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
              <td></td>
              <td class="text-center total">TOTAL</td>
              <td class="text-center total"></td>
              <td class="text-right total">{{number_format( array_sum($coldchain_total),2)}}</td>
            </tr>
    </tbody>
</table>

<hr />
@elseif( $cash_div_type == 'CERTIFICATIONS OPP - DOJ')
<h3 style="color:#016101; font-weight: bold; margin: 0; padding: 0;" class="text-center">Monthly Collections - Certifications OPP-DOJ</h3>
<h3 style="color:#8e0707; font-weight: bold; margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h3>
<table class="table table-bordered" >
    <thead>
        <tr>
            <th rowspan="2" colspan="1" class="text-center">DATE</th>
            <th rowspan="2" colspan="1" class="text-center">REFERENCE</th>
            <th rowspan="2" colspan="1"  class="text-center">CERTIFICATIONS</th>
            <th rowspan="2"  colspan="1" class="text-center">Totals</th>
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
              <td colspan="2" class="text-center total">TOTAL</td>
               <td class="text-right total">{{number_format( array_sum($opp_total),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($opp_total),2)}}</td>
            </tr>


    </tbody>
</table>

@elseif( $cash_div_type == 'PROVINCIAL HEALTH OFFICE')
<h3 style="color:#016101; font-weight: bold; margin: 0; padding: 0;" class="text-center">Monthly Collections of the Provincial Health Office</h3>
<h3 style="color:#8e0707; font-weight: bold; margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h3>

<table class="table table-bordered">
    <thead>
        <tr>
                <th rowspan="2">DATE</th>
                <th rowspan="2">HOSPITALS</th>
                <th rowspan="2">PERIOD COVERED</th>
                <th rowspan="1">DRUGS and MEDICINES</th>
                <th rowspan="1">MED/LAB/DEN</th>
                <th rowspan="2">HOSPITAL FEES</th>
                <th rowspan="2">OTHER SERVICES </th>
                <th rowspan="2">AFFILIATION </th>
                <th rowspan="2">TOTALS </th>
        </tr>
        <tr>
            <th>GAIN</th>
            <th>Xray/Suplies</th>
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
                             <td class="text-center">{{ $detail['drugsmeds'][0]->refno }}</td>
                            @elseif(isset($detail['medlabsden']))
                              <td class="text-center">{{ $detail['medlabsden'][0]->refno }}</td>
                            @elseif(isset($detail['hospitals']))
                              <td class="text-center">{{ $detail['hospitals'][0]->refno }}</td>
                            @elseif(isset($detail['hothersrvcs']))
                              <td class="text-center">{{ $detail['hothersrvcs'][0]->refno }}</td>
                            @else
                              <td class="text-right"></td>
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
              <td colspan="2" class="text-center total">TOTAL</td>
              <td class="text-right total"></td>
              <td class="text-right total">{{number_format( array_sum($tot_drugmeds),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_medlabsden),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_hospitals),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($tot_hothersrvcs),2)}}</td>
              <td class="text-right total"></td>
              <?php $tt = array_sum($tot_drugmeds) + array_sum($tot_medlabsden) + array_sum($tot_hospitals) + array_sum($tot_hothersrvcs); ?>
              <td class="text-right total">{{number_format( $tt,2)}}</td>
            </tr>


    </tbody>
</table>

@elseif( $cash_div_type == 'RPT')
<h3 style="color:#016101; font-weight: bold; margin: 0; padding: 0;" class="text-center">Monthly Collections on Real Property Tax</h3>
<h3 style="color:#8e0707; font-weight: bold; margin: 0; padding: 0;"  class="text-center">For the Month of  {{$datex->format('F Y')}}</h3>
<h4> RPT</h4>

<table  class="table table-bordered">
  <thead>
    <tr>
      <th >Date</th>
      <th >Municipalities</th>
      <th > RPT (NET) BASIC {{date("Y")}} </th>
      <th >PENALTIES</th>
      <th >RPT (NET) SEF  {{date("Y")}} </th>
      <th >PENALTIES</th>
      <th >PTR</th>
      <th >PENALTY PTR</th>
      <th >Permit Fees</th>
      <th >Permit Fees Penalties</th>
      <th>SAND & GRAVEL</th>
      <th>MINING TAX</th>
      <th>ACCOUNTABLE FORMS</th>
      <th>TOTALS</th>
    </tr>
  </thead>

<tbody>
  <?php $totals_xx = []; $totals_xxx = []; $totals_rpt_basic = [];  $totals_rpt_basic_penalty = []; $totals_special_educfund = []; $totals_sef_penalty = []; $totals_prof_tax = [];
        $totals_prof_tax_fines = []; $totals_permit_fees = [];    $totals_permit_fees_fines = [];  $totals_tax_sand_gravel = []; $totals_mining_tax = []; $totals_acct_forms = [];
   ?>
  @foreach($mun_rpt as $key => $details)

          <tr>
              <td>{{$key}}</td>
                <?php  $c = 0; ?>
                      @foreach($details as $keyz => $detail)
                              @if($c > 0)
                                  <tr>
                                      <td></td>
                              @endif
                               <?php $totals_xx[$keyz] = 0; ?>
                                  <td>{{$keyz}}</td>

                                  @if( isset($detail['rpt_basic']) )
                                      <td class="text-right">{{ number_format($detail['rpt_basic'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['rpt_basic'][0]->value;
                                            $totals_rpt_basic[] += $detail['rpt_basic'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif

                                  @if( isset($detail['rpt_basic_penalty']) )
                                      <td class="text-right">{{ number_format($detail['rpt_basic_penalty'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['rpt_basic_penalty'][0]->value;
                                            $totals_rpt_basic_penalty[] += $detail['rpt_basic_penalty'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif

                                  @if( isset($detail['special_educfund']) )
                                      <td class="text-right">{{ number_format($detail['special_educfund'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['special_educfund'][0]->value;
                                            $totals_special_educfund[] += $detail['special_educfund'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif


                                  @if( isset($detail['sef_penalty']) )
                                      <td class="text-right">{{ number_format($detail['sef_penalty'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['sef_penalty'][0]->value;
                                            $totals_sef_penalty[] += $detail['sef_penalty'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif


                                  @if( isset($detail['prof_tax']) )
                                      <td class="text-right">{{ number_format($detail['prof_tax'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['prof_tax'][0]->value;
                                            $totals_prof_tax[] += $detail['prof_tax'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif


                                  @if( isset($detail['prof_tax_fines']) )
                                      <td class="text-right">{{ number_format($detail['prof_tax_fines'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['prof_tax_fines'][0]->value;
                                            $totals_prof_tax_fines[] += $detail['prof_tax_fines'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif


                                  @if( isset($detail['permit_fees']) )
                                      <td class="text-right">{{ number_format($detail['permit_fees'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['permit_fees'][0]->value;
                                            $totals_permit_fees[] += $detail['permit_fees'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif

                                  @if( isset($detail['permit_fees_fines']) )
                                      <td class="text-right"> {{ number_format($detail['permit_fees_fines'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['permit_fees_fines'][0]->value;
                                            $totals_permit_fees_fines[] += $detail['permit_fees_fines'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif

                                  @if( isset($detail['tax_sand_gravel']) )
                                      <td class="text-right">{{ number_format($detail['tax_sand_gravel'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['tax_sand_gravel'][0]->value;
                                            $totals_tax_sand_gravel[] += $detail['tax_sand_gravel'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif

                                  @if( isset($detail['mining_tax']) )
                                      <td class="text-right">{{ number_format($detail['mining_tax'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['mining_tax'][0]->value;
                                            $totals_mining_tax[] += $detail['mining_tax'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif

                                  @if( isset($detail['acct_forms']) )
                                      <td class="text-right">{{ number_format($detail['acct_forms'][0]->value,2) }}</td>
                                      <?php $totals_xx[$keyz] += $detail['acct_forms'][0]->value;
                                            $totals_acct_forms[] += $detail['acct_forms'][0]->value;
                                       ?>
                                  @else
                                      <td class="text-right">0</td>
                                  @endif

                                  <td class="text-right">{{  number_format($totals_xx[$keyz],2) }}</td>


                                  <?php  $c++; ?>
                                  <?php $totals_xxx[] += $totals_xx[$keyz]; ?>

                      @endforeach

          </tr>
    @endforeach

     <tr>
              <td colspan="2" class="text-center total">TOTAL</td>
              <td class="text-right total">{{number_format( array_sum($totals_rpt_basic),2)}}</td>
               <td class="text-right total">{{number_format( array_sum($totals_rpt_basic_penalty),2)}}</td>
                <td class="text-right total">{{number_format( array_sum($totals_special_educfund),2)}}</td>
                 <td class="text-right total">{{number_format( array_sum($totals_sef_penalty),2)}}</td>
                  <td class="text-right total">{{number_format( array_sum($totals_prof_tax),2)}}</td>
                   <td class="text-right total">{{number_format( array_sum($totals_prof_tax_fines),2)}}</td>
                   <td class="text-right total">{{number_format( array_sum($totals_permit_fees),2)}}</td>
                   <td class="text-right total">{{number_format( array_sum($totals_permit_fees_fines),2)}}</td>
                   <td class="text-right total">{{number_format( array_sum($totals_tax_sand_gravel),2)}}</td>
                   <td class="text-right total">{{number_format( array_sum($totals_mining_tax),2)}}</td>
                   <td class="text-right total">{{number_format( array_sum($totals_acct_forms),2)}}</td>
              <td class="text-right total">{{number_format( array_sum($totals_xxx),2)}}</td>
            </tr>



</tbody>

</table>
@endif


</div>

</body>

</html>
