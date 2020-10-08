<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMonthlyProvincialIncome extends Migration
{
    private $table = 'col_monthly_provincial_income';
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
            $table->integer('month')->unsigned();
            $table->decimal('value', 10, 2)->nullable();
            $table->integer('col_acct_title_id')->unsigned()->nullable();
            $table->integer('col_acct_subtitle_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['id', 'col_acct_title_id', 'col_acct_subtitle_id'], $this->table);
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
