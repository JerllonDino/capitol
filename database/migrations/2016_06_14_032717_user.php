<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class User extends Migration
{
    private $table = 'dnlx_user';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('realname');
            $table->string('username');
            $table->string('position');
            $table->string('password');
            $table->string('email');
            $table->integer('group_id')->unsigned();
            $table->string('remember_token');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['id', 'username'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            [
                'realname' => 'Administrator',
                'username' => 'root',
                'position' => 'Administrator',
                'password' => \Hash::make('root'),
                'email' => '',
                'group_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
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
