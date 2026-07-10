<?php

namespace App\Http\Controllers;

use App\Models\RaffleEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RaffleEntryController extends Controller
{
    public function index()
    {
        return view('raffle.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'favourite_dip' => ['required', 'string', Rule::in([
                'Roasted Garlic and Herbs',
                'Sundried Tomato and Hazelnuts',
                'Smoked Paprika and Lime',
                'Olives and Capers',
                'Bee Honey and Flaky Salt',
            ])],
            'stall_number' => ['required', 'string', Rule::in(['01', '02', '03', '04'])],
        ]);

        $entry = RaffleEntry::create($validated);

        return response()->json([
            'message' => 'Your entry has been submitted successfully.',
            'id' => $entry->id,
        ], 201);
    }
}
