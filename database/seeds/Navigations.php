<?php

use Illuminate\Database\Seeder;

class Navigations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dnlx_navigation')->insert(
            array(
                // [ 'icon' => 'fa-book', 'name' => 'col_sandgravel_mnthly', 'title' => 'MONTHLY SAND ans GRAVEL', 'route' => 'sandgravel.monthly', 'parent' => null, 'permission_id' => $this->getpermission('col_monthly_provincial_income')->id, 'access_value' => 6, 'order_value' => 6 ],
                // [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_set_pc_mac', 'title' => 'Accesss PC\'s ', 'route' => 'settings_access.set_pc', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 10 ],
                // [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_subtitle_items', 'title' => 'Account Subtitles Items ', 'route' => 'account_subtitle_items.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 4 ],
                // [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_account_access', 'title' => 'Account Access ', 'route' => 'account_access.index', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 11 ],
                // [ 'icon' => 'fa-angle-double-right', 'name' => 'col_settings_sandgravel_type', 'title' => 'Sand and Gracel Types ', 'route' => 'sandgravel.types_clients', 'parent' => $this->getnav('col_settings_nav')->id, 'permission_id' => $this->getpermission('col_settings')->id, 'access_value' => 6, 'order_value' => 12 ],

                // # Reports
                [ 'icon' => 'fa-angle-double-right', 'name' => 'encoders_report', 'title' => 'Staff Report', 'route' => 'staff.encoders_report', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],
                // [ 'icon' => 'fa-angle-double-right', 'name' => 'accounts_report', 'title' => 'Accounts Report', 'route' => 'report.accounts_report', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],
                 // [ 'icon' => 'fa-angle-double-right', 'name' => 'amusements', 'title' => 'Amusements Report', 'route' => 'report.amusements_report', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],
                 // [ 'icon' => 'fa-angle-double-right', 'name' => 'cash_division_daily_report', 'title' => 'Cash Division Daily Report', 'route' => 'report.cashdiv_daily_report', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],

                // [ 'icon' => 'fa-angle-double-right', 'name' => 'report.sandgravel_report_municpality', 'title' => 'SAND AND GRAVEL MUNICIPAL REPORTS', 'route' => 'report.sandgravel_report_municpality', 'parent' => $this->getnav('col_reports_nav')->id, 'permission_id' => $this->getpermission('col_reports')->id, 'access_value' => 6, 'order_value' => 1 ],

            )
        );
    }


    private function getnav($nav_name) {
        $nav = DB::table('dnlx_navigation')
            ->where('name', '=', $nav_name)
            ->first();
        return $nav;
    }

    private function getpermission($permission_name) {
        $permission = DB::table('dnlx_permission')
            ->where('name', '=', $permission_name)
            ->first();
        return $permission;
    }
}
