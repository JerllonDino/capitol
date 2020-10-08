<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\AdaSettings;

class AdaSettingsController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'ADA Settings';
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->base['ada_settings'] = AdaSettings::get();
        return view('collection::adasettings')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function update(Request $request)
    {
        $ada_setting = AdaSettings::where('label', 'bank_name')->first();
        $ada_setting->value = $request['bank_name'];
        $ada_setting->save();
        
        $ada_setting = AdaSettings::where('label', 'bank_number')->first();
        $ada_setting->value = $request['bank_number'];
        $ada_setting->save();
        
        Session::flash('info', ['Successfully updated ADA Settings']);
        return redirect()->route('settings_ada.index');
    }
}
