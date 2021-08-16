<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->string('car_title')->nullable();
            $table->string('vin')->nullable();
            $table->string('license_plate')->nullable()->comment('Госномер');
            $table->string('sts')->nullable()->comment('Свидетельство о регистрации');
            $table->string('pts')->nullable()->comment('Паспорт транспортного средства');
            
            $table->integer('year')->nullable();
            $table->integer('milage')->nullable();
            $table->integer('owner_number')->nullable();
            $table->string('color')->nullable();
            $table->boolean('on_sale')->nullable()->default(0);
            $table->double('price', 12,2)->nullable();
            $table->json('exterior_damage')->nullable();
            $table->json('interior_damage')->nullable();
            $table->json('condition_electric')->nullable();
            $table->json('condition_engine')->nullable();
            $table->json('condition_gear')->nullable();
            $table->json('condition_transmission')->nullable();

            $table->json('services')->nullable();
            $table->text('car_additional')->nullable();

            $table->foreignId('car_type_id')->nullable();
            $table->foreignId('car_mark_id')->nullable();
            $table->foreignId('car_model_id')->nullable();
            $table->foreignId('car_generation_id')->nullable();
            $table->foreignId('car_series_id')->nullable();
            $table->foreignId('car_modification_id')->nullable();
            $table->foreignId('car_engine_id')->nullable();
            $table->foreignId('car_transmission_id')->nullable();
            $table->foreignId('car_gear_id')->nullable();

            $table->string('internal_id')->nullable()->unique();
            $table->string('external_id')->nullable()->unique();
            $table->string('arriving_method')->nullable()->default('0');
            $table->foreignId('tow_truck_payment_id')->nullable();
            $table->foreignId('parking_id')->nullable();
            $table->foreignId('partner_id')->nullable();
            $table->foreignId('presentation_contract_id')->nullable();
            $table->string('courier_fullname')->nullable();
            $table->string('courier_phone')->nullable();
            $table->foreignId('courier_type_id')->nullable();
            $table->foreignId('client_id')->nullable();
            $table->foreignId('responsible_user_id')->nullable();

            $table->string('parking_place_number')->nullable();
            $table->text('parking_car_sticker')->nullable();
            $table->text('parking_cost')->nullable();
            $table->text('parked_days')->nullable();

            $table->foreignId('status_id')->default(1);
            $table->foreignId('created_user_id')->nullable();

            $table->string('arriving_interval')->nullable();
            $table->timestamp('arriving_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('applications');
    }
}
