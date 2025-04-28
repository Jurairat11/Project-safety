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
            $table->unsignedBigInteger('prob_id');
            $table->string('prob_desc');
            $table->string('hazard_level_id')->constrained('hazard_levels','hazard_level_id')->onDelete('cascade');
            $table->string('hazard_type_id')->constrained('hazard_types','hazard_type_id')->onDelete('cascade');
            $table->string('img_before');
            $table->enum('status', ['reported','in_progress','resolved','closed'])->default('reported');
            $table->unsignedBigInteger('emp_id');
            $table->unsignedBigInteger('dept_id');

            $table->unsignedBigInteger('responsible_dept_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('prob_id')->references('prob_id')->on('problems')->onDelete('cascade');
            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');
            $table->foreign('dept_id')->references('dept_id')->on('depts')->onDelete('cascade');
            $table->foreign('responsible_dept_id')->references('dept_id')->on('depts')->onDelete('set null');
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
