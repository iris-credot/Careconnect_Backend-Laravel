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
    Schema::create('doctors', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('specialization');
        $table->string('license_number')->unique();
        $table->string('education')->nullable(); // instead of qualifications
        $table->string('hospital_affiliation')->nullable(); // add this
        $table->integer('experience_years')->nullable(); // instead of experience
        $table->decimal('consultation_fee', 10, 2)->nullable();
        $table->boolean('is_available')->default(true);
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
