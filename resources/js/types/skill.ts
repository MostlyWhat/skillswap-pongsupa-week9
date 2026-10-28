export type Skill = {
    title: string;
    category: string;
    price: number;
    description: string;
    schedule: string;
    tutor: string;
};

// The fields that the form sends to the API to create a skill
export type NewSkill = {
    title: string;
    category: string;
    price: number;
    description: string;
    provider?: string;
};
