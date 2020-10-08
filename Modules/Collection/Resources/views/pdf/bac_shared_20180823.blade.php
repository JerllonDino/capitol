<!DOCTYPE html>
<html>
<head>
    <title>ACCOUNTS REPORT</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 3px;
            margin-right: 3px;
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
                width: 100px;
            }

            .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
            border : 1px solid #000;
            padding: 1px;
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
            position: fixed;

        }
        .header {
             position: fixed;
            top: 0px;
            min-height: 250px;
        }
        .footer {
            bottom:15px;
        }
        .pagenum:before {
            content: counter(page);
        }





    </style>
</head>
<body>

<div class="header">
        <table class="center ">
    <tr>
        <td>
            <img src="{{asset('asset/images/benguet_capitol.png')}}" class="image_logo" />
        </td>
        <td>
        REPORT OF SHARED ACCOUNTS<br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

</div>
<div class="footer">
    Page <span class="pagenum"></span>
</div>


 <div class="col-sm-12" style="top: 135px;">
    <h5>BAC INFRA : {{ $month->format(' F , Y ') }}</h5>
        <div class="form-group col-sm-12 " id="result_bac">
            <table class="table col-sm-12 table-condensed table-hover">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                <?php  $total_bac = 0; ?>
                @foreach($shares['Atok'] as $key => $result)
                    <?php 
                        if(isset($result['name'])){
                            echo '<tr>';
                                    echo '<td>'.$result['name'].'</td>';
                                    echo '<td>'.$result['value'].'</td>';
                            echo '</tr>';
                             $total_bac += $result['value']; 
                        }else{
                            foreach ($result as $key => $value) {
                                echo '<tr>';
                                    echo '<td>'.$result[$key]['name'].'-'.$key.'</td>';
                                    echo '<td>'.$result[$key]['value'].'</td>';
                                echo '</tr>';
                            }
                        }
                    ?>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL BAC INFRA</th>
                        <th class="val" id="total_bac"><?=number_format($total_bac,2)?></th>
                    </tr>
                </tfoot>
            </table>
        </div>


    <div class="form-group col-sm-12 " id="result">
        <h5>Municaplities and Barangay Shares</h5>
        <table class="table col-sm-12 table-condensed table-hover">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                  <?php  $total_mncpal = 0; $total_brgy = 0; $brgy_accounts = array('title6','title55'); $brgy_total = []; $brgy_total_x = []; $mncpl_total = []; ?>
                

                    <?php 
                    dd( $shares );
                    foreach($shares as $key => $result){
                        $brgy_total[$key] = [];
                            if($key != 'Other Cities/Prov.'){
                                echo '<td colspan="2"><strong>'.$key.'</strong></td>';

                                    foreach($result as $subkey => $subresult){
                                        if(!isset($subresult['name'])){
                                            echo '<tr>';
                                                if($key != $subkey  ){
                                                    echo '<td colspan="2"><strong>&nbsp;&nbsp;&nbsp;&nbsp; Brgy.'.$subkey.'</strong></td>';
                                                    $brgy_total[$key][$subkey] = 0;
                                                }
                                                
                                            echo '</tr>';
                                                foreach($subresult as $subkeyx => $subresultx){
                                                   
                                                        if($key == $subkey){
                                                            $total_mncpal += $subresultx['value'];
                                                            echo '<tr>';
                                                            echo '<td>'.$subresultx['name'].'</td>';
                                                            echo '<td>'.number_format($subresultx['value'],2).'</td>';
                                                            echo '</tr>';
                                                            if(!isset($mncpl_total[$subresultx['name']])){
                                                                    $mncpl_total[$subresultx['name']] = 0;
                                                            }
                                                            $mncpl_total[$subresultx['name']] += $subresultx['value'];
                                                        }else{
                                                            if(!in_array($subkeyx, $brgy_accounts)){
                                                                $total_brgy += $subresultx['value'];
                                                                echo '<tr>';
                                                                echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subresultx['name'].'</td>';
                                                                echo '<td>'.number_format($subresultx['value'],2).'</td>';
                                                                echo '</tr>';
                                                                 
                                                                $brgy_total[$key][$subkey] += $subresultx['value'];

                                                                if(!isset($brgy_total_x[$subresultx['name']])){
                                                                        $brgy_total_x[$subresultx['name']] = 0;
                                                                }

                                                                $brgy_total_x[$subresultx['name']] += $subresultx['value'];
                                                            }
                                                        }
                                                }
                                        }

                                    }
                                    if(array_sum($brgy_total[$key]) > 0){
                                                echo '<tr>';
                                                echo '<td><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$key.' BRGY. TOTAL</strong></td>';
                                                echo '<td>'.number_format(array_sum($brgy_total[$key]) , 2).'</td>';
                                                echo '</tr>';
                                           }
                                }
                        }
                    ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" >MUNICAPLITIES</th>
                </tr>

                <?php
                        foreach ($mncpl_total as $key => $value) {
                            echo '<tr>';
                            echo '<th>'.$key.'</th>';
                            echo '<th  class="val text-right">'.number_format($value,2).'</th>';
                            echo '</tr>';
                        }
                ?>
                <tr>
                    <th>TOTAL FOR MUNICAPLITIES</th>
                    <th class="val text-right" id="total"><?=number_format($total_mncpal,2)?></th>
                </tr>

                <tr>
                    <th colspan="2">BARANGAY</th>
                </tr>
                <?php

                        foreach ($brgy_total_x as $key => $value) {
                            echo '<tr>';
                            echo '<th>'.$key.'</th>';
                            echo '<th  class="val text-right" >'.number_format($value,2).'</th>';
                            echo '</tr>';
                        }
                ?>
                 <tr>
                    <th >TOTAL FOR BARANGAY</th>
                    <th  class="val text-right" id="total"><?=number_format($total_brgy,2)?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


 </body>

 </html>