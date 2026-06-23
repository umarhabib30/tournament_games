<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->boolean('results_published')->default(false)->after('status');
            $table->timestamp('results_published_at')->nullable()->after('results_published');
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn(['results_published', 'results_published_at']);
        });
    }
};
