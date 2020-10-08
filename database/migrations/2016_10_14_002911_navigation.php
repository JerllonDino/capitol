<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Navigation extends Migration
{
    private $table = 'dnlx_navigation';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon', 100);
            $table->string('name', 100)->unique()->comment('Unique and readable reference IDs');
            $table->string('title', 100);
            $table->string('route')->nullable();
            $table->integer('parent')->unsigned()->nullable()->comment('Parent IDs within this table');
            $table->integer('permission_id')->unsigned();
            $table->string('access_value', 5)->nullable()->comment('Binary values: Read=4; Write=2; Delete=1');
            $table->integer('order_value');
            
            $table->index(['id', 'name', 'parent', 'permission_id'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'icon' => 'fa-user', 'name' => 'user_nav', 'title' => 'Users', 'route' => null, 'parent' => null, 'permission_id' => 1, 'access_value' => null, 'order_value' => 95 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'user_list', 'title' => 'List', 'route' => 'user.index', 'parent' => 1, 'permission_id' => 1, 'access_value' => 4, 'order_value' => 1 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'user_create', 'title' => 'Create', 'route' => 'user.create', 'parent' => 1, 'permission_id' => 1, 'access_value' => 2, 'order_value' => 2 ],
                [ 'icon' => 'fa-users', 'name' => 'group_nav', 'title' => 'Groups', 'route' => null, 'parent' => null, 'permission_id' => 2, 'access_value' => null, 'order_value' => 96 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'group_list', 'title' => 'List', 'route' => 'group.index', 'parent' => 4, 'permission_id' => 2, 'access_value' => 4, 'order_value' => 1 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'group_create', 'title' => 'Create', 'route' => 'group.create', 'parent' => 4, 'permission_id' => 2, 'access_value' => 2, 'order_value' => 2 ],
                [ 'icon' => 'fa-file-text-o', 'name' => 'audit', 'title' => 'Audit Log', 'route' => 'audit.index', 'parent' => null, 'permission_id' => 3, 'access_value' => 4, 'order_value' => 99 ],
				[ 'icon' => 'fa-gear', 'name' => 'settings_base', 'title' => 'Global Settings', 'route' => 'settings.edit', 'parent' => null, 'permission_id' => 4, 'access_value' => 6, 'order_value' => 99 ],
                [ 'icon' => 'fa-refresh', 'name' => 'backup', 'title' => 'Backup', 'route' => 'backup.index', 'parent' => null, 'permission_id' => 4, 'access_value' => 6, 'order_value' => 99 ],
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
}
