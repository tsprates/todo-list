# ToDo List

## How to use

### API

Run the command to start the server.

```sh
php artisan serve
```

If you are running the application for the first time, you will also need to install dependencies and migrate the database by following these steps:

* Run the command to install the dependencies.

```sh
composer install
```

* Run the command to create the necessary tables in the database to seed them.

```sh
php artisan migrate:fresh --seed
```

### UI

Run the following command to start the UI:

```sh
npm run dev
```
