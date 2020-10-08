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

        #date{
            height: 100px;
            background: pink;
            padding-top: 3cm;
        }
    </style>
</head>
<body>
<?php 
$total = 0 ;

?>


<table>
    
    <tr id="date">
        <td>{{ date('F d, Y', strtotime($receipt->date_of_entry)) }}</td>
    </tr>
</table>


</body>
</html>
