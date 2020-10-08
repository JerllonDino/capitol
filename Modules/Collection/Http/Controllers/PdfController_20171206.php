<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use PDF;
use Excel;
use Carbon\Carbon;

use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\CashDivision;
use Modules\Collection\Entities\CashDivisionItems;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\BudgetEstimate;
use Modules\Collection\Entities\F56Type;
use Modules\Collection\Entities\F56Detail;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItems;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\TransactionType;
use Modules\Collection\Entities\RcptCertificate;
use Modules\Collection\Entities\RcptCertificateType;
use Modules\Collection\Entities\ReportOfficers;
use Modules\Collection\Entities\MonthlyProvincialIncome;
use Modules\Collection\Entities\OtherFeesCharges;
class PdfController extends Controller
{
    public function __construct(Request $request)
    {
        $this->base['page_title'] = 'PDF Report';
    }

    private function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public function certificate($id)
    {
         $receipt = Receipt::whereId($id)->get();
        if($receipt[0]->is_many){
             $receipt = Receipt::where('is_many','=',$receipt[0]->is_many)->orderBy('serial_no','asc')->get();
        }
        $this->base['receipts'] =  $receipt ;

        $this->base['cert'] = RcptCertificate::where('col_receipt_id', '=', $id)->first();
        $this->base['OtherFeesCharges'] =OtherFeesCharges::where('receipt_id', '=', $id)->get();
        $day = ltrim(date('d', strtotime($this->base['cert']->date_of_entry)), '0');
        $this->base['ordinal_date'] = $this->ordinal($day);

        $type = $this->base['cert']->col_rcpt_certificate_type_id;
        $this->base['user'] = Session::get('user');

        # Get initial sof user
        $initials = '';
        $name = explode(' ', $this->base['cert']->user);
        foreach ($name as $n) {
            $initials .= $n[0];
        }
        $this->base['initials'] = strtoupper($initials);

        $pdf = new PDF;
        switch ($type) {
            case 1:
                # provincial permit
                $pdf = PDF::loadView('collection::pdf/cert_provincialpermit', $this->base);
                break;
            case 2:
                # transfer tax
                $pdf = PDF::loadView('collection::pdf/cert_transfertax', $this->base);
                break;
            case 3:
                # sand & gravel
                $pdf = PDF::loadView('collection::pdf/cert_sand_gravel', $this->base);
                break;
        }
        $pdf->setPaper('A4', 'portrait');
        return @$pdf->stream();
    }

    public function real_property(Request $request)
    {
        $form_56 = 2;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        

        $receipts = Receipt::where('report_date','>=', $date_start)
        ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', '=', $form_56)
            ->where('col_municipality_id', '=', $request['municipality'])
            ->orderBy('serial_no', 'ASC')
            ->get();

        if (count($receipts) == 0) {
            # No existing transaction
            Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
            return redirect()->route('report.real_property');
        }

        $class_amt = array();
        $classes = F56Type::geT();
        foreach ($classes as $class) {
            $class_amt[$class->id] = array(
                'basic_current' => 0,
                'basic_discount' => 0,
                'basic_previous' => 0,
                'basic_penalty_current' => 0,
                'basic_penalty_previous' => 0,
            );
        }

        foreach ($receipts as $rcpt_index => $receipt) {

            if ($receipt->is_cancelled) {
                continue;
            }

            if (isset($receipt->F56Detail)) {
                $index = $receipt->F56Detail->col_f56_type_id;
                $class_amt[$index]['basic_current'] += $receipt->F56Detail->basic_current;
                $class_amt[$index]['basic_discount'] += $receipt->F56Detail->basic_discount;
                $class_amt[$index]['basic_previous'] += $receipt->F56Detail->basic_previous;
                $class_amt[$index]['basic_penalty_current'] += $receipt->F56Detail->basic_penalty_current;
                $class_amt[$index]['basic_penalty_previous'] += $receipt->F56Detail->basic_penalty_previous;
            }
        }

        
        $date_end = new Carbon($date_end);
        $this->base['f56_type'] = F56Type::get();
        $this->base['date_range'] = date('F d, Y', strtotime($date_start)) .' to '. $date_end->subDay()->format('F d, Y') .'.';
        $this->base['municipality'] = Municipality::whereId($request['municipality'])->first();
        $this->base['receipts'] = $receipts;
        $this->base['class_amt'] = $class_amt;

        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/real_property', $this->base)
            ->setPaper('legal', 'landscape');
        return @$pdf->stream();
    }

    public function real_property_consolidated(Request $request)
    {
        $form_56 = 2;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $receipts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', '=', $form_56)
            ->orderBy('serial_no', 'ASC')
            ->get();

        if (count($receipts) == 0) {
            # No existing transaction
            Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
            return redirect()->route('report.real_property');
        }

        $class_amt = array();
        $classes = F56Type::geT();
        foreach ($classes as $class) {
            $class_amt[$class->id] = array(
                'basic_current' => 0,
                'basic_discount' => 0,
                'basic_previous' => 0,
                'basic_penalty_current' => 0,
                'basic_penalty_previous' => 0,
            );
        }

        foreach ($receipts as $rcpt_index => $receipt) {

            if ($receipt->is_cancelled) {
                continue;
            }

            if (isset($receipt->F56Detail)) {
                $index = $receipt->F56Detail->col_f56_type_id;
                $class_amt[$index]['basic_current'] += $receipt->F56Detail->basic_current;
                $class_amt[$index]['basic_discount'] += $receipt->F56Detail->basic_discount;
                $class_amt[$index]['basic_previous'] += $receipt->F56Detail->basic_previous;
                $class_amt[$index]['basic_penalty_current'] += $receipt->F56Detail->basic_penalty_current;
                $class_amt[$index]['basic_penalty_previous'] += $receipt->F56Detail->basic_penalty_previous;
            }
        }

        $this->base['f56_type'] = F56Type::get();
        $this->base['date_range'] = date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.';
        $this->base['receipts'] = $receipts;
        $this->base['class_amt'] = $class_amt;

        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/real_property_consolidated', $this->base)
            ->setPaper('legal', 'landscape');
        return @$pdf->stream();
    }

