<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchSkillsRequest;
use App\Services\SkillCatalog;
use App\Services\SkillSwap;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

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

        // If there is no max price then there is no price limit, so only filter by category
        if ($maxPrice === null) {
            $matchedSkills = $skillSwap->filterSkillsByCategory($catalog->all(), $category);
        } else {
            // Match the skills from the catalog to the user's category and max price
            $userNeeds = [
                'category' => $category,
                'maxPrice' => (float) $maxPrice,
            ];

            $matchedSkills = $skillSwap->matchSkillsToUser($userNeeds, $catalog->all());
        }

        // Send the matched skills back to the page that posted the form
        Inertia::flash('matchedSkills', $matchedSkills);

        return back();
    }
}
