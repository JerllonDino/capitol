<?php

namespace App\Http\Controllers;

use App\Models\Navigation;

use App\Models\Permission;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

use App\Http\Requests;

class NavigationController extends Controller
{
    # Generates navigation based on user's permissions
    public static function generate() {
        # Return if user isn't logged in
        if (empty(Session::get('permission'))) {
            return;
        }
        
        # Gets 'root' links of navigation based on user's permissions
        $user_permissions = Session::get('permission');
        $permission_names = array_keys($user_permissions);
        $permission_ids = Permission::whereIn('name', $permission_names)
            ->get(['id'])
            ->toArray();
        $root_links = Navigation::where('parent', null)
            ->whereIn('permission_id', $permission_ids)
            ->orderBy('order_value', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
        
        $navigation = array();
        
        foreach ($root_links as $root_link) {
            $permission_name = $root_link->Permission->name;
            
            # Has children
            if ($root_link->access_value === null) {
                $validchildren = self::getvalidchildren($root_link->id, $user_permissions[$permission_name]);
                if (!empty($validchildren)) {
                    array_push($navigation, self::addlink($root_link, $validchildren));
                }
            }
            
            # No children
            if ($user_permissions[$permission_name] & $root_link->access_value) {
                array_push($navigation, self::addlink($root_link));
            }
        }
        // dd($root_links);
        return $navigation;
    }
    
    private static function addlink($link, $children = []) {
        return [
            'icon' => $link->icon,
            'title' => $link->title,
            'route' => $link->route,
            'children' => $children,
        ];
    }
    
    # Gets accessible sub-links/children of root link
    private static function getvalidchildren($parent_id, $permission) {
        $children = Navigation::where('parent', $parent_id)
            ->orderBy('order_value', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
        
        $validchildren = array();
        foreach ($children as $child) {
            if ($permission & $child->access_value) {
                array_push($validchildren, self::addlink($child));
            }
        }
        
        return $validchildren;
    }
}