    public function real_property_p2(Request $request)
    {
        $form_56 = 2;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $receipts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', '=', $form_56)
            ->orderBy('serial_no', 'ASC')
            ->get();

        if (count($receipts) == 0) {
            # No existing transaction
            Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
            return redirect()->route('report.real_property');
        }

        # get list of transaction types
        $trantypes = [];
        $transaction_types = TransactionType::get();
        foreach($transaction_types as $type) {
            $trantypes[$type->id] = ['name' => $type->name, 'total' => 0];
        }

        # for remittances/deposits
        $remdep = array();
        $municipalities = Municipality::get();
        foreach ($municipalities as $mun) {
            $remdep[$mun->id] = ['name' => $mun->name, 'value' => 0];
        }

        $rcpt_acct_af = array();
        $bank = array();
        $total_paymt = 0;

        foreach ($receipts as $rcpt_index => $receipt) {
            if (!isset($rcpt_acct_af[$receipt->col_serial_id])) {
                $rcpt_acct_af[$receipt->col_serial_id]['serials'] = [];
            }

            array_push($rcpt_acct_af[$receipt->col_serial_id]['serials'], $receipt->serial_no);

            if ($receipt->is_cancelled) {
                continue;
            }

            $items = $receipt->items;
            foreach($items as $item) {

                $bank_row = array();
                if (isset($bank[$receipt->serial_no])){
                    $bank[$receipt->serial_no]['amt'] += $item->value;
                } elseif (in_array($receipt->transaction_type, [2,3])) {
                    $bank_row['bank'] = $receipt->bank_name;
                    $bank_row['check_no'] = $receipt->bank_number;
                    $bank_row['payee'] = $receipt->customer->name;
                    $bank_row['amt'] = $item->value;
                    $bank[$receipt->serial_no] = $bank_row;
                }
            }
        }
        $rcpt_acct = $this->format_sort_af($form_56, $rcpt_acct_af, $date_start, $date_end);

        # This part for remittances/deposits
        $mun_rcpts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('is_cancelled', '<>', 1)
            ->where('af_type', '=', $form_56)
            ->orderBy('serial_no', 'ASC')
            ->get();
        foreach ($mun_rcpts as $mun_rcpt) {
            if (isset($mun_rcpt->F56Detail)) {
                $sub_gross = $mun_rcpt->F56Detail->basic_current + $mun_rcpt->F56Detail->basic_previous + $mun_rcpt->F56Detail->basic_penalty_current + $mun_rcpt->F56Detail->basic_penalty_previous;
                $sub_net = $sub_gross - $mun_rcpt->F56Detail->basic_discount;
                $remdep[$mun_rcpt->col_municipality_id]['value'] += ($sub_net * 2);

                # not ADA
                if ($mun_rcpt->transaction_type != 4) {
                    $total_paymt += ($sub_net * 2);

                }
                $trantypes[$mun_rcpt->transaction_type]['total'] += ($sub_net * 2);
            }
        }

		if ($date_start == $date_end) {
            $this->base['date_range'] = date('F d, Y', strtotime($date_start));
        } else {
            $this->base['date_range'] = date('F d', strtotime($date_start)) .' - '. date('d, Y', strtotime($date_end));
        }
		$this->base['report_start'] = date('F d, Y', strtotime($date_start));

        $this->base['officer_name'] = ReportOfficers::where('name', 'rpt_name')->first();
        $this->base['officer_position'] = ReportOfficers::where('name', 'rpt_position')->first();
        $this->base['receipts'] = $receipts;
        $this->base['rcpt_acct'] = $rcpt_acct;
        $this->base['bank'] = $bank;
        $this->base['trantypes'] = $trantypes;
        $this->base['remdep'] = $remdep;
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        $this->base['total_paymt'] = $total_paymt;
        $this->base['total_in_words'] = $this->convert_number_to_words($total_paymt);

        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/real_property_p2', $this->base)
            ->setPaper('legal', 'portrait');
        return @$pdf->stream();
    }

