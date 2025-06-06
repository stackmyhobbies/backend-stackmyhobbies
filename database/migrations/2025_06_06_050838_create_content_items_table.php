<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\ContentStatus;
use App\Models\ContentType;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();

            //**  Llaves foraneas **//
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(ContentType::class, 'type_id')->constrained('content_types')->onDelete('cascade');
            $table->foreignIdFor(ContentStatus::class, 'status_id')->constrained('content_statuses')->onDelete('cascade');

            //** Campos principales
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image_url', 255)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('current_progress')->nullable();
            $table->integer('total_progress')->nullable();
            $table->integer('rating')->nullable();
            $table->text('notes')->nullable();
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
