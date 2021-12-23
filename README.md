GoT characters and quotes command
===================

A service to send Character information fetched from two api's to a remote service.

## Technologies used

- [Docker](https://www.docker.com/)
- [PHP 8](https://www.php.net/releases/8.0/en.php)
- [Laravel 8.x](https://laravel.com/docs/8.x)

## Installation guide

- Clone the project
- Start projetc

  `make start`
- Run the command to generate characters and their quotes

  `docker-compose exec app php artisan characters:create`

#### An endpoint to consult created characters will be available at
http://localhost:7098/api/characters

<h4 align="center">
    Made with ♡ by <a href="https://www.linkedin.com/in/danilocolasso/" target="_blank">Danilo Colasso</a>
</h4>