    public function provincial_income(Request $request)
    {
        $this->base['year'] = $request->year;
        $this->base['month'] = $request->month;

        $this->base['year_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
        $this->base['month_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
        $this->base['month_end'] = Carbon::createFromDate($request->year, ($request->month), 1, 'Asia/Manila');
        $this->base['categories'] = AccountCategory::get();
        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/provincial_income_new', $this->base)
            ->setPaper('legal');
        return @$pdf->stream();
    }

    public function provincial_income_old(Request $request)
    {
		$insurance_premium = 42;
        $misc_bts = 39;
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'year' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('report.provincial_income')
                ->withErrors($validator);
        }

        $this->base['provincial_name'] = ReportOfficers::where('name', 'prov_name')->first();
        $this->base['provincial_position'] = ReportOfficers::where('name', 'prov_position')->first();

        $year = $request['year'];
        $month = $request['month'];
        $this->base['year'] = $year;
        $this->base['categories'] = AccountCategory::all();
        $this->base['start_date'] = date('Y-m-d', strtotime($year.'-'.$month.'-01'));
        $days_in_month = date('t', strtotime($this->base['start_date']));
        $this->base['end_date'] = date('Y-m-d', strtotime($year.'-'.$month.'-'.$days_in_month));
        $this->base['end_prev_month'] = date('Y-m-d', strtotime('-1 day', strtotime($this->base['start_date'])));
        $this->base['date_prevmonth'] = date('M', strtotime($this->base['end_prev_month']));

        $this->base['budget_estimate'] = BudgetEstimate::where('year', '=', $year)->get();

        # No existing budget estimate
        if (!count($this->base['budget_estimate'])) {
            Session::flash('error', ['Please set the budget estimate for the year '. $year .'.']);
            return redirect()->route('report.provincial_income');
        }

        # Gets budget estimate for query year
        $this->base['titles'] = $this->base['subtitles'] = array();
        foreach ($this->base['budget_estimate'] as $be) {
            if ($be->col_acct_title_id != null) {
                $this->base['titles'][$be->col_acct_title_id]['estimate'] = $be->value;
                $this->base['titles'][$be->col_acct_title_id]['startyear_prevmonth'] = 0;
                $this->base['titles'][$be->col_acct_title_id]['total_value'] = 0;
                $this->base['titles'][$be->col_acct_title_id]['actual_value'] = 0;
                $this->base['titles'][$be->col_acct_title_id]['pct_collection'] = 0;
            } elseif($be->col_acct_subtitle_id != null) {
                $this->base['subtitles'][$be->col_acct_subtitle_id]['estimate'] = $be->value;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['startyear_prevmonth'] = 0;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['total_value'] = 0;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['actual_value'] = 0;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['pct_collection'] = 0;
            }elseif($be->col_acct_subtitleitems_id != null){
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['estimate'] = $be->value;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['startyear_prevmonth'] = 0;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['total_value'] = 0;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['actual_value'] = 0;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['pct_collection'] = 0;
            }
        }

        $year_start = date('Y-m-d', strtotime($year.'-01-01'));
        $year_end = date('Y-m-d', strtotime($year.'-12-31'));
        $this->base['date_startyear'] = date('M', strtotime($year_start));

        # Get actual value for Monthly Provincial Income
        $monthly_provincial_income = MonthlyProvincialIncome::where('year', $year)->get();

        foreach ($monthly_provincial_income as $mpi) {
            if ($mpi->col_acct_title_id != null) {
                $type = 'titles';
                $id = $mpi->col_acct_title_id;
            } else {
                $type = 'subtitles';
                $id = $mpi->col_acct_subtitle_id;
            }

            if ($mpi->month == $month) {
                $this->base[$type][$id]['actual_value'] += $mpi->value;
            }

            if (isset($this->base[$type][$id])) {
                $this->base[$type][$id]['total_value'] += $mpi->value;
                $this->base[$type][$id]['startyear_prevmonth'] += $mpi->value;
            }
        }
		#for f56 computation
		$basic_current = $basic_previous = $basic_discount = 0;
		$penalty_current = $penalty_previous = 0;

        # Get start year to previous month values for receipts
        $receipts = Receipt::whereMonth('report_date','=',$month)
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->get();
        foreach ($receipts as $receipt) {
            if ($receipt->af_type == 1) {
                foreach ($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    # for insurance premium
                    if ($id == $insurance_premium) {
                        $this->base[$type][$misc_bts]['startyear_prevmonth'] += 15;
                    }

                    if (isset($this->base[$type][$id])) {
                        $this->base[$type][$id]['startyear_prevmonth'] += $item->share_provincial;
                    }
                }
            } else {
                if (!isset($receipt->F56Detail)) {
                    continue;
                }
				$basic_current += $receipt->F56Detail->basic_current;
				$basic_previous += $receipt->F56Detail->basic_previous;
				$basic_discount += $receipt->F56Detail->basic_discount;
				$penalty_current += $receipt->F56Detail->basic_penalty_current;
				$penalty_previous += $receipt->F56Detail->basic_penalty_previous;
            }
        }
		$basic_current_mun = round($basic_current * .4, 2);
		$basic_current_brgy = round($basic_current * .25, 2);
		$basic_previous_mun = round($basic_previous * .4, 2);
		$basic_previous_brgy = round($basic_previous * .25, 2);
		$basic_discount_mun = round($basic_discount * .4, 2);
		$basic_discount_brgy = round($basic_discount * .25, 2);
		$penalty_current_mun = round($penalty_current * .4, 2);
		$penalty_current_brgy = round($penalty_current * .25, 2);
		$penalty_previous_mun = round($penalty_previous * .4, 2);
		$penalty_previous_brgy = round($penalty_previous * .25, 2);

		$this->base['titles'][2]['startyear_prevmonth'] =
			($basic_current - ($basic_current_mun + $basic_current_brgy)) +
			($basic_previous - ($basic_previous_mun + $basic_previous_brgy)) -
			($basic_discount - ($basic_discount_mun + $basic_discount_brgy));

		$this->base['subtitles'][1]['startyear_prevmonth'] =
			($penalty_current - ($penalty_current_mun + $penalty_current_brgy)) +
			($penalty_previous - ($penalty_previous_mun + $penalty_previous_brgy));

        $this->base['titles'][49]['startyear_prevmonth'] =
			round($basic_current * .5, 2) +
			round($basic_previous * .5, 2) -
			round($basic_discount * .5, 2);
		$this->base['titles'][50]['startyear_prevmonth'] =
			round($penalty_current *.5 , 2) +
			round($penalty_previous *.5 , 2);

		$basic_current = $basic_previous = $basic_discount = 0;
		$penalty_current = $penalty_previous = 0;

        # Get actual values for cash division
        $addtl = CashDivision::whereMonth('date_of_entry','=',$month)
            ->get();

        foreach ($addtl as $addt) {
            foreach ($addt->items as $item) {
                $id =  '';
                $type = '';
                if($item->col_acct_title_id != 0){
                    $type = 'titles';
                     $id =  $item->col_acct_title_id;
                }elseif($item->col_acct_subtitle_id != 0){
                     $type = 'subtitles';
                      $id =  $item->col_acct_subtitle_id;
                }
                if( $id !=  '' &&  $type != ''){
                      $this->base[$type][$id]['startyear_prevmonth'] += $item->share_provincial;
                }
            }
        }


        # Get actual values for receipts
        $receipts = Receipt::whereMonth('report_date','=',$month)
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->get();
        foreach ($receipts as $receipt) {
            if ($receipt->af_type == 1) {
                foreach ($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    # for insurance premium
                    if ($id == $insurance_premium) {
                        $this->base[$type][$misc_bts]['actual_value'] += 15;
                    }

                    if (isset($this->base[$type][$id])) {
                        $this->base[$type][$id]['actual_value'] += $item->share_provincial;
                    }
                }
            } else {
                if (!isset($receipt->F56Detail)) {
                    continue;
                }
				$basic_current += $receipt->F56Detail->basic_current;
				$basic_previous += $receipt->F56Detail->basic_previous;
				$basic_discount += $receipt->F56Detail->basic_discount;
				$penalty_current += $receipt->F56Detail->basic_penalty_current;
				$penalty_previous += $receipt->F56Detail->basic_penalty_previous;
            }
        }
		$basic_current_mun = round($basic_current * .4, 2);
		$basic_current_brgy = round($basic_current * .25, 2);
		$basic_previous_mun = round($basic_previous * .4, 2);
		$basic_previous_brgy = round($basic_previous * .25, 2);
		$basic_discount_mun = round($basic_discount * .4, 2);
		$basic_discount_brgy = round($basic_discount * .25, 2);
		$penalty_current_mun = round($penalty_current * .4, 2);
		$penalty_current_brgy = round($penalty_current * .25, 2);
		$penalty_previous_mun = round($penalty_previous * .4, 2);
		$penalty_previous_brgy = round($penalty_previous * .25, 2);

		$this->base['titles'][2]['actual_value'] =
			($basic_current - ($basic_current_mun + $basic_current_brgy)) +
			($basic_previous - ($basic_previous_mun + $basic_previous_brgy)) -
			($basic_discount - ($basic_discount_mun + $basic_discount_brgy));

		$this->base['subtitles'][1]['actual_value'] =
			($penalty_current - ($penalty_current_mun + $penalty_current_brgy)) +
			($penalty_previous - ($penalty_previous_mun + $penalty_previous_brgy));

        $this->base['titles'][49]['actual_value'] =
			round($basic_current * .5, 2) +
			round($basic_previous * .5, 2) -
			round($basic_discount * .5, 2);
		$this->base['titles'][50]['actual_value'] =
			round($penalty_current *.5 , 2) +
			round($penalty_previous *.5 , 2);

		$basic_current = $basic_previous = $basic_discount = 0;
		$penalty_current = $penalty_previous = 0;
        # Get actual values for cash division
        $addtl = CashDivision::whereMonth('date_of_entry','=',$month)
            ->get();
        foreach ($addtl as $addt) {
            foreach ($addt->items as $item) {
                $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;
                $this->base[$type][$id]['actual_value'] += $item->share_provincial;
            }
        }

        # Get total collection for receipt
        $receipts = Receipt::where('report_date','>=', $year_start)
            ->where('report_date','<=', $year_end)
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->get();
        foreach ($receipts as $receipt) {
            if ($receipt->af_type == 1) {
                foreach ($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    # for insurance premium
                    if ($id == $insurance_premium) {
                        $this->base[$type][$misc_bts]['total_value'] += 15;
                    }

                    if (isset($this->base[$type][$id])) {
                        $this->base[$type][$id]['total_value'] += $item->share_provincial;
                    }

                }
            } else {
                if (!isset($receipt->F56Detail)) {
                    continue;
                }
				$basic_current += $receipt->F56Detail->basic_current;
				$basic_previous += $receipt->F56Detail->basic_previous;
				$basic_discount += $receipt->F56Detail->basic_discount;
				$penalty_current += $receipt->F56Detail->basic_penalty_current;
				$penalty_previous += $receipt->F56Detail->basic_penalty_previous;
            }
        }
		$basic_current_mun = round($basic_current * .4, 2);
		$basic_current_brgy = round($basic_current * .25, 2);
		$basic_previous_mun = round($basic_previous * .4, 2);
		$basic_previous_brgy = round($basic_previous * .25, 2);
		$basic_discount_mun = round($basic_discount * .4, 2);
		$basic_discount_brgy = round($basic_discount * .25, 2);
		$penalty_current_mun = round($penalty_current * .4, 2);
		$penalty_current_brgy = round($penalty_current * .25, 2);
		$penalty_previous_mun = round($penalty_previous * .4, 2);
		$penalty_previous_brgy = round($penalty_previous * .25, 2);

		$this->base['titles'][2]['total_value'] =
			($basic_current - ($basic_current_mun + $basic_current_brgy)) +
			($basic_previous - ($basic_previous_mun + $basic_previous_brgy)) -
			($basic_discount - ($basic_discount_mun + $basic_discount_brgy));

		$this->base['subtitles'][1]['total_value'] =
			($penalty_current - ($penalty_current_mun + $penalty_current_brgy)) +
			($penalty_previous - ($penalty_previous_mun + $penalty_previous_brgy));

        $this->base['titles'][49]['total_value'] =
			round($basic_current * .5, 2) +
			round($basic_previous * .5, 2) -
			round($basic_discount * .5, 2);
		$this->base['titles'][50]['total_value'] =
			round($penalty_current *.5 , 2) +
			round($penalty_previous *.5 , 2);

        # Get total collection for cash division
        $addtl = CashDivision::where('date_of_entry','>=', $year_start)
            ->get();
        foreach ($addtl as $addt) {
            foreach ($addt->items as $item) {
                $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;
                $this->base[$type][$id]['total_value'] += $item->share_provincial;
            }
        }

        # Compute % of collection
        foreach($this->base['titles'] as $i => $title) {
            if ($this->base['titles'][$i]['estimate'] != 0) {
                $this->base['titles'][$i]['pct_collection'] = ($title['total_value'] / $title['estimate']) * 100;
            }
        }

        # Compute % of collection
        foreach($this->base['subtitles'] as $i => $subtitle) {
            if(isset($this->base['subtitles'][$i]['estimate'])){
                if ($this->base['subtitles'][$i]['estimate'] != 0) {
                    $this->base['subtitles'][$i]['pct_collection'] = ($subtitle['total_value'] / $subtitle['estimate']) * 100;
                }
            }

        }

        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/provincial_income', $this->base)
            ->setPaper('legal');
        return @$pdf->stream();
    }

    private function add_titles($category, $titles) {
        foreach ($titles as $title) {
            $category['titles'][$title->name] = array(
                'id' => $title->id,
                'count' => 0,
                'total' => 0,
            );
            $subtitles = $title->subs;
            foreach ($subtitles as $sub) {
                if (!$sub->name) { continue; }
                $category['subtitles'][$sub->name] = array(
                    'id' => $sub->id,
                    'count' => 0,
                    'total' => 0,
                );
            }
        }
        return $category;
    }

    public function collections_deposits(Request $request)
    {
        $form_51 = 1;
        $insurance_premium = 42;
        $amusement = 6;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));

