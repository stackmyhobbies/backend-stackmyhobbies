<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            $table->renameColumn('start_date', 'viewing_started_at');
            $table->renameColumn('end_date', 'viewing_finished_at');
        });
    }

    public function down(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            $table->renameColumn('viewing_started_at', 'start_date');
            $table->renameColumn('viewing_finished_at', 'end_date');
        });
    }
};
