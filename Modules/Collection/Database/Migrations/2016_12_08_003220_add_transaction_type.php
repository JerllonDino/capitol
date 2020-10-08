<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionType extends Migration
{
    private $table = 'col_transaction_type';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            
            $table->index(['id'], $this->table);
        });
        
        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'name' => 'Cash' ],
                [ 'name' => 'Check' ],
                [ 'name' => 'Money Order' ],
                [ 'name' => 'ADA - LBP' ],
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
