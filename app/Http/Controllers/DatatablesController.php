<?php
namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Group;
use App\Models\Backup;
use App\Models\Audit;
use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\Permissioncategory;
use App\Http\Requests;

class DatatablesController extends Controller
{
    # Calls appropriate method based on $category
    public function getdata($category)
    {
        return Datatables::of($this->$category())->make(true);
    }

    private function user()
    {
        $users = User::select(['id', 'realname', 'username', 'email', 'created_at']);
        return $users;
    }
    
    private function backup()
    {
        $backups = Backup::select(['id', 'date_of_entry', 'remark', 'location']);
        return $backups;
    }

    private function group()
    {
        $groups = Group::select(['id', 'name', 'description']);
        return $groups;
    }

    private function audit()
    {


        $audit_table = Audit::getTableName();
        $user_table = User::getTableName();

        
        $audits = Audit::select([$audit_table.'.created_at', $audit_table.'.type', $audit_table.'.old', $audit_table.'.new', $user_table.'.realname', $audit_table.'.ip_address'])
            ->join($user_table, $user_table.'.id', '=', 'user_id');
        return $audits;
    }

    private function group_permission()
    {
        $permission_table = Permission::getTableName();
        $group_permission_table = GroupPermission::getTableName();
        $permission_category_ids = array_column(
            GroupPermission::where('group_id', $_GET['id'])
                ->join($permission_table, $permission_table.'.id', '=', $group_permission_table.'.permission_id')
                ->groupBy('permissioncategory_id')
                ->get()
                ->toArray(),
            'permissioncategory_id'
        );
        
        $permission_categories = Permissioncategory::select(['id', 'name', 'description'])
            ->whereIn('id', $permission_category_ids);
        return $permission_categories;
    }
    
    private function group_members()
    {
        $group_members = User::select(['id', 'username', 'realname'])
            ->where('group_id', '=', $_GET['id']);
        return $group_members;
    }

    private function filter()
    {
        $datefrom = date("Y-m-d", strtotime($_GET['datefrom']));
        $dateto = date("Y-m-d", strtotime($_GET['dateto']));
        $transaction = $_GET['transaction'];
        $type = $_GET['type'];
        $user = $_GET['user'];
        $records = array();

        $query = Audit::where('id','!=',"");
        if($_GET['datefrom']!="" && $_GET['dateto']!="")
            $query->whereBetween('created_at', array($datefrom, $dateto));
        if($type!="")
            $query->where('type','=', $type);
        if($transaction!="") {
            $query->where('auditable_type','LIKE','%'.$transaction.'%');
            if($transaction == 'Group')
                $query->where('auditable_type','<>','App\Models\GroupPermission');
        }
        if($user!="")
            $query->where('user_id','=', $user);
        return $query;
    }
}
