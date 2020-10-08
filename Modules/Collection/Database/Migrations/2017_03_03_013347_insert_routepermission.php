<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRoutepermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # Insert
		DB::table('dnlx_routepermission')->insert(
  			array(
                [ 'route' => 'account_category.store', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_category.index', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_category.create', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_category.show', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_category.destroy', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 1 ],
                [ 'route' => 'account_category.update', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_category.edit', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_group.store', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_group.index', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_group.create', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_group.show', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_group.destroy', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 1 ],
                [ 'route' => 'account_group.update', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_group.edit', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_subtitle.index', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_subtitle.store', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_subtitle.create', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_subtitle.update', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_subtitle.destroy', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 1 ],
                [ 'route' => 'account_subtitle.show', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_subtitle.edit', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_title.index', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_title.store', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_title.create', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_title.show', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'account_title.update', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'account_title.destroy', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 1 ],
                [ 'route' => 'account_title.edit', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'budget_estimate.index', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'budget_estimate.store', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'budget_estimate.create', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'budget_estimate.destroy', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 1 ],
                [ 'route' => 'budget_estimate.update', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'budget_estimate.show', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 4 ],
                [ 'route' => 'budget_estimate.edit', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'customer.store', 'permission_id' => $this->getpermissionid('col_customer'), 'value' => 2 ],
                [ 'route' => 'customer.index', 'permission_id' => $this->getpermissionid('col_customer'), 'value' => 6 ],
                [ 'route' => 'customer.create', 'permission_id' => $this->getpermissionid('col_customer'), 'value' => 2 ],
                [ 'route' => 'customer.update', 'permission_id' => $this->getpermissionid('col_customer'), 'value' => 2 ],
                [ 'route' => 'customer.destroy', 'permission_id' => $this->getpermissionid('col_customer'), 'value' => 1 ],
                [ 'route' => 'customer.show', 'permission_id' => $this->getpermissionid('col_customer'), 'value' => 4 ],
                [ 'route' => 'customer.edit', 'permission_id' => $this->getpermissionid('col_customer'), 'value' => 2 ],
                [ 'route' => 'cash_division.store', 'permission_id' => $this->getpermissionid('col_cash_division'), 'value' => 2 ],
                [ 'route' => 'cash_division.index', 'permission_id' => $this->getpermissionid('col_cash_division'), 'value' => 6 ],
                [ 'route' => 'cash_division.create', 'permission_id' => $this->getpermissionid('col_cash_division'), 'value' => 2 ],
                [ 'route' => 'cash_division.update', 'permission_id' => $this->getpermissionid('col_cash_division'), 'value' => 2 ],
                [ 'route' => 'cash_division.show', 'permission_id' => $this->getpermissionid('col_cash_division'), 'value' => 4 ],
                [ 'route' => 'cash_division.destroy', 'permission_id' => $this->getpermissionid('col_cash_division'), 'value' => 1 ],
                [ 'route' => 'cash_division.edit', 'permission_id' => $this->getpermissionid('col_cash_division'), 'value' => 2 ],
                [ 'route' => 'pdf.provincial_income', 'permission_id' => $this->getpermissionid('col_reports'), 'value' => 7 ],
                [ 'route' => 'rates.index', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 6 ],
                [ 'route' => 'rates.update', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'rates.edit', 'permission_id' => $this->getpermissionid('col_settings'), 'value' => 2 ],
                [ 'route' => 'receipt.store', 'permission_id' => $this->getpermissionid('col_receipt'), 'value' => 2 ],
                [ 'route' => 'receipt.index', 'permission_id' => $this->getpermissionid('col_receipt'), 'value' => 6 ],
                [ 'route' => 'receipt.destroy', 'permission_id' => $this->getpermissionid('col_receipt'), 'value' => 1 ],
                [ 'route' => 'receipt.update', 'permission_id' => $this->getpermissionid('col_receipt'), 'value' => 2 ],
                [ 'route' => 'receipt.show', 'permission_id' => $this->getpermissionid('col_receipt'), 'value' => 4 ],
                [ 'route' => 'receipt.edit', 'permission_id' => $this->getpermissionid('col_receipt'), 'value' => 2 ],
                [ 'route' => 'report.provincial_income', 'permission_id' => $this->getpermissionid('col_reports'), 'value' => 7 ],
                [ 'route' => 'serial.store', 'permission_id' => $this->getpermissionid('col_serial'), 'value' => 2 ],
                [ 'route' => 'serial.index', 'permission_id' => $this->getpermissionid('col_serial'), 'value' => 6 ],
                [ 'route' => 'serial.destroy', 'permission_id' => $this->getpermissionid('col_serial'), 'value' => 1 ],
                [ 'route' => 'serial.update', 'permission_id' => $this->getpermissionid('col_serial'), 'value' => 2 ],
                [ 'route' => 'serial.show', 'permission_id' => $this->getpermissionid('col_serial'), 'value' => 4 ],
                [ 'route' => 'serial.edit', 'permission_id' => $this->getpermissionid('col_serial'), 'value' => 2 ],
                [ 'route' => 'field_land_tax.store', 'permission_id' => $this->getpermissionid('col_field_land_tax'), 'value' => 2 ],
                [ 'route' => 'field_land_tax.index', 'permission_id' => $this->getpermissionid('col_field_land_tax'), 'value' => 6 ],
                [ 'route' => 'field_land_tax.update', 'permission_id' => $this->getpermissionid('col_field_land_tax'), 'value' => 2 ],
                [ 'route' => 'field_land_tax.show', 'permission_id' => $this->getpermissionid('col_field_land_tax'), 'value' => 4 ],
                [ 'route' => 'field_land_tax.destroy', 'permission_id' => $this->getpermissionid('col_field_land_tax'), 'value' => 1 ],
                [ 'route' => 'field_land_tax.edit', 'permission_id' => $this->getpermissionid('col_field_land_tax'), 'value' => 2 ],
                [ 'route' => 'field_land_tax.cancel', 'permission_id' => $this->getpermissionid('col_field_land_tax'), 'value' => 3 ],
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
        $collection_permissions = [
            $this->getpermissionid('col_serial'),
            $this->getpermissionid('col_receipt'),
            $this->getpermissionid('col_customer'),
            $this->getpermissionid('col_reports'),
            $this->getpermissionid('col_settings')
        ];
        DB::table('dnlx_routepermission')
            ->whereIn('permission_id', $collection_permissions)
            ->delete();
    }
    
    private function getpermissionid($permission_name) {
      $permission = DB::table('dnlx_permission')
          ->where('name', '=', $permission_name)
          ->first();
  		return $permission->id;
	}
}
