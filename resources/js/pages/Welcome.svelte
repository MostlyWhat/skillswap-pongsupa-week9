<script lang="ts">
    import { Deferred, Form, Link, page, router } from '@inertiajs/svelte';
    import CalculateCostController from '@/actions/App/Http/Controllers/CalculateCostController';
    import MatchSkillsController from '@/actions/App/Http/Controllers/MatchSkillsController';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import {
        Select,
        SelectContent,
        SelectItem,
        SelectTrigger,
    } from '@/components/ui/select';
    import { Skeleton } from '@/components/ui/skeleton';
    import { toUrl } from '@/lib/utils';
    import { dashboard, login } from '@/routes';
    import { register } from '@/routes';
    import type { Skill, SkillCatalog } from '@/types';

    // The catalog is a deferred prop. It is undefined while Laravel loads the skills from the API.
    let {
        catalog,
    }: {
        catalog?: SkillCatalog;
    } = $props();

    const categories = $derived(catalog?.categories ?? ['All']);

    // Ask Laravel to load the skills from the API again, for example after an error
    function reloadSkills() {
        router.reload({ only: ['catalog'] });
    }

    const totalCost = $derived(page.flash.totalCost as number | undefined);

    let matchCategory = $state('All');

    // The last match result. It stays until the next match, so other forms do not clear it.
    let matchedSkills = $state<Skill[] | undefined>(undefined);

    $effect(() => {
        const flashedSkills = page.flash.matchedSkills as Skill[] | undefined;

        if (flashedSkills !== undefined) {
            matchedSkills = flashedSkills;
        }
    });

    // Show the match result after a match, and all the skills before the first match
    const shownSkills = $derived(matchedSkills ?? catalog?.skills ?? []);

    const auth = $derived(page.props.auth);
</script>

<AppHead title="Welcome"></AppHead>

<div
    class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
