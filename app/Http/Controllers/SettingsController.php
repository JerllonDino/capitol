<?php

namespace App\Http\Controllers;

use App\Models\Audit;

use App\Models\Setting;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

class SettingsController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->base['page_title'] = 'Settings';
    }

    public function edit() {
        $this->base['settings'] = Setting::all()->toArray();
        return view('base.settings')->with('base', $this->base);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'logo' => 'image',
            'auditlogdays' => 'numeric'
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.edit')
            ->withErrors($validator)
            ->withInput();
        }

        if ($file = $request['logo']) {
            $name = $file->getClientOriginalName();
            $file->move('base/img', $name);
            Setting::whereId(1)->update(['value'=>$name]);
        }
        $audits = Audit::all();
        if (!empty($request['auditlogdays'])) {
            if($request['auditlogdays']>0) {
                foreach($audits as $audit) {
                    $created_at = $audit->created_at;
                    $date = explode(" ", $created_at)[0];
                    $time_difference = time() - strtotime($date);
                    $time_difference = floor($time_difference/(86400));
                    if($time_difference <= $request['auditlogdays']) {
                        Audit::where('id', $audit->id)->delete();
                    }
                }
            }
            Setting::whereId(2)->update(['value'=>$request['auditlogdays']]);
        }
        Session::flash('info', ['Settings have been saved.']);
        return redirect()->route('settings.edit');
    }
}
