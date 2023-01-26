<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalParkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_parking', function (Blueprint $table) {
            $table->id();

            $table->foreignId('legal_id')->nullable()
                ->constrained('legals')
                ->onDelete('cascade');;
            $table->foreignId('parking_id')->nullable()
                ->constrained('parkings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legal_parking');
    }
}
