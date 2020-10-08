<?php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\User;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Http\Requests;

class GroupController extends Controller
{
    protected $group;
    protected $user;
    
    public function __construct(Request $request, Group $group, User $user) {
        parent::__construct($request);
        $this->base['page_title'] = 'Group';
        $this->group = $group;
        $this->user = $user;
    }

    # Lists all groups
    public function index() {
        $this->base['sub_header'] = 'List';
        return view('base.grouplist')->with('base', $this->base);
    }

    # Validates group creation
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:' . Group::getTableName() . '|min:1|max:255',
            'description' => 'string|max:255',
        ]);
        # Return if there are errors
        if ($validator->fails()) {
            return redirect()->route('group.create')
                ->withErrors($validator)
                ->withInput();
        }

        # Inserts valid data
        $group = Group::create(array(
            'name' => $request['name'],
            'description' => $request['description'],
        ));

        $permissions = Permission::all(['id']);
        $group_permissions = array();
        foreach ($permissions as $permission) {
            array_push(
                $group_permissions,
                [
                    'group_id' => $group->id,
                    'permission_id' => $permission->id,
                    'value' => 0,
                ]
            );
        }
        GroupPermission::insert($group_permissions);

        Session::flash('info', ['Group has been created.']);
        return redirect()->route('group.index');
    }

    # Form for creating groups
    public function create() {
        $this->base['sub_header'] = 'Add';
        $this->base['permissioncategories'] = array();

        return view('base.groupadd')->with('base', $this->base);
    }

    public function show($id) {
        $this->base['sub_header'] = 'View';
        $this->base['group'] = Group::where('id', $id)->first();
        return view('base.groupview')->with('base', $this->base);
    }

    public function update(Request $request, $id) {
        $group = Group::where('id', $id)->first();
        
        $validator_filter = ['description' => 'string|max:255'];
        
        # Adds validation filter if group name isn't same
        if ($request['name'] !== $group->name) {
            $validator_filter['name'] = 'required|unique:'.$group_table.'|min:1|max:255';
            $group->name = $request['name'];
        }

        $validator = Validator::make($request->all(), $validator_filter);

        # Return if there are errors
        if ($validator->fails()) {
            return redirect()->route('group.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        # Update group
        $group->description = $request['description'];
        $group->save();

        Session::flash('info', ['Group has been updated.']);
        return redirect()->route('group.show', ['id' => $id]);
    }

    public function destroy($id) {
        $groupmembers = User::where('group_id', $id)->get();
        
        # Group has no members, so delete group
        if ($groupmembers->isEmpty()){
            $group = $this->group->where('id', $id)->first();
            $group->delete();
            GroupPermission::where('group_id', $id)->delete();
            Session::flash('info', ['Group deleted.']);
            return redirect()->route('group.index');
        }
        
        # Group has members, so display form of deletion options
        $group_details = array();
        $this->base['sub_header'] = 'Delete';
        $groups = Group::where('id','!=', $id)->get();

        foreach($groups as $group) {
            $group_detail = array(
                'id' => $group['id'],
                'name' => $group['name'],
                'description' =>$group['description']
            );
            array_push($group_details, $group_detail);
        }
        $this->base['group_details'] = $group_details;
        $this->base['group'] = Group::where('id', $id)->first();

        return view('base.groupdeletechoice')->with('base', $this->base);
    }

    public function destroyChoice(Request $request, $id) {
        # Delete group and its permissions
        $group = $this->group->where('id', $id)->first();
        $group->delete();
        GroupPermission::where('group_id', $id)->delete();
        
        # Delete members of deleted group
        if ($request['deletetype']==1) {
            $users = $this->user->where('group_id', $id)->get();
            foreach($users as $user){
                $user->delete();
            }
            Session::flash('info', ['Group deleted.']);
        }
        
        # Transfer members of old group to a new group
        elseif ($request['deletetype']==2) {
            $name = $request['name'];
            $description = $request['description'];
            $group = Group::create(array(
                'name' => $name,
                'description' => $description,
            ));
            $user = $this->user->where('group_id', $id)->get();
            foreach($user as $u){
                $u->update(array('group_id' => $group->id));
            }
            $permissions = Permission::all(['id']);
            $group_permissions = array();
            foreach ($permissions as $permission) {
                array_push(
                    $group_permissions,
                    [
                        'group_id' => $group->id,
                        'permission_id' => $permission->id,
                        'value' => 0,
                    ]
                );
            }
            GroupPermission::insert($group_permissions);
            Session::flash('info', ['Group created and users transferred.']);
        }
        
        # Transfer members of old group to another existing group
        else {
            $new_group = $request['group'];
            $user = $this->user->where('group_id', $id)->get();
            foreach($user as $u){
                $u->update(array('group_id'=> $new_group));
            }
            
            Session::flash('info', ['Users transferred.']);
        }

        return redirect()->route('group.index');
    }

    # Form for updating existing groups
    public function edit($group_id) {
        $this->base['sub_header'] = 'Edit';
        $this->base['group'] = Group::where('id', $group_id)->first();
        $this->base['permissioncategories'] = array();

        return view('base.groupedit')->with('base', $this->base);
    }
}
