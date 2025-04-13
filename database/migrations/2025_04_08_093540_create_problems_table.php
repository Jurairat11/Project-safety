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
        Schema::create('problems', function (Blueprint $table) {
            $table->id('prob_id');
            $table->string('prob_desc');
            $table->foreignId('emp_id')->constrained('employees','emp_id')->onDelete('cascade');
            $table->foreignId('dept_id')->constrained('depts','dept_id')->onDelete('cascade');
            $table->string('pic_before')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('linked_report_id')->nullable()->constrained('issue_reports', 'report_id')->onDelete('set null');
            $table->enum('status', ['new', 'reported', 'dismissed'])->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
