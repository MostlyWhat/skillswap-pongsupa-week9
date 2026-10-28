// A skill as the Skill Swap backend API returns it
export type Skill = {
    id: number;
    title: string;
    category: string;
    price: number;
    description: string;
    provider: string;
};

// The skills and categories that the home page loads after it shows
export type SkillCatalog = {
    skills: Skill[];
    categories: string[];
    error: string | null;
};

// The fields that the form sends to the API to create a skill
export type NewSkill = {
    title: string;
    category: string;
    price: number;
    description: string;
    provider?: string;
};
