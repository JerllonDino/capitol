<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountCategory extends Migration
{
    private $table = 'col_acct_category';
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
            $table->SoftDeletes();
            
            $table->index(['id'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'name' => 'General Fund-Proper' ],
                [ 'name' => 'Benguet Technical School (BTS)' ],
                [ 'name' => 'Benguet Equipment Services Enterprise (BESE)' ],
                [ 'name' => 'Special Education Fund (SEF)' ],
                [ 'name' => 'Trust Fund' ],
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
