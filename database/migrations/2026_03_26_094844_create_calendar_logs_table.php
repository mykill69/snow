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
        Schema::create('calendar_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('task_id');
        $table->unsignedBigInteger('user_id'); // user performing the action
        $table->string('action'); // created, updated, status_changed, date_changed
        $table->text('remarks')->nullable(); // optional remarks
        $table->string('old_status')->nullable();
        $table->string('new_status')->nullable();
        $table->dateTime('old_start_date')->nullable();
        $table->dateTime('new_start_date')->nullable();
        $table->dateTime('old_end_date')->nullable();
        $table->dateTime('new_end_date')->nullable();
        $table->timestamps();

        $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_logs');
    }
};
