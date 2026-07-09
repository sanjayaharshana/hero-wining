<?php

namespace App\Http\Controllers;

use App\Models\RaffleEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exportCsv(): StreamedResponse
    {
        $filename = 'flora-entries-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Name', 'Favourite Dip', 'Winner', 'Submitted At']);

            RaffleEntry::orderBy('id')->chunk(200, function ($entries) use ($handle) {
                foreach ($entries as $entry) {
                    fputcsv($handle, [
                        $entry->id,
                        $entry->name,
                        $entry->favourite_dip,
                        $entry->is_winner ? 'Yes' : 'No',
                        $entry->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function destroy(RaffleEntry $entry): RedirectResponse
    {
        $entry->delete();

        return redirect()->route('admin.entries')->with('status', 'Entry deleted.');
    }

    private function isAdmin(Request $request): bool
    {
        return (bool) $request->session()->get('is_admin');
    }
}
