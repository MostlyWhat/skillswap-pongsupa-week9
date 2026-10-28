<?php

namespace App\Http\Controllers;

use App\Services\SkillCatalog;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class HomeController extends Controller
{
    /**
     * Show the home page with the skill cards.
     */
    public function __invoke(SkillCatalog $catalog): Response
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
}
