# Kolette Auth Module

## What?

This module provides authentication features to your baseplate-core application.

## Prerequisites

* PHP 8.1
* baseplate-core (https://gitlab.com/Kolette/baseplate/backend/baseplate-core)
* media-module (https://gitlab.com/Kolette/baseplate/backend/media-module)

## Installation

1. Run: php artisan lego:install AuthModule
2. Register the module service provider in config/concord.php
    ```php
    'modules' => [
        \Kolette\Auth\Providers\ModuleServiceProvider::class,
    ],
    ```
3. php artisan migrate
4. Seed the roles and permissions: `php artisan app:acl:sync`

## Configuration

Config files for the following are located in `src/resources/config`:

* Spatie Laravel Medialibrary
* Spatie Laravel Permission
* Laravel Sanctum

## Contribution

* Anthony Alaan (anthony.alaan@Kolette.com.au) - Author / Maintainer
* Wilmon Agulo (wilmon.agulo@Kolette.com.au)

If you'd like to report bugs or add improvements, feel free to create a new Issue or Merge Request and we'll start a
discussion :)
