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

class Form56SettingsController extends Controller
{
     protected $receipt;

    public function __construct(Request $request, Receipt $receipt)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'FORM56 Land Tax Collections Settings';
        $this->receipt = $receipt;
        $this->base['ada_settings'] = AdaSettings::get();
    }
    
    public function index()
    {
        $this->base['f56_types'] = F56Type::get();
        $this->base['sub_header'] = 'FORM56 Land Tax';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['user'] = Session::get('user');
        $this->base['sandgravel_types'] = sg_types::all();
        $this->base['f56_settings'] = F56Settings::where('deleted_at','=',NULL)->first();
        return view('collection::form56.settings.index')->with('base', $this->base);
    }

    public function save(Request $request){
       $f56_settings_deleted = F56Settings::where('deleted_at','=',NULL)->where('effictivity_year','<>',$request->year)->delete();
       $f56_settings = F56Settings::where('effictivity_year','=',$request->year)->first();
       if(!$f56_settings){
            $f56_settings = new F56Settings;
            $f56_settings->effictivity_year = $request->year;
       }
       $f56_settings->tax_percentage = $request->tax_percent;
       $f56_settings->disc_before_jan = $request->paid_in_full_december;
       $f56_settings->disc_from_jan_march = $request->paid_in_full_from;
       $f56_settings->penalty_per_mnth = $request->monthly_penalty;
       $f56_settings->save();
       Session::flash('info', ['FORM56 SETTINGS HAS BEEN UPDATED']);
       return back();
    }

   
}
