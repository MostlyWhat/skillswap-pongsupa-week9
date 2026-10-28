<?php

use App\Services\SkillSwap;

beforeEach(function () {
    $this->skillSwap = new SkillSwap;

    $this->skills = [
        ['title' => 'Python Tutoring', 'category' => 'Programming', 'price' => 20],
        ['title' => 'Guitar Lessons', 'category' => 'Music', 'price' => 15],
        ['title' => 'Resume Review', 'category' => 'Career', 'price' => 0],
        ['title' => 'Web Development', 'category' => 'Programming', 'price' => 25],
    ];
});

describe('filterSkillsByCategory', function () {
    test('filters skills by category', function () {
        $result = $this->skillSwap->filterSkillsByCategory($this->skills, 'Programming');

        expect($result)->toBe([
            ['title' => 'Python Tutoring', 'category' => 'Programming', 'price' => 20],
            ['title' => 'Web Development', 'category' => 'Programming', 'price' => 25],
        ]);
    });

    test('returns all skills for the All category', function () {
        $result = $this->skillSwap->filterSkillsByCategory($this->skills, 'All');

        expect($result)->toBe($this->skills);
    });

    test('returns an empty array when no skills match', function () {
        $result = $this->skillSwap->filterSkillsByCategory($this->skills, 'Cooking');

        expect($result)->toBe([]);
    });
});

describe('calculateTotalCost', function () {
    test('returns the correct total for different rates and hours', function () {
        expect($this->skillSwap->calculateTotalCost(20, 2))->toEqual(40);
        expect($this->skillSwap->calculateTotalCost(15, 3))->toEqual(45);
    });

    test('returns 0 for a free session', function () {
        expect($this->skillSwap->calculateTotalCost(0, 3))->toEqual(0);
    });

    test('returns 0 for zero hours', function () {
        expect($this->skillSwap->calculateTotalCost(20, 0))->toEqual(0);
    });

    test('returns the correct total for decimal hours', function () {
        expect($this->skillSwap->calculateTotalCost(25, 1.5))->toEqual(37.5);
    });
});

describe('matchSkillsToUser', function () {
    beforeEach(function () {
        $this->skills = [
            ['title' => 'Python Tutoring', 'category' => 'Programming', 'price' => 20],
            ['title' => 'JavaScript Help', 'category' => 'Programming', 'price' => 25],
            ['title' => 'Guitar Lessons', 'category' => 'Music', 'price' => 15],
            ['title' => 'Resume Review', 'category' => 'Career', 'price' => 0],
        ];
    });

    test('matches skills by category and price', function () {
        $userNeeds = ['category' => 'Programming', 'maxPrice' => 30];

        $result = $this->skillSwap->matchSkillsToUser($userNeeds, $this->skills);

        expect($result)->toBe([
            ['title' => 'Python Tutoring', 'category' => 'Programming', 'price' => 20],
            ['title' => 'JavaScript Help', 'category' => 'Programming', 'price' => 25],
        ]);
    });

    test('leaves out skills above the max price', function () {
        $userNeeds = ['category' => 'Programming', 'maxPrice' => 20];

        $result = $this->skillSwap->matchSkillsToUser($userNeeds, $this->skills);

        expect($result)->toBe([
            ['title' => 'Python Tutoring', 'category' => 'Programming', 'price' => 20],
        ]);
    });

    test('returns an empty array when no skills match', function () {
        $userNeeds = ['category' => 'Cooking', 'maxPrice' => 100];

        $result = $this->skillSwap->matchSkillsToUser($userNeeds, $this->skills);

        expect($result)->toBe([]);
    });

    test('matches every category for the All category', function () {
        $userNeeds = ['category' => 'All', 'maxPrice' => 20];

        $result = $this->skillSwap->matchSkillsToUser($userNeeds, $this->skills);

        expect($result)->toBe([
            ['title' => 'Python Tutoring', 'category' => 'Programming', 'price' => 20],
            ['title' => 'Guitar Lessons', 'category' => 'Music', 'price' => 15],
            ['title' => 'Resume Review', 'category' => 'Career', 'price' => 0],
        ]);
    });

    test('includes free skills', function () {
        $userNeeds = ['category' => 'Career', 'maxPrice' => 0];

        $result = $this->skillSwap->matchSkillsToUser($userNeeds, $this->skills);

        expect($result)->toBe([
            ['title' => 'Resume Review', 'category' => 'Career', 'price' => 0],
        ]);
    });
});
