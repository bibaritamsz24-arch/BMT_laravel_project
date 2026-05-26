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
        if (! Schema::hasTable('user_accounts') || Schema::hasColumn('user_accounts', 'must_change_password')) {
            return;
        }

        Schema::table('user_accounts', function (Blueprint $table): void {
            $table->boolean('must_change_password')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('user_accounts') || ! Schema::hasColumn('user_accounts', 'must_change_password')) {
            return;
        }

        Schema::table('user_accounts', function (Blueprint $table): void {
            $table->dropColumn('must_change_password');
        });
    }
};
