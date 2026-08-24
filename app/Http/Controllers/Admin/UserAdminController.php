<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('company', 'like', "%{$s}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        if ($perPage === 'all' || (is_numeric($perPage) && (int)$perPage >= 9999)) {
            $users = $query->latest()->paginate(99999)->withQueryString();
        } else {
            $users = $query->latest()->paginate(is_numeric($perPage) ? max(1, min(500, (int)$perPage)) : 15)->withQueryString();
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['role', 'search', 'per_page']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'role' => 'required|in:admin,event_organizer,member,news_agency,guest',
            'status' => 'required|in:active,pending,suspended',
            'member_tier' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'role' => 'required|in:admin,event_organizer,member,news_agency,guest',
            'status' => 'required|in:active,pending,suspended',
            'member_tier' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
