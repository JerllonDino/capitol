<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Audit;

use App\Models\User;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\DatatablesController;

class AuditController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->base['page_title'] = 'Audit Log';
    }
    
    public function index() {
        $audits = Audit::groupBy('auditable_type')->get();
        $transaction = array();
        foreach($audits as $audit){
            $type = explode("\\", $audit->auditable_type);
            $type = end($type);
            array_push($transaction, $type);
        }

        $users = User::orderBy('realname')
            ->get();
        $userlist = array();
        foreach($users as $usr){
            $user = array(
                'id' => $usr->id,
                'realname' => $usr->realname,
                'username' => $usr->username
            );
            array_push($userlist, $user);
        }

        $this->base['transaction'] = $transaction;
        $this->base['user'] = $userlist;
        return view('base.audit')->with('base', $this->base);
    }

}
