<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoutepermission extends Migration
{
    private $table = 'dnlx_routepermission';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('route');
            $table->integer('permission_id')->unsigned();
            $table->string('value', 5);
            
            $table->index(['id', 'permission_id'], $this->table);
        });

        DB::table($this->table)->insert(
			array(
                [ 'route' => 'audit.index', 'permission_id' => $this->getpermissionid('audit'), 'value' => 4 ],
                [ 'route' => 'group.store', 'permission_id' => $this->getpermissionid('group'), 'value' => 2 ],
                [ 'route' => 'group.index', 'permission_id' => $this->getpermissionid('group'), 'value' => 4 ],
                [ 'route' => 'group.create', 'permission_id' => $this->getpermissionid('group'), 'value' => 2 ],
                [ 'route' => 'group.delete', 'permission_id' => $this->getpermissionid('group'), 'value' => 1 ],
                [ 'route' => 'group.destroy', 'permission_id' => $this->getpermissionid('group'), 'value' => 1 ],
                [ 'route' => 'group.update', 'permission_id' => $this->getpermissionid('group'), 'value' => 2 ],
                [ 'route' => 'group.show', 'permission_id' => $this->getpermissionid('group'), 'value' => 4 ],
                [ 'route' => 'group.edit', 'permission_id' => $this->getpermissionid('group'), 'value' => 2 ],
                [ 'route' => 'group.permission.index', 'permission_id' => $this->getpermissionid('group'), 'value' => 4 ],
                [ 'route' => 'group.permission.show', 'permission_id' => $this->getpermissionid('group'), 'value' => 4 ],
                [ 'route' => 'group.permission.update', 'permission_id' => $this->getpermissionid('group'), 'value' => 2 ],
                [ 'route' => 'group.permission.edit', 'permission_id' => $this->getpermissionid('group'), 'value' => 2 ],
                [ 'route' => 'settings.edit', 'permission_id' => $this->getpermissionid('settings'), 'value' => 2 ],
                [ 'route' => 'settings.update', 'permission_id' => $this->getpermissionid('settings'), 'value' => 2 ],
                [ 'route' => 'user.store', 'permission_id' => $this->getpermissionid('user'), 'value' => 2 ],
                [ 'route' => 'user.index', 'permission_id' => $this->getpermissionid('user'), 'value' => 4 ],
                [ 'route' => 'user.create', 'permission_id' => $this->getpermissionid('user'), 'value' => 2 ],
                [ 'route' => 'user.destroy', 'permission_id' => $this->getpermissionid('user'), 'value' => 1 ],
                [ 'route' => 'user.update', 'permission_id' => $this->getpermissionid('user'), 'value' => 2 ],
                [ 'route' => 'user.show', 'permission_id' => $this->getpermissionid('user'), 'value' => 4 ],
                [ 'route' => 'user.edit', 'permission_id' => $this->getpermissionid('user'), 'value' => 2 ],
			)
		);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table);
    }
    
    private function getpermissionid($permission_name) {
      $permission = DB::table('dnlx_permission')
          ->where('name', '=', $permission_name)
          ->first();
  		return $permission->id;
	}
}
