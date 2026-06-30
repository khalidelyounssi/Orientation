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
        Schema::table('predictions', function (Blueprint $table): void {
            if (! Schema::hasColumn('predictions', 'recommendation')) {
                $table->longText('recommendation')->nullable()->after('risk_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table): void {
            if (Schema::hasColumn('predictions', 'recommendation')) {
                $table->dropColumn('recommendation');
            }
        });
    }
};
