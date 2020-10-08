<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditsTable extends Migration
{
    private $table = 'dnlx_audits';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('auditable');
            $table->text('old')->nullable();
            $table->text('new')->nullable();
            $table->string('user_id')->nullable();
            $table->string('route')->nullable();
            $table->ipAddress('ip_address', 45)->nullable();
            $table->timestamp('created_at');
            
            $table->index(['id', 'type', 'user_id', 'ip_address'], $this->table);
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
