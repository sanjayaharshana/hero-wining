<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaffleEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WinnerDrawController extends Controller
{
    public function show(): View
    {
        $pool = RaffleEntry::eligible()
            ->orderBy('id')
            ->get(['id', 'name']);

        $winners = RaffleEntry::winners()->get();

        return view('admin.winner-draw', [
            'pool' => $pool,
            'winners' => $winners,
            'eligibleCount' => $pool->count(),
        ]);
    }

    public function draw(): JsonResponse
    {
        $winner = DB::transaction(function () {
            $entry = RaffleEntry::eligible()
                ->inRandomOrder()
                ->lockForUpdate()
                ->first();

            if (! $entry) {
                return null;
            }

            $entry->forceFill([
                'is_winner' => true,
                'won_at' => now(),
            ])->save();

            return $entry;
        });

        if (! $winner) {
            return response()->json([
                'message' => 'No eligible entries left to draw.',
            ], 409);
        }

        return response()->json([
            'id' => $winner->id,
            'name' => $winner->name,
            'remaining' => RaffleEntry::eligible()->count(),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        RaffleEntry::query()->update([
            'is_winner' => false,
            'won_at' => null,
        ]);

        return redirect()->route('admin.winner-draw')->with('status', 'All winners have been reset.');
    }
}
