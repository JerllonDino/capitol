<?php
namespace Modules\Collection\Http\Controllers;

use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\AccountGroup;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\SubTitleItems;
use Modules\Collection\Entities\BudgetEstimate;
use Modules\Collection\Entities\CashDivision;
use Modules\Collection\Entities\Customer;
use Modules\Collection\Entities\Form;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\SerialSG;
use Modules\Collection\Entities\SerialSGtype;
use Modules\Collection\Entities\WeekdayHoliday;
use Modules\Collection\Entities\WithCert;
use Modules\Collection\Entities\RcptCertificate;
use Modules\Collection\Entities\RcptCertificateType;
use Modules\Collection\Entities\IsManySerials;
use Modules\Collection\Entities\SandandGravelMnthly;
use Modules\Collection\Entities\MonthlyProvincialIncome;
use Modules\Collection\Entities\F56TDARP;
use Modules\Collection\Entities\ReportOfficers;
use Modules\Collection\Entities\ReportOfficerNew;

use App\Models\User;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

use App\Http\Controllers\BreadcrumbsController;
use App\Http\Controllers\DatatablesController;

use DB;
use Modules\Collection\Entities\OpagCollection;
use Modules\Collection\Entities\PvetCollection;
use Modules\Collection\Entities\RptMunicipalExcel;
use Modules\Collection\Entities\RptMunicipalExcelItems;
use Modules\Collection\Entities\RptMunicipalExcelProvincialShare;
use Modules\Collection\Entities\TransactionType;

class CollectionDatatablesController extends DatatablesController
{

    protected function serial() {
        $form_table = Form::getTableName();
        $serial_table = Serial::getTableName();

        $serials = Serial::join($form_table, $form_table.'.id', '=', $serial_table.'.acctble_form_id')
                                        ->select([$serial_table.'.id', $form_table .'.name',$serial_table.'.serial_begin', $serial_table.'.serial_end', $serial_table.'.date_added',$serial_table.'.serial_current'])
                                     // ->where($serial_table.'.serial_current', '<>', 0)
            ;
        return $serials;
    }

     protected function serialsg() {
        $form_table = SerialSGtype::getTableName();
        $serial_table = SerialSG::getTableName();

        $serials = SerialSG::join($form_table, $form_table.'.id', '=', $serial_table.'.serial_type')
                                        ->select([$serial_table.'.id', $form_table .'.sg_type',$serial_table.'.serial_end', $serial_table.'.serial_start', $serial_table.'.serial_date',$serial_table.'.serial_qty'])
                                    
            ;
        return $serials;
    }

    protected function customer() {
        $customers = Customer::with('customer_type')->select(['id', 'name', 'address','customer_type_id'])->where('deleted_at', null)->orderByRaw('updated_at DESC');
        return $customers;
    }

    protected function colgrp() {
        $category_table = AccountCategory::getTableName();
        $group_table = AccountGroup::getTableName();

        $colgrps = AccountGroup::select([$group_table.'.id', $group_table.'.name as grp_name', $category_table.'.name'])
            ->join($category_table, $category_table.'.id', '=', 'acct_category_id');
        return $colgrps;
    }

    protected function categ() {
        $categs = AccountCategory::select(['id', 'name']);
        return $categs;
    }

    protected function subtitle() {
        $title_table = AccountTitle::getTableName();
        $subtitle_table = AccountSubtitle::getTableName();

        $subtitles = AccountSubtitle::join($title_table, $title_table.'.id', '=', 'col_acct_title_id')
             ->select([$subtitle_table.'.id as subid', $subtitle_table.'.name', $title_table.'.name as title_name']);
        return $subtitles;
    }

    public function subtitleitems(){
         $subtitle_table = AccountSubtitle::getTableName();
          $subtitleitems_table = SubTitleItems::getTableName();

            $SubTitleItems  = SubTitleItems::join($subtitle_table, $subtitle_table.'.id' ,'=' , $subtitleitems_table.'.col_acct_subtitle_id' )
                                            ->select([$subtitleitems_table.'.id',$subtitleitems_table.'.item_name',$subtitle_table.'.name']);

                                            return  $SubTitleItems;
    }

