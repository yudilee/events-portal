<?php

use App\Http\Controllers\Admin\BannerAdminController;
use App\Http\Controllers\Admin\BusinessUnitAdminController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\Admin\EventCommentAdminController;
use App\Http\Controllers\Admin\EventMediaAdminController;
use App\Http\Controllers\Admin\GuestBookAdminController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\Admin\PostEventAdminController;
use App\Http\Controllers\Admin\RegistrationAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Media\MediaPortalController;
use App\Http\Controllers\Member\MemberPortalController;
use App\Http\Controllers\Public\EventCommentController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\GuestBookController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MediaKitController;
use App\Http\Controllers\Public\NewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Accessible without login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Events
Route::get('/events', [EventController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('public.events.show');
Route::get('/events/{slug}/gallery', [EventController::class, 'gallery'])->name('public.events.gallery');
Route::get('/events/{slug}/recap', [EventController::class, 'recap'])->name('public.events.recap');
Route::get('/events/{slug}/register', [EventController::class, 'register'])->name('public.events.register');
Route::post('/events/{slug}/register', [EventController::class, 'storeRegistration'])->name('public.events.register.store');
Route::get('/events/{slug}/ticket/{code}', [EventController::class, 'ticket'])->name('public.events.ticket');

// Community Post-Event Wishes & Comments
Route::post('/events/{slug}/comments', [EventCommentController::class, 'store'])->name('public.events.comments.store');
Route::post('/comments/{comment}/like', [EventCommentController::class, 'like'])->name('public.comments.like');

// Guestbook Public, Reception Kiosk & QR Standee
Route::get('/events/{slug}/guestbook', [GuestBookController::class, 'show'])->name('public.events.guestbook');
Route::get('/events/{slug}/guestbook/kiosk', [GuestBookController::class, 'kiosk'])->name('public.events.guestbook.kiosk');
Route::get('/events/{slug}/guestbook/qr', [GuestBookController::class, 'qrStandee'])->name('public.events.guestbook.qr');
Route::post('/events/{slug}/guestbook', [GuestBookController::class, 'store'])->name('public.events.guestbook.store');
Route::post('/api/events/{slug}/lookup-ticket', [GuestBookController::class, 'lookupTicket'])->name('public.events.guestbook.lookup-ticket');

// News & Media Kit
Route::get('/news', [NewsController::class, 'index'])->name('public.news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('public.news.show');
Route::get('/media-kit', [MediaKitController::class, 'index'])->name('public.media-kit');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Member & VIP Customer Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:member,vip,admin'])->prefix('portal')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets', [MemberPortalController::class, 'tickets'])->name('tickets');
});

