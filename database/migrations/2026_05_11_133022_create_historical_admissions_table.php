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
        Schema::create('historical_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('bac_type');
            $table->integer('bac_year')->nullable();
            $table->decimal('general_average', 5, 2);
            $table->decimal('math_score', 5, 2)->nullable();
            $table->decimal('physics_score', 5, 2)->nullable();
            $table->decimal('french_score', 5, 2)->nullable();
            $table->decimal('english_score', 5, 2)->nullable();
            $table->decimal('computer_science_score', 5, 2)->nullable();
            $table->decimal('biology_score', 5, 2)->nullable();
            $table->decimal('economics_score', 5, 2)->nullable();
            $table->boolean('has_repeated_year')->default(false);
            $table->unsignedInteger('number_of_repeated_years')->default(0);
            $table->string('first_choice')->nullable();
            $table->string('second_choice')->nullable();
            $table->string('third_choice')->nullable();
            $table->string('preferred_domain')->nullable();
            $table->string('admitted_program')->nullable();
            $table->string('followed_program')->nullable();
            $table->string('recommended_program')->nullable();
            $table->string('first_year_result')->nullable();
            $table->string('final_status')->nullable();
            $table->decimal('gpa', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historical_admissions');
    }
};
