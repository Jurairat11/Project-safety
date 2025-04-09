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
        Schema::create('safeties', function (Blueprint $table) {
            $table->id('safety_id');
            $table->foreignId('prob_id')->constrained('problems','prob_id')->onDelete('cascade');
            $table->foreignId('hazard_level_id')->constrained('hazard_levels','hazard_level_id')->onDelete('cascade');
            $table->foreignId('hazard_type_id')->constrained('hazard_types','hazard_type_id')->onDelete('cascade');
            $table->string('pic_before');
            $table->string('pic_after');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safeties');
    }
};
