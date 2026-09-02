<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->json('content_json')->nullable();
            $table->string('category')->nullable();
            $table->string('status')->default('draft'); // draft | published | archived
            $table->boolean('featured')->default(false);
            $table->string('feature_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('medium_url')->nullable();
            $table->string('devto_url')->nullable();
            $table->string('paragraph_url')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
