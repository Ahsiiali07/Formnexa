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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 60)->default('invoicing')->index();
            $table->string('category_label', 80)->default('Invoicing & Billing');
            $table->text('excerpt');
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->string('featured_tool_route', 100)->nullable();
            $table->string('featured_tool_label', 100)->nullable();
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('focus_keyword', 120)->nullable();
            $table->unsignedSmallInteger('reading_time_minutes')->default(4);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
