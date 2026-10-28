<?php

test('calculate cost sends the total back to the page', function () {
    $response = $this->from(route('home'))->post(route('calculate-cost'), [
        'hourly_rate' => 25,
        'hours' => 1.5,
    ]);

    $response->assertRedirect(route('home'));
    $response->assertInertiaFlash('totalCost', 37.5);
});

test('calculate cost sends 0 for a free session', function () {
    $response = $this->from(route('home'))->post(route('calculate-cost'), [
        'hourly_rate' => 0,
        'hours' => 3,
    ]);

    $response->assertInertiaFlash('totalCost', 0);
});

test('calculate cost needs a rate and hours', function () {
    $response = $this->from(route('home'))->post(route('calculate-cost'), []);

    $response->assertSessionHasErrors(['hourly_rate', 'hours']);
});

test('calculate cost rejects negative values', function () {
    $response = $this->from(route('home'))->post(route('calculate-cost'), [
        'hourly_rate' => -5,
        'hours' => -1,
    ]);

    $response->assertSessionHasErrors(['hourly_rate', 'hours']);
});
