<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->integer('car_key_quantity')->nullable()->after('pts');
                $table->boolean('pts_provided')->default(false)->nullable()->after('pts');
                $table->boolean('sts_provided')->default(false)->nullable()->after('pts');
                $table->string('pts_type')->nullable()->after('pts');
                $table->boolean('favorite')->default(false)->nullable()->after('on_sale');
                $table->foreignId('accepted_by')->nullable()->after('created_user_id');
                $table->foreignId('issued_by')->nullable()->after('created_user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('car_key_quantity');
                $table->dropColumn('pts_provided');
                $table->dropColumn('sts_provided');
                $table->dropColumn('pts_type');
                $table->dropColumn('favorite');
                $table->dropColumn('accepted_by');
                $table->dropColumn('issued_by');
            });
        }
    }
}
