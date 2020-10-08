<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMunicipality extends Migration
{
    private $table = 'col_municipality';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('code', 100);
            $table->index(['id'], $this->table);
        });

        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'name' => 'Atok'                  , 'code' => '11' ],
                [ 'name' => 'Bakun'                 , 'code' => '9'  ],
                [ 'name' => 'Bokod'                 , 'code' => '13'  ],
                [ 'name' => 'Buguias'               , 'code' => '8'  ],
                [ 'name' => 'Itogon'                , 'code' => '1'  ],
                [ 'name' => 'Kabayan'               , 'code' => '12'  ],
                [ 'name' => 'Kapangan'              , 'code' => '7'  ],
                [ 'name' => 'Kibungan'              , 'code' => '10'  ],
                [ 'name' => 'La Trinidad'           , 'code' => '3'  ],
                [ 'name' => 'Mankayan'              , 'code' => '2'  ],
                [ 'name' => 'Sablan'                , 'code' => '5'  ],
                [ 'name' => 'Tuba'                  , 'code' => '4'  ],
                [ 'name' => 'Tublay'                , 'code' => '6'  ],
                [ 'name' => 'Other Cities/Prov.'    , 'code' => '0'  ],
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
