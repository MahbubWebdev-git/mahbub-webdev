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
        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->string('blog_category_id')->nullable();
                $table->string('blog_title')->nullable();
                $table->string('blog_image')->nullable();
                $table->string('blog_tags')->nullable();
                $table->text('blog_description')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('blogs') && Schema::hasColumn('blogs', 'blog_tages') && !Schema::hasColumn('blogs', 'blog_tags')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->renameColumn('blog_tages', 'blog_tags');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
