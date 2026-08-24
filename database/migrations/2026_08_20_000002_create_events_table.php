<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_unit_id')->nullable()->constrained('business_units')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('event_type')->default('customer_gathering'); // customer_gathering, soft_opening, exhibition, press_conference, test_drive
            $table->string('visibility')->default('public'); // public, members_only, invite_only, media_only
            $table->date('date');
            $table->string('start_time')->default('10:00');
            $table->string('end_time')->nullable();
            $table->string('timezone')->default('WIB');
            $table->string('venue_name');
            $table->text('venue_address')->nullable();
            $table->text('venue_map_url')->nullable();
            $table->string('dress_code')->default('Smart Casual');
            $table->string('rsvp_contact')->nullable();
            $table->string('rsvp_phone')->nullable();
            $table->integer('max_capacity')->default(200);
            $table->dateTime('registration_deadline')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('status')->default('published'); // draft, published, ongoing, completed, archived
            $table->boolean('is_registration_enabled')->default(true);
            $table->boolean('is_guestbook_enabled')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
