<?php

beforeEach(function () {
    $this->fakeSkillApi();
});

test('match skills sends the matched skills back to the page', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Programming',
        'max_price' => 25,
    ]);

    $response->assertRedirect(route('home'));
    $response->assertInertiaFlash('matchedSkills', [$this->apiSkills[0], $this->apiSkills[3]]);
});

test('match skills leaves out skills above the max price', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Programming',
        'max_price' => 20,
    ]);

    $response->assertInertiaFlash('matchedSkills', [$this->apiSkills[0]]);
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
        'category' => 'Programming',
        'max_price' => null,
    ]);

    $response->assertInertiaFlash('matchedSkills', [$this->apiSkills[0], $this->apiSkills[3]]);
});

test('match skills with All and no max price sends every skill', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'All',
    ]);

    $response->assertInertiaFlash('matchedSkills', $this->apiSkills);
});

test('match skills with All and a max price matches every category', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'All',
        'max_price' => 15,
    ]);

    $response->assertInertiaFlash('matchedSkills', [$this->apiSkills[1], $this->apiSkills[2]]);
});

test('match skills rejects a negative max price', function () {
    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'Music',
        'max_price' => -1,
    ]);

    $response->assertSessionHasErrors(['max_price']);
});

test('match skills sends an error when the API is down', function () {
    $this->fakeSkillApiDown();

    $response = $this->from(route('home'))->post(route('match-skills'), [
        'category' => 'All',
    ]);

    $response->assertRedirect(route('home'));
    $response->assertSessionHasErrors(['category']);
});
