<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertGroupPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # Insert
        $groups = DB::table('dnlx_group')->get();
        $permissions = $this->getcollectionpermissions();
        
        $group_permissions = array();
        foreach ($groups as $group) {
            foreach ($permissions as $permission) {
                array_push(
                    $group_permissions,
                    [
                        'group_id' => $group->id, 
                        'permission_id' => $permission->id,
                        'value' => 0
                    ]
                );
            }
        }
        
        DB::table('dnlx_group_permission')->insert($group_permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permissions = array_column($this->getcollectionpermissions(), 'id');
        DB::table('dnlx_group_permission')
            ->whereIn('permission_id', $permissions)
            ->delete();
    }
	
	private function getcollectionpermissions() {
        $permissions = DB::table('dnlx_permission')
            ->join('dnlx_permission_category', 'dnlx_permission_category.id', '=', 'dnlx_permission.permissioncategory_id')
            ->where('dnlx_permission_category.name', '=', 'Collection')
            ->get(['dnlx_permission.id']);
        
        return $permissions;
    }
}
