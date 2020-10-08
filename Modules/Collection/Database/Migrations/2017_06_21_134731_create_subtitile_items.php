<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtitileItems extends Migration
{
    private $table = 'col_acct_subtitle_items';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_name');
            $table->integer('col_acct_subtitle_id')->unsigned();
            $table->boolean('show_in_monthly');
            $table->softDeletes();

            $table->index(['id', 'col_acct_subtitle_id'], $this->table);
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
