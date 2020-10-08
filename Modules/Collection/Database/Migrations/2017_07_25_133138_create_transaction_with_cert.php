<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionWithCert extends Migration
{
  private $table = 'col_transaction_with_cert';

    public function up()
    {
           Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trans_id')->unsigned();
            $table->integer('process_status')->unsigned();
            $table->index(['id','trans_id', 'process_status'], $this->table);
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
