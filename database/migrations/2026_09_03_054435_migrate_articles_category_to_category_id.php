<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Categories are now a managed table (add/edit/delete in the admin)
     * instead of free-text. Any existing free-text values are turned into
     * real Category rows so nothing is silently lost.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('excerpt')
                ->constrained('categories')->nullOnDelete();
        });

        $values = DB::table('articles')->whereNotNull('category')->distinct()->pluck('category');

        foreach ($values as $name) {
            $categoryId = DB::table('categories')->where('name', $name)->value('id');

            if (! $categoryId) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('articles')->where('category', $name)->update(['category_id' => $categoryId]);
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('category')->nullable();
        });

        DB::table('articles')->orderBy('id')->each(function ($article) {
            $name = $article->category_id
                ? DB::table('categories')->where('id', $article->category_id)->value('name')
                : null;

            DB::table('articles')->where('id', $article->id)->update(['category' => $name]);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
