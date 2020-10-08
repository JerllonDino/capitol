<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\BreadcrumbsController;
use App\Http\Controllers\Controller;

use Modules\Collection\Entities\BudgetEstimate;
use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\CollectionRate;
use Carbon\Carbon;

class CollectionRateController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Collection Rates';
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['category'] = AccountCategory::all();
        $data['title'] = AccountTitle::orderBy('name')->get();
        $data['subtitle'] = AccountSubtitle::orderBy('name')->get();
        return view('collection::rate.select_account', compact('data'))->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('collection::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request, $type, $id)
    {
		$data['type'] = $type;
		$data['id'] = $id;
		$data['title'] = $data['subtitle'] = null;

		if ($type == 'title') {
			$data['title'] = AccountTitle::whereId($id)->first();
			$data['collectionrate'] = CollectionRate::where('col_acct_title_id', '=', $id)->get();
		} else {
			$data['subtitle'] = AccountSubtitle::whereId($id)->first();
			$data['collectionrate'] = CollectionRate::where('col_acct_subtitle_id', '=', $id)->get();
		}
        return view('collection::rate.edit_account', compact('data'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {

        $type = ($request['type'] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
        $collectionrate = CollectionRate::where($type, '=', $request['id'])->get();

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'type' => 'required',
            'rate_type' => 'required',
        ]);

        $sharepct_provincial = $request['sharepct_provincial'] == '' ? 0:$request['sharepct_provincial'];
        $sharepct_municipal = $request['sharepct_municipal'] == '' ? 0:$request['sharepct_municipal'];
        $sharepct_barangay = $request['sharepct_barangay'] == '' ? 0:$request['sharepct_barangay'];

        $total_share = $sharepct_provincial + $sharepct_municipal + $sharepct_barangay;
        if ($validator->fails()) {
            return redirect()->route('rates.edit', ['type' => $request['type'], 'id' => $request['id']])
                ->withErrors($validator);
        } elseif (($request['is_shared'] == 1) && ($total_share != 100)) {
            $validator->getMessageBag()
                ->add('serial', 'Total share for Provincial, Municipal and Brgy. must be 100');
            return redirect()->route('rates.edit', ['type' => $request['type'], 'id' => $request['id']])
                ->withErrors($validator);
        }
        //dd($request);

        # OKAY VALIDATION
        # delete old existing rates for account
        if ($collectionrate != null) {
			foreach ($collectionrate as $cr) {
				$cr->delete();
			}
		}

        $label = $value = '';
        $sharepct_provincial = (empty($request['sharepct_provincial'])) ? null : $request['sharepct_provincial'];
        $sharepct_municipal = (empty($request['sharepct_municipal'])) ? null : $request['sharepct_municipal'];
        $sharepct_barangay = (empty($request['sharepct_barangay'])) ? null : $request['sharepct_barangay'];
        if ($request['rate_type'] == 'fixed') {
            $value = $request['fixed_val'];
        } elseif ($request['rate_type'] == 'manual') {
            $value = null;
        } elseif ($request['rate_type'] == 'percent') {
            $value = $request['percent_val'];
        }

        if ($request['rate_type'] == 'schedule') {
            foreach($request['sched_label'] as $i => $label) {
                $label = $label;
                $value = $request['sched_val'][$i];

                $record = [
                    'col_acct_title_id' => ($request['type'] == 'title') ? $request['id'] : null,
                    'col_acct_subtitle_id' => ($request['type'] == 'subtitle') ? $request['id'] : null,
                    'type' => $request['rate_type'],
                    'label' => $label,
                    'value' => $value,
                    'is_shared' => $request['is_shared'],
                    'sharepct_provincial' => $sharepct_provincial,
                    'sharepct_municipal' => $sharepct_municipal,
                    'sharepct_barangay' => $sharepct_barangay,
                    'sched_is_perunit' => $request['sched_is_perunit'][$i],
                    'sched_unit' => $request['sched_unit'][$i],
                    'pct_is_sum_given' => null,
                    'pct_deadline' => null,
                    'pct_rate_per_month' => null,
                ];
                CollectionRate::create($record);
            }
        } else {
             $date_deadline = null;
            if(isset($request['date_deadline'])){
                $date_deadline = new Carbon($request['date_deadline']); 
                $date_deadline = $date_deadline->format('m/d');
            }
           
            $record = [
                'col_acct_title_id' => ($request['type'] == 'title') ? $request['id'] : null,
                'col_acct_subtitle_id' => ($request['type'] == 'subtitle') ? $request['id'] : null,
                'type' => $request['rate_type'],
                'label' => $label,
                'value' => $value,
                'is_shared' => $request['is_shared'],
                'sharepct_provincial' => $sharepct_provincial,
                'sharepct_municipal' => $sharepct_municipal,
                'sharepct_barangay' => $sharepct_barangay,
                'sched_is_perunit' => null,
                'sched_unit' => null,
                'pct_is_sum_given' => (isset($request['percent_of'])) ? $request['percent_of'] : null,
                'pct_deadline' => (isset($request['deadline'])) ? $request['deadline'] : null,
                'pct_deadline_date' => $date_deadline,
                'pct_rate_per_month' => (isset($request['rate_per_month'])) ? $request['rate_per_month'] : null,
            ];
            CollectionRate::create($record);
        }

		Session::flash('info', ['Collection rate has been saved.']);
        return redirect()->route('rates.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function show()
    {
    }
}
