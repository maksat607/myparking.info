<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('remember_token')
                ->constrained('users')
                ->onDelete('cascade');
            $table->boolean('status')->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_parent_id_foreign');
            $table->dropColumn('parent_id');
            $table->dropColumn('status');
        });
    }
}
