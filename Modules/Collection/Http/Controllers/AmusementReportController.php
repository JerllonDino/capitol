<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};

use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\{DB,Session};

use Modules\Collection\Entities\{AccountCategory,Municipality,AllowedMonths,Receipt,ReportOfficers};
use PDF,Excel,Carbon\Carbon;


class AmusementReportController extends Controller
{

   public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $this->base['page_title'] = 'Amusement Report';
        $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }
        return view('collection::ammusements.index')->with('base', $this->base);
    }

    public function gen_report(Request $request){
        $date = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $this->base['datex'] = $date;
        $days = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $end_mnth = $date->endOfMonth()->format('d') ;
        $municpality = Municipality::all();
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        $receipts = [];
        $p_tax = [];
        $p_tax['gtotal_ptax'] = 0;
        $muncpal = [];
          $d = 0;
          $month_p = [];
        for($x=1; $x<=$end_mnth ; $x++){
              $month_x =$days->addDays($d);
              $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
              $month_pxx[$x]['y-m-d'] = $month_x->format('j');
              $d = 1;
              foreach ($municpality as $municpalityx) {

                      $brgys = $municpalityx->barangays()->get();
                      foreach ($brgys as $brgy) {
                           $receiptsx = DB::table('col_receipt')
                           ->select(db::raw('col_receipt.report_date , SUM(value) as value, report_date'))
                          ->leftJoin('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                          ->where('col_receipt_items.col_acct_title_id','6')
                          ->where('col_receipt.is_printed','1')
                          ->where('col_receipt.is_cancelled','0')
                           ->where('col_receipt.col_municipality_id',$municpalityx->id)
                            ->where('col_receipt.col_barangay_id',$brgy->id)
                           ->where('col_receipt.report_date','=',  $month_p[$x]['y-m-d'] )
                           ->groupby('col_receipt.report_date')
                          ->get();



                          if( $receiptsx){
                              $permit_tax = DB::table('col_receipt')
                           ->select(db::raw('col_receipt.report_date , SUM(value) as value, report_date'))
                          ->leftJoin('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                          ->where('col_receipt_items.col_acct_title_id','18')
                           // ->where('col_receipt_items.nature','Proprietors, lessors or operators of amusement places')
                          ->where('col_receipt.is_printed','1')
                          ->where('col_receipt.is_cancelled','0')
                           ->where('col_receipt.col_municipality_id',$municpalityx->id)
                            ->where('col_receipt.col_barangay_id',$brgy->id)
                           ->where('col_receipt.report_date','=',  $month_p[$x]['y-m-d'] )
                           ->groupby('col_receipt.report_date')
                          ->get();
                            $p_tax[$month_pxx[$x]['y-m-d']][$municpalityx->name][$brgy->name]['a_tax'] = $receiptsx[0]->value;
                            if($permit_tax){
                              $p_tax['gtotal_ptax'] += $permit_tax[0]->value;
                              $p_tax[$month_pxx[$x]['y-m-d']][$municpalityx->name][$brgy->name]['p_tax'] = $permit_tax[0]->value;
                            }else{
                              $p_tax[$month_pxx[$x]['y-m-d']][$municpalityx->name][$brgy->name]['p_tax'] = 0;
                            }
                            $muncpal[$municpalityx->name][$brgy->name] = $brgy->name;
                            $receipts[$month_pxx[$x]['y-m-d']][$municpalityx->name][$brgy->name] = $receiptsx;

                          }
                      }
                  }
         }
            $this->base['p_tax'] = $p_tax;
            $this->base['receipts'] = $receipts;
            $this->base['mcpal'] = $muncpal;
              if (isset($request['button_pdf'])) {
                  $pdf = new PDF;
                  $pdf = PDF::loadView('collection::ammusements/pdf', $this->base)
                        ->setPaper('legal', 'portrait');
                  return @$pdf->stream();
              }else{
                   Excel::create('Amusement Report', function($excel) use($receipts) {

                    $excel->sheet('Amusement', function($sheet) use($receipts) {
                        $sheet->loadView('collection::ammusements.excel') ->with('base', $this->base);


                    });

                })->export('xls');
              }



    }

}
