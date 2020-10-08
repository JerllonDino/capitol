<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashDivision extends Migration
{
    private $table = 'col_cash_division';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('col_municipality_id')->unsigned()->nullable();
            $table->integer('col_barangay_id')->unsigned()->nullable();
            $table->integer('dnlx_user_id')->unsigned();
            $table->date('date_of_entry');
            $table->string('refno', 100);
            
            $table->index([
                'id',
                'col_municipality_id',
                'col_barangay_id',
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
