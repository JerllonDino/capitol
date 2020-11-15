<?php

namespace App\Http\Controllers;

use App\Models\GroupPermission;

use App\Models\Permission;

use App\Models\Permissioncategory;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

use App\Http\Requests;

class GroupPermissionController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->base['page_title'] = 'Group Permission';
        $this->permission_table = Permission::getTableName();
        $this->group_permission_table = GroupPermission::getTableName();

    }
    
    # Lists group's permission categories
    public function index($group_id) {
        $this->base['group_id'] = $group_id;
        $this->base['sub_header'] = 'List';
        return view('base.group_permissionlist')->with('base', $this->base);
    }
    
    public function show($group_id, $permissioncategory_id) {
        $this->base['sub_header'] = 'View';
        $this->base['group_id'] = $group_id;
        $this->base['permissioncategory_id'] = $permissioncategory_id;
        $this->base['permissioncategory'] = Permissioncategory::where('id', $permissioncategory_id)->first();
        $this->base['group_permissions'] = GroupPermission::where('group_id', $group_id)
            ->join($this->permission_table, $this->permission_table.'.id', '=', $this->group_permission_table.'.permission_id')
            ->where('permissioncategory_id', $permissioncategory_id)
            ->get(['permission_id', 'value', 'description']);
        // dd($this->base);
        return view('base.group_permissionview')->with('base', $this->base);
    }
    
    public function update($group_id, $permissioncategory_id, Request $request) {
        $group_permissions = GroupPermission::where('group_id', $group_id)
            ->join($this->permission_table, $this->permission_table.'.id', '=', $this->group_permission_table.'.permission_id')
            ->where('permissioncategory_id', $permissioncategory_id)
            ->get([$this->group_permission_table.'.id', $this->group_permission_table.'.permission_id', $this->group_permission_table.'.value']);
        foreach ($group_permissions as $group_permission) {
            $input = $request[$group_permission->permission_id];
            $permission_value = ($input !== null) ? array_sum($input) : 0;
            $group_permission->value = $permission_value;
            $group_permission->save();
        }
        
		Session::flash('info', ['Permissions have been updated.']);
		return redirect()->route('group.permission.show', ['group_id' => $group_id, 'permissioncategory_id' => $permissioncategory_id]);
    }
    
    # Form for updating group permissions
    public function edit($group_id, $permissioncategory_id) {
        $this->base['sub_header'] = 'Edit';
        $this->base['group_id'] = $group_id;
        $this->base['permissioncategory'] = Permissioncategory::where('id', $permissioncategory_id)->first();
        $this->base['group_permissions'] = GroupPermission::where('group_id', $group_id)
            ->join($this->permission_table, $this->permission_table.'.id', '=', $this->group_permission_table.'.permission_id')
            ->where('permissioncategory_id', $permissioncategory_id)
            ->get(['permission_id', 'value', 'description']);
        return view('base.group_permissionedit')->with('base', $this->base);
        
    }
}
