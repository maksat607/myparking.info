<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsToPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('shortname', 100)
                ->nullable()
                ->after('name');
            $table->string('base_type')->default('public');
            $table->string('inn');
            $table->string('kpp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('shortname');
            $table->dropColumn('base_type');
            $table->dropColumn('inn');
            $table->dropColumn('kpp');
        });
    }
}
