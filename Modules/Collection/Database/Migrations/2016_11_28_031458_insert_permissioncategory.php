<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissioncategory extends Migration
{
	public $permcat_name = 'Collection';
	private $permcat_desc = 'Revenue Collection permissions';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # Insert
        DB::table('dnlx_permission_category')->insert(
            [
                'name' => $this->permcat_name,
                'description' => $this->permcat_desc,
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
        # Delete entry only
        DB::table('dnlx_permission_category')
            ->where('name', '=', $this->permcat_name)
            ->where('description', '=', $this->permcat_desc)
            ->delete();
    }
}
