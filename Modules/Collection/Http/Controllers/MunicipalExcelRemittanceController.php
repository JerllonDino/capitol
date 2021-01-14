<?php

namespace Modules\Collection\Http\Controllers;

use Carbon\Carbon, Datatables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\{Controller};
use Illuminate\Support\Facades\Session;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\RptMunicipalExcel;
use Modules\Collection\Entities\RptMunicipalExcelItems;
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
        $municipalRemittances = RptMunicipalExcel::select('col_rpt_municipal_excel.*', 'col_municipality.name as municipality_name', 'col_rpt_municipal_excel_provincial.id as provincial_id')
                                                ->join('col_municipality', 'col_municipality.id', '=', 'col_rpt_municipal_excel.municipal')
                                                ->join('col_rpt_municipal_excel_provincial', 'col_rpt_municipal_excel_provincial.col_rpt_municipal_excel_id', '=', 'col_rpt_municipal_excel.id')
                                                ->where([
                                                    ['report_year', '=', $request['report_year']],
                                                    ['report_month', '=', $request['report_month']],
                                                    ['col_rpt_municipal_excel_provincial.is_verified', '=', $request['isVerified']]
                                                ])->get();
        
        return Datatables::of(collect($municipalRemittances))->make(true);
    }

    public function verifyProvincialShare(Request $request)
    {
        $values = $request->all();
        unset($values['_token']);
        unset($values['id']);
        $values['is_verified'] = 1;
        $provincialId = $request['id'];
        $provincialShare = RptMunicipalExcelProvincialShare::find($provincialId);
        $provincialShare->update($values);
        $sessionMessage = 'Municipal Remittance successfully ' . ($request['is_verified'] == 1 ? 'updated!' : 'verified!');
        Session::flash('successMessage', $sessionMessage);
        return redirect()->route('rpt.municipal_remittance');
    }

    public function getProvincialShare(Request $request)
    {
        $provincialShareId = $request['data_id'];
        $provincialShare = RptMunicipalExcelProvincialShare::where([
            ['id', $provincialShareId]])->first();
        
        $provincialShare =  $provincialShare ? (json_encode( (array) $provincialShare->toArray())) : 0;
        return response()->json($provincialShare);
    }
}
