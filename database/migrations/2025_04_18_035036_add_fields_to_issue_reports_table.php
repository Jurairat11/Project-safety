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
        Schema::table('issue_reports', function (Blueprint $table) {
            $table->string('form_no')->nullable()->unique()->after('report_id');
            $table->string('safety_dept')->nullable()->after('form_no');
            $table->string('section')->nullable()->after('safety_dept');
            $table->date('issue_date')->nullable()->after('section');
            $table->date('dead_line')->nullable()->after('issue_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issue_reports', function (Blueprint $table) {
            $table->dropColumn(['form_no', 'safety_dept', 'section', 'issue_date', 'dead_line']);
        });
    }
};
