<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->uuid('category_id')->nullable();
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->uuid('uploaded_by');
            $table->boolean('is_active')->default(true);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('book_categories')->onDelete('set null');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('is_active');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
