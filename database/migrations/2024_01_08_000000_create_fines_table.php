<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('borrowing_id');
            $table->uuid('user_id');
            $table->decimal('amount', 12, 2);
            $table->integer('days_overdue')->default(0);
            $table->text('reason');
            $table->enum('status', ['pending', 'paid', 'waived', 'cancelled'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('payment_notes')->nullable();
            $table->uuid('paid_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('borrowing_id')->references('id')->on('borrowings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('paid_by')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
