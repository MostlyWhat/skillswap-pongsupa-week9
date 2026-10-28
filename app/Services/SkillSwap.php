<?php

namespace App\Services;

class SkillSwap
{
    /**
     * Get the skills in the given category, or all skills for "All".
     *
     * @template TSkill of array{category: string}
     *
     * @param list<TSkill> $skills
     * @return list<TSkill>
     */
    public function filterSkillsByCategory(array $skills, string $category): array
    {
        // If no specific category then we can just return everything back
        if ($category === 'All') {
            return $skills;
        }

        // Return specific category
        $filteredSkills = [];

        // Go through all the skills
        foreach ($skills as $skill) {
            // If skill category is the same as the filter category
            if ($skill['category'] === $category) {
                // Append to filtered skill
                $filteredSkills[] = $skill;
            }
        }

        return $filteredSkills;
    }

    /**
     * Get the total cost of a session from the hourly rate and the hours.
     */
    public function calculateTotalCost(int|float $hourlyRate, int|float $hours): int|float
    {
        // Multiply the hourly rate by the hours, so a free rate or zero hours gives 0
        return $hourlyRate * $hours;
    }

    /**
     * Get the skills that match the user's category ("All" for every category)
     * and are within the user's max price.
     *
     * @template TSkill of array{category: string, price: int|float}
     *
     * @param  array{category: string, maxPrice: int|float}  $userNeeds
     * @param  list<TSkill>  $skills
     * @return list<TSkill>
     */
    public function matchSkillsToUser(array $userNeeds, array $skills): array
    {
        // Matched skills for the user
        $matchedSkills = [];

        // Go through all the skills
        foreach ($skills as $skill) {
            // If the user wants every category, or the skill category is the same as the user's category
            $categoryMatches = $userNeeds['category'] === 'All' || $skill['category'] === $userNeeds['category'];

            // If the category matches and the skill price is not more than the user's max price
            if ($categoryMatches && $skill['price'] <= $userNeeds['maxPrice']) {
                // Append to matched skills
                $matchedSkills[] = $skill;
            }
        }

        return $matchedSkills;
    }
}
