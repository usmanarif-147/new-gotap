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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('enterprise_id')
                ->nullable()->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('profile_id')
                ->nullable()->constrained('profiles')
                ->onDelete('cascade');
            $table->foreignId('card_id')
                ->nullable()->constrained('cards')
                ->onDelete('cascade');
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 255)->nullable();
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
        Schema::dropIfExists('leads');
    }
};
