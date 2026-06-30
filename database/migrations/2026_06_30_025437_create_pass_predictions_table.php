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
        Schema::create('pass_predictions', function (Blueprint $table) {
            $table->id();
            // Related satellite
            $table->foreignId('satellite_id')->constrained()->cascadeOnDelete();
            // User who requested the prediction
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('city')->nullable();
            // Observer's coordinates
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            // Pass timing
            // Appears above horizon
            $table->timestamp('rise_time');
            // Closest approach
            $table->timestamp('culmination_time');
            // Disappears below horizon
            $table->timestamp('set_time');
            // Max elevation angle in degrees
            $table->decimal('max_elevation', 5, 2);
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pass_predictions');
    }
};
