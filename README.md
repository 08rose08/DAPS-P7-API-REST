<a href="https://codeclimate.com/github/08rose08/DAPS-P7-API-REST/maintainability"><img src="https://api.codeclimate.com/v1/badges/aed79fcfa1c2f43bdcdb/maintainability" /></a>

<a href="https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony">PHP/Symfony developer with Openclassrooms</a><br>

# DAPS-P7-API-REST

## Context
BileMo is a company offering a selection of high-end mobile phones.
You are in charge of the development of the mobile phone showcase of the BileMo company. BileMo's business model is not to sell its products directly on the website, but to provide all platforms that want it with access to the catalog via an API (Application Programming Interface). It is therefore a matter of sales exclusively in B2B (business to business).
You will need to expose a number of APIs for applications on other web platforms to perform operations.
You will need to expose a number of APIs so that the applications of other web platforms can perform operations.
## Skills assessed
* Start authentication for each HTTP request
* Exposing a REST API with Symfony
* Monitor the quality of a project
* Produce technical documentation
* Analyze and optimize the performance of an application
* Design an efficient and adapted architecture 

## Let's go
* Configure the .env : `database and JWT_PASSPHRASE`
* Create your database : `php bin/console doctrine:database:create`
* Make the migrations : `php bin/console doctrine:migrations:migrate`
* Run `composer install`
* Load the fixtures if you want : `php bin/console doctrine:fixtures:load`
* Generate your keys with openssl : 
```
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
