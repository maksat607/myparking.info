<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_requests', function (Blueprint $table) {
            $table->id();
            $table->string('client_name')->nullable();
            $table->string('organization_name')->nullable();
            $table->text('comment')->nullable();

            $table->foreignId('status_id')->default(1);
            $table->foreignId('application_id');
            $table->foreignId('created_user_id')->nullable();

            $table->timestamp('arriving_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->string('arriving_interval')->nullable();

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
        Schema::dropIfExists('view_requests');
    }
}
