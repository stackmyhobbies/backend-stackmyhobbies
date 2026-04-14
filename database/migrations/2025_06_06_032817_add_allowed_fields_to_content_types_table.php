<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_types', function (Blueprint $table) {
            $table->json('allowed_units')->nullable();
            $table->json('allowed_segment_types')->nullable();
            $table->json('allowed_subsegment_types')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('content_types', function (Blueprint $table) {
            $table->dropColumn('allowed_units');
            $table->dropColumn('allowed_segment_types');
            $table->dropColumn('allowed_subsegment_types');
        });
    }
};