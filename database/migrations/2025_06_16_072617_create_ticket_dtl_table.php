<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_dtl', function (Blueprint $table) {
            // First, drop the foreign key constraint (if any)
            $table->dropForeign(['admin_id']);
        });

        Schema::table('ticket_dtl', function (Blueprint $table) {
            // Then, change the column type to JSON or TEXT
            $table->json('admin_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ticket_dtl', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->change();
        });

        Schema::table('ticket_dtl', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
