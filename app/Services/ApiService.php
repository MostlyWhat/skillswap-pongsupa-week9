<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ApiService
{
    /**
     * Get all the skills from the Skill Swap backend API.
     *
     * @return list<array{id: int, title: string, category: string, price: int|float, description: string, provider: string}>
     *
     * @throws RuntimeException
     */
    public function fetchSkills(): array
    {
        try {
            // Ask the backend for every skill in the database
            $response = Http::baseUrl($this->baseUrl())
                ->acceptJson()
                ->timeout(5)
                ->get('/skills')
                ->throw();

            return $response->json();
        } catch (ConnectionException|RequestException $exception) {
            Log::error('Error fetching skills: '.$exception->getMessage());

            throw new RuntimeException('Failed to fetch skills', previous: $exception);
        }
    }

    /**
     * Create a new skill in the Skill Swap backend API.
     *
     * @param  array{title: string, category: string, price: int|float, description: string, provider?: string}  $skillData
     * @return array{id: int, title: string, category: string, price: int|float, description: string, provider: string}
     *
     * @throws RuntimeException
     */
    public function createSkill(array $skillData): array
    {
        try {
            // Send the new skill to the backend so it is saved in the database
            $response = Http::baseUrl($this->baseUrl())
                ->acceptJson()
                ->timeout(5)
                ->post('/skills', $skillData)
                ->throw();

            return $response->json();
        } catch (ConnectionException|RequestException $exception) {
            Log::error('Error creating skill: '.$exception->getMessage());

            throw new RuntimeException('Failed to create skill', previous: $exception);
        }
    }

    /**
     * Get the base URL of the Skill Swap backend API.
     */
    private function baseUrl(): string
    {
        return (string) config('services.skill_api.url');
    }
}
