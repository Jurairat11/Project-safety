<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('issue_responses', function (Blueprint $table) {
            $table->string('status')->default('pending_review')->change();
        });
    }

    public function down(): void
    {
        Schema::table('issue_responses', function (Blueprint $table) {
            $table->string('status')->default('resolved')->change(); // กลับไปค่าเดิม
        });
    }
};