>
    <header
        class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
    >
        <nav class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="text-xl font-medium py-1.5 leading-normal text-[#1b1b18] dark:text-[#EDEDEC]">
                    Skill<span class="text-[#f53003] dark:text-[#FF4433]">Swap</span>
                </span>
                <br>
                <Link
                    href="/"
                    class="inline-block rounded-sm border border-transparent py-1.5 text-sm leading-normal text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    Home
                </Link>
                <Link
                    href="/learn"
                    class="inline-block rounded-sm border border-transparent py-1.5 text-sm leading-normal text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    Learn
                </Link>
                <Link
                    href="/share"
                    class="inline-block rounded-sm border border-transparent py-1.5 text-sm leading-normal text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    Share
                </Link>
                <Link
                    href="/earn"
                    class="inline-block rounded-sm border border-transparent py-1.5 text-sm leading-normal text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    Earn
                </Link>
                <Link
                    href="/about"
                    class="inline-block rounded-sm border border-transparent py-1.5 text-sm leading-normal text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    About
                </Link>
            </div>
            <div class="flex items-center gap-4">
                {#if auth.user}
                    <Link
                        href={toUrl(dashboard())}
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Dashboard
                    </Link>
                {:else}
                    <Link
                        href={toUrl(login())}
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        href={toUrl(register())}
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                {/if}
            </div>
        </nav>
    </header>
    <div class="lg:mt-auto w-full max-w-[335px] lg:max-w-4xl text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
        <h1 class="text-4xl font-bold">Learn. Share. Earn.</h1>
        <br>
        <h2 class="text-[#706f6c] dark:text-[#A1A09A]">SkillSwap is your go-to hub for cost-effective learning with talented peers. Get started today with our curated skills.</h2>
    </div>
    <div class="mb-6 w-full max-w-[335px] lg:max-w-4xl">
        <Form
            {...MatchSkillsController.form()}
            options={{ preserveScroll: true, preserveState: true }}
            class="flex flex-col gap-4 lg:flex-row lg:items-end"
        >
            {#snippet children({ errors, processing })}
                <div class="grid gap-2">
                    <Label for="match_category">Category</Label>
                    <Select type="single" name="category" bind:value={matchCategory}>
                        <SelectTrigger id="match_category" class="w-56">
                            {matchCategory}
                        </SelectTrigger>
                        <SelectContent>
                            {#each categories as category (category)}
                                <SelectItem value={category} label={category} />
                            {/each}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.category} />
                </div>
                <div class="grid gap-2">
                    <Label for="max_price">Max price per hour ($)</Label>
                    <Input
                        id="max_price"
                        name="max_price"
                        type="number"
                        min="0"
                        step="0.01"
                        placeholder="No limit"
                        class="w-40"
                    />
                    <InputError message={errors.max_price} />
                </div>
                <Button type="submit" disabled={processing}>Find My Match</Button>
            {/snippet}
        </Form>
    </div>
    <div
        class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 starting:opacity-0 lg:mb-auto"
    >
        <main
            class="grid w-full max-w-[335px] gap-4 rounded-lg lg:max-w-4xl lg:grid-cols-3"
            aria-busy={catalog === undefined}
        >
            <Deferred data="catalog">
                {#snippet fallback()}
                    <p class="text-[13px] text-[#706f6c] lg:col-span-3 dark:text-[#A1A09A]" aria-live="polite">Loading skills…</p>
                    {#each [1, 2, 3] as placeholder (placeholder)}
                        <Skeleton class="h-56 rounded-lg" />
                    {/each}
                {/snippet}

                {#if catalog?.error}
                    <Alert variant="destructive" class="lg:col-span-3">
                        <AlertTitle>Skills are not available</AlertTitle>
                        <AlertDescription>
                            <p>{catalog.error}</p>
                            <Button variant="outline" size="sm" class="mt-2" onclick={reloadSkills}>Try again</Button>
                        </AlertDescription>
                    </Alert>
                {:else if shownSkills.length === 0}
                    <p class="text-[13px] text-[#706f6c] lg:col-span-3 dark:text-[#A1A09A]" aria-live="polite">No skills match your needs.</p>
                {/if}
                {#each catalog?.error ? [] : shownSkills as skill (skill.id)}
                    <div
                        class="flex flex-col rounded-lg bg-white p-6 text-[13px] leading-5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                    >
                        <h1 class="mb-1 font-medium">{skill.title}</h1>
                        <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                            {skill.category} · {skill.price === 0 ? 'Free' : `$${skill.price}/hour`}
                        </p>
                        <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                            {skill.description}
                        </p>
                        <ul class="mb-4 flex flex-col lg:mb-6">
                            <li class="relative flex items-center gap-4 py-2">
                                <span class="relative bg-white py-1 dark:bg-[#161615]">
                                    <span
                                        class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]"
                                        ></span>
                                    </span>
                                </span>
                                <span>
                                    Skill by {skill.provider}
                                </span>
                            </li>
                        </ul>
                        <ul class="mt-auto flex gap-3 text-sm leading-normal">
                            <li>
                                <button
                                    onclick={() => alert(`You have booked a session with ${skill.provider}!`)}
                                    class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-[#f53003] hover:bg-[#f53003] dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-[#f53003] dark:hover:bg-[#f53003] transition-all duration-200"
                                >
                                    Book a Session with {skill.provider}
                                </button>
                            </li>
                        </ul>
                    </div>
                {/each}
            </Deferred>
        </main>
    </div>
    <section
        class="mt-6 w-full max-w-[335px] rounded-lg bg-white p-6 text-[13px] leading-5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:max-w-4xl dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
    >
        <h2 class="mb-1 font-medium">Cost Calculator</h2>
        <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">
            Enter an hourly rate and the number of hours to see the total cost of a session.
        </p>
        <Form
            {...CalculateCostController.form()}
            options={{ preserveScroll: true, preserveState: true }}
            class="flex flex-col gap-4 lg:flex-row lg:items-end"
        >
            {#snippet children({ errors, processing })}
                <div class="grid gap-2">
                    <Label for="hourly_rate">Hourly rate ($)</Label>
                    <Input
                        id="hourly_rate"
                        name="hourly_rate"
                        type="number"
                        min="0"
                        step="0.01"
                        required
                        placeholder="20"
                    />
                    <InputError message={errors.hourly_rate} />
                </div>
                <div class="grid gap-2">
                    <Label for="hours">Hours</Label>
                    <Input
                        id="hours"
                        name="hours"
                        type="number"
                        min="0"
                        step="0.5"
                        required
                        placeholder="2"
                    />
                    <InputError message={errors.hours} />
                </div>
                <Button type="submit" disabled={processing}>Calculate</Button>
            {/snippet}
        </Form>
        {#if totalCost !== undefined}
            <p class="mt-4 text-base font-medium" aria-live="polite">
                Total cost: {totalCost === 0 ? 'Free' : `$${totalCost.toFixed(2)}`}
            </p>
        {/if}
    </section>
    <footer class="pt-6 text-[#1b1b18] dark:text-[#EDEDEC] w-full max-w-[335px] lg:max-w-4xl">
        <p class="text-[#706f6c] dark:text-[#A1A09A]">SkillSwap is a platform for users to learn new skills cost-effectively through high-quality and personal learning experience with a verified expert.</p>
        <br>
        <p>&copy; Copyright 2026 SkillSwap Inc. All rights reserved.</p>
    </footer>
</div>
