<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTableLegals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legals', function (Blueprint $table) {
            $table->string('kpp', 255)
                ->after('inn');
            $table->boolean('status')
                ->after('user_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legals', function (Blueprint $table) {
            $table->dropColumn('kpp');
            $table->dropColumn('status');
        });
    }
}
