<?php

namespace App\Http\Controllers;

use App\Services\SkillCatalog;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Show the home page with the skill cards.
     */
    public function __invoke(SkillCatalog $catalog): Response
    {
        // Send the categories and all the skills to the page
        return Inertia::render('Welcome', [
            'categories' => $catalog->categories(),
            'skills' => $catalog->all(),
        ]);
    }
}
