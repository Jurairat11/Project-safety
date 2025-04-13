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
        Schema::create('issue_responses', function (Blueprint $table) {
            $table->id('response_id');
            $table->foreignId('report_id')->constrained('issue_reports','report_id')->onDelete('cascade');
            $table->string('cause');
            $table->string('img_after');
            $table->string('temporary_act')->nullable();
            $table->string('permanent_act')->nullable();
            $table->date('temp_due_date')->nullable();
            $table->date('perm_due_date')->nullable();
            $table->foreignId('temp_responsible')
            ->nullable()
            ->constrained('employees', 'emp_id')
            ->onDelete('cascade');
            $table->foreignId('perm_responsible')
            ->nullable()
            ->constrained('employees', 'emp_id')
            ->onDelete('cascade');
            $table->string('preventive_act');
            $table->foreignId('created_by')->constrained('employees','emp_id')->onDelete('cascade');
            $table->timestamp('reply_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_responses');
    }
};
