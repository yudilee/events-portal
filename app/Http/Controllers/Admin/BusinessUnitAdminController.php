<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BusinessUnitAdminController extends Controller
{
    public function index(): Response
    {
        $businessUnits = BusinessUnit::withCount(['events', 'pressReleases'])
            ->orderBy('sort_order', 'asc')
            ->get();

        return Inertia::render('Admin/BusinessUnits/Index', [
            'businessUnits' => $businessUnits,
        ]);
    }

    public function create(): Response
    {
        $maxOrder = BusinessUnit::max('sort_order') ?? 0;

        return Inertia::render('Admin/BusinessUnits/Form', [
            'unit' => null,
            'nextOrder' => $maxOrder + 1,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:business_units,slug',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        // Default icon and color if not specified
        $validated['icon'] = $validated['icon'] ?: 'Building2';
        $validated['badge_color'] = $validated['badge_color'] ?: 'teal';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        BusinessUnit::create($validated);

        return redirect()->route('admin.business-units.index')->with('success', 'Business unit added to ecosystem successfully!');
    }

    public function edit(int $id): Response
    {
        $unit = BusinessUnit::withCount(['events', 'pressReleases'])->findOrFail($id);

        return Inertia::render('Admin/BusinessUnits/Form', [
            'unit' => $unit,
            'nextOrder' => $unit->sort_order,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $unit = BusinessUnit::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:business_units,slug,' . $unit->id,
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['icon'] = $validated['icon'] ?: 'Building2';
        $validated['badge_color'] = $validated['badge_color'] ?: 'teal';

        $unit->update($validated);

        return redirect()->route('admin.business-units.index')->with('success', 'Business unit updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $unit = BusinessUnit::withCount('events')->findOrFail($id);

        if ($unit->events_count > 0) {
            // Nullify or keep foreign key integrity safely
            $unit->events()->update(['business_unit_id' => null]);
        }

        $unit->delete();

        return redirect()->route('admin.business-units.index')->with('success', 'Business unit deleted from ecosystem.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:business_units,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['items'] as $item) {
            BusinessUnit::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return back()->with('success', 'Ecosystem display order updated.');
    }
}
