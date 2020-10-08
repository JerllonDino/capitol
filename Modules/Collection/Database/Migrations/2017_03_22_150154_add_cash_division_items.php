<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashDivisionItems extends Migration
{
    private $table = 'col_cash_division_items';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('col_cash_division_id')->unsigned();
            $table->integer('col_acct_title_id')->unsigned();
            $table->integer('col_acct_subtitle_id')->unsigned();
            $table->decimal('value', 10, 2)->nullable();
            $table->decimal('share_provincial', 10, 2)->nullable();
            $table->decimal('share_municipal', 10, 2)->nullable();
            $table->decimal('share_barangay', 10, 2)->nullable();
            
            $table->index(['id', 'col_cash_division_id', 'col_acct_title_id', 'col_acct_subtitle_id'], $this->table);
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
