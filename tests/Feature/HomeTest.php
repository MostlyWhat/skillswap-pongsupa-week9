<?php

use Inertia\Testing\AssertableInertia as Assert;

test('home page loads the skills after the page shows', function () {
    $this->fakeSkillApi();

    $response = $this->get(route('home'));

    // The skills are a deferred prop, so the first response does not have them
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->missing('catalog')
    );
});

test('home page sends the skills from the API', function () {
    $this->fakeSkillApi();

    $response = $this->get(route('home'));

    $response->assertInertia(fn (Assert $page) => $page
        ->loadDeferredProps(fn (Assert $reload) => $reload
            ->has('catalog.skills', 4)
            ->has('catalog.skills.0', fn (Assert $skill) => $skill
                ->where('title', 'Python Tutoring')
                ->where('category', 'Programming')
                ->where('price', 20)
                ->etc()
            )
            ->where('catalog.error', null)
        )
    );
});

test('home page includes a free skill', function () {
    $this->fakeSkillApi();

    $response = $this->get(route('home'));

    $response->assertInertia(fn (Assert $page) => $page
        ->loadDeferredProps(fn (Assert $reload) => $reload
            ->where('catalog.skills.2.provider', 'Alex Martinez')
            ->where('catalog.skills.2.price', 0)
        )
    );
});

test('home page sends the categories from the API with All first', function () {
    $this->fakeSkillApi();

    $response = $this->get(route('home'));

    $response->assertInertia(fn (Assert $page) => $page
        ->loadDeferredProps(fn (Assert $reload) => $reload
            ->where('catalog.categories', ['All', 'Programming', 'Music', 'Career'])
        )
    );
});

test('home page sends an error when the API is down', function () {
    $this->fakeSkillApiDown();

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->loadDeferredProps(fn (Assert $reload) => $reload
            ->where('catalog.skills', [])
            ->where('catalog.categories', ['All'])
            ->where('catalog.error', fn (string $error) => str_contains($error, 'could not load the skills'))
        )
    );
});
