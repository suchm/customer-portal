# Customer Portal

The customer portal is built with Laravel, Vue.js and Inertia. It allows customers to view and upgrade their subscriptions, send messages and update their profile details.

## Installation

Pre-requisites:

- Setup a MySQL database locally.
- Development environment setup e.g Herd or Docker.
- Composer needs to be installed.

Clone the git files from the customer-portal repo to a local repository.

cd into the repository then run the following commands:

Install dependencies:

```bash
composer install
```
Copy `.env.example` and create a `.env` file.

Generate an encryption key

```bash
php artisan key:generate
```
Add your postgreSQL database configuration to the `.env` file. Update the below details to match your configuration:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=customer_portal
DB_USERNAME=root
DB_PASSWORD=root
```
Run migrations:

```bash
php artisan migrate
```
Add Categories:

```bash
php artisan db:seed
```
Execute package scripts for development:

```bash
npm run dev
```
Once your local environment is up and running the site should now load

When your site is ready for production run the following command:

```bash
npm run build
```
## Usage

To navigate to the customer portal you need to login.

Once logged in you will be redirected to the `my-account` page which will display a list of options for you to choose.

The orders and help options show, but don't yet have the functionality added.

## Testing

Run the tests

```bash
php artisan test
```





