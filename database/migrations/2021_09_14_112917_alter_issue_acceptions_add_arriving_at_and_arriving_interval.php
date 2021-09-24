<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterIssueAcceptionsAddArrivingAtAndArrivingInterval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issue_acceptions', function (Blueprint $table) {
            $table->timestamp('arriving_at')->nullable()->after('is_issue');
            $table->string('arriving_interval')->nullable()->after('arriving_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issue_acceptions', function (Blueprint $table) {
            $table->dropColumn('arriving_at');
            $table->dropColumn('arriving_interval');
        });
    }
}
