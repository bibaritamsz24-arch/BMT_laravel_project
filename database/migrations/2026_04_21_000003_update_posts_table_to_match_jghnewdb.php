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
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('posts', 'title')) {
                $table->string('title')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('posts', 'content')) {
                $table->text('content')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            $columnsToDrop = [];

            if (Schema::hasColumn('posts', 'title')) {
                $columnsToDrop[] = 'title';
            }

            if (Schema::hasColumn('posts', 'content')) {
                $columnsToDrop[] = 'content';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
