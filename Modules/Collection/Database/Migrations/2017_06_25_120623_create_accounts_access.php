<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsAccess extends Migration
{
    private $table = 'col_acctount_access';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('col_acct_title_id')->unsigned()->nullable();
            $table->integer('col_acct_subtitle_id')->unsigned()->nullable();
            $table->boolean('show_in_landtax');
            $table->boolean('show_in_fieldlandtax');
            $table->boolean('show_in_cashdivision');
            $table->boolean('show_in_form51');
            $table->boolean('show_in_form56');
            $table->timestamps();

            $table->index(['id','col_acct_title_id', 'col_acct_subtitle_id', 'show_in_landtax', 'show_in_fieldlandtax', 'show_in_cashdivision', 'show_in_form51',  'show_in_form56'], $this->table);
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
