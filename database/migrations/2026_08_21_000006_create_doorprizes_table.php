<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doorprizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('label'); // e.g. "Grand Prize", "2nd Prize", "Door Prize #1"
            $table->text('description')->nullable(); // e.g. "Mercedes-Benz Luxury Watch & Voucher Rp 5.000.000"
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('winner_guest_book_entry_id')
                ->nullable()
                ->constrained('guest_book_entries')
                ->nullOnDelete();
            $table->timestamp('won_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doorprizes');
    }
};
