<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BacCollections extends Migration
{
    private $table = 'col_bac_collections';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dnlx_user_id')->unsigned();
            $table->integer('type')->unsigned()->comment('1: Goods & Services; 2: INFRA; 3: Drugs & Meds');
            $table->decimal('value', 10, 2)->nullable();
            $table->date('date_of_entry');
            
            $table->index([
                'id',
                'dnlx_user_id',
                'date_of_entry',
            ], $this->table);
        });
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
