<?php

namespace App\Http\Controllers;

use App\Models\Group;

use Illuminate\Http\Request;

use App\Http\Requests;

class AjaxController extends Controller
{
    # Calls appropriate method based on $_POST['action']
    public function index(Request $request) {
        $action = $_POST['action'];
        $response = $this->$action($_POST);
        return response()->json($response);
    }

    private function get_groupmembers($params) {
        $group = Group::whereId($params['groupid'])->first();
		$groupmembers = $group->users;
        $response['members'] = $groupmembers;
        return $response;
    }
}