        $receipts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', $form_51)
            ->orderBy('serial_no', 'ASC')
            ->get();



        foreach ($receipts as $i => $r) {
            if(!isset($r->serial)){
                 Session::flash('error', ['A REFERENCE SEIRAL HAS BEEN DELETED!  PLS. CALL ADMIN.']);
                  return redirect()->route('report.collections_deposits');
            }
            if ($r->serial->acct_cat_id != $_GET['type']) {
                unset($receipts[$i]);
            }
        }

        if (count($receipts) == 0) {
            # No existing transaction
            Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
            return redirect()->route('report.collections_deposits');
        }

        # get officer
        $acctble_officer_name = ReportOfficers::whereId(10)->first();
        $acctble_officer_position = ReportOfficers::whereId(11)->first();

        # get list of transaction types
        $total_in_words = 0;
        $trantypes = [];
        $transaction_types = TransactionType::get();
        foreach($transaction_types as $type) {
            $trantypes[$type->id] = ['name' => $type->name, 'total' => 0];
        }



        # get list of accounts
        $accounts = [];
        $receipts_total = [];
        $category = AccountCategory::whereId($request['type'])->first();
        $accounts[$category->name] = array(
            'id' => $category->id,
            'count' => 0,
            'titles' => [],
            'subtitles' => [],
        );
        $accounts[$category->name] = $this->add_titles($accounts[$category->name], $category->group_title);


