<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // BIGINT UNSIGNED PRIMARY KEY
            
            $table->string('username')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('names')->nullable();
            $table->text('bio')->nullable();
            $table->string('role')->default('patient')->nullable();
            $table->string('address')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('gender')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otpExpires')->nullable();
            $table->boolean('verified')->default(false);

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
