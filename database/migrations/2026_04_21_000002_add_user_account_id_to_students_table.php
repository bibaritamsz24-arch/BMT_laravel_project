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
        if (Schema::hasColumn('students', 'user_account_id')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_account_id')
                ->nullable()
                ->after('id')
                ->constrained('user_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('students', 'user_account_id')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_account_id');
        });
    }
};
