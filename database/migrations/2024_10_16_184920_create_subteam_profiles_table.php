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
        Schema::create('subteam_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subteam_id')->constrained('subteams');
            $table->foreignId('profile_id')->constrained('profiles');
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
        Schema::dropIfExists('subteam_profiles');
    }
};
