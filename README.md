####  Install all the dependencies using composer

`composer install`

####  Copy the example env file and make the required configuration changes in the .env file

`cp .env.example .env`

####  Generate a new application key

`php artisan key:generate`

####  Generate a new JWT authentication secret key

`php artisan jwt:secret`

### Run command

git config core.fileMode false

