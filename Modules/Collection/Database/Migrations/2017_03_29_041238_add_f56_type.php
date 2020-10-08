<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddF56Type extends Migration
{
    private $table = 'col_f56_type';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            
            $table->index(['id'], $this->table);
        });
        
        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'name' => 'Residential' ],
                [ 'name' => 'Agricultural' ],
                [ 'name' => 'Commercial' ],
                [ 'name' => 'Industrial' ],
                [ 'name' => 'Mineral' ],
                [ 'name' => 'Special' ],
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
        Schema::dropIfExists($this->table);
    }
}
