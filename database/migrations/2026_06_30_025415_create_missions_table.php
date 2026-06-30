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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('agency')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->default('planned');
            $table->date('launch_date')->nullable();
            $table->string('launch_site')->nullable();
            $table->string('rocket')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('satellite_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
