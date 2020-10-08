<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Collection\Entities\ReceiptItems;

class AutocompleteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $vars = $request->all();
        $method = $request->input('action');
        $result = $this->$method($vars);

        $data['suggestions'] = array();
        foreach($result as $key => $value){
            $data['suggestions'][$key] = array();
            foreach ($value as $keyx => $valuex) {
                $data['suggestions'][$key][$keyx] = "$valuex";
            }
        }
        return $data;
    }



    protected function get_nature($vars) {
        $nature = ReceiptItems::where('nature', 'like', '%'.$vars['query'].'%')
            ->select(['id AS data','nature AS value'])
            ->orderBy('nature', 'asc')
            ->groupby('nature')
            ->get(['id', 'nature'])
            ->toArray();
            return $nature;

    }




}
