## Laravel Test

### Installation


#### Setup project
```bash
$ git clone https://github.com/pcsaini/revinfotech-laravel-test.git
$ cd revinfotech-laravel-test
$ composer install
```

- Copy .env.example file and save as .env
- Enter Database name and credentials.

```bash
$ php artisan key:generate
$ php artisan config:cache
$ php artisan migrate
$ php artisan db:seed
```

- Run below command for the showing images
```bash
$ php artisan stroage:link
```

- Run Below command to run project
```bash
$ php artisan serve
```

- Laravel development server started: http://127.0.0.1:8000
