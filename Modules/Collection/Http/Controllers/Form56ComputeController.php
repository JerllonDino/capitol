<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Collection\Entities\F56Settings;
use Modules\Collection\Entities\Customer;
use Modules\Collection\Entities\Form;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItems;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\WeekdayHoliday;
use Modules\Collection\Entities\TransactionType;
use Modules\Collection\Entities\CollectionRate;
use Modules\Collection\Entities\F56Type;
use Modules\Collection\Entities\F56Detail;
use Modules\Collection\Entities\F56TDARP;
use Modules\Collection\Entities\ReceiptItemDetail;
use Modules\Collection\Entities\AdaSettings;
use Modules\Collection\Entities\SandGravelTypes as sg_types;
use Modules\Collection\Entities\SGbooklet;
use Carbon\Carbon;

class Form56ComputeController extends Controller
{
     protected $receipt;

    public function __construct(Request $request, Receipt $receipt)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'FORM56 Land Tax Collections Settings';
        $this->receipt = $receipt;
        $this->base['ada_settings'] = AdaSettings::get();
    }
    

    public function form56_compute_benedict(Request $request){
      $years = $request->p_years;
      $years = explode('-',  $years);

       if(count($years) > 1){
          if($years[0] > $years[1]){
            $xxx = $years[0] - $years[1] +1;
            $y_start = $years[1];
          }else{
             $xxx = $years[1] - $years[0] +1;
             $y_start = $years[0];
          }
       }else{
            $xxx = 1;
            $y_start = $years[0];
       }

       // if(isset($request->isEdit)) {
          // for editing existing receipts, base date on date of entry
          $date = Carbon::parse($request->date_of_entry);
       // } else {
       //    $date = Carbon::now(); 
       // }
        $zb = [];
        $zb['tdrpno'] = $request->tdrpno;
        $zb['type_d'] = $request->type_p;
        $zb['assessed_value'] = $request->assessed_value;

        $computed = [];
        $computed['basic_current'] = 0;
        $computed['basic_discount'] = 0;
        $computed['basic_penalty_current'] = 0;
        $computed['basic_previous'] = 0;
        $computed['basic_penalty_previous'] = 0;
        $computed['month_penalty'] = 0;
        $computed['month_penalty_year'] = [];
       for ($i=0; $i < $xxx  ; $i++) { 
          $year_x  = $y_start + $i;
           $f = '';
           $zb['year_x'] = $year_x;
          if($date->format('Y') ==  $year_x ){
                $f = $this->currentYear($zb, $date);
                $computed['basic_current'] += $f['val_currentyear'];
                $computed['basic_discount'] += $f['val_discount'];
                $computed['basic_penalty_current'] += $f['val_penaltycurrentyear'];
          }else if( $date->format('Y') >  $year_x  ){
                $f = $this->previuosYear($zb, $date);
                $computed['basic_previous'] += $f['val_previousyear'];
                $computed['basic_penalty_previous'] += $f['val_penaltypreviousyear'];
                $computed['month_penalty_year'][$zb['year_x']]['interest'] =  $f['interest'];
                $computed['month_penalty_year'][$zb['year_x']]['diff'] =  $f['diff'];
                $computed['month_penalty_year'][$zb['year_x']]['val_penaltypreviousyear'] =  $f['val_penaltypreviousyear'];
                $computed['month_penalty_year'][$zb['year_x']]['val_previousyear'] =  $f['val_previousyear'];
          }else if( $date->format('Y') <  $year_x ){
                $f = $this->advanceYear($zb, $date);
                $computed['basic_current'] += $f['val_currentyear'];
                $computed['basic_discount'] += $f['val_discount'];
          }
           // $computed['basic_current'] = round( $computed['basic_current'],2,PHP_ROUND_HALF_UP);
           // $computed['basic_discount'] = round( $computed['basic_discount'],2,PHP_ROUND_HALF_UP);
           // $computed['basic_penalty_current'] = round( $computed['basic_penalty_current'],2,PHP_ROUND_HALF_UP);
           // $computed['basic_previous'] = round( $computed['basic_previous'],2,PHP_ROUND_HALF_UP);
           // $computed['basic_penalty_previous'] = round( $computed['basic_penalty_previous'],2,PHP_ROUND_HALF_UP);
          $computed['basic_current'] = round(floatval($computed['basic_current']), 2);
          $computed['basic_discount'] = round(floatval($computed['basic_discount']), 2);
          $computed['basic_penalty_current'] = round(floatval($computed['basic_penalty_current']), 2);
          $computed['basic_previous'] = round(floatval($computed['basic_previous']), 2);
          $computed['basic_penalty_previous'] = round(floatval($computed['basic_penalty_previous']), 2);
          
          $computed['month_penalty'] +=  round(floatval($f['month_penalty']), 2);

          $zb[] = $f;
       }

       return $computed;
    }

    function currentYear($zb, $date_of_entry){
      if(isset($date_of_entry) && $date_of_entry != "") {
        // for editing existing receipts, base date on date of entry
        $date = Carbon::parse($date_of_entry);
      } else {
        $date = Carbon::now();
      }
      $month = $date->format('m');
      $dz = [];
      $dz['year'] = $zb['year_x'];
      $dz['val_currentyear'] = 0;
      $dz['val_discount'] = 0;
      $dz['val_penaltycurrentyear'] = 0;
      $dz['val_previousyear'] = 0;
      $dz['val_penaltypreviousyear'] = 0;
      $dz['interest'] = 0;
      $dz['month_penalty'] = 0;
      $dz['month'] = $month;
      $dz['date_of_entry'] = $date_of_entry;
     
      switch ($zb['type_d']) {
        case '0':
            $dz = $this->fullYear($dz,$zb);
          break;
        case '1':       
            $dz = $this->firstQuarter($dz,$zb);
          break;
        case '2':  
            $dz = $this->secondQuarter($dz,$zb);
          break;
        case '3':      
            $dz = $this->thirdQuarter($dz,$zb);
          break;
        case '4': 
           $dz = $this->fourthQuarter($dz,$zb);
          break;
        case '5':        
          $dz = $this->fullYear($dz,$zb);
          break;
        case '6':       
          $dz = $this->fullYear($dz,$zb);
          break;
        case '7':       
          $dz = $this->fullYear($dz,$zb);
          break;
        case '8':      
          $dz = $this->fullYear($dz,$zb);
          break;
        default:
          break;
      }
      $precision = 3;
      $dz['val_currentyear'] = ($dz['val_currentyear']);
      $dz['val_discount'] = ($dz['val_discount']);
      $dz['val_penaltycurrentyear'] = ($dz['val_penaltycurrentyear']);

      return $dz;
    }

    function fullYear($dz,$zb){
      if($dz['month'] < '04'){
        $dz['val_currentyear'] = $zb['assessed_value'] * .01;
        $dz['val_discount'] = $dz['val_currentyear'] * .08;
      }else{
        $dz['val_currentyear_x'] = ( $zb['assessed_value'] * .01 ) / 4;
        $m_penalty = $dz['month'] * 0.02;
        // $penaltyx = 0;
        // if($dz['month'] >= '04' && $dz['month'] <= '06'){
        //     $penaltyx = $dz['val_currentyear_x'];
        // }elseif ($dz['month'] >= '07' && $dz['month'] <= '09') {
        //     $penaltyx = $dz['val_currentyear_x'] * 2;
        // }elseif ($dz['month'] >= '10' && $dz['month'] <= '12') {
        //     $penaltyx = $dz['val_currentyear_x'] * 3;
        // }

        $penalty_percent = 0;
        $penalty_per_qtr = [];
        
        // exlude current month
        // $rcpt_date = Carbon::now()->format('m-d');
        // $rcpt_month = Carbon::now()->format('m');
        $rcpt_date = Carbon::parse($dz['date_of_entry'])->format('m-d');
        $rcpt_month = Carbon::parse($dz['date_of_entry'])->format('m');
        if($rcpt_date >= Carbon::createFromDate(Carbon::now()->format('Y'), 3, 31)->format('m-d')) {
          // 1st starts Jan 1 - Mar 31
          $penalty_percent += $rcpt_month * 2;
          $penalty_qtr = round(floatval((round(floatval($zb['assessed_value']), 2) * .01)/4), 2) * (($rcpt_month * 2)/100);
          array_push($penalty_per_qtr, round(floatval($penalty_qtr), 2));
        } 
        if($rcpt_date >= Carbon::createFromDate(Carbon::now()->format('Y'), 6, 30)->format('m-d')) {
          // 2nd quarter starts Apr 1 - Jun 30
          $penalty_percent += ($rcpt_month - 3) * 2;
          $penalty_qtr = round(floatval(round(floatval($zb['assessed_value'] * .01), 2)/4), 2) * (($rcpt_month - 3) * 2)/100;
          array_push($penalty_per_qtr, round(floatval($penalty_qtr), 2));
        } 
        if($rcpt_date >= Carbon::createFromDate(Carbon::now()->format('Y'), 9, 30)->format('m-d')) {
          // 3rd quarter starts Jul 1 - Sep 30
          $penalty_percent += ($rcpt_month - 6) * 2;
          $penalty_qtr = round(floatval((round(floatval($zb['assessed_value']), 2) * .01)/4), 2) * (($rcpt_month - 6) * 2)/100;
          array_push($penalty_per_qtr, round(floatval($penalty_qtr), 2));
        } 
        // 4th qtr (mnths 10-12) no penalty     

        $dz['month_penalty'] =  floatval($m_penalty);
        // $dz['val_penaltycurrentyear'] =  $penaltyx *  $m_penalty ;
        // $dz['val_penaltycurrentyear'] = (($zb['assessed_value'] * .01)/4) * ($penalty_percent/100);
        $dz['val_penaltycurrentyear'] = array_sum($penalty_per_qtr);
        $dz['val_currentyear'] = ($zb['assessed_value'] * .01);
      }
      return $dz;
    }

    function perQuarter($dz,$zb){
      $val_currentyear = (   ($zb['assessed_value'] * .01 ) / 4 ) ;
      $val_currentyear = (number_format($val_currentyear, 3+1, '.', ''));

      return $val_currentyear;
    }

    function firstQuarter($dz,$zb){
      if($dz['month'] < '04'){
        $dz['val_currentyear'] = $zb['assessed_value'] * .01;
      }else{
        // $dz['interest'] = ($dz['month']) * 0.02;
        $dz['interest'] = (($zb['assessed_value'] * .01)/4) * .08;
        $dz['val_currentyear'] = $this->perQuarter($dz,$zb) ;
        $dz['val_penaltycurrentyear'] =  round(floatval( round(floatval($dz['val_currentyear']), 2) * round(floatval($dz['interest']), 2)  ), 2);
      }
      return $dz;
    }

    function secondQuarter($dz,$zb){
      $tdarp = F56TDARP::where('tdarpno','=',$zb['tdrpno'])->first();
      $x = $this->firstQuarter($dz,$zb);
      if($dz['month'] < '07'){
        $dz['val_currentyear'] = $zb['assessed_value'] * .01;
        $dz['val_penaltycurrentyear'] = round(floatval($x['val_penaltycurrentyear']), 2) ;
      }else{
        // $dz['interest'] = ($dz['month']) * 0.02;
        $dz['interest'] = (($zb['assessed_value'] * .01)/4) * .22; // 8% (4 months * 0.02) + 14% (7 months * 0.02)
        $dz['val_currentyear'] =  ($zb['assessed_value'] * .01 ) - ( $this->perQuarter($dz,$zb) * 2 ) ;
        $dz['val_penaltycurrentyear'] =  round(floatval( round(floatval($dz['val_currentyear']), 2) * round(floatval($dz['interest']), 2)  ), 2);
      }
      return $dz;
    }


    function thirdQuarter($dz,$zb){
      $tdarp = F56TDARP::where('tdarpno','=',$zb['tdrpno'])->first();
      $x = $this->secondQuarter($dz,$zb);
      if($dz['month'] < '10'){
        $dz['val_currentyear'] = $this->perQuarter($dz,$zb) + $x['val_currentyear'];
        $dz['val_penaltycurrentyear'] = round(floatval($x['val_penaltycurrentyear']), 2) ;
      }else{
        $dz['interest'] = ($dz['month']) * 0.02;
        $dz['interest'] = (($zb['assessed_value'] * .01)/4) * .42; // 8% (4 months * 0.02) + 14% (7 months * 0.02) + 20% (10 months * 0.02)
        // $dz['val_currentyear'] = ($zb['assessed_value'] * .01 ) -  $this->perQuarter($dz,$zb)  ;
        $dz['val_penaltycurrentyear'] =  ( round(floatval($dz['val_currentyear']), 2) * round(floatval($dz['interest']), 2)  ) ;
      }
      return $dz;
    }

    function fourthQuarter($dz,$zb){
      if($dz['month'] <= '12'){
        $x = $this->thirdQuarter($dz,$zb);
        $dz['val_currentyear'] = $this->perQuarter($dz,$zb) + $x['val_currentyear'];
        $dz['val_penaltycurrentyear'] = round(floatval($x['val_penaltycurrentyear']), 2) ;
      }else{
         $this->previuosYear($zb, '');
      }
      return $dz;
    }

    function advanceYear($zb, $date_of_entry){
      if(isset($date_of_entry) && $date_of_entry != "") {
        // for editing existing receipts, base date on date of entry
        $date = Carbon::parse($date_of_entry);
      } else {
        $date = Carbon::now();
      }
      $dz = [];
      $dz['year'] = $zb['year_x'];
      $dz['val_currentyear'] = 0;
      $dz['val_discount'] = 0;
      $dz['val_penaltycurrentyear'] = 0;
      $dz['month_penalty'] = 0;

      $dz['val_currentyear'] = $zb['assessed_value'] * .01;
      $dz['val_discount'] = $dz['val_currentyear'] * .10;

      $precision = 3;
      $dz['val_currentyear'] = ($dz['val_currentyear']);
      $dz['val_discount'] = ($dz['val_discount']);

      return $dz;
    }

    function previuosYear($zb, $date_of_entry){
      if(isset($date_of_entry) && $date_of_entry != "") {
        // for editing existing receipts, base date on date of entry
        $date = Carbon::parse($date_of_entry);
      } else {
        $date = Carbon::now();
      }
      $dz = [];
      $dz['year'] = $zb['year_x'];
      $year_x = '01/01/'.$zb['year_x'];
      $year_x = new Carbon($year_x);

       $dz['month_penalty'] = 0;
       $diff = $date->diffInMonths($year_x);
      $dz['diff']  = $diff + 1;
      if( $dz['diff'] > 36){
        $dz['diff'] = 36;
      }

      $precision = 3;

      // NOTE, based on given chart
      // years 1992 and above, maximum penalty % 0.72 (36*.02)
      // years 1991 and below, maximum penalty % 0.24
      if($year_x->format('Y') <= 1991)
        $dz['interest'] = 0.24;
      else
        $dz['interest'] = $dz['diff'] * 0.02;
      $dz['val_penaltycurrentyear'] = 0;
      $dz['val_penaltypreviousyear'] = 0;

      $dz['val_previousyear'] = $zb['assessed_value'] * .01;
      $dz['val_penaltypreviousyear'] = $dz['val_previousyear'] * $dz['interest'];

      $dz['val_previousyear'] = ($dz['val_previousyear']);
      $dz['val_penaltypreviousyear'] = ($dz['val_penaltypreviousyear']);
     
      return $dz;
      
    }
    

   
}
     