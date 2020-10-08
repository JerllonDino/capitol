<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceipt extends Migration
{
    private $table = 'col_receipt';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('serial_no');
            $table->integer('af_type')->unsigned();
            $table->integer('col_serial_id')->unsigned();
            $table->integer('col_municipality_id')->unsigned()->nullable();
            $table->integer('col_barangay_id')->unsigned()->nullable();
            $table->integer('dnlx_user_id')->unsigned();
            $table->integer('col_customer_id')->unsigned();
            $table->date('report_date');
            $table->date('date_of_entry');
            $table->boolean('is_printed');
            $table->boolean('is_cancelled');
            $table->string('cancelled_remark', 100);
            $table->string('transaction_source', 50);
            $table->string('transaction_type', 100);
            $table->string('bank_name', 100);
            $table->string('bank_number', 100);
            $table->string('bank_date', 100);
            $table->string('bank_remark', 100);
            $table->string('remarks', 500);
            $table->softDeletes();
            $table->index([
                'id',
                'serial_no',
                'col_serial_id',
                'col_municipality_id',
                'col_barangay_id',
                'dnlx_user_id',
                'col_customer_id',
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
