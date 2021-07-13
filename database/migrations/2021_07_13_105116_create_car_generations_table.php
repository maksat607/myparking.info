<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarGenerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_generations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('year_begin')->nullable();
            $table->integer('year_end')->nullable();
            $table->foreignId('car_model_id')->nullable();

            $table->integer('rank')->default(0)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_generations');
    }
}
