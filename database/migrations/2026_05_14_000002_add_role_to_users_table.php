<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users') || Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('admin')->after('password');
        });

        if (Schema::hasTable('students')) {
            DB::table('users')
                ->whereIn('email', DB::table('students')->select('email'))
                ->update(['role' => 'student']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role');
        });
    }
};
