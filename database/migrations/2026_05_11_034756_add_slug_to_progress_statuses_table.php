<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progress_statuses', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
        });

        foreach (DB::table('progress_statuses')->get() as $row) {
            DB::table('progress_statuses')
                ->where('id', $row->id)
                ->update(['slug' => Str::slug($row->name)]);
        }

        Schema::table('progress_statuses', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('progress_statuses', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
