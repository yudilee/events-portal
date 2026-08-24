<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->longText('content_html')->nullable()->after('description');
            $table->longText('post_event_summary')->nullable()->after('content_html');
            $table->boolean('is_gallery_enabled')->default(true)->after('is_guestbook_enabled');
            $table->boolean('is_post_event_enabled')->default(true)->after('is_gallery_enabled');
            $table->dateTime('post_event_published_at')->nullable()->after('is_post_event_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'content_html',
                'post_event_summary',
                'is_gallery_enabled',
                'is_post_event_enabled',
                'post_event_published_at',
            ]);
        });
    }
};
