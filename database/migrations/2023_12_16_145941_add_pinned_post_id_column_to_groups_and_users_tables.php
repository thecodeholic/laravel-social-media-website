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
        Schema::table('groups', function (Blueprint $table) {
            $table->foreignId('pinned_post_id')->nullable()->constrained('posts');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pinned_post_id')->nullable()->constrained('posts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('pinned_post_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pinned_post_id');
        });
    }
};
