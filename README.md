# SkillSwap

SkillSwap is a platform for users to learn new skills cost-effectively through high-quality and personal learning
experience with a verified expert. But also for experts to get paid easily by offering expertise to users with
flexibility. Unlike other platforms that can feel unsafe and not offer the right skills at a high-price, SkillSwap
verifies the credibility of both users and tutors, offers flexibility to book and teach at your own pace, and quality
lessons verified by users-alike.

SkillSwap design and implementation for Rize's Software Development Course by Pongsupa "Poonpun" Supapa.

This is the Week 9 Release Version, this version includes the changes below.

- Connection to the SkillSwap backend from Laravel Backend
- Ability to add skills to the backend
- Frontend can update the filters and list of skills as new ones are added

## Docker Installation for Production Preview

Please make sure that [Docker](https://docs.docker.com/get-docker/) is installed for this method. The image installs PHP
and Composer via [php.new](https://php.new/), and Node.js and npm, then runs the same steps as the installation below.

1. Clone the repository and cd to cloned project directory.
2. Build the Docker container:

   ```bash
   docker build -t 'skillswap-week9' .
   ```

3. Start the development server and open the ports, Laravel runs on port 8000 and Vite on port 5173, but only 8000 is
   needed to see the changes:

   ```bash
   docker run --rm -p 8000:8000 -p 5173:5173 'skillswap-week9'
   ```

4. Open [http://localhost:8000](http://localhost:8000) in your browser to view and test the website.

5. Running tests by sending a remote testing command to the skillswap container.

   ```bash
   docker run --rm 'skillswap-week9' php artisan test
   ```

6. Any other php commands must also be run the same way inside the container.

   ```bash
   docker run --rm 'skillswap-week9' INSERT_COMMAND_HERE
   ```

## Prerequisite

Please make sure to have the following packages installed:

- PHP 8.5+
- Composer
- Node.js and npm
- PostgreSQL, MySQL, or SQLite

Note: You can use [php.new](https://php.new/) to install everything above except Node.js and npm.

## Installation for Local Development

1. Install the packages above.
2. Clone the repository, cd to cloned project directory, and run the commands below.
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

5. Configure your database connection in `.env`, then run migrations, currently defaults to sqlite:

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
