<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountTitle extends Migration
{
    private $table = 'col_acct_title';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->integer('acct_group_id')->unsigned();
            $table->boolean('show_in_monthly');
            $table->softDeletes();
            
            $table->index(['id', 'code', 'acct_group_id'], $this->table);
        });
        
        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'code' => '4-01-01-020', 'name' => 'Professional Tax', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-02-040', 'name' => 'Real Property Tax-Basic (Net of Discount)', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-02-080', 'name' => 'Real Property Transfer Tax', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-03-040', 'name' => 'Tax on Sand, Gravel & Other Quarry Prod.', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-03-050', 'name' => 'Tax on Delivery Trucks & Vans', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-03-060', 'name' => 'Amusement Tax', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-03-070', 'name' => 'Franchise Tax', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-03-080', 'name' => 'Printing and Publication Tax', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-04-990', 'name' => 'Other Taxes (Mining Claims)', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-05-010', 'name' => 'Tax Revenue - Fines & Penalties - on Individual (PTR)', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-05-020', 'name' => 'Tax Revenue - Fines & Penalties - Property Taxes', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-05-020', 'name' => 'Tax Revenue - Fines & Penalties - Real Property Taxes', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-05-030', 'name' => 'Tax Revenue - Fines & Penalties - Goods & Services', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-06-010', 'name' => 'Share from Internal Revenue Collections (IRA)', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-06-030', 'name' => 'Share from National Wealth', 'acct_group_id' => 1, 'show_in_monthly' => 1 ],
                [ 'code' => '', 'name' => 'BAC Goods & Services', 'acct_group_id' => 1, 'show_in_monthly' => 0 ],
                [ 'code' => '', 'name' => 'BAC INFRA', 'acct_group_id' => 1, 'show_in_monthly' => 0 ],
                [ 'code' => '', 'name' => 'BAC Drugs & Meds', 'acct_group_id' => 1, 'show_in_monthly' => 0 ],
                
                [ 'code' => '4-02-01-010', 'name' => 'Permit Fees', 'acct_group_id' => 2, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-01-040', 'name' => 'Clearance & Certification Fees', 'acct_group_id' => 2, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-01-110', 'name' => 'Verification & Auth. Fees', 'acct_group_id' => 2, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-01-980', 'name' => 'Fines & Penalties - Service Income', 'acct_group_id' => 2, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-01-990', 'name' => 'Other Service Income', 'acct_group_id' => 2, 'show_in_monthly' => 1 ],
                
                [ 'code' => '4-02-02-020', 'name' => 'Affiliation Fees', 'acct_group_id' => 3, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-050', 'name' => 'Rent Income', 'acct_group_id' => 3, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-180', 'name' => 'Sales Revenue', 'acct_group_id' => 3, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-200', 'name' => 'Hospital Fees', 'acct_group_id' => 3, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-220', 'name' => 'Interest Income', 'acct_group_id' => 3, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-980', 'name' => 'Fines & Penalties - Business Income', 'acct_group_id' => 3, 'show_in_monthly' => 1 ],
                
                [ 'code' => '4-04-01-020', 'name' => 'Share from PCSO (Lotto)', 'acct_group_id' => 4, 'show_in_monthly' => 1 ],
                [ 'code' => '4-05-01-050', 'name' => 'Gain on Sale of Property, Plant & Equipment', 'acct_group_id' => 4, 'show_in_monthly' => 1 ],
                [ 'code' => '4-06-01-010', 'name' => 'Miscellaneous Income', 'acct_group_id' => 4, 'show_in_monthly' => 1 ],
                
                [ 'code' => '4-02-02-010', 'name' => 'School Fees', 'acct_group_id' => 5, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-050', 'name' => 'Rent Income', 'acct_group_id' => 5, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-220', 'name' => 'Interest Income', 'acct_group_id' => 5, 'show_in_monthly' => 1 ],
                
                [ 'code' => '4-02-01-020', 'name' => 'Registration Fees', 'acct_group_id' => 6, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-01-040', 'name' => 'Clearance and Certification Fees', 'acct_group_id' => 6, 'show_in_monthly' => 1 ],
                
                [ 'code' => '4-03-01-050', 'name' => 'Subsidy from General Fund Proper', 'acct_group_id' => 7, 'show_in_monthly' => 1 ],
                [ 'code' => '4-05-01-050', 'name' => 'Gain on Sale of Property, Plant & Equipment', 'acct_group_id' => 7, 'show_in_monthly' => 1 ],
                [ 'code' => '4-06-01-010', 'name' => 'Miscellaneous Income', 'acct_group_id' => 7, 'show_in_monthly' => 1 ],
                [ 'code' => '', 'name' => 'Supplies and Materials', 'acct_group_id' => 7, 'show_in_monthly' => 0 ],
                [ 'code' => '', 'name' => 'Transfer of Fund', 'acct_group_id' => 7, 'show_in_monthly' => 0 ],
                [ 'code' => '', 'name' => 'Insurance Premium', 'acct_group_id' => 7, 'show_in_monthly' => 0 ],
                [ 'code' => '', 'name' => 'Assessment Fee', 'acct_group_id' => 7, 'show_in_monthly' => 0 ],
                [ 'code' => '', 'name' => 'Trainors Fee', 'acct_group_id' => 7, 'show_in_monthly' => 0 ],
                
                [ 'code' => '4-02-02-050', 'name' => 'Rent Income', 'acct_group_id' => 8, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-220', 'name' => 'Interest Income', 'acct_group_id' => 8, 'show_in_monthly' => 1 ],
                
                [ 'code' => '4-03-01-050', 'name' => 'Subsidy from General Fund Proper', 'acct_group_id' => 9, 'show_in_monthly' => 1 ],
                [ 'code' => '4-06-01-010', 'name' => 'Miscellaneous Income', 'acct_group_id' => 9, 'show_in_monthly' => 1 ],
                
                [ 'code' => '4-01-02-050', 'name' => 'Special Education Tax', 'acct_group_id' => 10, 'show_in_monthly' => 1 ],
                [ 'code' => '4-01-05-020', 'name' => 'Tax Revenue - Fines & Penalties - Property Tax', 'acct_group_id' => 10, 'show_in_monthly' => 1 ],
                [ 'code' => '4-02-02-220', 'name' => 'Interest Income', 'acct_group_id' => 10, 'show_in_monthly' => 1 ],
                [ 'code' => '4-06-01-010', 'name' => 'Miscellaneous Income', 'acct_group_id' => 10, 'show_in_monthly' => 1 ],
                
                [ 'code' => '', 'name' => 'Publication Cost', 'acct_group_id' => 11, 'show_in_monthly' => 0 ],
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
