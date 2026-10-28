<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateCostRequest;
use App\Services\SkillSwap;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class CalculateCostController extends Controller
{
    /**
     * Calculate the total cost of a session and send it back to the page.
     */
    public function __invoke(CalculateCostRequest $request, SkillSwap $skillSwap): RedirectResponse
    {
        // Get the rate and the hours from the form as numbers
        $hourlyRate = (float) $request->validated('hourly_rate');
        $hours = (float) $request->validated('hours');

        // Calculate the total cost for the session
        $totalCost = $skillSwap->calculateTotalCost($hourlyRate, $hours);

        // Send the total back to the page that posted the form
        Inertia::flash('totalCost', $totalCost);

        return back();
    }
}
