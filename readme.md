# Streamly CMS

[![GitHub license](https://img.shields.io/badge/license-GPLv2%2B-blue.svg)]()


### Quickstart

 - Clone this repository
 - composer install
 - php artisan migrate
 - php artisan db:seed
 - You are ready to go

### Docker (Apache + PHP 7.4)

 - Copy env:
   - PowerShell: `Copy-Item .env.docker.example .env.docker`
   - Bash: `cp .env.docker.example .env.docker`
 - Build + start: `docker compose up --build`
 - App URL: `http://localhost:8080`
 - phpMyAdmin: `http://localhost:8081` (server: `db`, user: `root`, pass: `root`)
 - Run commands inside container:
   - `docker compose exec app composer install`
    - `docker compose exec app php artisan key:generate`
    - `docker compose exec app php artisan migrate --seed`

### Quick Guide

Once you host your project you can use any rest client like Postman and start making requests

POST    http://laravel-jwt-api.local/auth/login



#### Features

 1. Out of the box JWT
 2. Role based authentication
 3. Login, Registration, Forget Password, Email Verification Implemented.
 4. User and Role creation APIs ready.
 5. Pretty Routes (Available at /routes)

#### Versioning

This project uses uses [Semantic Versioning](http://semver.org/)

#### Changelog

See the [CHANGELOG](CHANGELOG.md) for the project history and recent updates.
