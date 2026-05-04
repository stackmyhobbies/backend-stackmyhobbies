<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            $table->date('aired_from')->nullable()->after('viewing_finished_at');
            $table->date('aired_to')->nullable()->after('aired_from');
        });
    }

    public function down(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            $table->dropColumn(['aired_from', 'aired_to']);
        });
    }
};
