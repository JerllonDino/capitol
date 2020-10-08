<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColAcctSubtitle extends Migration
{
    private $table = 'col_acct_subtitle';
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
            $table->integer('col_acct_title_id')->unsigned();
            $table->boolean('show_in_monthly');
            $table->softDeletes();
            
            $table->index(['id', 'col_acct_title_id'], $this->table);
        });
        
        # Insert
        DB::table($this->table)->insert(
            array(
                # Under Property Taxes
                [ 'name' => 'Tax Revenue - Fines & Penalties - Real Property Taxes', 'col_acct_title_id' => 11, 'show_in_monthly' => 1 ],
                
                # Under Rent Income
                [ 'name' => 'General (Buildings/ Lots/ Lights & Water)', 'col_acct_title_id' => 25, 'show_in_monthly' => 1 ],
                [ 'name' => 'Benguet Cold Chain Operation', 'col_acct_title_id' => 25, 'show_in_monthly' => 1 ],
                [ 'name' => 'Lodging (OPAG)', 'col_acct_title_id' => 25, 'show_in_monthly' => 1 ],
                [ 'name' => 'Provincial Health Office (PHO)', 'col_acct_title_id' => 25, 'show_in_monthly' => 1 ],
                
                # Under Sales Revenue
                [ 'name' => 'Drugs & Medicines - 5 District Hospitals', 'col_acct_title_id' => 26, 'show_in_monthly' => 1 ],
                [ 'name' => 'Accountable Forms/ Printed Forms', 'col_acct_title_id' => 26, 'show_in_monthly' => 1 ],
                [ 'name' => 'Sales on Del. Receipts/ Books/ Appl. Fees', 'col_acct_title_id' => 26, 'show_in_monthly' => 1 ],
                [ 'name' => 'Sales on Agricultural Products', 'col_acct_title_id' => 26, 'show_in_monthly' => 1 ],
                [ 'name' => 'Sales on Veterenary Products', 'col_acct_title_id' => 26, 'show_in_monthly' => 1 ],
                
                # Under Hospital Fees
                [ 'name' => 'Medical, Dental & Laboratory Fees', 'col_acct_title_id' => 27, 'show_in_monthly' => 1 ],
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
