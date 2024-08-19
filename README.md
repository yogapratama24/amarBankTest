# Documentation 
## Amar Bank Technical Test

## Prerequisites

1. PHP >= 7.3
2. PostgreSQL
3. Make sure your php installation are configured properly

## Set-up

1. Configure Project

    ```sh
    # Run scripts to install used package
    composer install
    ```
2. Copy env file
    ```sh
    # Run scripts to make env file
    cp .env.example .env
    ```

3. Database Migration
    ```sh
    # Run scripts to run database migration
    php vendor/bin/phinx migrate
    ```

### Run Development

```sh
# Run Service, after you run configure project
php -S localhost:8000 -t public/
```

## How To Use This Application ?
1. You can import postman collection that i have provide in documentation folder
2. In postman documentation already exists some of example for you test

## How To Use This Unit Test ?
### For Unit Test Has 3 Parts
1. **Controller Testing** You can run this command
   ```sh
    vendor/bin/phpunit --bootstrap vendor/autoload.php src/App/Controllers/LoanControllerTest.php 
    ```
2. **Service Testing** You can run this command
   ```sh
    vendor/bin/phpunit --bootstrap vendor/autoload.php src/App/Services/LoanServiceTest.php    
    ```
3. **Repository Testing** You can run this command
   ```sh
    vendor/bin/phpunit --bootstrap vendor/autoload.php src/App/Repositories/LoanRepositoryTest.php    
    ```

postman collection has been attached to the documentation folder