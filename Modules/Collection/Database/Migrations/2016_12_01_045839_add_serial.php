<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSerial extends Migration
{
    private $table = 'col_serial';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('acctble_form_id')->unsigned();
            $table->bigInteger('serial_begin');
            $table->bigInteger('serial_end');
            $table->date('date_added');
            $table->string('unit', 100)->nullable();
            $table->integer('acct_cat_id')->unsigned()->nullable();
            $table->integer('municipality_id')->unsigned()->nullable();
            $table->bigInteger('serial_current');
            
            $table->index(['id', 'acctble_form_id', 'date_added'], $this->table);
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
