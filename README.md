COCTELEANDO API REST

This is a project built in Laravel with a REST API architecture so that users can learn about mixology, and also share their knowledge with their peers. They will also be able to publish cocktails they have created so that others can make them anywhere in the world.

The technologies used are:

- PHP >= 8.1
- Laravel 10
- MySQL
- Laravel Passport (API authentication)
- PHPUnit (feature tests)

INSTALLATION:
To install it on your computer you should:

- Clone the repository:
git clone <repository-url>
cd <project-name>

- Install dependencies:
composer install

- Configure .env (copy .env.example):
cp .env.example .env

- Configure the database:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password

- Generate the application key:
php artisan key:generate

- Run migrations and seeders:
php artisan migrate --seed

- Configure Passport (API authentication):
php artisan passport:install

 USAGE:
To use it you should:

- Start the local server:
php artisan serve

- The API will be available at: http://127.0.0.1:8000/api

Authentication:

- Laravel Passport is used to authenticate users via personal tokens.

- Protected endpoints require the header:
Authorization: Bearer <token>

Testing:

- Feature tests are included with PHPUnit:
php artisan test

User functionalities:

- Register and login to obtain an authentication token.
- Create, read, update, and delete their own cocktails.*
- Create, read, update, and delete their own ingredients.*
- Search cocktails by name, ingredient, or favorites.
- Order cocktails by name, creation date, or prioritize favorites.
- Mark and unmark cocktails as favorites.
- View the ingredients of each cocktail with quantity and unit.

* Users can only update and/or delete cocktails they created and ingredients they created but are not used in cocktails created by other users.

Main endpoints:

Method       Route                Description
POST     /api/register         User registration
POST     /api/login            Login and obtain token
GET      /api/cocktails        List cocktails
POST     /api/cocktails        Create cocktail (auth)
PUT      /api/cocktails/{id}   Update cocktail (auth)
DELETE   /api/cocktails/{id}   Delete cocktail (auth)
GET      /api/cocktails/search Search cocktails by name, ingredient, or favorite (optional auth)
