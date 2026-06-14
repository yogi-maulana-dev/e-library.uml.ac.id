<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('book_id');
            $table->dateTime('borrow_date');
            $table->dateTime('due_date');
            $table->dateTime('returned_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'borrowed', 'returned', 'cancelled', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->uuid('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->boolean('is_overdue')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('restrict');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
            $table->index('status');
            $table->index('is_overdue');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
