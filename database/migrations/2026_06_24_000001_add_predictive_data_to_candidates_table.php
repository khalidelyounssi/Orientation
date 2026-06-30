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
        Schema::table('candidates', function (Blueprint $table): void {
            if (! Schema::hasColumn('candidates', 'external_student_id')) {
                $table->string('external_student_id')->nullable()->after('id')->index();
            }

            if (! Schema::hasColumn('candidates', 'academic_status')) {
                $table->string('academic_status')->nullable()->after('external_student_id');
            }

            if (! Schema::hasColumn('candidates', 'predictive_data')) {
                $table->json('predictive_data')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table): void {
            if (Schema::hasColumn('candidates', 'predictive_data')) {
                $table->dropColumn('predictive_data');
            }

            if (Schema::hasColumn('candidates', 'academic_status')) {
                $table->dropColumn('academic_status');
            }

            if (Schema::hasColumn('candidates', 'external_student_id')) {
                $table->dropColumn('external_student_id');
            }
        });
    }
};
