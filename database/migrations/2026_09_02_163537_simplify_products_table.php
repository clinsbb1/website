<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Products dropped their case-study and SEO fields — there is no
     * /work/{slug} page anymore, just the homepage + a full listing page.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('short_description', 'description');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->change();

            $table->dropColumn([
                'overview',
                'metrics',
                'technologies',
                'case_study_enabled',
                'problem',
                'role',
                'what_we_built',
                'technical_approach',
                'outcome',
                'image',
                'og_image',
                'seo_title',
                'seo_description',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('overview')->nullable();
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
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('description', 'short_description');
        });
    }
};
