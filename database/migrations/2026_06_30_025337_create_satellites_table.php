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
        Schema::create('satellites', function (Blueprint $table) {
            $table->id();
            // Satellite name
            $table->string('name');
            // Unique ID in NORAD catalogue
            $table->integer('norad_id')->unique();
            $table->string('country')->nullable();
            // Communication, Weather, Spy, Navigation
            $table->string('type')->nullable();
            $table->string('status')->default('active');
            // Two-Line Element. Mathematical orbit model. Position is calculated from it
            $table->text('tle_line1')->nullable();
            $table->text('tle_line2')->nullable();
            // LEO (low), MEO (mid), GEO (geostationary), HEO (high elliptic)
            $table->string('orbit_type')->nullable();
            // Key orbit parameters
            $table->decimal('altitude_km', 10, 2)->nullable();
            $table->decimal('velocity_kms', 8, 4)->nullable();
            $table->decimal('inclination', 6, 3)->nullable();
            // PostGIS: coordinates point (longtitude, latitude)
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->timestamp('last_tracked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satellites');
    }
};
