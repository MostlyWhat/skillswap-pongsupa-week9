<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    /**
     * The skills that the fake Skill Swap API sends in tests.
     *
     * @var list<array{id: int, title: string, category: string, price: int|float, description: string, provider: string}>
     */
    protected array $apiSkills = [
        ['id' => 1, 'title' => 'Python Tutoring', 'category' => 'Programming', 'price' => 20, 'description' => 'Learn Python fundamentals and data structures', 'provider' => 'Sarah Chen'],
        ['id' => 2, 'title' => 'Guitar Lessons', 'category' => 'Music', 'price' => 15, 'description' => 'Beginner to intermediate guitar instruction', 'provider' => 'Mike Johnson'],
        ['id' => 3, 'title' => 'Resume Review', 'category' => 'Career', 'price' => 0, 'description' => 'Professional resume feedback and editing', 'provider' => 'Alex Martinez'],
        ['id' => 4, 'title' => 'Web Development', 'category' => 'Programming', 'price' => 25, 'description' => 'HTML, CSS, and JavaScript fundamentals', 'provider' => 'Emma Thompson'],
    ];

    /**
     * Make the Skill Swap API send the test skills.
     */
    protected function fakeSkillApi(): void
    {
        Http::fake([
            '*/skills' => Http::response($this->apiSkills),
        ]);
    }

    /**
     * Make the Skill Swap API fail, like when the server is not running.
     */
    protected function fakeSkillApiDown(): void
    {
        Http::fake([
            '*/skills' => Http::failedConnection(),
        ]);
    }

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (! Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }
}
