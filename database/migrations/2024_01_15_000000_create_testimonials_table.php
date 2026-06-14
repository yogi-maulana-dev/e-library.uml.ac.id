<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('position')->nullable();
            $table->string('avatar')->nullable();
            $table->text('content');
            $table->integer('rating')->default(5);
            $table->boolean('is_approved')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
