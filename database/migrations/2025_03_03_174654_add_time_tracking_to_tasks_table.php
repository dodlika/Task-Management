<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('estimated_time')->nullable(); // in minutes
            $table->integer('actual_time')->default(0); // in minutes
            $table->timestamp('time_started_at')->nullable();
            $table->timestamp('time_stopped_at')->nullable();
            $table->boolean('is_tracking')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_time', 
                'actual_time', 
                'time_started_at', 
                'time_stopped_at', 
                'is_tracking'
            ]);
        });
    }
};