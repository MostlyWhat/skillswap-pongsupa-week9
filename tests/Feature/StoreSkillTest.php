<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->newSkill = [
        'title' => 'Chess Coaching',
        'category' => 'Games',
        'price' => 12.5,
        'description' => 'Openings, tactics, and endgames',
        'provider' => 'Pat Lee',
    ];
});

test('store skill sends the new skill to the API', function () {
    Http::fake(['*/skills' => Http::response(['id' => 5, ...$this->newSkill], 201)]);

    $this->from(route('home'))->post(route('skills.store'), $this->newSkill);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->data() === $this->newSkill);
});

test('store skill goes back to the page with the created skill', function () {
    Http::fake(['*/skills' => Http::response(['id' => 5, ...$this->newSkill], 201)]);

    $response = $this->from(route('home'))->post(route('skills.store'), $this->newSkill);

    $response->assertRedirect(route('home'));
    $response->assertSessionHasNoErrors();
    $response->assertInertiaFlash('createdSkill', ['id' => 5, ...$this->newSkill]);
});

test('store skill uses Anonymous when there is no name', function () {
    Http::fake(['*/skills' => Http::response(['id' => 5], 201)]);

    $this->from(route('home'))->post(route('skills.store'), [...$this->newSkill, 'provider' => '']);

    Http::assertSent(fn (Request $request) => $request['provider'] === 'Anonymous');
});

test('store skill accepts a free skill', function () {
    Http::fake(['*/skills' => Http::response(['id' => 5], 201)]);

    $response = $this->from(route('home'))->post(route('skills.store'), [...$this->newSkill, 'price' => 0]);

    $response->assertSessionHasNoErrors();
    Http::assertSent(fn (Request $request) => $request['price'] == 0);
});

test('store skill needs every required field', function () {
    Http::fake();

    $response = $this->from(route('home'))->post(route('skills.store'), []);

    $response->assertSessionHasErrors(['title', 'category', 'price', 'description']);
    Http::assertNothingSent();
});

test('store skill rejects bad values', function (string $field, mixed $value) {
    Http::fake();

    $response = $this->from(route('home'))->post(route('skills.store'), [...$this->newSkill, $field => $value]);

    $response->assertSessionHasErrors([$field]);
    Http::assertNothingSent();
})->with([
    'short title' => ['title', 'ab'],
    'long title' => ['title', str_repeat('a', 101)],
    'short category' => ['category', 'a'],
    'negative price' => ['price', -1],
    'price too high' => ['price', 1001],
    'price not a number' => ['price', 'free'],
    'short description' => ['description', 'Too short'],
    'long description' => ['description', str_repeat('a', 501)],
    'long name' => ['provider', str_repeat('a', 101)],
]);

test('store skill sends an error when the API is down', function () {
    $this->fakeSkillApiDown();

    $response = $this->from(route('home'))->post(route('skills.store'), $this->newSkill);

    $response->assertRedirect(route('home'));
    $response->assertSessionHasErrors(['form']);
});
