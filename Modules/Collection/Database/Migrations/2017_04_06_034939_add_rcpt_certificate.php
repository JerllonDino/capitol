<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRcptCertificate extends Migration
{
    private $table = 'col_rcpt_certificate';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('col_receipt_id')->unsigned();
            $table->integer('col_rcpt_certificate_type_id')->unsigned();
            $table->string('recipient');
            $table->string('address');
            $table->string('detail', 5000);
            $table->date('date_of_entry');
            $table->string('provincial_governor');
            $table->string('actingprovincial_governor')->nullable();
            $table->string('provincial_treasurer');
            $table->string('asstprovincial_treasurer')->nullable();
            $table->string('user');
            $table->string('provincial_note')->nullable();
            $table->string('provincial_clearance_number')->nullable();
            $table->string('provincial_type')->nullable();
            $table->boolean('provincial_bidding')->nullable();
            $table->string('transfer_notary_public')->nullable();
            $table->string('transfer_ptr_number')->nullable();
            $table->string('transfer_doc_number')->nullable();
            $table->string('transfer_page_number')->nullable();
            $table->string('transfer_book_number')->nullable();
            $table->string('transfer_series')->nullable();
            $table->string('transfer_prepare_name')->nullable();
            $table->string('transfer_prepare_position')->nullable();
            $table->string('sand_requestor')->nullable();
            $table->string('sand_requestor_addr')->nullable();
            $table->string('sand_requestor_sex')->nullable();
            $table->string('sand_type')->nullable();
            $table->string('sand_sandgravelprocessed')->nullable();
            $table->string('sand_abc')->nullable();
            $table->string('sand_sandgravel')->nullable();
            $table->string('sand_boulders')->nullable();
            $table->index([
                'id',
                'col_receipt_id',
                'col_rcpt_certificate_type_id',
            ], $this->table);
        });
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
