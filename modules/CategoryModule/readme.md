# Category Module

## What?

The reporting module is used for implementing categories for your baseplate-core laravel application.

## Prerequisites

* baseplate-core (https://gitlab.com/Kolette/baseplate/backend/baseplate-core)

## Installation

1. php artisan lego:install Category
2. Register the module service provider in config/concord.php
    ```php
    'modules' => [
        \Kolette\Category\Providers\ModuleServiceProvider::class,
    ],
    ```

3. php artisan migrate

## Usage

## Support

If you have any concerns please feel free to share it with us! Contact us
@ [anthony.alaan@Kolette.com.au](mailto:anthony.alaan@Kolette.com.au)

## Roadmap

There are no planned additional features for this module yet.

## Authors and acknowledgment

Author: [Anthony Alaan](mailto:anthony.alaan@Kolette.com.au)

Contributors:

- [Wilmon Agulo](mailto:wilmon.agulo@Kolette.com.au)

## Licenses

Copyright 2023 Kolette Pty Ltd

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the
License. You may
obtain a copy of the License at

https://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "
AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language
governing permissions and
limitations under the License.

