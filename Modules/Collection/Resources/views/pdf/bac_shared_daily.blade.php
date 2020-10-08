<?php
        $bac_infra_ids = [];
        $bac_infra__names = [];
         $valuex = [];
            foreach ($shares['bac_infra'] as $key => $bac_infra) {
               
                foreach ($bac_infra as $keyx => $details) {
                   

                    if(!in_array( $keyx , $bac_infra_ids)){
                        $bac_infra_ids[$keyx] = $keyx;
                        $bac_infra__names[$keyx] =  $details['name'];
                        $valuex[$key][$keyx]= $details['value'];
                    }else{
                          if(!isset($valuex[$key][$keyx]))  $valuex[$key][$keyx] = $details['value'];
                          else $valuex[$key][$keyx] += $details['value'];
                    }
                }
               
            }
            ksort($bac_infra_ids);
            ksort($bac_infra__names);
         $bacc_infra = [];
            foreach ($valuex as $key => $vvalue) {
                    foreach ($bac_infra_ids as $zkeyd => $zvalue) {
                        if(!isset($vvalue[$zvalue])){
                            $bacc_infra[$key][$zvalue] = 0;
                        }else{
                             $bacc_infra[$key][$zvalue] = $vvalue[$zvalue];
                        }
                    }  
            }

            $Municipal_brgy = [];
            foreach ($shares['municipalities'] as $mncplk => $mncpl) {
                if($mncpl->id != 14){
                    $Municipal_brgy[$mncpl->name] = [];
                    foreach ($shares['dates'] as $key => $date) {
                        if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                            foreach ($shares['shares_mncpal'][$mncpl->name][$date['j']] as $key => $value) {
                                if(!array_key_exists($key, $Municipal_brgy[$mncpl->name])){
                                    $Municipal_brgy[$mncpl->name][$key] = [];
                                    if(count($value) > 0){
                                        foreach ($value as $keyt => $valuet) {
                                            if(!in_array($valuet['name'], $Municipal_brgy[$mncpl->name][$key])){
                                                $Municipal_brgy[$mncpl->name][$key][$keyt] = $valuet['name'];
                                            } 
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

$Municipal_share_brgy = [];
foreach ($shares['municipalities'] as $mncplk => $mncpl) {
    if($mncpl->id != 14){
        $Municipal_share_brgy[$mncpl->name] = [];
        foreach ($shares['dates'] as $keys => $date) {
            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                $Municipal_share_brgy[$mncpl->name][$date['j']] = [];
                foreach ($Municipal_brgy[$mncpl->name] as $brgy => $bvalue) {
                    if(!array_key_exists($brgy,$Municipal_share_brgy[$mncpl->name][$date['j']])){
                        $Municipal_share_brgy[$mncpl->name][$date['j']][$brgy] = [];

                        foreach ($Municipal_brgy[$mncpl->name][$brgy] as $ccc => $cvalue) {
                            if(isset( $shares['shares_mncpal'][$mncpl->name][$date['j']][$brgy][$ccc] ))
                                $Municipal_share_brgy[$mncpl->name][$date['j']][$brgy][$ccc] = $shares['shares_mncpal'][$mncpl->name][$date['j']][$brgy][$ccc];
                            else
                                $Municipal_share_brgy[$mncpl->name][$date['j']][$brgy][$ccc] = [];
                        }
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>ACCOUNTS REPORT</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html,body {
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
            font-size: 12px;
        }

        .totals{
            font-weight: bold;
            font-size: 15px;
        }

        .text-right{
            text-align: right;
            padding-right: 8px !important;
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
            bottom:15px;
            position: fixed;
        }
        .pagenum:before {
            content: counter(page);
        }

        tr.center>th{
        	text-align: center;
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
        REPORT ON SHARED ACCOUNTS<br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>

        </td>
        </tr>
    </table>

</div>
<div class="footer">
    Page <span class="pagenum"></span>
</div>


 <div class="col-sm-12" >

    <?php 
        $date_fr = $data['start_datex']->format('F d').' to '.$data['end_datex']->format('F d , Y');

        if($data['start_datex']->format('F d') == $data['end_datex']->format('F d')){
            $date_fr = $data['start_datex']->format('F d , Y');
        }
    ?>
 <h4>BAC INFRA on {{ $date_fr }}</h4>
 <table class="table table-condensed ">
    <thead>
            <tr class="center">
                <th>TYPE</th>
                <?php
                        foreach ($shares['dates'] as $key => $date) {
                            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                                echo '<th>'.$date['j'].'</th>';
                            }
                        }
                ?>
                <th>TOTAL</th>
            </tr>
    </thead>

    <tbody>
    <?php


            $totalxx = [];
            $totaldd = [];
            $totalxd = 0;
            foreach ($bac_infra__names as $key => $value) {
                $totalxx[$key] = 0;
                echo '<tr>';
                    echo '<td><strong>'.$value.'</strong></td>';
                    foreach ($shares['dates'] as $dkey => $date) {
                        if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                            if( isset($bacc_infra[$date['j']]) ){
                             $totalxx[$key] += $bacc_infra[$date['j']][$key];
                             $totaldd[$date['j']][] = $bacc_infra[$date['j']][$key];

                             echo '<td class="text-right">'.number_format($bacc_infra[$date['j']][$key],2).'</td>';
                            }else{
                                echo "<td></td>";
                            }
                            
                        }
                    }
                    $totalxd += $totalxx[$key];
                    echo '<td class="text-right ">'.number_format($totalxx[$key],2).'</td>';
                echo '</tr>';
            }
        ksort($totaldd);
        echo "<tr>";
                 echo '<td class="totals">Total</td>';
                foreach ($shares['dates'] as $dkey => $date) {
                    if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                        if(isset($totaldd[$date['j']]))
                            echo '<td class="text-right totals" >'.number_format(array_sum($totaldd[$date['j']]),2).'</td>';
                        else
                            echo '<td class="totals"></td>';
                    }
                }

                echo '<td class="text-right totals">'.number_format($totalxd,2)."</td>";

        echo "</tr>";


    ?>


        
    </tbody>
 </table>
<br /><br /><br />

<h4>Municipal Shares</h4>
<table class="table table-condensed ">
    <thead>
            <tr class="center">
                <th>Municipality</th>
                <?php
                        foreach ($shares['dates'] as $key => $date) {
                            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                                echo '<th>'.$date['j'].'</th>';
                            }
                        }
                ?>
                <th>TOTAL</th>
            </tr>
    </thead>

    <tbody>
        <?php
        $total_r = [];
        $total = 0;
        foreach ($shares['municipalities'] as $mncplk => $mncpl) {
            if($mncpl->id != 14){
                echo '<tr>';
                    echo '<td colspan="'.(count($shares['dates'])+2).'" style="font-size:19px;" >'.$mncpl->name.'</td>';
                echo '</tr>';
                
                foreach ($Municipal_brgy[$mncpl->name] as $mkey => $mvalue) {
                        echo '<tr>';
                        if($mkey == $mncpl->name){
                            echo '<td colspan="'.(count($shares['dates'])+2).'" ><strong>&nbsp;&nbsp;'.$mkey.'</strong></td>';
                        }else{
                            echo '<td colspan="'.(count($shares['dates'])+2).'" ><strong>&nbsp;&nbsp;&nbsp;&nbsp;'.$mkey.'</strong></td>';
                        }
                        echo '</tr>';
                            foreach ($mvalue as $key => $value) {
                                $total_c[$mncpl->name][$mkey][$value] = 0;
                                    echo '<tr>';
                                        echo '<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$value.'</td>';
                                        foreach ($shares['dates'] as $dkey => $date) {
                                            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){

                                                   if(count($Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]) > 0){
                                                        echo '<td class="text-right">'.number_format($Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]['value'],2).'</td>';
                                                         $total_c[$mncpl->name][$mkey][$value] += $Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]['value'];
                                                         $total_r[$date['j']][] = $Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]['value'];
                                                   }else{
                                                        echo '<td></td>';
                                                   }
                                            }
                                        }
                                        echo '<td class="text-right ">'.number_format($total_c[$mncpl->name][$mkey][$value],2)."</td>";
                                         $total += $total_c[$mncpl->name][$mkey][$value];
                                    echo '</tr>';
                            }

                }
            }
        }
        ksort($total_r);
        echo "<tr>";
                 echo '<td class="totals">Total</td>';
                foreach ($shares['dates'] as $dkey => $date) {
                    if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                        if(isset($total_r[$date['j']]))
                            echo '<td class="text-right totals" >'.number_format(array_sum($total_r[$date['j']]),2).'</td>';
                        else
                            echo '<td class="totals"></td>';
                    }
                }

                echo '<td class="text-right totals">'.number_format($total,2)."</td>";

        echo "</tr>";
        ?>
        
    </tbody>
 </table>

</div>
</body>

</html>