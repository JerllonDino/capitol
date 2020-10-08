<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Permissioncategory extends Migration
{
    private $table = 'dnlx_permission_category';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            
            $table->index(['id', 'name'], $this->table);
        });
        
        # Insert
        DB::table($this->table)->insert(
            [
                'name' => 'Base',
                'description' => 'Base permissions',
            ]
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
