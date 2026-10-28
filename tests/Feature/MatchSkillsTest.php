<?php

use App\Services\SkillCatalog;

test('match skills sends the matched skills back to the page', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Computer Science',
        'max_price' => 25,
    ]);

    $skills = (new SkillCatalog)->all();

    $response->assertRedirect(route('home'));
    $response->assertInertiaFlash('matchedSkills', [$skills[1], $skills[3]]);
});

test('match skills leaves out skills above the max price', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Computer Science',
        'max_price' => 10,
    ]);

    $skills = (new SkillCatalog)->all();

    $response->assertInertiaFlash('matchedSkills', [$skills[3]]);
});

test('match skills sends an empty list when no skills match', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Cooking',
        'max_price' => 100,
    ]);

    $response->assertInertiaFlash('matchedSkills', []);
});

test('match skills needs a category', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), []);

    $response->assertSessionHasErrors(['category']);
});

test('match skills with no max price only filters by category', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Computer Science',
        'max_price' => null,
    ]);

    $skills = (new SkillCatalog)->all();

    $response->assertInertiaFlash('matchedSkills', [$skills[1], $skills[3]]);
});

test('match skills with All and no max price sends every skill', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'All',
    ]);

    $response->assertInertiaFlash('matchedSkills', (new SkillCatalog)->all());
});

test('match skills with All and a max price matches every category', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'All',
        'max_price' => 15,
    ]);

    $skills = (new SkillCatalog)->all();

    $response->assertInertiaFlash('matchedSkills', [$skills[2], $skills[3]]);
});

test('match skills rejects a negative max price', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Chemistry',
        'max_price' => -1,
    ]);

    $response->assertSessionHasErrors(['max_price']);
});
