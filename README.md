## Quote Quizz

Web application backend representing the famous quote quiz where the user will have to pick a correct answer to the asked questions.

## About this Project

This project represents backend of the specified web application and it's based on [Laravel 9](https://laravel.com/)

## Requirements

- PHP v8.1
- MySQL v.8.0
- [Composer](https://getcomposer.org/)

## Installation guide

First thing to do after pulling the project - run following command to install dependencies:

```sh
composer install
```

Next - rename .env.example file to .env and update several environment variables:

```
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```

### Migrations
Run migrations to migrate all the database tables

```sh
php artisan migrate
```

### Public Directory
After installing the project, you should configure web server's document / web root to be the ```public``` directory of the project.
Rr you can run following command: 

```sh
php artisan serve --port=8080 
```

## Admin user
In order to create admin user you have to run tinker command first to open **Psy Shell**:

```sh
php artisan tinker
```

After shell is open - run following commands one by one (Dont't forget to replace credentials):

```sh
$user = new App\Models\User();
$user->password = Hash::make('the-password-of-choice');
$user->email = 'email@example.com';
$user->name = 'User name';
$user->save();
exit
```

All done.