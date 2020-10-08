<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		# Find Permissioncategory
        $permissioncategory = $this->getpermissioncategory();
		
		# Insert
		DB::table('dnlx_permission')->insert(
  			array(
                [ 'name' => 'col_serial', 'description' => 'Serial transactions', 'permissioncategory_id' => $permissioncategory->id ],
                [ 'name' => 'col_receipt', 'description' => 'Land Tax transactions', 'permissioncategory_id' => $permissioncategory->id ],
                [ 'name' => 'col_field_land_tax', 'description' => 'Field Land Tax transactions', 'permissioncategory_id' => $permissioncategory->id ],
                [ 'name' => 'col_cash_division', 'description' => 'Cash Division transactions', 'permissioncategory_id' => $permissioncategory->id ],
                #[ 'name' => 'col_bac_income', 'description' => 'BAC Income transactions', 'permissioncategory_id' => $permissioncategory->id ],
                [ 'name' => 'col_monthly_provincial_income', 'description' => 'Monthly Provincial Income', 'permissioncategory_id' => $permissioncategory->id ],
                [ 'name' => 'col_customer', 'description' => 'Customer/Payor transactions', 'permissioncategory_id' => $permissioncategory->id ],
                [ 'name' => 'col_reports', 'description' => 'Reports', 'permissioncategory_id' => $permissioncategory->id ],
                [ 'name' => 'col_settings', 'description' => 'Settings', 'permissioncategory_id' => $permissioncategory->id ],
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
		# Find inserted entries and delete them
        $permissioncategory = $this->getpermissioncategory();
		
		DB::table('dnlx_permission')
            ->where('permissioncategory_id', '=', $permissioncategory->id)
            ->delete();
    }
	
	private function getpermissioncategory() {
      $permissioncategory = DB::table('dnlx_permission_category')
          ->where('name', '=', 'Collection')
          ->first();
  		return $permissioncategory;
	}
}
