<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchSkillsRequest;
use App\Services\SkillCatalog;
use App\Services\SkillSwap;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use RuntimeException;

class MatchSkillsController extends Controller
{
    /**
     * Find the skills that match the user's needs and send them back to the page.
     */
    public function __invoke(MatchSkillsRequest $request, SkillCatalog $catalog, SkillSwap $skillSwap): RedirectResponse
    {
        // Get the user's category and max price from the form
        $category = (string) $request->validated('category');
        $maxPrice = $request->validated('max_price');

        // Get the latest skills from the database
        try {
            $skills = $catalog->all();
        } catch (RuntimeException) {
            return back()->withErrors(['category' => 'We could not load the skills to match. Please try again.']);
        }

        // If there is no max price then there is no price limit, so only filter by category
        if ($maxPrice === null) {
            $matchedSkills = $skillSwap->filterSkillsByCategory($skills, $category);
        } else {
            // Match the skills from the catalog to the user's category and max price
            $userNeeds = [
                'category' => $category,
                'maxPrice' => (float) $maxPrice,
            ];

            $matchedSkills = $skillSwap->matchSkillsToUser($userNeeds, $skills);
        }

        // Send the matched skills back to the page that posted the form
        Inertia::flash('matchedSkills', $matchedSkills);

        return back();
    }
}
