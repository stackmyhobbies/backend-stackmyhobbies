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
        Schema::table('content_types', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
        });

        foreach (DB::table('content_types')->get() as $row) {
            DB::table('content_types')
                ->where('id', $row->id)
                ->update(['slug' => Str::slug($row->name)]);
        }

        Schema::table('content_types', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('content_types', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
