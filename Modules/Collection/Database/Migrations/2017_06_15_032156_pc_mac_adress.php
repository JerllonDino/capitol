<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PcMacAdress extends Migration
{
    private $table = 'col_pc_settings';

    public function up()
    {
         Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('pc_name', 100);
            $table->string('pc_mac', 100)->nullable();
            $table->string('pc_ip', 100)->nullable();
            $table->integer('pc_receipt')->nullable()->comment('refer to col_serial');
            $table->string('process_type');
            $table->string('form_type')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['pc_name','pc_mac','pc_ip','pc_receipt'], $this->table);
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
