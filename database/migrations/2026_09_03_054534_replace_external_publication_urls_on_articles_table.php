<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * An article can only ever have been cross-posted to one place, so the
     * three separate medium_url/devto_url/paragraph_url columns collapse
     * into a single platform + url pair. Where more than one was somehow
     * set, the first found (medium, then devto, then paragraph) wins —
     * nothing is fabricated, just narrowed to one per the new UI.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('external_platform')->nullable()->after('canonical_url');
            $table->string('external_url')->nullable()->after('external_platform');
        });

        foreach (['medium' => 'medium_url', 'devto' => 'devto_url', 'paragraph' => 'paragraph_url'] as $platform => $column) {
            DB::table('articles')->whereNotNull($column)->where('external_platform', null)->update([
                'external_platform' => $platform,
                'external_url' => DB::raw($column),
            ]);
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['medium_url', 'devto_url', 'paragraph_url']);
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('medium_url')->nullable();
            $table->string('devto_url')->nullable();
            $table->string('paragraph_url')->nullable();
        });

        foreach (['medium' => 'medium_url', 'devto' => 'devto_url', 'paragraph' => 'paragraph_url'] as $platform => $column) {
            DB::table('articles')->where('external_platform', $platform)->update([
                $column => DB::raw('external_url'),
            ]);
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['external_platform', 'external_url']);
        });
    }
};
