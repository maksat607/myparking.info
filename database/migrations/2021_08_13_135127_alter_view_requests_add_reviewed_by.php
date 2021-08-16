<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterViewRequestsAddReviewedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('applications')) {
            Schema::table('view_requests', function (Blueprint $table) {
                $table->foreignId('reviewed_by')->nullable()->after('created_user_id');
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
            Schema::table('view_requests', function (Blueprint $table) {
                $table->dropColumn('reviewed_by');
            });
        }
    }
}
