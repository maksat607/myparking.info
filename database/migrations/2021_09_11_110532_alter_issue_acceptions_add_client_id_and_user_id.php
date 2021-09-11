<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterIssueAcceptionsAddClientIdAndUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issue_acceptions', function (Blueprint $table) {
            $table->foreignId('client_id')
                ->nullable()
                ->after('application_id')
                ->constrained('clients')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->nullable()
                ->after('client_id')
                ->constrained('users')
                ->onDelete('cascade');
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
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
