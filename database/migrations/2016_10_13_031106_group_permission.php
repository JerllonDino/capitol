<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupPermission extends Migration
{
    private $table = 'dnlx_group_permission';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->string('value', 5)->comment('Binary values: Read=4; Write=2; Delete=1');
            
            $table->index(['id', 'group_id', 'permission_id'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'group_id' => 1, 'permission_id' => 1, 'value' => 7 ],
                [ 'group_id' => 1, 'permission_id' => 2, 'value' => 7 ],
                [ 'group_id' => 1, 'permission_id' => 3, 'value' => 7 ],
                [ 'group_id' => 1, 'permission_id' => 4, 'value' => 7 ],
                [ 'group_id' => 1, 'permission_id' => 5, 'value' => 7 ],
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
