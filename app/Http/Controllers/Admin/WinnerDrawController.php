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
            ->get(['id', 'first_name', 'last_name']);

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
            'first_name' => $winner->first_name,
            'last_name' => $winner->last_name,
            'mobile_number_masked' => $this->maskMobile($winner->mobile_number),
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

    private function maskMobile(string $mobile): string
    {
        $digits = preg_replace('/\s+/', '', $mobile);
        $length = strlen($digits);

        if ($length <= 5) {
            return $digits;
        }

        $visibleStart = substr($digits, 0, 3);
        $visibleEnd = substr($digits, -2);
        $masked = str_repeat('•', max(0, $length - 5));

        return "{$visibleStart}{$masked}{$visibleEnd}";
    }
}
