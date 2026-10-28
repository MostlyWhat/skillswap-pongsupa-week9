# SkillSwap

SkillSwap is a platform for users to learn new skills cost-effectively through high-quality and personal learning
experience with a verified expert. But also for experts to get paid easily by offering expertise to users with
flexibility. Unlike other platforms that can feel unsafe and not offer the right skills at a high-price, SkillSwap
verifies the credibility of both users and tutors, offers flexibility to book and teach at your own pace, and quality
lessons verified by users-alike.

SkillSwap design and implementation for Rize's Software Development Course by Pongsupa "Poonpun" Supapa.

This is the Week 9 Release Version, this connects to the SkillSwap backend.

## Prerequisite

Please make sure to have the following packages installed:

- PHP 8.5+
- Composer
- Node.js and npm
- PostgreSQL, MySQL, or SQLite

Note: You can use [php.new](https://php.new/) to install everything above except Node.js and npm.

## Installation

1. Install the packages above.
2. Clone the repository and cd to cloned project directory.
3. Install dependencies:

    ```bash
   composer install
   npm install
   ```

4. Setup environmental variables:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database connection in `.env`, then run migrations:

   ```bash
   php artisan migrate
   ```

6. Start the development server:

   ```bash
   composer run dev
   ```

7. Running tests

   ```bash
   php artisan test
   ```

## Tech Stack

- Laravel - Backend Framework
- Svelte 5 - Frontend Framework
- TailwindCSS - Styling
- PostgreSQL - Database
