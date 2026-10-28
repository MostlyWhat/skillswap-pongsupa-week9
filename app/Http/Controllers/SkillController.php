<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchSkillsRequest;
use App\Http\Requests\StoreSkillRequest;
use App\Services\ApiService;
use App\Services\SkillCatalog;
use App\Services\SkillSwap;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class SkillController extends Controller
{
    /**
     * Show the home page with the skill cards.
     */
    public function index(SkillCatalog $catalog): Response
    {
        // Load the skills after the page shows, so the page can show a loading state
        return Inertia::render('Welcome', [
            'catalog' => Inertia::defer(function () use ($catalog) {
                try {
                    $skills = $catalog->all();

                    return [
                        'skills' => $skills,
                        'categories' => $catalog->categories($skills),
                        'error' => null,
                    ];
                } catch (RuntimeException) {
                    // Send an error state so the page can tell the user and offer a retry
                    return [
                        'skills' => [],
                        'categories' => ['All'],
                        'error' => 'We could not load the skills. Make sure the SkillSwap API is running, then try again.',
                    ];
                }
            }),
        ]);
    }

    /**
     * Find the skills that match the user's needs and send them back to the page.
     */
    public function match(MatchSkillsRequest $request, SkillCatalog $catalog, SkillSwap $skillSwap): RedirectResponse
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

    /**
     * Save a new skill in the database through the backend API, then reload the skills.
     */
    public function store(StoreSkillRequest $request, ApiService $api): RedirectResponse
    {
        // Get the skill fields from the form as the API expects them
        $skillData = [
            'title' => (string) $request->validated('title'),
            'category' => (string) $request->validated('category'),
            'price' => (float) $request->validated('price'),
            'description' => (string) $request->validated('description'),
            'provider' => $request->validated('provider') ?: 'Anonymous',
        ];

        try {
            $skill = $api->createSkill($skillData);
        } catch (RuntimeException) {
            // Keep the form values and tell the user that the skill was not saved
            return back()->withErrors(['form' => 'We could not save your skill. Make sure the SkillSwap API is running, then try again.']);
        }

        // Tell the page which skill was created. Going back reloads the skills from the database.
        Inertia::flash('createdSkill', $skill);

        return back();
    }
}
