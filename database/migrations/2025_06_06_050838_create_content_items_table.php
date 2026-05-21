<?php

use App\Models\ContentType;
use App\Models\ProgressStatus;
use App\Models\User;
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
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();

            // ** Campos principales
            $table->text('description')->nullable();
            // En tu archivo de migración
            $table->string('image_path', 255)->nullable()->after('slug');
            $table->dateTime('viewing_started_at')->nullable();
            $table->dateTime('viewing_finished_at')->nullable();
            $table->integer('current_progress')->nullable();
            $table->integer('total_progress')->nullable();
            $table->enum('progress_unit', ['episodes', 'pages', 'minutes', 'chapters'])->default('episodes');
            $table->decimal('rating', 3, 1)->nullable();
            $table->text('notes')->nullable();

            // **  Llaves foraneas **//
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(ContentType::class, 'content_type_id')->constrained('content_types')->onDelete('cascade');
            $table->foreignIdFor(ProgressStatus::class, 'progress_status_id')->constrained('progress_statuses')->onDelete('cascade');

            $table->enum('segment_type', ['season', 'volume', 'part', 'edition', 'movie'])->nullable()->after('status_id');
            $table->unsignedInteger('segment_number')->nullable()->after('segment_type');

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};
