<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddF56Detail extends Migration
{
    private $table = 'col_f56_detail';
    
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
            $table->integer('col_f56_type_id')->unsigned();
            $table->string('period_covered', 100);
            $table->decimal('basic_current', 10, 2)->nullable();
            $table->decimal('basic_discount', 10, 2)->nullable();
            $table->decimal('basic_previous', 10, 2)->nullable();
            $table->decimal('basic_penalty_current', 10, 2)->nullable();
            $table->decimal('basic_penalty_previous', 10, 2)->nullable();
            
            $table->index([
                'id',
                'col_receipt_id',
                'col_f56_type_id'
            ], $this->table);
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
