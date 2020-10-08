<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBarangay extends Migration
{
    private $table = 'col_barangay';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 25);
            $table->string('name', 100);
            $table->integer('municipality_id')->unsigned();
            
            $table->index(['id', 'municipality_id'], $this->table);
        });
        
        # Insert
        DB::table($this->table)->insert(
            array(
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 1 ],
                [ 'code' => '002', 'name' => 'Abiang', 'municipality_id' => 1 ],
                [ 'code' => '003', 'name' => 'Caliking', 'municipality_id' => 1 ],
                [ 'code' => '004', 'name' => 'Cattubo', 'municipality_id' => 1 ],
                [ 'code' => '005', 'name' => 'Naguey', 'municipality_id' => 1 ],
                [ 'code' => '006', 'name' => 'Paoay', 'municipality_id' => 1 ],
                [ 'code' => '007', 'name' => 'Pasdong', 'municipality_id' => 1 ],
                [ 'code' => '008', 'name' => 'Topdac', 'municipality_id' => 1 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 2 ],
                [ 'code' => '002', 'name' => 'Ampusongan', 'municipality_id' => 2 ],
                [ 'code' => '003', 'name' => 'Bagu', 'municipality_id' => 2 ],
                [ 'code' => '004', 'name' => 'Dalipey', 'municipality_id' => 2 ],
                [ 'code' => '005', 'name' => 'Gambang', 'municipality_id' => 2 ],
                [ 'code' => '006', 'name' => 'Kayapa', 'municipality_id' => 2 ],
                [ 'code' => '007', 'name' => 'Sinacbat', 'municipality_id' => 2 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 3 ],
                [ 'code' => '002', 'name' => 'Ambuclao', 'municipality_id' => 3 ],
                [ 'code' => '003', 'name' => 'Bila', 'municipality_id' => 3 ],
                [ 'code' => '004', 'name' => 'Bobok-Bisal', 'municipality_id' => 3 ],
                [ 'code' => '005', 'name' => 'Daclan', 'municipality_id' => 3 ],
                [ 'code' => '006', 'name' => 'Ekip', 'municipality_id' => 3 ],
                [ 'code' => '007', 'name' => 'Karao', 'municipality_id' => 3 ],
                [ 'code' => '008', 'name' => 'Nawal', 'municipality_id' => 3 ],
                [ 'code' => '009', 'name' => 'Pito', 'municipality_id' => 3 ],
                [ 'code' => '010', 'name' => 'Tikey', 'municipality_id' => 3 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 4 ],
                [ 'code' => '002', 'name' => 'Abatan', 'municipality_id' => 4 ],
                [ 'code' => '003', 'name' => 'Amgaleygey', 'municipality_id' => 4 ],
                [ 'code' => '004', 'name' => 'Amlimay', 'municipality_id' => 4 ],
                [ 'code' => '005', 'name' => 'Baculongan Norte', 'municipality_id' => 4 ],
                [ 'code' => '006', 'name' => 'Baculongan Sur', 'municipality_id' => 4 ],
                [ 'code' => '007', 'name' => 'Bangao', 'municipality_id' => 4 ],
                [ 'code' => '008', 'name' => 'Buyacaoan', 'municipality_id' => 4 ],
                [ 'code' => '009', 'name' => 'Calamagan', 'municipality_id' => 4 ],
                [ 'code' => '010', 'name' => 'Catlubong', 'municipality_id' => 4 ],
                [ 'code' => '011', 'name' => 'Lengaoan', 'municipality_id' => 4 ],
                [ 'code' => '012', 'name' => 'Loo', 'municipality_id' => 4 ],
                [ 'code' => '013', 'name' => 'Natubleng', 'municipality_id' => 4 ],
                [ 'code' => '014', 'name' => 'Sebang', 'municipality_id' => 4 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 5 ],
                [ 'code' => '002', 'name' => 'Ampucao', 'municipality_id' => 5 ],
                [ 'code' => '003', 'name' => 'Dalupirip', 'municipality_id' => 5 ],
                [ 'code' => '004', 'name' => 'Gumatdang', 'municipality_id' => 5 ],
                [ 'code' => '005', 'name' => 'Loakan', 'municipality_id' => 5 ],
                [ 'code' => '006', 'name' => 'Tinongdan', 'municipality_id' => 5 ],
                [ 'code' => '007', 'name' => 'Tuding', 'municipality_id' => 5 ],
                [ 'code' => '008', 'name' => 'Ucab', 'municipality_id' => 5 ],
                [ 'code' => '009', 'name' => 'Virac', 'municipality_id' => 5 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 6 ],
                [ 'code' => '002', 'name' => 'Adaoay', 'municipality_id' => 6 ],
                [ 'code' => '003', 'name' => 'Anchokey', 'municipality_id' => 6 ],
                [ 'code' => '004', 'name' => 'Bashoy', 'municipality_id' => 6 ],
                [ 'code' => '005', 'name' => 'Ballay', 'municipality_id' => 6 ],
                [ 'code' => '006', 'name' => 'Batan', 'municipality_id' => 6 ],
                [ 'code' => '007', 'name' => 'Duacan', 'municipality_id' => 6 ],
                [ 'code' => '008', 'name' => 'Eddet', 'municipality_id' => 6 ],
                [ 'code' => '009', 'name' => 'Gusaran', 'municipality_id' => 6 ],
                [ 'code' => '010', 'name' => 'Kabayan Barrio', 'municipality_id' => 6 ],
                [ 'code' => '011', 'name' => 'Lusod', 'municipality_id' => 6 ],
                [ 'code' => '012', 'name' => 'Pacso', 'municipality_id' => 6 ],
                [ 'code' => '013', 'name' => 'Tawangan', 'municipality_id' => 6 ],
                
                [ 'code' => '001', 'name' => 'Central', 'municipality_id' => 7 ],
                [ 'code' => '002', 'name' => 'Balakbak', 'municipality_id' => 7 ],
                [ 'code' => '003', 'name' => 'Beleng-Belis', 'municipality_id' => 7 ],
                [ 'code' => '004', 'name' => 'Boklaoan', 'municipality_id' => 7 ],
                [ 'code' => '005', 'name' => 'Cayapes', 'municipality_id' => 7 ],
                [ 'code' => '006', 'name' => 'Cuba', 'municipality_id' => 7 ],
                [ 'code' => '007', 'name' => 'Datakan', 'municipality_id' => 7 ],
                [ 'code' => '008', 'name' => 'Gadang', 'municipality_id' => 7 ],
                [ 'code' => '009', 'name' => 'Gaswiling', 'municipality_id' => 7 ],
                [ 'code' => '010', 'name' => 'Labueg', 'municipality_id' => 7 ],
                [ 'code' => '011', 'name' => 'Paykek', 'municipality_id' => 7 ],
                [ 'code' => '012', 'name' => 'Pudong', 'municipality_id' => 7 ],
                [ 'code' => '013', 'name' => 'Pongayan', 'municipality_id' => 7 ],
                [ 'code' => '014', 'name' => 'Sagubo', 'municipality_id' => 7 ],
                [ 'code' => '015', 'name' => 'Taba-ao', 'municipality_id' => 7 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 8 ],
                [ 'code' => '002', 'name' => 'Badeo', 'municipality_id' => 8 ],
                [ 'code' => '003', 'name' => 'Lubo', 'municipality_id' => 8 ],
                [ 'code' => '004', 'name' => 'Madaymen', 'municipality_id' => 8 ],
                [ 'code' => '005', 'name' => 'Palina', 'municipality_id' => 8 ],
                [ 'code' => '006', 'name' => 'Sagpat', 'municipality_id' => 8 ],
                [ 'code' => '007', 'name' => 'Tacadang', 'municipality_id' => 8 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 9 ],
                [ 'code' => '002', 'name' => 'Alapang', 'municipality_id' => 9 ],
                [ 'code' => '003', 'name' => 'Alno', 'municipality_id' => 9 ],
                [ 'code' => '004', 'name' => 'Ambiong', 'municipality_id' => 9 ],
                [ 'code' => '005', 'name' => 'Bahong', 'municipality_id' => 9 ],
                [ 'code' => '006', 'name' => 'Balili', 'municipality_id' => 9 ],
                [ 'code' => '007', 'name' => 'Beckel', 'municipality_id' => 9 ],
                [ 'code' => '008', 'name' => 'Bineng', 'municipality_id' => 9 ],
                [ 'code' => '009', 'name' => 'Betag', 'municipality_id' => 9 ],
                [ 'code' => '010', 'name' => 'Cruz', 'municipality_id' => 9 ],
                [ 'code' => '011', 'name' => 'Lubas', 'municipality_id' => 9 ],
                [ 'code' => '012', 'name' => 'Pico', 'municipality_id' => 9 ],
                [ 'code' => '013', 'name' => 'Puguis', 'municipality_id' => 9 ],
                [ 'code' => '014', 'name' => 'Shilan', 'municipality_id' => 9 ],
                [ 'code' => '015', 'name' => 'Tawang', 'municipality_id' => 9 ],
                [ 'code' => '016', 'name' => 'Wangal', 'municipality_id' => 9 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 10 ],
                [ 'code' => '002', 'name' => 'Balili', 'municipality_id' => 10 ],
                [ 'code' => '003', 'name' => 'Bedbed', 'municipality_id' => 10 ],
                [ 'code' => '004', 'name' => 'Bulalacao', 'municipality_id' => 10 ],
                [ 'code' => '005', 'name' => 'Cabiten', 'municipality_id' => 10 ],
                [ 'code' => '006', 'name' => 'Colalo', 'municipality_id' => 10 ],
                [ 'code' => '007', 'name' => 'Guinaoang', 'municipality_id' => 10 ],
                [ 'code' => '008', 'name' => 'Paco', 'municipality_id' => 10 ],
                [ 'code' => '009', 'name' => 'Suyoc', 'municipality_id' => 10 ],
                [ 'code' => '010', 'name' => 'Sapid', 'municipality_id' => 10 ],
                [ 'code' => '011', 'name' => 'Tabio', 'municipality_id' => 10 ],
                [ 'code' => '012', 'name' => 'Taneg', 'municipality_id' => 10 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 11 ],
                [ 'code' => '002', 'name' => 'Bagong', 'municipality_id' => 11 ],
                [ 'code' => '003', 'name' => 'Balluay', 'municipality_id' => 11 ],
                [ 'code' => '004', 'name' => 'Banangan', 'municipality_id' => 11 ],
                [ 'code' => '005', 'name' => 'Banengbeng', 'municipality_id' => 11 ],
                [ 'code' => '006', 'name' => 'Bayabas', 'municipality_id' => 11 ],
                [ 'code' => '007', 'name' => 'Kamog', 'municipality_id' => 11 ],
                [ 'code' => '008', 'name' => 'Pappa', 'municipality_id' => 11 ],
                
                [ 'code' => '001', 'name' => 'Poblacion', 'municipality_id' => 12 ],
                [ 'code' => '002', 'name' => 'Ansagan', 'municipality_id' => 12 ],
                [ 'code' => '003', 'name' => 'Camp 1', 'municipality_id' => 12 ],
                [ 'code' => '004', 'name' => 'Camp 3', 'municipality_id' => 12 ],
                [ 'code' => '005', 'name' => 'Camp 4', 'municipality_id' => 12 ],
                [ 'code' => '006', 'name' => 'Nangalisan', 'municipality_id' => 12 ],
                [ 'code' => '007', 'name' => 'San Pascual', 'municipality_id' => 12 ],
                [ 'code' => '008', 'name' => 'Tabaan Norte', 'municipality_id' => 12 ],
                [ 'code' => '009', 'name' => 'Tabaan Sur', 'municipality_id' => 12 ],
                [ 'code' => '010', 'name' => 'Tadiangan', 'municipality_id' => 12 ],
                [ 'code' => '011', 'name' => 'Taloy Norte', 'municipality_id' => 12 ],
                [ 'code' => '012', 'name' => 'Taloy Sur', 'municipality_id' => 12 ],
                [ 'code' => '013', 'name' => 'Twin Peaks', 'municipality_id' => 12 ],
                
                [ 'code' => '001', 'name' => 'Caponga (Pob.)', 'municipality_id' => 13 ],
                [ 'code' => '002', 'name' => 'Ambassador', 'municipality_id' => 13 ],
                [ 'code' => '003', 'name' => 'Ambongdolan', 'municipality_id' => 13 ],
                [ 'code' => '004', 'name' => 'Ba-ayan', 'municipality_id' => 13 ],
                [ 'code' => '005', 'name' => 'Basil', 'municipality_id' => 13 ],
                [ 'code' => '006', 'name' => 'Tublay Central', 'municipality_id' => 13 ],
                [ 'code' => '007', 'name' => 'Daclan', 'municipality_id' => 13 ],
                [ 'code' => '008', 'name' => 'Tuel', 'municipality_id' => 13 ],
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
