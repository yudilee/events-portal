<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role ?? 'guest',
                    'company' => $request->user()->company ?? '',
                    'member_tier' => $request->user()->member_tier ?? null,
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'footerSettings' => fn () => [
                'brand_story' => \App\Models\SiteSetting::get('footer_brand_story', 'Pioneering the automotive lifestyle since 1970. Integrated ecosystem encompassing authorized Mercedes-Benz dealership, body repair, luxury detailing, and industrial haulage.'),
                'brand_tagline' => \App\Models\SiteSetting::get('footer_brand_tagline', 'Everlasting, Continuous Growth.'),
                'branches' => \App\Models\SiteSetting::get('footer_branches', [
                    ['city' => 'Jakarta', 'address' => 'Daan Mogot Km 10 No. 9'],
                    ['city' => 'Surabaya', 'address' => 'Jl. Demak No. 166-168'],
                    ['city' => 'Bali', 'address' => 'Denpasar Dealership & Service'],
                    ['city' => 'Semarang', 'address' => 'Central Java Operations'],
                ]),
                'head_office' => \App\Models\SiteSetting::get('footer_head_office', 'Jl. Demak No. 166-168, Surabaya'),
                'phone' => \App\Models\SiteSetting::get('footer_phone', '+62 31 531 1306'),
                'rsvp_phone' => \App\Models\SiteSetting::get('footer_rsvp_phone', '+62 877-8222-3724'),
                'website_url' => \App\Models\SiteSetting::get('footer_website_url', 'https://hartonomotor-group.com'),
                'website_label' => \App\Models\SiteSetting::get('footer_website_label', 'Visit hartonomotor-group.com'),
                'copyright' => \App\Models\SiteSetting::get('footer_copyright', '© 2026 PT Hartono Raya Motor & Hartono Group. All rights reserved.'),
            ],
            'footerBusinessUnits' => fn () => \Illuminate\Support\Facades\Schema::hasTable('business_units')
                ? \App\Models\BusinessUnit::orderBy('sort_order')->take(6)->get(['id', 'name', 'slug'])
                : [],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
