<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertNavigation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # Insert
        DB::table('dnlx_navigation')->insert(
            array(
                [ 'icon' => 'fa-book', 'name' => 'col_serial', 'title' => 'Serial', 'route' => 'serial.index', 'parent' => null, 'permission_id' => $this->getpermission('col_serial')->id, 'access_value' => 6, 'order_value' => 1 ],
                [ 'icon' => 'fa-file-o', 'name' => 'col_receipt', 'title' => 'Land Tax Collections', 'route' => 'receipt.index', 'parent' => null, 'permission_id' => $this->getpermission('col_receipt')->id, 'access_value' => 6, 'order_value' => 2 ],
                [ 'icon' => 'fa-file-o', 'name' => 'col_cash_division', 'title' => 'Cash Division Collections', 'route' => 'cash_division.index', 'parent' => null, 'permission_id' => $this->getpermission('col_cash_division')->id, 'access_value' => 6, 'order_value' => 3 ],
                [ 'icon' => 'fa-file-o', 'name' => 'col_field_land_tax', 'title' => 'Field Land Tax Collections', 'route' => 'field_land_tax.index', 'parent' => null, 'permission_id' => $this->getpermission('col_field_land_tax')->id, 'access_value' => 6, 'order_value' => 4 ],
                [ 'icon' => 'fa-file-o', 'name' => 'col_monthly_provincial_income', 'title' => 'Monthly Provincial Income', 'route' => 'monthly_provincial_income.index', 'parent' => null, 'permission_id' => $this->getpermission('col_monthly_provincial_income')->id, 'access_value' => 6, 'order_value' => 5 ],
                [ 'icon' => 'fa-address-card-o', 'name' => 'col_customer', 'title' => 'Customer/Payor', 'route' => 'customer.index', 'parent' => null, 'permission_id' => $this->getpermission('col_customer')->id, 'access_value' => 6, 'order_value' => 6 ],
				[ 'icon' => 'fa-files-o', 'name' => 'col_reports_nav', 'title' => 'Collection Reports', 'route' => null, 'parent' => null, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => null, 'order_value' => 7 ],
				[ 'icon' => 'fa-gear', 'name' => 'col_settings_nav', 'title' => 'Collection Settings', 'route' => null, 'parent' => null, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => null, 'order_value' => 99 ],
            )
        );

		DB::table('dnlx_navigation')->insert(
            array(
                # Reports
				[ 'icon' => 'fa-angle-double-right', 'name' => 'col_provincial_income', 'title' => 'Provincial Income', 'route' => 'report.provincial_income', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_collections_deposits', 'title' => 'Collections and Deposits', 'route' => 'report.collections_deposits', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_real_property', 'title' => 'Real Property Tax', 'route' => 'report.real_property', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_shared_report', 'title' => 'Report on Shared & BAC', 'route' => 'report.shared', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],

                # Settings
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_acct_category', 'title' => 'Account Categories', 'route' => 'account_category.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 1 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_acct_grp', 'title' => 'Account Groups', 'route' => 'account_group.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 2 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_title', 'title' => 'Account Titles', 'route' => 'account_title.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 3 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_subtitle', 'title' => 'Account Subtitles', 'route' => 'account_subtitle.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 4 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_budget', 'title' => 'Budget Estimate', 'route' => 'budget_estimate.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 5 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_rates', 'title' => 'Collection Rates', 'route' => 'rates.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 6 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_holidays', 'title' => 'Holidays', 'route' => 'holiday_settings.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 7 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_report_officers', 'title' => 'Report Officers', 'route' => 'settings_report_officers.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 8 ],
                [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_ada', 'title' => 'ADA Settings', 'route' => 'settings_ada.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 9 ],

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
        $col_nav = array(
            'col_serial',
            'col_settings_nav',
            'col_settings_budget',
            'col_settings_rates',
            'col_settings_subtitle',
            'col_settings_title',
            'col_settings_acct_grp',
            'col_settings_acct_category',
			'col_receipt',
			'col_customer',
			'col_provincial_income',
			'col_collections_deposits',
			'col_reports_nav'
        );
		DB::table('dnlx_navigation')
            ->whereIn('name', $col_nav)
            ->delete();

    }

	private function getpermission($permission_name) {
		$permission = DB::table('dnlx_permission')
			->where('name', '=', $permission_name)
			->first();
		return $permission;
	}

	private function getnav($nav_name) {
		$nav = DB::table('dnlx_navigation')
			->where('name', '=', $nav_name)
			->first();
		return $nav;
	}
}
