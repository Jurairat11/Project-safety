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
        Schema::create('issue_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->foreignId('prob_id')->constrained('problems','prob_id')->onDelete('cascade');
            $table->string('prob_desc');
            $table->string('hazard_level_id')->constrained('hazard_levels','hazard_level_id')->onDelete('cascade');
            $table->string('hazard_type_id')->constrained('hazard_types','hazard_type_id')->onDelete('cascade');
            $table->string('img_before');
            $table->enum('status', ['reported','in_progress','resolved','closed'])->default('reported');
            $table->foreignId('emp_id')->constrained('employees','emp_id')->onDelete('cascade');
            $table->foreignId('dept_id')->constrained('depts','dept_id')->onDelete(  'cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_reports');
    }
};
