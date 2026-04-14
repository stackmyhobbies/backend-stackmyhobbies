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
        Schema::table('content_items', function (Blueprint $table) {
            //
            $table->string('segment_subtype')->nullable()->after('segment_label');
            $table->integer('segment_subnumber')->nullable()->after('segment_subtype');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            //
            $table->dropColumn('segment_subtype');
            $table->dropColumn('segment_subnumber');
        });
    }
};
