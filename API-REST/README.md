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
git clone https://github.com/Cristhian-Valenci/API-REST.git
cd api-rest

- Install dependencies:
composer install

- Configure .env (copy .env.example):
cp .env.example .env

- Configure the database:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api-rest
DB_USERNAME=root
DB_PASSWORD=

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
(if the test dont work, do: mkdir -p tests/unit)


User functionalities:

- Admin has authorization to do everything on the website.
- A registered user can create, list, search and sort cocktails. They can only edit or delete cocktails they created themselves, and they can create and list ingredients but can only edit or delete ingredients they created themselves and that are not used in cocktails created by other users.
- An unregistered user can only view the cocktails and ingredients on the website.


Cocktail Search Endpoint:

- Route: GET /api/cocktails/search

Query parameters:
- q → string to search by cocktail name or ingredient
- favorites → true to filter only user's favorite cocktails (requires auth)
- order → order results by:
   - name → alphabetically by cocktail name
   - created_at → by creation date (newest first)
   - favorites → prioritize the user’s favorite cocktails

How it works:
- If q is provided, the API searches for cocktails whose name contains q or that include ingredients containing q.
- If favorites=true and the user is authenticated, only favorite cocktails are returned.
- The order parameter can be combined with q and favorites to sort results accordingly.

Examples:
- Search by cocktail name:
GET /api/cocktails/search?q=margarita

- Search by ingredient:
GET /api/cocktails/search?q=rum

- Show only favorites (requires authentication):
GET /api/cocktails/search?favorites=true

- Search by ingredient and order alphabetically:
GET /api/cocktails/search?q=gin&order=name