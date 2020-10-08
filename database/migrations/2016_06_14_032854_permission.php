<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Permission extends Migration
{
    private $table = 'dnlx_permission';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique;
            $table->string('description');
            $table->integer('permissioncategory_id')->unsigned();
            
            $table->index(['id', 'permissioncategory_id'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'name' => 'user', 'description' => 'User transactions', 'permissioncategory_id' => 1 ],
                [ 'name' => 'group', 'description' => 'Group transactions', 'permissioncategory_id' => 1 ],
                [ 'name' => 'audit', 'description' => 'Audit Log', 'permissioncategory_id' => 1 ],
                [ 'name' => 'settings', 'description' => 'Settings', 'permissioncategory_id' => 1 ],
                [ 'name' => 'backup', 'description' => 'Backup', 'permissioncategory_id' => 1 ],
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
