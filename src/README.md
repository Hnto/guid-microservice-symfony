# GUID generator

### Requirements
- PHP 7.1.3 (or higher)
- MySQL

#
## Steps to run application 
- edit ".env" file to include the database connection
- run `composer install`
- run `bin/console doctrine:migrations:migrate`
- run `bin/console server:start` in the public folder to run the application

## setup database
- run `bin/console doctrine:migrations:migrate`

## How to create entities
- run `php bin/console make:entity` and follow the steps

## How to generate migrations from entities
- run `php bin/console make:migration`

## How to create a new endpoint
- add the route path in the `config/routes.yaml` file
  - every request that must also be authenticated and authorized must be added to the `config/packages/security.yaml` file

** API documentation can be found when starting the server and going to `http(s)://HOSTNAME/docs/index.html`.

#
### Make API requests
The following API endpoints are available as of now (26/03/2018)
- /guids (GET) shows all non-assigned guids
- /guids (PUT) creates a new guid
- /guids (POST) assigns a guid
- /authenticate (GET) authenticate with api key

### Documentation generator
- install homebrew on mac
  - If you are another platform go to [https://github.com/bukalapak/snowboard](https://github.com/bukalapak/snowboard) for installation instructions
- brew tap bukalapak/packages
- brew install snowboard
- snowboard html -o index.html blueprint/guid.apib **(do this in the folder "public/docs/)**
