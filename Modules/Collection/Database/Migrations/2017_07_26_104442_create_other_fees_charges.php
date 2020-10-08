<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherFeesCharges extends Migration
{
   private $table = 'col_rcpt_other_fees_charges';
    public function up()
    {
         Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receipt_id')->unsigned();
            $table->string('fees_charges');
            $table->decimal('ammount',13,2);
            $table->string('or_number');
            $table->datetime('fees_date');
            $table->index(['id','receipt_id', 'or_number', 'fees_date'], $this->table);
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
