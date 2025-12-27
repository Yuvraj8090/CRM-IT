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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Lead basic info
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone', 20);
            $table->string('profession')->nullable();

            // Lead status as text
            $table->string('lead_status')->nullable();

            // Package reference
            $table->foreignId('package_id')
                  ->constrained('packages')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
