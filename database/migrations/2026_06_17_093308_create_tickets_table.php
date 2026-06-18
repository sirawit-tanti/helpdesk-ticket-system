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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no')->unique();

            $table->foreignId('requester_id')
                ->constrained('users')
                ->restrictOnDelete();
                
            $table->foreignId('assignee_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->foreignId('ticket_category_id')
                ->constrained('ticket_categories')
                ->restrictOnDelete();

            $table->foreignId('ticket_priority_id')
                ->constrained('ticket_priorities')
                ->restrictOnDelete();

            $table->foreignId('ticket_status_id')
                ->constrained('ticket_statuses')
                ->restrictOnDelete();

            $table->string('title');
            $table->text('description');

            $table->timestamp('due_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            $table->index(['requester_id', 'ticket_status_id']);
            $table->index(['assignee_id', 'ticket_status_id']);
            $table->index(['department_id', 'ticket_status_id']);
            $table->index(['ticket_priority_id', 'ticket_status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};