<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    /**
     * Display the footer and general settings manager.
     */
    public function footer(): Response
    {
        $settings = [
            'brand_story' => SiteSetting::get('footer_brand_story', 'Pioneering the automotive lifestyle since 1970. Integrated ecosystem encompassing authorized Mercedes-Benz dealership, body repair, luxury detailing, and industrial haulage.'),
            'brand_tagline' => SiteSetting::get('footer_brand_tagline', 'Everlasting, Continuous Growth.'),
            'branches' => SiteSetting::get('footer_branches', [
                ['city' => 'Jakarta', 'address' => 'Daan Mogot Km 10 No. 9'],
                ['city' => 'Surabaya', 'address' => 'Jl. Demak No. 166-168'],
                ['city' => 'Bali', 'address' => 'Denpasar Dealership & Service'],
                ['city' => 'Semarang', 'address' => 'Central Java Operations'],
            ]),
            'head_office' => SiteSetting::get('footer_head_office', 'Jl. Demak No. 166-168, Surabaya'),
            'phone' => SiteSetting::get('footer_phone', '+62 31 531 1306'),
            'rsvp_phone' => SiteSetting::get('footer_rsvp_phone', '+62 877-8222-3724'),
            'website_url' => SiteSetting::get('footer_website_url', 'https://hartonomotor-group.com'),
            'website_label' => SiteSetting::get('footer_website_label', 'Visit hartonomotor-group.com'),
            'copyright' => SiteSetting::get('footer_copyright', '© 2026 PT Hartono Raya Motor & Hartono Group. All rights reserved.'),
        ];

        $businessUnits = BusinessUnit::orderBy('sort_order')->get(['id', 'name', 'slug']);

        return Inertia::render('Admin/Settings/Footer', [
            'settings' => $settings,
            'businessUnits' => $businessUnits,
        ]);
    }

    /**
     * Update footer settings.
     */
    public function updateFooter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand_story' => ['required', 'string'],
            'brand_tagline' => ['nullable', 'string', 'max:255'],
            'branches' => ['required', 'array'],
            'branches.*.city' => ['required', 'string', 'max:100'],
            'branches.*.address' => ['required', 'string', 'max:255'],
            'head_office' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:100'],
            'rsvp_phone' => ['required', 'string', 'max:100'],
            'website_url' => ['required', 'url', 'max:255'],
            'website_label' => ['required', 'string', 'max:255'],
            'copyright' => ['required', 'string', 'max:255'],
        ]);

        SiteSetting::set('footer_brand_story', $validated['brand_story'], 'footer', 'textarea');
        SiteSetting::set('footer_brand_tagline', $validated['brand_tagline'] ?? '', 'footer', 'text');
        SiteSetting::set('footer_branches', $validated['branches'], 'footer', 'json');
        SiteSetting::set('footer_head_office', $validated['head_office'], 'footer', 'text');
        SiteSetting::set('footer_phone', $validated['phone'], 'footer', 'text');
        SiteSetting::set('footer_rsvp_phone', $validated['rsvp_phone'], 'footer', 'text');
        SiteSetting::set('footer_website_url', $validated['website_url'], 'footer', 'text');
        SiteSetting::set('footer_website_label', $validated['website_label'], 'footer', 'text');
        SiteSetting::set('footer_copyright', $validated['copyright'], 'footer', 'text');

        return back()->with('success', 'Footer content updated successfully!');
    }
}