/*
|--------------------------------------------------------------------------
| News Agency & Media Press Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:news_agency,media,admin'])->prefix('media')->name('media.')->group(function () {
    Route::get('/dashboard', [MediaPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/press-releases', [MediaPortalController::class, 'pressReleases'])->name('press-releases');
    Route::post('/events/{event}/accreditation', [MediaPortalController::class, 'requestAccreditation'])->name('accreditation');
});

/*
|--------------------------------------------------------------------------
| Admin & Event Organizer Back-Office
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,event_organizer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Hero Carousel / Banners Management
    Route::resource('banners', BannerAdminController::class);
    Route::post('/banners/reorder', [BannerAdminController::class, 'reorder'])->name('banners.reorder');
    Route::post('/banners/{banner}/toggle-active', [BannerAdminController::class, 'toggleActive'])->name('banners.toggle');

    // Business Units Ecosystem CMS
    Route::resource('business-units', BusinessUnitAdminController::class);
    Route::post('/business-units/reorder', [BusinessUnitAdminController::class, 'reorder'])->name('business-units.reorder');

    // Events Management
    Route::resource('events', EventAdminController::class)->except(['destroy']);

    // Event Media Gallery Manager
    Route::get('/events/{event}/media', [EventMediaAdminController::class, 'index'])->name('events.media.index');
    Route::post('/events/{event}/media', [EventMediaAdminController::class, 'store'])->name('events.media.store');
    Route::post('/media/{media}/featured', [EventMediaAdminController::class, 'toggleFeatured'])->name('media.featured');
    Route::put('/media/{media}/caption', [EventMediaAdminController::class, 'updateCaption'])->name('media.caption');

    // Post-Event Narrative & Recap Builder
    Route::get('/events/{event}/post-event', [PostEventAdminController::class, 'edit'])->name('events.post-event');
    Route::post('/events/{event}/post-event', [PostEventAdminController::class, 'update'])->name('events.post-event.update');

    // Attendee Wishes & Feedback Moderation
    Route::get('/wishes', [EventCommentAdminController::class, 'index'])->name('wishes.index');
    Route::post('/wishes/{comment}/toggle-approval', [EventCommentAdminController::class, 'toggleApproval'])->name('wishes.approval');
    Route::post('/wishes/{comment}/toggle-highlight', [EventCommentAdminController::class, 'toggleHighlight'])->name('wishes.highlight');

    // Registrations & Attendee Management
    Route::get('/registrations', [RegistrationAdminController::class, 'index'])->name('registrations.index');
    Route::get('/events/{event}/registrations', [RegistrationAdminController::class, 'index'])->name('events.registrations');
    Route::post('/registrations/{id}/check-in-toggle', [RegistrationAdminController::class, 'toggleCheckIn'])->name('registrations.check-in-toggle');
    Route::post('/registrations/{id}/status', [RegistrationAdminController::class, 'updateStatus'])->name('registrations.status');
    Route::get('/events/{event}/registrations/export', [RegistrationAdminController::class, 'exportCsv'])->name('registrations.export');

    // Live On-Site Check-in Scanner
    Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in');
    Route::get('/events/{event}/check-in', [CheckInController::class, 'index'])->name('events.check-in');
    Route::post('/api/check-in/scan', [CheckInController::class, 'scan'])->name('api.check-in.scan');

    // Guestbook Moderation
    Route::get('/guestbook', [GuestBookAdminController::class, 'index'])->name('guestbook.index');
    Route::get('/events/{event}/guestbook', [GuestBookAdminController::class, 'index'])->name('events.guestbook');
    Route::post('/guestbook/{id}/approval', [GuestBookAdminController::class, 'toggleApproval'])->name('guestbook.approval');
    Route::post('/guestbook/{id}/highlight', [GuestBookAdminController::class, 'toggleHighlight'])->name('guestbook.highlight');

    // Doorprize Prize Setup & Live Big-Screen Roulette
    Route::get('/doorprize', [\App\Http\Controllers\Admin\DoorprizeController::class, 'index'])->name('doorprize.index');
    Route::get('/events/{event}/doorprize', [\App\Http\Controllers\Admin\DoorprizeController::class, 'index'])->name('events.doorprize');
    Route::post('/events/{event}/doorprize', [\App\Http\Controllers\Admin\DoorprizeController::class, 'store'])->name('events.doorprize.store');
    Route::put('/doorprize/{prize}', [\App\Http\Controllers\Admin\DoorprizeController::class, 'update'])->name('doorprize.update');
    Route::post('/events/{event}/doorprize/reorder', [\App\Http\Controllers\Admin\DoorprizeController::class, 'reorder'])->name('events.doorprize.reorder');
    Route::get('/events/{event}/doorprize/roulette', [\App\Http\Controllers\Admin\DoorprizeController::class, 'roulette'])->name('events.doorprize.roulette');
    Route::post('/events/{event}/doorprize/spin', [\App\Http\Controllers\Admin\DoorprizeController::class, 'spin'])->name('events.doorprize.spin');

    // News & Press Releases CMS
    Route::resource('news', NewsAdminController::class)->except(['destroy']);

    // Site & Footer Settings
    Route::get('/settings/footer', [\App\Http\Controllers\Admin\SettingController::class, 'footer'])->name('settings.footer');
    Route::post('/settings/footer', [\App\Http\Controllers\Admin\SettingController::class, 'updateFooter'])->name('settings.footer.update');

    // Super Administrator Only Actions (Deletions, Resets, User Management)
    Route::middleware('role:admin')->group(function () {
        // Event Deletion
        Route::delete('/events/{event}', [EventAdminController::class, 'destroy'])->name('events.destroy');

        // Registration & Attendee Deletion
        Route::delete('/registrations/{id}', [RegistrationAdminController::class, 'destroy'])->name('registrations.destroy');

        // Guestbook Entry Deletion
        Route::delete('/guestbook/{id}', [GuestBookAdminController::class, 'destroy'])->name('guestbook.destroy');

        // Doorprize Deletions & Resets
        Route::delete('/doorprize/{prize}', [\App\Http\Controllers\Admin\DoorprizeController::class, 'destroy'])->name('doorprize.destroy');
        Route::post('/doorprize/{prize}/reset', [\App\Http\Controllers\Admin\DoorprizeController::class, 'resetPrize'])->name('doorprize.reset');
        Route::delete('/doorprize/winner/{winner}', [\App\Http\Controllers\Admin\DoorprizeController::class, 'deleteWinner'])->name('doorprize.winner.destroy');
        Route::delete('/events/{event}/doorprize/reset-all', [\App\Http\Controllers\Admin\DoorprizeController::class, 'resetAll'])->name('events.doorprize.reset-all');

        // Wishes Deletion
        Route::delete('/wishes/{comment}', [EventCommentAdminController::class, 'destroy'])->name('wishes.destroy');

        // Gallery Media Deletion
        Route::delete('/media/{media}', [EventMediaAdminController::class, 'destroy'])->name('media.destroy');

        // News Deletion
        Route::delete('/news/{news}', [NewsAdminController::class, 'destroy'])->name('news.destroy');

        // User Management
        Route::resource('users', UserAdminController::class)->except(['create', 'show', 'edit']);
    });
});
