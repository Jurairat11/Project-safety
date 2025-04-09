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
        Schema::table('hazard_levels', function (Blueprint $table) {
            $table->renameColumn('Desc','Level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazard_levels', function (Blueprint $table) {
            $table->renameColumn('Level','Desc');
        });
    }
};
