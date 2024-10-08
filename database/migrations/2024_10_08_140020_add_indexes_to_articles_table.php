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
        Schema::table('articles', function (Blueprint $table) {
            // Adding full-text index to title and content columns
            $table->fullText(['title', 'content']);

            // Adding regular indexes
            $table->index('source');
            $table->index('category');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Dropping the indexes
            $table->dropFullText(['title', 'content']);
            $table->dropIndex(['source']);
            $table->dropIndex(['category']);
            $table->dropIndex(['published_at']);
        });
    }
};
