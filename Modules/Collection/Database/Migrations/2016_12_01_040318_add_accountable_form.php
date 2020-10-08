<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountableForm extends Migration
{
    private $table = 'col_acctble_form';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            
            $table->index(['id'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'name' => 'Form 51' ],
                [ 'name' => 'Form 56' ],
            )
        );
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
