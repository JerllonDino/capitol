<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\BreadcrumbsController;
use App\Http\Controllers\Controller;

use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\SandandGravelMnthly;
use Modules\Collection\Entities\Municipality;
use Carbon\Carbon;

class SandGravelMonthlyController extends Controller
{
  public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Monthly Sand and Gravel';

        $this->base['months'] = array();
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            array_push($this->base['months'], $month);
        }
    }

    public function index()
    {

        return view('collection::monthly_sand_gravel.index')->with('base', $this->base);
    }


    public function col_sandgravel_mnthly_create(){
        $this->base['municipality'] = Municipality::all();
        return view('collection::monthly_sand_gravel.create',$this->base)->with('base', $this->base);
    }

    public function col_sandgravel_mnthly_store(Request $request){
        $mnth = $request['month'];
        $year = $request['year'];
         $municipality = Municipality::all();
        foreach($municipality as $key => $value){
                $SandandGravelMnthly = SandandGravelMnthly::where('month',$mnth)->where('year',$year)->where('municipality', $value->id)->first();
                if(!$SandandGravelMnthly){
                    $SandandGravelMnthly = new SandandGravelMnthly;
                    $SandandGravelMnthly->month = $mnth;
                    $SandandGravelMnthly->year = $year;
                    $SandandGravelMnthly->municipality = $value->id;
                }
                $SandandGravelMnthly->mcpal_value = $request['mnth_mncpal'][$value->id];
                $SandandGravelMnthly->save();
           
        }

        Session::flash('info', ['Successfully inserted SAND and GRAVEL MONTHLY']);
        return redirect()->route('sandgravel.monthly')->with('base', $this->base);
        
    }

    public function col_sandgravel_mnthly_view($year, $month)
    {
        $this->base['year'] = $year;
        $this->base['month'] = $month;
        $this->base['SandandGravelMnthly'] = SandandGravelMnthly::where('month',$month)->where('year',$year)->get();
        $this->base['municipality'] = Municipality::all();
        return view('collection::monthly_sand_gravel.view',$this->base)->with('base', $this->base);
    }

    public function col_sandgravel_mnthly_edit($year, $month){
        $this->base['year'] = $year;
        $this->base['month'] = $month;
        $this->base['SandandGravelMnthly'] = SandandGravelMnthly::where('month',$month)->where('year',$year)->get();
        $this->base['municipality'] = Municipality::all();
        return view('collection::monthly_sand_gravel.edit',$this->base)->with('base', $this->base);
    }

   
}
