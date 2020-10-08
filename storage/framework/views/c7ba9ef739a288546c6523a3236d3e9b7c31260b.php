<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        html{ margin: 0px; width: 12.10cm; height: 22cm;}
        @page  { margin: 0px; width: 12.10cm; height: 22cm; }
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
            padding-left: 0.75cm;
            position: fixed;
        }

        #payor {
            padding-top: 4cm;
            padding-left:  1.75cm;
            position: fixed;
            max-width: 320px;
        }

        #collection_part {
            padding-top: 6.25cm;
            padding-left: 1cm;
            position: fixed;
            font-size: 0.9em;
            width: 9cm;
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
<?php echo e($total = 0); ?>

</span>

<div class="row">

    <div id="header" class="dbg">

    </div>

    <div id="date" class="dbg">
        <?php if(\Carbon\Carbon::parse($receipt->date_of_entry)->format('m') == 9): ?>
            Sept. <?php echo e(date('d, Y H:i', strtotime($receipt->date_of_entry))); ?>

        <?php else: ?>
            <?php echo e(date('M', strtotime($receipt->date_of_entry))); ?>. <?php echo e(date('d, Y H:i', strtotime($receipt->date_of_entry))); ?>

        <?php endif; ?>
    </div>

    <?php if(strlen($receipt->customer->name) > 64): ?>
    <div id="payor" class="dbg" style="font-size:8;">
    <?php else: ?>
    <div id="payor" class="dbg">
    <?php endif; ?>
        <?php echo e($receipt->customer->name); ?>

    </div>
<?php
$cert_sandgravelprocessed = 0; $cert_abc = 0; $cert_sandgravel = 0;$cert_boulders = 0 ;
$mun_brg = '';
?>
    <div id="collection_part" class="dbg">
        <!-- if type = 3 (sand &gravel) -->
        <?php
            $find_sg = false;
            if(isset($receipt->items)) {
                foreach($receipt->items as $items) {
                    if($items->col_acct_title_id == 4)
                        $find_sg = true;
                }
            }
        ?>
        <?php if($find_sg == true): ?>
            <p><b>Sand and gravel Tax:</b></p>
        <?php endif; ?>
        
        <?php foreach($receipt->items as $item): ?>
        <?php
            if(in_array($item->col_acct_title_id ,[4])){
                $mun_brg = '';
                // $mun_brg = $receipt->barangay->name.' , '.$receipt->municipality->name;

                if(!is_null($receipt->barangay) )
                    $mun_brg .= $receipt->barangay->name;
                $mun_brg .= ' , ';
                if(!is_null($receipt->municipality->name))
                    $mun_brg .= $receipt->municipality->name;
            }
            $total += $item->value ;
        ?>
            <div class="coll_nature dbg">
                <?php   
                    $nnn = str_replace("#br", "<br />&nbsp;&nbsp;", ucwords($item->nature));
                    $nnn = str_replace("__", "&nbsp;&nbsp;",  $nnn );
                ?>
                <?php if(preg_match('/certificat/i', $nnn)): ?>
                    <?php echo $nnn; ?>

                <?php else: ?>
                    <?php if($find_sg == true && $item->col_acct_title_id == 4): ?>
                        -<?php echo $nnn; ?>

                    <?php else: ?>
                        <?php echo $nnn; ?>

                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="coll_amt dbg">
               <?php echo e(number_format($item->value, 2)); ?>

            </div>
        <div class="coll_pad"></div>
        <?php endforeach; ?>
        <br>
         <hr />
        <?php if($mun_brg!= ''): ?>
            <strong>  <?php echo e($mun_brg); ?> </strong>
        <?php endif; ?>
        <?php if($receipt->remarks): ?>
            <hr />
            <?php echo $receipt->remarks; ?>

        <?php endif; ?>

        <?php if($receipt->sgbooklet->count() > 0): ?>
            <hr />
            <strong>BOOKLET</strong><br />
            <?php

                foreach ($receipt->sgbooklet as $sgbooklet => $sgbookletv) {
                    $subsss = $sgbookletv->booklet_end - $sgbookletv->booklet_start;
                    $count_bklt = $subsss/50;
                    echo $sgbookletv->booklet_start.'-'.$sgbookletv->booklet_end.' ( '.round($count_bklt).' )<br />';
                }
            ?>
        <?php endif; ?>
        <hr />
    </div>


    <div id="total" class="dbg">
        Php <?php echo e(number_format($total, 2)); ?>

    </div>


    <div id="total_words" class="dbg">
        <?php echo e(convert_number_to_words(number_format($total, 2, '.', ''))); ?>

    </div>

    <div id="collecting_offcer" class="dbg">
        IMELDA I. MACANES<br/>
        <small>PROVINCIAL TREASURER</small>
    </div>
</div>


</body>
</html>
