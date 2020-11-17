<?php
namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Backup;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

use Illuminate\Http\Request;
use App\Http\Requests;

class SessionController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
		$this->base['page_title'] = 'Login';
    }

    public function index() {
        $logo_setting = Setting::where('name', 'logo')->firstOrFail();
        $logo = (!empty($logo_setting->value)) ? '/base/img/' . $logo_setting->value : '';
        if (empty(Session::get('user'))) {
            return view('base.sessionlogin', compact('logo'))->with('base', $this->base);
        }
        return redirect()->route('profile.dashboard');
    }

    public function login(Request $request) {
        # create backup here..
        # because scheduling doesn't work on windows
        $date = \Carbon\Carbon::now()->toDateString();
        $filename = date('Y-m-d') . '_backup.sql';
        $bk = Backup::where('location', 'storage/backups/'.$filename)->first();
        if (is_null($bk)) {
            $user = Config::get('database.connections.mysql.username');
            $password = Config::get('database.connections.mysql.password');
            $host = Config::get('database.connections.mysql.host');
            $database_name = Config::get('database.connections.mysql.database');
            $root_f = storage_path().'/backups/'.$filename;
            exec('(mysqldump --opt  --skip-extended-insert --complete-insert --user="'.$user.'" --password="'.$password.'" '.$database_name.' > '.$root_f.' ) 2>&1', $output3, $result3);
            
            Backup::create(array(
                'date_of_entry' => \Carbon\Carbon::now()->toDateTimeString(),
                'remark' => 'Automated backup for ' . $date,
                'location' => 'storage/backups/'.$filename,
            ));
            // exec('"../../../mysql/bin/mysqldump" --opt --user='.$user.' --password='.$password.' --host='.$host.' capitol > "../storage/backups/'.$filename.'" 2>&1');

        }
        
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        $userdata = [
            'username' => $request['username'],
            'password' => $request['password'],
            'deleted_at' => null,
        ];

        # Set user var for session
        $user = User::select(
                'id',
                'realname',
                'username',
                'position',
                'email',
                'group_id',
                'created_at',
                'updated_at',
                'deleted_at'
            )
            ->where('username', $request['username'])
            ->first();
        
        # Return if there are errors
        if ($validator->fails()) {
            return redirect()->route('session.index')
                ->withErrors($validator);
        } elseif (!Auth::attempt($userdata) || is_null($user)) {
            $validator->getMessageBag()
                ->add('validation', 'The username/password is incorrect.');
            return redirect()->route('session.index')
                ->withErrors($validator);
        }

        # Set session for authenticated user
        Session::put('user', $user);
        Session::put('permission', self::getuserpermissions());
        return redirect()->route('profile.dashboard');
    }

    public function logout() {
        $date = \Carbon\Carbon::now()->toDateString();
        $filename = date('Y-m-d_H-i-s') . '_backup.sql';
        $user = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        $host = Config::get('database.connections.mysql.host');
        $database_name = Config::get('database.connections.mysql.database');
        Backup::create(array(
            'date_of_entry' => \Carbon\Carbon::now()->toDateTimeString(),
            'remark' => 'Automated backup for ' . $date,
            'location' => 'storage/backups/'.$filename,
        ));
        $root_f = storage_path().'/backups/'.$filename;
        // exec('"../../../mysql/bin/mysqldump" --opt --user='.$user.' --password='.$password.' --host='.$host.' capitol > "../storage/backups/'.$filename.'" 2>&1');
        exec('(mysqldump --opt  --skip-extended-insert --complete-insert --user="'.$user.'" --password="'.$password.'" '.$database_name.' > '.$root_f.' ) 2>&1', $output3, $result3);

        Auth::logout();
        Session::flush();
        return redirect()->route('session.index');
    }

    public static function getuserpermissions() {
        if (empty(Session::get('user'))) {
            return;
        }
        
        $user = User::where('id', Session::get('user')->id)->first();
        if (!$user) {
            \Cookie::queue(\Cookie::forget('laravel_session'));
            return ['ok' => true];
        }
        $group_permissions = $user->group->group_permissions;
        foreach ($group_permissions as $group_permission) {
            $permission = $group_permission->permission;
            $permissions[$permission->name] = $group_permission->value;
        }
        
        return $permissions;
    }

    public function edit_profile() {
        $user = Auth::user();
        $this->base['page_title'] = 'Profile';
        $this->base['sub_header'] = 'Edit';
        return view('base.profileedit', compact('user'))->with('base', $this->base);
    }

    public function update_profile(Request $request, $id) {
		$password_error = false;
        
        $user = User::find($id);
		$filter = [ 'realname' => 'required|max:255' ];
		
		# Add password filter if it isn't empty
		if (!empty($request['password'])) {
			# Check if stored password is same as input
            $password_error = !Hash::check($request['password'], $user->password);
            
			$filter['password'] = 'required|min:3|max:255';
			$filter['new_password'] = 'required|same:retype_password|min:3|max:255';
            $filter['retype_password'] = 'required';
			$user->password = Hash::make($request['new_password']);
		}
		
		# Add email filter if current email isn't the same
		if ($request['email'] !== $user->email) {
			$filter['email'] = 'sometimes|email|unique:' . User::getTableName() . '|max:255';
			$user->email = $request['email'];
		}
		
		$validator = Validator::make($request->all(), $filter);
        
		# Return if there are errors
		if ($validator->fails() || $password_error) {
            if ($password_error) {
				$validator->getMessageBag()
					->add('password', 'Incorrect password input.');
			}
            
			return redirect()->route('profile.edit')
				->withErrors($validator)
				->withInput();
		}
		
		$user->realname = $request['realname'];
		$user->save();
		
		Session::flash('info', ['Profile has been updated.']);
		return redirect()->route('profile.edit');
	}
    
    public function show_dashboard() {
        $this->base['page_title'] = 'Dashboard';
        return view('base.dashboard')->with('base', $this->base);
    }

}
