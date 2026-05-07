<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->index(); // To identify guest users too
            $table->integer('quantity');
            $table->timestamp('expires_at'); // Reservation expires after 10 minutes
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete(); // Set after payment
            $table->timestamps();

            $table->index(['ticket_type_id', 'expires_at']); // Fast lookups for available stock
            $table->index(['user_id', 'ticket_type_id']);    // Prevent double-reservation per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_reservations');
    }
};
