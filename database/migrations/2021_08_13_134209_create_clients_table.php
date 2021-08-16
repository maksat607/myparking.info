<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('passport')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('description')->nullable();

            $table->string('preferred_contact_method')->nullable();

            $table->string('organization_name')->nullable();
            $table->string('organization_address')->nullable();
            $table->string('organization_phone')->nullable();

            $table->string('inn')->nullable()->comment('ИНН КПП');
            $table->string('ogrn')->nullable()->comment('ОГРН');
            $table->string('issuance_document')->nullable();
            $table->integer('legal_type_id')->default(1)->comment('1-физ, 2-юр');

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
        Schema::dropIfExists('clients');
    }
}
