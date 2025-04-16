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
        Schema::table('problems', function (Blueprint $table) {
            $table->string('status')->default('new')->change();
            // เปลี่ยนจาก enum เป็น string เพื่อให้ยืดหยุ่นมากขึ้น
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->enum('status', ['new', 'reported', 'dismissed'])->default('new')->change();
        });
    }
};
