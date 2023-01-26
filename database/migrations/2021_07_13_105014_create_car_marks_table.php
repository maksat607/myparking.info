<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_marks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('name_rus', 100)->nullable();
            $table->foreignId('car_type_id')->default(1)->nullable();

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
        Schema::dropIfExists('car_marks');
    }
}
