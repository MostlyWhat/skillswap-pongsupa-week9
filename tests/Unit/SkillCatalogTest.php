<?php

use App\Services\ApiService;
use App\Services\SkillCatalog;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->catalog = new SkillCatalog(new ApiService);
});

test('all gets the skills from the API', function () {
    $this->fakeSkillApi();

    expect($this->catalog->all())->toBe($this->apiSkills);
});

test('categories lists each category one time with All first', function () {
    expect($this->catalog->categories($this->apiSkills))
        ->toBe(['All', 'Programming', 'Music', 'Career']);
});

test('categories is only All when there are no skills', function () {
    expect($this->catalog->categories([]))->toBe(['All']);
});
