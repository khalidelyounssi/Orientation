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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->integer('marital_status')->nullable();
            $table->integer('application_mode')->nullable();
            $table->integer('application_order')->nullable();
            $table->integer('course')->nullable();
            $table->integer('daytime_evening_attendance')->nullable();
            $table->integer('previous_qualification')->nullable();
            $table->decimal('previous_qualification_grade', 6, 2)->nullable();
            $table->integer('nacionality')->nullable();
            $table->integer('mothers_qualification')->nullable();
            $table->integer('fathers_qualification')->nullable();
            $table->integer('mothers_occupation')->nullable();
            $table->integer('fathers_occupation')->nullable();
            $table->decimal('admission_grade', 6, 2)->nullable();
            $table->boolean('displaced')->default(false);
            $table->boolean('educational_special_needs')->default(false);
            $table->boolean('debtor')->default(false);
            $table->boolean('tuition_fees_up_to_date')->default(true);
            $table->integer('gender')->nullable();
            $table->boolean('scholarship_holder')->default(false);
            $table->integer('age_at_enrollment')->nullable();
            $table->boolean('international')->default(false);

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
