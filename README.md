Squareblog is a web blogging platform.

## Installation

Install this by running cloning this repository and install like you normally install Laravel.

- Run `composer install` and `npm install yarn`
- Run `yarn` and `yarn run dev` to generate assets
- Copy `.env.example` to `.env` and fill your values (`php artisan key:generate`, TELESCOPE_ENABLED=true, REDIS_CLIENT=predis)
- Run `php artisan migrate`, this will seed a user based on your `BASIC_AUTH` `.env` values
- Run `php artisan db:seed`, this will seed a user(user@email.com) with posts.
- Start your queue listener and setup the Laravel scheduler.
- Open app in browser

### Login details for Demo users

```php
user@email.com / password
System generated admin user for importing posts: admin@email.com / password
```


## Testing

You can run the tests with:

```bash
vendor/bin/phpunit
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.