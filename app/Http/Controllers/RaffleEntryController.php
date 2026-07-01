<?php

namespace App\Http\Controllers;

use App\Models\RaffleEntry;
use Illuminate\Http\Request;

class RaffleEntryController extends Controller
{
    public function index()
    {
        return view('raffle.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:20', 'regex:/^[0-9+ -]{7,20}$/'],
        ]);

        $entry = RaffleEntry::create($validated);

        return response()->json([
            'message' => 'Your entry has been submitted successfully.',
            'id' => $entry->id,
        ], 201);
    }
}
