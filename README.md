# Projet_7_BileMo_API

## Introduction

This is my first Symfony API project of my apprenticeship training with OpenClassrooms.

## Information

BileMo is a company offering a variety of premium mobile phones. BileMo's business model is not to sell its products directly on the website, but to provide all platforms that want it with access to the catalog via an API. It is a B2B (business to business).

## Constraint

These are the main constraints for this project:

 - Only referenced customers can access to the APIs. API customers must be authenticated through OAuth or JWT.
 - The API must be RESTFull and follow the 3 first stages of Richardson Model.
 - The data must be in JSON.
 - The responses should be cached when possible to optimize the performance of the request to the API.

## Code quality

[![Maintainability](https://api.codeclimate.com/v1/badges/568ea778323ab12b5329/maintainability)](https://codeclimate.com/github/vincentsig/Projet_7_BileMo_API/maintainability)

## Development environment 

- PHP  7.3.19
- Symfony 5.1
- Apache 2.4.35
- MySQL 5.7.23

## Installation

**1. Download or clone the github repository:**  

      [BileMo Repository](https://github.com/vincentsig/Projet_7_BileMo_API)

**2. Install the back-end  dependencies**

      composer install

**4. Install Redis and launch the server:**
    
     [Install Redis](https://redis.io/download)

**5 Generate the SSH keys**

      $ mkdir -p config/jwt
      $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
      $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

**6. Setup your environment**

      Create an .env.local file and fill in the required environment variables if needed.

      ###> doctrine/doctrine-bundle ###
            DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

      ###> lexik/jwt-authentication-bundle ###
            JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
            JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
            JWT_PASSPHRASE='your_passphrase'
      ###< lexik/jwt-authentication-bundle ###

      ###> snc/redis-bundle ###
      # passwords that contain special characters (@, %, :, +) must be urlencoded
            REDIS_HOST='your_host'
            REDIS_PORT='your_port'
      ###< snc/redis-bundle ###


**7. Create the Database**

      php bin/console doctrine:database:create

**8. Update schema**
 
      php bin/console doctrine:schema:update --force

**9. Load the dataFixtures**

      php bin/console doctrine:fixtures:load

## Authentification

      curl -X POST -H "Content-Type: application/json" http://127.0.0.1:8000/api/login_check -d '{"username":"user.test@gmail.com","password":"12345"}'

## Documentation**

      The documentation is available on /api/doc



      
    
    
