<?php

use App\Services\ApiService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    config(['services.skill_api.url' => 'http://skill-api.test/api']);

    $this->api = new ApiService;
});

describe('fetchSkills', function () {
    test('gets the skills from the API', function () {
        $this->fakeSkillApi();

        $skills = $this->api->fetchSkills();

        expect($skills)->toBe($this->apiSkills);
    });

    test('calls the skills endpoint of the configured API', function () {
        $this->fakeSkillApi();

        $this->api->fetchSkills();

        Http::assertSent(fn (Request $request) => $request->method() === 'GET'
            && $request->url() === 'http://skill-api.test/api/skills');
    });

    test('throws when the API sends an error status', function () {
        Http::fake(['*/skills' => Http::response(['error' => 'Failed to fetch skills'], 500)]);

        $this->api->fetchSkills();
    })->throws(RuntimeException::class, 'Failed to fetch skills');

    test('throws when the API is not running', function () {
        $this->fakeSkillApiDown();

        $this->api->fetchSkills();
    })->throws(RuntimeException::class, 'Failed to fetch skills');
});

describe('createSkill', function () {
    beforeEach(function () {
        $this->newSkill = [
            'title' => 'Chess Coaching',
            'category' => 'Games',
            'price' => 12.5,
            'description' => 'Openings, tactics, and endgames',
            'provider' => 'Pat Lee',
        ];
    });

    test('sends the new skill to the API as JSON', function () {
        Http::fake(['*/skills' => Http::response(['id' => 5, ...$this->newSkill], 201)]);

        $this->api->createSkill($this->newSkill);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === 'http://skill-api.test/api/skills'
            && $request->isJson()
            && $request->data() === $this->newSkill);
    });

    test('returns the skill that the API created', function () {
        Http::fake(['*/skills' => Http::response(['id' => 5, ...$this->newSkill], 201)]);

        $skill = $this->api->createSkill($this->newSkill);

        expect($skill['id'])->toBe(5)
            ->and($skill['title'])->toBe('Chess Coaching');
    });

    test('throws when the API rejects the skill', function () {
        Http::fake(['*/skills' => Http::response(['error' => 'Missing required fields'], 400)]);

        $this->api->createSkill($this->newSkill);
    })->throws(RuntimeException::class, 'Failed to create skill');

    test('throws when the API is not running', function () {
        $this->fakeSkillApiDown();

        $this->api->createSkill($this->newSkill);
    })->throws(RuntimeException::class, 'Failed to create skill');
});
