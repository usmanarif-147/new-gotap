<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('email_signature')->nullable()->after('cover_photo');
            $table->string('virtual_background', 255)->nullable()->after('email_signature');
            $table->boolean('email_signature_enabled')->default(false)->after('virtual_background');
            $table->boolean('virtual_background_enabled')->default(false)->after('email_signature_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['email_signature', 'virtual_background', 'email_signature_enabled', 'virtual_background_enabled']);
        });
    }
}; 