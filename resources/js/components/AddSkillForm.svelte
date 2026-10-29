<script lang="ts">
    import { Form, page } from '@inertiajs/svelte';
    import SkillController from '@/actions/App/Http/Controllers/SkillController';
    import InputError from '@/components/InputError.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import type { Skill } from '@/types';

    let {
        categories,
        onCreated,
    }: {
        categories: string[];
        onCreated?: () => void;
    } = $props();

    // The skill that the API saved, so the form can tell the user it worked
    const createdSkill = $derived(page.flash.createdSkill as Skill | undefined);

    // Do not suggest "All" as a category for a new skill
    const suggestedCategories = $derived(categories.filter((category) => category !== 'All'));
</script>

<section
    class="mt-6 w-full max-w-[335px] rounded-lg bg-white p-6 text-[13px] leading-5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:max-w-4xl dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
>
    <h2 class="mb-1 font-medium">Share a Skill</h2>
    <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">
        Add a skill that you can teach. SkillSwap saves it in the database, so it stays after you refresh the page.
    </p>
    <Form
        {...SkillController.store.form()}
        options={{ preserveScroll: true, preserveState: true }}
        resetOnSuccess
        disableWhileProcessing
        onSuccess={() => onCreated?.()}
        class="grid gap-4 lg:grid-cols-2"
    >
        {#snippet children({ errors, processing })}
            <div class="grid gap-2">
                <Label for="skill_title">Title</Label>
                <Input
                    id="skill_title"
                    name="title"
                    type="text"
                    required
                    minlength={3}
                    maxlength={100}
                    placeholder="Python Tutoring"
                    aria-invalid={errors.title ? 'true' : undefined}
                />
                <InputError message={errors.title} />
            </div>
            <div class="grid gap-2">
                <Label for="skill_category">Category</Label>
                <Input
                    id="skill_category"
                    name="category"
                    type="text"
                    list="skill_category_options"
                    required
                    minlength={2}
                    maxlength={50}
                    placeholder="Programming"
                    aria-invalid={errors.category ? 'true' : undefined}
                />
                <datalist id="skill_category_options">
                    {#each suggestedCategories as category (category)}
                        <option value={category}></option>
                    {/each}
                </datalist>
                <InputError message={errors.category} />
            </div>
            <div class="grid gap-2">
                <Label for="skill_price">Price per hour ($)</Label>
                <Input
                    id="skill_price"
                    name="price"
                    type="number"
                    required
                    min="0"
                    max="1000"
                    step="0.01"
                    placeholder="0 for a free skill"
                    aria-invalid={errors.price ? 'true' : undefined}
                />
                <InputError message={errors.price} />
            </div>
            <div class="grid gap-2">
                <Label for="skill_provider">Your name (optional)</Label>
                <Input
                    id="skill_provider"
                    name="provider"
                    type="text"
                    maxlength={100}
                    placeholder="Anonymous"
                    aria-invalid={errors.provider ? 'true' : undefined}
                />
                <InputError message={errors.provider} />
            </div>
            <div class="grid gap-2 lg:col-span-2">
                <Label for="skill_description">Description</Label>
                <textarea
                    id="skill_description"
                    name="description"
                    required
                    minlength={10}
                    maxlength={500}
                    rows={3}
                    placeholder="What will learners get from this skill?"
                    aria-invalid={errors.description ? 'true' : undefined}
                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                ></textarea>
                <InputError message={errors.description} />
            </div>
            <div class="flex flex-col gap-2 lg:col-span-2 lg:flex-row lg:items-center lg:gap-4">
                <Button type="submit" disabled={processing}>
                    {processing ? 'Saving…' : 'Add Skill'}
                </Button>
                <InputError message={errors.form} />
                {#if createdSkill && !processing && !errors.form}
                    <p class="text-sm font-medium text-green-700 dark:text-green-500" aria-live="polite">
                        "{createdSkill.title}" was added to SkillSwap.
                    </p>
                {/if}
            </div>
        {/snippet}
    </Form>
</section>
