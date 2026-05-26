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
        if (Schema::hasColumn('degrees', 'contact_number')) {
            Schema::table('degrees', function (Blueprint $table) {
                $table->dropColumn('contact_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('degrees', 'contact_number')) {
            Schema::table('degrees', function (Blueprint $table) {
                $table->string('contact_number', 20)->nullable()->after('title');
            });
        }
    }
};
