# Node.js 22 and npm, with curl and git already installed
FROM node:22

# The php.new script needs a shell and a terminal type to run
ENV SHELL=/bin/bash
ENV TERM=xterm

# Install PHP 8.5 and Composer with php.new
RUN /bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"

# Add the php.new tools to the PATH, the same as the php.new shell profile line
# php.new installs herd-lite, which is a stripped down version of Laravel Herd
# It is designed to update and maintain the laravel, composer, and php cli
ENV PATH="/root/.config/herd-lite/bin:${PATH}"
ENV PHP_INI_SCAN_DIR="/root/.config/herd-lite/bin"

WORKDIR /app

# Copy the project
COPY . .

# Install the dependencies (README step 3)
RUN composer install --no-interaction \
    && npm install

# Set up the environment variables and the SQLite database (README steps 4 and 5)
RUN [ -f .env ] || cp .env.example .env \
    && php artisan key:generate --force \
    && touch database/database.sqlite \
    && php artisan migrate --force

# Let Laravel and Vite listen outside the container, on their default ports
ENV SERVER_HOST=0.0.0.0
ENV LARAVEL_SAIL=1

# Laravel on 8000, Vite on 5173
EXPOSE 8000 5173

# Start the development server, which runs Laravel, the queue, and Vite (README step 6)
CMD ["composer", "run", "dev"]
