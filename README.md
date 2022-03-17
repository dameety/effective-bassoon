Squareblog is a web blogging platform.

## Installation

Install this by running cloning this repository and install like you normally install Laravel.

-   Run `composer install` and `npm install yarn`
-   Run `yarn` and `yarn run dev` to generate assets
-   Copy `.env.example` to `.env` and fill your values (`php artisan key:generate`)

```php
EXTERNAL_BLOG_POST_API='api json endpoint that returns blog posts'
TELESCOPE_ENABLED=true
REDIS_CLIENT=predis
SYSTEM_GENERATED_ADMIN_USER=
```

-   Run `php artisan migrate`, this will seed a user based on your `BASIC_AUTH` `.env` values
-   Run `php artisan db:seed`, this will seed a user(user@email.com) with posts.
-   Start your queue listener and setup the Laravel scheduler.
-   Open app in browser

### Login details for Demo users

```php
user@email.com / password
System generated admin user: admin@email.com (or email set in SYSTEM_GENERATED_ADMIN_USER) / password
```

## Testing

You can run the tests with:

```bash
vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
