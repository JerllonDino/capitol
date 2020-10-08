<?php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Http\Requests;

class UserController extends Controller
{
    protected $user;

    public function __construct(Request $request, User $user) {
        parent::__construct($request);
        $this->base['page_title'] = 'User';
        $this->user = $user;
    }

    # Lists all users
    public function index() {
        $this->base['sub_header'] = 'List';
        return view('base.user.list')->with('base', $this->base);
    }

    # Validates user creation
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'realname' => 'required|max:255',
            'username' => 'required|alpha_num|min:3|max:255',
            'position' => 'required|min:3|max:255',
            'password' => 'required|same:retype_password|min:3|max:255',
            'email' => 'sometimes|email|unique:' . User::getTableName() . '|max:255',
            'group' => 'required',
        ]);

        # Return if there are errors
        $user = User::where('username', $request['username'])
            ->where('deleted_at', null)->first();
        if ($validator->fails()) {
            return redirect()->route('user.create')
                ->withErrors($validator)
                ->withInput();
        } elseif (!empty($user)) {
            $validator->getMessageBag()
                ->add('username', 'The username must be unique.');
            return redirect()->route('user.create')
                ->withErrors($validator)
                ->withInput();
        }

        # Inserts valid data
        User::create(array(
            'realname' => $request['realname'],
            'username' => $request['username'],
            'position' => $request['position'],
            'password' => Hash::make($request['password']),
            'email' => $request['email'],
            'group_id' => $request['group'],
            'created_at' => date('Y-m-d H:i:s'),
        ));
        Session::flash('info', ['User has been created.']);
        return redirect()->route('user.index');
    }

    # Form for creating users
    public function create() {
        $this->base['sub_header'] = 'Add';
        $this->base['groups'] = Group::orderBy('name')->get();
        return view('base.user.add')->with('base', $this->base);
    }

    public function show($id) {
        $this->base['sub_header'] = 'View';
        $this->base['user'] = User::where('id', $id)->first();
        return view('base.user.view')->with('base', $this->base);
    }

    public function update(Request $request, $id) {
        $user = User::where('id', $id)->first();

        # Get initial filter
        $validator_filter = [
            'realname' => 'required|max:255',
            'group' => 'required',
        ];

        # Add password filter if it isn't empty
        if (!empty($request['password'])) {
            $validator_filter['password'] = 'required|same:retype_password|min:3|max:255';
            $user->password = Hash::make($request['password']);
        }

        # Add email filter if current email isn't the same
        if ($request['email'] !== $user->email) {
            $validator_filter['email'] = 'sometimes|email|unique:' . User::getTableName() . '|max:255';
            $user->email = $request['email'];
        }

        # Filter input
        $validator = Validator::make($request->all(), $validator_filter);

        # Return if there are errors
        if ($validator->fails()) {
            return redirect()->route('user.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        # Update
        $user->position = $request['position'];
        $user->realname = $request['realname'];
        $user->group_id = $request['group'];
        $user->save();

        Session::flash('info', ['User has been updated.']);
        return redirect()->route('user.show', ['id' => $id]);
    }

    public function destroy($id) {
        $user = $this->user->where('id', $id)->first();
        $user->delete();
        Session::flash('info', ['User has been deleted.']);
        return redirect()->route('user.index');
    }

    # Form for updating existing users
    public function edit($id) {
        $result = User::findOrFail($id);
        $grp =  User::findOrFail($id)->group;
        $this->base['sub_header'] = 'Edit';
        $this->base['groups'] = Group::orderBy('name')->get();
        return view('base.user.edit', compact('result', 'grp'))->with('base', $this->base);
    }
    
}