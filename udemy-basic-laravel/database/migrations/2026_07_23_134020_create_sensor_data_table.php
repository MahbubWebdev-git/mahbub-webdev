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
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->float('temperature'); // তাপমাত্রা সেভ করার জন্য
            $table->float('humidity');    // আর্দ্রতা সেভ করার জন্য
            $table->string('device_id')->nullable(); // আরডুইনো ডিভাইসের ইউনিক আইডি (নিরাপত্তার জন্য)
            $table->timestamps(); // এটি স্বয়ংক্রিয়ভাবে তৈরি হওয়ার তারিখ ও সময় (created_at) সেভ রাখবে
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
