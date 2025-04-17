<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ลบตารางเก่า ถ้ามีอยู่
        Schema::dropIfExists('issue_reports');

        // สร้างใหม่
        Schema::create('issue_reports', function (Blueprint $table) {
            $table->id('report_id');

            // ความสัมพันธ์กับ Problem
            $table->foreignId('prob_id')->constrained('problems', 'prob_id')->onDelete('cascade');

            // รายละเอียดจาก problem
            $table->text('prob_desc');

            $table->string('hazard_level_id');
            $table->string('hazard_type_id');
            $table->string('img_before')->nullable();

            // ไม่มี CHECK constraint ใน status
            $table->string('status')->default('reported');
            $table->string('created_by');

            // Reporter
            $table->string('emp_id');
            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');

            $table->foreignId('dept_id')->constrained('depts', 'dept_id')->onDelete('cascade');

            // หน่วยงานผู้รับผิดชอบ
            $table->foreignId('responsible_dept_id')->nullable()->constrained('depts', 'dept_id')->nullOnDelete();

            $table->text('issue_desc')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_reports');
    }
};
