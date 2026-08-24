<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('guest_name');
            $table->string('guest_company')->nullable();
            $table->string('guest_role')->nullable();
            $table->text('message');
            $table->string('photo_path')->nullable();
            $table->string('type')->default('wish'); // 'wish', 'afterthought', 'general'
            $table->unsignedInteger('likes_count')->default(0);
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_highlighted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_comments');
    }
};