    protected function title()
    {
        $category_table = AccountCategory::getTableName();
        $group_table = AccountGroup::getTableName();
        $title_table = AccountTitle::getTableName();


        $titles = AccountTitle::join($group_table, $group_table.'.id', '=',$title_table.'.acct_group_id')
            ->join($category_table, $category_table.'.id', '=', $group_table.'.acct_category_id')
            ->select([$title_table.'.id', $title_table.'.code', $title_table.'.name', $category_table.'.name as cat_name']);
        return $titles;
    }

    protected function budget()
    {
        $years = BudgetEstimate::select(['year'])
            ->groupBy('year');
        return $years;
    }

    protected function holiday()
    {
        $years = WeekdayHoliday::select(['month', 'year'])
            ->groupBy('year', 'month');
        return $years;
    }

    protected function monthly_provincial_income()
    {
        $years = MonthlyProvincialIncome::select(['month', 'year','auto_generated'])
            ->groupBy('year', 'month');
        return $years;
    }

    protected function monthly_sand_gravel()
    {
        $years = SandandGravelMnthly::select(['month', 'year'])
            ->groupBy('year', 'month');
        return $years;
    }

    protected function customer_rcpt()
    {
        $receipts = Receipt::select(['id', 'serial_no', 'date_of_entry', 'transaction_source'])
            ->where('col_customer_id', '=', $_GET['customer']);
        return $receipts;
    }

    protected function report_officer_new()
    {
        /*$reportofficernew = ReportOfficerNew::select(['id', 'officer_name', 'position_name', 'created_at', 'updated_at', 'deleted_at'])->withTrashed();
        return $reportofficernew;*/

        $tblposition = ReportOfficers::getTableName();
        $tblofficers = ReportOfficerNew::getTableName();

        // $query = ReportOfficerNew::select([
        //     $tblofficers.'.id AS officer_id', 
        //     $tblofficers.'.officer_name', 
        //     $tblofficers.'.position_name', 
        //     $tblofficers.'.created_at AS o_created', 
        //     $tblofficers.'.updated_at AS o_updated', 
        //     $tblofficers.'.deleted_at AS o_deleted',
        //     $tblposition.'.id AS position_id',  
        //     $tblposition.'.position', 
        //     $tblposition.'.created_at AS p_created', 
        //     $tblposition.'.updated_at AS p_updated', 
        //     $tblposition.'.deleted_at AS p_deleted'])
        //     ->leftjoin($tblposition, $tblposition.'.id', '=', $tblofficers.'.position_name')            
        //     ->withTrashed()
        //     ->get();

        $query = ReportOfficerNew::select('officer_name', 'col_new_report_officers.id', 'position')
        ->join('col_report_officer_position', 'col_new_report_officers.position_name', '=', 'col_report_officer_position.id')
        ->where('col_new_report_officers.deleted_at', null)
        ->where('col_report_officer_position.deleted_at', null)
        ->get();

        return $query;
    } 

    protected function report_officer_position()
    {
        // $reportofficer = ReportOfficers::select(['id', 'position', 'created_at', 'updated_at', 'deleted_at'])->withTrashed();
         $reportofficer = ReportOfficersPostion::all();
        return $reportofficer;
    }    

