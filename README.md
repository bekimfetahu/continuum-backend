## Project task

Project consist of two parts, backend Laravel server and frontend which run as Vuejs SPA.

I have used Laravel Passport grant for authentication and authorisation and the Vue app uses
token to access authorized resources.

After installation, you will need to run 
php artisan serve and then open vue application 

In my case vue application is served from http://localhost:8080
and backend from http://localhost:8000

If you run on same ports than you do not need to do any configuration in the vue application

## Installation

``` bash
$ git clone https://github.com/bekimfetahu/continuum-backend.git

$ cd continuum-backend/

$ composer install

    copy env.example to .env

    edit .env to your database connection

    DB_DATABASE=continuum
    DB_USERNAME=root
    DB_PASSWORD=

$ php artisan key:generate

$ php artisan migrate

$ php artisan db:seed
    User: admin@admin.com pass:password is created
    First 10 Clients do not have any transactions, for delete test

$ php artisan passport:keys

$ php artisan passport:client --password

    Just enter default values

    What should we name the password grant client? [Laravel Password Grant Client]:
    >

    Which user provider should this client use to retrieve users? [users]:
    [0] users
    >

    Password grant client created successfully.
    Client ID: 1
    Client secret: ff73LfLSl0UW0tRipdv5yAuIr9iOwPmkURyiBBWg


    Add to .env your generated key

    PASSPORT_CLIENT_ID=1
    PASSPORT_SECRET="ff73LfLSl0UW0tRipdv5yAuIr9iOwPmkURyiBBWg"

$ php artisan serve
    Laravel development server started: http://127.0.0.1:8000
    
    Run phpunit tests
$ ./vendor/bin/phpunit
```


Note server IP and port as this is going to be used by Node Vue front end SPA
