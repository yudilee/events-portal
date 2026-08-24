<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add quantity to doorprizes
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(1)->after('description');
        });

        // 2. Create doorprize_winners table for 1-to-many winner support
        Schema::create('doorprize_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doorprize_id')->constrained('doorprizes')->cascadeOnDelete();
            $table->foreignId('guest_book_entry_id')->constrained('guest_book_entries')->cascadeOnDelete();
            $table->timestamp('won_at')->nullable();
            $table->timestamps();
        });

        // 3. Migrate any existing legacy winners into the new table
        $existingPrizes = DB::table('doorprizes')->whereNotNull('winner_guest_book_entry_id')->get();
        foreach ($existingPrizes as $prize) {
            DB::table('doorprize_winners')->insert([
                'doorprize_id' => $prize->id,
                'guest_book_entry_id' => $prize->winner_guest_book_entry_id,
                'won_at' => $prize->won_at ?? now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('doorprize_winners');

        Schema::table('doorprizes', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