        # get list of municipalities and barangays
        $amusement_shares = [];
        $shares = [];
        $municipalities = Municipality::get();
        foreach ($municipalities as $mun) {
            $shares[$mun->id] = array(
                'name' => $mun->name,
                'count' => 0,
                'total_share' => 0,
                'barangays' => [],
            );

            $amusement_shares[$mun->id] = array(
                'name' => $mun->name,
                'total_share' => 0,
            );
            $barangays = $mun->barangays;
            foreach ($barangays as $brgy) {
                $shares[$mun->id]['barangays'][$brgy->id] = array(
                    'name' => $brgy->name,
                    'count' => 0,
                    'total_share' => 0,
                );
            }
        }

        # variables for receipt accountability
        $rcpt_acct_af = array();
        $bank = array();

        foreach ($receipts as $rcpt_index => $receipt) {
            if ($receipt->serial->acct_cat_id != $_GET['type']) {
                unset($receipts[$rcpt_index]);
                continue;
            }

            if (!isset($rcpt_acct_af[$receipt->col_serial_id])) {
                $rcpt_acct_af[$receipt->col_serial_id]['serials'] = [];
            }
            array_push($rcpt_acct_af[$receipt->col_serial_id]['serials'], $receipt->serial_no);

            if ($receipt->is_cancelled) {
                continue;
            }
            $total = 0;
            $total_share_mun = 0;
            $total_share_brgy = 0;
            foreach($receipt->items as $item) {
                $category = null;

                if ($item->col_acct_title_id !== 0) {
                    # title
                    $category = $item->acct_title->group->category;
                    $type = 'titles';
                    $name = $item->acct_title->name;
                } else {
                    # subtitle
                    $category = $item->acct_subtitle->title->group->category;
                    $type = 'subtitles';
                    $name = $item->acct_subtitle->name;
                }

                if (isset($accounts[$category->name])) {

                    if (!isset($shares[$receipt->col_municipality_id][$receipt->serial_no])) {
                        if ($receipt->col_municipality_id !== 0) {
                            $shares[$receipt->col_municipality_id][$receipt->serial_no] = 0;
                            $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id][$receipt->serial_no] = 0;
                        }

                        if ($receipt->col_barangay_id != 0) {
                            $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id]['count'] += 1;
                            $shares[$receipt->col_municipality_id]['count'] += 1;
                        }
                    }

                    #amusement shares
                    if ($item->col_acct_title_id == $amusement) {
                        if($receipt->col_municipality_id != 0){
                            $amusement_shares[$receipt->col_municipality_id]['total_share'] += $item->share_municipal;
                        }
                    }

                    # for processing receipts paid via bank
                    $bank_row = array();
                    if (isset($bank[$receipt->serial_no])){
                        $bank[$receipt->serial_no]['amt'] += $item->value;
                    } else {
                        if (in_array($receipt->transaction_type, [2,3])) {
                            $bank_row['bank'] = $receipt->bank_name;
                            $bank_row['check_no'] = $receipt->bank_number;
                            $bank_row['payee'] = $receipt->customer->name;
                            $bank_row['amt'] = $item->value;
                            $bank[$receipt->serial_no] = $bank_row;
                        }
                    }


                    if ($receipt->col_municipality_id !== 0 && $receipt->col_municipality_id != 14) {
                        
                        $shares[$receipt->col_municipality_id]['total_share'] += $item->share_municipal;
                        if(!isset($shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id]['total_share'])){
                           continue;
                        }
                        $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id]['total_share'] += $item->share_barangay;
                        $shares[$receipt->col_municipality_id][$receipt->serial_no] += $item->share_municipal;
                        $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id][$receipt->serial_no] += $item->share_barangay;
                    }

                    # for BTS misc from insurance premium
                    $for_misc = 0;
                    if ($accounts[$category->name][$type][$name]['id'] == $insurance_premium) {
                        $for_misc = 15;
                        if (!isset($accounts[$category->name][$type]['Miscellaneous Income'][$item->receipt->serial_no])) {
                            $accounts[$category->name][$type]['Miscellaneous Income'][$item->receipt->serial_no] = 15;
                        } else {
                            $accounts[$category->name][$type]['Miscellaneous Income'][$item->receipt->serial_no] += 15;
                        }
                        $accounts[$category->name][$type]['Miscellaneous Income']['total'] += 15;
                        $accounts[$category->name][$type]['Miscellaneous Income']['count'] += 1;
                    }

                    $total += $item->value;
                    $accounts[$category->name]['count'] += 1;
                    $accounts[$category->name][$type][$name]['count'] += 1;
                    if (!isset($accounts[$category->name][$type][$name][$item->receipt->serial_no])) {
                        $accounts[$category->name][$type][$name][$item->receipt->serial_no] = $item->share_provincial - $for_misc;
                    } else {
                        $accounts[$category->name][$type][$name][$item->receipt->serial_no] += $item->share_provincial;
                    }

                    if ($receipt->is_cancelled !== 1) {
                        $accounts[$category->name][$type][$name]['total'] += $item->share_provincial - $for_misc;
                        $trantypes[$receipt->transaction_type]['total'] += $item->value;

                        if ($receipt->transaction_type != 4) {
                            $total_in_words += $item->value;
                        }
                    }
                }
            }

            if ($receipt->is_cancelled !== 1) {
                $receipts_total[$receipt->serial_no] = $total;
            }
        }

        $rcpt_acct = $this->format_sort_af($form_51, $rcpt_acct_af, $date_start, $date_end, $_GET['type']);

        $share_columns = 0;
        $total_columns = 2;
        # remove unused accounts
        foreach ($accounts as $i => $account) {
            if ($account['count'] == 0) {
                unset($accounts[$i]);
            }
            # removes unused titles
            foreach ($account['titles'] as $j => $titles) {
                if ($titles['count'] == 0) {
                    unset($accounts[$i]['titles'][$j]);
                } else {
                    $total_columns += 1;
                }
            }

            # removes unused subtitles
            foreach ($account['subtitles'] as $j => $subtitles) {
                if ($subtitles['count'] == 0) {
                    unset($accounts[$i]['subtitles'][$j]);
                } else {
                    $total_columns += 1;
                }
            }
        }
        # remove unused shares
        foreach ($shares as $i => $share) {
            if ($share['count'] == 0) {
                unset($shares[$i]);
            } else {
                $total_columns += 1;
                $share_columns += 1;
            }

            foreach ($share['barangays'] as $j => $brgy) {
                if(isset($brgy['count'])){
                   if ($brgy['count'] == 0 || $brgy['total_share'] == 0) {
                        unset($shares[$i]['barangays'][$j]);
                    } else {
                        $share_columns += 1;
                        $total_columns += 1;
                    } 
                }
                
            }
        }

        #remove unused amusement shares
        foreach ($amusement_shares as $i => $as) {
            if ($as['total_share'] == 0) {
                unset($amusement_shares[$i]);
            }
        }

        if ($date_start == $date_end) {
            $this->base['date_range'] = date('F d, Y', strtotime($date_start));
        } else {

            $date_end = new Carbon($date_end);
            $this->base['date_range'] = date('F d', strtotime($date_start)) .' - '. $date_end->subDay()->format('d, Y') ;
        }
		$this->base['report_start'] = date('F d, Y', strtotime($date_start));

        $this->base['bac_type_1'] = 0;
        $this->base['bac_type_2'] = 0;
        $this->base['bac_type_3'] = 0;

        $this->base['officer_name'] = ReportOfficers::where('name', 'coldep_name')->first();
        $this->base['officer_position'] = ReportOfficers::where('name', 'coldep_position')->first();
        $this->base['trust_fund_officer_name'] = ReportOfficers::where('name', 'trust_fund_officer_name')->first();
        $this->base['trustfund_officer_position'] = ReportOfficers::where('name', 'trustfund_officer_position')->first();
        $this->base['bts_officer_name'] = ReportOfficers::where('name', 'bts_officer_name')->first();
        $this->base['bts_report_officer'] = ReportOfficers::where('name', 'bts_report_officer')->first();
        $this->base['bts_report_officer_position'] = ReportOfficers::where('name', 'bts_report_officer_position')->first();
        $this->base['bese_officer_name'] = ReportOfficers::where('name', 'bts_officer_name')->first();
        $this->base['bese_report_officer'] = ReportOfficers::where('name', 'bts_report_officer')->first();
        $this->base['bese_report_officer_position'] = ReportOfficers::where('name', 'bts_report_officer_position')->first();
        $this->base['total_in_words'] = $this->convert_number_to_words($total_in_words);
        $this->base['date'] = date('F d, Y');
        $this->base['accounts'] = $accounts;
        $this->base['shares'] = $shares;
        $this->base['receipts'] = $receipts;
        $this->base['receipts_total'] = $receipts_total;
        $this->base['total_columns'] = $total_columns;
        $this->base['share_columns'] = $share_columns;
        $this->base['rcpt_acct'] = $rcpt_acct;
        $this->base['trantypes'] = $trantypes;
        $this->base['bank'] = $bank;
        $this->base['fund'] = AccountCategory::whereId($_GET['type'])->first();
        $this->base['acctble_officer_name'] = $acctble_officer_name;
        $this->base['acctble_officer_position'] = $acctble_officer_position;
        $this->base['amusement_shares'] = $amusement_shares;


        if(isset($request['button_pdf'])){
                $paper_size = $this->set_paper_size($total_columns);
                $pdf = PDF::loadView('collection::pdf.collections_deposits', $this->base)
                    ->setPaper($paper_size, 'landscape');
            return $pdf->stream();
        }elseif(isset($request['button_html'])){
            return view('collection::html.collections_deposits')->with('base', $this->base);
        }else{
             $test_c = '';

                Excel::create('DAILY COLLECTIONS REPORT', function($excel) use($test_c) {
                        $excel->sheet('COLLECTIONS REPORT', function($sheet) use($test_c) {
                            // $sheet->loadView('collection::excel.collections_deposits_excelx', $this->base);
                            $sheet->mergeCells('A1:'.$this->letters($this->base['total_columns']+1).'1');
                            $sheet->mergeCells('A2:'.$this->letters($this->base['total_columns']+1).'2');
                            $sheet->mergeCells('A3:'.$this->letters($this->base['total_columns']+1).'3');
                            $sheet->mergeCells('A7:'.$this->letters($this->base['total_columns']+1).'7');

                            $sheet->mergeCells('B4:J4');
                            $sheet->setHeight(1, 16);
                            $sheet->setHeight(2, 16);
                            $sheet->setHeight(3, 16);
                            $sheet->setHeight(4, 16);
                            $sheet->setHeight(5, 16);
                            $sheet->setHeight(6, 1);
                            $sheet->setHeight(7, 16);
                            /*HEADER*/
                            $sheet->cells('A1', function($cell) {
                                $cell->setValue('REPORT OF COLLECTIONS AND DEPOSITS');
                            });
                            $sheet->cells('A2', function($cell) {
                                $cell->setValue('PROVINCIAL GOVERNMENT OF BENGUET');
                            });
                            $sheet->cells('A3', function($cell) {
                                $cell->setValue('OFFICE OF THE PROVINCIAL TREASURER');
                            });
                            $sheet->cells('A4', function($cell) {
                                $cell->setValue('FUND');
                            });
                            $sheet->cells('B4', function($cell) {
                                $cell->setValue($this->base['fund']->name);
                            });
                            $sheet->cells('L4', function($cell) {
                                $cell->setValue('REPORT NO.:'.$_GET['report_no']);
                                $cell->setFontColor('#0f07fc');
                            });
                            $sheet->cells('A5', function($cell) {
                                $cell->setValue('Name of Accountable Officer: '.$this->base['acctble_officer_name']->value.' - '.$this->base['acctble_officer_position']->value.'');
                                $cell->setFontSize(14);
                                $cell->setFontFamily('Arial');
                                $cell->setFontWeight('bold');
                            });
                            $sheet->cells('A7', function($cell) {
                                $cell->setValue(' A. Collection');
                                $cell->setFontSize(14);
                                $cell->setFontFamily('Arial');
                                $cell->setFontWeight('bold');
                            });
                            
                            $sheet->cells('A1:'.$this->letters($this->base['total_columns']+1).'3', function($cells) {
                                $cells->setAlignment('center');
                            });

                            $sheet->cells('A1:'.$this->letters($this->base['total_columns']+1).'1', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Roman');
                                $cells->setFontWeight('bold');
                            });

                            $sheet->cells('A2:'.$this->letters($this->base['total_columns']+1).'2', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Times New Roman');
                                $cells->setFontWeight('bold');
                            });
                            $sheet->cells('A3:'.$this->letters($this->base['total_columns']+1).'3', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Times New Roman');
                                $cells->setFontWeight('bold');
                            });

                            $sheet->cells('A4:'.$this->letters($this->base['total_columns']+1).'4', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Arial');
                                $cells->setFontWeight('bold');
                            });

                            $sheet->cells('C10:'.$this->letters($this->base['total_columns']+1).'11', function($cells) {
                                $cells->setAlignment('center');
                            });
                            
                            $sheet->setWidth('A', 15);
                            $sheet->setWidth('B', 30);
                            /* END HEADER*/

                        });
                    })->export('xls');
        }
    }



    public function receipt(Request $request, $id)
    {
        /*
        PLEASE NOTE PAPER CONFIGURATION FOR FORM 51
        1. Print button
        2. Select Printer
        3. Properties
        4. User Defined Paper tab
        5. Form 51 <NEW NAME>
        6. Paper Size
            Width: 4.80 inches
            Height: 8.50 inches
            NO MARGIN
        */

        /*
        PLEASE NOTE PAPER CONFIGURATION FOR FORM 56
        1. Print button
        2. Select Printer
        3. Properties
        4. User Defined Paper tab
        5. Form 56 <NEW NAME>
        6. Paper Size
            Width: 5.10 inches
            Height: 10.30 inches
            NO MARGIN
        */
        $this->base['receipt'] = Receipt::whereId($id)->first();

        # Update printed flag
        $this->base['receipt']->is_printed = 1;
        $this->base['receipt']->save();

        $this->base['total_words'] = 0;
		$items = $this->base['receipt']->items;
		foreach ($items as $item) {
			$this->base['total_words'] += $item->value;
		}
        $this->base['total_words'] = $this->convert_number_to_words(number_format($this->base['total_words'], 2, '.', ''));

        $pdf = new PDF;
        if ($this->base['receipt']->af_type == 1) {
            $customPaper = array(0,0,350,600);
            $pdf = PDF::loadView('collection::pdf/form_51', $this->base)
                ->setPaper($customPaper);
        } else {
            $customPaper = array(0,0,370,690);
            $pdf = PDF::loadView('collection::pdf/form_56', $this->base)
                ->setPaper($customPaper);
        }

        return @$pdf->stream();
    }

    private function convert_number_to_words($number) {
        $num = $this->convert($number);
        $num = (strstr($num, 'pesos')) ? $num : $num . ' pesos';
        return $num .= ' only';
    }

    private function convert($number) {
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

    private function format_sort_af($form, $rcpt_acct_af, $date_start, $date_end, $fund=null) {
        $existing_rcpt = Serial::where('serial_current', '<>', 0)
            ->where('acctble_form_id', '=', $form)
            ->where('acct_cat_id', '=', $fund)
            ->get();

        # list of accountable forms unused
        $rcpt_list = array();
        foreach($existing_rcpt as $er) {
            if (!array_key_exists($er->id, $rcpt_acct_af)) {
                $beginning_qty = $ending_qty = ($er->serial_end - $er->serial_current) + 1;
                $beginning_first = $ending_first = $er->serial_current;
                $beginning_last = $ending_last = $er->serial_end;

                $receipt_qty = '';
                $receipt_first = '';
                $receipt_last = '';

                if ($form == 2) {
                    #form 56
                    $idx = $er->municipality->name . $er->serial_begin;
                } else {
                    # form 51
                    $idx = $er->serial_begin;
                }

                if (strtotime($er->date_added) == strtotime($date_end)) {
                    $receipt_qty = ($er->serial_end - $er->serial_begin) + 1;
                    $receipt_first = $er->serial_begin;
                    $receipt_last = $er->serial_end;

                    $beginning_qty = '';
                    $beginning_first = '';
                    $beginning_last = '';
                }

                $issued_qty = '';
                $issued_first = $issued_last = '';
                $src = (isset($er->municipality)) ? $er->municipality->name : '';
                $rcpt_acct[$idx] = array(
                    'src' => $src,
                    'beg_qty' => $beginning_qty,
                    'beg_from' => $beginning_first,
                    'beg_to' => $beginning_last,
                    'rec_qty' => $receipt_qty,
                    'rec_from' => $receipt_first,
                    'rec_to' => $receipt_last,
                    'iss_qty' => $issued_qty,
                    'iss_from' => $issued_first,
                    'iss_to' => $issued_last,
                    'end_qty' => $ending_qty,
                    'end_from' => $ending_first,
                    'end_to' => $ending_last,
                );
            }
        }


        # list of accountable forms used
        foreach($rcpt_acct_af as $i => $rcpt) {
            $serial = Serial::whereId($i)->first();
            $src = (isset($serial->municipality)) ? $serial->municipality->name : '';
            sort($rcpt['serials']);
            $issued_first = $rcpt['serials'][0];
            $issued_last = $rcpt['serials'][count($rcpt['serials']) - 1];
            $issued_qty = count($rcpt['serials']);

            $receipt_first = '';
            $receipt_last = '';
            $receipt_qty = '';

            $gt_et_start = true;
            $lt_et_end = (strtotime($serial->date_added) == strtotime($date_end));
            if ($form == 2) {
                # For Form 56
                $idx = $serial->municipality->name . $serial->serial_begin;
            } else {
                # Form 51
                $idx = $serial->serial_begin;
            }

            $beginning_first = $issued_first;
            $beginning_last = $serial->serial_end;
            $beginning_qty = ($beginning_last - $beginning_first) + 1;

            if ($gt_et_start && $lt_et_end) {
                $receipt_first = $serial->serial_begin;
                $receipt_last = $serial->serial_end;
                $receipt_qty = ($receipt_last - $receipt_first) + 1;

                $beginning_first = '';
                $beginning_last = '';
                $beginning_qty = '';
            }

            $ending_first = '';
            $ending_last = '';
            $ending_qty = '';
            if ($serial->serial_current != 0) {
                $ending_first = $issued_last + 1;
                $ending_last = $serial->serial_end;
                $ending_qty = ($ending_last - $ending_first) + 1;
            }

            $rcpt_acct[$idx] = array(
                'src' => $src,
                'beg_qty' => $beginning_qty,
                'beg_from' => $beginning_first,
                'beg_to' => $beginning_last,
                'rec_qty' => $receipt_qty,
                'rec_from' => $receipt_first,
                'rec_to' => $receipt_last,
                'iss_qty' => $issued_qty,
                'iss_from' => $issued_first,
                'iss_to' => $issued_last,
                'end_qty' => $ending_qty,
                'end_from' => $ending_first,
                'end_to' => $ending_last,
            );
        }
        // dd($rcpt_acct);
        ksort($rcpt_acct);
        return $rcpt_acct;
    }

    public function set_paper_size($total_columns){
        $paper_size = 'legal';
        if($total_columns >= 22 && $total_columns <= 30){
            $paper_size = 'sra2';
        }elseif ($total_columns >= 31 && $total_columns < 41) {
             $paper_size = 'sra1';
        }
        return $paper_size;
    }

    public function letters($letter = null){
            $letters = [ 
                        1 => "A",
                        2 => "B",
                        3 => "C",
                        4 => "D",
                        5 => "E",
                        6 => "F",
                        7 => "G",
                        8 => "H",
                        9 => "I",
                        10 => "G",
                        11 => "K",
                        12 => "L",
                        13 => "M",
                        14 => "N",
                        15 => "O",
                        16 => "P",
                        17 => "Q",
                        18 => "R",
                        19 => "S",
                        20 => "T",
                        21 => "U",
                        22 => "V",
                        23 => "W",
                        24 => "X",
                        25 => "Y",
                        26 => "Z"
                      ];

            if($letter<=26){
                $l = $letters[$letter];
            }else{
                $bl = round( $letter/26 , 0) ;
                if($letter%26 == 0){
                    $bl = $bl-1;
                }
                $lb = $letters[$bl];
                $ll = ( $letter%26 ) == 0 ? 26 : ( $letter%26 ) ;
                $lll = $letters[$ll];
                $l = $lb.$lll;
            }
            return $l;
    }
}