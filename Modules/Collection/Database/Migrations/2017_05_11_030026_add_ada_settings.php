<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdaSettings extends Migration
{
    private $table = 'col_ada_settings';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('label', 100);
            $table->string('value', 100);
            
            $table->index(['id'], $this->table);
        });
        
        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'label' => 'bank_name', 'value' => 'GF-LBP'],
                [ 'label' => 'bank_number', 'value' => '1372-0023-16'],
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
