<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // নতুন রেজিস্ট্রেশনের পর ইউজার ডিফল্টভাবে পেন্ডিং (0) থাকবে
            $table->boolean('is_approved')->default(0)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // রোলব্যাক করার সময় কলামটি মুছে যাবে
            $table->dropColumn('is_approved');
        });
    }
};
