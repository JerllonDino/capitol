<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRcptCertificateType extends Migration
{
    private $table = 'col_rcpt_certificate_type';
    
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
                [ 'name' => 'Provincial Permit' ],
                [ 'name' => 'Transfer Tax' ],
                [ 'name' => 'Sand & Gravel' ],
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
