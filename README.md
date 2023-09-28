## About Duocmatico

Duocmatico is a web app to help students of Duoc UC to manage and prepare the classes they need to take in the next semester. It also helps them to know the classes they have already taken and the ones they need to take in order to graduate.

On this repo, we include all the code related to the backend of the app. The frontend code is on a separate repo.

## Installation

In order to run this API inside your local machine, you need to have installed the following software:

-   PHP 8.1
-   Composer
-   MySQL 8.0
-   Laravel 8.0 or latest

Once you have installed all the software, you need to clone this repo and run the following commands:

```bash
composer install
```

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

```bash
php artisan serve
```

> **Note**
> This repo uses Google Auth to authenticate users. In order to make it work, you need to create a Google Auth project and add those credentials as a `firebase_credentials.json` file and then, to their respective env variables. You can find more information about this on the [official documentation](https://firebase.google.com/docs/admin/setup#initialize-sdk).

## License

Duocmatico (API and Frontend) is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
