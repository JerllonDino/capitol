<?php

Class convertx {
   function convert($number) {
        $hyphen      = '-';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'negative ';
        $decimal     = ' pesos and ';
        $dictionary  = array(
            '00'                => 'zero',
            '01'                => 'one',
            '02'                => 'two',
            '03'                => 'three',
            '04'                => 'four',
            '05'                => 'five',
            '06'                => 'six',
            '07'                => 'seven',
            '08'                => 'eight',
            '09'                => 'nine',
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
            $fraction = str_pad($fraction,  2, 0);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction) && $fraction != 0) {
            $string .= $decimal;
            $string .= $this->convert($fraction) . ' centavos';
        }

        return $string;
    }
}


  function convert_number_to_words($number) {
        $convertxx = new convertx;
        $num = $convertxx->convert($number);
        $num = (strstr($num, 'pesos')) ? $num : $num . ' pesos';
        return $num .= ' only';
    }

    function zeroToDash($number, $length)
    {
        return $number > 0 ? number_format($number, $length) : '-';
    }

    function dynamicFontSize($string, $type = 0)
    {
        $string_length = strlen($string);
        $fontSize = "13px";
        // if type is receipt number
        if ($type == 0) {
            switch ($string_length) {
                // 10,000.00
                case 9:
                    $fontSize = "12px";
                    break;
                // 100,000.00
                case 10:
                    $fontSize = "11px";
                    break;
                // 1,000,000.00
                case 12:
                    $fontSize = "10px";
                    break;
                default:
                    break;
            }
        }
        // if type is receipt string
        if ($type == 1) {
            if ($string_length >= 40) {
                $fontSize = "12px";
            }
            if($string_length >= 80){
                $fontSize = "11px";
            }
            if($string_length >= 120){
                $fontSize = "10px";
            }if ($string_length >= 160) {
                $fontSize = "9px";
            }
        }

        return "<span style='font-size:".$fontSize."'>".$string."</span>";
    }

    

 
