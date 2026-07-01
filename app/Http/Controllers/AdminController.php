<?php

namespace App\Http\Controllers;

use App\Models\RaffleEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($this->isAdmin($request)) {
            return redirect()->route('admin.entries');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $adminPassword = config('admin.password');

        if (! $adminPassword || ! hash_equals($adminPassword, $request->input('password'))) {
            return back()->withErrors([
                'password' => 'Incorrect password.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('is_admin', true);

        return redirect()->route('admin.entries');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('is_admin');
        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }

    public function index(): View
    {
        $entries = RaffleEntry::latest()->paginate(20);

        return view('admin.entries', compact('entries'));
    }

    private function isAdmin(Request $request): bool
    {
        return (bool) $request->session()->get('is_admin');
    }
}
