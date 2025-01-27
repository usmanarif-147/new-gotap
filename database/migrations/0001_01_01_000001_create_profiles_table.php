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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('enterprise_id')
                ->nullable()->constrained('users')
                ->onDelete('cascade');
            $table->string('type')->default('social');
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('work_position', 255)->nullable();
            $table->string('job_title', 255)->nullable();
            $table->string('company', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('bio', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('photo', 255)->nullable();
            $table->tinyInteger('is_leads_enabled')->default(0);
            $table->string('cover_photo', 255)->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('user_direct')->default(0);
            $table->integer('tiks')->default(0);
            $table->integer('taps')->default(0);
            $table->tinyInteger('private')->default(0);
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
        Schema::dropIfExists('profiles');
    }
};
