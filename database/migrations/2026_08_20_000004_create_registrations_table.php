<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('registration_code')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->string('attendee_type')->default('general_guest'); // general_guest, vip, media, partner, staff
            $table->integer('num_attendees')->default(1);
            $table->string('vehicle_model')->nullable(); // For Mercedes-Benz or customer gatherings
            $table->string('license_plate')->nullable();
            $table->string('media_outlet_name')->nullable(); // For press/news agencies
            $table->text('dietary_notes')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('confirmed'); // pending, confirmed, waitlist, cancelled, attended
            $table->timestamp('checked_in_at')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('qr_code_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
