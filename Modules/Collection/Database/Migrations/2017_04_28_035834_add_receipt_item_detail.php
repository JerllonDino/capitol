<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiptItemDetail extends Migration
{
    private $table = 'col_receipt_item_detail';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('col_receipt_item_id')->unsigned();
            $table->integer('col_collection_rate_id')->unsigned();
            $table->string('label', 100);
            $table->decimal('value', 10, 2)->nullable();
            $table->boolean('sched_is_perunit')->nullable();
            $table->string('sched_unit', 100)->nullable();
            $table->softDeletes();
            $table->index(['id', 'col_receipt_item_id', 'col_collection_rate_id'], $this->table);
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
