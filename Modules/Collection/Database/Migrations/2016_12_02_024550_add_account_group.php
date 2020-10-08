<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountGroup extends Migration
{
    private $table = 'col_acct_group';
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
            $table->integer('acct_category_id')->unsigned();
            $table->SoftDeletes();
            
            $table->index(['id', 'acct_category_id'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'name' => 'Tax Revenue', 'acct_category_id' => 1 ],
                [ 'name' => 'Service Income', 'acct_category_id' => 1 ],
                [ 'name' => 'Business Income', 'acct_category_id' => 1 ],
                [ 'name' => 'Share, Grants & Donations/Gains/Misc. Income', 'acct_category_id' => 1 ],
                [ 'name' => 'Business Income', 'acct_category_id' => 2 ],
                [ 'name' => 'Service Income', 'acct_category_id' => 2 ],
                [ 'name' => 'Transfers, Assistance & Subsidy/Gain/Misc. Income', 'acct_category_id' => 2 ],
                [ 'name' => 'Business Income', 'acct_category_id' => 3 ],
                [ 'name' => 'Transfer, Assistance and Subsidy/Misc.', 'acct_category_id' => 3 ],
                [ 'name' => 'Tax Revenue', 'acct_category_id' => 4 ],
                [ 'name' => 'Particulars', 'acct_category_id' => 5 ],
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
