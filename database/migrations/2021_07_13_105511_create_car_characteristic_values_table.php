<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarCharacteristicValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_characteristic_values', function (Blueprint $table) {
            $table->id();
            $table->string('value', 100)->index();
            $table->string('unit', 100);
            $table->foreignId('car_characteristic_id')->nullable();
            $table->foreignId('car_modification_id')->nullable();

            $table->integer('rank')->default(0)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['car_characteristic_id', 'car_modification_id'], 'characteristic_modification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_characteristic_values');
    }
}
