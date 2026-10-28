<?php

use Inertia\Testing\AssertableInertia as Assert;

test('home page sends all the skills from the catalog', function () {
    $response = $this->get(route('home'));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->has('skills', 4)
        ->has('skills.0', fn (Assert $skill) => $skill
            ->where('title', 'Chemistry Tutoring (Intermediate)')
            ->where('category', 'Chemistry')
            ->where('price', 20)
            ->etc()
        )
    );
});

test('home page includes a free skill', function () {
    $response = $this->get(route('home'));

    $response->assertInertia(fn (Assert $page) => $page
        ->where('skills.3.tutor', 'Jami Doe')
        ->where('skills.3.price', 0)
    );
});

test('home page sends the catalog categories with All first', function () {
    $response = $this->get(route('home'));

    $response->assertInertia(fn (Assert $page) => $page
        ->where('categories', ['All', 'Chemistry', 'Computer Science', 'Engineering Science'])
    );
});
