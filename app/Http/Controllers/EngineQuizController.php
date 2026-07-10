<?php

namespace App\Http\Controllers;

use App\Models\RaffleEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EngineQuizController extends Controller
{
    public const DIPS = [
        'Roasted Garlic and Herbs',
        'Sundried Tomato and Hazelnuts',
        'Smoked Paprika and Lime',
        'Olives and Capers',
        'Bee Honey and Flaky Salt',
    ];

    public function show(string $stall)
    {
        return view('engine.quiz', [
            'stall' => $stall,
            'dips' => self::DIPS,
        ]);
    }

    public function store(Request $request, string $stall)
    {
        $validated = $request->validate([
            'favourite_dip' => ['required', 'string', Rule::in(self::DIPS)],
        ]);

        $entry = RaffleEntry::create([
            'favourite_dip' => $validated['favourite_dip'],
            'stall_number' => $stall,
        ]);

        return response()->json([
            'message' => 'Your answer has been submitted successfully.',
            'id' => $entry->id,
        ], 201);
    }
}
