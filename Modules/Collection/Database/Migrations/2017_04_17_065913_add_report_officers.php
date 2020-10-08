<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportOfficers extends Migration
{
    private $table = 'col_report_officers';
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
            $table->string('value');

            $table->index(['id', 'name'], $this->table);
        });

        DB::table($this->table)->insert(
			array(
                [ 'name' => 'provgov_name', 'value' => 'CRESCENCIO C. PACALSO' ],
				[ 'name' => 'provtreasurer_name', 'value' => 'IMELDA I. MACANES' ],
				[ 'name' => 'coldep_name', 'value' => 'MEGIELITA S. GALIMBA' ],
				[ 'name' => 'coldep_position', 'value' => 'SAO (Cashier IV)' ],
                [ 'name' => 'prov_name', 'value' => 'IMELDA I. MACANES' ],
				[ 'name' => 'prov_position', 'value' => 'Provincial Treasurer' ],
                [ 'name' => 'rpt_name', 'value' => 'MEGIELITA S. GALIMBA' ],
				[ 'name' => 'rpt_position', 'value' => 'SAO (Cashier IV)' ],
                [ 'name' => 'asstprovtreasurer_name', 'value' => 'JULIE V. ESTEBAN' ],
                [ 'name' => 'transfertax_name', 'value' => 'ISABEL D. KIW-AN' ],
				[ 'name' => 'transfertax_position', 'value' => 'Local Revenue Collection Officer IV' ],
                [ 'name' => 'vicegov_name', 'value' => 'FLORENCE B. TINGBAOEN' ],
                [ 'name' => 'trust_fund_officer_name', 'value' => 'IRENE C. BAGKING' ],
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
        Schema::drop($this->table);
    }
}
