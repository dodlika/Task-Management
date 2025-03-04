<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add estimated time column
            if (!Schema::hasColumn('tasks', 'estimated_time')) {
                $table->integer('estimated_time')->nullable()->after('due_date');
            }

            // Add actual time tracking columns
            if (!Schema::hasColumn('tasks', 'actual_time')) {
                $table->integer('actual_time')->default(0)->after('estimated_time');
            }

            if (!Schema::hasColumn('tasks', 'time_started_at')) {
                $table->timestamp('time_started_at')->nullable()->after('actual_time');
            }

            if (!Schema::hasColumn('tasks', 'time_stopped_at')) {
                $table->timestamp('time_stopped_at')->nullable()->after('time_started_at');
            }

            if (!Schema::hasColumn('tasks', 'is_tracking')) {
                $table->boolean('is_tracking')->default(false)->after('time_stopped_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop columns if they exist
            $table->dropColumnIfExists([
                'estimated_time', 
                'actual_time', 
                'time_started_at', 
                'time_stopped_at', 
                'is_tracking'
            ]);
        });
    }
};