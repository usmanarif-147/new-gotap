<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compaign_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprise_id')->constrained('users');
            $table->string('subject', 255)->nullable();
            $table->string('message', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compaign_emails');
    }
};
