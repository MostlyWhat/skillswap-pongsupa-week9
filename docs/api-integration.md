# Connecting SkillSwap to the Backend API and My Process for Assignment 7

## Summary

For Assignment 7, I connected SkillSwap to the Skill Swap backend API, so the skills come from a real database instead
of the hardcoded list from last week. I also added a form so the user can add a new skill, and the skill is saved in the
database through the API. The assignment examples call the API from the browser with JavaScript `fetch`, but my stack
for SkillSwap is a Laravel application, so I made the calls from PHP in Laravel and the Svelte page gets the data from
Laravel. I also added a Dockerfile so the website can be run without installing PHP and Node.js on the computer.

## Calling the API from Laravel instead of the browser

There are 2 ways to get the data from the API, the browser can call the API directly or Laravel can call the API and
send the result to the page. I picked Laravel, since the page already gets the skills from the `HomeController` in the
last assignment, and the functions from Assignment 6 (`filterSkillsByCategory`, `calculateTotalCost`,
`matchSkillsToUser`) are in PHP. So if Laravel gets the skills, the functions can keep working with the same arrays, and
the browser does not need to know where the backend is.

The API calls are in `app/Services/ApiService.php` with two functions, `fetchSkills` that gets all the skills with
`GET /skills`, and `createSkill` that saves a new skill with `POST /skills`. Both use the Laravel `Http` client, which
is the PHP version of `fetch` or axios on JavaScript. If the backend is not running or sends back an error, the function
writes the error to the log and throws a `RuntimeException`. Then the controller can catch it and tell the user instead
of showing a crash page.

Reference: [Laravel HTTP Client Documentation](https://laravel.com/docs/http-client)

### The API URL

The URL of the backend is not in the code, it is in the `.env` file as `SKILL_API_URL` and `config/services.php` reads
it. The default is `http://localhost:3000/api`, which is where the backend runs when you start it locally. So it works
without changing anything, but you can change it in case the backend runs somewhere else.

### Loading state and error state

Since the skills now come from another server, the page can take some time to get them. I used `Inertia::defer()` in
the controller, which shows the page first and then loads the skills after. While the skills are loading, the page shows
a loading state, and if the API cannot be reached, the page shows an error message with a retry button instead of an
empty list.

Reference: [Inertia Deferred Props Documentation](https://inertiajs.com/deferred-props)

### Adding a skill with the form

The form is in `resources/js/components/AddSkillForm.svelte` and it uses the Inertia `<Form>` component like the cost
calculator from last week. The form sends the skill to Laravel, and a Form Request checks that the input is valid. The
fields are title, category, price, description, and the provider which is optional and defaults to `Anonymous`. Then the
controller sends it to the API with `createSkill`. After the API saves the skill, the page reloads the skills from the
database. So the new skill and the new category show up in the list and in the filter bar right away, and it stays after
a refresh.

### Combining the controllers

Last week each route had its own controller, `HomeController` and `MatchSkillsController`. This week I added a third one
for saving a skill, so I combined all the skill routes into one `SkillController` with `index`, `match`, and `store`,
since all three are about skills. The cost calculator stays in its own `CalculateCostController` since it does not use
the skill list.

### Docker

I added a `Dockerfile` so the website can be run inside a container. The image starts from Node.js 22, installs PHP and
Composer with [php.new](https://php.new/), then runs the same installation steps as the README. The `.dockerignore`
skips `node_modules` and `vendor`, because on my computer they are the Windows version and Docker runs on Linux, so it
needs its own copy. The steps to build and run it are in the README.

## My process completing the assignment

I copied my Week 8 application as a starting point. I planned to follow the test-driven development the same way as
Assignment 6, writing the failing tests first, then the code, then the UI. I did write and run the tests along the way,
but I forgot to commit the tests on their own, so they ended up all in one commit at the end with the cleanup. So the
commit history does not show the red, green, refactor steps like last time, and it is below.

1. `feat: copied repo from week 8 as a starting point`
2. `feat: add API service with fetchSkills and createSkill`
3. `feat: load skills from the Skill Swap API through Laravel and PHP`
4. `feat: add skill creation form and combine skill routes into SkillController`
5. `feat: add API service and skill form tests, remove unused frontend API code`

In the second commit I first made the API service in TypeScript for the browser, but after I switched to calling the API
from Laravel, nothing used it anymore, so I removed it in the last commit.

Here are the files that changed this week.

Services (The API calls and the skill data)

- The API calls: `app/Services/ApiService.php`
- The skill data and categories from the API: `app/Services/SkillCatalog.php`

Controllers (Call the API and send the result to the page)

- Home Page, Match Skills, and Add Skill: `app/Http/Controllers/SkillController.php`

Form Requests (Validate the form input before the controller uses it)

- Add Skill Validation: `app/Http/Requests/StoreSkillRequest.php`

Config

- API URL: `config/services.php` and `.env.example`

Routes

- Home, Match Skills, and Add Skill Routes: `routes/web.php`

UI (The page that shows the skills from the API)

- Home Page with the loading and error state: `resources/js/pages/Welcome.svelte`
- Add Skill Form: `resources/js/components/AddSkillForm.svelte`
- Skill Type to match the API: `resources/js/types/skill.ts`

Docker

- Docker Image: `Dockerfile`
- Files to skip in the image: `.dockerignore`
