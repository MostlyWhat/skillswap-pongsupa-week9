<?php

namespace App\Services;

use RuntimeException;

class SkillCatalog
{
    public function __construct(private ApiService $api) {}

    /**
     * Get the filter categories for the given skills, with "All" first.
     *
     * @param  list<array{category: string}>  $skills
     * @return list<string>
     */
    public function categories(array $skills): array
    {
        // Start with "All" so it is the first filter button
        $categories = ['All'];

        // Go through all the skills
        foreach ($skills as $skill) {
            // If the skill category is not in the list yet
            if (! in_array($skill['category'], $categories, true)) {
                // Append to categories
                $categories[] = $skill['category'];
            }
        }

        return $categories;
    }

    /**
     * Get all the skills that SkillSwap offers from the database, through the backend API.
     *
     * @return list<array{id: int, title: string, category: string, price: int|float, description: string, provider: string}>
     *
     * @throws RuntimeException
     */
    public function all(): array
    {
        return $this->api->fetchSkills();
    }
}
