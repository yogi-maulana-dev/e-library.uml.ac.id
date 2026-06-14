<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->uuid('category_id');
            $table->uuid('publisher_id')->nullable();
            $table->uuid('shelf_id')->nullable();
            $table->string('isbn')->unique()->nullable();
            $table->string('issn')->unique()->nullable();
            $table->date('publication_date')->nullable();
            $table->integer('publication_year')->nullable();
            $table->integer('pages')->nullable();
            $table->string('language')->default('id');
            $table->string('cover')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->decimal('price', 12, 2)->nullable();
            $table->enum('status', ['available', 'limited', 'unavailable'])->default('available');
            $table->text('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('book_categories')->onDelete('restrict');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
            $table->foreign('shelf_id')->references('id')->on('shelves')->onDelete('set null');
            $table->index('slug');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index(['category_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
