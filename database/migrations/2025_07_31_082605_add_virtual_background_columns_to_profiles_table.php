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
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'virtual_background')) {
                $table->string('virtual_background')->nullable();
            }
            if (!Schema::hasColumn('profiles', 'virtual_background_enabled')) {
                $table->boolean('virtual_background_enabled')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['virtual_background', 'virtual_background_enabled']);
        });
    }
};
