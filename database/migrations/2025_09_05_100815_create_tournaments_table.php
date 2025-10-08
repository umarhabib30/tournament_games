<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('time_to_enter')->nullable();
            $table->enum('open_close', ['open', 'close'])->default('open');
            $table->enum('time_or_free', ['time', 'free_form'])->default('time');
            $table->enum('elimination_type', ['percentage', 'all'])->default('all');
            $table->integer('elimination_percent')->nullable();
            $table->integer('rounds')->nullable();
            $table->string('url')->nullable();
            $table->enum('status', ['active','inactive', 'inprogress', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
