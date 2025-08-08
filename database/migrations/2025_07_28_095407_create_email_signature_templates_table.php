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
    Schema::create('email_signature_templates', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('profile_id');
        $table->string('name');
        $table->json('fields')->nullable();
        $table->json('images')->nullable();
        $table->json('preview_data')->nullable();
        $table->timestamps();

        $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_signature_templates');
    }
};
