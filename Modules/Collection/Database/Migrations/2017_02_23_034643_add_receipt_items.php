<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiptItems extends Migration
{
    private $table = 'col_receipt_items';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('col_receipt_id')->unsigned();
            $table->string('nature', 300);
            $table->integer('col_acct_title_id')->unsigned();
            $table->integer('col_acct_subtitle_id')->unsigned();
            $table->decimal('value', 10, 2)->nullable();
            $table->decimal('share_provincial', 10, 2)->nullable();
            $table->decimal('share_municipal', 10, 2)->nullable();
            $table->decimal('share_barangay', 10, 2)->nullable();
            $table->softDeletes();
            $table->index(['id', 'col_receipt_id', 'col_acct_title_id', 'col_acct_subtitle_id'], $this->table);
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
