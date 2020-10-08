<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Validator;

use Closure;

use App\Models\RoutePermission;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\File::exists('ongoing_db_restore')) {
            return response(view('base.ongoing_restore'));
        }
        
        # Returns user to '/' if they do not have permission to access that route
        $user_permissions = Session::get('permission');
        $routename = \Request::route()->getName();
        $routepermission = RoutePermission::where('route', '=', $routename)->first();
        if (!is_null($routepermission)) {
            $access = ($user_permissions[$routepermission->permission->name] & $routepermission->value);
            if (!$access) {
                return redirect()->route('session.index');
            }
        }
        
        # Checks if user has been deleted and redirects to login page
        $user = Auth::user();
        if ($user && !is_null($user->deleted_at)) {
            Auth::logout();
            Session::flush();
            Session::flash('error', ['Your account has been deleted.']);
            return redirect()->route('session.index');
        }
        
        return $next($request);
    }
}
