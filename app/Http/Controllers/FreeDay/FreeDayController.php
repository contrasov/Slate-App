<?php

namespace App\Http\Controllers\FreeDay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\FreeDay;

class FreeDayController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'free_day' => [
                'required',
                'date',
                'after_or_equal:today',
                Rule::unique('free_days', 'free_day')->where(fn ($query) => $query->where('user_id', Auth::id())),
            ],
        ], [
            'free_day.unique' => 'Essa data já está marcada como fechada.',
            'free_day.after_or_equal' => 'Escolha hoje ou uma data futura.',
        ]);

        $freeDay = FreeDay::create([
            'user_id' => Auth::id(),
            'free_day' => $validated['free_day'],
        ]);

        return back()->with('closedDayCreated', $freeDay->id);
    }

    public function destroy(Request $request, int $freeDay)
    {
        FreeDay::where('user_id', $request->user()->id)
            ->findOrFail($freeDay)
            ->delete();

        return back()->with('success', 'A data foi reaberta.');
    }
}
