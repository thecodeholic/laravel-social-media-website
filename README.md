# Laravel Social Media Website
Laravel Social Media Website built with Inertia Vue.js. The project was created during the following 48 hours YouTube Playlist [YouTube Tutorial](https://www.youtube.com/watch?v=4iiEyOKhvao&list=PLLQuc_7jk__Wa8IoZ2s0J-ql_MIisndtZ).

## Demo
The application is deployed on the following domain [laravel-space.com](https://laravel-space.com/)

## Installation with docker

#### 1. Clone the project
```bash
git clone https://github.com/thecodeholic/laravel-social-media-website.git
```

#### 2. Run `composer install`
Navigate into project folder using terminal and run

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

#### 3. Copy `.env.example` into `.env`

```bash
cp .env.example .env
```

#### 4. Start the project in detached mode

```bash
./vendor/bin/sail up -d
```
From now on whenever you want to run artisan command you should do this from the container. <br>
Access to the docker container
```bash
./vendor/bin/sail bash
```

#### 5. Set encryption key

```bash
php artisan key:generate --ansi
```

#### 6. Run migrations

```bash
php artisan migrate
```

