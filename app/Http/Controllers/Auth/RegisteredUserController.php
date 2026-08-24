<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => 'required|string|max:50',
            'company' => 'nullable|string|max:255',
            'account_type' => 'required|string|in:member,news_agency',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = $request->account_type === 'news_agency' ? 'news_agency' : 'member';
        $tier = $request->account_type === 'news_agency' ? 'media' : 'regular';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'role' => $role,
            'status' => 'active',
            'member_tier' => $tier,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        if ($user->isNewsAgency()) {
            return redirect()->route('media.dashboard')->with('success', 'Welcome to the Hartono Media Press Room!');
        }

        return redirect()->route('member.dashboard')->with('success', 'Welcome to the Hartono Group Customer Portal!');
    }
}
