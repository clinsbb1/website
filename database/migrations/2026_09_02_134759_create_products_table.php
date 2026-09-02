<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description');
            $table->text('overview')->nullable();
            $table->string('status')->default('In development'); // Live | In development | In progress | Archived
            $table->string('website_url')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('metrics')->nullable();
            $table->json('technologies')->nullable();
            $table->boolean('case_study_enabled')->default(false);
            $table->text('problem')->nullable();
            $table->text('role')->nullable();
            $table->text('what_we_built')->nullable();
            $table->text('technical_approach')->nullable();
            $table->text('outcome')->nullable();
            $table->string('image')->nullable();
            $table->string('og_image')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();

            $table->index(['published', 'featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
