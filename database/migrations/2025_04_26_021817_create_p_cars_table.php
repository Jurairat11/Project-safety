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
        Schema::create('p_cars', function (Blueprint $table) {
            $table->id('form_no')->unique();
            $table->string('safety_dept')->nullable();
            $table->string('section')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('dead_line')->nullable();
            $table->string('issue_desc')->nullable();
            $table->string('hazard_level_id')->nullable()->constrained('hazard_levels','hazard_level_id')->nullOnDelete();
            $table->string('hazard_type_id')->nullable()->constrained('hazard_types','hazard_type_id')->nullOnDelete();
            $table->string('img_before')->nullable();
            $table->string('img_after')->nullable();
            $table->string('cause')->nullable();
            $table->string('temporary_act')->nullable();
            $table->date('temp_due_date')->nullable();
            $table->string('temp_responsible')->nullable();
            $table->string('permanent_act')->nullable();
            $table->date('perm_due_date')->nullable();
            $table->string('perm_responsible')->nullable();
            $table->string('preventive_act')->nullable();
            $table->string('status')->nullable();
            $table->string('parent_id')->nullable();


            $table->foreignId('report_id')->nullable()->constrained('issue_reports','report_id')->nullOnDelete();
            $table->foreignId('response_id')->nullable()->constrained('issue_responses','response_id')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_cars');
    }
};
