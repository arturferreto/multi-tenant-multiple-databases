# mtmd
Implementing multi-tenancy in Laravel applications using multiple databases.

## install

init sail
```bash
  docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

create database
```postgresql
  CREATE DATABASE landlord;
```

```bash
  cp .env.example .env;
  sail artisan landlord:migrate --fresh --seed;
  sail artisan tenant:migrate --fresh --seed;
  sail artisan key:generate;
  sail npm install;
  sail npm run dev;
```
