<?php

namespace Modules\Collection\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
use Modules\Collection\Entities\WeekdayHoliday;
use Modules\Collection\Entities\WithCert;
use Modules\Collection\Entities\RcptCertificate;
use Modules\Collection\Entities\RcptCertificateType;
use Modules\Collection\Entities\IsManySerials;
use Modules\Collection\Entities\SandandGravelMnthly;
use Modules\Collection\Entities\MonthlyProvincialIncome;
use App\Models\User;

use Carbon\Carbon;

class StaffReportsController extends Controller
{
   public function __construct(Request $request, Serial $serial)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'STAFF REPORTS';
         $this->base['months'] = array();
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            array_push($this->base['months'], $month);
        }
    }

    public function encoders_report()
    {
        return view('collection::staff_report.index')->with('base',$this->base);
    }

    public function encoders_report_view( Request $request){
        $data['start_date'] = new Carbon($request->input('start_date'));
        $data['end_date'] = new Carbon($request->input('end_date'));
        $this->base['data'] = $data;
        $this->base['user_table'] = User::select('id','realname')->where('group_id',2)->get();
        $table = [];

        foreach ($this->base['user_table'] as $key => $value) {
            $table[$value->id] = Receipt::where('report_date','>=',$data['start_date']->format('Y-m-d'))->where('report_date','<=',$data['end_date']->format('Y-m-d'))->where('dnlx_user_id',$value->id)->get()->count();
        }
        $this->base['table'] = $table;
        return view('collection::staff_report.view',$this->base)->with('base',$this->base);
    }

    
}
