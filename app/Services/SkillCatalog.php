<?php

namespace App\Services;

class SkillCatalog
{
    /**
     * Get the filter categories, with "All" first.
     *
     * @return list<string>
     */
    public function categories(): array
    {
        // Start with "All" so it is the first filter button
        $categories = ['All'];

        // Go through all the skills
        foreach ($this->all() as $skill) {
            // If the skill category is not in the list yet
            if (! in_array($skill['category'], $categories, true)) {
                // Append to categories
                $categories[] = $skill['category'];
            }
        }

        return $categories;
    }

    /**
     * Get all the skills that SkillSwap offers.
     *
     * @return list<array{title: string, category: string, price: int|float, description: string, schedule: string, tutor: string}>
     */
    public function all(): array
    {
        return [
            [
                'title' => 'Chemistry Tutoring (Intermediate)',
                'category' => 'Chemistry',
                'price' => 20,
                'description' => 'This chemistry skill covers Organic Chemistry, Stoichiometry, and Kinetics.',
                'schedule' => 'Flexible hours by your terms',
                'tutor' => 'John Doe',
            ],
            [
                'title' => 'Computer Science Tutoring (Advanced)',
                'category' => 'Computer Science',
                'price' => 25,
                'description' => 'This computer science skill covers Operating Systems, Artificial Intelligence, and Cybersecurity.',
                'schedule' => 'Monday, Wednesday, and Friday',
                'tutor' => 'Jane Doe',
            ],
            [
                'title' => 'Engineering Science Tutoring (Beginner)',
                'category' => 'Engineering Science',
                'price' => 15,
                'description' => 'This computer science skill covers Hydraulics, Electronics, and Documentation.',
                'schedule' => 'Mondays Afternoons and Friday Mornings',
                'tutor' => 'James Doe',
            ],
            [
                'title' => 'Computer Science Tutoring (Advanced)',
                'category' => 'Computer Science',
                'price' => 0,
                'description' => 'This computer science skill covers Operating Systems, Artificial Intelligence, and Cybersecurity.',
                'schedule' => 'Saturday and Sunday Mornings',
                'tutor' => 'Jami Doe',
            ],
        ];
    }
}
