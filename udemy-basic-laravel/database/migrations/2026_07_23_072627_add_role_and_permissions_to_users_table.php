<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; // এটি নিশ্চিত করুন
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // এখানে Subtable এর জায়গায় Blueprint হবে
        Schema::table('users', function (Blueprint $table) {
            // রোল ডিফল্ট হিসেবে 'subscriber' বা সাধারণ ইউজার থাকবে
            $table->string('role')->default('subscriber')->after('is_approved');
            
            // কোন কোন পেজ এডিট করতে পারবে তা অ্যারে আকারে রাখার জন্য text কলাম
            $table->text('allowed_pages')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'allowed_pages']);
        });
    }
};
