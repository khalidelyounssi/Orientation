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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('prediction_label');
            $table->boolean('is_dropout_risk');
            $table->decimal('dropout_probability', 6, 4);
            $table->decimal('dropout_probability_percent', 5, 2);
            $table->decimal('threshold', 4, 2)->default(0.50);
            $table->string('risk_level')->nullable();
            $table->longText('recommendation')->nullable();
            $table->string('model_name')->nullable();
            $table->string('model_version')->nullable();
            $table->json('api_response')->nullable();
            $table->foreignId('predicted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
