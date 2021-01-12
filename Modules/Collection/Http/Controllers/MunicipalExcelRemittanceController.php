<?php

namespace Modules\Collection\Http\Controllers;

use Carbon\Carbon, Datatables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\{Controller};
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\RptMunicipalExcel;
use Modules\Collection\Entities\RptMunicipalExcelProvincialShare;

class MunicipalExcelRemittanceController extends Controller
{
    public function viewMunicipalRemittance()
    {
        $this->base['page_title'] = 'Field Division Municipal Remittance';
        $this->base['municipality'] = Municipality::all();
        $this->base['months'] = array();
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            array_push($this->base['months'], $month);
        }
        return view('collection::customer.rpt_municipal_remittance')->with('base', $this->base);
    }

    public function getMunicipalRemittances(Request $request)
    {
        $municipalRemittances = RptMunicipalExcel::select('col_rpt_municipal_excel.*', 'col_municipality.name as municipality_name')
        ->join('col_municipality', 'col_municipality.id', '=', 'col_rpt_municipal_excel.municipal')
        ->where([
            ['report_year', '=', $request['report_year']],
            ['report_month', '=', $request['report_month']]
        ])->get();
        
        return Datatables::of(collect($municipalRemittances))->make(true);
    }

    public function searchProvincialShare(Request $request)
    {
        $municipality = $request['municipality'];
        $month = $request['month'];
        $year = $request['year'];
        $provincialShare = RptMunicipalExcelProvincialShare::select('col_rpt_municipal_excel_provincial.*', 'col_municipality.name as municipality_name')
                                                            ->join('col_rpt_municipal_excel', 'col_rpt_municipal_excel.id', '=', 'col_rpt_municipal_excel_provincial.col_rpt_municipal_excel_id')
                                                            ->join('col_municipality', 'col_municipality.id', '=', 'col_rpt_municipal_excel.municipal')
                                                            ->where([
                                                                ['col_rpt_municipal_excel.municipal', '=', $municipality],
                                                                ['col_rpt_municipal_excel.report_month', '=', $month],
                                                                ['col_rpt_municipal_excel.report_year', '=', $year]
                                                            ])->first();
    
        $provincialShare = $provincialShare ? (json_encode( (array) $provincialShare->toArray())) : 0;
        return response()->json($provincialShare);
    }
}