    protected function receipt()
    {
        $show_year = Input::get('show_year');
        $show_mnth = Input::get('show_mnth');
        $show_day = Input::get('show_day');

        $receipt_table = Receipt::getTableName();
        $customer_table = Customer::getTableName();
        $user_table = User::getTableName();
        $form_table = Form::getTableName();
        $Withcert_table = WithCert::getTableName();
        $ismany_table = IsManySerials::getTableName();
        $cert_table = RcptCertificate::getTableName();
        $certtype_table = RcptCertificateType::getTableName();
        $TransactionType = TransactionType::getTableName();
        if($show_mnth != 'ALL'):
            if($show_day == 'ALL' || $show_day == null):
                $receipts = Receipt::select([$cert_table.'.col_rcpt_certificate_type_id',$certtype_table.'.name as cert_typex',$ismany_table.'.col_receipt_serial_parent',$Withcert_table.'.process_status',$Withcert_table.'.cert_type',$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', $receipt_table.'.serial_no', $receipt_table.'.date_of_entry', $customer_table.'.name', $receipt_table.'.is_cancelled', $receipt_table.'.is_printed',$receipt_table.'.report_date','col_pc_settings.pc_ip', $ismany_table.'.col_serials', $TransactionType . '.name as transaction_type'])
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->where('transaction_source', '=', 'receipt')
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($Withcert_table, $Withcert_table.'.trans_id', '=', $receipt_table.'.id')
                ->leftjoin($ismany_table, $ismany_table.'.id', '=', $receipt_table.'.is_many')
                ->leftjoin($cert_table, $cert_table.'.col_receipt_id', '=', $receipt_table.'.id')
                ->leftjoin($certtype_table, $certtype_table.'.id', '=', $cert_table.'.col_rcpt_certificate_type_id')
                ->leftJoin('col_pc_settings','col_pc_settings.pc_receipt','=','col_receipt.col_serial_id')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            else:
                $receipts = Receipt::select([$cert_table.'.col_rcpt_certificate_type_id',$certtype_table.'.name as cert_typex',$ismany_table.'.col_receipt_serial_parent',$Withcert_table.'.process_status',$Withcert_table.'.cert_type',$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', $receipt_table.'.serial_no', $receipt_table.'.date_of_entry', $customer_table.'.name', $receipt_table.'.is_cancelled', $receipt_table.'.is_printed',$receipt_table.'.report_date','col_pc_settings.pc_ip', $ismany_table.'.col_serials', $TransactionType . '.name as transaction_type'])
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->whereDay($receipt_table.'.date_of_entry','=',$show_day)
                ->where('transaction_source', '=', 'receipt')
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($Withcert_table, $Withcert_table.'.trans_id', '=', $receipt_table.'.id')
                ->leftjoin($ismany_table, $ismany_table.'.id', '=', $receipt_table.'.is_many')
                ->leftjoin($cert_table, $cert_table.'.col_receipt_id', '=', $receipt_table.'.id')
                ->leftjoin($certtype_table, $certtype_table.'.id', '=', $cert_table.'.col_rcpt_certificate_type_id')
                ->leftJoin('col_pc_settings','col_pc_settings.pc_receipt','=','col_receipt.col_serial_id')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            endif;
        else:
             $receipts = Receipt::select([$cert_table.'.col_rcpt_certificate_type_id',$certtype_table.'.name as cert_typex',$ismany_table.'.col_receipt_serial_parent',$Withcert_table.'.process_status',$Withcert_table.'.cert_type',$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', $receipt_table.'.serial_no', $receipt_table.'.date_of_entry', $customer_table.'.name', $receipt_table.'.is_cancelled', $receipt_table.'.is_printed',$receipt_table.'.report_date','col_pc_settings.pc_ip', $ismany_table.'.col_serials', $TransactionType . '.name as transaction_type'])
            ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
            ->where('transaction_source', '=', 'receipt')
            ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
            ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
            ->join($form_table, $form_table.'.id', '=', 'af_Type')
            ->leftjoin($Withcert_table, $Withcert_table.'.trans_id', '=', $receipt_table.'.id')
            ->leftjoin($ismany_table, $ismany_table.'.id', '=', $receipt_table.'.is_many')
            ->leftjoin($cert_table, $cert_table.'.col_receipt_id', '=', $receipt_table.'.id')
            ->leftjoin($certtype_table, $certtype_table.'.id', '=', $cert_table.'.col_rcpt_certificate_type_id')
            ->leftJoin('col_pc_settings','col_pc_settings.pc_receipt','=','col_receipt.col_serial_id')
            ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
            ->get();
        endif;
       
        $unique = [];
        
        foreach($receipts as $r) {
            if(!isset($count_repeat[$r->serial_no])) {
                $count_repeat[$r->serial_no] = 0;
            }
            if($count_repeat[$r->serial_no] <= 1) {
                $count_repeat[$r->serial_no]++;
            }
        }

        foreach($receipts as $r) {
            if(!isset($repeat_ctr[$r->serial_no]))
                $repeat_ctr[$r->serial_no] = 0;

            if(isset($count_repeat[$r->serial_no])) {
                if($count_repeat[$r->serial_no] <= 1 && $repeat_ctr[$r->serial_no] < 1) {
                    array_push($unique, $r);
                    $repeat_ctr[$r->serial_no]++;
                } else {
                    $multiple = Receipt::where('serial_no', $r->serial_no)->get();
                    $array_multiple = [];
                    foreach($multiple as $receipt) {
                        array_push($array_multiple, [
                            'serial_no' => $receipt->serial_no,
                            'date_of_entry' => $receipt->date_of_entry,
                            'report_date' => $receipt->report_date
                        ]);
                    }
                    $multiple_unq = array_unique($array_multiple, SORT_REGULAR);
                    if(($repeat_ctr[$r->serial_no] < 1) || $r->is_printed == 0 || count($multiple_unq) > 1) {
                        array_push($unique, $r);
                        $repeat_ctr[$r->serial_no]++;
                    }
                }
            }
        }

        $receipts_col = collect($unique);

        return $receipts_col;
    }

    protected function field_land_tax()
    {
        $show_year = Input::get('show_year');
        $show_mnth = Input::get('show_mnth');
        $show_day = Input::get('show_day');
        
        $receipt_table = Receipt::getTableName();
        $customer_table = Customer::getTableName();
        $user_table = User::getTableName();
        $form_table = Form::getTableName();
        $many_serials = IsManySerials::getTableName();
        $TransactionType = TransactionType::getTableName();
        if($show_mnth != 'ALL'):
            if($show_day == 'ALL' || $show_day == null):
                $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
                ->with('WithCert')
                ->with('RcptCertificate')
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->where('transaction_source', '=', 'field_land_tax')
                ->where($receipt_table.'.af_type','=',1)
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            else:
                $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
                ->with('WithCert')
                ->with('RcptCertificate')
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->whereDay($receipt_table.'.date_of_entry','=',$show_day)
                ->where('transaction_source', '=', 'field_land_tax')
                ->where($receipt_table.'.af_type','=',1)
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            endif;
        else:
            $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
            ->with('WithCert')
            ->with('RcptCertificate')
            ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
            ->where('transaction_source', '=', 'field_land_tax')
            ->where($receipt_table.'.af_type','=',1)
            ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
            ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
            ->join($form_table, $form_table.'.id', '=', 'af_Type')
            ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
            ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
            ->get();
        endif;
        
        return $receipts;
    }

    protected function opag()
    {
        $show_year = Input::get('show_year');
        $show_mnth = Input::get('show_mnth');
        $show_day = Input::get('show_day');
        
        $receipt_table = Receipt::getTableName();
        $customer_table = Customer::getTableName();
        $user_table = User::getTableName();
        $form_table = Form::getTableName();
        $many_serials = IsManySerials::getTableName();
        $TransactionType = TransactionType::getTableName();
        if($show_mnth != 'ALL'):
            if($show_day == 'ALL' || $show_day == null):
                $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
                ->with('WithCert')
                ->with('RcptCertificate')
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->where('transaction_source', '=', 'opag')
                ->where($receipt_table.'.af_type','=',1)
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            else:
                $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
                ->with('WithCert')
                ->with('RcptCertificate')
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->whereDay($receipt_table.'.date_of_entry','=',$show_day)
                ->where('transaction_source', '=', 'opag')
                ->where($receipt_table.'.af_type','=',1)
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            endif;
        else:
            $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
            ->with('WithCert')
            ->with('RcptCertificate')
            ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
            ->where('transaction_source', '=', 'opag')
            ->where($receipt_table.'.af_type','=',1)
            ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
            ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
            ->join($form_table, $form_table.'.id', '=', 'af_Type')
            ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
            ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
            ->get();
        endif;
        
        return $receipts;
    }

    protected function pvet()
    {
        $show_year = Input::get('show_year');
        $show_mnth = Input::get('show_mnth');
        $show_day = Input::get('show_day');
        
        $receipt_table = Receipt::getTableName();
        $customer_table = Customer::getTableName();
        $user_table = User::getTableName();
        $form_table = Form::getTableName();
        $many_serials = IsManySerials::getTableName();
        $TransactionType = TransactionType::getTableName();
        if($show_mnth != 'ALL'):
            if($show_day == 'ALL' || $show_day == null):
                $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
                ->with('WithCert')
                ->with('RcptCertificate')
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->where('transaction_source', '=', 'pvet')
                ->where($receipt_table.'.af_type','=',1)
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            else:
                $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
                ->with('WithCert')
                ->with('RcptCertificate')
                ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
                ->whereMonth($receipt_table.'.date_of_entry','=',$show_mnth)
                ->whereDay($receipt_table.'.date_of_entry','=',$show_day)
                ->where('transaction_source', '=', 'pvet')
                ->where($receipt_table.'.af_type','=',1)
                ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
                ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
                ->join($form_table, $form_table.'.id', '=', 'af_Type')
                ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
                ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
                ->get();
            endif;
        else:
            $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed', 'col_receipt_serial_parent', 'col_serials', $TransactionType . '.name as transaction_type'])
            ->with('WithCert')
            ->with('RcptCertificate')
            ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
            ->where('transaction_source', '=', 'pvet')
            ->where($receipt_table.'.af_type','=',1)
            ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
            ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
            ->join($form_table, $form_table.'.id', '=', 'af_Type')
            ->leftjoin($many_serials, $many_serials.'.id', '=', $receipt_table.'.is_many')
            ->leftJoin($TransactionType, $TransactionType . '.id', '=', $receipt_table . '.transaction_type')
            ->get();
        endif;
        
        return $receipts;
    }

    protected function form56()
    {
        $show_year = Input::get('show_year');
        $receipt_table = Receipt::getTableName();
        $customer_table = Customer::getTableName();
        $user_table = User::getTableName();
        $form_table = Form::getTableName();
        $F56TDARP = F56TDARP::getTableName();
        $Municipality = Municipality::getTableName();
        $Barangay = Barangay::getTableName();
        $TransactionType = TransactionType::getTableName();
        


        $receipts = Receipt::select([$receipt_table.'.id', $user_table.'.realname', $form_table.'.name as form_name',$Municipality.'.name as mun_name',$Barangay.'.name as brgy_name', $TransactionType.'.name as transaction_type', 'serial_no', 'date_of_entry', $customer_table.'.name', 'is_cancelled', 'is_printed'])
            ->whereYear($receipt_table.'.date_of_entry','=',$show_year)
            ->where($receipt_table.'.af_type','=',2)
            ->where('transaction_source', '=', 'field_land_tax')
            ->join($customer_table, $customer_table.'.id', '=', 'col_customer_id')
            ->join($user_table, $user_table.'.id', '=', 'dnlx_user_id')
            ->join($form_table, $form_table.'.id', '=', 'af_Type')
            ->leftjoin($Municipality, $Municipality.'.id', '=', $receipt_table.'.col_municipality_id')
            ->leftjoin($Barangay, $Barangay.'.id', '=', $receipt_table.'.col_barangay_id')
            ->leftJoin($TransactionType, $TransactionType.'.id', '=', $receipt_table.'.transaction_type')
            ->get();
        return $receipts;
    }

    protected function cash_division()
    {
        $cash_division_table = CashDivision::getTableName();
        $user_table = User::getTableName();
        $Municipality = Municipality::getTableName();
        $customer_table = Customer::getTableName();

        $cash_division = CashDivision::select([$cash_division_table.'.id',$cash_division_table.'.refno',$Municipality.'.name', $user_table.'.realname', 'date_of_entry',$customer_table.'.name as customer_name',$cash_division_table.'.deleted_at'])
            ->leftjoin($user_table, $user_table.'.id', '=', 'dnlx_user_id')
            ->leftjoin($Municipality, $Municipality.'.id', '=', $cash_division_table.'.col_municipality_id')
            ->leftjoin($customer_table, $customer_table.'.id', '=', 'col_customer_id')
            ->withTrashed()
            ->get();
        return $cash_division;
    }

    protected function cash_municipal_rpt_excel()
    {
        $excel_table = RptMunicipalExcel::getTableName();
        $excel_provincial = RptMunicipalExcelProvincialShare::getTableName();
        $excel_items = RptMunicipalExcelItems::getTableName();
        $Municipality = Municipality::getTableName();

        $importedExcels = RptMunicipalExcel::select(
                            $excel_table . '.*',
                            $Municipality . '.name as municipality_name'
                            )
                            ->where([
                                [$excel_provincial . '.is_verified', 1]
                            ])
                            ->where(function($query) {
                                $query->orWhere('is_printed_basic', 0)
                                      ->orWhere('is_printed_sef', 0);
                            })
                            ->with('excelItems')
                            ->leftJoin($excel_provincial, $excel_provincial .'.'. $excel_table . '_id', '=', $excel_table . '.id')
                            ->join($Municipality, $Municipality . '.id', '=', $excel_table . '.municipal')
                            ->get();
        
        return $importedExcels;
    }
}
