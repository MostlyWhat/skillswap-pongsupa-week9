import type { NewSkill, Skill } from '@/types';

// The Skill Swap backend API. Set VITE_SKILL_API_URL in .env to use a different server.
export const API_BASE_URL = import.meta.env.VITE_SKILL_API_URL || 'http://localhost:3000/api';

/**
 * Get all the skills from the database through the backend API.
 */
export async function fetchSkills(): Promise<Skill[]> {
    try {
        const response = await fetch(`${API_BASE_URL}/skills`);

        if (!response.ok) {
            throw new Error('Failed to fetch skills');
        }

        const data: Skill[] = await response.json();

        return data;
    } catch (error) {
        console.error('Error fetching skills:', error);
        throw error;
    }
}

/**
 * Save a new skill in the database through the backend API.
 */
export async function createSkill(skillData: NewSkill): Promise<Skill> {
    try {
        const response = await fetch(`${API_BASE_URL}/skills`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(skillData),
        });

        if (!response.ok) {
            throw new Error('Failed to create skill');
        }

        const data: Skill = await response.json();

        return data;
    } catch (error) {
        console.error('Error creating skill:', error);
        throw error;
    }
}
