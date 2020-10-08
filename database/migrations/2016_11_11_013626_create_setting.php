<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetting extends Migration
{
    private $table = 'dnlx_settings';
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
            $table->string('value');
            
            $table->index(['id', 'name'], $this->table);
        });

        DB::table($this->table)->insert(
			array(
				[ 'name' => 'logo', 'value' => '' ],
				[ 'name' => 'audit_life', 'value' => '' ],
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
        Schema::drop($this->table);
    }
}
