<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBudgetEstimate extends Migration
{
    private $table = 'col_budget_estimate';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year')->unsigned();
            $table->decimal('value', 10, 2)->nullable();
            $table->integer('col_acct_title_id')->unsigned()->nullable();
            $table->integer('col_acct_subtitle_id')->unsigned()->nullable();
            $table->integer('col_acct_subtitleitems_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->index(['id', 'year', 'col_acct_title_id', 'col_acct_subtitle_id','col_acct_subtitleitems_id'], $this->table);
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
